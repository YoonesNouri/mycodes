<?php
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

page($page);
function page($pg){
	global $user;
	$content=$pg['content'].'</div><div class="clear"><input type="hidden" id="type" value="'.$pg['ajax'].'" />';
	if(is_ajax()) {
		echo $content;
		return;
	}
	if (!$pg['title']) $pg['title']='پنل پیامک اتحادیه';
	$page='<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
<meta charset="utf-8">
<title>'.$pg['title'].'</title>
<link href="/s/s.css?1" media="all" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="/s/s.js?4" type="text/javascript"></script>
<script type="text/javascript"> var _gaq = _gaq || []; _gaq.push([\'_setAccount\', \'UA-26522562-8\']); _gaq.push([\'_trackPageview\']); </script>
</head><body>
<div id="fnav_wrapper"><div id="fnav"><a href="http://www.tgju.ir"></a><a href="http://www.tgju.org"></a><a href="http://www.tgju.net"></a><a href="http://www.nimset.ir"></a></div></div>
<div id="header"><div id="logo_area"><a href="/"><img alt="TGJU" src="/s/h.png"></a></div></div>
<div id="nav_wrapper"><div id="nav">
	<a href="/'.(($user['uid'])?'dashboard.php':'').'">صفحه اصلی</a>	
	<a href="/faq.html">سوالات متداول</a>
	<a href="/agreement.html">شرایط و مقررات</a>
	<a href="/contact.php">تماس با ما</a>
</div></div>
<div id="content_wrapper"><div id="content">'.$content.'</div></div>
<div id="footer">هر گونه کپی برداری از محتوا، تولیدات، شکل و سایر اجزای سایت صرفا با موافقت مکتوب مجاز می باشد <br>
1391</div></body></html>';
	if ($pg['save']) {		
		file_write('public-notify/'.$pg['save'],$page,true);
	} else echo $page;
}
function notice(){
	return msg_box('notice','به دلیل عدم شفافیت قیمت ها در بازار تهران. قیمت ارز در بازار تهران برای کاربران ارسال نمی گردد. تا برگشت شرایط به حالت معمول به مدت زمان سرویس شما اضافه خواهد شد.')
		.msg_box('info','کاربرانی که به هر دلیلی ترجیح به عدم دریافت پیامک دارند. می توانند با ارسال شماره کارت بانکی خود تقاضای استرداد وجه نمایند. توجه داشته باشید هزینه انتقال وجه به کارت را سایت تقبل خواهد نمود و از وجه باقی مانده شما به هیچ وجه هزینه ای کسر نخواهد شد.');
}