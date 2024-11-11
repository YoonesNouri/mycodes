<?php
include "db.php";
include "backend.php";
include "create-index.ads-data.php";
include "ati-cfg.php";

db_zone();

$latest_exchanges=getJSON('latest_exchanges');
$prev=getJSON('before_ime');
$last_ime=getJSON('last_ime');
if ($daily=getJSON('daily_rates')) {
	$before=$daily['prev'];
	$today=$daily['today'];
}
$page['title']='آتی سکه';
$page['rev']=10;
$page['countdown']=58;//$last_ime['countdown_timer'];
$page['save']='index.html';
$page['ajax']='a';
$page['js']='var pg_countdown='.$page['countdown'].';';


$ad=advertisement('ati');
/*$notice='این بخش به صورت آزمایشی راه اندازی شده است. لطفا نظرات و انتقادات خود را از طریق فرم  "<a href="/contact.php">تماس با ما</a>" ارسال نمایید.<br />
توجه داشته باشید نرخ های قید شده در این صفحه مربوط به بورس کالا بوده و ارتباطی با بازار نقدی ندارد.';*/

aj('earnings',$earnings);
aj('pg_countdown',$page['countdown']);
list($coin_tbl_one,$coin_tbl_two,$countdown,$ati_countdown)=coin_table();
$page['content']=
	'<div id="primaryContent">'
	.'<div'.ajk('notice').'>'.ajv((($notice)?'<div>'.$notice.'</div>':'')).'</div>'	
		.$coin_tbl_one
		.coin_comparision()
		.$coin_tbl_two
		//.adbox()
		.charts_tool()
	.'</div>'
	.'<div id="secondaryContent">'
		.market_table($latest_exchanges,$before,$today)
		.$countdown
		.$ad
		//.ime_iframes()
		.$ati_countdown
	.'</div><div class="clear"></div>'
	.'<div id="ime_box"><iframe frameborder="0" scrolling="no" class="imeframe" src="http://cdn.ime.co.ir/"></iframe></div>'
	;

list($date,$time)=persian_date();
$page['date']='<span class="adt">'.$date.'</span> - <span class="atm">'.$time.'</span>';
aj('atm',$time);
aj('adt',$date);

aj('rev',$page['rev']);
$page['ajax-content-semi']=json_encode(just_diff($ajax,'ati'));
$page['ajax-content-full']=json_encode($ajax);

include "page-ati.php";
function ime_iframes(){
	global $ati_months,$last_ime;
	foreach ($ati_months as $key => $val){
		if (!$last_ime[$key]['sell']) continue;
		$data.=(($data)?'|':'').$val['id'];
		list($tmp)=explode(' ',$val['title']);
		$months.=(($months)?' - ':'').'<a href="#" data-'.$val['id'].'="hide">'.$tmp.'</a>';
	}
	return '<table id="ime" data-m="'.$data.'"><tr class="th"><th>کادر آتی</th><th colspan="2"><a href="#" class="ime_disp">نمایش</a></th></tr>'
		.'<tr class="hide" data-pos="1"><td>محل نمایش</td><td><a href="#" class="selected">پایین</a></td><td><a href="#" data-ime_box="toppos">بالا</a></td></tr>'
		.'<tr class="hide"><td>محل اتصال</td><td><a href="#" class="selected">صفحه</a></td><td><a href="#" data-ime_box="stickbottom" data-freespace="freespace">مرورگر</a></td></tr>'
		.'<tr class="hide"><td>نحوه نمایش</td><td><a href="#" class="selected">کامل</a></td><td><a href="#" data-frameholder="imecomp">کم حجم</a></td></tr>'
		.'<tr class="hide"><td>سایز نمایش</td><td><a href="#" class="selected">کامل</a></td><td><a href="#" data-frameholder="scale">80%</a></td></tr>'
		.'<tr class="hide"><td>شدت نمایش</td><td><a href="#" class="selected">کامل</a></td><td><a href="#" data-frameholder="transparent">محو</a></td></tr>'
		.'<tr class="hide" data-skip="1"><td>عدم نمایش</td><td colspan="2">'.$months.'</td></tr>'
		.'<tr class="hide"><td colspan="3" class="fa">از مرورگر فایرفاکس استفاده نمایید.</td></tr>'
		.'</table>';
}
function charts_tool(){	
	$time=db_array(db_query('SELECT day(NOW()) as day'));
	return '<table id="charts">
	<tr>
		<th>نمودار</th>
		<th>لحظه ای</th>
		<th>روزانه</th>
		<th>ماهانه</th>
	</tr>
	<tr>
		<td class="fa">نمودار خطی</td>
		<td class="chart">
			<a href="http://www.mazanex.com/c/s/linechart-fcoin.png?stamp">سکه تمام</a> | <a href="http://www.mazanex.com/c/s/linechart-dollar.png?stamp">دلار</a> | <a href="http://www.mazanex.com/c/s/linechart-gold.png?stamp">طلا</a>
		</td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td class="fa">کندل استیک <sup> <a href="http://www.mazanex.com/faq.html#candlestick">[؟]</a></sup></td>
		<td></td>
		<td class="chart">
			<a href="http://www.mazanex.com/c/d/candlestick-fcoin.png?'.$time['day'].'">سکه تمام</a> | <a href="http://www.mazanex.com/c/d/candlestick-dollar.png?'.$time['day'].'">دلار</a> | <a href="http://www.mazanex.com/c/d/candlestick-gold.png?'.$time['day'].'">طلا</a>
		</td>
		<td class="chart">
			<a href="http://www.mazanex.com/c/d/candlestick-fcoin-m.png?'.$time['day'].'">سکه تمام</a> | <a href="http://www.mazanex.com/c/d/candlestick-dollar-m.png?'.$time['day'].'">دلار</a> | <a href="http://www.mazanex.com/c/d/candlestick-gold-m.png?'.$time['day'].'">طلا</a>
		</td>
	</tr>
	</table><div class="hide" id="chart_box"></div>';
}

function market_table($latest_exchanges,$before,$today) {
	global $ati_deposite;
	$latest_exchanges['deposite']['sell']=$ati_deposite;
	$key=array(
		//'3:40'=>'دلار آزاد',
		'15:40'=>'دلار بانکی غیر مرجع',
		'0:1'=>'انس',
		'3:2'=>'مثقال طلا',
		'3:3'=>'گرم طلا 18',
		'3:10'=>'سکه بهار آزادی',
		'3:11'=>'سکه امامی',
		'3:12'=>'سکه نیم',
		'3:13'=>'سکه ربع',
		'3:14'=>'سکه گرمی',
		'13:113'=>'صندوق اسپایدر',
		'13:114'=>'شاخص بورس',		
		//'20:40'=>'دلار از روی طلا', //نرخ دلار از روی نرخ طلا در بازار تهران.
		//'3:41'=>'یورو آزاد',
		'deposite'=>'وجه تضمینی',		//مبلغ مورد نیاز برای سپرده گذاری برای انجام معامله هر یک عدد سکه در بورس آتی.
		//'leverage'=>'لوریج <sup><a href="#h10">[10]</a></sup>',		
		);
	$out='<table class="exch_tbl" id="ati_exch"><tr class="legend">
	<th>نرخ</th>
	<th>فروش</th>
	<th class="tip" data-tip="ستون تغییر از روی آخرین نرخ روز قبل کاری محاسبه می شود.">تغییر</th>
	</tr>
	';	
	//<th>کمترین<sup> <a href="#note2">[؟]</a></sup></th>
	//<th>بیشترین<sup> <a href="#note2">[؟]</a></sup></th>
	$regulate_gold_dollar=false;$regulate_market=false;
	foreach ($key as $k => $name){
		if ($k=='3:11') {			
			$latest_exchanges['leverage']['sell']=$latest_exchanges[$k]['sell']/($ati_deposite);
			$before['leverage']['last_sell']=$before[$k]['last_sell']/($ati_deposite);
		}
		list($rate_type,$currency_id)=explode(':',$k);
		if ($currency_id == 113 ||$currency_id == 114 || $currency_id == 1 || $rate_type=='leverage') $decimal=1; elseif($currency_id < 40)  $decimal=10000; else $decimal=10;
		
		$change=change_perc($before[$k]['last_sell']*$decimal,$latest_exchanges[$k]['sell']*$decimal,(($decimal==1)?2:0),'seperated');
		$out.='<tr'.((++$number%2)?'':' class="even"').'><td class="fa">'.$name.'</td>'.
		'<td'.ajk('s'.$k).'>'.ajv(number_format($latest_exchanges[$k]['sell']*$decimal,(($decimal==1)?2:0))).'</td>'.
		'<td'.ajk('c'.$k,$change['class']).'>'.ajv($change['change']).'</td>';
		//'<td'.ajk('l'.$k).'>'.ajv(number_format(round($today[$k]['min'])*$decimal)).'</td>'.
		//'<td'.ajk('h'.$k).'>'.ajv(number_format(round($today[$k]['max'])*$decimal)).'</td></tr>';
	}

	return $out.'</table>';
}
function coin_comparision(){
	global $prev,$last_ime,$ati_months;
	$header='<table id="coin_comp"><tr>
	<th class="first" data-tip="میزان سود و یا زیان ناشی از آفست کردن بین ماه های آتی." class="tip">نسبت سکه آتی</th>';
	foreach ($ati_months as $code => $val){
		if (!$last_ime[$code]['sell']) continue;
		$header.='<th>'.$val['title'].'</th>';
	}	
	$header.='</tr>';
	
	foreach ($ati_months as $code_y => $val_y){
		if (!$last_ime[$code_y]['sell']) continue;
		$data.='<tr>';
		foreach ($ati_months as $code_x => $val_x){
			if (!$last_ime[$code_x]['sell']) continue;
			list(,$month_y)=explode('_',$code_y);
			list(,$month_x)=explode('_',$code_x);
			$change=change_perc($last_ime[$code_x]['sell']*10000,$last_ime[$code_y]['sell']*10000,0,'seperated');
			$month.=((!$month)?'<td>'.$val_y['title'].'</td>':'').'<td'.ajk('cc'.$code_y.$code_x,$change['class']).'>'.ajv($change['change']).'</td>';
		}	
		$data.=$month.'</tr>';
		$month=null;
	}	
	return $header.$data.'</table>';
}

function coin_table() {
	global $prev,$before,$latest_exchanges,$last_ime,$today,$ati_months,$ati_deposite;
	$key['3:11']='نقدی';
	foreach ($ati_months as $code => $val){
		$key[$code]=$val['title'];
	}
	$key['cgold']='بر اساس طلا';
	//$key['cdollar']='بر اساس دلار';

	$part_one='<table id="ati_tbl"><tr>
		<th class="first">نرخ سکه تمام</th>
		<th>قیمت</th>
		<th>تغییر<sup> <a href="#h1">[1]</a></sup></th>
		<th data-tip="بیشترین و کمترین قیمت ثبت شده طی روز اخیر." class="tip">کمترین</th>
		<th data-tip="بیشترین و کمترین قیمت ثبت شده طی روز اخیر." class="tip">بیشترین</th>	
		<th>اختلاف با نقدی <sup></sup></th>	
		<th data-tip="تعداد روز باقی مانده تا سررسید قرارداد آتی." class="tip">تا سررسید</th>	
		<th data-tip="نرخ فعلی تسویه سکه (حجم معاملات / ارزش معاملات). برای کنترل احتمال کال مارجین شدن در انتهای معاملات." class="tip">نرخ تسویه</th>
		<th data-tip="تعداد سکه معامله شده روز تا این لحظه." class="tip">حجم</th>
	</tr>';

	$part_two='<table id="ati_tbl_ext">
	<tr>
		<th rowspan="2" class="first">سکه آتی تکمیلی</th>
		<th colspan="2" data-tip="محاسبه سود و زیان ناشی از بستن پوزیشن فعلی برای رفع تحویل در آتی با بازار نقدی." class="tip">آفست نقدی</th>	
		<th colspan="2" data-tip="سود حاصل از میزان سرمایه گذاری به میزان وجه تضمینی در شرایط حال در مقایسه با بازار نقدی. (اختلاف سکه آتی و نقدی در لوریج)" class="tip">سود وجه تضمینی</th>	
		<th colspan="2" data-tip="میزان سود و یا زیان  کم ریسک حاصل از خرید در بازار آتی و فروش در بازار نقدی. در مقایسه با سرمایه مورد نیاز." class="tip">آربیتراژ خرید</th>	
		<th colspan="2" data-tip="میزان سود و یا زیان  کم ریسک حاصل از فروش در بازار آتی و خرید در بازار نقدی. در مقایسه با سرمایه مورد نیاز." class="tip">آربیتراژ فروش</th>	
		<th rowspan="2" data-tip="بزرگی سرمایه قابل کنترل در هر یک از ماه های آتی به نسبت سرمایه گذاری نقدی انجام شده به شکل وجه تضمینی." class="tip">لوریج</th>	
	</tr>
	<tr>
		<th>ماه</th><th>سال</th>	
		<th>ماه</th><th>سال</th>	
		<th>ماه</th><th>سال</th>	
		<th>ماه</th><th>سال</th>		
	</tr>
	';

	foreach ($key as $currency_id => $name){
		$profit_change='-';
		$min='-';
		$max='-';		
		$since='-';
		$balance_closed='-';
		$volume='-';
		if ($currency_id=='3:11') {
			$market_price=$sell=$latest_exchanges['3:11']['sell']*10000;
			$sell_yesterday=$before['3:11']['last_sell']*10000;
			$min=$today['3:11']['min']*10000;
			$max=$today['3:11']['max']*10000;
		} elseif (stristr($currency_id,'139')){
			list($year,$month)=explode('_',$currency_id);
			$sell=$last_ime[$currency_id]['sell']*10000;
			$sell_yesterday=$prev[$currency_id]['sell']*10000;
			
			$min=$last_ime[$currency_id]['min']*10000;
			$max=$last_ime[$currency_id]['max']*10000;
			
			/*		Esfand 21 instead of 26		*/
			if ($month==12) $end_day=22; else $end_day=27;
			
			list($y,$m,$d)=jalali_to_gregorian($year,$month,$end_day);
			$since=db_array(db_query('SELECT TIMESTAMPDIFF(DAY,NOW(),"'.$y.'-'.$m.'-'.$d.' 00:00:00") as days;'));			
			$since=$since['days'];
			if (!$balance_closed=$last_ime[$currency_id]['close_balance']) $balance_closed='-';
			
			$profit_change=change_perc($market_price,$sell,0,'percentage');
			$offset_month=number_format(($profit_change['change']/$since)*30,2).'%';
			$offset_year=number_format(($profit_change['change']/$since)*365,2).'%';
			
			$profit_change=change_perc($ati_deposite*10000,$sell-$market_price+$ati_deposite*10000,0,'percentage');
			$return_on_margin_month=number_format(($profit_change['change']/$since)*30,2).'%';
			$return_on_margin_year=number_format(($profit_change['change']/$since)*365,2).'%';
			list($tmp)=explode(' ',$ati_months[$currency_id]['title']);
			$ati_countdown.=countdown('قیمت '.$tmp,$last_ime[$currency_id]['timestamp'],'t'.$currency_id,'compact');
			
			$buy_arbitaj=(($market_price-$sell)*100/($market_price-$ati_deposite*10000));
			$sell_arbitaj=((($sell-$market_price)*100/($market_price+$ati_deposite*10000)));
			$buy_arbitaj_year=number_format($buy_arbitaj/($since/365),2).'%';
			$buy_arbitaj_month=number_format($buy_arbitaj/($since/30),2).'%';
			$sell_arbitaj_month=number_format($sell_arbitaj/($since/30),2).'%';
			$sell_arbitaj_year=number_format($sell_arbitaj/($since/365),2).'%';
			$volume=number_format($last_ime[$currency_id]['volume']*10);
		} elseif ($currency_id=='cgold') {
			$sell=$latest_exchanges['4:11']['sell']*10000;
			$sell_yesterday=$before['4:11']['last_sell']*10000;
		} elseif ($currency_id=='cdollar') {
			$sell=round((($latest_exchanges['0:1']['sell']/41.47)*9.76)*$latest_exchanges['3:40']['sell'])*10;
			$sell_yesterday=round((($before['0:1']['last_sell']/41.47)*9.76)*$before['3:40']['last_sell'])*10;
		}
		if (!$sell) continue;
		if ($min=='0') $min=$sell; 
		if ($min!='-') $min=number_format($min);
			
		if ($max=='0') $max=$sell; 
		if ($max!='-') $max=number_format($max);

		if ($balance_closed!='-') $balance_closed=number_format($balance_closed);
		
		$change=change_perc($sell_yesterday,$sell,0,'seperated');
		$market_diff=change_perc($market_price,$sell,0,'seperated');
		
		$part_one.='<tr><td>'.$name.'</td>'.
		'<td'.ajk('s'.$currency_id).'>'.ajv(number_format($sell)).'</td>'.
		'<td'.ajk('c'.$currency_id,$change['class']).'>'.ajv($change['change']).'</td>'.
		'<td'.ajk('l'.$currency_id).'>'.ajv($min).'</td>'.
		'<td'.ajk('h'.$currency_id).'>'.ajv($max).'</td>'.
		'<td'.ajk('cp'.$currency_id,$market_diff['class']).'>'.ajv($market_diff['change']).'</td>'.
		'<td'.ajk('px'.$currency_id).'>'.ajv($since).'</td>'.		
		'<td'.ajk('xs'.$currency_id).'>'.ajv($balance_closed).'</td>'.
		'<td'.ajk('xv'.$currency_id).'>'.ajv($volume).'</td>'.
		'</tr>';

		if (stristr($currency_id,'139')) $part_two.='<tr><td>'.$name.'</td>'.
		'<td'.ajk('cxm'.$currency_id,class_color($offset_month)).'>'.ajv($offset_month).'</td>'.
		'<td'.ajk('cxy'.$currency_id,class_color($offset_year)).'>'.ajv($offset_year).'</td>'.
		'<td'.ajk('cym'.$currency_id,class_color($return_on_margin_month)).'>'.ajv($return_on_margin_month).'</td>'.
		'<td'.ajk('cyy'.$currency_id,class_color($return_on_margin_year)).'>'.ajv($return_on_margin_year).'</td>'.
		'<td'.ajk('csm'.$currency_id,class_color($buy_arbitaj_month)).'>'.ajv($buy_arbitaj_month).'</td>'.
		'<td'.ajk('csy'.$currency_id,class_color($buy_arbitaj_year)).'>'.ajv($buy_arbitaj_year).'</td>'.
		'<td'.ajk('cbm'.$currency_id,class_color($sell_arbitaj_month)).'>'.ajv($sell_arbitaj_month).'</td>'.
		'<td'.ajk('cby'.$currency_id,class_color($sell_arbitaj_year)).'>'.ajv($sell_arbitaj_year).'</td>'.
		'<td'.ajk('lv'.$currency_id).'>'.ajv(number_format($sell/($ati_deposite*10000),1)).'</td>'.
		'</tr>';
	}
	;
	
	$countdowns=//countdown('دلار',$latest_exchanges['3:40']['timestamp'],'t40','compact')
		countdown('سکه نقدی',$latest_exchanges['3:11']['timestamp'],'t11','compact')
		.countdown('انس',$latest_exchanges['0:1']['timestamp'],'t01','compact');
	return array($part_one.'</table>',$part_two.'</table>',$countdowns,$ati_countdown);
}
function class_color($i){
	if (!$i) return;
	if (stristr($i,'-')) return 'neg';
	return 'pos';
}
function adbox(){
	return '<table>
    <tbody><tr><th colspan="2">معرفی سرویس پیامک<a href="http://www.bemoghe.com"> ”بموقع“</a></th></tr>
    <tr>
        <td width="50%">دریافت آخرین قیمت ها با ارسال پیامک</td>
        <td>دریافت پیامک در زمان و یا نرخ مشخص</td>
    </tr>
    <tr>
        <td class="fa">با ارسال هر یک از کلمات کلیدی داخل پرانتز زیر به شماره <b>10007880</b> آخرین نرخ مربوط به آن کلمه کلیدی را از طریق پیامک دریافت نمایید.<br>برای آزمایش سرویس دو عدد پیامک به صورت رایگان پاسخ داده میشود.
		<div class="alert alert-info" style="margin:10px 0">نرخ های آتی (<b>ati</b>)، قیمت سکه (<b>tamam</b>)، نیم (<b>nim</b>)، آزادی (<b>azadi</b>)، ربع (<b>rob</b>)، گرمی (<b>gram</b>)، دلار (<b>dollar</b>)، مثقال (<b>mesghal</b>)، انس (<b>ounce</b>)، بورس اوراق بهادار (<b>brouse</b>)، صندوق اسپایدر (<b>spider</b>)، ...</div>
		</td>
        <td class="fa">شما میتوانید نرخ های مورد نظر خود را در بازه های زمانی و یا ساعات مشخص از طریق پیامک به صورت خودکار دریافت کنید. و یا با مشخص نمودن میزان تغییر و یا رقم خاص در صورت رسیدن نرخ  انتخابی به آن رقم از طریق پیامک مطلع شوید.<br />به طور مثال برای نرخ دلار میزان 200 ریال تغییرات را مشخص نموده اید. قیمت فعلی دلار 20,000 ریال باشد در صورت رسیدن قیمت به 20,200 ریال یک پیامک و مجددا بعد از رسیدن نرخ به 20,400 مجددا یک پیامک دیگر دریافت می نمایید. این روند تا غیر فعال کردن گزینه انتخابی و یا اتمام اشتراک شما به صورت خودکار ادامه خواهد داشت.</td>
    </tr>
    <tr>
        <td class="fa alert" style="margin:10px 0" colspan="2"> توجه داشته باشید دریافت پیامک از این سرویس فقط برای خطوطی که عدم دریافت پیامک تبلیغاتی فعال نشده باشد امکان پذیر می باشد. به دلیل مشکل اپراتورهای خطوط پیامک، ارسال پیامک به گوشی شما از روی خطوط دیگری صورت میگیرد. <br />برای اطلاعات بیشتر به وب سایت <a href="http://www.bemoghe.com"> ”بموقع“</a> مراجعه نمایید.</td>
    </tr>
</tbody></table>';
}