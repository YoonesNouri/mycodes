<?php
include 'notify-backend.php';
include 'lib-user.php';
include 'lib-sms.php';

$ch = curl_init();
list($url,$content,$header)=sms_gateway_report(418478);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$content);
curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
$result= curl_exec ($ch);
curl_close ($ch);
print $result;
