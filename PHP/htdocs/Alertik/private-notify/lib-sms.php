<?php
include 'gateway-'.$gateway.'.php';
function push_sms($i){
	if (!is_array($i)) return;
	foreach ($i as $sms){	
		
		/*		Choose suitable line		*/
		$sms['send_by']=line_choose($sms);
		
		/*		Insert SMS		*/
		$sms_id=sms_insert(array(
			'uid'=>$sms['uid'],
			'send_by'=>$sms['send_by'],
			'trigger_type'=>$sms['trigger_type'],
			'trigger_id'=>$sms['trigger_id'],
			'content'=>$sms['content'],
			'sms_cost_type'=>$sms['sms_cost_type'],
			'send_retry'=>$sms['send_retry'],
			'addedon'=>$sms['addedon'],
			'current_status'=>$sms['current_status'],
			'recreate'=>array('c'=>$sms['request'],'r'=>$sms['retry'],'t'=>$sms['time']),
		));
		$to_send[]=array('sms_id'=>$sms_id,'send_by'=>$sms['send_by'],'content'=>$sms['content'],'phone_no'=>$sms['phone_no'],'uid'=>$sms['uid']);
		
		/*		Decrease credit		*/
		if (!$sms['sms_cost_type']) credit_use($sms['uid']);
		
	}
	db_query('commit');
	return send_sms($to_send);
}
function sms_insert($i){
	//$uid,$trigger_type,$trigger_id,$content,$sms_cost_type=0,$send_by=1,$send_retry=0,$addedon=null
	if (!$i['trigger_id']) $i['trigger_id']=0;
	if (!$i['sms_cost_type']) $i['sms_cost_type']=0;
	if (!$i['send_by']) $i['send_by']=1;
	if (!$i['send_retry']) $i['send_retry']=0;
	if (!$i['current_status']) $i['current_status']=0;
	if (!$i['recreate']) $i['recreate']=array();
	db_query('INSERT notify_sms (uid,trigger_type, trigger_id, track_id, current_status, content, sms_cost_type, send_by, send_retry, addedon, lastupdate,recreate) VALUES
		('.$i['uid'].','.$i['trigger_type'].','.$i['trigger_id'].',0,'.$i['current_status'].',"'.$i['content'].'",'.$i['sms_cost_type'].','.$i['send_by'].','.$i['send_retry'].','.(($i['addedon'])?'"'.$i['addedon'].'"':'NOW()').',NOW(),"'.db_safe(json_encode($i['recreate'])).'")');
	return mysql_insert_id();
}

function sms_status($sms_id,$status,$track_id=0){
	db_query('UPDATE notify_sms SET current_status='.$status.(($track_id)?',track_id='.$track_id:'').',lastupdate=NOW() WHERE sms_id='.$sms_id);
}

function send_sms_status_update($url,$status){
	/*		Just to check successful SMS		*/
	$info=get_sms_id($url);
	/*		Update Credit		*/
	//if ($info['uid']&&!$info['free']) credit_use($info['uid'],-1);
	
	/*		Status Change		*/
	sms_status($info['sms_id'],$status);
}
/*		Just to check successful SMS		*/
function get_sms_id($str) {
	global $send_map;
	return $send_map[$str];
}

function send_sms_url_get($urls,$i) {
	global $send_map;
	if (!$urls[$i]['content']) return;
	list($url,$content,$header,$timeout)=sms_gateway($urls[$i]);
	/*		Just to check successful SMS		*/
	$send_map[$url]=$urls[$i];
	return array($url,$content,$header,$timeout);
}
function finalize_sms_content($out,$credit){
	global $notice_balance;
	if ($credit<=$notice_balance) return $out."\nاعتبار رو به پایان"; else return $out;
}

function send_sms($urls) {
    $rolling_window = (sizeof($urls) < 3) ? sizeof($urls) : 3; // Maximum number of open connection is 5
    $master = curl_multi_init();
    /*$std_options = array(
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		//CURLOPT_POST => true ,
		CURLOPT_TIMEOUT => 6,
	);*/

    // start the first batch of requests
    for ($i = 0; $i < $rolling_window; $i++) {
        $ch = curl_init();
        if (!is_array($tmp=send_sms_url_get($urls,$i))) break;
		//$std_options[CURLOPT_URL]=$tmp[0];
		//$std_options[CURLOPT_POSTFIELDS]=$tmp[1];
		//$std_options[CURLOPT_HTTPHEADER]=$tmp[2];
		//curl_setopt_array($ch,$std_options);
		curl_setopt($ch,CURLOPT_TIMEOUT,$tmp[3]);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_URL,$tmp[0]);
        curl_multi_add_handle($master, $ch);
    }

    do {
        while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
        if($execrun != CURLM_OK) break;
        // a request was just completed -- find out which one
        while($done = curl_multi_info_read($master)) {
            $info = curl_getinfo($done['handle']);
			// start a new request (it's important to do this before removing the old one)
			
			if (is_array($tmp=send_sms_url_get($urls,$i++))) {
				//$std_options[CURLOPT_URL]=$tmp[0];
				//$std_options[CURLOPT_POSTFIELDS]=$tmp[1];
				//$std_options[CURLOPT_HTTPHEADER]=$tmp[2];				
				$ch = curl_init();
				//curl_setopt_array($ch,$std_options);
				curl_setopt($ch,CURLOPT_TIMEOUT,$tmp[3]);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_URL,$tmp[0]);
				curl_multi_add_handle($master, $ch);			
			}
            if ($info['http_code'] == 200)  {
                $output = curl_multi_getcontent($done['handle']);
                /*		Request is successful		*/
                send_sms_status_update($info['url'],gateway_status($output));
				$report[]=$output;
                // remove the curl handle that just completed
                curl_multi_remove_handle($master, $done['handle']);

            } else {
                // request failed.  add error handling.
				/*		Record timeout		*/
				send_sms_status_update($info['url'],30);
				/*record_problem('send_fail',print_r(curl_error($ch),true).
				print_r($info,true).print_r(curl_multi_getcontent($done['handle']),true));*/
            }
        }
		if ($running) curl_multi_select($master); // Wait until activity detection
    } while ($running);
    curl_multi_close($master);
    return $report;
}
/*
		Credit = false means no credit check
*/
function prepare_sms($i,$credit=false,$without_uniquefier=false){
	global $sms_length;
	$today=db_array(db_query('SELECT MINUTE(NOW()) as m,HOUR(NOW()) as h'));
	$today_text="\n".(($today['h']<10)?'0':'').$today['h'].':'.(($today['m']<10)?'0':'').$today['m'];
	//preg_replace(array("/0/", "/1/", "/2/",'/3/','/4/','/5/','/6/','/7/','/8/','/9/'),array("٠","۱","۲","٣","۴","۵","٦","٧","٨","۹"),);

	//.' '.(($i['last_value'])?'('.($org_price-$i['last_value']).')':'') Compare to preview one
	if (is_array($i)) foreach ($i as $l){
		if (!$org_price) {
			$org_price=get_price($l['source_type'],$l['rate_type'],$l['currency_id'],'sell-raw');
		}
		if (!$l['currency_id']) continue;
		
		$out.="\n".prepare_sms_row($l['source_type'],$l['rate_type'],$l['currency_id']);
		$currencies[]=array($l['source_type'],$l['rate_type'],$l['currency_id']);
		if (mb_strlen($out,'utf-8')>$sms_length-20) break;
	}
	$out.=$today_text;
	if ($credit) $out=finalize_sms_content($out,$credit);
	/*		SMS unqiuefy		*/
	if (!$without_uniquefier) $out=sms_uniquefy($out,$today['m']);

	return array($org_price,$out,$currencies);
}
function sms_uniquefy($sms_content,$current_min){
	$unique=getJSON('sms_uniquefy');
	/*		Check uniqueness based un current time as SMS content's change		*/
	if ($unique['min']!=$current_min){		
		$unique=array('min'=>$current_min);
	}
	$sms_content.=sms_uniquefy_add_persian($unique[md5($sms_content)]++);
	setJSON('sms_uniquefy',$unique);
	return $sms_content;
}
function sms_uniquefy_add_persian($number=0){
	$number++;
	/*		Persian chars	*/
	$persian_chars=array('','٭','٫','٬','،','؛','؞','؟','ٙ','ٚ','ؐ','ؑ','ؒ','ؔ','ً','ٌ','ٍ','َ','ُ','ِ','ّ','ْ','ٓ','ٔ','ٕ','ٖ','ٗ','٘','ٛ','ٜ','ٝ','ٞ','۔','ە','ۛ','۠','ۤ','۪','۫','۬','أ','ؤ','إ','ئ','ا','ب','ة','ت','ث','ج','ح','خ','د','ذ','ر','ز','س','ش','ص','ض','ط','ظ','ع','غ ','ٶ','ٷ','ٸ','ٹ','ٺ','ٻ','ټ','ٽ','پ','ٿ','ڀ','ځ','ڂ','ڃ','ڄ','څ','چ','ڇ','ڈ','ډ','ڊ','ڋ','ڌ','ڍ','ڎ','ڏ','ڐ','ڑ','ڒ','ړ','ڔ','ڕ','ږ','ڗ','ژ','ڙ','ښ','ڛ','ڜ','ڝ','ڞ','ڟ','ڠ','ڡ','ڢ','ڣ','ڤ','ڥ','ڦ','ڧ','ڨ','ک','ڪ','ګ','ڬ','ڭ','ڮ','گ','ڰ','ڱ','ڲ','ڳ','ڴ','ڵ','ڶ','ڷ','ڸ','ڹ','ں','ڻ','ڼ','ڽ','ھ','ڿ','ۀ','ہ','ۂ','ۃ','ۄ','ۅ','ۆ','ۇ','ۈ','ۉ','ۊ','ۋ','ی','ۍ','ێ','ۏ','ې','ۑ');
	/*		Return persian char from unicode range to make sms persian		*/
	$base=155; //count($persian_chars)+1;
	while($number){
		if ($number<=$base){
			$uniqness.=$persian_chars[$number];
			$number=0;
		} else {
			$result=floor($number/$base);
			$remaining=$number-($base*$result);
			$uniqness.=$persian_chars[$remaining];
			$number=$result;
		}		
	}
	return $uniqness;
}
function prepare_sms_row($source_type,$rate_type,$currency_id,$type='full'){
	$org_price=get_price($source_type,$rate_type,$currency_id);
	if (strstr($org_price,'.')) $decimal=1; else $decimal=0;
	$out=rate_name($source_type,$rate_type,$currency_id).' '.number_format($org_price,$decimal);
	$timestamp=get_price($source_type,$rate_type,$currency_id,'timestamp');
	$tmp=db_array(db_query('SELECT CURDATE() as cur_date,DATE(FROM_UNIXTIME('.$timestamp.')) as last_date,TIME(FROM_UNIXTIME('.$timestamp.')) as last_time,UNIX_TIMESTAMP(NOW()) as timestamp;'));
	$tmp2=explode(':',$tmp['last_time']);
	$time=$tmp2[0].':'.$tmp2[1];
	if($tmp['cur_date']!=$tmp['last_date']){
		list($y,$m,$d)=to_jalali($tmp['last_date']);
		$out.=' '.$d.'/'.$m;//' '.$time.
	} elseif ($tmp['timestamp']-$timestamp>5*60){
		$out.=' '.$time;
	}
	return $out;
}