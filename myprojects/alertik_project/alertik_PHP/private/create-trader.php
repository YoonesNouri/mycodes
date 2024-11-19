<?php
chdir(dirname(__FILE__));
include "rc.php";
include "news.php";
include "create-js-update.php";
$required=['0:1','3:3'];

$rs=db_query('SELECT rate_type,currency_id,DATE_FORMAT(addedon,"%Y%m%d") as date,"00000" as time,start,max,min,end,"0" as vol FROM exch_archive 
WHERE duration_type = 0 AND ((rate_type = 0 AND currency_id = 1) OR (rate_type = 3 AND currency_id = 2) OR (rate_type = 3 AND currency_id = 11))
ORDER BY addedon, currency_id;');

$files['ounce']='<DTYYYYMMDD>,<TIME>,<OPEN>,<HIGH>,<LOW>,<CLOSE>,<VOL>'."\r\n";
$files['mesghal']='<DTYYYYMMDD>,<TIME>,<OPEN>,<HIGH>,<LOW>,<CLOSE>,<VOL>'."\r\n";
$files['usd']='<DTYYYYMMDD>,<TIME>,<OPEN>,<HIGH>,<LOW>,<CLOSE>,<VOL>'."\r\n";
$files['emami']='<DTYYYYMMDD>,<TIME>,<OPEN>,<HIGH>,<LOW>,<CLOSE>,<VOL>'."\r\n";

while($row=db_array($rs)){
    $t=$row['rate_type'];
    $c=$row['currency_id'];
    unset($row['rate_type']);
    unset($row['currency_id']);
    if ($t==3 && $c==11) $name = 'emami';
    if ($t==0 && $c==1) {        
        $name = 'ounce';
        //41.47 ounce to gram
        //4.33 mesghal to gram
        //9.577367206 mesghal to ounce
        //0.104412829 ounce to mesghal
        $ounce['start'] = $row['start']*0.104412829;
        $ounce['end'] = $row['end']*0.104412829;
        $ounce['min'] = $row['min']*0.104412829;
        $ounce['max'] = $row['max']*0.104412829;
    }
    if ($t==3 && $c==2) {        
        $name = 'mesghal';
        $row['start'] = $row['start']*10000;
        $row['end'] = $row['end']*10000;
        $row['min'] = $row['min']*10000;
        $row['max'] = $row['max']*10000;
        $usd=$row;
        $usd['start']=$usd['start']/$ounce['start'];
        $usd['end']=$usd['end']/$ounce['end'];
        $usd['min']=$usd['min']/$ounce['min'];
        $usd['max']=$usd['max']/$ounce['max'];
        $files['usd'].=implode(',',$usd)."\r\n";
    }
    $files[$name].=implode(',',$row)."\r\n";
}

foreach ($files as $name=>$data){
    write_archive($name,$data);
}

function write_archive($name,$data){
	file_write($name.'-mar.txt',$data);
}
