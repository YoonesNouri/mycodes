<?php
chdir(dirname(__FILE__));
include "rc.php";
include '../private/ati-cfg.php';

$settings=get_json('settings_tg');
$sensored=$settings['sensored'];
$notice=$settings['notice'];

$latest_exchanges=get_json('latest_exchanges');
list($date,$time)=persian_date();

html_write('index.html',
	theme([1=>'index',0=>'head',10=>'foot'],[
		'latest_exchanges'=>$latest_exchanges,
		'rev'=>aj('rev',3),
		'countdown'=>aj('pg_countdown',57),
		'js'=>'var pg_countdown=57;',
		'date'=>'<span class="adt">'.aj('adt',$date).'</span> - <span class="atm">'.aj('atm',$time).'</span>',
		'ati_months'=>$ati_months,
		'notice'=>$notice,
		],'theme/tg/'
		)
	);

json_write('ajax/if.json',$ajax);
json_write('ajax/is.json',new_just_diff($ajax,'tg'));
