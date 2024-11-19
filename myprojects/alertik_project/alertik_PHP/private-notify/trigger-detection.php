<?php
chdir(dirname(__FILE__));
include 'notify-backend.php';
include 'lib-user.php';
include 'lib-sms.php';
include 'lib-credit.php';
include 'conf-packages.php';
$latest_exchanges=getJSON('latest_exchanges');
$last_ime=getJSON('last_ime');

db_query('begin');

/*		Detect triggers		*/
$triggers_price=detect_price();
$triggers_time=detect_time();

/*		No trigger detected terminate		*/
if (!$triggers_time && !$triggers_price) exit;

$triggers=$triggers_time.(($triggers_time&&$triggers_price)?',':'').$triggers_price;
echo ''; // Hack! apache goes down without echo!!

/*		Remove detected triggers		*/
if ($triggers_price) db_query('DELETE FROM notify_detect_change WHERE trigger_id IN('.$triggers_price.')');
if ($triggers_time) db_query('DELETE FROM notify_detect_time WHERE trigger_id IN('.$triggers_time.')');

/*		Create new detected triggers and sms		*/
$sms=create_sms_trigger($triggers);

/*		Send SMS		*/
push_sms($sms);

function create_sms_trigger($triggers){
	global $packages_map;
	$rs=db_query('SELECT notify_triggers.uid,line_status,trigger_type,rate_type,source_type,last_value,details,rate_type,currency_id,trigger_id,phone_no FROM notify_triggers LEFT JOIN notify_users ON(notify_users.uid = notify_triggers.uid) WHERE trigger_id IN('.$triggers.')');
	while ($row=db_array($rs)){
		if ($row['uid']){
			if (!$credit=check_credit($row['uid'])) {
				db_query('UPDATE notify_triggers SET active = "N" WHERE uid = '.$row['uid']);
				db_query('DELETE FROM notify_detect_change WHERE trigger_id IN (SELECT trigger_id FROM notify_triggers WHERE uid = '.$row['uid'].')');
				db_query('DELETE FROM notify_detect_time WHERE trigger_id IN (SELECT trigger_id FROM notify_triggers WHERE uid = '.$row['uid'].')');
				continue; //Insufficient fund
			}
		} else $credit=false;
		list($new_value,$content,$preserve_request)=prepare_sms_trigger($row,$credit,(($row['uid'])?false:true));
		
		/*		Package based trigger		*/
		if (!$row['uid']){
			$package_triggered[$packages_map[$row['trigger_id']]]=array('content'=>$content,'request'=>$preserve_request);
			continue;
		}
		/*		Trigger should run for once		*/
		if ($row['trigger_type']==1){
			/* 		Update last value and last time	and inactivate trigger 	*/
			db_query('UPDATE notify_triggers SET last_value = '.$new_value.',last_time=NOW(),active="N" WHERE trigger_id = '.$row['trigger_id']);
		} else {
			/* 		Update last value and last time		*/
			db_query('UPDATE notify_triggers SET last_value = '.$new_value.',last_time=NOW() WHERE trigger_id = '.$row['trigger_id']);
			/*		New trigger update 	*/
			detection_create($row['uid'],$row['trigger_id'],$new_value);
		}					
		
		/*		SMS	Data		*/
		$sms[]=array(
			'content'=>$content,
			'phone_no'=>$row['phone_no'],
			'uid'=>$row['uid'],
			'trigger_type'=>$row['trigger_type'],
			'trigger_id'=>$row['trigger_id'],
			'line_status'=>$row['line_status'],
			'current_status'=>11,	// SMS Status: sending
			'sms_cost_type'=>0, // Normal SMS cost
			'retry'=>100,	// 100 Retries
			'time'=>60,	// Stop after 100min retry
			'request'=>$preserve_request,			
		);
		
	}
	if (is_array($package_triggered)) return collect_package_sms($sms,$package_triggered);
	return $sms;
}
function collect_package_sms($sms,$package_triggered){
	global $packages_map;
	/*	 Get uid and send SMS and Update triggers belong to the package	*/
	foreach ($package_triggered as $key => $content){
		$trigger_ids=array_keys($packages_map, $key);
		if (is_array($trigger_ids)) foreach ($trigger_ids as $trigger_id){
			detection_create(0,$trigger_id);
		}
		$pack_ids.=(($pack_ids)?',':'').$key;
	}
	$rs=db_query('SELECT notify_packages.uid,pack_id,phone_no,line_status FROM notify_packages 
		INNER JOIN notify_users ON (notify_users.uid = notify_packages.uid)
		WHERE pack_id IN('.$pack_ids.')');
	while ($row=db_array($rs)){
		$sms[]=array(
			'content'=>package_sms_uniquefy($package_triggered[$row['pack_id']]['content']),
			'phone_no'=>$row['phone_no'],
			'uid'=>$row['uid'],
			'trigger_type'=>100,
			'trigger_id'=>$row['pack_id'],
			'line_status'=>$row['line_status'],
			'sms_cost_type'=>100,
			'current_status'=>12,	// SMS Status: sending
			'retry'=>100,	// 100 Retries
			'time'=>60,	// Stop after 60min retry
			'request'=>$package_triggered[$row['pack_id']]['request'],			
		);
	}	
	return $sms;
}
function package_sms_uniquefy($content){
	$time=substr($content, -2);
	return sms_uniquefy($content,$time);
}
function prepare_sms_trigger($i,$credit,$without_uniquefier){
	if ($i['currency_id']) $currencies[]=parse_currency(array($i['source_type'],$i['rate_type'],$i['currency_id']));
	if ($i['details']) $details=json_decode($i['details'],true);
	if (is_array($details['currency_addition'])) foreach ($details['currency_addition'] as $l){
		$currencies[]=parse_currency($l);
	}
	return prepare_sms($currencies,$credit,$without_uniquefier);
}
function detect_price(){
	global $notify_rates;
	foreach ($notify_rates as $key=>$rate){
		$target=explode(':',$key);
		if (!$target[2]) continue;
		$price=get_price($target[0],$target[1],$target[2],'sell-raw');
		$where.=(($where)?' OR ':'').'((price_min>='.$price.' OR (price_max <= '.$price.' AND price_max > 0)) AND (last_value != '.$price.') AND source_type = '.$target[0].' AND rate_type = '.$target[1].' AND currency_id = '.$target[2].')';
	}
	$rs=db_query('SELECT trigger_id FROM notify_detect_change WHERE time_start<=HOUR(NOW()) AND time_end>=HOUR(NOW()) AND change_delay<=NOW() AND ('.$where.') LIMIT 20;');
	while($row=db_array($rs)){
		$triggers.=(($triggers)?',':'').$row['trigger_id'];
	}
	return $triggers;
}
function detect_time(){
	global $notify_rates;
	foreach ($notify_rates as $key=>$rate){
		$target=explode(':',$key);
		if (!$target[2]) continue;
		$timestamp=get_price($target[0],$target[1],$target[2],'timestamp');
		$price=get_price($target[0],$target[1],$target[2],'sell-raw');
		$where.=(($where)?' OR ':'').'(UNIX_TIMESTAMP(time_trigger) < '.$timestamp.' AND last_value != '.$price
			.' AND source_type = '.$target[0].' AND rate_type = '.$target[1].' AND currency_id = '.$target[2].')';		
		$where.=(($where)?' OR ':'').'(time_trigger < NOW() AND last_value != '.$price
			.' AND next = 0 AND source_type = '.$target[0].' AND rate_type = '.$target[1].' AND currency_id = '.$target[2].')';		
	}
	$rs=db_query('SELECT trigger_id FROM notify_detect_time WHERE time_start<=HOUR(NOW()) AND time_end>=HOUR(NOW()) AND ('.$where.') LIMIT 20;');
	while($row=db_array($rs)){
		$triggers.=(($triggers)?',':'').$row['trigger_id'];
	}
	return $triggers;
}