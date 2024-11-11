<?php
$dev=true;
$db_server ='127.0.0.1';
$db_user='exchange';
$db_pass='zISOlaQo';
$db_name='exchange';
$time_zone='Asia/Tehran';
$root='/home/web/mazanex.com/';
if ($dev){
	$root='/home/web/mazanex/';
	$db_user='root' ;
	$db_pass='';
	$time_zone='Asia/Tehran';
}
$theme_folder='theme/';
date_default_timezone_set($time_zone);

$users=[
	'shahin'=>['pass'=>'sssss','id'=>1,'name'=>'Shahin'],
	'maryam'=>['pass'=>'8406773','id'=>2,'name'=>'Maryam'],
	'masi'=>['pass'=>'Masi00MasS','id'=>3,'name'=>'Masoumeh'],
	'efazati'=>['pass'=>'ok1ok2ok3','id'=>4,'name'=>'efazati'],
];
