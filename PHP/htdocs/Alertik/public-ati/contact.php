<?php
include "../private-arzlive/rc.php";

if (strlen($_POST['message'])>10) {
	echo '<br /><strong>با تشکر از شما<br />پیغام شما دریافت شد</strong><br /><br />';
	$url = 'http://panel.bemoghe.com/request.php';

	$_POST['source_id']=8;
	$options = array(
	    'http' => array(
	        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        'method'  => 'POST',
	        'content' => http_build_query(array_merge($_SERVER,$_POST)),
	    ),
	);
	$context  = stream_context_create($options);
	file_get_contents($url, false, $context);
	exit;
}
echo theme([1=>'contact',0=>'head',10=>'foot'],[],$theme_folder);
