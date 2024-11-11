<?php
include "../conf.php";
include '../lib/backend.php';
include '../lib/web.php';
include '../lib/mysql.php';
$commands=[
	['type'=>'section','title'=>'Cron Tasks'],
	['url'=>'list-cfg.php','title'=>'CFG Editor','blank'=>true],
	['url'=>'?empty_cron_log=1','title'=>'Empty Cron Log'],
];

source_monitor_statistics(
	['news'=>
		['query'=>'SELECT source_id as provider_id,count(*) as number FROM backlog WHERE addedon > DATE_SUB(NOW(),INTERVAL [day] DAY) GROUP BY source_id',
		'replace'=>['day',30,1]],
	]);

echo theme(array(1=>'backend',0=>'head',10=>'foot'),['commands'=>$commands],'theme/backend');

function source_monitor_statistics($monitors){
	foreach ($monitors as $type => $monitor){
		$rs=db_query(str_replace('['.$monitor['replace'][0].']', $monitor['replace'][1], $monitor['query']));
		while($row=db_array($rs)){
			$list[$row['provider_id']]=$row['number'];
		}
		$rs=db_query(str_replace('['.$monitor['replace'][0].']', $monitor['replace'][2], $monitor['query']));
		while($row=db_array($rs)){
			unset($list[$row['provider_id']]);
		}
		foreach ($list as $provider_id => $value) {
			backend_notice('Provider ID '.$provider_id.' '.$type.' number is down from '.round($value/30),'error');
		}		
	}
}