<?php
//test(); exit;
if (iran_gov_open()){
	//if (!($count%5)) asso_parse();
	sana_parse();
	if (!($count%20)) cbi_parse();
	//if (!($count%5)) melli_parse();
}
if (iran_open()) {
	//if (livedata_open()) tablotala_parse();
	//tabloaramis_parse();
	parsi_parse();
	mirdamad_parse();
	//kitcofa_parse();
	//goldinfo_parse();
	eranico_parse();
	zcast_parse();
	if (livedata_open()) livedata_parse();
	//tala_index_parse();
	//mesghal_parse();
	//if (!($count%5)) abanexchange_parse();
}
if (!($count%2)) xe_parse();
if (!($count%10)) spider_parse();
kitco_parse();

if (!($count%2) && tse_open()) tse_parse();

//tgju_manual_parse();
function test(){
	global $save;
	//$r=leech_url(['url'=>'http://www.kitcofa.ir/','referer'=>'http://www.kitcofa.ir/','return-header'=>1]);
	//preg_match_all('/^Set-Cookie:\s*([^\r\n]*)/mi', $r['content'], $ms);
	sana_parse();
	print_r($save);exit;
	echo htmlspecialchars($data);
	print_r($ms);
	echo $ms[1][0];
	//$r=leech_url(['url'=>'http://127.0.0.1/mazanex/private/leech-res.php?new='.date('Ynjgis'),//.time()
	//$r=leech_url(['url'=>'http://www.kitcofa.ir/','referer'=>'http://www.kitcofa.ir/','return-header'=>1]);
	//preg_match_all('/^Set-Cookie:\s*([^\r\n]*)/mi', $r['content'], $ms);
	//$r=leech_url(['url'=>'http://www.kitcofa.ir/plugins/home/class/loading1.php?new='.date('Ynjgis'),//.time()
	//	'referer'=>'http://www.kitcofa.ir/',
		//'cookie'=>$ms[1][0].'; _ga=GA1.2.1327978419.1382519745',
	//	'header'=>["X-Requested-With: XMLHttpRequest","Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8"]
	//	]);*/
	//Set-Cookie: PHPSESSID=0eqnq20rm7cbfrjjrki48a8776; path=/
	/*$r=leech_url(['url'=>'http://www.kitcofa.ir/plugins/home/class/loading1.php?new=201392312461868',//.time()
		'referer'=>'http://www.kitcofa.ir',
		'cookie'=>'PHPSESSID=59gtja033pnro8pmk1evisfqe0'//$ms[1][0],
		]);*/
	print_r($r);
}
function onlinecurrency(){
	global $error_report,$save;
	list($usable,$data,$info)=GetURL('http://onlinecurrency.ir/');
	if (!$usable) return $error_report[]=$info;
	$table=focus_table('قیمت ارز در بازار ایران (تومان)',$data);
	return array('sell'=>extract_point_data(array('دلار آمريکا',1,'<td',3,'</td>'),$data)*10,'source'=>'onlinecurrency');
}
function sana_parse(){
	global $error_report,$save;
	list($usable,$data,$info)=GetURL('http://www.sanarate.ir/','',40);
	if (!$usable) return $error_report[]=$info;
	$table=focus_table('MainContent_ViewSanaRates_tblSale',$data);
	$row=explode('<tr',$table);
	$save[]=['currency_id'=>40,'rate_type'=>17,'source_id'=>36,'sell'=>extract_point_data(['<td',3],$row[count($row)-2])/10];
}
function goldinfo_parse(){
	global $error_report,$save;
	$source_id=15;
	$rate_type=3;
	list($usable,$data,$info)=GetURL('http://goldinfo.ir/prices.php?id=1/28/146/72/2/148/3/4/5/6/7/8/9/16/17/149/18/19/20/21/22/85/140/142/141/143/144/23/90/24/25&_=1402904258132');
	if (!$data=json_decode($data,true)) return;
	$strs=[28=>40,3=>10,4=>11,5=>12,6=>13,7=>14,2=>2];
	$sell= $data['d'][$remote_key]['p']/1000;
	if ($local_key>39) $sell= $data['d'][$remote_key]['p']/1;
	//$strs=[10=>'',11=>'تمام سکه بهار آزادی',12=>'sekke-nim',13=>'sekke-rob',14=>'sekke-grm'];
	if (!$usable) return $error_report[]=$info;
	foreach ($strs as $remote_key => $local_key) {
		$save[]=['currency_id'=>$local_key,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>(($local_key==40)?$sell-0:$sell)];
	}
}
function eranico_parse(){
	global $error_report,$save;
	$source_id=14;
	$rate_type=3;
	list($usable,$data,$info)=GetURL('http://www.eranico.com');
	$table=focus_table('قیمت ارز (بازار تهران - اسکناس)',$data);
	$strs=[40=>'دلار آمریکا',41=>'یورو',42=>'پوند',43=>'درهم',];
	//$strs=[10=>'',11=>'تمام سکه بهار آزادی',12=>'sekke-nim',13=>'sekke-rob',14=>'sekke-grm'];
	if (!$usable) return $error_report[]=$info;
	foreach ($strs as $key => $name) {
		$sell=extract_point_data([$name,1,'second',1,'<span>',1,'<'],$table);
		$save[]=['currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell/10];
	}
}
function zcast_parse(){
	global $error_report,$save;
	$source_id=5;
	$rate_type=3;
	list($usable,$data,$info)=GetURL('http://zcast.ir/ajax?0.'.time());
	$data=str_replace(',','',$data);
	//40=>'dolar',41=>'euro',42=>'pound',43=>'uae-derham',
	$strs=array(2=>'gold_bazartehran',10=>'sekke-gad',11=>'sekke-jad',12=>'sekke-nim',13=>'sekke-rob',14=>'sekke-grm',40=>'arz_dolar');//,41=>'arz_euro');
	if (!$usable) return $error_report[]=$info;
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array('"'.$name.'":{',1,'v":"',1,'"'),$data)/(($key==40||$key==41)?1:1000);
		if ($key==40) $sell=$sell-0;
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}
}
function tablotala_parse(){
	global $error_report,$save;
	$source_id=9;
	$rate_type=3;
	list($usable,$data,$info)=GetURL('http://tablotala.com');
	$session=extract_point_data(array("pr.Init('",1,"'"),$data);
	list($usable,$data,$info)=GetURL('http://tablotala.com/ajax/price/'.$session.'/0/');

	$strs=array(2=>'IRG17',10=>'IRCOLD',11=>'IRCNEW',12=>'IRC2',13=>'IRC4',14=>'IRCGRAM',40=>'IRUSD');
	if (!$usable) return $error_report[]=$info;
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array($name.'-',1,'-'),$data);
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell/(($key==40)?1:1000));
	}
}
function livedata_open(){
	$today=db_array(db_query('SELECT DAYOFWEEK(NOW()) as d,HOUR(NOW()) as h'));
	if ($today['d']!=6&&$today['h']>10&&$today['h']<20) return true; //Market is open
}
function tse_open(){
	$today=db_array(db_query('SELECT DAYOFWEEK(NOW()) as d,HOUR(NOW()) as h'));
	if ($today['d']!=6&&$today['d']!=5&&$today['h']>8&&$today['h']<18) return true; //Market is open
}

function iran_open(){
	$today=db_array(db_query('SELECT DAYOFWEEK(NOW()) as d,HOUR(NOW()) as h'));
	if ($today['d']!=6&&$today['h']>=11&&$today['h']<20) return true; //Market is open
}
function iran_gov_open(){
	$today=db_array(db_query('SELECT DAYOFWEEK(NOW()) as d,HOUR(NOW()) as h'));
	if ($today['d']!=6&&$today['h']>7&&$today['h']<20) return true; //Market is open
}
function mesghal_parse() {
	global $error_report,$save;
	$source_id=4;
	$rate_type=3;
	list($usable,$data,$info)=GetURL('http://www.mesghal.ir');
	if (!$usable) return $error_report[]=$info;
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/"), array("", " ", " "), $data);

	/*		Market 		*/
	$strs=array(40=>'&#1583;&#1604;&#1575;&#1585; &#1570;&#1605;&#1585;&#1610;&#1705;&#1575;',41=>'&#1610;&#1608;&#1585;&#1608;',42=>'&#1662;&#1608;&#1606;&#1583; &#1575;&#1606;&#1711;&#1604;&#1587;&#1578;&#1575;&#1606;',49=>'&#1583;&#1604;&#1575;&#1585; &#1575;&#1587;&#1578;&#1585;&#1575;&#1604;&#1610;&#1575;',51=>'&#1601;&#1585;&#1575;&#1606;&#1705; &#1587;&#1608;&#1574;&#1610;&#1587;',50=>'&#1705;&#1608;&#1585;&#1608;&#1606; &#1587;&#1608;&#1574;&#1583;',
	52=>'&#1705;&#1608;&#1585;&#1608;&#1606; &#1606;&#1585;&#1608;&#1688;',64=>'&#1705;&#1608;&#1585;&#1608;&#1606; &#1583;&#1575;&#1606;&#1605;&#1575;&#1585;&#1705;',47=>'&#1610;&#1705;&#1589;&#1583; &#1610;&#1606; &#1688;&#1575;&#1662;&#1606;',
	53=>'&#1583;&#1604;&#1575;&#1585; &#1607;&#1606;&#1711; &#1705;&#1606;&#1711;',54=>'&#1585;&#1740;&#1606;&#1711;&#1740;&#1578; &#1605;&#1575;&#1604;&#1586;&#1610;',46=>'&#1604;&#1610;&#1585; &#1578;&#1585;&#1705;&#1610;&#1607;',61=>'&#1585;&#1610;&#1575;&#1604; &#1593;&#1585;&#1576;&#1587;&#1578;&#1575;&#1606;',43=>'&#1583;&#1585;&#1607;&#1605; &#1575;&#1605;&#1575;&#1585;&#1575;&#1578;',
	56=>'&#1607;&#1586;&#1575;&#1585;&#1583;&#1610;&#1606;&#1575;&#1585; &#1593;&#1585;&#1575;&#1602;',55=>'&#1583;&#1610;&#1606;&#1575;&#1585; &#1705;&#1608;&#1610;&#1578;',45=>'&#1610;&#1608;&#1575;&#1606; &#1670;&#1610;&#1606;',48=>'&#1585;&#1608;&#1662;&#1610;&#1607; &#1607;&#1606;&#1583;',
	57=>'&#1576;&#1575;&#1578; &#1578;&#1575;&#1610;&#1604;&#1606;&#1583;',60=>'&#1575;&#1601;&#1594;&#1575;&#1606;&#1740; &#1575;&#1601;&#1594;&#1575;&#1606;&#1587;&#1578;&#1575;&#1606;',58=>'&#1585;&#1608;&#1662;&#1740;&#1607; &#1662;&#1575;&#1705;&#1587;&#1578;&#1575;&#1606;',59=>'&#1605;&#1606;&#1575;&#1578; &#1570;&#1584;&#1585;&#1576;&#1575;&#1610;&#1580;&#1575;&#1606;',44=>'&#1583;&#1604;&#1575;&#1585; &#1705;&#1575;&#1606;&#1575;&#1583;&#1575;');
	list(,$market)=explode('&#1602;&#1610;&#1605;&#1578; &#1575;&#1585;&#1586; &#1583;&#1585; &#1576;&#1575;&#1586;&#1575;&#1585; &#1575;&#1610;&#1585;&#1575;&#1606;',$data);
	foreach ($strs as $key => $name) {
		$buy=extract_point_data(array($name,1,'</td>',1,'</td>'),$market);
		$sell=extract_point_data(array($name,1,'</td>',2,'</td>'),$market);
		if ($key==47) {
			$buy=$buy/100;
			$sell=$sell/100;
		}
		if ($key==56) {
			$buy=$buy/1000;
			$sell=$sell/1000;
		}
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}

	// Gold price in iran
	$i=extract_point_data(array('&#1607;&#1585; &#1605;&#1579;&#1602;&#1575;&#1604; &#1591;&#1604;&#1575;&#1740; 17 &#1593;&#1610;&#1575;&#1585;:',2,'</tr>'),$data);
	$save[]=array('currency_id'=>2,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$i/1000);

	// Old coin
	$i=extract_point_data(array('&#1578;&#1605;&#1575;&#1605; &#1587;&#1705;&#1607; &#1576;&#1607;&#1575;&#1585; &#1570;&#1586;&#1575;&#1583;&#1740; &#1591;&#1585;&#1581; &#1602;&#1583;&#1610;&#1605;:',2,'</tr>'),$data);
	$save[]=array('currency_id'=>10,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$i/1000);
	// New coin
	$i=extract_point_data(array('&#1578;&#1605;&#1575;&#1605; &#1587;&#1705;&#1607; &#1576;&#1607;&#1575;&#1585; &#1570;&#1586;&#1575;&#1583;&#1740; &#1591;&#1585;&#1581; &#1580;&#1583;&#1610;&#1583;:',2,'</tr>'),$data);
	$save[]=array('currency_id'=>11,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$i/1000);
	// Half coin
	$i=extract_point_data(array('&#1606;&#1610;&#1605; &#1587;&#1705;&#1607; &#1576;&#1607;&#1575;&#1585; &#1570;&#1586;&#1575;&#1583;&#1740; :',2,'</tr>'),$data);
	$save[]=array('currency_id'=>12,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$i/1000);
	// Quarter coin
	$i=extract_point_data(array('&#1585;&#1576;&#1593; &#1587;&#1705;&#1607; &#1576;&#1607;&#1575;&#1585; &#1570;&#1586;&#1575;&#1583;&#1740; :',2,'</tr>'),$data);
	$save[]=array('currency_id'=>13,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$i/1000);
	// Gram coin
	$i=extract_point_data(array('&#1587;&#1705;&#1607; &#1610;&#1705; &#1711;&#1585;&#1605;&#1740; :',1,'</tr>'),$data);
	$save[]=array('currency_id'=>14,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$i/1000);


}

function asso_parse() {
	global $error_report,$save;
	$currency_array=array(40=>'6',41=>'3',42=>'4',43=>'10',46=>'18');
	//,48=>'روپيه هندوستان',49=>'دلار استراليا',50=>'کرون سوئد',51=>'فرانک سوئيس',54=>'رينگيت مالزي',61=>'ريال عربستان',47=>'ين ژاپن',64=>'کرون دانمارک',44=>'دلار کانادا',45=>'يوان چين'
	$source_id=35;
	$rate_type=16;
	list($usable,$data,$info)=GetURL('http://team.kanoonsarafan.com/public/remoteaccess/cash_price_firstpage.js');
	if (!$usable) return $error_report[]=$info;
	//$table=focus_table('tw_bestprice_table',$data);
	$table=$data;
	foreach ($currency_array as $key => $name){
		$buy=extract_point_data(array('id:'.$name,1,'buy_price:',1,','),$table)/10;
		$sell=extract_point_data(array('id:'.$name,1,'sell_price:',1,','),$table)/10;
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}
}

function tgju_manual_parse() {
	global $error_report,$save;
	$source_id=13;
	$data=getJSON('tgju_manual');
	foreach ($data as $key=>$sell){
		$key=explode('i',$key);
		list($rate_type,$currency_id)=explode('_',$key[1]);
		if (!$currency_id) continue;
		$save[]=array('currency_id'=>$currency_id,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell/(($currency_id>=40)?10:10000));
	}
}
function tabloaramis_parse() {
	global $error_report,$save;
	$currency_array=[10=>'سکه طرح قدیم',11=>'تمام سکه86 نقدی',12=>'نیم سکه تاریخ 86',13=>'ربع سکه تاریخ 86',14=>'گرمی',2=>'آبشده نقدی',40=>'دلار آمریکا'];
	$source_id=11;//56=>'دینار عراق',
	$rate_type=3;
	list($usable,$data,$info)=GetURL('http://tabloaramis.com/','','',['referer'=>'http://tabloaramis.com/']);

	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/","/,/"), array("", " ", " ",""), $data);
	if (!$usable) return $error_report[]=$info;

	foreach ($currency_array as $key => $name) {
		$sell=extract_point_data(array($name,1,'sellpItem',1,'>',1,'</'),$data)/(($key < 40)?100:1);
		if ($sell<2||!sell) continue;
		$save[]=['currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell/(($key < 40)?10:1)];
	}
}
function kitcofa_parse() {
	global $error_report,$save;
	$currency_array=array(40=>'دلار');/*,41=>'یورو',42=>'پوند',43=>'درهم',44=>'دلار کانادا',45=>'یوان چین',46=>'لیره ترک');/*,48=>'روپیه هند',
		47=>'ین ژاپن',	49=>'دلار استرالیا',50=>'کرون سوئد',51=>'فرانک سوئیس',54=>'رینگیت مالزی',55=>'دینار کویت',61=>'ریال عربستان',
		62=>'روبل روسیه',47=>'ین ژاپن',64=>'کرون دانمارک',60=>'افغانی');*/
	$source_id=10;//56=>'دینار عراق',
	$rate_type=3;

	list($usable,$data,$info)=GetURL('http://www.kitcofa.ir/','','',['referer'=>'http://www.kitcofa.ir/']);
	preg_match('/xmlhttp.open."GET",\'(.*?kitcofa.*?)\'/',$data,$matches);
	list($usable,$data,$info)=GetURL($matches[1].time(),'','',['referer'=>'http://www.kitcofa.ir/']);
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/","/,/"), array("", " ", " ",""), $data);
	if (!$usable) return $error_report[]=$info;
	if ($data[2]!='t') {
		echo 'kitcofa main leeched';
		return;
	}

	foreach ($currency_array as $key => $name) {
		$sell=(extract_point_data(array($name,1,'<td',1,'>',2,'</'),$data)/(($key < 40)?1000:1))-0;
		if ($sell<2||!sell) continue;
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell/10);
	}
}
function mirdamad_parse() {
	global $error_report,$save;
	$currency_array=[40=>'دلار آمریکا',41=>'یورو',42=>'پوند',43=>'درهم',44=>'دلار کانادا',45=>'یوان چین',46=>'لیره ترک',48=>'روپیه هند',
		47=>'ین ژاپن',	49=>'دلار استرالیا',50=>'کرون سوئد',51=>'فرانک سوئیس',54=>'رینگیت مالزی',55=>'دینار کویت',61=>'ریال عربستان',
		62=>'روبل روسیه',47=>'ین ژاپن',64=>'کرون دانمارک',60=>'افغانی'];/*);*/
	$source_id=16;
	$rate_type=3;

	list($usable,$data,$info)=GetURL('http://mirdamadexchange.com/','','',[]);
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/","/,/"), array("", " ", " ",""), $data);
	list(,$data)=explode("var XSelect_",$data);
	foreach ($currency_array as $key => $name) {
		$sell=((int)extract_point_data([$name,1,"''",4],$data)/(($key < 40)?10000:10));
		if ($sell<2||!sell) continue;
		if ($key==40) $sell-=3;
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}
}
function parsi_parse() {
	global $error_report,$save;
	$currency_array=[40=>'دلار آمریکا',41=>'یورو',42=>'پوند',43=>'درهم',44=>'دلار کانادا',45=>'یوان چین',46=>'لیر ترک',48=>'روپیه هند',
		47=>'ین ژاپن',	49=>'دلار استرالیا',50=>'کرون سوئد',54=>'رینگیت مالزی',61=>'ریال عربستان',
		47=>'ین ژاپن'];
	//,51=>'فرانک سوئیس',55=>'دینار کویت',62=>'روبل روسیه',,64=>'کرون دانمارک',60=>'افغانی'
	$source_id=17;
	$rate_type=3;

	list($usable,$data,$info)=GetURL('http://www.sarafiparsi.com/wp-content/plugins/currency-to-IRR/currate.html','','',[]);
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/","/,/"), array("", " ", " ",""), $data);

	foreach ($currency_array as $key => $name) {
		$sell=((int)extract_point_data([$name,1,'<td>',2],$data)/(($key < 40)?1000:1));
		if ($sell<2||!sell) continue;
		if ($key==40) $sell-=3;
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}
}
function livedata_parse() {
	global $error_report,$save;
	//$currency_array=array(40=>'USD',41=>'EUR',42=>'GBP',43=>'AED',44=>'CAD',45=>'CNY',46=>'TRY',48=>'INR',49=>'AUD',50=>'SEK',51=>'CHF',54=>'MYR',55=>'KWD',56=>'IQD',61=>'SAR',62=>'RUB',63=>'SGD');//,47=>'JPY',52=>'NOK',53=>'HKD',57=>'THB',58=>'PKR',59=>'AZN',60=>'AFN',64=>'DKK',65=>'KRW'
	$currency_array=array(40=>'دلار آمریکا',41=>'یورو',42=>'پوند',43=>'درهم');//,44=>'دلار کانادا',45=>'یوان چین',46=>'لیر ترک',48=>'روپیه هند',49=>'دلار استرالیا',50=>'کرون سوئد',51=>'فرانک سوئیس',54=>'رینگیت مالزی',55=>'دینار کویت',56=>'دینار عراق',61=>'ریال عربستان',62=>'روبل روسیه',63=>'دلار سنگاپور',47=>'ین ژاپن',53=>'دلار هنگ کنگ',64=>'کرون دانمارک');
	//,52=>'NOK',57=>'THB',58=>'PKR',59=>'AZN',60=>'AFN',65=>'KRW'
	$source_id=8;
	$rate_type=3;
	list($usable,$data,$info)=GetURL('http://www.livedata.ir/');

	if (!$usable) return $error_report[]=$info;

	$strs=[2=>'s_1006',10=>'s_1002',11=>'s_1001',12=>'s_1003',13=>'s_1004',14=>'s_1005',40=>'s_2001',41=>'s_2002',42=>'s_2003',43=>'s_2004',];
	$data=str_replace(',', '', $data);
	foreach ($strs as $key => $name) {
		$sell=extract_point_data([$name,1,'>',1,'</td'],$data)/(($key < 40)?1000:1);
		if ($sell<2||!sell) continue;
		if ($key==40) $sell=$sell-0;
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}
}


function spider_parse() {
	global $error_report,$save;
	$source_id=32;
	$rate_type=13;
	$currency_id=113;
	list($usable,$data,$info)=GetURL('http://www.spdrgoldshares.com/ajax/home/','',10);
	if (!$usable) return $error_report[]=$info;
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/","/,/"), array("", " ", " ",""), $data);
	$sell=extract_point_data(array('<ajaxTotalTonnes>',1,'</ajaxTotalTonnes>'),$data);
	$save[]=array('currency_id'=>$currency_id,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>0,'sell'=>$sell);
}
function tse_parse() {
	global $error_report,$save;
	$source_id=33;
	$rate_type=13;
	$currency_id=114;
	list($usable,$data,$info)=GetURL('http://new.tse.ir/json/HomePage/plot.json','',5);
	if (!$usable) return $error_report[]=$info;
	$sell=extract_point_data(['"yData":[',1,','],$data);
	$save[]=array('currency_id'=>$currency_id,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>0,'sell'=>$sell);
}
function cbi_parse() {
	global $error_report,$save;
	$source_id=34;
	$rate_type=2;
	$strs=[40=>'USD',41=>'EUR',42=>'GBP',43=>'AED',45=>'CNY',46=>'TRY',48=>'INR',62=>'RUB',47=>'JPY100',65=>'KRW1000'];
	list($usable,$data,$info)=GetURL('http://cbi.ir/exrates/rates_fa.aspx','',5);
	if (!$usable) return $error_report[]=$info;
	$data=focus_table('class="Modules"',$data);
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array($name,1,'<td>',1,'</td>'),$data)/10;
		if ($sell<2||!sell) continue;
		if ($key==65) $sell=$sell/1000;
		if ($key==47) $sell=$sell/100;
		$save[]=['currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>0,'sell'=>$sell];
	}
}

function tala_parse() {
	global $error_report,$save;
	$source_id=5;
	$rate_type=3;
	$strs=array(40=>'قیمت دلار-بازار',41=>'قیمت یورو-بازارتهران',42=>'قیمت پوند-بازارتهران',43=>'قیمت درهم امارات',2=>'قیمت طلا بازار تهران',10=>'قیمت سکه طرح قدیم',11=>'قیمت سکه طرح جدید',12=>'قیمت نیم سکه',13=>'قیمت ربع سکه',14=>'قیمت سکه گرمی');
	list($usable,$data,$info)=GetURL('http://tala.ir/fullprice.php','',40);
	if (!$usable) return $error_report[]=$info;
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/"), array("", " ", " "), $data);
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array($name,1,'</td>',1),$data)/(($key < 40)?10000:10);
		if ($sell<2||!sell) continue;
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}
}
function tala_index_parse() {
	global $error_report,$save;
	$source_id=5;
	$rate_type=3;
	$strs=array(//40=>'dolar',41=>'euro',42=>'pound',43=>'uae-derham',
		2=>'bazartehran',10=>'sekke-gad',11=>'sekke-jad',12=>'sekke-nim',13=>'sekke-rob',14=>'sekke-grm');
	list($usable,$data,$info)=GetURL('http://www.talaserver.com/webservice/price_live.php','',40);
	$table=focus_table('قیمت زنده طلا / ارز	سایت طلا',$data);
	if (!$usable) return $error_report[]=$info;
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/"), array("", " ", " "), $data);
	foreach ($strs as $key => $name) {
		$sell=extract_point_data(array('id="'.$name.'"',1,'</td>'),$data)/(($key < 40)?10000:10);
		//echo $name.':'.$sell."\n";
		if ($sell<2||!sell) continue;
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}
}

function melli_parse() {
	global $error_report,$save;
	$source_id=6;
	$rate_type=2;
	$strs=array(11=>'سکه تمام بهار',12=>'سکه نیم بهار',13=>'سکه ربع بهار',14=>'سکه گرمی',41=>'یورو',40=>'دلار آمريکا',42=>'پوند انگلیس',44=>'دلار کانادا',61=>'ریال عربستان',43=>'درهم امارات',45=>'یوان چین',47=>'یکصد ین ژاپن',51=>'فرانک سوئیس',50=>'کرون سوئد',52=>'کرون نروژ',64=>'کرون دانمارک',53=>'دلار هنگ كنگ ',62=>'روبل روسيه',65=>'هزار وون كره جنوبي',46=>'لير تركيه');
	//,49=>'دلار استرالیا'
	list($usable,$data,$info)=GetURL('http://www.bmi.ir/fa/curr.aspx','',5);
	if (!$usable) return $error_report[]=$info;
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/", "/,/"), array("", " ", " ",""), $data);
	$data = preg_replace('/<!--(.|\s)*?-->/', '', $data);
	foreach ($strs as $key => $name) {
		if ($key < 40) {
			$sell=extract_point_data(array($name,1,'</td>',3,'</td>'),$data,true)/10000;
		} else {
			$buy=extract_point_data(array($name,1,'</td>',1,'</td>'),$data,true)/10;
			$sell=extract_point_data(array($name,1,'</td>',2,'</td>'),$data,true)/10;
		}
		if ($key==47) {
			$buy=$buy/100;
			$sell=$sell/100;
		}
		if ($key==65) {
			$buy=$buy/1000;
			$sell=$sell/1000;
		}
		if ($sell>0) $save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
		else $off=true;
	}
	if ($off) echo '[6]';
}
function xe_parse() {
	global $error_report,$save,$currency_array;
	$source_id=3;
	$rate_type=0;
	list($usable,$data,$info)=GetURL('http://www.xe.com/currencytables/?basecur=USD');
	if (!$usable) return $error_report[]=$info;
	$data = preg_replace(array("/\t/", "/\s{2,}/", "/\n/", "/,/"), array("", " ", " ",""), $data);
	$data = preg_replace('/<!--(.|\s)*?-->/', '', $data);
	list(,$data)=explode("<table id='historicalRateTbl'",$data);
	list($data)=explode("</table>",$data);
	foreach ($currency_array as $key => $name) {
		if ($key<=40) continue;
		//if ($name=='AZN') continue;
		$save[]=array('currency_id'=>$key,'source_id'=>$source_id,'rate_type'=>$rate_type,'sell'=>extract_point_data(array($name.'<',1,'</td>',2),$data));
	}
}
function kitco_parse() {
	global $error_report,$save;
	$source_id=1;
	$rate_type=0;
	list($usable,$data_org,$info)=GetURL('http://www.kitco.com/market/');
	if (!$usable) return $error_report[]=$info;
	$data=focus_table('The World Spot Price - Asia/Europe/NY markets',$data_org);
	// Gold Market
	$buy=extract_point_data(array('gold</a>',1,'</td>',3),$data);
	$sell=extract_point_data(array('gold</a>',1,'</td>',4),$data);
	$save[]=array('currency_id'=>1,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$sell,'buy'=>$buy);
	$buy=extract_point_data(array('silver</a>',1,'</td>',3),$data);
	$sell=extract_point_data(array('silver</a>',1,'</td>',4),$data);
	$save[]=array('currency_id'=>5,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$sell,'buy'=>$buy);
	$buy=extract_point_data(array('PLATINUM</a>',1,'</td>',3),$data);
	$sell=extract_point_data(array('PLATINUM</a>',1,'</td>',4),$data);
	$save[]=array('currency_id'=>6,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$sell,'buy'=>$buy);
	$buy=extract_point_data(array('PALLADIUM</a>',1,'</td>',3),$data);
	$sell=extract_point_data(array('PALLADIUM</a>',1,'</td>',4),$data);
	$save[]=array('currency_id'=>7,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$sell,'buy'=>$buy);

	//$sell=extract_point_data(array('Crude Oil</a>',1,'</td>',1),$data_org);
	//$save[]=array('currency_id'=>8,'rate_type'=>$rate_type,'source_id'=>$source_id,'sell'=>$sell);
}
function abanexchange_parse() {
	global $error_report,$save;
	$source_id=7;
	list($usable,$data,$info)=GetURL('http://abanexchange.com/currency');
	if (!$usable) return $error_report[]=$info;
	$data = preg_replace(array("/٠/", "/۱/","/۲/", "/٣/", "/۴/", "/۵/", "/٦/", "/٧/", "/٨/", "/۹/","/\t/", "/٬/", "/\n/", "/\r/"), array("0", "1", "2",'3','4','5','6','7','8','9',"", "", " ",''), $data);

	list(,$market)=explode('مشاهده نرخ ارز',$data);
	$rate_type=3;
	$strs=array(40=>'دلار آمريکا',42=>'پوند انگليس' ,51=>'فرانک سويس',50=>'کرون سوئد',52=>'کرون نروژ' ,64=>'کرون دانمارک' ,43=>'درهم امارات متحده عربی',55=>'دينار کويت' ,47=>'یکصد ين ژاپن',53=>'دلار هنگ کنگ' ,44=>'دلار کانادا' ,46=>'لير ترکيه',62=>'روبل روسيه',49=>'دلار استراليا',61=>'ريال سعودی',63=>'دلار سنگاپور',45=>'یوان چين' ,54=>'رينگيت مالزی',41=>'یورو');

	foreach ($strs as $key => $name) {
		$buy=extract_point_data(array($name,1,'</td>',1,'</td>'),$market)/10;
		$sell=extract_point_data(array($name,1,'</td>',2,'</td>'),$market)/10;
		if ($sell>0); else continue;
		if ($key==47) {
			$buy=$buy/100;
			$sell=$sell/100;
		}
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}

	list(,$market)=explode('نرخ خرید و فروش حواله ',$data);
	$rate_type=4;
	$strs=array(40=>'دلار آمريکا',42=>'پوند انگليس' ,43=>'درهم امارات متحده عربی',44=>'دلار کانادا' ,49=>'دلار استراليا',41=>'یورو');
	foreach ($strs as $key => $name) {
		$buy=extract_point_data(array($name,1,'</td>',1,'</td>'),$market)/10;
		$sell=extract_point_data(array($name,1,'</td>',2,'</td>'),$market)/10;
		if ($sell>0); else continue;
		if ($key==47) {
			$buy=$buy/100;
			$sell=$sell/100;
		}
		$save[]=array('currency_id'=>$key,'rate_type'=>$rate_type,'source_id'=>$source_id,'buy'=>$buy,'sell'=>$sell);
	}
}
