<?php
include "../private-atlas/rc.php";

$settings=get_json('settings_atlas');
$sensored=$settings['sensored'];
$notice=$settings['notice'];

$latest_exchanges=get_json('latest_exchanges');
list($date,$time)=persian_date();

echo theme([1=>'index',0=>'head',10=>'foot'],[
		'exchange'=>$latest_exchanges,
		'rev'=>aj('rev',1),
		'countdown'=>aj('pg_countdown',57),
		'js'=>'var pg_countdown=57;',
		'date'=>'<span class="adt">'.aj('adt',$date).'</span> - <span class="atm">'.aj('atm',$time).'</span>',
		'notice'=>$notice,
		'dollar_chart'=>chart_data(3,40),
		//'euro_chart'=>chart_data(3,41),
		],$theme_folder
		);

function chart_data($rate_type,$currency_id){
	$rs=db_query('SELECT sell,UNIX_TIMESTAMP(addedon) as tm, day(addedon) as dd FROM exch_rate WHERE rate_type='.$rate_type.' AND currency_id = '.$currency_id.' ORDER BY addedon DESC LIMIT 20;');
	while ($row=db_array($rs)) { 
		if (!$current_day) $current_day=$row['dd'];
		$counter[$row['dd']]++;
		// Just display 2 days at max
		if (count($counter)>2) break;
		// If the last day have more than 5 results do not display even previous one
		if ($current_day!=$row['dd']&&$counter[$current_day]>3) break;
		$data[]=[$row['tm']*1000,$row['sell']*10];
	}
	krsort($data);
	foreach ($data as $value) {
		$arr[]=$value;
	}
	return json_encode($arr);
}