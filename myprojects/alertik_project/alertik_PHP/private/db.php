<?php
$root='/home/web/mazanex.com/';
$conn = mysqli_connect('127.0.0.1:3306', 'exchange', 'zISOlaQo') or die(mysqli_error()."Could not connect-old");
mysqli_select_db($conn,'exchange');
function db_zone($country=''){
	if (!$country){
		//db_query('SET time_zone = "Asia/Tehran";');
		date_default_timezone_set("Asia/Tehran");
		db_query('SET time_zone = "Asia/Tehran";');

	}
	if ($country=='tr'){
		//db_query('SET time_zone = "Asia/Istanbul";');
		date_default_timezone_set("Asia/Istanbul");
		db_query('SET time_zone = "+02:00";');
	}
}
function db_array($rs){
	return mysqli_fetch_array($rs);
}
function db_query($sql) {
	global $conn;
	$rs=mysqli_query($conn,$sql) or die(mysqli_error().$sql);
	return $rs;
}
function db_safe($i){
	global $conn;
	return mysqli_real_escape_string($conn,$i);
}

function GetCFG($M){
	$rs=db_query("SELECT value FROM `cfg` WHERE name='".$M."' ");
	if ($row=mysqli_fetch_array($rs)) return $row[0];
}
function getJSON($m,$default=array()){
	if ($tmp=getCFG($m)) return json_decode($tmp,true); else return $default;
}
function setJSON($m,$n){
	setCFG($m,json_encode($n,true));
}
function SetCFG($M,$N){
	db_query("REPLACE INTO `cfg` SET value='".db_safe($N)."', name='".$M."' ");
}
function file_write($filename,$content,$compress=false){
	global $root;
	$filename=$root.$filename;
	if ($compress==true) {
		$content=preg_replace('~>\s+<~', '><', $content);
		$content=str_replace('</td>', '', $content);
		$content=str_replace('</tr>', '', $content);
		$content=str_replace('</th>', '', $content);
		$content=str_replace('</li>', '', $content);
		$content=str_replace('</option>', '', $content);
	}
	file_put_contents($filename.'.tmp',$content);
	rename($filename.'.tmp',$filename); // Atomic Update
}