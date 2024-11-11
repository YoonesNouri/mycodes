<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
<meta charset="utf-8">
<title><?php echo $title ?></title>
<link href="/s/s.css?12" media="all" rel="stylesheet" type="text/css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="/s/s.js?2" type="text/javascript"></script>
<script type="text/javascript"> var _gaq = _gaq || []; _gaq.push([\'_setAccount\', \'UA-26522562-7\']); _gaq.push([\'_trackPageview\']); </script>
</head><body>
<div id="header_wrapper<?php if ($wrapper_type) echo '_'.$wrapper_type ?>">	
	<div id="header">
		<div id="nav_wrapper">
			<div id="nav">
				<ul>
					<li><a href="/'.(($user['uid'])?'dashboard.php':'').'">صفحه اصلی</a></li>
					<li><a href="/faq.html">سوالات متداول</a></li>
					<li><a href="/agreement.html">شرایط و مقررات</a></li>
					<li><a href="/contact.php">تماس با ما</a></li>
					<?php if($uid){ ?><li><a href="/?a=logout">خروج از حساب</a></li><?php } ?>
				</ul>
			</div>
		</div><?php if($wrapper_type=='index'){ ?><a id="header_try" href="#freesms"></a><a id="header_reg" href="#register"></a><?php } ?>
	</div>
</div>
<div id="content_wrapper"><div id="content"><?php
echo msg_box('notice','به دلیل عدم شفافیت قیمت ها در بازار تهران. قیمت ارز در بازار تهران برای کاربران ارسال نمی گردد. تا برگشت شرایط به حالت معمول به مدت زمان سرویس شما اضافه خواهد شد.').msg_box('info','کاربرانی که به هر دلیلی ترجیح به عدم دریافت پیامک دارند. می توانند با ارسال شماره کارت بانکی خود تقاضای استرداد وجه نمایند. توجه داشته باشید هزینه انتقال وجه به کارت را سایت تقبل خواهد نمود و از وجه باقی مانده شما به هیچ وجه هزینه ای کسر نخواهد شد.');