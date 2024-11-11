<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-user.php';
include '../private-notify/lib-credit.php';
include '../private-notify/gateway-parsian.php';
include '../lib/f.leech.php';
include 'order-html.php';

if (!user_cookie_authenticate()) redirect('/');
if (validated_user()) redirect('/validation.php');
if ($master_id[$user['uid']]&&$_GET['i']!=null) $user['uid']=$_GET['i'];

if ($_POST['type']) prepare_gateway($user['uid'],$_POST['type'],$_POST['gateway']);
if ($_GET['au']) {
	$page['title']='تایید خرید سرویس';
	$page['content']='<h2>تایید پرداخت وجه</h2>'
		.finalize_payment($user['uid'],$_GET['au'],$_GET['rs'])
		.'<div class="clear"></div>';
} else {
	$page['title']='خرید سرویس';
	$page['content']=
		'<h2>خرید اشتراک پیامک</h2>
		<div class="alert alert-success">لطفا از جدول زیر سرویس مورد نظر خود را انتخاب نموده و برای خرید روی قیمت آن کلیک نمایید.</div>'.
		package_table(true).
		order_form().
		'<div class="clear"></div>';
}
include "../private-notify/notify-page.php";
function finalize_payment($uid,$transaction_id,$status){
	db_query('begin');
	$rs=db_query('SELECT * FROM notify_transfers WHERE uid = '.$uid);
	while ($row=db_array($rs)){
		$memo=json_decode($row['memo'],true);
		if ($memo['start']['transaction_id']==$transaction_id) {
			if($row['valid']=='Y') return '<div class="alert alert-error">این پرداخت قبلا تایید شده است. برای بازگشت به <a href="/dashboard.php">صفحه اصلی</a> کلیک نمایید.</div>';
			$response=finalize_transfer($transaction_id);
			$memo['finish']=$response;
			db_query('UPDATE notify_transfers SET memo="'.mysql_real_escape_string(json_encode($memo,JSON_UNESCAPED_UNICODE)).'" WHERE transfer_id = '.$row['transfer_id']);
			if (!$response['success']) {
				return '<div class="alert alert-error">'.$response['msg'].'</div>';
			} else {
				db_query('UPDATE notify_transfers SET valid="Y" WHERE transfer_id = '.$row['transfer_id']);
				/*		Activate Account		*/
				transfer_resource_commit($row);
				return '<div class="alert alert-success">سرویس خریداری شده با موفقیت فعال شد. برای بازگشت به <a href="/dashboard.php">صفحه اصلی</a> کلیک نمایید.</div>';
			}
			break;
		}
	}
	return '<div class="alert alert-error">کد انتقال وجه یافت نشد. در صورتی که اطمینان دارید وجه از حساب شما کسر شده است با ما تماس حاصل نمایید.</div>';
}
function prepare_gateway($uid,$type,$gateway){
	global $price_list;
	if (!is_array($package=$price_list[$type])) {
		echo json_encode(array('error'=>'<div class="alert alert-error">مشکلی در سرویس انتخابی وجود دارد.</div>'));
		exit;
	}
	db_query('INSERT INTO notify_transfers(uid,amount,pack_id,credit,service_days,addedon,memo,valid) 
			VALUES ('.$uid.','.$package['price'].','.$package['package_id'].','.$package['sms'].','
			.$package['days'].',NOW(),"'.mysql_real_escape_string(json_encode(array(),JSON_UNESCAPED_UNICODE)).'","N")');
	$transfer_id=mysql_insert_id();
	$response=start_transfer(array('transfer_id'=>$transfer_id,'amount'=>$package['price']));
	db_query('UPDATE notify_transfers SET memo="'.mysql_real_escape_string(json_encode(array('start'=>$response),JSON_UNESCAPED_UNICODE)).'" WHERE transfer_id = '.$transfer_id);
	
	if (!$response['success']) {
		if (!$response['msg']) $response['msg']='مشکلی در عملکرد درگاه بانک وجود دارد لطفا مجددا سعی نمایید.';
		echo json_encode(array('error'=>'<div class="alert alert-error">'.$response['msg'].'</div>'));
	} else {
		echo json_encode(array('redirect'=>$response['redirect']));
	}
	exit;
}
function order_form(){
	return '
<div id="payment_finalize" class="hide">
	<form action="/payment.php">
		<div class="alert_box"></div>
		<table class="common_tbl">
			<tr>
				<th colspan="7">برای نهایی کردن خرید روی دکمه خرید کلیک کنید.</th>
			</tr>
			<tr>
				<td>کد سرویس</td>
				<td>نوع سرویس</td>
				<td>تعداد پیامک</td>
				<td>تعداد روز</td>
				<td>قیمت</td>
				<td>درگاه پرداخت</td>
				<td>نهایی کردن خرید</td>
			</tr>
			<tr class="even">
				<td><input name="type" id="type" value="" size="4"></td>
				<td><input name="title" id="title" value="" size="60" readonly></td>
				<td><input name="sms" id="sms" value="" size="7" readonly></td>
				<td><input name="days" id="days" class="number" value="" size="7" readonly></td>
				<td><input name="price" id="price" class="number" value="" size="7" readonly></td>
				<td>
					<select name="gateway" id="gateway">
						<option value="1">بانک پارسیان</option>
					</select>
				</td>
				<td><input type="submit" name="submit" class="submit btn-green" value="خرید سرویس"></td>
			</tr>
		</table>
	</form>
	<a href="#" id="choose_another">انتخاب یک سرویس دیگر</a>
</div>';
}
function manual_transfer_form(){
	return '<form action="/contact.php" class="ajaxpost" data-response=".right form">'.service_select().'
		<select name="account_type" required="required">
			<option value="">پرداخت به حساب...</option>
			<option value="card">کارت ملت</option>
			<option value="account">حساب ملت</option>
		</select>
		<input name="amount" required="required" class="number" value="" placeholder="مبلغ پرداخت"  size="10" />
		<input name="transfer_code" required="required" value="" placeholder="شماره قبض پرداخت"  size="20" />
		<input name="memo" value="" placeholder="4 رقم آخر شماره کارت و یا نام واریز کننده"  size="40" />
		<input type="submit" name="submit" value="ارسال" /></form>';/*	<ol>
		<li>خرید اشتراک با ارزش کمتر از 100,000 ریال فقط از طریق پرداخت اینترنتی میسر می باشد. که فعلا انجام پرداخت اینترنتی مقدور نمی باشد.</li>
		<li>لطفا مشخصات دقیق پرداخت خود را در فرم زیر وارد نمایید تا پرداخت شما تایید شود.</li>
		<li>عموما پرداخت شما در طول یک روز کاری تایید خواهد شد.</li>
		<li>لطفا، فقط در صورتی که پرداخت انجام داده اید فرم زیر را تکمیل نمایید.</li>
	</ol><br />'.manual_transfer_form().'*/
}
function line_status_form(){
	return '<form action="/line-status.php" class="ajaxpost" data-response="#line_check" id="line_check"><input type="submit" name="submit" value="بررسی کن" class="submit btn-green" /></form>';
}
function check_line_status($uid){
	$rs=db_query('SELECT current_status FROM notify_sms WHERE uid = '.$uid.' AND track_id > 0 ORDER BY sms_id DESC LIMIT 5;');
	while($row=db_array($rs)){
		if ($row['current_status']!=1) $ok++;
		if ($row['current_status']==1) $block++;
	}
	if ($ok&&!$block) return msg_box('success','بر اساس اطلاعات موجود خط شما نباید در دریافت پیامک از سایت مشکلی داشته باشد.');
	if (!$ok&&$block) return msg_box('error','بدلیل درخواست شما مبنی بر غیر فعال سازی دریافت پیامک های تبلیغاتی، امکان ارسال پیامک از طریق خدمات مخابرات به شما وجود ندارد. و امکان سرویس دهی به شما را نداریم. می توانید برای  اطمینان بیشتر برای کنترل دستی خط شما با ما تماس حاصل نمایید.');
	if ($ok&&$block) return msg_box('notice','اطلاعات قابل اطمینانی در مورد خط شما نداریم. اما احتمالا خط شما در دریافت پیامک مشکلی نخواهد داشت.');
	if (!$ok&&!$block) return msg_box('error','شما هنوز از پیامک های مجانی خود استفاده نکرده اید و یا وضعیت پیامک های ارسالی هنوز مشخص نشده است. برای اطمینان از قابل ارسال بودن پیامک به خط شما لطفا به صفحه اصلی رفته و یک پیامک دو سویه تست نموده برای روشن شدن وضعیت خط پس از چند دقیقه مجددا به این صفحه وارد شوید.');
}

/*<div class="clear"></div>
<h2>پرداخت اینترنتی</h2>
<div class="right">
	<h4>نحوه پرداخت اینترنتی</h4>
	<ol>
		<li>برای پرداخت از طریق این روش نیاز به رمز دوم کارت خود را دارید.</li>
		<li>برای پرداخت به سامانه بانک پارسیان منتقل می شوید.</li>
		<li>در صورت پرداخت موفق سرویس انتخابی بلافاصله فعال خواهد شد.</li>
	</ol>
</div>
<div class="left">
	<h4>گزینه های پرداخت اینترنتی</h4>
	متاسفانه هنوز این گزینه فعال نشده است.
</div>
<!--<li>لطفا حتی الامکان از روش پرداخت آنلاین استفاده نمایید.</li>-->
*/