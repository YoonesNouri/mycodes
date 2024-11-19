<?php
include "db.php";
include "backend.php";
include "func-leech.php";
$debug=true;

$count=getCFG("sarafi:ct");

//saman_parse();
pasargad_parse();
//tehran_parse();
//if (!($count%5)) melli_parse();
//if (!($count%2)) tala_parse();

//save_sarafi_parse($save);
if (++$count>10000) $count=1;
setCFG("sarafi:ct",$count);
if (is_array($error_report)) print_r($error_report);
function pasargad_parse() {
global $error_report,$save;
$source_id=100;
list($usable,$data,$info)=GetURL('http://pasargadexchange.com/');
if (!$usable) return $error_report[]=$info; 


/* Havaleh */
$table=focus_table('',$data);
$strs=array('4-40'=>'دلار امريكا','4-41'=>'يورو');
foreach ($strs as $key => $name) {
$sell=extract_point_data(array($name,1,'</td>',5),$table);
echo $name.' Sell:'.$sell.'<br />';
}


//$rate_type=3; 
//$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
}
function royal_parse() {
	global $error_report,$save;
	$source_id=100;
	list($usable,$data,$info)=GetURL('http://www.sarafiroyal.com/');
	if (!$usable) return $error_report[]=$info; 
	
	/* Paper */ 
	$table=focus_table('id="ctl00_MainContent_1_ArzPriceShow1_gridView"',$data);
	$strs=array('3-40'=>'دلار امریکا - نرخ آزاد','3-41'=>'یورو - نرخ آزاد','3-42'=>'پوند انگلیس');
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array($name,1,'</td>',2),$table);
		$buy=extract_point_data(array($name,1,'</td>',1),$table);
		echo $name.' Sell: '.$sell.'<br />';
		echo $name.' Buy: '.$buy.'<br />';
	}

	/* Coin */
	$table=focus_table('id="ctl00_MainContent_1_CoinPriceShow2_gridView"',$data);
	$strs=array('3-14'=>'یک گرمی','3-13'=>'ربع سکه','3-12'=>'نیم سکه','3-11'=>'سکه امامی','3-10'=>'سکه بهار آزادی');
	foreach ($strs as $key => $name) {
	$sell=extract_point_data(array($name,1,'</td>',2),$table);
	$buy=extract_point_data(array($name,1,'</td>',2),$table);
	echo $name.' Sell: '.$sell.'<br />';
	echo $name.' Buy: '.$buy.'<br />';
	}

	//$rate_type=3; 
	//$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
}
function saman_parse() {
	global $error_report,$save;
	$source_id=100;
	list($usable,$data,$info)=GetURL('http://www.samanexchange.com/persian/Currency.asp');
	if (!$usable) return $error_report[]=$info;	
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/"), array("", " ", " "), $data);
	
	/*		Paper 		*/	
	$table=focus_table('نرخ روز ارز',$data);
	$strs=array('3-40'=>'دلار امریکا - USA','3-41'=>'یورو - EUR');
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array($name,1,'</td>',2),$table);
		$buy=extract_point_data(array($name,1,'</td>',1),$table);
		echo $name.' Sell:'.$sell.'<br />';
		echo $name.' Buy:'.$buy.'<br />';
	}
	
	
	//$rate_type=3;	
	//$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
}
function tehran_parse() {
	global $error_report,$save;
	$source_id=100;
	list($usable,$data,$info)=GetURL('http://www.sarafitehran.com/');
	if (!$usable) return $error_report[]=$info;	
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/"), array("", " ", " "), $data);
	
	/*		Paper 		*/	
	$table=focus_table('نرخ ارز (اسکناس)',$data);
	$strs=array('3-40'=>'دلار آمریکا','3-41'=>'یورو','3-42'=>'پوند انگلیس');
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array($name,0,'</td>',-3),$table);
		$buy=extract_point_data(array($name,0,'</td>',-2),$table);
		echo $name.' Sell:'.$sell.'<br />';
		echo $name.' Buy:'.$buy.'<br />';
	}

	/*		Coin		*/
	$table=focus_table('نرخ سکه</span>',$data);
	$strs=array('3-14'=>'یک گرمی','3-13'=>'ربع سکه','3-12'=>'نیم سکه','3-11'=>'سکه امامی','3-10'=>'سکه بهار آزادی');
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array($name,0,'</td>',-3),$table);
		$buy=extract_point_data(array($name,0,'</td>',-2),$table);
		echo $name.' Sell:'.$sell.'<br />';
		echo $name.' Buy:'.$buy.'<br />';
	}
	
	/*		Havaleh		*/
	$table=focus_table('نرخ حواله</h3>',$data);
	$strs=array('4-40'=>'دلار آمریکا شرکتی','4-41'=>'یورو شرکتی','5-40'=>'دلار آمریکا شخصی','5-41'=>'یورو شخصی');
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array($name,0,'</td>',-2),$table);
		echo $name.' Sell:'.$sell.'<br />';
	}
	
	
	//$rate_type=3;	
	//$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
}


/*
	$last_fetch: latest rates for each source to check if they have been changed or not
			prevent duplicate values for each currencies
	$latest_exchanges: Latest rates which could be used to render main page values, even for virtual values
*/
function save_sarafi_parse($records) {
	//record on storage like this one [currency_id-rate_type][source_id]=[timestamp][price]
	global $error_report,$latest_exchanges;
	if ($tmp=getCFG('last_fetch')) $last_fetch=json_decode($tmp,true); // Keep records for each source
	if ($tmp=getCFG('latest_exchanges')) $latest_exchanges=json_decode($tmp,true); // Keep records for each currencies only	
	/*		Compare records to previous records for each source if they are not same record them and store them for the next step		*/
	foreach ($records as $record) {
		$record['buy']=(float)$record['buy'];
		$record['sell']=(float)$record['sell'];		
		$last_sell=$last_fetch[$record['source_id'].':'.$record['rate_type'].':'.$record['currency_id']];
		$last_sell_all=$latest_exchanges[$record['rate_type'].':'.$record['currency_id']]['sell'];
		
		// Check value
		if ($last_sell==$record['sell']) continue; // Prevent duplicate
		if ($last_sell) {
			if (abs($last_sell-$record['sell'])>($last_sell+$record['sell'])/10) {
				if (abs($last_sell_all-$record['sell'])>($last_sell_all+$record['sell'])/10) {			
					$error_report[]='more than 20% diff, source_id: '.$record['source_id'];
					$error_report[]=$record;
					continue;
				}
			}
			if (abs($last_sell-$record['sell'])>($last_sell+$record['sell'])/20) {
				if (abs($last_sell_all-$record['sell'])>($last_sell_all+$record['sell'])/20) {
					$error_report[]='more than 10% diff but recorded, source_id'.$record['source_id'];
					$error_report[]=$record;			
				}
			}
		}
		if ($latest_exchanges[$record['rate_type'].':'.$record['currency_id']]['sell']!=$record['sell']) {
			$exchange[$record['rate_type'].':'.$record['currency_id']]=array('sell'=>$record['sell'],'buy'=>$record['buy']);
		}
		$sql='INSERT INTO backlog VALUES ('.$record['currency_id'].','.$record['rate_type'].',NOW(),'.$record['source_id'].','.$record['buy'].','.$record['sell'].')';
		mysql_query($sql) or die(mysql_error().$sql.': Save Parse');		
		$last_fetch[$record['source_id'].':'.$record['rate_type'].':'.$record['currency_id']]=$record['sell'];
		$last_fetch[$record['source_id'].':'.$record['rate_type'].':'.$record['currency_id'].':t']=time();
	}
	
	
	/*		Compare records to previous records for each currencies if they are not same record them and store them for the next step		*/
	if (is_array($exchange)) foreach ($exchange as $key => $rate) {
		
		if ($last_fetch[$key]==$rate['sell']) continue; // If that's not changed continue
		list($rate_type,$currency_id)=explode(':',$key);
		
		// Record them all
		save_exchange($rate_type,$currency_id,$rate['buy'],$rate['sell']);
		$last_fetch[$key]=$rate['sell'];
		
		// Calucate gold and coin world market rate based on $ value and exchange rate
		if ($key=='0:1'||$key=='3:40') { // If dollar or gold rate have been change, you need to calculate world rates again
			// Ounce of 995 to gram of 750 	999.99
			$cal['world_gram_buy']=($latest_exchanges['0:1']['buy']*$latest_exchanges['3:40']['sell']*750)/(1000*31.1034768*1000);
			$cal['world_gram_sell']=($latest_exchanges['0:1']['sell']*$latest_exchanges['3:40']['sell']*750)/(1000*31.1034768*1000);
			save_exchange(1,3,rounding($cal['world_gram_buy'],6),rounding($cal['world_gram_sell'],6));
			
			// Gram 750 to Mesghal 705
			$cal['mesghal_buy']=($cal['world_gram_buy']*705*4.6083)/750;
			$cal['mesghal_sell']=($cal['world_gram_sell']*705*4.6083)/750;
			save_exchange(1,2,rounding($cal['mesghal_buy'],6),rounding($cal['mesghal_sell'],6));
			
			// Gram 750 to Gram 900
			$cal['fine_buy']=($cal['world_gram_buy']*900)/750;
			$cal['fine_sell']=($cal['world_gram_sell']*900)/750;
			save_exchange(1,14,rounding($cal['fine_buy'],6),rounding($cal['fine_sell'],6));

			// Coin
			save_exchange(1,10,rounding($cal['fine_buy']*8.133,6),rounding($cal['fine_sell']*8.133,6));
			save_exchange(1,11,rounding($cal['fine_buy']*8.133,6),rounding($cal['fine_sell']*8.133,6));
			save_exchange(1,12,rounding($cal['fine_buy']*4.0665,6),rounding($cal['fine_sell']*4.0665,6));
			save_exchange(1,13,rounding($cal['fine_buy']*2.03225,6),rounding($cal['fine_sell']*2.03225,6));
		}
		
		if ($rate_type==0 && $currency_id <70 && $currency_id > 40) { // calculate world currencies in Iranian Rial	
			$buy=rounding((1/$rate['sell']) * $latest_exchanges['3:40']['buy']);	
			$sell=rounding((1/$rate['sell']) * $latest_exchanges['3:40']['sell']);
			save_exchange(1,$currency_id,rounding($buy),rounding($sell));
		}
		
		// Calculate coins and 18k gold from market value
		if ($key=='3:2') {
			// Mesghal 705 to gram of 750
			$cal['gram_market']=($rate['sell']*750)/(705*4.6083);
			save_exchange(3,3,0,rounding($cal['gram_market']));
			
			
			// 750 to 900
			$cal['gram_fine_market']=($cal['gram_market']*900)/750;

			save_exchange(4,10,0,rounding($cal['gram_fine_market']*8.133,6));
			save_exchange(4,11,0,rounding($cal['gram_fine_market']*8.133,6));
			save_exchange(4,12,0,rounding($cal['gram_fine_market']*4.0665,6));
			save_exchange(4,13,0,rounding($cal['gram_fine_market']*2.03225,6));
			save_exchange(4,14,0,rounding($cal['gram_fine_market']));
		}
	}
	
	setCFG('last_fetch',json_encode($last_fetch));
	setCFG('latest_exchanges',json_encode($latest_exchanges));
}
