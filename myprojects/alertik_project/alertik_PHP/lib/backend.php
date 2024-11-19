<?php
run_commands();

function run_commands(){
	if (isset($_GET['server_status'])){
		exit(server_status());
	} elseif (isset($_GET['empty_cron_log'])){
		exit(empty_cron());
	}
}

function empty_cron(){
	file_put_contents('../logs/cron-'.CONF_TYPE.'.txt','');
	return notice('Crontab Log File Truncated.');
}

function server_status(){
	return cpu_usage().' '.ram_usage();
}
function cpu_usage(){
	if (function_exists('sys_getloadavg')) $load = sys_getloadavg();
	return 'CPU load avg '.$load[0].' '.$load[1].' '.$load[2];
}
function ram_usage(){
	return exec("cat /proc/meminfo | grep 'MemFree'");
}
function backend_notice($msg='',$type='success'){
	static $msgs;
	if ($msg) $msgs.='<div class="alert alert-'.$type.'">'.$msg.'</div>';
	return $msgs;
}