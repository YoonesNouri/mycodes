<div class='reload_area row' id="header">
	<?=(($countdown)?'<div class="span2 counter dynamic" data-time="'.$countdown.'" ><a class=manual href="/"><img class="reload" src="/s/img/reload.png"></a> <span>57 ثانیه</span> تا بارگذاری مجدد</div>':'')?>
	<div class='span10 reloadborder'></div>
</div>
<div class="notice" id="notice"></div>
<div class='row'>
	<div class='span8'>
		<div class='row'>
			<div class='span8 table_title'>برابری ریال در برابر ارزهای رایج در بازار تهران</div>
		</div>
		<div class='row'>
			<div class='span8'>
				<div class='well'>
					<?= top_right($exchange,$sensored) ?>
					<div class='note'>* کلیه قیمت های ارائه شده بر اساس ریال می باشد.</div>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='span8 '>
				<div class='chart well' id="chart" data-dollar='<?=$dollar_chart?>' data-euro='<?=$euro_chart?>'></div>
			</div>
		</div>
	</div>
	<div class='span4'>
		<div class='row'>
			<div class='span4 table_title'>قیمت طلا و سکه در بازار تهران</div>
		</div>
		<div class='row'>
			<div class='span4'>
			<div class='well'><?= top_left($exchange,$sensored) ?></div>
			</div>
		</div>
		<div class='row'>
			<div class='span4'>
				<a targer='_blank'>
					<img class='banner' src='img/banner.png' />
				</a>
			</div>
		</div>
	</div>
</div> 

<div class='row'> 
	<div class='span6'>
		<div class='well'><?= bottom_left($exchange,$sensored) ?></div>
	</div>
	<div class='span6'>
		<div class='well'><?= bottom_right($exchange,$sensored) ?></div>
	</div>
</div> 

<div class='row clocks'>
	<div class='span12 well allclocks'>
		<?= analog_clock('تهران','Asia/Tehran') ?>
		<?= analog_clock('لندن','Europe/London') ?>
		<?= analog_clock('نیویورک','America/New_York') ?>
		<?= analog_clock('دوبی','Asia/Dubai') ?>
		<?= analog_clock('استانبول','Europe/Istanbul') ?>
		<div class='cls'></div>
	</div> 
</div>

</div>
</div>
<input type="hidden" id="type" value="i">
<?php
$title='صرافی اطلس';
function analog_clock($name,$timezone){
	static $counter,$script;
	?>
	<div class='clock_area'>
		<ul class="analog" data-timezone="<?=timezone_str_to_number($timezone)?>">
			<li class="hour"></li>
			<li class="min"></li>
			<li class="sec"></li>
			<li class="meridiem"></li>
		</ul>
		<div class='tzname'><?= $name ?></div>
	</div>
	<?php
}

function timezone_offset_string( $offset ) {
	return (( $offset >= 0 ) ? '+' : '-').abs( $offset / 3600 );
}
function timezone_str_to_number($str){
	$offset = timezone_offset_get( new DateTimeZone( $str ), new DateTime() );	
	return timezone_offset_string( $offset );
}
function top_left($ex,$sensored) {
	$elements=['0:1'=>'انس طلا <span class="mini">دلار</span>','3:2'=>'مثقال طلا','3:3'=>'گرم طلای 18',
		'3:10'=>'سکه آزادی','3:11'=>'سکه امامی','3:12'=>'سکه نیم','3:13'=>'سکه ربع','3:14'=>'سکه گرمی',
		];
	$out='<table class="table table-bordered" id="gold_tbl">
		<tr>
			<th>طلا و سکه</th>
			<th>قیمت خرید</th>
			<th>میزان تغییر</th>
		</tr>';
	foreach ($elements as $key=>$name){
		if ($sensored[$key]){
			$out.='<tr><td>'.$name.'</td>'
			.'<td'.ajk('s'.$key).'>'.ajv('-').'</td>'
			.'<td'.ajk('c'.$key).'>'.ajv('-').'</td>'
			.'</tr>';  
		} else {
			$c=$ex[$key];
			$change=change_perc(number_fill($key,$c['yesterday'],true),number_fill($key,$c['sell'],true),(strstr($key,'0:')?2:0),'seperated');
			$out.='<tr><td>'.$name.'</td>'
			.'<td'.ajk('s'.$key).'>'.ajv(number_fill($key,$c['sell'])).'</td>'
			.'<td class="change_icon"><div'.ajk('c'.$key,$change['class']).'>'.ajv($change['change']).'</div></td>'
			.'</tr>';  
		}
	}
	return $out.'</table>';
}

function bottom_right($ex,$sensored) {
	return exchange_table_compact($ex,$sensored,[
		54=>'رینگیت مالزی',55=>'دینار کویت',56=>'دینار عراق',58=>'روپیه پاکستان',60=>'افغانی',
		61=>'ریال عربستان',62=>'روبل روسیه',63=>'دلار سنگاپور',64=>'کرون دانمارک',65=>'وون کره جنوبی']);
}
function bottom_left($ex,$sensored) {
	return exchange_table_compact($ex,$sensored,[
		44=>'دلار کانادا',45=>'یوان چین',46=>'لیره ترکیه',47=>'ین ژاپن',48=>'روپیه هند',49=>'دلار استرالیا',
		50=>'کرون سوئد',51=>'فرانک سوئیس',52=>'کرون نروژ',53=>'دلار هنگ کنگ',]);
}
function exchange_table_compact($ex,$sensored,$elements){
	$out='<table class="table table-bordered">
		<tr>
			<th>نام ارز</th>
			<th>برابری با دلار</th>
			<th>قیمت خرید</th>
			<th>قیمت فروش</th>
		</tr>';
	foreach ($elements as $key=>$name){
		if ($sensored[$key]){
			$out.='<tr><td><span class="fg fg-'.$key.'"></span> '.$name.'</td>'
				.'<td'.ajk('s0:'.$key).'>'.ajv('-').'</td>'
				.'<td'.ajk('b3:'.$key).'>'.ajv('-').'</td>'
				.'<td'.ajk('s3:'.$key).'>'.ajv('-').'</td>'
			.'</tr>';  
		} else {
			$out.='<tr><td><span class="fg fg-'.$key.'"></span> '.$name.'</td>'
				.'<td'.ajk('s0:'.$key,color_code(0,$key)).'>'.ajv(number_form(1/last(0,$key,'sell'))).'</td>'
				.'<td'.ajk('b3:'.$key).'>'.ajv(number_form(last(3,$key,'buy'))).'</td>'
				.'<td'.ajk('s3:'.$key).'>'.ajv(number_form(last(3,$key,'sell'))).'</td>'
			.'</tr>';  
		}
	}
	return $out.'</table>';
}
function top_right($ex,$sensored,$type=0){
	$elements=[40=>'دلار',41=>'یورو',42=>'پوند',43=>'درهم امارات'];
	$out='<table class="table table-bordered">
		<tr>
			<th>نام ارز</th>
			<th>برابری با دلار</th>
			<th>قیمت خرید</th>
			<th>قیمت فروش</th>
			<th>نوسان فروش</th>
			'.((!$type)?'<th>قیمت حواله</th>':'').'
		</tr>';
	foreach ($elements as $key=>$name){
		if ($sensored[$key]){
			$out.='<tr><td><span class="fg fg-'.$key.'"></span> '.$name.'</td>'
				.'<td'.ajk('s0:'.$key).'>'.ajv('-').'</td>'
				.'<td'.ajk('b3:'.$key).'>'.ajv('-').'</td>'
				.'<td'.ajk('s3:'.$key).'>'.ajv('-').'</td>'
				.'<td'.ajk('c3:'.$key).'>'.ajv('-').'</td>'
				.'<td'.ajk('bm0:'.$key).'>'.ajv('-').'</td>'
			.'</tr>';  
		} else {
			$change=change_perc(last(3,$key,'yesterday'),last(3,$key,'sell'),(($key==0)?2:0),'seperated');
			$out.='<tr><td><span class="fg fg-'.$key.'"></span> '.$name.'</td>'
				.'<td'.ajk('s0:'.$key,color_code(0,$key)).'>'.ajv(number_form(1/(($key==40)?1:last(0,$key,'sell')))).'</td>'
				.'<td'.ajk('b3:'.$key).'>'.ajv(number_form(last(3,$key,'buy'))).'</td>'
				.'<td'.ajk('s3:'.$key).'>'.ajv(number_form(last(3,$key,'sell'))).'</td>'
				.'<td class="change_icon"><div '.ajk('c3'.$key,$change['class']).'>'.ajv($change['change']).'</div></td>'
				.((!$type)?'<td><a href="#contactus" class="contactnow">تماس بگیرید</a></td>':'')
			.'</tr>';  
		}
	}
	return $out.'</table>';
}
function color_code($type,$key){
	$last=(float)last($type,$key,'sell');
	$yesterday=(float)last($type,$key,'yesterday');
	if ($last<$yesterday) $out='pos';
	if ($last>$yesterday) $out='neg';
	return $out;
}
