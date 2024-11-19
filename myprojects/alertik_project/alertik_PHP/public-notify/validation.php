<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-user.php';

if (!user_cookie_authenticate()) redirect('/');
if (!$validate_code=validated_user()) redirect('/dashboard.php');
if (isset($_POST['validate'])){
	if ($validate_code!=$_POST['validate']){
		?><div class="alert alert-error">کد فعال سازی صحیح نمی باشد.</div><?php
	} else {
		db_query('UPDATE notify_users SET phone_approved = 0 WHERE uid = '.$user['uid']);
		echo '<!--refresh-->';
	}
	exit;
}

if ($master_id[$user['uid']]&&$_GET['i']!=null) $user['uid']=$_GET['i']; // Permission to edit or view triggers belong to any user

$page['title']='فعال سازی حساب کاربری';
$page['content']='
<p class="alert alert-success">برای اطمینان از عدم وجود مشکل در دریافت پیامک از طریق مخابرات. ابتدا باید خط تلفن همراه شما فعال سازی شود. پس از فعال سازی دسترسی شما به صفحه اصلی سایت باز می شود. در صورتی که سوالی در مورد روند فعال سازی خط دارید می توانید با پشتیبانی سایت به شماره تلفن 88834685 و 88307981 تماس حاصل نمایید.</p>
<h2>فعال سازی حساب کاربری</h2>
'.activation_desc().'
<div class="clear"></div>
';//.benchmark();

include '../private-notify/notify-page.php';

function account_details($uid){
	$rs=db_query('SELECT credit,name,TIMESTAMPDIFF(DAY,NOW(),service_until) as days,line_status FROM notify_users WHERE uid ='.$uid);	
	if ($row=db_array($rs)) {
		if (!$row['days'] || $row['days']<0) $row['days']=0;
		$out=$row;
	}
	$rs=db_query('SELECT TIMESTAMPDIFF(DAY,NOW(),upto) as service_days FROM notify_packages WHERE uid ='.$uid);	
	while ($row=db_array($rs)) {
		$out['packages']=$row['service_days'];
	}
	return $out;
}
function activation_desc(){
	return '
<div class="right">
	<h4>مراحل فعال سازی خط شما</h4>
	<p><b>مرحله اول</b> <span style="color:red">ابتدا یک پیامک از روی خطی که در ثبت نام بر روی وب سایت استفاده نموده اید به شماره 10007880 ارسال نمایید.</span></p>
	<p><b>مرحله دوم</b> پس از چند دقیقه (معمولا کمتر از دو دقیقه) یک پیامک حاوی کد فعال سازی به تلفن همراه شما خواهد رسید.</p>
	<p><b>مرحله سوم</b> برای اتمام مراحل فعال سازی کد دریافتی خود را در باکس کد فعال سازی مقابل وارد نموده و دکمه فعال سازی را فشار دهید.</p>

	<h4>توضیحات</h4>
	<p>1. برای بررسی وضعیت پیامک ارسالی دکمه بررسی وضعیت مقابل را فشار دهید.</p>
	<p>2. در صورتی که پس از چند دقیقه هنوز پیامکی دریافت نکرده اید. ممکن است به دلیل مشکل مخابرات پیامک شما به وب سایت و یا پیامک وب سایت به شما نرسیده باشد. برای اطمینان می توانید مراحل فعال سازی را مجددا انجام دهید.</p>
	<p>3. در صورت عدم دریافت پاسخ از وب سایت احتمالا دریافت پیامک های تبلیغاتی برای خط شما غیر فعال شده است که در این صورت امکان ارسال پیامک از طرف ما به شما وجود ندارد.</p>
</div>
<div class="left">
	<h4>فعال سازی حساب</h4>
	<p>لطفا کد (عدد) دریافتی از طریق پیامک را در باکس زیر وارد نموده دکمه فعال سازی را فشار دهید.</p>
	<div id="validation"></div>
	<form action="/validation.php" class="ajaxpost" data-response="#validation">
		<input name="validate" size="12" style="padding:10px;" placeholder="کد فعال سازی" />
		<input type="submit" name="submit" value="فعال سازی" class="submit btn-green" />
	</form>
	<br /><br />
	<h4>بررسی وضعیت پیامک</h4>
	<p>به دلیل مشکلات موجود در سرویس ها ارائه شده از طرف مخابرات در مواردی، دریافت و یا ارسال پیامک با مشکل مواجه می شوند. برای بررسی وضعیت آخرین پیامک ارسالی و دریافتی باکلیک بر روی دکمه بررسی. از دلایل احتمالی مشکل مطلع شوید.</p>
	<form action="/line-status.php" class="ajaxpost" data-response="#line_check" id="line_check"><input type="submit" name="submit" value="بررسی وضعیت" class="submit btn-green" /></form>
</div>
<div class="clear"></div>';
}