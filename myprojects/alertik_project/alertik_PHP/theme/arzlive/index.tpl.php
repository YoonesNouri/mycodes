<div class="row">
	<div class="col8">
		<div class="line"></div><h2>نرخ بازار</h2>
		<div id="" class="box"><?= main_table($latest_exchanges) ?></div>
		<?php /*<div class="ad adv dad1" data-ad='<?=aj('dad1_ad',$ads[1])?>'><div></div></div>*/ ?>

		<div class="line"></div><h2>نمودار</h2>
		<div class="box  charts">
			<div class="chart"><div class="title">سکه به ریال</div><a href="/c/linechart-fcoin.png"<?= ajk('kcoin')?> target="_blank"><?= ajv(arzlive_chart('coin',last(3,11,'timestamp'))) ?></a></div>&nbsp;
			<div class="chart"><div class="title">طلا به ریال</div><a href="/c/linechart-gold.png"<?= ajk('kgold')?> target="_blank"><?= ajv(arzlive_chart('gold',last(3,3,'timestamp'))) ?></a></div>&nbsp;
			<div class="chart"><div class="title">دلار به ریال</div><a href="/c/linechart-dollar.png" <?= ajk('kdollar')?> target="_blank"><?= ajv( arzlive_chart('dollar',last(3,40,'timestamp'))) ?></a></div>&nbsp;
			<span class="stretch"></span>
		</div>

		<div class="line"></div><h2>نرخ سایر ارزها</h2>
		<div class="box">
			<div class="row">
				<div class="col6"><?= exchange_table_left() ?></div>
				<div class="col6 collast"><?= exchange_table_right() ?></div>
				<div class="clear"></div>
			</div>
		</div>

		<div class="line"></div><h2>سرمایه گذاری</h2>
		<div id="" class="box"><?= investment_table()?><div class="small_note">بازده سرمایه گذاری ده میلیون ریالی در بازه های زمانی متفاوت.</div></div>

		<div class="line"></div><h2>تبدیل نرخ ها</h2>
		<?= $conversation_tool ?>

	</div>
	<div class="col4 collast">
		<div class="line"></div><h2>اخبار اقتصادی</h2>
		<div id="news_box" <?= ajk('n1','box')?>><?= ajv(news_links($news_list)) ?></div>

		<div class="line"></div><h2>سود و زیان</h2>
		<div id="" class="box"><?= today_profit_loss_table()?></div>

		<?php /* <div><a href="/app.html"><img style="width:310px;height:99px;margin:0 auto;display:block" src="/s/android-ad.png"></a></div> */ ?>
		<div>به کمک <b><a href="https://bab.ir">فروشگاه ساز باب</a></b> محصولات خود را بر روی اینترنت وابسته به قیمت ارز به فروش برسانید.</div>
		<br>
		<div class="ad adv pad2"><?=aj('pad2',$ads[4][0][0])?></div>

		<?php /*<div class="line"></div><h2>اخبار منتخب</h2>
		<div id="news_box" <?= ajk('n2','box')?>><?= ajv(news_links($promoted_news_list)) ?></div> */ ?>

		<div class="line"></div><h2>بورس و آتی</h2>
		<div id="" class="box"><?= misc_table($latest_exchanges,$ati_months)?></div>


		<div class="line"></div><h2>بازار جهانی</h2>
		<div id="" class="box"><?= left_table($latest_exchanges,$ati_months)?></div>

		<div class="line"></div><h2>برابری ارزها</h2>
		<div id="" class="box"><?= exchange_rate_right()?></div>

	</div>
</div>
<input type="hidden" id="type" value="i">
<?php
function news_links($news_list){
	foreach ($news_list as $news) {
		$out.='<div class="news'.($news['last']?' last':'').'">'
			.'<a href="'.$news['url'].'" target="_blank">'.$news['title'].'</a>'
				.'<div class="details">'.$news['source'].' - '.$news['time_txt'].'</div>'
			.'</div>';
	}
	return $out;
}
$title='قیمت دلار طلا و سکه - ارزلایو';
function arzlive_chart($type,$price){
	return '<img src="/c/d-'.$type.'.png?'.$price.'">';
}
function left_table($ex) {
	global $sensored;
	$elements=array('0:5'=>'انس نقره','0:6'=>'انس پلاتین','0:7'=>'انس پالادیوم','0:8'=>'نفت سبک');
	$out='<table id="gold_tbl"><tr class="legend"><th>قیمت طلا<th>قیمت زنده<th>تغییر</tr>';
	foreach ($elements as $key=>$name){
		$out.='<tr><td>'.$name;
		if ($sensored[$key]){
			$out.='<td'.ajk('s'.$key).'>'.ajv('-')
				.'<td'.ajk('c'.$key,$change['3:2']['class']).'>'.ajv('-').'</tr>';
		} else {
			$c=$ex[$key];
			$change=change_perc(number_fill($key,$c['yesterday'],true),number_fill($key,$c['sell'],true),(strstr($key,'0:')?2:0),'seperated');
			$change['class'].=' price';
			$out.='<td'.ajk('s'.$key,'price').'>'.ajv(number_fill($key,$c['sell']))
				.'<td'.ajk('c'.$key,$change['class']).'>'.ajv($change['change']).'</tr>';
		}
	}
	return $out.'</table>';
}
function main_table($ex) {
	global $sensored;
	$elements=['0:1'=>'انس','3:2'=>'مثقال','3:3'=>'گرم 18'
		//,'3:40'=>'دلار','3:41'=>'یورو'//'17:40'=>'دلار صرافی (سنا)',
		,'3:10'=>'بهار آزادی','3:11'=>'امامی','3:12'=>'نیم','3:13'=>'ربع','3:14'=>'گرمی'];

	$out='<table id="gold_tbl"><tr class="legend"><th><th><th>قیمت زنده<th>نمودار زنده<th>تغییر<th>کمترین<th>بیشترین<th>زمان</tr>';
	foreach ($elements as $key=>$name){
		list($r,$k)=explode(':',$key);
		$out.='<tr>';
		if ($key=='0:1') $out.='<td rowspan="3" class="bc"><div class="title">طلا</div>';
		if ($key=='17:40') $out.='<td rowspan="3" class="bc"><div class="title">ارز</div>';
		if ($key=='3:10') $out.='<td rowspan="5" class="bc"><div class="title">سکه</div>';

		$out.='<td>'.$name;
		if ($sensored[$key]){
			$out.='<td'.ajk('s'.$key).'>'.ajv('-')
				.'<td'.ajk('c'.$key,$change['3:2']['class']).'>'.ajv('-')
				.'<td'.ajk('l'.$key).'>'.ajv('-')
				.'<td'.ajk('h'.$key).'>'.ajv('-')
				.'<td'.ajk('z'.$key,'fa').'>'.ajv('-').'</tr>';
		} else {
			$price=last($r,$k,'sell');
			$min=last($r,$k,'min');
			$max=last($r,$k,'max');
			$t=last($r,$k,'timestamp');
			$price_before=last($r,$k,'yesterday');
			$decimal=decimal_point($price);
			$change=change_perc($price_before,$price,$decimal,'seperated');
			list($chart_time,$chart_data)=chart_price($r,$k);
			$out.='<td'.ajk('s'.$key,'price').'>'.ajv(number_format($price,$decimal))
				.'<td class="spark d'.str_replace(':','_',$key).'" data-chart="'.aj('d'.str_replace(':','_',$key).'-chart',$chart_data).'" data-t="'.aj('d'.str_replace(':','_',$key).'-t',$chart_time).'">'
				.'<td'.ajk('c'.$key,$change['class'].' price').'>'.ajv($change['change'])
				.'<td'.ajk('l'.$key,'small').'>'.ajv(number_format($min,$decimal))
				.'<td'.ajk('h'.$key,'small').'>'.ajv(number_format($max,$decimal))
				.'<td'.ajk('z'.$key,'fa small').'>'.ajv(update_time($t)).'</tr>';
		}
	}
	return $out.'</table>';
}
function chart_price($rate_type,$currency_id){
	$rs=db_query('SELECT sell, DATE_FORMAT(addedon,"%H:%i") as point_time FROM exch_rate
		WHERE rate_type = '.$rate_type.' AND currency_id = '.$currency_id.' AND DATE(addedon)= CURDATE()
		ORDER BY addedon DESC');
	//if (db_count($rs)>50) $omit_small_changes=true;
	while ($row=db_array($rs)){
		/*if (++$count_all<30) {
			$all_sell[]=$row['sell'];
			$all_time[]=$row['point_time'];
		}*/
		//if (!$current) $current=$row['sell'];
		//if ($omit_small_changes) echo abs($current-$row['sell']).'<'.($row['sell']/300).'<br>';
		/*if ($omit_small_changes && abs($current-$row['sell']) < $row['sell']/1000){
			continue;
		}*/
		//if ($min&&$data_day!=$row['data_day']) break;
		//if (++$count>30) break;
		//$current=$row['sell'];
		$sell[]=price_normalize($rate_type,$currency_id,$row['sell']);
		$time[]=$row['point_time'];
	}
	if ($sell) {
		if (count($sell)>35) list($sell,$time)=reduce_point($sell,$time,30);
		return [implode(',',array_reverse($time)),implode(',',array_reverse($sell))];
	}
}
function reduce_point($sell,$time,$number){
	//$threshold=(max($sell)-min($sell))*((count($sell)/$number)-2)/$number;
	$omit=floor(count($sell)/$number);
	foreach ($sell as $index => $price){
		/*if (!$current) $current=$price;
		if (abs($current-$price)<$threshold) {
			echo $threshold.' ';
			continue;
		}*/
		if ($price!=max($sell)||$price!=min($sell)){
			if ($count-->0||$current==$price) continue;
		}
		$count=$omit;
		$out[0][$index]=$price;
		$out[1][$index]=$time[$index];
		$current=$price;
	}
	return $out;
}
function exchange_table_right(){
	$currencies=[42=>'پوند',43=>'درهم امارات',44=>'دلار کانادا',45=>'یوان چین',46=>'لیره ترکیه',47=>'ین ژاپن',48=>'روپیه هند',49=>'دلار استرالیا',50=>'کرون سوئد',51=>'فرانک سوئیس',52=>'کرون نروژ',60=>'افغانی',];
	return exchange_table(3,$currencies,true);
}
function exchange_table_left(){
	$currencies=[53=>'دلار هنگ کنگ',54=>'رینگیت مالزی',55=>'دینار کویت',56=>'دینار عراق',57=>'بات تایلند',58=>'روپیه پاکستان',59=>'مانات آذربایجان',61=>'ریال عربستان',62=>'روبل روسیه',63=>'دلار سنگاپور',64=>'کرون دانمارک',65=>'وون کره جنوبی',];
	return exchange_table(3,$currencies,true);
}
function exchange_rate_right(){
	$currencies=[47=>'ین ژاپن',41=>'یورو',42=>'پوند',43=>'درهم امارات',45=>'یوان چین',46=>'لیره ترکیه'];
	return exchange_table(0,$currencies);
}
function exchange_table($rate_type,$key,$guess=false) {
	global $sensored;
	$ex=array(0=>'نرخ برابری',2=>'ارز مرجع',3=>'بازار تهران',15=>'مبادلات ارزی');
	$out='<table id="ex'.$rate_type.'" class=exch_tbl><tr class="legend"><th>'.$ex[$rate_type].'<th>'.(($rate_type)?'فروش':'هر دلار').'<th>تغییر</tr>';
	foreach ($key as $k => $name){
		if ($rate_type) $add_zero=10; else $add_zero=1;
		$ajax_key=$rate_type.':'.$k;

		$price=last($rate_type,$k,'sell');
		$price_before=last($rate_type,$k,'yesterday');
		if ($guess) {
			$price=currency_guess($k);
			$price_before=currency_guess($k,'yesterday');
		}

		if ($price>0) ; else continue;
		$decimal=decimal_point($price);
		$change=change_perc($price_before,$price,$decimal,'seperated');
		$change['class'].=' price';
		$out.='<tr>'
			.'<td><span class="fg fg-'.$k.'"></span> '.$name.'</td>';
		if ($sensored[$ajax_key]) {
			$out.='<td'.ajk('s'.$ajax_key,"price").'>'.ajv('-').'</td><td'.ajk('c'.$ajax_key,$change['class']).'>'.ajv('-').'</td>';
		} else {
			$out.='<td'.ajk('s'.$ajax_key,"price").'>'.ajv(number_format($price,$decimal)).'</td><td'.ajk('c'.$ajax_key,$change['class']).'>'.ajv($change['change']).'</td>';
		}
		$out.='</tr>';
	}
	return $out.'</table>';
}
function investment_table(){
	$keys=[
		'3:2'=>'طلا',
		'3:11'=>'سکه',
		'13:114'=>'بورس',
		'3:40'=>'دلار',
		/*'3:41'=>'یورو',*/
	];
	$out='<table class=invest_tbl><tr class="legend"><th><th>یک هفته<th>دو هفته<th>یک ماه</tr>';
	foreach ($keys as $key => $name){
		list($rate_type,$currency_id)=explode(':',$key);
		$price=last($rate_type,$currency_id,'sell');

		$week_price=past_price($rate_type,$currency_id,7);
		$profit_week=($price*10000000/$week_price);
		$change_week=change_perc(10000000,$profit_week,0,'percentage');

		$week2_price=past_price($rate_type,$currency_id,14);
		$profit2_week=($price*10000000/$week2_price);
		$change2_week=change_perc(10000000,$profit2_week,0,'percentage');

		$month_price=past_price($rate_type,$currency_id,30);
		$profit_month=($price*10000000/$month_price);
		$change_month=change_perc(10000000,$profit_month,0,'percentage');

		$out.='<tr'.((++$number%2)?'':' class=even').'>'
			.'<td>'.$name.'</td>';
		if ($sensored[$key]) {
			$out.='<td'.ajk('s'.$key,'price').'>'.ajv('-').'</td><td'.ajk('c'.$key,$change_week['class']).'>'.ajv('-').'</td>';
		} else {
			$out.='<td'.ajk('slw'.$key,$change_week['class'].' price').'>'.ajv(profit_display($profit_week)).'</td>';
			$out.='<td'.ajk('sl2w'.$key,$change2_week['class'].' price').'>'.ajv(profit_display($profit2_week)).'</td>';
			$out.='<td'.ajk('slm'.$key,$change_month['class'].' price').'>'.ajv(profit_display($profit_month)).'</td>';
		}
		$out.='</tr>';
	}
	return $out.'</table>';
}
function profit_display($number){
	$number=number_format($number);
	if (!$number) return '-';
	return $number;
}
function today_profit_loss_table(){
	$keys=[
		'3:2'=>'طلا',
		'3:11'=>'سکه',
		'13:114'=>'بورس',
		'3:40'=>'دلار',
		/*'3:41'=>'یورو',*/
	];
	$out='<table class=invest_tbl><tr class="legend"><th colspan="2">یک میلیون ریال شما امروز تا این ساعت چقدر شد؟</tr>';
	foreach ($keys as $key => $name){
		list($rate_type,$currency_id)=explode(':',$key);
		$price=last($rate_type,$currency_id,'sell');

		$price_before=last($rate_type,$currency_id,'yesterday');
		$profit=($price*1000000/$price_before);
		$change_today=change_perc(1000000,$profit,0,'seperated');

		$out.='<tr'.((++$number%2)?'':' class=even').'>'
			.'<td>'.$name.'</td>';
		if ($sensored[$key]) {
			$out.='<td'.ajk('s'.$key,'price').'>'.ajv('-').'</td>';
		} else {
			$out.='<td'.ajk('slt'.$key,'price').'>'.ajv(number_format($profit)).'</td>';
		}
		$out.='</tr>';
	}
	return $out.'</table>';
}
function voting(){
	$vote='<div class="xvq" data-q="3">برای کدام یک از موارد زیر به سایت مراجعه مینمایید؟</div>
	<a href="#" data-a="1">1. نرخ دلار</a>
	<a href="#" data-a="2">2. نرخ سایر ارزها</a>
	<a href="#" data-a="3">3. نرخ سکه</a>
	<a href="#" data-a="4">4. نرخ طلا</a>
	<a href="#" data-a="5">5. نمودارها</a>
	<a href="#" data-a="6">6. ابزار سرمایه گذاری و سایر ابزارها</a>
	';
	return '<div class="xv">'.$vote.'</div>';
}
function misc_table($ex,$ati_months) {
	global $sensored;
	$keys=array('13:114'=>'شاخص بورس');
	$out='<table class=misc_tbl><tr class="legend"><th>سکه آتی<th>قیمت<th>تغییر</tr>';
	foreach ($keys as $key => $name){
		list($rate_type,$currency_id)=explode(':',$key);
		$price=last($rate_type,$currency_id,'sell');
		$price_before=last($rate_type,$currency_id,'yesterday');
		if ($price>0) ; else continue;
		$decimal=decimal_point($price);
		$change=change_perc($price_before,$price,$decimal,'seperated');
		$out.='<tr'.((++$number%2)?'':' class=even').'>'
			.'<td>'.$name.'</td>';
		if ($sensored[$key]) {
			$out.='<td'.ajk('s'.$key,'price').'>'.ajv('-').'</td><td'.ajk('c'.$key,$change['class']).'>'.ajv('-').'</td>';
		} else {
			$out.='<td'.ajk('s'.$key,'price').'>'.ajv(number_format($price,$decimal)).'</td><td'.ajk('c'.$key,$change['class'].' price').'>'.ajv($change['change']).'</td>';
		}
		$out.='</tr>';
	}
	$ex=get_json('last_ime');
	if (is_array($ati_months)) foreach ($ati_months as $key => $data){
		$c=$ex[$key];
		$price=$c['sell']*10000;
		$price_before=$c['yesterday']*10000;

		if ($price>0) ; else continue;
		$decimal=decimal_point($price);
		$change=change_perc($price_before,$price,$decimal,'seperated');
		$out.='<tr'.((++$number%2)?'':' class=even').'>'
			.'<td>'.$data['title'].'</td>';
		if ($sensored['ati']) {
			$out.='<td'.ajk('s'.$key).'>'.ajv('-').'</td><td'.ajk('c'.$key,$change['class']).'>'.ajv('-').'</td>';
		} else {
			$out.='<td'.ajk('s'.$key,'price').'>'.ajv(number_format($price,$decimal)).'</td><td'.ajk('c'.$key,$change['class'].' price').'>'.ajv($change['change']).'</td>';
		}
		$out.='</tr>';
	}
	return $out.'</table>';
}

function update_time($t){
	if (date('j')<>date('j',$t)){
		list($y,$m,$d)=gregorian_to_jalali(date('Y',$t),date('m',$t),date('d',$t));
		return $d.' '.jalali_month($m);
		//return (($d>9)?'':'0').$d.'/'.(($m>9)?'':'0').$m.'/'.$y;
	}else{
		return date('H:i',$t);
	}
}
