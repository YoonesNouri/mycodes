<?php
chdir(dirname(__FILE__));
include 'notify-backend.php';
include 'lib-sms.php';
db_query('begin');

/*		Disable lines with lots of failed send		*/
/*		Atleast 10 fails without any successful attempt in last 30 days		*/
/*
$rs=db_query('SELECT uid,sum(if(track_id>0 && current_status = 1,1,0)) as blocked,sum(if(track_id>0 && current_status != 1,1,0)) as not_blocked FROM notify_sms WHERE uid>0 AND send_by = 1 AND addedon > "2012-09-18" AND DATE_SUB(NOW(), INTERVAL 30 DAY)<addedon GROUP BY uid');
while($row=db_array($rs)){
	if ($row['blocked']>9 && !$row['not_blocked']) $uids.=(($uids)?',':'').$row['uid'];
}
if ($uids){
	echo 'Line is blocked for following users: '.$uids."\n";
	db_query('UPDATE notify_users SET line_status = 200 WHERE uid IN('.$uids.')');
	db_query('UPDATE notify_triggers SET active = "N" WHERE uid IN('.$uids.')');
	$rs=db_query('SELECT trigger_id FROM notify_triggers WHERE uid IN('.$uids.')');
	while ($row=db_array($rs)){
		$triggers.=(($triggers)?',':'').$row['trigger_id'];
	}
	if ($triggers){
		db_query('DELETE FROM notify_detect_change WHERE trigger_id IN ('.$triggers.')');
		db_query('DELETE FROM notify_detect_time WHERE trigger_id IN ('.$triggers.')');
	}
}
*/

/*		Remove expired subescriptions	*/
$rs=db_query('SELECT uid FROM notify_packages WHERE upto <= NOW()');
while($row=db_array($rs)){
	sms_insert(array('uid'=>$row['uid'],'trigger_type'=>50,'content'=>'بسته اشتراکی شما به پایان رسید.','sms_cost_type'=>50,'recreate'=>array('r'=>100,'t'=>(floor(time()/60)+100)))); // Send with next send-sms run
	echo $row['uid']." package finished!\n";
}
db_query('DELETE FROM notify_packages WHERE upto <= NOW()');
db_query('commit');

/*		Remove old sessions		*/