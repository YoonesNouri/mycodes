<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-user.php';
include 'order-html.php';

if(is_ajax()) {
	if (user_cookie_authenticate()) redirect('/dashboard.php');
	if ($_POST['repass']) { //Register
		if (is_array($error=user_register())) {
			echo json_encode(array('error'=>$error));
		} else {
			redirect('/dashboard.php');
		}		
	} elseif ($_POST['phone_no']) { //Login
		if (is_array($error=user_signin())) {
			echo json_encode(array('error'=>$error));
		} else {
			redirect('/dashboard.php');
		}		
	} 
	exit;
} 

if ($_GET['a']=='logout') user_signout();
if (user_cookie_authenticate()) redirect('/dashboard.php');

if ($_POST['repass']) { //Register
	if (!is_array($error=user_register())) redirect('/dashboard.php');
}elseif ($_POST['phone_no']) { //Login
	if (!is_array($error=user_signin())) redirect('/dashboard.php');
} 

$page['wrapper_type']='index';
$page['title']='پیامک سکه، طلا  و ارز - اتحادیه طلا، جواهر و سکه تهران';// class="ajax"
$page['content']='<div class="alert alert-success">به علت محدودیت های موجود از سوی اداره مخابرات کشور، تنها خطوطی قادر به دریافت پیامک از این سامانه هستند که دریافت پیامک تبلیغاتی به طور کامل بر روی آن ها غیر فعال نشده باشد. لطفا پس از عضویت در سایت و قبل از سفارش، حتما از سرویس آزمایشی که در  صفحه کاربری در اختیارتان قرار می گیرد استفاده نمایید و در صورتی که پیامک درخواستی، به شما نمی رسد جهت بررسی مشکل از طریق صفحه تماس با ما مشکل خود را با ما درمیان بگذارید.</div>
<h2>انواع سرویس ها</h2>
<div class="desc_box"><h4>سرویس عمومی</h4>
در این سرویس، تعداد معینی از موارد نظیر سکه جدید، سکه قدیم، طلای 18 عیار و مثقال طلا بر اساس انتخاب مشترک، یا درساعات مشخص و یا بر مبنای نوسانات قیمت برای وی ارسال می گردد. برای این سرویس 6 حالت مختلف پیش بینی شده است .</div>

<div class="desc_box"><h4>سرویس 2 سویه (آنلاین)</h4>
در این سرویس با ارسال نام یک و یا چند نرخ در کنار هم به شماره 10007880 بلافاصله پیامکی حاوی آخرین قیمت نرخ های مورد نظر برای مشترک ارسال می گردد. در واقع می توان این سرویس را به نوعی پرسش و پاسخ تشبیه کرد که طی آن، سامانه پیامکی به سئوالات شما فورا پاسخ می دهد.</div>

<div class="desc_box"><h4>سرویس اختصاصی (مبتنی بر رویداد)</h4>
در این سرویس می توانید کلیه مشخصات اعم از موارد دریافت نرخ، تعداد پیامک دریافتی، حدفاصل پیامک ها، دریافت پیامک در صورت نوسان و میزان این نوسان، بالا رفتن قیمت ها از یک نرخ مشخص، ساعات دلخواه دریافت پیامک و سایر موارد را بر اساس نیاز و علایق خود تنظیم نمائید.</div>
<div class="clear"></div>
<br />
<div class="right">	
	<h2>ورود به سایت</h2>
	<form id="login" class="ajax" action="/" method="post" data-place="#container" data-validator="login">
		<div class="alert_box"></div>
		<input type="number" name="phone_no" class="phone_no" placeholder="شماره تلفن (مثال 091212345678)" required="required" />
		<input type="password" name="pass" class="pass" placeholder="کلمه عبور" required="required" />
		<input type="submit" name="submit" class="submit btn-green" value="ورود به سایت" />
	</form><br />
	<div class="alert alert-success">برای خرید اشتراک و دریافت پیامک، ابتدا باید عضو سایت شوید.<br>شماره تلفن پشتیبانی سایت : 88307981 - 88834685</div>
</div>
<div class="left">
	<h2>هم اکنون ثبت نام کنید</h2>
	<form id="register" class="ajax" action="/" method="post" data-place="#container" data-validator="register">
		<div class="alert_box"></div>
		<input type="text" name="lname" class="lname" placeholder="نام خانوادگی" required="required" />
		<input type="text" name="fname" class="fname" placeholder="نام" required="required" />
		<input type="text" name="email" class="email" placeholder="آدرس ایمیل" required="required" />
		<input type="number" name="phone_no" class="phone_no" placeholder="شماره تلفن (مثال 091212345678)" required="required" />
		<input type="password" name="pass" class="pass" placeholder="کلمه عبور" required="required" />
		<input type="password" name="repass" class="repass" placeholder="تکرار کلمه عبور" required="required" />	
		<input type="checkbox" name="agreement" class="agreement" id="iaccept" required="required" /><label for="iaccept"><a href="/agreement.html">شرایط عضویت و ثبت نام </a> را قبول دارم.</label><br />
		<input type="submit" name="submit" class="submit btn-green" value="عضویت در سایت" />
	</form>
</div>
<div class="clear"></div>
<h2>هزینه سرویس ها</h2>
'.package_table();
include '../private-notify/notify-page.php';
function line_status_form(){
	return '<form action="/line-status.php" class="ajaxpost" data-response="#line_check" id="line_check">
		<input name="phone_to_check" required="required" value="" placeholder="شماره تلفن خط برای بررسی"  size="30" />
		<input type="submit" name="submit" value="بررسی کن" class="submit btn-green" /></form>';
}
/* 
<h2>بررسی دلیل عدم دریافت پیامک</h2>
	<p>به دلیل مشکلات موجود در سرویس ها ارائه شده از طرف مخابرات در مواردی، دریافت و یا ارسال پیامک با مشکل مواجه می شوند. برای بررسی وضعیت آخرین پیامک ارسالی و دریافتی با ورود شماره تلفن همراه خود در بخش زیر. از دلایل احتمالی مشکل مطلع شوید.</p>
	'.line_status_form().'

	<h2 id="freesms">پیامک رایگان</h2>
	<p>برای آشنایی شما عزیزان، این امکان فراهم شده است تا پیش از استفاده دائم از این سامانه آن را مورد امتحان قرار دهید. به همین منظور برای هر شماره تلفن همراه، دو پیامک اول رایگان می باشد.به دلیل مشکلات اپراتورهای خطوط پیامک، ارسال پاسخ پیامک ممکن است از روی شماره دیگری صورت گیرد.</p>
	<p class="info">10007880<br />کلمات کلیدی مورد نظر خود را به شماره بالا پیامک کنید.</p>
*/	