<?php
//ini_set('display_errors', '1');
include '../private-notify/notify-backend.php';
include '../private-notify/lib-sms.php';
include '../private-notify/lib-credit.php';
include '../private-notify/conf-keywords.php';

db_query('begin');
$sms_cost_type=0;
$skip=0;

/*		Parse SMS			*/
$msg=$_GET['message'];
preg_match("/([1-9][0-9]+)/",  substr($_GET['from'], -10),$phone_no);
$phone_no=$phone_no[0];
$debug.=print_r($_GET,true);

/*	 	Find UID 	 	*/
list($uid,$credit,$line_status)=check_phone_and_credit($phone_no); 

/*		Validate user		*/
$validate_sms=validate_sms($uid);

/*		Check Credit		*/
if (!$uid&&$free_two_way_sms) {
	$credit=check_and_update_free_credit($phone_no);
	$sms_cost_type=5;
}

if (!$credit) $skip+=1;

/*		Detect Request		*/
if (!$requested=detect_request($msg)) {
	if (trim($msg)) record_problem('received',$debug);	
	$skip+=2;
}
if ($validate_sms) $skip=7;

$debug.=print_r($requested,true);

/*		Record SMS			*/
db_query('INSERT notify_sms_incoming (uid,message, addedon, phone_no, result) VALUES 
		('.$uid.',"'.mysql_real_escape_string($msg).'",NOW(),"'.$phone_no.'",'.($skip).')');
$incomming_sms_id=mysql_insert_id();

/*		Skip further Steps	*/
if ($skip&&!$validate_sms){
	db_query('commit');
	//file_put_contents('debug.txt',$debug);
	echo 'NOT SENT: '.$skip;
	exit;
}

if(!$validate_sms){
	$latest_exchanges=getJSON('latest_exchanges');
	$last_ime=getJSON('last_ime');
	/*		Prepare Result		*/
	list(,$content,$preserve_request)=prepare_sms($requested,$credit);
}

/*		Gateway SMS Send & Update SMS Status	*/
$sms[]=array(
	'phone_no'=>$phone_no,
	'uid'=>$uid,
	'trigger_type'=>30, // Receive SMS
	'trigger_id'=>$incomming_sms_id,
	'content'=>(($validate_sms)?$validate_sms:$content),
	'sms_cost_type'=>(($validate_sms)?7:$sms_cost_type), // How cost of this sms is calculated
	'line_status'=>$line_status,
	'current_status'=>13,	// SMS Status: sending
	'retry'=>100,	// 100 Retries
	'time'=>60,	// Stop after 100min retry
	'request'=>(($validate_sms)?'':$preserve_request),
);

$debug.=print_r(push_sms($sms),true);

//file_put_contents('debug.txt',$debug);

function validate_sms($uid){
	if (!$uid) return;
	if ($validate_code=validated_user($uid)){
		return "Activation code: $validate_code\nکد فعال سازی: $validate_code";
	}
}
function detect_request($msg){
	global $sms_keywords;
	$requests=preg_split("/[,\n\.]+/", $msg);
	foreach ($requests as $request) {
		if (!$request) continue;
		foreach ($sms_keywords as $key => $val){
			if (stristr($request,$key)) {
				$tmp=explode('|',$val);
				foreach ($tmp as $code){
					$results[]=$code;
				}				
				break;
			}
		}
	}
	if (is_array($results)){
		$codes=array_unique($results);
		foreach ($codes as $code){
			$out[]=parse_currency($code);
		}
	}
	if (is_array($out)) return $out;
}
function check_and_update_free_credit($phone){
	global $free_two_way_sms;
	$rs=db_query('SELECT sms_count FROM notify_free_sms WHERE phone_no="'.$phone.'"');
	if ($row=db_array($rs)){
		if ($row['sms_count']>=$free_two_way_sms) return 0;
	}
	db_query('REPLACE notify_free_sms (phone_no,sms_count) VALUES ("'.$phone.'",'.(1+$row['sms_count']).')');
	return (1+$row['sms_count']);
}
?>SENT