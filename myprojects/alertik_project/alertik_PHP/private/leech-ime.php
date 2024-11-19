<?php
include "db.php";
include "backend.php";
include "func-leech.php";
include "ati-cfg.php";
$countdown_timer=58;
//$debug=true;
db_zone();

db_query('begin;');
$last_ime=getJSON('last_ime'); // Keep records for each source

if (ime_open()) {
	foreach ($ati_months as $tmp_key=>$tmp_ati) {
		if (leech_timer($last_ime[$tmp_key]['prev_timestamp'],$last_ime[$tmp_key]['timestamp'],array('min'=>10,'max'=>58,'major'=>true))) {
			$current_update_time=$last_ime[$tmp_key]['timestamp'];
			//echo $tmp_key.':'.time()."\n";
			if (ime_parse($tmp_key,$tmp_ati)) {
				$changed=true;
				$last_ime[$tmp_key]['prev_timestamp']=$current_update_time;
			}
		}
	}
}

if (day_change_detect()) $changed=true;

if ($changed) {
	$last_ime['countdown_timer']=$countdown_timer;
	setCFG('last_ime',json_encode($last_ime));
}
db_query('commit;');
if (is_array($error_report)) print_r($error_report);
function leech_timer($prev_update,$last_update,$settings){
	global $countdown_timer;
	return true;
	/*
		Previous Update|----update_time----|LastUpdate|-------update_time/2-------|
	*/
	$update_time=($last_update-$prev_update)/2;
	if (time()-$last_update>$settings['max']) return true;
	//if (time()-$last_update>$update_time) return false;
	if ($update_time<$settings['min']) $update_time=$settings['min'];
	if ($update_time>$settings['max']) $update_time=$settings['max'];
	if ($settings['major']) {
		if ($countdown_timer>$update_time) $countdown_timer=$update_time;
	}
	echo time().' '.$update_time.' '.($last_update+$update_time-time())."<".' '.(($last_update+$update_time<time())?true:false)."\n";
	if ($last_update+$update_time<time()) return true;
}
/*
	Reset all of the changes to zero at 12 each night and also remove min and max
*/
function day_change_detect(){//
	if($row=db_array(db_query('SELECT DAY(NOW()) as d;'))) {
		if ($tmp=getCFG('before_ime')) $prev=json_decode($tmp,true);
		if ($row['d']!=$prev['day_change']){
			global $last_ime;
			foreach ($last_ime as $key => $value){
				unset($last_ime[$key]['volume']);
				$last_ime[$key]['min']=$last_ime[$key]['sell'];
				$last_ime[$key]['max']=$last_ime[$key]['sell'];
				$last_ime[$key]['yesterday']=$last_ime[$key]['sell'];
			}
			$prev=$last_ime;
			$prev['day_change']=$row['d'];
			setCFG('before_ime',json_encode($prev));
			return true;
		}
	}
}

function ime_xml ($content) {
	$content = trim(str_replace('"', "'", str_replace(array("\n", "\r", "\t"), '', $content)));
	$content=simplexml_load_string($content);
	return (array)$content->entry;
	//	return json_encode(simplexml_load_string($content), JSON_UNESCAPED_UNICODE);
}
function ime_parse($key,$ati) {
	global $error_report,$ime_data,$last_ime;
	$post=json_encode(['ContractCode'=>$ati['id']]);

	$data = array("ContractCode" => $ati['id']);
	$data_string = json_encode($data);

	$ch = curl_init('http://cdn.ime.co.ir/Services/Fut_Live_Loc_Service.asmx/GetContractInfo');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json',
	    'Content-Length: ' . strlen($data_string))
	);

	$result = json_decode(curl_exec($ch),true);
	$result=$result['d'];

	$last_sell=$result['LastTradedPrice']/10000;
	//list($y,$m,$d)=explode('/',$ime_data['ODD']);
	//list($y,$m,$d)=jalali_to_gregorian($y,$m,$d);

	$check=md5($ati['y'].','.$ati['m'].',NOW(),'.$last_sell.','.$result['AskTotalVolume'].','.$result['BidTotalVolume'].','.$result['OpenInterests']); // Detect Changes
	if ($check== $last_ime[$key]['change_detect'] || !$last_sell)  return;
	$last_ime[$key]['change_detect']=$check;
	$last_ime[$key]['sell']= $last_sell;
	$last_ime[$key]['close_balance']=($result['TradesValue']*100)/$result['TradesVolume'];
	$last_ime[$key]['timestamp']=time();
	$last_ime[$key]['volume']=$result['TradesVolume'];
	//"'.$y.'/'.$m.'/'.$d.' '.$ime_data['ODT'].'"
	db_query('INSERT INTO ime (pyear, pmonth, addedon, last_price, sale_volume, buy_volume, open_position, pdate, duration_type)
		VALUES ('.$ati['y'].','.$ati['m'].',NOW(),'.$last_sell.','.$result['AskTotalVolume'].','.$result['BidTotalVolume'].','.$result['OpenInterests'].',NOW(),0);');
	db_query('REPLACE INTO ime_daily (pyear, pmonth, price_start, price_end, price_min, price_max,
		price_daily, open_position, volume, worth, archive_json, addedon)
		VALUES ('.$ati['y'].','.$ati['m'].','.($result['FirstTradedPrice']/10000).','.($result['LastTradedPrice']/10000).','.($result['LowTradedPrice']/10000).','.($result['HighTradedPrice']/10000)
			.','.($result['LastSettlementPrice']/10000).','.$result['OpenInterests'].','.$result['TradesVolume'].','.$result['TradesValue'].',\''.json_encode($ime_data).'\',NOW());');

	/*		Calculate min and max for main page renderer	*/
	if ($result['LowTradedPrice']/10000<$last_ime[$key]['min'] || !$last_ime[$key]['min']) $last_ime[$key]['min']=$result['LowTradedPrice']/10000;
	if ($result['HighTradedPrice']/10000>$last_ime[$key]['max']) $last_ime[$key]['max']=$result['HighTradedPrice']/10000;
	return true;
}

function ime_open(){
	$today=db_array(db_query('SELECT DAYOFWEEK(NOW()) as d,HOUR(NOW()) as h'));
	if ($today['d']!=6&&$today['h']>=9&&$today['h']<19) return true; //Market is open
}
