<?php
function focus_table($name,$data){
	if ($name) {
		list(,$data)=explode(strtolower($name),strtolower($data));
		list($data)=explode('</table>',$data);
	}
	return preg_replace(	array("/\t/","/\s{2,}/","/\n/","/۰/","/۱/","/۲/","/۳/","/۴/","/۵/","/۶/","/۷/","/۸/","/۹/","/,/"), 
							array(""," "," ","0","1","2","3","4","5","6","7","8","9",""),$data);	
}
function rounding($n,$digit=5){
	$tmp=$digit-strlen(floor($n));
	return round($n*pow(10,$tmp))/pow(10,$tmp);
}
/*
		Don't break tags e.g. <td> is correct but not <td 
		$strs is array of strings to explode and part no. of text which you need to obtain
		part no. 0 means previous part, 1 next part, ...
*/
function extract_point_data($strs,$data,$suspend_error=false) {
	global $error_report,$debug;
	foreach ($strs as $key => $str) {
		if ($key%2) continue; // odd		
		if ($debug) echo 'Rule no "'.$key.'" - searching for  "'.htmlentities($str).'" to extract part "'.$part.'<br />' ;
		if ($debug) echo 'Source Data: <textarea>'.$data.'</textarea><br />';
		if (!stristr($data,$str)) { // Record key match error
			if ($debug) echo 'match not found! <br />';
			if (!$suspend_error) {
				$error_report[]='Match error on part '.$key;
				$error_report[]=$strs;
			}
			return;
		}
		if (!$part=$strs[$key+1]) $part=0; // Omition on last step
		$ex=explode(strtolower($str),strtolower($data)); // Atleast two pieces ,(($part)?$part:1)+1
		if ($part<0) $part=count($ex)+$part; // Negative value for part number means from last to first
		$data=$ex[$part];
		if ($debug) echo 'Result: <textarea>'.$data.'</textarea><br />';
	}
	//$data = preg_replace(array("/\t/", "/\s/", "/\n/", "/\r/", "/ /"), array("", "", "","",""), strip_tags($data[0]));	
	preg_match("/([0-9.]+)/", strip_tags($data),$data);
	//echo '<textarea>'.$data[0].'</textarea>';
	if ($debug) echo '<br /><br />';
	return $data[0];
}

function GetURL($url,$cookie='',$timeout=10,$settings=array()){
	global $debug;
	$max_try=5;
	
	while ($info['http_code']!=200&&$try++<$max_try){
		$ch = curl_init();
		if ($cookie) curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0a2) Gecko/20110612 Firefox/6.0a2");
		curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_URL,$url);
		if ($settings['referer']) {
			curl_setopt($ch, CURLOPT_REFERER, $settings['referer']);
		}
		if ($settings['post']) {
			//curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $settings['post']);
		}

		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		if ($settings['header']){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $settings['header']);
		}
		$data = curl_exec ($ch);
		$info = curl_getinfo($ch);
		curl_close ($ch);
		if ($debug){
			echo "&bull; ".$url."  "."- ".$info['http_code']." / ".ceil(strlen($data)/1024)."KB / ".$info['total_time']; 			
			flush();
		}
	}
	return array((($info['http_code']==200 && strlen($data)>0)?true:false),$data,$info);
}