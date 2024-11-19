<?php
include '../private/db.php';
include '../private/backend.php';
include 'notify-config.php';
db_zone();

function record_problem($name,$data){
	if(is_array($data)) $data=print_r($data,true);
	file_put_contents('../logs/sms/'.$name.'_'.time().'.txt',$data);
}

function get_price($source_type, $rate_type, $currency_id, $return_key='sell') {
	global $latest_exchanges,$last_ime;
	$zero=1;
	if (!is_array($latest_exchanges)) $latest_exchanges=getJSON('latest_exchanges');
	if (!is_array($last_ime)) $last_ime=getJSON('last_ime');	
	if ($return_key=='sell') $zero=zero_decision($source_type, $rate_type, $currency_id);
	if ($return_key=='sell-raw') $return_key='sell'; 
	if ($source_type==0) {
		$key=$rate_type.':'.$currency_id;
		return $latest_exchanges[$key][$return_key]*$zero;
	} elseif ($source_type==1) {
		$key=$rate_type.'_'.$currency_id;
		return $last_ime[$key][$return_key]*$zero;
	}
}
function currency_postfix($source_type, $rate_type, $currency_id){
	if ($source_type==0) {
		if ($currency_id==1) return 'دلار';
		if ($currency_id==113) return 'تن';
		if ($currency_id==114) return 'واحد';
	}
	
	return 'ریال';
}
function zero_decision($source_type, $rate_type, $currency_id){
	if ($source_type==1) return 10000;
	if ($currency_id==1) return 1;
	if ($currency_id < 40) return 10000;
	return 10;
}

/*
	 Detection row create
*/
function detection_create($uid,$trigger_id,$current_price=0,$skip_delay=false){
	$rs=db_query('SELECT * FROM notify_triggers WHERE '.(($trigger_id)?'trigger_id = '.$trigger_id.' AND':'').' uid = '.$uid);
	while ($row=db_array($rs)) {
		$details=json_decode($row['details'],true);
		if (!$current_price) $current_price = get_price($row['source_type'],$row['rate_type'],$row['currency_id'],'sell-raw');
		if ($row['trigger_type']==1) { //Target price
			if ($details['price_target']>$current_price){
				$min=0;
				$max=$details['price_target'];
			} else {
				$min=$details['price_target'];
				$max=0;
			}
			detection_price_insert($row,$min,$max,'NOW()',$current_price);
		} elseif ($row['trigger_type']==2) { //Percentage Change
			if ($skip_delay) $tmp='NOW()'; else $tmp='DATE_ADD(NOW(),INTERVAL '.$details['change_delay'].' MINUTE)';
			detection_price_insert($row,(1-($details['change_amount']/100))*$current_price, (1+($details['change_amount']/100))*$current_price, $tmp,$current_price);
		} elseif ($row['trigger_type']==3) { //Step Change
			if ($skip_delay) $tmp='NOW()'; else $tmp='DATE_ADD(NOW(),INTERVAL '.$details['change_delay'].' MINUTE)'; 
			detection_price_insert($row,$current_price-$details['change_amount'],$current_price+$details['change_amount'], $tmp ,$current_price);
		} elseif ($row['trigger_type']==4) { //Target Date - First after it
			detection_time_insert($row,date_create_target(array('hour'=>$details['time_hour'],'min'=>$details['time_min'])),1,$current_price);
		} elseif ($row['trigger_type']==5) { //Target Date - Exact
			detection_time_insert($row,date_create_target(array('hour'=>$details['time_hour'],'min'=>$details['time_min'])),0,$current_price);
		} elseif ($row['trigger_type']==6) { //Every 2 hour - First after it
			detection_time_insert($row,'DATE_ADD(NOW(),INTERVAL '.$details['timegap'].' MINUTE)',1,$current_price);
		} elseif ($row['trigger_type']==7) { //Every 2 hour - Exact
			detection_time_insert($row,'DATE_ADD(NOW(),INTERVAL '.$details['timegap'].' MINUTE)',0,$current_price);
		}
	}
}
function detection_price_insert($i,$min,$max,$delay,$last_value){
	db_query('REPLACE INTO notify_detect_change (trigger_id, time_start, time_end, source_type, rate_type, currency_id, price_min, price_max, change_delay,last_value)
	VALUES('.$i['trigger_id'].','.$i['time_start'].','.$i['time_end'].','.$i['source_type'].','.$i['rate_type'].','.$i['currency_id'].','.$min.','.$max.','.$delay.','.$last_value.')');
}
function detection_time_insert($i,$date,$next,$last_value){
	db_query('REPLACE INTO notify_detect_time (trigger_id, time_start, time_end, source_type, rate_type, currency_id, time_trigger, next,last_value)
	VALUES('.$i['trigger_id'].','.$i['time_start'].','.$i['time_end'].','.$i['source_type'].','.$i['rate_type'].','.$i['currency_id'].','.$date.','.$next.','.$last_value.')');
}
function date_create_target($i){
	$row=db_array(db_query('SELECT YEAR(NOW()) as year, MONTH(NOW()) as month, DAY(NOW()) as day, HOUR(NOW()) as hour,MINUTE(NOW()) as min'));
	$date=$row['year'].'-'.$row['month'].'-'.$row['day'].' '.(($i['hour'])?$i['hour']:$row['hour']).':'.(($i['min'])?$i['min']:'00').':00';
	if ($i['hour']) {
		$row=db_array(db_query('SELECT CASE WHEN NOW()>"'.$date.'" THEN DATE_ADD("'.$date.'",INTERVAL 1 DAY) ELSE "'.$date.'" END;'));
	} else { // One Hour after
		$row=db_array(db_query('SELECT CASE WHEN NOW()>"'.$date.'" THEN DATE_ADD("'.$date.'",INTERVAL 1 HOUR) ELSE "'.$date.'" END;'));
	}
	return '"'.$row[0].'"';
}
function rate_name($source_type,$rate_type,$currency_id){
	global $sms_keywords_name;
	return $sms_keywords_name[$source_type.':'.$rate_type.':'.$currency_id];
}
function rate_name_fa($source_type,$rate_type,$currency_id){
	global $sms_keywords_legend;
	return $sms_keywords_legend[$source_type.':'.$rate_type.':'.$currency_id];
}

function parse_currency($input,$return_string=false){
	if (is_array($input)) $tmp=$input; else $tmp=explode(':',$input);
	if ($return_string) return (int)$tmp[0].':'.(int)$tmp[1].':'.(int)$tmp[2];
	else return array('source_type'=>(int)$tmp[0],'rate_type'=>(int)$tmp[1],'currency_id'=>(int)$tmp[2]);
}
function is_ajax(){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') return true;
	return false;
}
function benchmark(){
	static $benchmark_start;
	if (!$benchmark_start) $benchmark_start = microtime(true);
	else return "<!--". (microtime(true) - $benchmark_start)."-->";
}
function redirect($url){
	if (is_ajax()) echo json_encode(array('redirect'=>$url));
	else location_redirect($url);
	exit;
}
function location_redirect($location=null){
	if ($location) header("Location: ".$location);
}
function msg_box($type,$msg){
	return '<div class="alert'.(($type)?' alert-'.$type:'').'">'.$msg.'</div>';
}
function validated_user($uid=0){
	global $user;
	$row=db_array(db_query('SELECT phone_approved FROM notify_users WHERE uid ='.(($uid)?$uid:$user['uid'])));
	return $row['phone_approved'];
}
function xml_val($tag,$data){
	preg_match("/<$tag>(.*)<\/$tag>/is", $data,$str);
	return $str[1];
}