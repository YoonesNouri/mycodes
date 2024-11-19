<?php
include 'notify-backend.php';
include 'lib-user.php';
include 'lib-sms.php';
// turn off the WSDL cache
$parameters['username'] = "varasteh";
$parameters['password'] = "0444";
$parameters['recId'] = array(0);


$ch = curl_init();
list($url,$content,$header)=sms_gateway2(418478);
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$content);
curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
$result= curl_exec ($ch);
curl_close ($ch);
print $result;


//send_sms(array(array('sms_id'=>0,'content'=>"US$ 21,940\nBours 246,490 11:05 9/6\nSeke Azadi 8,380,000 15:15\n۱۵:۲٠",'phone_no'=>'9121028944')));
function sms_gateway2($i){
	$url='http://panel.aradsms.ir/post/send.asmx?wsdl';
	$content='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <GetDelivery xmlns="http://tempuri.org/">
      <recId>'.$i.'</recId>
    </GetDelivery>
  </soap12:Body>
</soap12:Envelope>';    
	//30005086868992
	//50100009
	$header=array('Content-Type: application/soap+xml; charset=utf-8', 'Content-Length: '.strlen($content));
	return array($url,$content,$header);
}