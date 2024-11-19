<?php
chdir(dirname(__FILE__));
include "../private/rc.php";
include '../private/ati-cfg.php';
include '../private/news.php';

$rate_type=13;$currency_id=114;
$days_before=7;
//echo 'SELECT sell FROM exch_rate WHERE rate_type='.$rate_type.' AND currency_id='.$currency_id.' AND TIMESTAMPDIFF(DAY,addedon,NOW())>'.($days_before-1).' ORDER BY addedon DESC LIMIT 1;';
//echo db_row('SELECT sell FROM exch_rate WHERE rate_type='.$rate_type.' AND currency_id='.$currency_id.' AND TIMESTAMPDIFF(DAY,addedon,NOW())>'.($days_before-1).' ORDER BY addedon DESC LIMIT 1;')['sell'];
echo past_price($rate_type,$currency_id,$days_before);
echo 'Price'.$price=last($rate_type,$currency_id,'sell');

$week_price=past_price($rate_type,$currency_id,7);
echo 'profit'.$profit_week=($price*10000000/$week_price);
$change_week=change_perc(10000000,$profit_week,0,'percentage');
print_r($change_week);

$week2_price=past_price($rate_type,$currency_id,14);
$profit2_week=($price*10000000/$week2_price);
$change2_week=change_perc(10000000,$profit2_week,0,'percentage');

$month_price=past_price($rate_type,$currency_id,30);
$profit_month=($price*10000000/$month_price);
$change_month=change_perc(10000000,$profit_month,0,'percentage');
