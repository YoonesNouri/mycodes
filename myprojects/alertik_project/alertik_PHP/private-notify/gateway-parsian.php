<?php
function start_transfer($i){
	list($url,$content,$header)=bank_gateway_start_transfer($i);
	$response=leech_url(array('url'=>$url,'timeout'=>10,'post'=>$content,'max_try'=>1,'header'=>$header));	
	if ($response['success']) {
		$xml_response=bank_gateway_parse_transfer($response['content']);		
		$xml_response['redirect']='https://www.pecco24.com/pecpaymentgateway/default.aspx?au='.$xml_response['transaction_id'];
		return array_merge($response,$xml_response);
	}
	$response['msg']='مشکلی در درگاه بانک وجود دارد لطفا مجددا سعی نمایید.';	
	return $response;
}
function finalize_transfer($transaction_id){
	list($url,$content,$header)=bank_gateway_get_status($transaction_id);
	$response=leech_url(array('url'=>$url,'timeout'=>10,'post'=>$content,'max_try'=>1,'header'=>$header));	
	if ($response['success']) {
		$xml_response=bank_gateway_parse_status($response['content']);		
		return array_merge($response,$xml_response);
	}
	$response['msg']='مشکلی در درگاه بانک وجود دارد لطفا مجددا سعی نمایید.';	
	return $response;
}
function bank_gateway_get_status($transaction_id){
	$url='https://www.pecco24.com:27635/pecpaymentgateway/eshopservice.asmx?wsdl';
	$content='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <PinPaymentEnquiry xmlns="http://tempuri.org/">
      <pin>lHVkM215RohHfwsrd21b</pin>
      <authority>'.$transaction_id.'</authority>
      <status>1</status>
    </PinPaymentEnquiry>
  </soap12:Body>
</soap12:Envelope>';    
	$header=array('Content-Type: application/soap+xml; charset=utf-8', 'Content-Length: '.strlen($content));
	return array($url,$content,$header);
}
function bank_gateway_start_transfer($i){
	$url='https://www.pecco24.com:27635/pecpaymentgateway/eshopservice.asmx?wsdl';
	$content='<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <PinPaymentRequest xmlns="http://tempuri.org/">
      <pin>lHVkM215RohHfwsrd21b</pin>
      <amount>'.$i['amount'].'</amount>
      <orderId>'.$i['transfer_id'].'</orderId>
      <callbackUrl>http://www.tgju.net/order.php</callbackUrl>
      <authority>0</authority>
      <status>1</status>
    </PinPaymentRequest>
  </soap12:Body>
</soap12:Envelope>';    
	$header=array('Content-Type: application/soap+xml; charset=utf-8', 'Content-Length: '.strlen($content));
	return array($url,$content,$header);
}
function bank_gateway_parse_status($data){
	$status=xml_val('Status',$data);
	$success=0;
	if ($status==1) $msg='انتقال وارد شرایط نا مشخصی شده است و صورت نگرفت.';
	elseif ($status==2) $msg='انتقال به دلیل گذشت زمان صورت نگرفت.';
	elseif ($status==10) $msg='شماره کارت شما اشتباه وارد شده است.';
	elseif ($status==11) $msg='کارت شما باطل شده است.';
	elseif ($status==12) $msg='پسورد کارت شما اشتباه می باشد.';
	elseif ($status==13) $msg='مبلغ صحیح نبوده و یا موجودی کافی نداشته اید.';
	elseif ($status==14) $msg='مبلغ مشخص شده بیش از حداکثر مبلغ قابل برداشت از طرف ما می باشد.';
	elseif ($status==15) $msg='سقف برداشت روزانه شما پر شده است.';
	elseif ($status==16) $msg='خطا در اطلاعات کارت شما.';
	elseif ($status==17) $msg='کارت مورد نظر قابل استفاده در بانک پارسیان نمی باشد.';
	elseif ($status==18) $msg='انتقال نا معتبر شده است.';
	elseif ($status>49&&$status<60) $msg='نتیجه نهایی انتقال مشخص نشده است.';
	elseif ($status==30) $msg='مبلغ قبلا برداشت شده است.';
	else{
		$msg='خطای نامشخصی در دریافت اطلاعات وضعیت پرداخت صورت گرفته است.';
	}
	if($status==0) {
		$success=1;	
		$msg='Fine';
	}
	return array('status'=>$status,'success'=>$success,'msg'=>$msg);	
}
function bank_gateway_parse_transfer($data){
	$status=xml_val('Status',$data);
	$success=0;
	if ($status==20||$status==22){
		$msg='مشکلی در حساب سایت وجود دارد.';
	} elseif  ($status==30){
		$msg='این عملیات قبلا با موفقیت انجام شده است.';
	} elseif  ($status==34){
		$msg='شماره تراکنش فروشنده درست نمی باشد.';
	} else{
		$msg='خطای نامشخصی در دریافت اطلاعات اولیه از بانک صورت گرفت';
	}
	if($status==0) {
		$success=1;
		$msg='Fine';
	}
	
	return array('transaction_id'=>xml_val('Authority',$data),'status'=>$status,'success'=>$success,'msg'=>$msg);	
}