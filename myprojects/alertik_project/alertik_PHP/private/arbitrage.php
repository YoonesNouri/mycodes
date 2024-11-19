<?php
chdir(dirname(__FILE__));
include "rc.php";
$coin = 0;
$gold = 0;

db_query('SET sql_mode = "";');

$rs=db_query('SELECT currency_id, avg(sell) as price, date(addedon) as dateofprice FROM exch_rate 
    WHERE rate_type = 3 AND (currency_id = 2 OR currency_id = 11) GROUP BY date(addedon),currency_id ORDER BY addedon, currency_id;');

while($row=db_array($rs)){
    if ($row['currency_id']==11) {
        $gold = round($row['price']*10000);
        render_row($coin,$gold,$row);
        $coin = 0;
        $gold = 0;
        continue;
    }
    $coin = round($row['price'])*10000;
}

function render_row($coin,$gold,$row){
    if ($coin==0 || $gold ==0) return;
    $coin_mesghal=$coin*2.25404157;
    $diff=round(100-$coin_mesghal*100/$gold);
    echo $row["dateofprice"].'   '.$coin.'  |  '.$gold.'  |  '.$diff."\n";//.'  |  '.round($coin_mesghal).'  |  '.'  |  '
}