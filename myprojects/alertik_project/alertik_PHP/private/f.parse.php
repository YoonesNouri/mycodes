<?php
/*
parse function
	data:
	where: string, step
		1. split data on specified string
		2. select step part
			1 next part
			2 second part
			0 previous part
			-1 last from the end
			-2 second from the end
	filters: same as below
	example
		data is "1<td>2<td>3<td>4<td>5<td>6"
		"<td>",0 equal to 1
		"<td>",1 equal to 2
		"<td>",-1 equal to 6
		"<td>",-2 equal to 5

filter function or 3rd argument of parse function
	data:
	filters:
		encoding: convert to utf-8
			auto: Detect encoding usuing charset in html code
			other: Use manually mentioned encoding
		html-space: convert tab and multiple spaces to one space, remove \r and \n
		no-space: remove all spaces including tab and \r and \n
		strip-tags: remove html tags
		arabic-numbers: convert arabic numbers to latin
		text: just text and not html
		url: 
		email: 
		phone: 
		remove-comments: remove comments
		remove=>: remove included characters
		number=>: number
			min-digit=>: minimum number of digit
			max-digit=>: maximum number of digit
			positive=>true: must not be negative
			int=>: it's not float
*/
function parse($data,$where,$filters=null) {
	global $error_report,$debug;
	if ($debug) echo '<textarea>'.$data.'</textarea><br />';
	foreach ($where as $key => $str) {
		if ($key%2) continue; // odd		
		if ($debug) echo 'Rule no '.$key.' - searching for  "'.htmlentities($str).'"<br />';
		if (!stristr($data,$str)) { // Record key match error
			if ($debug) echo 'match not found! <br />';
			$error_report[]='Match error on part '.$key;
			$error_report[]=$where;
			return;
		}
		if (!$part=$where[$key+1]) $part=0; // Omition on last step
		$ex=explode(strtolower($str),strtolower($data)); // Atleast two pieces ,(($part)?$part:1)+1
		if ($part<0) $part=count($ex)+$part; // Negative value for part number means from last to first
		$data=$ex[$part];
		if ($debug) echo '<textarea>'.$data.'</textarea><br />';
	}
	if (is_array($filters)) return filter($data,$filters); else	return $data;
}

function filter($data,$filters){
	foreach ($filters as $filter_key=>$filter_option){
		if ($filter_key==='encoding') {
			if ($filter_option=='auto'){
				preg_match_all('/charset=([-a-z0-9_]+)/i',$data,$charset);
				$filter_option=$charset[1][count($charset[1])-1];				
			}
			$data = iconv($filter_option, "UTF-8",  $data);		
		}	
		if ($filter_option==='html-space'||$filter_option==='text'||$filter_key==='number') {
			$data = preg_replace(array("/\t/", "/\n/", "/\r/", "/[\s\r\n\t]{2,}/"), array(" ", " ", " "," "), $data);
		}
		if ($filter_option==='no-space'||$filter_key==='phone') {
			$data = preg_replace("/[\s\r\n\t]/m","", $data);
		}
		if ($filter_key==='remove') {
			$data = preg_replace("/[".$filter_option."]/","", $data);
		}
		if ($filter_option==='strip-tags'||$filter_option==='text'||$filter_key==='number') { 
			$data=strip_tags($data);
		}
		if ($filter_option==='arabic-numbers'||$filter_key==='number') { 
			$data=preg_replace(array("/۰/","/۱/","/۲/","/۳/","/۴/","/۵/","/۶/","/۷/","/۸/","/۹/"),array("0","1","2","3","4","5","6","7","8","9"),$data);	
		}
		if ($filter_option==='remove-comments') {
			$data = preg_replace('/<!--(.|\s)*?-->/', '', $data);
		}
		if ($filter_option==='email') {
			preg_match('/[a-z]+[a-z0-9]*[@][A-z0-9_\.]{2,}[.][a-z]{2,}/im', $data,$data);
			$data=$data[0];
		}
		if ($filter_option==='url') {
			preg_match('/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/im',$data,$data);
			$data=$data[0];
		}
		if ($filter_option==='phone') {
			$data=str_replace(array('-','(',')'),array('','',''),$data);
			preg_match('/[0-9]{3,}/im', '', $data,$data);
			$data=$data[0];
		}
		if ($filter_key==='number') {
			$data=str_replace(',','',$data);
			if ($filter_option['min-digit']||$filter_option['max-digit']) $digit='{'.$filter_option['min-digit'].','.$filter_option['max-digit'].'}';
			preg_match("/(".(($filter_option['positive'])?'':'[-]?')."[0-9".(($filter_option['int'])?'':'\.')."]".$digit.")/m", $data,$data);			
			$data=$data[0];
		}
	}
	return $data;
}