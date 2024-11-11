<?php
function last($type,$currency_id,$key=''){
	static $latest_exchanges;
	if (!is_array($latest_exchanges)) $latest_exchanges=get_json('latest_exchanges');
	$current=$latest_exchanges[$type.':'.$currency_id];
	if (!$key) return $current;
	$current=$current[$key];
	if ($key=='sell'||$key=='buy'||$key=='yesterday'||$key=='min'||$key=='max') {
		$current=price_normalize($type,$currency_id,$current);
	}
	return $current;
}
function currency_guess($currency_id,$date='sell'){
	$dollar_time=last(3,40,'timestamp');
	$price=last(6,$currency_id,$date);
	$currency=last(3,$currency_id,$date);
	if ($currency_id==40) return $currency;
	$currency_time=last(3,$currency_id,'timestamp');
	/* 	If currency is less than 20min behind dollar OR If exch rate older than currency OR exch_rate is not available		*/
	if ($date=='sell'){
		if ((($dollar_time-$currency_time)/60)<20||$dollar_time<$currency_time) return $currency;
	}
	if ($modifier=get_json('exch_diff')[$currency_id]) {
		$price+=$price*$modifier;
	}
	return $price;
}
function past_price($rate_type,$currency_id,$days_before){
	$price=db_row('SELECT sell FROM exch_rate WHERE rate_type='.$rate_type.' AND currency_id='.$currency_id.' AND TIMESTAMPDIFF(DAY,addedon,NOW())>'.($days_before-1).' ORDER BY addedon DESC LIMIT 1;')['sell'];
	return price_normalize($rate_type,$currency_id,$price);
}
function price_normalize($rate_type,$currency_id,$price){
	if ($rate_type==13);
	elseif ($rate_type==3&&$currency_id<40)  $price=$price*10000;
	elseif ($rate_type) $price=$price*10;
	return $price;
}
function decimal_point($price){
	if ($price<10) return 4;
	elseif ($price<100) return 3;
	elseif ($price<3000) return 2;
	//elseif ($price<10000) return 1;
	return 0;
}
function notice_simple($msg,$type){
	//alert'.(($type)?' alert-'.$type:'').'
  	return $msg;
}
function number_fill($key,$price,$raw=false){
	list($rate_type,$currency_id)=explode(':',$key);
	$zero=1;
	if ($rate_type==0) {
		$zero=1;
	}
	if ($rate_type==3||$rate_type==17) {
		$zero=10000;
		if ($currency_id>=40) $zero=10;
	}
	$price=$price*$zero;
	if ($raw) return number_format($price,decimal_point($price),'.','');
	return number_format($price,decimal_point($price));
}
function number_form($price,$decimal_part=4){
	list($decimal)=explode('.',$price.'.');
	$number=$decimal_part-strlen($decimal);
	if ($number<1) $number=0;
	return number_format($price,$number);
}
function change_perc($before,$now,$decimal=0,$type='html'){
	if (!$before) {
		if ($type=='html') return '<span>(0.00%) 0</span>';
		if ($type=='seperated') return array('change'=>'(0.00%) 0');
		if ($type=='percentage') return array('change'=>'0.00');
		return;
	}
	$perc=number_format((($now*100)/$before)-100,2);
	$change=$now-$before;
	if ($change<0) $class='neg'; elseif ($change>0) $class='pos';
	$formated_change=number_format($change,$decimal);

	if ($type=='html') return '<span'.(($class)?' class="'.$class.'"':'').'>('.$perc.'%) '.$formated_change.'</span>';
	if ($type=='img') return array('('.$perc.'%)  '.$formated_change,$class);
	if ($type=='img:percentage') return array($perc,$class);
	if ($type=='simple') return str_replace(',','',$formated_change);
	if ($type=='seperated') return array('change'=>'('.$perc.'%) '.$formated_change,'class'=>$class);
	if ($type=='percentage') return array('change'=>$perc,'class'=>$class);
}

function advertisement($ad_division=null){
	global $ads,$ads_display;
	if ($tmp=getCFG('adv_code'.$ad_division)) $adv_setting=json_decode($tmp,true);
	$adv_setting['code']--;
	if ($adv_setting['code']>0) { //Keep ads more than one minutes
		setCFG('adv_code'.$ad_division,json_encode($adv_setting));
		return $adv_setting['ad'].'<a href="'.(($ad_division)?'//www.mazanex.com':'').'/advertisements.html" class="notes">نمایش تمام آگهی ها</a>';
	}
	$adv_setting['number']++;
	if ($adv_setting['number']>=count($ads_display)) $adv_setting['number']=0;

	$selected_ad=$ads_display[$adv_setting['number']];
	$ads=$ads[$selected_ad[0]];
	$adv_setting['code']=$selected_ad[1];
	if (strstr($ads['img'],'//')) {
		$img_url=$ads['img'];
	} elseif ($ad_division){
		$img_url='//www.mazanex.com'.$ads['img'];
	} else {
		$img_url=$ads['img'];
	}
	$adv_setting['ad']='<br /><div class="ad">'.aj('ad','<a href="'.$ads['url'].'" target="_blank"><img src="'.$img_url.'" alt="AD" /></a>').'</div>';
	setCFG('adv_code'.$ad_division,json_encode($adv_setting));
	return $adv_setting['ad'].'<a href="'.(($ad_division)?'//www.mazanex.com':'').'/advertisements.html" class="notes">نمایش تمام آگهی ها</a>';
}
function just_diff($ajax,$uniq=null) {
	if ($tmp=getCFG('ajax_prev'.$uniq)) $prev_ajax=json_decode($tmp,true);
	if ($tmp=getCFG('ajax_prev_change'.$uniq)) $change=json_decode($tmp,true);
	foreach($ajax as $key => $value){
		$change[$key]++;
		/*
			If value changed in comparision to the previous value
			If variable belong to counter
			If change reach to 10000 to reset it
			continue
		*/
		if ($value!=$prev_ajax[$key]||$change[$key]==10000||$key[0]=='t') {
			/*		For countdown just add new partial update if the its updated less than 100 seconds ago		*/
			if ($key[0]!='t' || $key=='tt' || $value<70) {
				$diff[$key]=$value;
				$change[$key]=0;
			}
		}
		/*		Resend values over time incase of ajax failure, after 1, 10 and 20 minutes		*/
		if ($change[$key]==5) { // || $change[$key]==10 || $change[$key]==20
			$diff[$key]=$value;
		}
	}
	if (!is_array($diff))$diff['z']=0;
	setCFG('ajax_prev'.$uniq,json_encode($ajax));
	setCFG('ajax_prev_change'.$uniq,json_encode($change));
	return $diff;
}
function new_just_diff($ajax,$uniq=null) {
	if ($tmp=get_cfg('ajax_prev'.$uniq)) $prev_ajax=json_decode($tmp,true);
	if ($tmp=get_cfg('ajax_prev_change'.$uniq)) $change=json_decode($tmp,true);
	foreach($ajax as $key => $value){
		$change[$key]++;
		/*
			If value changed in comparision to the previous value
			If variable belong to counter
			If change reach to 10000 to reset it
			continue
		*/
		if ($value!=$prev_ajax[$key]||$change[$key]==10000||$key[0]=='t') {
			/*		For countdown just add new partial update if the its updated less than 100 seconds ago		*/
			if ($key[0]!='t' || $key=='tt' || $value<70) {
				$diff[$key]=$value;
				$change[$key]=0;
			}
		}
		/*		Resend values over time incase of ajax failure, after 1, 10 and 20 minutes		*/
		if ($change[$key]==5) { // || $change[$key]==10 || $change[$key]==20
			$diff[$key]=$value;
		}
	}
	if (!is_array($diff))$diff['z']=0;
	set_cfg('ajax_prev'.$uniq,json_encode($ajax));
	set_cfg('ajax_prev_change'.$uniq,json_encode($change));
	return $diff;
}
function persian_date(){
	$row=db_array(db_query('SELECT YEAR(NOW()) as y, MONTH(NOW()) as m, DAY(NOW()) as d, DAYOFWEEK(NOW()) as w_d, HOUR(NOW()) as h,MINUTE(NOW()) as min '));
	list($y,$m,$d)=gregorian_to_jalali($row['y'],$row['m'],$row['d']);
	$m=jalali_month($m);
	$week_day=persian_day($row['w_d']);
	if (strlen($row['h'])==1)$row['h']='0'.$row['h'];
	if (strlen($row['min'])==1)$row['min']='0'.$row['min'];
	return array($week_day.' '.$d.' '.$m.' '.$y,$row['h'].':'.$row['min']);
}
function turkish_date(){
	$row=db_array(db_query('SELECT YEAR(NOW()) as y, MONTH(NOW()) as m, DAY(NOW()) as d, DAYOFWEEK(NOW()) as w_d, HOUR(NOW()) as h,MINUTE(NOW()) as min '));
	list($y,$m,$d)=array($row['y'],$row['m'],$row['d']);
	$m=turkish_month($m);
	$week_day=turkish_day($row['w_d']);
	if (strlen($row['h'])==1)$row['h']='0'.$row['h'];
	if (strlen($row['min'])==1)$row['min']='0'.$row['min'];
	return array($d.' '.$m.' '.$y.' '.$week_day,$row['h'].':'.$row['min']);
}
function countdown_tr($name='',$timestamp,$class="",$displaytype=null){
	if (!$timestamp) return;
	$time=time()-$timestamp;
	$str=second_to_readable_format_tr($time);
	if ($class) aj($class,$time);
	if ($name=='notext')
		return '<div class="counter'.(($class)?' '.$class:'').'" data-time="'.$time.'"><span>'.$str.'</span></div>';
	else
		return '<div class="counter'.(($class)?' '.$class:'').'" data-time="'.$time.'">Son değişiklik\'dan '.$name.' <span>'.$str.'</span></div>';
}
function countdown($name='',$timestamp,$class="",$displaytype=null){
	if (!$timestamp) return;
	$time=time()-$timestamp;
	$str=second_to_readable_format($time);
	if ($class) aj($class,$time);
	if ($name=='notext')
		return '<div class="counter'.(($class)?' '.$class:'').'" data-time="'.$time.'"><span>'.$str.'</span></div>';
	elseif ($displaytype=='compact')
		return '<div class="counter'.(($class)?' '.$class:'').'" data-time="'.$time.'">از تغییر '.$name.' <span>'.$str.'</span></div>';
	else
		return '<div class="counter'.(($class)?' '.$class:'').'" data-time="'.$time.'">آخرین تغییر '.$name.' <span>'.$str.'</span> قبل صورت گرفته است.</div>';
}
function second_to_readable_format_tr($time){
	if ($day=floor($time/(60*60*24))){
		$str=$day.' gün, ';
		$time=$time-($day*60*60*24);
	}
	if ($hour=floor($time/(60*60))){
		$str.=$hour.' saat, ';
		$time=$time-($hour*60*60);
	}
	if ($minute=floor($time/(60))){
		$str.=$minute.' dakika, ';
		$time=$time-($minute*60);
	}
	return $str.$time.' saniye ';
}
function second_to_readable_format($time){
	if ($day=floor($time/(60*60*24))){
		$str=$day.' روز، ';
		$time=$time-($day*60*60*24);
	}
	if ($hour=floor($time/(60*60))){
		$str.=$hour.' ساعت، ';
		$time=$time-($hour*60*60);
	}
	if ($minute=floor($time/(60))){
		$str.=$minute.' دقیقه و ';
		$time=$time-($minute*60);
	}
	return $str.$time.' ثانیه ';
}
function just_data($ajax){
	unset($ajax['ad']);
	unset($ajax['notice']);
	unset($ajax['earnings']);
	unset($ajax['pg_countdown']);
	return $ajax;
}
function aclick_ads($site_id,$number=1){
	$file='../../aclick/public/get-ads/'.$site_id.'.json';
	if (!file_exists($file)) return;
	$ads=json_decode(file_get_contents($file),true);
	foreach ($ads as $ad_index=>$ad){
		for ($i=0; $i <$number ; $i++) {
			$out[$ad_index][]=$ad[array_rand($ad)];
		}
	}
	return $out;
}
function ajk($key,$additional_class=null){
	global $ajax_key_reserved;
	$key=str_replace(':','_',$key);
	$ajax_key_reserved=$key;
	if ($additional_class) return ' class="'.$key.' '.$additional_class.'"';
	return ' class='.$key;
}
function ajv($value){
	global $ajax_key_reserved;
	return aj($ajax_key_reserved,$value);
}
function aj($key,$value){
	global $ajax;
	/*
		$key	Means
		c		change
		s		sell
		b		buy
		l		min
		h		max
		t		seconds from last update
	*/
	$key=str_replace(':','_',$key);
	return $ajax[$key]=$value;
}
function to_jalali($str,$string=false){
	list($date,$time)=explode(' ',$str);
	$t=explode('-',$date);
	list($y,$m,$d)=gregorian_to_jalali($t[0],$t[1],$t[2]);
	if (!$string) return array($y,$m,$d,$time);
	return $time.' '.$y.'/'.$m.'/'.$d;
}
// \"jalali.php\" is convertor to and from Gregorian and Jalali calendars.
// Copyright (C) 2000  Roozbeh Pournader and Mohammad Toossi
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// A copy of the GNU General Public License is available from:
//
//    http://www.gnu.org/copyleft/gpl.html
//


function div($a,$b) {
    return (int) ($a / $b);
}

function gregorian_to_jalali ($g_y, $g_m, $g_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);





   $gy = $g_y-1600;
   $gm = $g_m-1;
   $gd = $g_d-1;

   $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400);

   for ($i=0; $i < $gm; ++$i)
      $g_day_no += $g_days_in_month[$i];
   if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0)))
      /* leap and after Feb */
      $g_day_no++;
   $g_day_no += $gd;

   $j_day_no = $g_day_no-79;

   $j_np = div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
   $j_day_no = $j_day_no % 12053;

   $jy = 979+33*$j_np+4*div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */

   $j_day_no %= 1461;

   if ($j_day_no >= 366) {
      $jy += div($j_day_no-1, 365);
      $j_day_no = ($j_day_no-1)%365;
   }

   for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
      $j_day_no -= $j_days_in_month[$i];
   $jm = $i+1;
   $jd = $j_day_no+1;

   return array($jy, $jm, $jd);
}

function jalali_to_gregorian($j_y, $j_m, $j_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);



   $jy = $j_y-979;
   $jm = $j_m-1;
   $jd = $j_d-1;

   $j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4);
   for ($i=0; $i < $jm; ++$i)
      $j_day_no += $j_days_in_month[$i];

   $j_day_no += $jd;

   $g_day_no = $j_day_no+79;

   $gy = 1600 + 400*div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
   $g_day_no = $g_day_no % 146097;

   $leap = true;
   if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
   {
      $g_day_no--;
      $gy += 100*div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */
      $g_day_no = $g_day_no % 36524;

      if ($g_day_no >= 365)
         $g_day_no++;
      else
         $leap = false;
   }

   $gy += 4*div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
   $g_day_no %= 1461;

   if ($g_day_no >= 366) {
      $leap = false;

      $g_day_no--;
      $gy += div($g_day_no, 365);
      $g_day_no = $g_day_no % 365;
   }

   for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
      $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
   $gm = $i+1;
   $gd = $g_day_no+1;

   return array($gy, $gm, $gd);
}
function gregorian_month($i){
	$months=array(1=>'ژانویه',
	2=>'فوریه',
	3=>'مارس',
	4=>'آوریل',
	5=>'می',
	6=>'ژوئن',
	7=>'ژوئیه',
	8=>'اوت',
	9=>'سپتامبر',
	10=>'اکتبر',
	11=>'نوامبر',
	12=>'دسامبر');
	return $months[$i];
}
function turkish_month($i){
	$months=array(1=>'Ocak',
	2=>'Şubat',
	3=>'Mart',
	4=>'Nisan',
	5=>'Mayıs',
	6=>'Haziran',
	7=>'Temmuz',
	8=>'Ağustos',
	9=>'Eylül',
	10=>'Ekim',
	11=>'Kasım',
	12=>'Aralık');
	return $months[$i];
}
function persian_day($i){
	$days=array(
		1=>'یک شنبه',
		2=>'دو شنبه',
		3=>'سه شنبه',
		4=>'چهار شنبه',
		5=>'پنج شنبه',
		6=>'جمعه',
		7=>'شنبه'
	);
	return $days[$i];
}
function turkish_day($i){
	$days=array(
		1=>'Pazar',
		2=>'Pazartesi',
		3=>'Salı',
		4=>'Çarşamba',
		5=>'Perşembe',
		6=>'Cuma',
		7=>'Cumartesi'
	);
	return $days[$i];
}
function jalali_month($i){
	$months=array(1=>'فروردین',
	2=>'اردیبهشت',
	3=>'خرداد',
	4=>'تیر',
	5=>'مرداد',
	6=>'شهریور',
	7=>'مهر',
	8=>'آبان',
	9=>'آذر',
	10=>'دی',
	11=>'بهمن',
	12=>'اسفند');
	return $months[$i];
}
