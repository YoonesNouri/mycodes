<?php
function create_api(){
	global $latest_exchanges,$today;
	$basic=['0:1'=>'ounce','0:5'=>'silver_ounce','0:6'=>'platinium','0:7'=>'palladium','0:8'=>'light_sweet_crude_oil',
		'3:2'=>'mesghal','3:3'=>'18k_gold','3:10'=>'coin_old','3:11'=>'coin_full','3:12'=>'coin_half',
		'3:13'=>'coin_quarter','3:14'=>'coin_gram',
		'0:40'=>'ex_dollar','0:41'=>'ex_euro','0:42'=>'ex_pound','0:43'=>'ex_derham','0:44'=>'ex_canada_dollar',
		'0:45'=>'ex_yuan','0:46'=>'ex_turkish_lira','0:47'=>'ex_yen','0:48'=>'ex_india_rupee','0:49'=>'ex_australian_dollar',
		'0:50'=>'ex_swedish_kron','0:51'=>'ex_swiss_frank','0:52'=>'ex_norway_kron','0:53'=>'ex_hong_kong_dollar',
		'0:54'=>'ex_ringit','0:55'=>'ex_kuwait_dinare','0:56'=>'ex_iraq_dinar','0:57'=>'ex_bat','0:58'=>'ex_pakestan_rupee',
		'0:61'=>'ex_arabestan_rial','0:62'=>'ex_rubl','0:64'=>'ex_denmark','0:65'=>'ex_korea',
		'13:114'=>'stock',
		'17:40'=>'sana_dollar',
	];

	foreach ($basic as $key => $name) {
		if ($latest_exchanges[$key]['sell']>0) ; else continue;
		if (strstr($key, '0:')||strstr($key, '13:')) $decimal=1; else $decimal=10000;
		$json[$name]=[
			'sell'=>$latest_exchanges[$key]['sell']*$decimal,
			'min'=>(float)$today[$key]['min']*$decimal,
			'max'=>(float)$today[$key]['max']*$decimal,
			'updated'=>$latest_exchanges[$key]['timestamp'],
		];
	}
	$json['timestamp']=time();
	//file_write('../service/arzgooya.json',json_encode($json,JSON_PRETTY_PRINT));
	create_mobile_api();
}
function create_mobile_api(){
	global $latest_exchanges,$today,$before;
	$basic=['0:1'=>'ounce',
		'3:2'=>'mesghal','3:3'=>'18k_gold','3:10'=>'coin_old','3:11'=>'coin_new','3:12'=>'coin_half',
		'3:13'=>'coin_quarter','3:14'=>'coin_gram',
		'3:40'=>'dollar','3:41'=>'euro','3:42'=>'pound','3:43'=>'derham','3:44'=>'canadian_dollar',
		'3:45'=>'yuan','3:46'=>'turkish_lira','3:47'=>'yen',
	];

	foreach ($basic as $key => $name) {
		//if ($latest_exchanges[$key]['sell']>0) ; else continue;
		list($rate_type,$currency_id)=explode(':',$key);
		$price=number_fill($key,$latest_exchanges[$key]['sell'],true);
		$price_before=number_fill($key,$before[$key]['last_sell'],true);
		if ($rate_type==3&&$currency_id>40){
			$price=currency_guess($currency_id);
			$price=number_format($price,decimal_point($price),'.','');
			$price_before=currency_guess($currency_id,'yesterday');
			$price_before=number_format($price_before,decimal_point($price_before),'.','');
		}
		$change=change_perc($price_before,$price ,decimal_point($price),'simple');
		$change_percentage=change_perc($price_before,$price ,decimal_point($price),'percentage')['change'];
		$json[]=[
			'id'=>$name,
			'sell'=>number_format($price,decimal_point($price)),
			'min'=>number_fill($key,$today[$key]['min']),
			'max'=>number_fill($key,$today[$key]['max']),
			'updated'=>$latest_exchanges[$key]['timestamp'],
			'change'=>number_format($change,decimal_point($price)),
			'change_percentage'=>$change_percentage,
		];
	}
	//$json['timestamp']=time();
	file_write('../service/m1.json',json_encode($json));
}
function create_js_update($latest_exchanges,$before){
	//,45=>'یوان چین',46=>'لیره ترکیه',47=>'ین ژاپن',48=>'روپیه هند',49=>'دلار استرالیا',50=>'کرون سوئد',51=>'فرانک سوئیس',52=>'کرون نروژ',53=>'دلار هنگ کنگ',54=>'رینگیت مالزی',55=>'دینار کویت',56=>'دینار عراق',57=>'بات تایلند',58=>'روپیه پاکستان',59=>'مانات آذربایجان',60=>'افغانی',61=>'ریال عربستان',62=>'روبل روسیه',63=>'دلار سنگاپور',64=>'کرون دانمارک',65=>'وون کره جنوبی'

	$basic=['0:1'=>'انس جهانی به دلار','3:2'=>'مثقال طلا در بازار','3:3'=>'گرم طلای 18 در بازار','3:10'=>'سکه بهار آزادی','3:11'=>'سکه امامی','3:12'=>'سکه نیم','3:13'=>'سکه ربع','3:14'=>'سکه گرمی',
		'3:40'=>'دلار','3:41'=>'یورو','3:42'=>'پوند','3:43'=>'درهم امارات','3:44'=>'دلار کانادا'];
	$paid=['0:1'=>'انس جهانی به دلار','3:2'=>'مثقال طلا در بازار','3:3'=>'گرم طلای 18 در بازار','3:10'=>'سکه بهار آزادی','3:11'=>'سکه امامی','3:12'=>'سکه نیم','3:13'=>'سکه ربع','3:14'=>'سکه گرمی',
		'3:40'=>'دلار','17:40'=>'دلار سنا','3:41'=>'یورو','3:42'=>'پوند','3:43'=>'درهم امارات','3:44'=>'دلار کانادا',
		'3:45'=>'یوان چین','3:46'=>'لیره ترکیه','3:47'=>'ین ژاپن','3:48'=>'روپیه هند','3:49'=>'دلار استرالیا','3:50'=>'کرون سوئد','3:51'=>'فرانک سوئیس','3:52'=>'کرون نروژ','3:60'=>'افغانی',
		'3:53'=>'دلار هنگ کنگ','3:54'=>'رینگیت مالزی','3:55'=>'دینار کویت','3:56'=>'دینار عراق','3:57'=>'بات تایلند','3:58'=>'روپیه پاکستان','3:59'=>'مانات آذربایجان','3:61'=>'ریال عربستان','3:62'=>'روبل روسیه','3:63'=>'دلار سنگاپور','3:64'=>'کرون دانمارک','3:65'=>'وون کره جنوبی',
		'0:5'=>'نقره',	'0:47'=>'yen','0:41'=>'euro','0:42'=>'pound','0:43'=>'derham','0:44'=>'canada','0:45'=>'yuan','0:46'=>'lir','13:114'=>'شاخص بورس'
		];

	$basic_data=create_js_data($latest_exchanges,$before,$basic);
	$paid_data=create_js_data($latest_exchanges,$before,$paid);
	$tmp=db_row('SELECT CURDATE() as cur_date,CURTIME() as cur_time;');
	list($y,$m,$d)=to_jalali($tmp['cur_date']);
	$tmp=explode(':',$tmp['cur_time']);
	file_write('../service/p.js',
		'var update="'.$y.'-'.$m.'-'.$d.' '.$tmp[0].':'.$tmp[1].'"; var last='.json_encode($basic_data['last']).';var change='.json_encode($basic_data['change']).';'.file_get_contents('../public/static/webmaster.js').'');
	file_write('../service/e.js',
		'var update="'.$y.'-'.$m.'-'.$d.' '.$tmp[0].':'.$tmp[1].'"; var last='.json_encode($paid_data['last']).';var change='.json_encode($paid_data['change']).';'.file_get_contents('../public/static/web-exclusive.js').'');
}
function create_js_data($latest_exchanges,$before,$currencies){
	foreach ($currencies as $key => $name) {
		list($rate_type,$currency_id)=explode(':',$key);
		$price=number_fill($key,$latest_exchanges[$key]['sell'],true);
		$price_before=number_fill($key,$before[$key]['last_sell'],true);
		if ($rate_type==3&&$currency_id>41){
			$price=currency_guess($currency_id);
			$price=number_format($price,decimal_point($price),'.','');
			$price_before=currency_guess($currency_id,'yesterday');
			$price_before=number_format($price_before,decimal_point($price_before),'.','');
		}
		$out['last'][str_replace(':','_',$key)]=$price;
		if (!$out['change'][str_replace(':','_',$key)]=change_perc($price_before,$price,0,'simple')) $out['change'][str_replace(':','_',$key)]=0;
	}
	return $out;
}