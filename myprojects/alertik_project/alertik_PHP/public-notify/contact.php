<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-credit.php';
if (isset($_POST['service'])){
	$_POST['details']=service_spec($_POST['service']);
	$_POST['addedon']=date('d/m/Y, g:i a');
	$dt='<b>نام:</b>'.print_r($_POST,TRUE).print_r($_COOKIE,TRUE)."
	";
	$filename="../private/messages.txt";
	$fp=fopen ($filename, "a");
	fwrite($fp,$dt);
	fclose($fp);
	echo msg_box('success','با تشکر از شما، پیغام شما مبنی بر واریز نقدی دریافت شد.');
	exit;
}
if (strlen($_POST['message'])>10) {
	$dt="
<b>نام:</b> ".$_POST["name"]."
<b>موضوع:</b> ".$_POST["subject"]."
<b>متن پیغام:</b> ".$_POST["message"]."
<b>آدرس ایمیل:</b> ".$_POST["email"]."
<b>رجوع از:</b> ".$_SERVER['HTTP_REFERER']."
<b>کوکی:</b> ".print_r($_COOKIE,true)."
<b>آدرس ای پی:</b> ".$_SERVER['REMOTE_ADDR']."

	";
	$filename="../private/messages.txt";
	$fp=fopen ($filename, "a");
	fwrite($fp,$dt);
	fclose($fp);
	echo msg_box('success','با تشکر از شما، پیغام شما دریافت شد.');
	$source_id=9;
	include '../../sap/recorder.php';	
	exit;
}
$page['title']='Bemoghe تماس با ما';
/*	در صورتی سوالی دارید ابتدا صفحه
	<a href="/faq.html">سوالات متداول</a> 
	را مطلعه نمایید و در صورت تداوم سوال خود از طریق فرم زیر
	سوال خود را برای ما ارسال نمایید.
	<br />*/
$page['content']='<form action="/contact.php" class="ajaxpost" data-response="#content">
	<br />
	<h2>برای ارتباط با ما لطفا از فرم زیر استفاده نمایید.</h2>	

	لطفا قبل از تماس با ما صفحه <a href="/faq.html">سوالات متداول</a> را مطالعه نمایید.	<br /><br />
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
include "../private-notify/notify-page.php";