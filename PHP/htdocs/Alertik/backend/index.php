<?php
include 'rc.php';
include '../lib/backend.php';

$commands=[
	['type'=>'section','title'=>'Cron Tasks'],
	['url'=>'monitor-sources.php','title'=>'Leech','blank'=>true],	
];
echo theme(array(1=>'backend',0=>'head',10=>'foot'),['commands'=>$commands,'notice'=>backend_notice()],'theme/backend');