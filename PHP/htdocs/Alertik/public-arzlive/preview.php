<?php
include "../private-arzlive/rc.php";
include '../private/ati-cfg.php';

$settings=get_json('settings_arzlive');
$sensored=$settings['sensored'];
$notice=$settings['notice'];

$latest_exchanges=get_json('latest_exchanges');
list($date,$time)=persian_date();

echo theme([1=>'index',0=>'head',10=>'foot'],[
		'news_list'=>get_news_list(5),			
		'latest_exchanges'=>$latest_exchanges,
		'rev'=>aj('rev',1),
		'countdown'=>aj('pg_countdown',57),
		'js'=>'var pg_countdown=57;',
		'date'=>'<span class="adt">'.aj('adt',$date).'</span> - <span class="atm">'.aj('atm',$time).'</span>',
		'notice'=>$notice,
		'ati_months'=>$ati_months,
		],$theme_folder
	);
function get_news_list($limit){
	$rs=db_query('SELECT *,TIMESTAMPDIFF(MINUTE,addedon,NOW()) as mins_passed 
		FROM khabarist.news WHERE status > 0 AND duplicate_id = news_id AND category_id = 2
		ORDER BY news_id DESC LIMIT '.$limit.';');
	while ($row=db_array($rs)){
		$row['content']=json_decode($row['content'],true);
		$score=$row['positive']-$row['negative'];
		if ($score<0) $score=0;
		$news[]=['news_id'=>$row['news_id'],'source'=>source_name($row['source_id']),'time_txt'=>time_passed($row['mins_passed'])
			,'time'=>$row['mins_passed']
			,'title'=>$row['content']['title'],'link'=>$row['content']['link'],'score'=>$score,'last'=>((++$count==$limit)?1:0)];
	}
	return $news;
}
function source_name($source_id){
	$source=source_info($source_id);
	return $source['name-fa'];
}
function source_info($monitor_id=0){
	static $cache;
	if (is_array($cache[$monitor_id])) return $cache[$monitor_id];
	$rs=db_query('SELECT monitor_id,rules FROM khabarist.leech_monitor');
	while ($row=db_array($rs)){
		$cache[$row['monitor_id']]=json_decode($row['rules'],true);
	}
	if ($monitor_id) return $cache[$monitor_id];
	return $cache;
}
function time_passed($mins=0){
	if (floor($mins/1440)>0){
		return floor($mins/1440).' روز قبل';
	} elseif (floor($mins/60)>0){
		return floor($mins/60).' ساعت قبل';
	} elseif ($mins>0){
		return $mins.' دقیقه قبل';
	}
	return 'لحظاتی قبل';
}