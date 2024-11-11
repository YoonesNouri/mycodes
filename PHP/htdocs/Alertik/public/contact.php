<?php
include "../private/rc.php";

if (strlen($_POST['message'])>10) {
	echo '<br /><strong>با تشکر از شما<br />پیغام شما دریافت شد</strong><br /><br />';
	$source_id=1;
	$url = 'http://panel.bemoghe.com/request.php';

	$_POST['source_id']=1;
	$options = array(
	    'http' => array(
	        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        'method'  => 'POST',
	        'content' => http_build_query(array_merge($_SERVER,$_POST)),
	    ),
	);
	$context  = stream_context_create($options);
	file_get_contents($url, false, $context);
	exit;
}
echo theme([1=>'contact',0=>'head',10=>'foot'],[],$theme_folder);

/*$page['title']='Mazanex تماس با ما';
$page['content']='<form action="" class="ajaxpost">
	<br />
	<h2>برای ارتباط با ما لطفا از فرم زیر استفاده نمایید.</h2>
	در صورتی سوالی دارید ابتدا صفحه
	<a href="/about.html">درباره وب سایت</a>
	و
	<a href="/faq.html">سوالات متداول</a>
	را مطالعه نمایید و در صورت تداوم سوال خود از طریق فرم زیر
	سوال خود را برای ما ارسال نمایید.
	<br />
	در صورتی که تصمیم به دادن آگهی دارید لطفا ابتدا صفحه
	<a href="/ads.html">آگهی</a> را مطالعه نمایید.
	<br />
	در صورتی که پیشنهاد و یا انتقادی دارید و یا ... لطفا از فرم زیر استفاده نمایید.
	<br /><br />
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
*/