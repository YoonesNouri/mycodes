<div id="primaryContent">
	<div <?=ajk('notice')?>><?=ajv((($notice)?'<div>'.$notice.'</div>':''))?></div>
	<?= gold_table($latest_exchanges) ?>
	<?php /*<div class="ad adh3" data-ad='<?=tg_advertisement('main_top_right2')?>'><div></div></div>*/ ?>
	<?= coin_table($latest_exchanges) ?>
	<?php /*<div class="ad adh2" data-ad='<?=tg_advertisement('main_bottom_right')?>'><div></div><div></div><div></div><div></div><div></div></div>*/ ?>
	<?= market_table($latest_exchanges) ?>

	<table class="tools" id="gld">
		<tr>
			<th colspan=5>مبدل مقیاس ها</th>
		</tr>
		<tr><td>طلای 18 عیار<td>طلای 22 عیار<td>مثقال طلا<td>اونس طلا<td>ریال</tr>
		<tr>
			<td><input data-t="1" size=12 /></td>
			<td><input data-t="1.221333" size=12 /></td>
			<td><input data-t="4.331802" size=12 /></td>
			<td><input data-t="41.4713024" size=12 /></td>
			<td><input class=irr data-t="0" size=12 /></td>
		</tr>
	</table>

<span class="hide s0_0">1</span>
</div>
<div id="secondaryContent">
	<?php /*<div class="ad adv" data-ad='<?=tg_advertisement('main_top_left')?>'><div></div></div>*/ ?>
	<?php /*<div class="ad adv" data-ad='<?=tg_advertisement('main_top2_left')?>'><div></div></div>*/ ?>
	<?= exchange_table(0);//Exchange rates?>

	<table class=chart>
		<tr class="legend"><th>نمودار<th><a href="c/rt-mesghal.png">مثقال طلا</a><th><a href="c/rt-fcoin.png">سکه</a><th><a href="c/rt-ounce.png">انس</a></tr>
		<tr class=tip data-tip="خط قرمز آخرین روز کاری و خاکستری<br>مربوط به روز قبل کاری می باشد."><td colspan=4 class=target><img src="c/rt-mesghal.png?<?=time()?>" alt="نمودار" /></tr>
	</table>

	<?php /*<div class="ad adv" data-ad='<?=tg_advertisement('main_bottom_left')?>'><div></div></div>*/ ?>
	<?= misc_table($latest_exchanges,$ati_months)?>
	<?= ''//.voting() ?>
</div>
<input type="hidden" id="type" value="i">
<?php
$title='قیمت زنده سکه و طلا - اتحادیه طلا و جواهر';
function market_table($ex) {
	global $sensored;
	$diamond_price=get_json('diamond-price-tg');
	$elements=array('0:6'=>'انس پلاتین','0:7'=>'انس پالادیوم','0:8'=>'نفت سبک',
		'dia1'=>'<sup>VVS1 G</sup> برلیان 1',
		'dia2'=>'<sup>SI2 J</sup> برلیان 0.99',
		'dia3'=>'<sup>VVS2 H</sup> برلیان 0.49',
	);
	$out='<table id="gold_tbl"><tr><th>متوسط قیمت کالاها<th>قیمت به دلار<th>تغییر<th>کمترین<th>بیشترین<th>زمان</tr>';
	foreach ($elements as $key=>$name){
		$out.='<tr><td>'.$name;
		if ($sensored[$key]){
			$out.='<td'.ajk('s'.$key).'>'.ajv('-')
				.'<td'.ajk('c'.$key,$change['3:2']['class']).'>'.ajv('-')
				.'<td'.ajk('l'.$key).'>'.ajv('-')
				.'<td'.ajk('h'.$key).'>'.ajv('-')
				.'<td'.ajk('z'.$key,'fa').'>'.ajv('-').'</tr>';		
		} else {
			if (strstr($key,'dia')) $c=$diamond_price[$key]; else $c=$ex[$key];
			$change=change_perc(number_fill($key,$c['yesterday'],true),number_fill($key,$c['sell'],true),(strstr($key,'0:')?2:0),'seperated');
			$out.='<td'.ajk('s'.$key).'>'.ajv(number_fill($key,$c['sell']))
				.'<td'.ajk('c'.$key,$change['class']).'>'.ajv($change['change'])
				.'<td'.ajk('l'.$key).'>'.ajv(number_fill($key,$c['min']))
				.'<td'.ajk('h'.$key).'>'.ajv(number_fill($key,$c['max']))
				.'<td'.ajk('z'.$key,'fa').'>'.ajv(update_time($c['timestamp'])).'</tr>';		
		}
	}
	return $out.'</table>';
}
function gold_table($ex) {
	global $sensored;
	$elements=array('0:1'=>'انس طلا <sup>دلار</sup>','3:2'=>'مثقال طلا','3:3'=>'گرم طلای 18','0:5'=>'انس نقره <sup>دلار</sup>',);
	$out='<table id="gold_tbl"><tr><th>قیمت طلا<th>قیمت زنده<th>تغییر<th>کمترین<th>بیشترین<th>زمان</tr>';
	foreach ($elements as $key=>$name){
		$out.='<tr><td>'.$name;
		if ($sensored[$key]){
			$out.='<td'.ajk('s'.$key).'>'.ajv('-')
				.'<td'.ajk('c'.$key,$change['3:2']['class']).'>'.ajv('-')
				.'<td'.ajk('l'.$key).'>'.ajv('-')
				.'<td'.ajk('h'.$key).'>'.ajv('-')
				.'<td'.ajk('z'.$key,'fa').'>'.ajv('-').'</tr>';		
		} else {
			$c=$ex[$key];
			$change=change_perc(number_fill($key,$c['yesterday'],true),number_fill($key,$c['sell'],true),(strstr($key,'0:')?2:0),'seperated');
			$out.='<td'.ajk('s'.$key).'>'.ajv(number_fill($key,$c['sell']))
				.'<td'.ajk('c'.$key,$change['class']).'>'.ajv($change['change'])
				.'<td'.ajk('l'.$key).'>'.ajv(number_fill($key,$c['min']))
				.'<td'.ajk('h'.$key).'>'.ajv(number_fill($key,$c['max']))
				.'<td'.ajk('z'.$key,'fa').'>'.ajv(update_time($c['timestamp'])).'</tr>';		
		}
	}
	return $out.'</table>';
}
function tg_advertisement($type){
	$ads=get_json('tg_ad_'.$type);
	foreach ($ads as $index=>$ad){
		if (!$ad['act']) continue;
		$out[]=str_replace('/','^',$ad['url'].'`'.$ad['img']);
	}
	if (is_array($out)) return json_encode($out); else return '';
}
function exchange_table($rate_type) {
	global $sensored;
	$ex=array(0=>'نرخ ارزها',2=>'ارز مرجع',3=>'بازار تهران',15=>'مبادلات ارزی');
	$key=array(40=>'دلار',41=>'یورو',42=>'پوند',43=>'درهم امارات',44=>'دلار کانادا',45=>'یوان چین',46=>'لیره ترکیه');
	$out='<table id="ex'.$rate_type.'" class=exch_tbl><tr class="legend"><th>'.$ex[$rate_type].'<th>'.(($rate_type)?'فروش':'هر دلار').'<th>تغییر</tr>';
	foreach ($key as $k => $name){
		if ($rate_type) $add_zero=10; else $add_zero=1;
		$ajax_key=$rate_type.':'.$k;
		$price=last($rate_type,$k,'sell');
		$price_before=last($rate_type,$k,'yesterday');
		if ($price>0) ; else continue;
		$decimal=decimal_point($price);
		$change=change_perc($price_before,$price,$decimal,'seperated');
		$out.='<tr'.((++$number%2)?'':' class=even').'>'
			.'<td>'.$name.' <span class="fg fg-'.$k.'"></span></td>';
		if ($sensored[$ajax_key]) {
			$out.='<td'.ajk('s'.$ajax_key).'>'.ajv('-').'</td><td'.ajk('c'.$ajax_key,$change['class']).'>'.ajv('-').'</td>';		
		} else {
			$out.='<td'.ajk('s'.$ajax_key).'>'.ajv(number_format($price,$decimal)).'</td><td'.ajk('c'.$ajax_key,$change['class']).'>'.ajv($change['change']).'</td>';		
		}
		$out.='</tr>';
		if ($rate_type==3) { 
			$timestamp=last('3','40','timestamp');
		} elseif (!$timestamp||$timestamp<last($rate_type,$k,'timestamp')) $timestamp=last($rate_type,$k,'timestamp');
	}
	return $out.'</table>'.countdown('',$timestamp,'t1'.$rate_type,'compact');
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
			$out.='<td'.ajk('s'.$key).'>'.ajv('-').'</td><td'.ajk('c'.$key,$change['class']).'>'.ajv('-').'</td>';		
		} else {
			$out.='<td'.ajk('s'.$key).'>'.ajv(number_format($price,$decimal)).'</td><td'.ajk('c'.$key,$change['class']).'>'.ajv($change['change']).'</td>';		
		}
		$out.='</tr>';
	}
	$ex=get_json('last_ime');
	foreach ($ati_months as $key => $data){
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
			$out.='<td'.ajk('s'.$key).'>'.ajv(number_format($price,$decimal)).'</td><td'.ajk('c'.$key,$change['class']).'>'.ajv($change['change']).'</td>';		
		}
		$out.='</tr>';
		if (!$timestamp||$timestamp<$c['timestamp']) $timestamp=$c['timestamp'];
	}
	return $out.'</table>'.countdown('',$timestamp,'tm','compact');
}

function coin_table($ex) {
	global $sensored;
	$key=array('10'=>array('بهار آزادی',8.133),'11'=>array('امامی',8.133),'12'=>array('نیم',4.0665),'13'=>array('ربع',2.03225),'14'=>array('گرمی',1));
	$out='<table id="coin_tbl"><tr><th>سکه<th>قیمت زنده<th>تغییر<th>کمترین<th>بیشترین<th>ارزش طلا<th>زمان</tr>';
	foreach ($key as $currency_id => $element){
		$k='3:'.$currency_id;
		$out.='<tr><td>'.$element[0].'</td>';
		if ($sensored[$k]) {
			$out.='<td'.ajk('s'.$k).'>'.ajv('-').'<td'.ajk('c'.$k,$change['class']).'>'.ajv('-').'<td'.ajk('l'.$k).'>'.ajv('-')
				.'<td'.ajk('h'.$k).'>'.ajv('-').'<td'.ajk('s'.'4:'.$currency_id).'>'.ajv('-').'<td'.ajk('z'.$k,'fa').'>'.ajv('-');
		} else {
			$c=$ex[$k];		
			$worth=($ex['3:3']['sell']/750)*900*$element[1];
			$change=change_perc($c['yesterday']*10000,$c['sell']*10000,0,'seperated');
			$out.='<td'.ajk('s'.$k).'>'.ajv(number_format($c['sell']*10000)).'</td>'
				.'<td'.ajk('c'.$k,$change['class']).'>'.ajv($change['change']).'</td>'
				.'<td'.ajk('l'.$k).'>'.ajv(number_format($c['min']*10000)).'</td>'
				.'<td'.ajk('h'.$k).'>'.ajv(number_format($c['max']*10000)).'</td>'
				.'<td'.ajk('z'.'4:'.$currency_id).'>'.ajv(number_format(round($worth*100)*100)).'</td>'
				.'<td'.ajk('z'.$k,'fa').'>'.ajv(update_time($c['timestamp'])).'</td>';
		}
		$out.='</tr>';
		if (!$timestamp_market||$timestamp_market<$latest_exchanges[$k]['timestamp'])$timestamp_market=$latest_exchanges[$k]['timestamp'];
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
