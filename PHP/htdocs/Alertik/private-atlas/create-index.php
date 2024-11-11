<?php
chdir(dirname(__FILE__));
include "rc.php";

$settings=get_json('settings_atlas');
$sensored=$settings['sensored'];
$notice=$settings['notice'];

$latest_exchanges=get_json('latest_exchanges');
list($date,$time)=persian_date();

$vars=['exchange'=>$latest_exchanges,
	'rev'=>aj('rev',1),
	'countdown'=>aj('pg_countdown',57),
	'js'=>'var pg_countdown=57;',
	'date'=>'<span class="adt">'.aj('adt',$date).'</span> - <span class="atm">'.aj('atm',$time).'</span>',
	'notice'=>$notice,
	'dollar_chart'=>chart_data(3,40),
	];

html_write('index.html',theme([1=>'index',0=>'head',10=>'foot'],$vars,$theme_folder));
html_write('../public-andisheh/index.html',theme([1=>'and-index',0=>'and-head',10=>'and-foot'],$vars,$theme_folder));

$vars=['news'=>db_all('SELECT *,TIMESTAMPDIFF(HOUR,addedon,NOW()) as hours,
		TIMESTAMPDIFF(MINUTE,addedon,NOW()) as mins,TIMESTAMPDIFF(DAY,addedon,NOW()) as days FROM khabarist.news 
		WHERE source_id IN(9,10) ORDER BY news_id DESC LIMIT 20')];

html_write('news.html',theme([1=>'news',0=>'head',10=>'foot'],$vars,$theme_folder));
html_write('../public-andisheh/news.html',theme([1=>'news',0=>'and-head',10=>'and-foot'],$vars,$theme_folder));

json_write('ajax/if.json',$ajax);
json_write('ajax/is.json',new_just_diff($ajax,'atlas'));

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