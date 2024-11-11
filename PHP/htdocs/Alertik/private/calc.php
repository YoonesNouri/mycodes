<?php
include "db.php";
include "backend.php";
$debug=false;
db_zone();
if ($tmp=getCFG('last_calc')) $calc=json_decode($tmp,true);
if (!is_array($calc)) $calc=array('y'=>'2000','m'=>'01','d'=>'01','h'=>'0');
db_query('BEGIN;');
//$lastest=hourly_insert($calc);
$lastest=daily_insert($calc);
earning_data($calc);
if ($day_changed) exch_archive($calc);
setCFG('last_calc',json_encode($lastest));
db_query('COMMIT;');
/*
	Daily exchange rates
	Monthly exchange rates
	Monthly exchange rates in persian

*/
function exch_archive(){
	exch_archive_daily();
	exch_archive_monthly();
	exch_archive_monthly_persian();
}
/*		Daily Rates for 2 days		*/
function exch_archive_daily(){
	$rs=db_query('SELECT YEAR(addedon) as y,MONTH(addedon) as m,DAY(addedon) as d,addedon,sell,currency_id,rate_type FROM exch_rate WHERE addedon >= DATE_SUB(CURDATE(),INTERVAL 2 DAY) ORDER BY addedon');
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
	foreach ($data as $val){
		$tmp=explode('-',$val['date']);
		list($y,$m,$d)=to_jalali($val['date']);
		list($rate_type,$currency_id)=explode('-',$val['key']);
		db_query('REPLACE INTO exch_archive (rate_type, currency_id,duration_type, addedon, pyear, pmonth, pday, start, end, min, max)
			VALUES('.$rate_type.','.$currency_id.',0,"'.$val['date'].'",'.$y.','.$m.','.$d.','.$val['start'].','.$val['end'].','.$val['min'].','.$val['max'].')');
	}
}
/*		Monthly Rates for 2 months		*/
function exch_archive_monthly(){
	$today_row=db_array(db_query('SELECT YEAR(NOW()) as y,MONTH(NOW()) as m,DAY(NOW()) as d'));
	$start=$today_row['y'].'-'.$today_row['m'].'-01 00:00:00';
	$rs=db_query('SELECT *,YEAR(addedon) as y,MONTH(addedon) as m,DAY(addedon) as d FROM exch_archive WHERE duration_type = 0 AND addedon >= DATE_SUB("'.$start.'",INTERVAL 2 MONTH) ORDER BY addedon');
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
	$today_row=db_array(db_query('SELECT DATE_SUB(CURDATE(),INTERVAL 2 MONTH)'));
	list($y,$m,)=to_jalali($today_row[0]);
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

	foreach ($data as $val){
		$tmp=explode('-',$val['date']);
		list($y,$m,$d)=jalali_to_gregorian($tmp[0],$tmp[1],$tmp[2]);
		list($rate_type,$currency_id)=explode('-',$val['key']);
		db_query('REPLACE INTO exch_archive (rate_type, currency_id,duration_type, addedon, pyear, pmonth, pday, start, end, min, max)
			VALUES('.$rate_type.','.$currency_id.',2,"'.$y.'-'.$m.'-'.$d.'",'.$tmp[0].','.$tmp[1].','.$tmp[2].','.$val['start'].','.$val['end'].','.$val['min'].','.$val['max'].')');
	}
}

function earning_data($lastupdate) {
	global $daily,$day_changed;
	$what=array('3:3','3:11','3:40','3:41'); //Gold, Coin, Dollar, Euro
	if ($tmp=getCFG('earnings')) $earnings=json_decode($tmp,true);
	foreach ($what as $v) {
		$earnings[$v][0]=(float)$daily['today'][$v]['last_sell']; //Now
		$earnings[$v][1]=(float)$daily['prev'][$v]['last_sell']; //Yesterday
		if ($day_changed) { // weekly and monthly earnings
			list($rate_type,$currency_id)=explode(':',$v);
			if ($row=db_array(db_query('SELECT end FROM exch_archive WHERE addedon >= DATE_SUB("'.$lastupdate['y'].'-'.$lastupdate['m'].'-'.$lastupdate['d'].' 00:00:00",INTERVAL 30 DAY) AND rate_type = '.$rate_type.' AND currency_id = '.$currency_id.' ORDER BY addedon LIMIT 1'))) {
				$earnings[$v][2]=(float)$row['end']; //1 Months Ago
			}
			if ($row=db_array(db_query('SELECT end FROM exch_archive WHERE addedon >= DATE_SUB("'.$lastupdate['y'].'-'.$lastupdate['m'].'-'.$lastupdate['d'].' 00:00:00",INTERVAL 90 DAY) AND rate_type = '.$rate_type.' AND currency_id = '.$currency_id.' ORDER BY addedon LIMIT 1'))){
				$earnings[$v][3]=(float)$row['end']; //3 Months Ago
			}
		}
	}
	setCFG('earnings',json_encode($earnings));
}
function daily_insert($lastupdate) {
	// Remove if it's min max and prev values are same as last_exch values
	global $daily;
	if ($tmp=getCFG('daily_rates')) $daily=json_decode($tmp,true);
	$daily=reset_all_change_at_midnight($daily,$lastupdate); // Do reset just once for all changes
	$rs=db_query('SELECT rate_type,currency_id,min(buy) as min_buy,min(sell) as min_sell,max(sell) as max_sell,avg(buy) as avg_buy,avg(sell) as avg_sell,
	YEAR(addedon) as y,MONTH(addedon) as m,DAY(addedon) as d,addedon FROM exch_rate
	WHERE addedon >= "'.$lastupdate['y'].'-'.$lastupdate['m'].'-'.$lastupdate['d'].' 00:00:00"
	GROUP BY currency_id,rate_type,y,m,d ORDER BY addedon');
	while ($row=db_array($rs)) { // Avg
		$start=$row['y'].'-'.$row['m'].'-'.$row['d'].' 00:00:00';
		$end=$row['y'].'-'.$row['m'].'-'.$row['d'].' 23:59:59';
		if ($last=db_array(db_query('SELECT buy,sell FROM exch_rate WHERE addedon >= "'.$start.'" AND addedon <= "'.$end.'" AND rate_type = '.$row['rate_type'].' AND currency_id = '.$row['currency_id'].' ORDER BY addedon DESC LIMIT 1;'))){ // Last one
			//save_exchange($row['rate_type'],$row['currency_id'],$last['buy'],$last['sell'],4,(($row['min_buy']>0)?$row['min_buy']:$row['min_sell']),$row['max_sell'],$start);
		} else echo 'Logical problem on preserving latest value on last day';
		//save_exchange($row['rate_type'],$row['currency_id'],0,$row['avg_sell'],3,(($row['min_buy']>0)?$row['min_buy']:$row['min_sell']),$row['max_sell'],$start);

		/*		calculate min and max for main page renderer	*/
		$key = $row['rate_type'].':'.$row['currency_id'];
		$daily['today'][$key]=array('last_buy'=>$last['buy'],'last_sell'=>$last['sell'],'min'=>(($row['min_buy']>0)?$row['min_buy']:$row['min_sell']),'max'=>$row['max_sell']);
		/*		calculate min and max for main page renderer	*/

		$latest=array('y'=>$row['y'],'m'=>$row['m'],'d'=>$row['d']);
	}
	setCFG('daily_rates',json_encode($daily));
	return $latest;
}
//+ Reorganize same as ati cal
function reset_all_change_at_midnight($daily,$lastupdate){// Reset all of the changes to zero at 12 each night
	global $day_changed;
	$day_changed=false; // Earning day change trigger
	$rs=db_query('SELECT DAY(NOW()) as d');
	while ($row=db_array($rs)) {
		if ($row['d']!=$daily['day_change']){
			$daily['prev']=$daily['today'];
			$day_changed=true;
			$daily['day_change']=$row['d'];

			$latest_exchanges=getJSON('latest_exchanges');
			foreach ($latest_exchanges as $key => $val){
				$latest_exchanges[$key]['min']=$val['sell'];
				$latest_exchanges[$key]['max']=$val['sell'];
				$latest_exchanges[$key]['yesterday']=$val['sell'];
			}
			setJSON('latest_exchanges',$latest_exchanges);

			$diamond=getJSON('diamond-price-tg');
			foreach ($diamond as $key => $val){
				$diamond[$key]['min']=$val['sell'];
				$diamond[$key]['max']=$val['sell'];
				$diamond[$key]['yesterday']=$val['sell'];
			}
			setJSON('diamond-price-tg',$diamond);

		}
	}
	return $daily;
}

