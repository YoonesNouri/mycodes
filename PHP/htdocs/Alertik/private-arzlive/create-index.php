<?php
chdir(dirname(__FILE__));
include "rc.php";
include '../private/ati-cfg.php';
include '../private/news.php';

$settings=get_json('settings_arzlive');
$sensored=$settings['sensored'];
$notice=$settings['notice'];
$sensored=array('3:40'=>true);

$latest_exchanges=get_json('latest_exchanges');
list($date,$time)=persian_date();

html_write('index.html',
	theme([1=>'index',0=>'head',10=>'foot'],[
		'news_list'=>get_news_list(5),
		//'promoted_news_list'=>get_promoted_news_list(3),
		'latest_exchanges'=>$latest_exchanges,
		'rev'=>aj('rev',7),
		'countdown'=>aj('pg_countdown',57),
		'js'=>'var pg_countdown=57;',
		'date'=>'<span class="adt">'.aj('adt',$date).'</span> - <span class="atm">'.aj('atm',$time).'</span>',
		'notice'=>$notice,
		'ati_months'=>$ati_months,
		'ads'=>aclick_ads(4),
		'conversation_tool'=>theme('index.conversation_tool',[],$theme_folder),
		],$theme_folder
		)
	);

json_write('ajax/if.json',$ajax);
json_write('ajax/is.json',new_just_diff($ajax,'arzlive'));
