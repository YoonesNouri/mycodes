<?php
include "db.php";
include "backend.php";
include "func-leech.php";
//include '../lib/f.leech.php';

$currency_array=array(40=>'USD',41=>'EUR',42=>'GBP',43=>'AED',44=>'CAD',45=>'CNY',46=>'TRY',47=>'JPY',48=>'INR',49=>'AUD',50=>'SEK',51=>'CHF',52=>'NOK',53=>'HKD',54=>'MYR',55=>'KWD',56=>'IQD',57=>'THB',58=>'PKR',59=>'AZN',60=>'AFN',61=>'SAR',62=>'RUB',63=>'SGD',64=>'DKK',65=>'KRW');
$count=getCFG("fetch:ct");
db_zone();
//$debug=true;
include 'leech-data.php';

save_parse($save);
if (++$count>10000) $count=1;
setCFG("fetch:ct",$count);
if (is_array($error_report)) print_r($error_report);

calculate_currencies_from_gold();

function calculate_currencies_from_gold(){
	if ($tmp=getCFG('latest_exchanges')) $latest_exchanges=json_decode($tmp,true);
	if (time()-$latest_exchanges['3:3']['timestamp']>120) return; // Skip calculation if the market result belong to older than 00second

	if ($tmp=getCFG('daily_rates')) {
		$daily=json_decode($tmp,true);
		$before=$daily['prev'];
		$today=$daily['today'];
	}

	$dollar_by_gold=(($latest_exchanges['3:3']['sell']*10000)/($latest_exchanges['0:1']['sell']/41.47));
	$dollar_by_gold_min=(($today['3:3']['min']*10000)/($today['0:1']['max']/41.47));
	$dollar_by_gold_max=(($today['3:3']['max']*10000)/($today['0:1']['min']/41.47));
	$previous_dollar_by_gold=(($before['3:3']['last_sell']*10000)/($before['0:1']['last_sell']/41.47));	 //41.26 => 41.47

	for($k=40;$k<80;$k++){
		if ($latest_exchanges['0:'.$k]['sell']>0||$k==40) ; else continue;
		if ($k==40) {
			$price=$dollar_by_gold;
			$price_min=$dollar_by_gold_min;
			$price_max=$dollar_by_gold_max;
			$price_before=$previous_dollar_by_gold;
		} else {
			$price=(1/$latest_exchanges['0:'.$k]['sell'])*$dollar_by_gold;
			if ($before['0:'.$k]['last_sell']) $price_before=(1/$before['0:'.$k]['last_sell'])*$dollar_by_gold;
			$price_min=(1/$today['0:'.$k]['min'])*$dollar_by_gold;
			$price_max=(1/$today['0:'.$k]['max'])*$dollar_by_gold;
		}
		$latest_exchanges['20:'.$k]['sell']=$price/10;
		$latest_exchanges['20:'.$k]['timestamp']=time();
		$before['20:'.$k]['last_sell']=$price_before/10;
		$today['20:'.$k]['min']=(($price_min<=$price)?$price_min:$price)/10;
		$today['20:'.$k]['max']=(($price_max>=$price)?$price_max:$price)/10;
	}

	$daily['prev']=$before;
	$daily['today']=$today;
	setCFG('daily_rates',json_encode($daily));
	setCFG('latest_exchanges',json_encode($latest_exchanges));
}

function change_limit($new,$old,$percentage_limit){
	$diff=$old-$new;
	if (abs($diff*100/$new)>$percentage_limit) return true;
	return false;
}
/*
	$last_fetch: latest rates for each source to check if they have been changed or not
			prevent duplicate values for each currencies
	$latest_exchanges: Latest rates which could be used to render main page values, even for virtual values
*/
function save_parse($records) {
	global $error_report,$latest_exchanges,$latest_exchanges_tr;
	$last_fetch=getJSON('last_fetch'); // Keep records for each source
	$latest_exchanges=getJSON('latest_exchanges'); // Keep records for each currencies only
	$latest_exchanges_tr=getJSON('latest_exchanges_tr'); // Keep records for each currencies only

	/*		Clean Up Arrays		*/
	//unset($latest_exchanges['1:40']);
	/*		Clean Up Arrays		*/

	/*		Compare records to previous records for each source if they are not same record them and store them for the next step		*/
	if (is_array($records)) foreach ($records as $record) {
		$record['buy']=(float)$record['buy'];
		$record['sell']=(float)$record['sell'];
		$last_sell=$last_fetch[$record['source_id'].':'.$record['rate_type'].':'.$record['currency_id']];
		$last_sell_all=$latest_exchanges[$record['rate_type'].':'.$record['currency_id']]['sell'];

		// Check value
		if ($last_sell==$record['sell'] || !$record['sell']) continue; // Prevent duplicate

		if ($last_sell) {
			//if (change_limit($last_sell,$record['sell'],4)) {
			if (change_limit($last_sell_all,$record['sell'],10)) {
				$error_report[]='more than 10% diff, source_id: '.$record['source_id'].' Same Source:'.$last_sell.' All Source: '.$last_sell_all;
				$error_report[]=$record;
				continue;
			}
			if (change_limit($last_sell_all,$record['sell'],5)) {
				$error_report[]='more than 2% diff, source_id: '.$record['source_id'].' Same Source:'.$last_sell.' All Source: '.$last_sell_all;
				$error_report[]=$record;
			}
		}
		if ($latest_exchanges[$record['rate_type'].':'.$record['currency_id']]['sell']!=$record['sell']) {
			$exchange[$record['rate_type'].':'.$record['currency_id']]=array('sell'=>$record['sell'],'buy'=>$record['buy']);
		}
		$sql='REPLACE INTO backlog VALUES ('.$record['currency_id'].','.$record['rate_type'].',NOW(),'.$record['source_id'].','.$record['buy'].','.$record['sell'].')';
		db_query($sql);
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
			// Ounce of 995 to gram of 750
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

		/* 		Calculate world currencies in Iranian Rial if exchange rate change detected		*/
		if ($rate_type==0 && $currency_id <70 && $currency_id > 40) {
			$buy=rounding((1/$rate['sell']) * $latest_exchanges['3:40']['buy']);
			$sell=rounding((1/$rate['sell']) * $latest_exchanges['3:40']['sell']);
			save_exchange(1,$currency_id,rounding($buy),rounding($sell));
		}
		/* 		Calculate world currencies in Iranian Rial if dollar's market rate changed		*/
		if ($rate_type==3 && $currency_id == 40){
			foreach ($latest_exchanges as $key_tmp => $value_tmp){
				list($rate_type_tmp,$currency_id_tmp)=explode(':',$key_tmp);
				if ($rate_type_tmp==0 && $currency_id_tmp <70 && $currency_id_tmp > 40) {
					$buy=rounding((1/$value_tmp['sell']) * $latest_exchanges['3:40']['buy']);
					$sell=rounding((1/$value_tmp['sell']) * $latest_exchanges['3:40']['sell']);
					save_exchange(1,$currency_id_tmp,rounding($buy),rounding($sell));
					$sell=rounding((1/$value_tmp['sell']) * $latest_exchanges['3:40']['sell']);
					save_exchange(6,$currency_id_tmp,0,rounding($sell));
				}
			}
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

	setJSON('last_fetch',$last_fetch);
	setJSON('latest_exchanges',$latest_exchanges);
	//setJSON('latest_exchanges_tr',$latest_exchanges_tr);
}

function save_exchange($rate_type,$currency_id,$buy,$sell){
	global $latest_exchanges,$latest_exchanges_tr;
	$current=$latest_exchanges[$rate_type.':'.$currency_id];
	if ($current['sell']==$sell) return; // Prevent duplicate
	if ($sell<$current['min'] || !$current['min']) $current['min']=$sell;
	if ($sell>$current['max']) $current['max']=$sell;
	$latest_exchanges[$rate_type.':'.$currency_id]=array('sell'=>$sell,'buy'=>$buy,'timestamp'=>time(),'min'=>$current['min'],'max'=>$current['max'],'yesterday'=>$current['yesterday']);
	if (!$rate_type){
		$current=$latest_exchanges[$rate_type.':'.$currency_id];
		if ($sell<$current['min'] || !$current['min']) $current['min']=$sell;
		if ($sell>$current['max']) $current['max']=$sell;
		$latest_exchanges_tr[$rate_type.':'.$currency_id]=array('sell'=>$sell,'buy'=>$buy,'timestamp'=>time(),'min'=>$current['min'],'max'=>$current['max'],'yesterday'=>$current['yesterday']);
	}
	db_query('REPLACE INTO exch_rate VALUES ('.$currency_id.','.$rate_type.',NOW(),'.$buy.','.$sell.')');
}
