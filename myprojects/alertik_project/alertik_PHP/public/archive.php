<?php
include "../private/db.php";
include "../private/backend.php";
$count=(int)apc_fetch('mxb'.$_SERVER['REMOTE_ADDR']);
$count++;
if ($count==20) {
	echo 'برای استفاده مجدد 24 ساعت بعد مراجعه نمایید|0';
	exit;
}
apc_store ('mxb'.$_SERVER['REMOTE_ADDR'],$count,43200);
echo retrieve_rate().'|'.(20-$count);
function retrieve_rate(){ 
	$m=(int)$_POST['m'];
	$d=(int)$_POST['d'];
	$y=(int)$_POST['y'];
	if ($m<1||$m>12 || $d<1||$d>31 || $y<1390||$y>1395) return 'تاریخ مشکل دارد';
	list($rate,$currency)=explode('_',$_POST['rate']);
	$rate=(int)$rate;
	$currency=(int)$currency;
	if ($rate<1||$rate>3 || $currency<0 || $currency>100) return 'نرخ انتخابی مشکل دارد';
	list($y,$m,$d)=jalali_to_gregorian($y,$m,$d);
	$rs=db_query('SELECT * FROM exch_archive WHERE addedon <= "'.$y.'-'.$m.'-'.$d.'" AND duration_type = 0 AND rate_type='.$rate.' AND currency_id='.$currency.' ORDER BY addedon DESC LIMIT 1;');
	while ($row=db_array($rs)){
		return 
			//number_format($row['start']*(($currency < 40)?10000:10)).
			number_format($row['end']*(($currency < 40)?10000:10));
			//.number_format($row['min']*(($currency < 40)?10000:10))
			//.number_format($row['max']*(($currency < 40)?10000:10));
	} //else return 'نرخی برای این روز وجود ندارد'; 	
}