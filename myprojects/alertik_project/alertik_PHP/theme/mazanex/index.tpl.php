<?php
//exchange_table(16,$latest_exchanges,$before,$today) //Market rate
//.exchange_table(2,$latest_exchanges,$before,$today) // Central bank rates
//.exchange_table(1,$latest_exchanges,$before,$today); // World rate in rial
//.website_lastupdate()
//.educate()
//.earning_tool()

$title='قیمت لحظه ای طلا، سکه و ارز در بازار ایران و جهان';
$side='<div id="news_box" '.ajk('news','box').'>'.ajv(news_links($news_list)).'</div>'
	.'<div>به کمک <a href="https://bab.ir">فروشگاه ساز باب</a> محصولات خود را بر روی اینترنت وابسته به قیمت ارز به فروش برسانید.</div>'
	.$ads[1][0][0]//print_r($ads[],true)
	.''
	.$voting
	.exchange_table(0,$latest_exchanges,$before,$today) // Exchange Rates
	;

echo $timemachine_html.'<input id="type" value="i" type="hidden"><div id="primaryContent">'
	.'<div'.ajk('notice').'>'.ajv((($notice)?'<div>'.$notice.'</div>':'')).'</div>'
	.gold_table($latest_exchanges,$before,$today)
	.coin_table($latest_exchanges,$before,$today)
	.money_table($latest_exchanges,$before,$today)
	.charts_tool()
	.$conversation_tool
	.$gold_tool
	.'<span class="hide s0_0">1</span></div><div id="secondaryContent">'.$side.'</div>';

function news_links(){
	$news_list=get_news_list(5);
	$out='<br><h3>آخرین اخبار اقتصادی از <a href="//www.khabarist.com" style="display:inline">خبریست</a></h3>';
	foreach ($news_list as $news) {
		$out.='<div class="news'.($news['last']?' last':'').'">
			<a href="'.$news['url'].'" target="_blank">'.$news['title'].'</a>
				<div class="details">'.$news['source'].' - '.$news['time_txt'].'</div>
			</div>';
	}
	return $out;
}
function exchange_table($rate_type,$latest_exchanges,$before,$today,$filter='') {
	global $sensored;
	$ex=array(0=>'نرخ برابری با هر دلار',1=>'بازار جهانی <sup> <a href="/faq.html#note6">[؟]</a></sup>',2=>'نرخ بانکی<sup> <a href="/faq.html#note5">[؟]</a></sup>',3=>'بازار تهران<sup> <a href="/faq.html#note11">[؟]</a></sup>',4=>'حواله',20=>'ارز از روی نرخ طلا<sup> <a href="/faq.html#note10">[؟]</a></sup>',15=>'مبادلات ارزی',16=>'کانون صرافان');
	$key=array(40=>'دلار',41=>'یورو',42=>'پوند',43=>'درهم امارات',44=>'دلار کانادا',45=>'یوان چین',46=>'لیره ترکیه',47=>'ین ژاپن',48=>'روپیه هند',49=>'دلار استرالیا',50=>'کرون سوئد',51=>'فرانک سوئیس',52=>'کرون نروژ',53=>'دلار هنگ کنگ',54=>'رینگیت مالزی',55=>'دینار کویت',56=>'دینار عراق',57=>'بات تایلند',58=>'روپیه پاکستان',59=>'مانات آذربایجان',60=>'افغانی',61=>'ریال عربستان',62=>'روبل روسیه',63=>'دلار سنگاپور',64=>'کرون دانمارک',65=>'وون کره جنوبی');
	$out='<table id="ex'.$rate_type.'" class="exch_tbl'.(($rate_type==3)?'':'').(($rate_type==20)?' adv':'').'">'
		.((!$rate_type)?'<tr class="legend"><th>دلار برای محاسبه<sup><a href="/faq.html#note12">[؟]</a></sup><th class="tools" colspan="2"><input id="custom_dollar" size="12"></tr>':'')
		.'<tr class="legend"><th>'.$ex[$rate_type].'<th>فروش<th>تغییر</tr>';
	foreach ($key as $k => $name){
		if ($rate_type) $add_zero=10; else $add_zero=1;
		$price=$latest_exchanges[$rate_type.':'.$k]['sell']*$add_zero;
		$price_before=$before[$rate_type.':'.$k]['last_sell']*$add_zero;
		if ($price>0) ; else continue;
		$decimal=decimal_point($price);
		$ajax_key=$rate_type.':'.$k;
		$change=change_perc($price_before,$price,$decimal,'seperated');
		$out.='<tr'.((++$number%2)?'':' class=even').'>';
		if ($k!=46) $out.='<td>'.$name.' <span class="fg fg-'.$k.'"></span></td>';
		else $out.='<td>'.$name.' <a href="//www.kapalix.com"><span class="fg fg-'.$k.'"></span></a></td>';
		if ($sensored[$ajax_key]) {
			$out.='<td'.ajk('s'.$ajax_key).'>'.ajv('-').'</td><td'.ajk('c'.$ajax_key,$change['class']).'>'.ajv('-').'</td>';
		} else {
			$out.='<td'.ajk('s'.$ajax_key).'>'.ajv(number_format($price,$decimal)).'</td><td'.ajk('c'.$ajax_key,$change['class']).'>'.ajv($change['change']).'</td>';
		}
		$out.='</tr>';
		if ($rate_type==3) {
			$timestamp=$latest_exchanges['3:40']['timestamp'];
		} elseif (!$timestamp||$timestamp<$latest_exchanges[$rate_type.':'.$k]['timestamp']) $timestamp=$latest_exchanges[$rate_type.':'.$k]['timestamp'];
	}
	return $out.'</table>'.countdown('',$timestamp,'t1'.$rate_type);//(($rate_type==20||$rate_type==3)?'adv':'')
}

function money_table($latest_exchanges,$before,$today) {
	global $sensored;
	$keys=array(
		'40'=>'دلار بازار تهران',
		//'20:40'=>'دلار از روی نرخ طلا <sup> <a href="/faq.html#note10">[؟]</a></sup>',// <sup> <a href="/faq.html#note10">[؟]</a></sup>
		//'2:41'=>'یورو بانکی',
		'41'=>'یورو در بازار تهران',
		//'20:41'=>'یورو از روی نرخ طلا <sup> <a href="/faq.html#note10">[؟]</a></sup>',
		//'1:41'=>'نرخ جهانی یورو <sup> <a href="/faq.html#note6">[؟]</a></sup>',
		'42'=>'پوند در بازار تهران',
		//'20:42'=>'پوند از روی نرخ طلا <sup> <a href="/faq.html#note10">[؟]</a></sup>',
		'43'=>'درهم در بازار تهران',
		);
	$out='<table id="m_tbl"><tr>
	<th class=first>نرخ ارز</th>
	<th>فروش</th>
	<th>از روی دلار<sup> <a href="/faq.html#note6">[؟]</a></sup></th>
	<th>از روی طلا<sup> <a href="/faq.html#note10">[؟]</a></sup></th>
	<th>تغییر<sup> <a href="/faq.html#note1">[؟]</a></sup></th>
	<th>کمترین<sup> <a href="/faq.html#note2">[؟]</a></sup></th>
	<th>بیشترین<sup> <a href="/faq.html#note2">[؟]</a></sup></th>
	</tr>
	';	//<th>خرید</th>
	foreach ($keys as $key => $name){
		$k='3:'.$key;
		$change=change_perc($before[$k]['last_sell']*10,$latest_exchanges[$k]['sell']*10,0,'seperated');
		if ($sensored[$k]){
			$out.='<tr class="hide"><td class=fa>'.$name.'</td>'.
				'<td'.ajk('s'.$k).'>'.ajv('-').'</td>'.
				'<td'.ajk('s'.$key).'>'.ajv('-').'</td>'.
				'<td'.ajk('s'.$key).'>'.ajv('-').'</td>'.
				'<td'.ajk('c'.$k,$change['class']).'>'.ajv('-').'</td>'.
				'<td'.ajk('l'.$k).'>'.ajv('-').'</td>'.
				'<td'.ajk('h'.$k).'>'.ajv('-').'</td></tr>';
		} else {
			$out.='<tr><td class=fa>'.$name.'</td>'.
				'<td'.ajk('s'.$k).'>'.ajv(number_format(round($latest_exchanges[$k]['sell'])*10)).'</td>'.
				'<td'.ajk('s1:'.$key).'>'.ajv((($key==40)?'-':number_format(round($latest_exchanges['1:'.$key]['sell'])*10))).'</td>'.
				'<td'.ajk('s20:'.$key).'>'.ajv(number_format(round($latest_exchanges['20:'.$key]['sell'])*10)).'</td>'.
				'<td'.ajk('c'.$k,$change['class']).'>'.ajv($change['change']).'</td>'.
				'<td'.ajk('l'.$k).'>'.ajv(number_format(round($today[$k]['min'])*10)).'</td>'.
				'<td'.ajk('h'.$k).'>'.ajv(number_format(round($today[$k]['max'])*10)).'</td></tr>';
		}

	}
	return $out.'</table>';
}

function coin_table($latest_exchanges,$before,$today) {
	global $sensored;
	$key=array('10'=>'بهار آزادی','11'=>'امامی','12'=>'نیم','13'=>'ربع','14'=>'گرمی');
	$out='<table id="coin_tbl"><tr>
	<th class=first>نرخ سکه</th>
	<th>بازار تهران</th>
	<th>تغییر<sup> <a href="/faq.html#note1">[؟]</a></sup></th>
	<th>کمترین<sup> <a href="/faq.html#note2">[؟]</a></sup></th>
	<th>بیشترین<sup> <a href="/faq.html#note2">[؟]</a></sup></th>
	<th>ارزش طلا در بازار<sup> <a href="/faq.html#note3">[؟]</a></sup></th>
	</tr>
	';
	//<th>ارزش طلا در بازار جهانی<sup> <a href="/faq.html#note4">[؟]</a></sup></th>
	//<th>نرخ بانکی<sup> <a href="/faq.html#note7">[؟]</a></sup></th>
	foreach ($key as $currency_id => $name){
		$k='3:'.$currency_id;
		$change=change_perc($before[$k]['last_sell']*10000,$latest_exchanges[$k]['sell']*10000,0,'seperated');
		$out.='<tr><td>'.$name.'</td>';
		if ($sensored[$k]) {
			$out.='<td'.ajk('s'.$k).'>'.ajv('-').'</td>'.'<td'.ajk('c'.$k,$change['class']).'>'.ajv('-').'</td>'.
			'<td'.ajk('l'.$k).'>'.ajv('-').'</td>'.'<td'.ajk('h'.$k).'>'.ajv('-').'</td>'.'<td'.ajk('s'.'4:'.$currency_id).'>'.ajv('-').'</td>';
		} else {
		$out.='<td'.ajk('s'.$k).'>'.ajv(number_format($latest_exchanges[$k]['sell']*10000)).'</td>'.
			'<td'.ajk('c'.$k,$change['class']).'>'.ajv($change['change']).'</td>'.
			'<td'.ajk('l'.$k).'>'.ajv(number_format($today[$k]['min']*10000)).'</td>'.
			'<td'.ajk('h'.$k).'>'.ajv(number_format($today[$k]['max']*10000)).'</td>'.
			'<td'.ajk('s'.'4:'.$currency_id).'>'.ajv(number_format(round($latest_exchanges['4:'.$currency_id]['sell']*100)*100)).'</td>';
			//'<td>'.number_format($latest_exchanges['1:'.$currency_id]['sell']*10000).'</td>'.
			//'<td>'.(($currency_id!=10)?number_format($latest_exchanges['2:'.$currency_id]['sell']*10000):'<sup><a href="/faq.html#note8">[؟]</a></sup> -').'</td>'.
		}
		$out.='</tr>';
		if (!$timestamp_market||$timestamp_market<$latest_exchanges[$k]['timestamp'])$timestamp_market=$latest_exchanges[$k]['timestamp'];
	}
	//if (!$timestamp_bank||$timestamp_bank<$latest_exchanges['2:'.$currency_id]['timestamp'])$timestamp_bank=$latest_exchanges['2:'.$currency_id]['timestamp'];
	return $out.'</table>'.countdown('نرخ سکه در بازار تهران',$timestamp_market,'t23');//.countdown('نرخ سکه در بانک',$timestamp_bank);//countdown('نرخ سکه در بازار تهران',$timestamp);
}
function gold_table($latest_exchanges,$before,$today) {
	global $sensored;
	$change['0:1']=change_perc($before['0:1']['last_sell'],$latest_exchanges['0:1']['sell'],2,'seperated');
	$change['3:2']=change_perc($before['3:2']['last_sell']*10000,$latest_exchanges['3:2']['sell']*10000,0,'seperated');
	$change['1:3']=change_perc($before['1:3']['last_sell']*10000*4.3318,$latest_exchanges['1:3']['sell']*10000*4.3318,0,'seperated');
	$change['3:3']=change_perc($before['3:3']['last_sell']*10000,$latest_exchanges['3:3']['sell']*10000,0,'seperated');
	return '<table id="gold_tbl">'.
			'<tr>'.
				'<th class=first>نرخ طلا</th>'.
				'<th>فروش</th>'.
				'<th>تغییر<sup> <a href="/faq.html#note1">[؟]</a></sup></th>'.
				'<th>کمترین<sup> <a href="/faq.html#note2">[؟]</a></sup></th>'.
				'<th>بیشترین<sup> <a href="/faq.html#note2">[؟]</a></sup></th>'.
			'</tr>'.
			'<tr>'.
				'<td>انس در بازار جهانی <sup>دلار</sup></td>'.
				'<td'.ajk('s'.'0:1').'>'.ajv(number_format($latest_exchanges['0:1']['sell'],2)).'</td>'.
				'<td'.ajk('c'.'0:1',$change['0:1']['class']).'>'.ajv($change['0:1']['change']).'</td>'.
				'<td'.ajk('l'.'0:1').'>'.ajv(number_format($today['0:1']['min'],2)).'</td>'.
				'<td'.ajk('h'.'0:1').'>'.ajv(number_format($today['0:1']['max'],2)).'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>مثقال طلا در بازار تهران</td>'.
				'<td'.ajk('s'.'3:2').'>'.ajv(number_format($latest_exchanges['3:2']['sell']*10000)).'</td>'.
				'<td'.ajk('c'.'3:2',$change['3:2']['class']).'>'.ajv($change['3:2']['change']).'</td>'.
				'<td'.ajk('l'.'3:2').'>'.ajv(number_format($today['3:2']['min']*10000)).'</td>'.
				'<td'.ajk('h'.'3:2').'>'.ajv(number_format($today['3:2']['max']*10000)).'</td>'.
				//'<td'.ajk('s'.'3:2').'>'.ajv('-').'</td>'.
				//'<td'.ajk('c'.'3:2',$change['3:2']['class']).'>'.ajv('-').'</td>'.
				//'<td'.ajk('l'.'3:2').'>'.ajv('-').'</td>'.
				//'<td'.ajk('h'.'3:2').'>'.ajv('-').'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>مثقال طلا بر اساس دلار</td>'.
				'<td'.ajk('s'.'1:3').'>'.ajv(number_format(round($latest_exchanges['1:3']['sell']*100*4.3318)*100)).'</td>'.
				'<td'.ajk('c'.'1:3',$change['1:3']['class']).'>'.ajv($change['1:3']['change']).'</td>'.
				'<td'.ajk('l'.'1:3').'>'.ajv(number_format(round($today['1:3']['min']*10*4.3318)*1000)).'</td>'.
				'<td'.ajk('h'.'1:3').'>'.ajv(number_format(round($today['1:3']['max']*10*4.3318)*1000)).'</td>'.
				//'<td'.ajk('s'.'1:3').'>'.ajv('-').'</td>'.
				//'<td'.ajk('c'.'1:3',$change['1:3']['class']).'>'.ajv('-').'</td>'.
				//'<td'.ajk('l'.'1:3').'>'.ajv('-').'</td>'.
				//'<td'.ajk('h'.'1:3').'>'.ajv('-').'</td>'.
			'</tr>'.
			'<tr>'.
				'<td>گرم طلای 18 در بازار تهران</td>'.
				'<td'.ajk('s'.'3:3').'>'.ajv(number_format($latest_exchanges['3:3']['sell']*10000)).'</td>'.
				'<td'.ajk('c'.'3:3',$change['3:3']['class']).'>'.ajv($change['3:3']['change']).'</td>'.
				'<td'.ajk('l'.'3:3').'>'.ajv(number_format($today['3:3']['min']*10000)).'</td>'.
				'<td'.ajk('h'.'3:3').'>'.ajv(number_format($today['3:3']['max']*10000)).'</td>'.
				//'<td'.ajk('s'.'3:3').'>'.ajv('-').'</td>'.
				//'<td'.ajk('c'.'3:3',$change['3:3']['class']).'>'.ajv('-').'</td>'.
				//'<td'.ajk('l'.'3:3').'>'.ajv('-').'</td>'.
				//'<td'.ajk('h'.'3:3').'>'.ajv('-').'</td>'.
			'</tr>'.
			/*'<tr>'.
				'<td>گرم طلای 18 در بازار جهانی <sup>ریال</sup></td>'.
				'<td id="d3_1">'.number_format($latest_exchanges['1:3']['sell']*10000).'</td>'.
				'<td>'.change_perc($before['1:3']['last_sell']*10000,$latest_exchanges['1:3']['sell']*10000).'</td>'.
				'<td>'.number_format($today['1:3']['min']*10000).'</td>'.
				'<td>'.number_format($today['1:3']['max']*10000).'</td>'.
			'</tr>'.*/
		'</table>'.countdown('قیمت انس طلا در بازار جهانی',$latest_exchanges['0:1']['timestamp'],'t30').countdown('قیمت طلا در بازار تهران',$latest_exchanges['3:2']['timestamp'],'t32');
}
function charts_tool(){
	//<a href="/c/d/candlestick-dollar.png?'.$time['day'].'">دلار</a> |
	//<a href="/c/d/candlestick-dollar-m.png?'.$time['day'].'">دلار</a> |
	//<a href="/c/s/linechart-dollar.png?stamp">دلار</a> |
	$time=db_row('SELECT day(NOW()) as day');
	return '<table id="charts">
	<tr>
		<th>نمودار</th>
		<th>لحظه ای</th>
		<th>روزانه</th>
		<th>ماهانه</th>
	</tr>
	<tr>
		<td class=fa>نمودار خطی</td>
		<td class="chart">
			<a href="/c/s/linechart-fcoin.png?stamp">سکه تمام</a> |  <a href="/c/s/linechart-gold.png?stamp">طلا</a>
		</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td class=fa>کندل استیک <sup> <a href="/faq.html#candlestick">[؟]</a></sup></td>
		<td></td>
		<td class=chart>
			<a href="/c/d/candlestick-fcoin.png?'.$time['day'].'">سکه تمام</a>  | <a href="/c/d/candlestick-gold.png?'.$time['day'].'">طلا</a>
		</td>
		<td class=chart>
			<a href="/c/d/candlestick-fcoin-m.png?'.$time['day'].'">سکه تمام</a>  | <a href="/c/d/candlestick-gold-m.png?'.$time['day'].'">طلا</a>
		</td>
	</tr>
	</table><div class="hide" id="chart_box"></div>';
}
function earning_tool(){
return '<table id="earning" class="tools">
<tr><th colspan="4">محاسبه سود و زیان سرمایه گذاری <sup> <a href="/faq.html#note9">[؟]</a></sup></tr>
<tr><td>زمان سرمایه گذاری</td><td>میزان سرمایه گذاری<sup>ریال</sup></td><td>نوع سرمایه گذاری</td><td>سود و زیان <sup>ریال</sup></td></tr>
<tr><td class="fa">
<select id="earning_duration">
  <option value="1">ابتدای امروز</option>
  <option value="2">از سی روز قبل</option>
  <option value="3" selected="selected">از 90 روز قبل</option>
</select>
</td>
<td><input id="earning_investment" size="12" value="10,000,000"></td>
<td class="fa"><select id="earning_type">
<option value="3:3">طلا</option>
<option value="3:11">سکه</option>
<option value="3:40">دلار</option>
<option value="3:41">یورو</option>
 </select></td>
<td class="fa"><span id="earning_conclusion">'.earning_now().'</span></td></tr>
</table>';
}

function earning_now(){
	global $earnings;
	if ($earnings['3:3'][3]) $earning=round((($earnings['3:3'][0]*10000000)/$earnings['3:3'][3])-10000000);
	if ($earning < 0) {$earn_type="neg"; $earn_name='زیان';}
	else {$earn_type="pos";  $earn_name='سود';}
	return '<span class="'.$earn_type.'">'.number_format(abs($earning)).' '.$earn_name.'</span>';
}
function msg_box($type,$msg){
	return '<div class="alert'.(($type)?' alert-'.$type:'').'">'.$msg.'</div>';
}
