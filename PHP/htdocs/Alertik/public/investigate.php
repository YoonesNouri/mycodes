<?php
include "../private/db.php";
include "../private/backend.php";
//lastest_exchange();
//daily_rates_today();
//daily_rates_before();
//last_fetch();

function last_fetch(){
	if ($tmp=getCFG('last_fetch')) {
		$last_fetch=json_decode($tmp,true);
		$last_fetch['8:3:47']=21.01;
		$last_fetch['3:47']=21.01;
		$last_fetch['8:3:47:t']=1338883022;
		setCFG('last_fetch',json_encode($last_fetch));
	}
}
function lastest_exchange() {
	if ($tmp=getCFG('latest_exchanges')) {
		$daily=json_decode($tmp,true);
		print_r($daily['3:2']);
		print_r($daily['3:3']);
		//return;
		//$daily['3:10']=array('timestamp'=>'1338636009','sell'=>685,'buy'=>0);
		//$daily['3:11']=array('timestamp'=>'1338636009','sell'=>680,'buy'=>0);
		print_r($daily['3:2']);
		print_r($daily['3:3']);		
		//setCFG('latest_exchanges',json_encode($daily));
	}
}
function daily_rates_before(){
	if ($tmp=getCFG('daily_rates')) {
		$daily=json_decode($tmp,true);
		print_r($daily['prev']['3:2']);
		print_r($daily['prev']['3:3']);
		$daily['prev']['3:2']['min']=300;
		$daily['prev']['3:2']['last_sell']=300;
		$daily['prev']['3:3']['min']=69.255;
		$daily['prev']['3:3']['last_sell']=69.255;
		print_r($daily['prev']['3:2']);
		print_r($daily['prev']['3:3']);
		//$daily['today']['3:11']['min']=680;
		setCFG('daily_rates',json_encode($daily));
		//print_r($daily);
	}
}
function daily_rates_today(){
	if ($tmp=getCFG('daily_rates')) {
		$daily=json_decode($tmp,true);
		print_r($daily['today']['3:2']);
		print_r($daily['today']['3:3']);
		$daily['today']['3:2']['min']=300;
		$daily['today']['3:2']['last_sell']=300;
		$daily['today']['3:3']['min']=69.255;
		$daily['today']['3:3']['last_sell']=69.255;
		print_r($daily['today']['3:2']);
		print_r($daily['today']['3:3']);
		//$daily['today']['3:11']['min']=680;
		setCFG('daily_rates',json_encode($daily));
		//print_r($daily);
	}
}