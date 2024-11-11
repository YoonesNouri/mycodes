<?php
/*
leech_url
	Input array
		url: url to get
		max_try: number of try on fail (default: 2)
		timeout: timeout in seconds (default: 10)
		useragent: user agent (default: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0a2) Gecko/20110612 Firefox/6.0a2)
		cookie: cookie to send
		referer: referer to send
		post: list of fields to send
	Output array
		content: response content
		success: request is successful
		http_code: reponse code
		+ other curl specific variables
	Example
		$response=leech_url(array('url'=>'http://www.google.com'));
		
leech_urls
	Input array requests
		callback: callback function name (default: call leech_finish(request,response) function)
		+ All of leech_url options except max_try is usable
		+ Given urls should be unique
	Input array options
		max_connetion: Maximum number of connection (default: 3)
		+ All of leech_url options except max_try is usable
	Example
		leech_urls(array(array('url'=>'http://www.google.com','callback'=>'google'),array('url'=>'http://www.yahoo.com')));
*/	
function leech_urls($requests,$options=array()) {
	/*		Maximum number of connections 	*/
	$options['max_connetion']=(($options['max_connetion'])?$options['max_connetion']:3);
    $rolling_window = (sizeof($requests) < $options['max_connetion']) ? sizeof($requests) : $options['max_connetion'];	
	$element=0;
    $master = curl_multi_init();
	
    /*		First batch of requests		*/
    for ($i = 0; $i < $rolling_window; $i++) {
        if (!$request=$requests[$element++]) break;
		$ch = curl_init();		
		$ch=_leech_options_set($ch,$request,$options);
        curl_multi_add_handle($master, $ch);
    }

    do {
        while(($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
        if($execrun != CURLM_OK) break;
        /*		A request was just completed -- find out which one		*/
        while($done = curl_multi_info_read($master)) {
            $response = curl_getinfo($done['handle']);
			/* 		Start a new request (it's important to do this before removing the old one)		*/
			if ($request=$requests[$element++]) {				
				$ch = curl_init();
				$ch=_leech_options_set($ch,$request,$options);
				curl_multi_add_handle($master, $ch);			
			}
			$response['content'] = curl_multi_getcontent($done['handle']);
			_leech_urls_process_result($response,$requests);
			/* 		Remove the curl handle that just completed		*/
			curl_multi_remove_handle($master, $done['handle']);
        }
		if ($running) curl_multi_select($master); // Wait until activity detection
    } while ($running);
    curl_multi_close($master);
    return $report;
}	
function leech_url($i){
	$i['max_try']=(($i['max_try'])?$i['max_try']:2);
	while ($try++<$i['max_try']){
		$ch = curl_init();
		$ch = _leech_options_set($ch,$i);
		$data = curl_exec ($ch);
		$out = curl_getinfo($ch);
		curl_close ($ch);
		$out['content']=$data;
		//if ($debug)	echo "&bull; ".$url."  "."- ".$out['http_code']." / ".ceil(strlen($data)/1024)."KB / ".$out['total_time']; 			
		if ($out['http_code']>199&&$out['http_code']<400) {
			$out['success']=true;
			break;
		}
	}
	return $out;
}
function _leech_urls_process_result($response,$requests){	
	/*		Find out which request has been finished		*/
	foreach ($requests as $request){
		if ($response['url']==$request['url']) {
			$finished_request=$request;
			break;
		}
	}
	if (!$finished_request) echo 'No match found for url. Please, fix error in code';
	if ($response['http_code']>199&&$response['http_code']<400) $finished_request['success']=true;
	// else record_problem('send_fail',print_r(curl_error($ch),true).print_r($info,true).print_r(curl_multi_getcontent($done['handle']),true));	
	if (function_exists($finished_request['callback'])) $finished_request['callback']($finished_request,$response);
	elseif (function_exists('leech_finish')) leech_finish($finished_request,$response);
}
function _leech_options_set($ch,$i,$parent_options=null){
	if ($parent_options) $i=array_merge($parent_options,$i);
	$i['timeout']=(($i['timeout'])?$i['timeout']:10);
	$i['useragent']=(($i['useragent'])?$i['useragent']:'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0a2) Gecko/20110612 Firefox/6.0a2');
	curl_setopt($ch, CURLOPT_USERAGENT,$i['useragent']);
	curl_setopt($ch, CURLOPT_TIMEOUT,$i['timeout']);
	curl_setopt($ch, CURLOPT_URL,$i['url']);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);		
	if ($i['cookie']) curl_setopt($ch, CURLOPT_COOKIE,$i['cookie']);
	if ($i['referer']) curl_setopt($ch, CURLOPT_REFERER, $i['referer']);
	if (isset($i['post'])) {
		curl_setopt($ch,CURLOPT_POST,0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $i['post']);
	}
	return $ch;
}