<?php
$gateway='arad';
$master_id=array(1=>true,2=>true,3=>true);
//$sms_gateway='http://127.0.0.1/www/mazanex/public-notify/recorder.php?fromnumber=0000&password=0&username=0&';
list($ati_names,$ati_codes,$ati_keys,$two_way)=ati_keys();
$notify_rates=array(
	//'cat2'=>'ارزها',
	/*'0:3:40'=>'دلار آزاد',
	'0:3:41'=>'یورو',
	'0:3:42'=>'پوند انگلیس',
	'0:3:43'=>'درهم امارات',*/
	'cat1'=>'سکه نقدی',
	'0:3:10'=>'سکه آزادی',	
	'0:3:11'=>'سکه تمام',
	'0:3:12'=>'سکه نیم',
	'0:3:13'=>'سکه ربع',
	'0:3:14'=>'سکه گرمی',
	'cat2'=>'طلا',
	'0:3:2'=>'مثقال طلا',
	'0:3:3'=>'گرم طلای 18',
	'0:0:1'=>'اونس طلا',
	'cat6'=>'سایر موارد',
	'0:13:114'=>'شاخص بورس تهران',
	'cat5'=>'بورس آتی سکه',
);
$notify_rates=array_merge($notify_rates,$ati_names);
$notice_balance=5;
//$free_two_way_sms=2;
$sms_length=130;
$trigger_types=array(
	1 =>'نرخ مشخص',
	2=>'میزان تغییرات',
	3=>'میزان تغییرات',
	4=>'ساعات مشخص',
	5=>'ساعات مشخص',
	6=>'بازه زمانی',
	7=>'بازه زمانی'
);
$sms_keywords_legend=array(
	/*'cat1'=>'ارز در بازار آزاد',
	'0:3:40'=>'دلار آمریکا',
	//'0:3:65'=>'وون کره',
	'0:3:64'=>'کرون دانمارک',
	'0:3:63'=>'دلار سنگاپور',
	'0:3:62'=>'روبل روسیه',
	'0:3:61'=>'ریال عربستان',
	'0:3:56'=>'دینار عراق',
	'0:3:55'=>'دینار کویت',
	'0:3:54'=>'رینگیت مالزی',
	'0:3:53'=>'دلار هنگ کنگ',
	'0:3:51'=>'فرانک سویس',
	'0:3:50'=>'کرون سوئد',
	'0:3:49'=>'دلار استرالیا',
	'0:3:48'=>'روپیه هند',
	'0:3:47'=>'ین ژاپن',
	'0:3:46'=>'لیر ترکیه',
	'0:3:45'=>'یوان چین',
	'0:3:44'=>'دلار کانادا',
	'0:3:43'=>'درهم امارات',
	'0:3:42'=>'پوند انگلیس',
	'0:3:41'=>'یورو',*/
	
	'cat2'=>'سکه در بازار تهران',
	'0:3:14'=>'سکه گرمی',
	'0:3:13'=>'سکه ربع',
	'0:3:12'=>'سکه نیم',
	'0:3:11'=>'سکه تمام',
	'0:3:10'=>'سکه آزادی',
	
	'cat4'=>'طلا',
	'0:3:3'=>'گرم طلای 18',
	'0:0:1'=>'اونس',
	'0:3:2'=>'مثقال',
	
	'cat5'=>'موارد دیگر',
	'0:13:114'=>'شاخص بورس تهران',
	//'0:13:113'=>'صندوق اسپایدر',

	'cat3'=>'بورس آتی سکه',
	
);
$sms_keywords_legend=array_merge($sms_keywords_legend,$ati_names);

$sms_keywords_name=array(
	/*'0:3:40'=>'US$',
	//'0:3:65'=>'Wonn',
	'0:3:64'=>'Danmark Kron',
	'0:3:63'=>'Singapore$',
	'0:3:62'=>'Ruble',
	'0:3:61'=>'Riyal',
	'0:3:56'=>'Iraq Dinar',
	'0:3:55'=>'Kuwait Dinar',
	'0:3:54'=>'Ringgit',
	'0:3:53'=>'HK$',
	'0:3:51'=>'Swiss Franc',
	'0:3:50'=>'Swed Kron',
	'0:3:49'=>'Australia$',
	'0:3:48'=>'India Rupee',
	'0:3:47'=>'Japan Yen',
	'0:3:46'=>'Turk Lira',
	'0:3:45'=>'Yuan',
	'0:3:44'=>'Canada$',
	'0:3:43'=>'Derham',
	'0:3:42'=>'Pound',
	'0:3:41'=>'Euro',*/
	
	'0:3:14'=>'SGram',
	'0:3:13'=>'Rob',
	'0:3:12'=>'Nim',
	'0:3:11'=>'Tamam',
	'0:3:10'=>'A.zadi',
	
	'0:3:3'=>'Tala18',
	'0:0:1'=>'Ounce',
	'0:3:2'=>'Mesghal',
	
	'0:13:114'=>'Bours',
	'0:13:113'=>'Spider',
		
	'0:0:5'=>'Silver',
	'0:0:6'=>'Platinium',
	'0:0:7'=>'Palladium',
	'0:0:8'=>'Oil',
	
);
$sms_keywords_name=array_merge($sms_keywords_name,$ati_codes);

$price_list=array(
	'ti1'=>array('title'=>'سرویس 30 روزه دریافت قیمت سکه و طلا در ساعات مشخص','price'=>65000,'days'=>30,'sms'=>0,'package_id'=>1),
	'ti4'=>array('title'=>'سرویس 120 روزه دریافت قیمت سکه و طلا در ساعات مشخص','price'=>248000,'days'=>120,'sms'=>0,'package_id'=>1),
	'tg1'=>array('title'=>'سرویس 30 روزه دریافت قیمت طلا در ساعات مشخص','price'=>65000,'days'=>30,'sms'=>0,'package_id'=>2),
	'tg4'=>array('title'=>'سرویس 120 روزه دریافت قیمت طلا در ساعات مشخص','price'=>248000,'days'=>120,'sms'=>0,'package_id'=>2),
	'tc1'=>array('title'=>'سرویس 30 روزه دریافت قیمت سکه در ساعات مشخص','price'=>91000,'days'=>30,'sms'=>0,'package_id'=>3),
	'tc4'=>array('title'=>'سرویس 120 روزه دریافت قیمت سکه در ساعات مشخص','price'=>347000,'days'=>120,'sms'=>0,'package_id'=>3),
	
	'ci1'=>array('title'=>'سرویس 30 روزه دریافت قیمت طلا و سکه بر اساس میزان نوسانات','price'=>105000,'days'=>30,'sms'=>0,'package_id'=>11),
	'ci4'=>array('title'=>'سرویس 120 روزه دریافت قیمت طلا و سکه بر اساس میزان نوسانات','price'=>399000,'days'=>120,'sms'=>0,'package_id'=>11),
	'cg1'=>array('title'=>'سرویس 30 روزه دریافت قیمت طلا بر اساس میزان نوسانات','price'=>105000,'days'=>30,'sms'=>0,'package_id'=>12),
	'cg4'=>array('title'=>'سرویس 120 روزه دریافت قیمت طلا بر اساس میزان نوسانات','price'=>399000,'days'=>120,'sms'=>0,'package_id'=>12),
	'cc1'=>array('title'=>'سرویس 30 روزه دریافت قیمت سکه بر اساس میزان نوسانات','price'=>147000,'days'=>30,'sms'=>0,'package_id'=>13),
	'cc4'=>array('title'=>'سرویس 120 روزه دریافت قیمت سکه بر اساس میزان نوسانات','price'=>558000,'days'=>120,'sms'=>0,'package_id'=>13),
	//'ca1'=>array('title'=>'سرویس 30 روزه دریافت قیمت آتی بر اساس میزان نوسانات','price'=>154000,'days'=>30,'sms'=>0,'package_id'=>14),
	//'ca4'=>array('title'=>'سرویس 120 روزه دریافت قیمت آتی بر اساس میزان نوسانات','price'=>585000,'days'=>120,'sms'=>0,'package_id'=>14),

	'ss4'=>array('title'=>'سرویس قابل تنظیم پایه','price'=>66000,'days'=>120,'sms'=>60,'trigger_id'=>0,'package_id'=>0),
	'sm4'=>array('title'=>'سرویس قابل تنظیم مقدماتی','price'=>189000,'days'=>120,'sms'=>180,'trigger_id'=>0,'package_id'=>0),
	'sb4'=>array('title'=>'سرویس قابل تنظیم پیشرفته','price'=>510000,'days'=>120,'sms'=>540,'trigger_id'=>0,'package_id'=>0),
);
function ati_keys(){
	include '../private/ati-cfg.php';
	foreach ($ati_months as $key => $ati_month){
		list($rate_type,$currency_id)=explode('_',$key);
		$codes['1:'.$rate_type.':'.$currency_id]=$ati_month['id'];
		//$two_way[$ati_month['title']]='1:'.$rate_type.':'.$currency_id;
		$two_way[$ati_month['id']]='1:'.$rate_type.':'.$currency_id;
		$legend['1:'.$rate_type.':'.$currency_id]='سکه '.$ati_month['title'];
		$keys.=(($keys)?'|':'').'1:'.$rate_type.':'.$currency_id;
	}
	return array($legend,$codes,$keys,$two_way);
}