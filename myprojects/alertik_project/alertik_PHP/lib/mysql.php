<?php
/*
	v0.09
	+ Log Errors
*/

$_mysql_conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name) or die("Could not connect: ".mysqli_error($_mysql_conn));
if ($db_utf8) mysqli_set_charset($_mysql_conn,'utf8');
db_timezone($time_zone);

function db_timezone($time_zone){
	if ($time_zone) db_query('SET time_zone = "'.$time_zone.'";');
}
function db_all($rs){
	return mysqli_fetch_all(db_query($rs),MYSQLI_ASSOC);
}
function db_switch($db){
	global $_mysql_conn;
	mysqli_select_db($_mysql_conn,$db);
}
function db_array($rs){
	return mysqli_fetch_assoc($rs);
}
function db_query($sql) {
	global $_mysql_conn;
	$rs=mysqli_query($_mysql_conn,$sql) or die(mysqli_error($_mysql_conn).$sql);
	return $rs;
}
function db_multi_query($sql) {
	global $_mysql_conn;
	mysqli_multi_query($_mysql_conn,$sql) or die(mysqli_error($_mysql_conn).$sql);
	while (mysqli_more_results($_mysql_conn) && mysqli_next_result($_mysql_conn));
}
function db_safe($i){
	global $_mysql_conn;
	return mysqli_real_escape_string($_mysql_conn,$i);
}
function db_last_id(){
	global $_mysql_conn;
	return mysqli_insert_id($_mysql_conn);
}
function get_cfg($M){
	$rs=db_query("SELECT value FROM `cfg` WHERE name='".$M."' ");
	if ($row=mysqli_fetch_row($rs)) return $row[0];
}
function set_cfg($M,$N){
	db_query("REPLACE INTO `cfg` SET value='".db_safe($N)."', name='".$M."' ");
}
function get_json($m,$default=array()){
	if ($tmp=get_cfg($m)) return json_decoder($tmp,true); else return $default;
}
function json_encoder($data){
	return json_encode($data);
}
function json_decoder($data,$is_array=true){
	if ($data[0]!="{"&&strlen($data)>20){
		$data=gzuncompress(base64_decode($data));
	}
	return json_decode($data,$is_array);

}
function json_compact($array){
	$d = json_encode($array,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	if (strlen($d)<=100) return $d;
	return base64_encode(gzcompress($d));
}
function set_json($m,$n){
	set_cfg($m,json_compact($n));
}
function db_json_encoder($i){
	return db_safe(json_compact($i));
}
function db_row($sql){
	$rs=db_query($sql);
	if ($row=db_array($rs)) return $row;
	return false;
}
function db_count($rs){
	return mysqli_num_rows($rs);
}
function db_replace($table,$data = []){
	foreach ($data as $value) {
		if (is_array($value)) $value = json_encoder($value);
		if (is_string($value)) $value = '"'.db_safe($value).'"';
		$values[] = $value;
	}
	db_query('REPLACE INTO '.$table.' ('.implode(',',array_keys($data)).') VALUES ('.implode(',',$values).')');
}

