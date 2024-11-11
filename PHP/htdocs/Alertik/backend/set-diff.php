<?php
include 'rc.php';
$currencies=[41=>'یورو',42=>'پوند',43=>'درهم امارات',44=>'دلار کانادا',45=>'یوان چین',46=>'لیره ترکیه',47=>'ین ژاپن',48=>'روپیه هند',49=>'دلار استرالیا',50=>'کرون سوئد',51=>'فرانک سوئیس',52=>'کرون نروژ',53=>'دلار هنگ کنگ',54=>'رینگیت مالزی',55=>'دینار کویت',56=>'دینار عراق',57=>'بات تایلند',58=>'روپیه پاکستان',59=>'مانات آذربایجان',60=>'افغانی',61=>'ریال عربستان',62=>'روبل روسیه',63=>'دلار سنگاپور',64=>'کرون دانمارک',65=>'وون کره جنوبی'];
if ($_POST['save']) save_diff($currencies,$_POST);
echo theme([10=>'diff',0=>'head',20=>'foot'],['currencies'=>currencies_table($currencies)],'theme/backend');

function currencies_table($currencies){
	$exch_diff=get_json('exch_diff');	
	foreach($currencies as $currency_id => $name){
		$price=last(3,$currency_id,'sell');
		$uni_price=0;
		$change=0;
		if (last(0,$currency_id,'sell')) $uni_price=last(3,40,'sell')/last(0,$currency_id,'sell');
		if ($price) $change=1-($uni_price*1/$price);//*100000;

		$out['data'][]=[
			'currency'=>$name,
			'last_price'=>number_format($price),
			'dollar_based'=>number_format($uni_price),
			'timestamp'=>number_format((time()-last(3,$currency_id,'timestamp'))/60/60).'h',
			'previous'=>$exch_diff[$currency_id],
			'change'=>number_format($change,6),
			'diff'=>'<input name="'.$currency_id.'" value="" size="5">',
			];			 
	}
	$out['captions']=[
		'currency'=>['name'=>'ارز'],
		'timestamp'=>['name'=>'زمان'],
		'last_price'=>['name'=>'آخرین قیمت'],
		'dollar_based'=>['name'=>'قیمت بر اساس دلار'],
		'previous'=>['name'=>'اختلاف قبلی'],
		'change'=>['name'=>'اختلاف جدید'],
		'diff'=>['name'=>'ثبت اختلاف'],
	];
	return $out;	
}
function save_diff($currencies,$i){
	$exch_diff=get_json('exch_diff');
	foreach($currencies as $currency_id => $name){
		$change=$i[$currency_id];
		if ($change==='') continue;
		$exch_diff[$currency_id]=$change;
	}
	set_json('exch_diff',$exch_diff);	
}