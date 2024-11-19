<?php
function credit_use($uid,$amount=-1){
	db_query('UPDATE notify_users SET credit=credit+'.$amount.' WHERE uid = '.$uid);
}
/*
	uid
	service: Service code
	amount: Money amount
	credit: SMS Count
	days: until expire
	memo
	transfer_type
		1	System Credit
		2	Mellat Alavi - Gateway
		3	Mellat Alavi - Card to Card
*/
function credit_in($i){	
	$credit=str_replace(',','',(($i['credit'])?$i['credit']:0));
	$days=str_replace(',','',(($i['days'])?$i['days']:0));
	$amount=str_replace(',','',(($i['amount'])?$i['amount']:0));
	$pack_id=str_replace(',','',(($i['pack_id'])?$i['pack_id']:0));
	$memo=array('service'=>$i['service'],'memo'=>$i['memo'],'transfer_type'=>$i['transfer_type']);
	$i['uid']=str_replace(',','',(($i['uid'])?$i['uid']:0));
	if (!$i['credit']&&!$i['days']){
		list(,$days,$credit)=service_spec($i['service']);
	}
	db_query('INSERT INTO notify_transfers(uid,amount,credit,service_days,addedon,memo) 
			VALUES ('.$i['uid'].','.$amount.','.$credit.','.$days.',NOW(),"'.mysql_real_escape_string(json_encode($memo)).'")');
	$details=db_array(db_query('SELECT phone_no,credit,service_until,UNIX_TIMESTAMP(service_until) as service_timestamp,UNIX_TIMESTAMP(DATE(NOW())) as now_timestamp 
		FROM notify_users WHERE uid='.$i['uid']));
	if ($details['service_timestamp']>$details['now_timestamp']) { // Still there are some days until finishing subscription date
		db_query('UPDATE notify_users SET credit=credit+'.$credit.',service_until=DATE_ADD(service_until,INTERVAL '.$days.' DAY) WHERE uid = '.$i['uid']);
	} else { // Already expired or finished
		db_query('UPDATE notify_users SET credit='.$credit.',service_until=DATE_ADD(NOW(),INTERVAL '.$days.' DAY) WHERE uid = '.$i['uid']);
	}
	if ($pack_id){
		$row=db_array(db_query('SELECT uid,upto,UNIX_TIMESTAMP(upto) as service_timestamp,UNIX_TIMESTAMP(DATE(NOW())) as now_timestamp 
		FROM notify_packages WHERE uid='.$i['uid']));
		if ($row['service_timestamp']>$row['now_timestamp']) { // Still there are some days until finishing subscription date
			db_query('REPLACE notify_packages (pack_id,uid,upto) VALUES ('.$pack_id.','.$i['uid'].',DATE_ADD("'.$row['upto'].'",INTERVAL '.$days.' DAY))');
		} else { // Already expired or finished
			db_query('REPLACE notify_packages (pack_id,uid,upto) VALUES ('.$pack_id.','.$i['uid'].',DATE_ADD(NOW(),INTERVAL '.$days.' DAY))');
		}
	}
	return $details['phone_no'];
}
function transfer_resource_commit($i){	
	$credit=str_replace(',','',(($i['credit'])?$i['credit']:0));
	$days=str_replace(',','',(($i['service_days'])?$i['service_days']:0));
	$amount=str_replace(',','',(($i['amount'])?$i['amount']:0));
	$pack_id=str_replace(',','',(($i['pack_id'])?$i['pack_id']:0));
	$memo=array('service'=>$i['service'],'memo'=>$i['memo'],'transfer_type'=>$i['transfer_type']);
	$i['uid']=str_replace(',','',(($i['uid'])?$i['uid']:0));
	if (!$i['credit']&&!$i['service_days']){
		list(,$days,$credit)=service_spec($i['service']);
	}
	$details=db_array(db_query('SELECT phone_no,credit,service_until,UNIX_TIMESTAMP(service_until) as service_timestamp,UNIX_TIMESTAMP(DATE(NOW())) as now_timestamp 
		FROM notify_users WHERE uid='.$i['uid']));
	if ($details['service_timestamp']>$details['now_timestamp']) { // Still there are some days until finishing subscription date
		db_query('UPDATE notify_users SET credit=credit+'.$credit.',service_until=DATE_ADD(service_until,INTERVAL '.$days.' DAY) WHERE uid = '.$i['uid']);
	} else { // Already expired or finished
		db_query('UPDATE notify_users SET credit='.$credit.',service_until=DATE_ADD(NOW(),INTERVAL '.$days.' DAY) WHERE uid = '.$i['uid']);
	}
	if ($pack_id){
		$row=db_array(db_query('SELECT uid,upto,UNIX_TIMESTAMP(upto) as service_timestamp,UNIX_TIMESTAMP(DATE(NOW())) as now_timestamp 
		FROM notify_packages WHERE uid='.$i['uid']));
		if ($row['service_timestamp']>$row['now_timestamp']) { // Still there are some days until finishing subscription date
			db_query('REPLACE notify_packages (pack_id,uid,upto) VALUES ('.$pack_id.','.$i['uid'].',DATE_ADD("'.$row['upto'].'",INTERVAL '.$days.' DAY))');
		} else { // Already expired or finished
			echo 'REPLACE notify_packages (pack_id,uid,upto) VALUES ('.$pack_id.','.$i['uid'].',DATE_ADD(NOW(),INTERVAL '.$days.' DAY))';
			db_query('REPLACE notify_packages (pack_id,uid,upto) VALUES ('.$pack_id.','.$i['uid'].',DATE_ADD(NOW(),INTERVAL '.$days.' DAY))');
		}
	}
	db_query("commit");
	return $details['phone_no'];
}
function service_spec($code){
	global $service_types;
	return $service_types[$code];
}
function check_phone_and_credit($phone){
	$rs=db_query('SELECT uid,line_status,credit,UNIX_TIMESTAMP(service_until) as service_timestamp,UNIX_TIMESTAMP(NOW()) as now_timestamp FROM notify_users WHERE phone_no="'.$phone.'"');
	if ($row=db_array($rs)){
		return array($row['uid'],(($row['service_timestamp']>$row['now_timestamp'] && $row['credit']>0)?$row['credit']:0),$row['line_status']);
	}
	return array(0,0);
}
function check_credit($uid){
	if (!$uid) return true; // Permission for editing user = 0
	$rs=db_query('SELECT credit,UNIX_TIMESTAMP(service_until) as service_timestamp,UNIX_TIMESTAMP(NOW()) as now_timestamp,line_status FROM notify_users WHERE uid="'.$uid.'"');
	if ($row=db_array($rs)) {
		if ($row['line_status']==200) return 0;
		if ($row['service_timestamp']>$row['now_timestamp'] && $row['credit']>0) return $row['credit'];
	}
	return 0;
}
function check_free_credit($phone){
	global $free_two_way_sms;
	if (!$free_two_way_sms) return 0;
	$rs=db_query('SELECT sms_count FROM notify_free_sms WHERE phone_no="'.$phone.'"');
	if ($row=db_array($rs)){
		if ($row['sms_count']>=$free_two_way_sms) return 0; 
	}
	return true;
}