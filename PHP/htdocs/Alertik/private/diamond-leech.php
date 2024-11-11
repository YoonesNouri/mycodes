<?php
ini_set('max_execution_time','1000'); 
include "db.php";
include "backend.php";
include "func-leech.php";
$debug=false;
$count=getCFG("fetch:ct");
if (!($count%30)) {
	pricescope_parse();
	diamond_price();
}
if (is_array($error_report)) print_r($error_report);
/*
	$diamond[cut][weight-start][color][clarity] = $ price
*/
function pricescope_parse(){
	global $price;
	list($usable,$data,$info)=GetURL('http://www.pricescope.com/diamond-prices/round','',60);
	if (!$usable) return $error_report[]=$info;	
	$price=getJSON('diamond-price');
	$cut='round';
	$parts=array('0.3'=>'0.3-0.37',
	'0.38'=>'0.38-0.45',
	'0.46'=>'0.46-0.49',
	'0.5'=>'0.5-0.69',
	'0.7'=>'0.7-0.89',
	'0.9'=>'0.9-0.99',
	'1'=>'1-1.49',
	'1.5'=>'1.5-1.99',
	'2'=>'2-2.99',
	'3'=>'3-3.99',
	'4'=>'4-4.99',
	'5'=>'5-5.99');	
	list(,$data)=explode('Scroll down to see all the diamond ranges or click below to view the loose diamond price table.',$data);
	list($data,)=explode('"sidebar"',$data);
	list(,$data)=explode('"dps-win-block"',$data);
	foreach ($parts as $key => $val) {
		list(,$table)=explode($val,$data);
		list($table,)=explode('</table>',$table);
		$price_array=extract_number_array($table,'<td>','</td>');
		$price[$cut][$key]=prepare_price_array($price[$cut][$key],$price_array);
	}
	setJSON('diamond-price',$price);
}
function prepare_price_array($current,$price_array){
	$clarities=array('FL','IF','VVS1','VVS2','VS1','VS2','SI1','SI2','SI3','I1','I2','I3');
	$colors=array('D','E','F','G','H','I','J','K','L','M','N','O');
	foreach ($colors as $color) {
		foreach ($clarities as $clarity) {
			$count++;
			$price=$price_array[$count];
			if (!$price) continue;
			$current[$clarity][$color]=$price;
		}
	}
	return $current;
}

function extract_number_array($data,$start,$end){
	$parts=explode($start,$data);
	foreach ($parts as $key => $part) {
		list($part)=explode($end,$part);		
		preg_match("/([0-9.]+)/", strip_tags($part),$extracted);
		$array[]=$extracted[0];
	}
	return $array;
}

function diamond_price(){
	global $price;
	$diamond_stat=getJSON('diamond-price-tg');
	$diamonds=array(
		'dia1'=>array("1",'VVS1','G',1),
		'dia2'=>array("0.9",'SI2','J',0.99),
		'dia3'=>array("0.46",'VVS2','H',0.49),
	);
	foreach ($diamonds as $d_key=>$d){
		$sell=$price['round'][$d[0]][$d[1]][$d[2]]*$d[3];
		$current=$diamond_stat[$d_key];
		if ($current['sell']!=$sell) {
			if ($sell<$current['min'] || !$current['min']) $current['min']=$sell;
			if ($sell>$current['max']) $current['max']=$sell;
			$diamond_stat[$d_key]=array('sell'=>$sell,'timestamp'=>time(),'min'=>$current['min'],'max'=>$current['max'],'yesterday'=>$current['yesterday']);
		}
		
	}
	setJSON('diamond-price-tg',$diamond_stat);
}