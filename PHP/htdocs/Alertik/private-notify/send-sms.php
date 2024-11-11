<?php
chdir(dirname(__FILE__));
include 'notify-backend.php';
include 'lib-user.php';
include 'lib-credit.php';
include 'lib-sms.php';
$send_sms=getCFG('send_sms');

/*		Detect and terminate previously launched send-sms		*/
if ($send_sms && $send_sms+170>time()) {
	echo "-";
	exit; 
}

/*		Prevent duplicate run		*/
setCFG('send_sms',time()); 

$missing_sms=find_sms_track_id();

prepare_remaing_sms($missing_sms);

invalidate_old_sms();

send_remaining_sms();


setCFG('send_sms',0);

function prepare_remaing_sms($remaining_sms){
	if (is_array($remaining_sms)){
		foreach ($remaining_sms as $sms_id => $t){
			$keys.=(($keys)?',':'').$sms_id;
		}
		if ($keys){
			db_query('UPDATE notify_sms SET current_status = 0,lastupdate=NOW() WHERE sms_id IN('.$keys.');');		
		}
	}
	/*		
			Return 'in sending' SMS to sending pool after 130 seconds
			Return 'waiting for confirmation' SMS to sending pool after 240 seconds
	*/
	db_query('UPDATE notify_sms SET current_status = 0,lastupdate=NOW() WHERE
		(track_id = 0) AND
		(((current_status BETWEEN 10 AND 19) AND TIMESTAMPDIFF(SECOND,lastupdate,NOW()) >= 130) OR
		((current_status BETWEEN 20 AND 39) AND TIMESTAMPDIFF(SECOND,lastupdate,NOW()) >= 240))');
}

function invalidate_old_sms(){
	/*		Timeout detector - Check SMS after 7min		*/
	$rs=db_query('SELECT sms_id,send_retry, recreate, TIMESTAMPDIFF(MINUTE,addedon,NOW()) as time_passed FROM notify_sms WHERE  current_status < 40 AND TIMESTAMPDIFF(MINUTE,addedon,NOW()) >= 7');
	while ($row=db_array($rs)){
		$recreate=json_decode($row['recreate'],true);
		if ($recreate['t']>$row['time_passed']&&$recreate['r']>$row['send_retry']) continue;
		$keys.=(($keys)?',':'').$row['sms_id'];		
	}
	if ($keys) {
		db_query('UPDATE notify_sms SET current_status = 50 WHERE sms_id IN ('.$keys.')');		
	}	
}
function collect_credit_blocked_lines(){
	$rs=db_query('SELECT notify_sms.uid FROM notify_sms INNER JOIN notify_users ON (notify_users.uid = notify_sms.uid) 
		WHERE track_id = 0 AND notify_sms.uid>0 AND sms_cost_type=0 AND send_by = 2 AND TIMESTAMPDIFF(SECOND,notify_sms.lastupdate,NOW()) > 60 AND TIMESTAMPDIFF(MINUTE,notify_sms.addedon,NOW()) < 20');
	while ($row=db_array($rs)){
		//credit_use($row['uid'],-1);
	}
	db_query('UPDATE notify_sms SET track_id = 1 WHERE send_by = 2 AND track_id = 0');
}
function send_remaining_sms(){
	/*		Collect SMS to send		*/
	$rs=db_query('SELECT notify_sms.content,notify_sms.sms_id,notify_users.phone_no as user_phone_no,notify_sms_incoming.phone_no as free_phone_no,notify_users.uid,send_by,line_status,recreate FROM notify_sms 
		LEFT JOIN notify_sms_incoming ON (notify_sms_incoming.sms_id = notify_sms.trigger_id) 
		LEFT JOIN notify_users ON (notify_users.uid = notify_sms.uid) 
		WHERE track_id = 0 AND current_status = 0 ORDER BY sms_id LIMIT 60;');
	while ($row=db_array($rs)){
		$row['recreate']=json_decode($row['recreate'],true);
		$row['send_by']=line_choose($row);
		$row['phone_no']=(($row['user_phone_no'])?$row['user_phone_no']:$row['free_phone_no']);
		if (is_array($row['recreate']['c'])){
			$tmp=$row['recreate']['c'];
			unset($recreate_currencies);
			foreach ($tmp as $currency){
				$recreate_currencies[]=array('source_type'=>$currency[0],'rate_type'=>$currency[1],'currency_id'=>$currency[2]);
			}
			list(,$row['content'])=prepare_sms($recreate_currencies);
			db_query('UPDATE notify_sms SET current_status = 14,send_retry=send_retry+1,content="'.$row['content'].'" WHERE sms_id = '.$row['sms_id']);		
		} else {
			$keys.=(($keys)?',':'').$row['sms_id'];		
		}
		$data[]=array('sms_id'=>$row['sms_id'],'send_by'=>$row['send_by'],'content'=>$row['content'],'phone_no'=>$row['phone_no'],'uid'=>$row['uid']);
	}
	if ($keys) {
		db_query('UPDATE notify_sms SET current_status = 14,send_retry=send_retry+1 WHERE sms_id IN ('.$keys.')');		
	}
	
	/*		Gateway SMS Send and update SMS Status	*/
	if (is_array($data)) {
		send_sms($data);
		//record_problem('send-sms',print_r($data,true).print_r(send_sms($data),true));	
	} 
}

function find_sms_track_id(){
	if (!is_array($tmp=get_missing_track())) return false;
	list($missing_track_id,$used_track_id,$number_to_get,$sms_details,$missing_track_id_all)=$tmp;
	$ch = curl_init();
	list($url,$content,$header)=sms_gateway_get($number_to_get+30);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$content);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	curl_setopt($ch, CURLOPT_TIMEOUT,15);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	$result= curl_exec ($ch);
	curl_close ($ch);

	$messages=explode('<MessagesBL>',$result);
	if (count($messages)<2) return false;
	db_query('begin');
	//print_r($messages);
	foreach ($messages as $msg){
		$tmp=sms_gateway_get_parse($msg);
		if (!$tmp['track_id']) continue;
		if ($used_track_id[$tmp['track_id']]) continue; // Skip previously used track_id
		if ($sms_id=array_search(md5(trim($tmp['content'])), $missing_track_id_all)){
			unset($missing_track_id[$sms_id]);
			unset($missing_track_id_all[$sms_id]);
			$sms_detail=$sms_details[$sms_id];			
			if (!$tmp['status']) {
				/* 		If SMS blocked by operator and less than 120 seconds passed skip everthing and move to the next message	*/
				if ($sms_detail['update_time_passed']<120) continue;
				
				/*		Message is blocked!		*/
				db_query('UPDATE notify_sms SET track_id = '.$tmp['track_id'].', current_status = 60, lastupdate=NOW() WHERE sms_id = '.$sms_id);

				/*		If the orginal one blocked less than 3 times or 9 retry 		*/
				if ($sms_detail['send_retry']<15) {
					sms_insert(array('uid'=>$sms_detail['uid'],'trigger_type'=>$sms_detail['trigger_type'],'trigger_id'=>$sms_detail['trigger_id'],
						'content'=>$sms_detail['content'],'sms_cost_type'=>10,'send_retry'=>($sms_detail['send_retry']+2),
						'addedon'=>$sms_detail['addedon'],'send_by'=>$sms_detail['send_by'],'recreate'=>json_decode($sms_detail['recreate'],true)));
				} 

			} else {
				/*		Message successfully reached the phone_no		*/
				db_query('UPDATE notify_sms SET track_id = '.$tmp['track_id'].', current_status = 40, lastupdate=NOW() WHERE sms_id = '.$sms_id);
			}
		} 
	}
	db_query('commit');
	return $missing_track_id;
}
function get_missing_track(){
	$rs=db_query('SELECT *,TIMESTAMPDIFF(SECOND,addedon,NOW()) as add_time_passed,TIMESTAMPDIFF(SECOND,lastupdate,NOW()) as update_time_passed 
		FROM notify_sms WHERE send_by=1 AND addedon>DATE_SUB(NOW(),INTERVAL 35 MINUTE)');
		//AND TIMESTAMPDIFF(SECOND,notify_sms.lastupdate,NOW()) > 30
	while ($row=db_array($rs)){
		$count++;
		if ($row['track_id']>0) {
			$used_track_id[$row['track_id']]=true;
		} elseif($row['current_status']>19&&$row['current_status']<40&&$row['update_time_passed']>25) {
			/*		In wait for confirmation and more than 25 second				*/
			/*		SMS with missing track_id which we are going to send			*/
			$missing_track_id[$row['sms_id']]=md5(trim($row['content'])); 
			/*		All of SMS with missing track_id which we are going to send		*/
			$missing_track_id_all[$row['sms_id']]=md5(trim($row['content']));
		} else {
			$missing_track_id_all[$row['sms_id']]=md5(trim($row['content'])); 
		}
		$sms_details[$row['sms_id']]=$row;
	}
	if (is_array($missing_track_id)) return array($missing_track_id,$used_track_id,$count,$sms_details,$missing_track_id_all);
}