<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-sms.php';

$request[]=parse_currency($_GET['trigger_target']);
if (is_array($_GET['currency_addition'])) foreach ($_GET['currency_addition'] as $input){
	if (!$input) continue;
	$request[]=parse_currency($input);
}

/*		Prepare Result		*/
list(,$sms)=prepare_sms($request,false,true);
echo str_replace("\n",'<br />',$sms);
