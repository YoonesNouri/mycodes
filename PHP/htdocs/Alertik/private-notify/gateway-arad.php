<?php
$active_phone_no='10007880';/* First one always will be used so fix array to */
$gateways=array('10007880','30005086868992','50100009','10007880');
function sms_gateway($i){
	global $active_phone_no,$gateways;
	$timeout=3;
	if ($i['send_by']) {
		$active_phone=$gateways[($i['send_by']-1)];
	} else {
		$active_phone=$active_phone_no;
	}
	if ($active_phone!=$active_phone_no) $timeout=7;
	
	$url='http://panel.aradsms.ir/post/sendSMS.ashx?from='.$active_phone.'&to='.$i['phone_no'].'&text='.urlencode($i['content']).'&username=tugold&password=6871';
	return array($url,$content,$header,$timeout);
}
function gateway_status($text){	
	if (stristr($text,'1-')) return 20;
	record_problem('gate_way_response',$text);					
	return 21;
}
function sms_gateway_arad_soap($i){
	global $active_phone_no;
	$url='http://panel.aradsms.ir/post/send.asmx?wsdl';
	$content='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <SendSms xmlns="http://tempuri.org/">
      <username>tugold</username>
      <password>6871</password>
      <to>
        <string>0'.$i['phone_no'].'</string>
      </to>
      <from>'.$active_phone_no.'</from>
      <text>'.$i['content'].'</text>
      <recId>
        <long>'.$i['sms_id'].'</long>
      </recId>
	  <udh></udh>
	  <status>'.(0x0).'</status>
    </SendSms>
  </soap12:Body>
</soap12:Envelope>';    
	$header=array('Content-Type: application/soap+xml; charset=utf-8', 'Content-Length: '.strlen($content));
	return array($url,$content,$header);
}
function gateway_status_soap($text){	
	if (stristr($text,'<SendSmsResult>1</SendSmsResult>')) return 200; else {
		record_problem('gate_way_response',$text);					
	}
	return 100;
}
function line_choose($i){
	return 1;
	if ($i['send_by']==2) {
		if (!$i['phone_no']){
			if ($i['uid']) {
				$i['phone_no']=get_phone_no($i['uid']);
			} else return 2;
		}
		if ($i['phone_no'][1]!=1) return 1;
		return 2;
	}
	if (!$i['send_by']||$i['send_by']==1){
		if ($i['line_status']==200&&$i['phone_no'][1]==1) return 2; // Just for hamrahe aval choose 2
	}
	return 1;
}
function sms_gateway_get($number_to_get){
	global $active_phone_no;
	if ($number_to_get>1000) $number_to_get=1000;
	if (!$number_to_get) $number_to_get=1;
	$url='http://panel.aradsms.ir/post/send.asmx?wsdl';
	$content='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <getMessages xmlns="http://tempuri.org/">
      <username>tugold</username>
      <password>6871</password>
      <location>2</location>
      <from>'.$active_phone_no.'</from>
      <index>0</index>
      <count>'.$number_to_get.'</count>
    </getMessages>
  </soap12:Body>
</soap12:Envelope>';    
	$header=array('Content-Type: application/soap+xml; charset=utf-8', 'Content-Length: '.strlen($content));
	return array($url,$content,$header);
}
function sms_gateway_report($rec_id){
	global $active_phone_no;
	$url='http://panel.aradsms.ir/post/send.asmx?wsdl';
	$content='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <GetDelivery xmlns="http://tempuri.org/">
      <recId>'.$rec_id.'</recId>
    </GetDelivery>
  </soap12:Body>
</soap12:Envelope>';   
echo $content; 
	$header=array('Content-Type: application/soap+xml; charset=utf-8', 'Content-Length: '.strlen($content));
	return array($url,$content,$header);
}
function sms_gateway_get_parse($data){
	return array('content'=>xml_val('Body',$data),'track_id'=>xml_val('MsgID',$data),'status'=>xml_val('RecSuccess',$data));	
}