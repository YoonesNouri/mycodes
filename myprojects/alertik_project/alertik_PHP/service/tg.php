<?php
preg_match('/[0-9.,:_"{}]{500,}/', @file_get_contents('http://www.mazanex.com/ext/p.js'),$rates);
$rates=@json_decode($rates[0],true);
/*
از متغییر های زیر میتونید استفاده کنید
$rates['0_1']	انس
$rates['3_2']	مثقال طلا
$rates['3_3']	طلای 18
$rates['3_10']	سکه طرح قدیم
$rates['3_11']	سکه طرح جدید
$rates['3_12']	سکه نیم
$rates['3_13']	سکه ربع
$rates['3_14']	سکه گرمی
$rates['2_40']	دلار بانکی
$rates['2_41']	یورو بانکی
$rates['2_42']	پوند بانکی
$rates['3_40']	دلار آزاد
$rates['3_41']	یورو آزاد
$rates['3_42']	پوند آزاد
*/