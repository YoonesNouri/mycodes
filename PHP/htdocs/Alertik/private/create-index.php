<?php
chdir(dirname(__FILE__));
include "rc.php";
include "news.php";
include "create-js-update.php";
$sensored=array('3:40'=>true);
//,'3:41'=>true,'3:42'=>true,'3:43'=>true,'3:44'=>true,'3:45'=>true,'20:40'=>true,'1:41'=>true,'1:43'=>true,'1:42'=>true
if ($daily=get_json('daily_rates')) {
	$before=$daily['prev'];
	$today=$daily['today'];
}
$earnings=get_JSON('earnings');
$latest_exchanges=get_JSON('latest_exchanges');
list($date,$time)=persian_date();
html_write('index.html',
	theme([1=>'index',0=>'head',10=>'foot'],[
		'news_list'=>get_news_list(6),
		'promoted_news_list'=>get_promoted_news_list(4),
		'latest_exchanges'=>$latest_exchanges,
		'rev'=>aj('rev',8),
		'countdown'=>aj('pg_countdown',57),
		'js'=>'var earnings='.json_encode(aj('earnings',$earnings)).';var pg_countdown=57;',
		'date'=>'<span class="adt">'.aj('adt',$date).'</span> - <span class="atm">'.aj('atm',$time).'</span>',
		'notice'=>'',//notice_simple('درصورتی که به دلیل فیلتر بودن سایت برای ورود به سایت با مشکل روبرو هستید. میتوانید از وب سایت <a href="http://www.arzlive.com">ارز لایو</a> استفاده نمایید','success')
		'ati_months'=>$ati_months,
		'ads'=>aclick_ads(1),
		'before'=>$before,
		'today'=>$today,
		'timemachine_html'=>theme('index.timemachine',[],$theme_folder),
		//'vote'=>theme('index.vote',[],$theme_folder),
		'gold_tool'=>theme('index.gold_tool',[],$theme_folder),
		//'conversation_tool'=>theme('index.conversation_tool',$theme_folder),
		'archive_timestamp'=>aj('tt',floor(time()/60)),
		],$theme_folder
		)
	);
json_write('ajax/if.json',$ajax);
json_write('ajax/is.json',new_just_diff($ajax,'arzlive'));
create_js_update($latest_exchanges,$before);
create_api();
//write_archive($ajax,json_encode(just_data($ajax),JSON_UNESCAPED_UNICODE),$pg['archive_timestamp']);

function write_archive($type,$data,$timestamp){
	global $root;
	$name=str_split($timestamp,2);
	$folder='public/ajax/'.$type.'/'.$name[0].'/'.$name[1].'/'.$name[2];
	if (!file_exists($root.$folder)) mkdir($root.$folder,0777,true);
	file_write($folder.'/'.$name[3].'.json',$data);
	file_write($folder.'.json',$data);
}
