<?php
ini_set('memory_limit','1400M');
ini_set('max_execution_time','100000'); 
include "../private/db.php";
include "../private/backend.php";
$debug=false;
db_zone();

exch_archive_daily();
exch_archive_monthly();
exch_archive_monthly_persian();

/*		Daily Rates for 2 days		*/
function exch_archive_daily(){
	mysql_select_db('exchange_archive');
	$rs=db_query('SELECT YEAR(addedon) as y,MONTH(addedon) as m,DAY(addedon) as d,addedon,sell,currency_id,rate_type FROM exch_rate WHERE duration_type = 0 AND  addedon >= DATE_SUB(CURDATE(),INTERVAL 2000 DAY) ORDER BY addedon');
	while($row=db_array($rs)){
		$date=$row['y'].'-'.$row['m'].'-'.$row['d'];
		$key=$row['rate_type'].'-'.$row['currency_id'];
		$id=$date.':'.$key;
		if (!$data[$id]['start']){
			$data[$id]['start']=$row['sell'];
			$data[$id]['date']=$date;
			$data[$id]['key']=$key;
		}
		$data[$id]['end']=$row['sell'];
		if (!$data[$id]['min']||$data[$id]['min']>$row["sell"])$data[$id]['min']=$row["sell"];
		if ($data[$id]['max']<$row["sell"])$data[$id]['max']=$row["sell"];
	}	
	mysql_select_db('exchange');
	foreach ($data as $val){
		$tmp=explode('-',$val['date']);
		list($y,$m,$d)=gregorian_to_jalali($tmp[0],$tmp[1],$tmp[2]);
		list($rate_type,$currency_id)=explode('-',$val['key']);
		db_query('REPLACE INTO exch_archive (rate_type, currency_id,duration_type, addedon, pyear, pmonth, pday, start, end, min, max)
			VALUES('.$rate_type.','.$currency_id.',0,"'.$val['date'].'",'.$y.','.$m.','.$d.','.$val['start'].','.$val['end'].','.$val['min'].','.$val['max'].')');
	}
}

/*		Monthly Rates for 2 months		*/
function exch_archive_monthly(){
	mysql_select_db('exchange');
	$today_row=db_array(db_query('SELECT YEAR(NOW()) as y,MONTH(NOW()) as m,DAY(NOW()) as d'));
	$start=$today_row['y'].'-'.$today_row['m'].'-01 00:00:00';
	$rs=db_query('SELECT *,YEAR(addedon) as y,MONTH(addedon) as m,DAY(addedon) as d FROM exch_archive WHERE duration_type = 0 AND addedon >= DATE_SUB("'.$start.'",INTERVAL 200 MONTH) ORDER BY addedon');
	while($row=db_array($rs)){
		$date=$row['y'].'-'.$row['m'].'-01';
		$key=$row['rate_type'].'-'.$row['currency_id'];
		$id=$date.':'.$key;
		if (!$data[$id]['start']){
			$data[$id]['start']=$row['start'];
			$data[$id]['date']=$date;
			$data[$id]['key']=$key;			
		}
		$data[$id]['end']=$row['end'];
		if (!$data[$id]['min']||$data[$id]['min']>$row['min'])$data[$id]['min']=$row['min'];
		if ($data[$id]['max']<$row['max'])$data[$id]['max']=$row['max'];
	}

	foreach ($data as $val){
		$tmp=explode('-',$val['date']);
		list($y,$m,$d)=gregorian_to_jalali($tmp[0],$tmp[1],1);
		list($rate_type,$currency_id)=explode('-',$val['key']);
		db_query('REPLACE INTO exch_archive (rate_type, currency_id,duration_type, addedon, pyear, pmonth, pday, start, end, min, max)
			VALUES('.$rate_type.','.$currency_id.',1,"'.$tmp[0].'-'.$tmp[1].'-01",'.$y.','.$m.','.$d.','.$val['start'].','.$val['end'].','.$val['min'].','.$val['max'].')');
	}
}
/*		Monthly Rates for 2 months persian	*/
function exch_archive_monthly_persian(){
	mysql_select_db('exchange');
	$today_row=db_array(db_query('SELECT DATE_SUB(CURDATE(),INTERVAL 200 MONTH)'));
	$tmp=explode('-',$today_row[0]);
	list($y,$m,)=gregorian_to_jalali($tmp[0],$tmp[1],$tmp[2]);
	list($y,$m,$d)=jalali_to_gregorian($y,$m,1);

	$start=$y.'-'.$m.'-'.$d.' 00:00:00';
	$rs=db_query('SELECT *,YEAR(addedon) as y,MONTH(addedon) as m,DAY(addedon) as d FROM exch_archive WHERE duration_type = 0 AND addedon >= "'.$start.'" ORDER BY addedon');
	while($row=db_array($rs)){
		list($y,$m,)=gregorian_to_jalali($row['y'],$row['m'],$row['d']);
		$date=$y.'-'.$m.'-01';
		$key=$row['rate_type'].'-'.$row['currency_id'];
		$id=$date.':'.$key;
		if (!$data[$id]['start']){
			$data[$id]['start']=$row['start'];
			$data[$id]['date']=$date;
			$data[$id]['key']=$key;
		}
		$data[$id]['end']=$row['end'];
		if (!$data[$id]['min']||$data[$id]['min']>$row['min'])$data[$id]['min']=$row['min'];
		if ($data[$id]['max']<$row['max'])$data[$id]['max']=$row['max'];
	}
	mysql_select_db('exchange');
	foreach ($data as $val){
		$tmp=explode('-',$val['date']);
		list($y,$m,$d)=jalali_to_gregorian($tmp[0],$tmp[1],$tmp[2]);
		list($rate_type,$currency_id)=explode('-',$val['key']);
		db_query('REPLACE INTO exch_archive (rate_type, currency_id,duration_type, addedon, pyear, pmonth, pday, start, end, min, max)
			VALUES('.$rate_type.','.$currency_id.',2,"'.$y.'-'.$m.'-'.$d.'",'.$tmp[0].','.$tmp[1].','.$tmp[2].','.$val['start'].','.$val['end'].','.$val['min'].','.$val['max'].')');
	}
}