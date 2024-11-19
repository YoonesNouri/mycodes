<?php
if (strlen($_POST['message'])>10) {
	$dt="
<b>نام:</b> ".$_POST["name"]."
<b>موضوع:</b> ".$_POST["subject"]."
<b>متن پیغام:</b> ".$_POST["message"]."
<b>آدرس ایمیل:</b> ".$_POST["email"]."
<b>رجوع از:</b> ".$_SERVER['HTTP_REFERER']."
<b>آدرس ای پی:</b> ".$_SERVER['REMOTE_ADDR']."

	";
	echo '<br /><strong>با تشکر از شما<br />پیغام شما دریافت شد</strong><br /><br />';
	$source_id=6;
	include '../../sap/recorder.php';
	exit;
}
$page['title']='تماس با اتحادیه';
$page['content']='<form action="" class="ajaxpost">
	<br />
	<h2>برای ارتباط با ما لطفا از فرم زیر استفاده نمایید.</h2>	
	نام و نام خانوادگی
	<br />
	<input name="name" value=""  size="50" />
	<br /><br />
	پست الکترونیکی
	<br />
	<input name="email" value=""  size="50" />
	<br /><br />
	موضوع
	<br />
	<input name="subject" value="" size="50" />
	<br /><br />
	متن پیغام
	<br />
	<textarea name="message" rows="5" cols="100"></textarea>
	<br /><br />
	<input type="submit" name="submit" value="ارسال" />
</form>';
include "../private-tg/page.php";
/*	در صورتی سوالی دارید ابتدا صفحه
	<a href="/about.html">درباره وب سایت</a> 
	و 
	<a href="/faq.html">سوالات متداول</a> 
	را مطلعه نمایید و در صورت تداوم سوال خود از طریق فرم زیر
	سوال خود را برای ما ارسال نمایید.
	<br />
	در صورتی که تصمیم به دادن آگهی دارید لطفا ابتدا صفحه 
	<a href="/ads.html">آگهی</a> را مطالعه نمایید.
	<br />
	در صورتی که پیشنهاد و یا انتقادی دارید و یا ... لطفا از فرم زیر استفاده نمایید.
	<br /><br />*/