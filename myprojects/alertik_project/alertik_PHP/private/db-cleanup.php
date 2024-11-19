<?php
/*
	Archive backlog belong to previous days
	Archive exch_rate older than 2 or 40 days based on requirements
*/

include "db.php";
include "backend.php";

backlog_cleanup();
exch_rate_cleanup();
ime_cleanup();

function exch_rate_cleanup() {
	global $root;
	$file=$root.'/data/exch_rate.'.date("Y-m-d").'.sql';
	file_put_contents($file,exch_rate_dump());
	exec('gzip -9 '.$file);
	exch_rate_delete();
}
function exch_rate_delete() {
	db_query("DELETE FROM exch_rate WHERE 
		(addedon < DATE_SUB(NOW(),INTERVAL 35 DAY));");  
	db_query("OPTIMIZE TABLE exch_rate;");  
}
function exch_rate_dump() {
	$rs = db_query("SELECT * FROM exch_rate WHERE addedon < DATE_SUB(NOW(),INTERVAL 35 DAY);");  
  	while ($row = db_array($rs)) {		
		$out.=(($out)?',(':'(');
		foreach ($row as $key => $rr) 
			$out.=(($key<>0)?',':'')."'".db_safe($rr)."'";
		$out.=")";		
	}	
	return (($out)?"REPLACE INTO `exch_rate` VALUES ".$out.";":"");
}

function ime_cleanup() {
	global $root;
	$file=$root.'/data/ime.'.date("Y-m-d").'.sql';
	file_put_contents($file,ime_dump());
	exec('gzip -9 '.$file);
	ime_delete();
}
function ime_delete() {
	db_query("DELETE FROM ime WHERE 
		(addedon < DATE_SUB(NOW(),INTERVAL 35 DAY));");  
	db_query("OPTIMIZE TABLE ime;");  
}
function ime_dump() {
	$rs = db_query("SELECT * FROM ime WHERE addedon < DATE_SUB(NOW(),INTERVAL 35 DAY);");  
  	while ($row = db_array($rs)) {		
		$out.=(($out)?',(':'(');
		foreach ($row as $key => $rr) 
			$out.=(($key<>0)?',':'')."'".db_safe($rr)."'";
		$out.=")";		
	}	
	return (($out)?"REPLACE INTO `ime` VALUES ".$out.";":"");
}

function backlog_cleanup() {
	global $root;
	$file=$root.'/data/backlog.'.date("Y-m-d").'.sql';
	file_put_contents($file,backlog_dump());
	exec('gzip -9 '.$file);
	backlog_delete();
}
function backlog_delete() {
	db_query("DELETE FROM backlog WHERE addedon < DATE_SUB(NOW(),INTERVAL 1 DAY);");  
	db_query("OPTIMIZE TABLE backlog;");  
}
function backlog_dump() {
	$rs = db_query("SELECT * FROM backlog WHERE addedon < DATE_SUB(NOW(),INTERVAL 1 DAY);");  
  	while ($row = db_array($rs)) {		
		$out.=(($out)?',(':'(');
		foreach ($row as $key => $rr) 
			$out.=(($key<>0)?',':'')."'".db_safe($rr)."'";
		$out.=")";		
	}	
	return (($out)?"REPLACE INTO `backlog` VALUES ".$out.";":"");
}