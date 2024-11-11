<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-user.php';
include '../private-notify/lib-credit.php';
include '../private-notify/lib-sms.php';
include '../private-notify/admin-commands.php';
include '../lib/commands-panel.php';

if (!user_cookie_authenticate()||!$master_id[$user['uid']]) redirect('/dashboard.php');

if ($_POST['phone_no']){
	preg_match("/([1-9][0-9]+)/",  substr($_POST['phone_no'], -10),$phone_no);
	$_POST['phone_no']=$phone_no[0];
}

$page['title']='Bemoghe شارژ';
if ($_GET['command_panel']) $page['content']=execute_admin_panel($admin_commands,$_GET['command_panel'],$_POST);

$page['content'].=admin_panel_display($admin_commands);

/*
if ($_POST['uids']) send_msg();
if ($_POST['uid']){
	db_query('begin');
	$phone_no=credit_in($_POST);
	sms_insert(array('uid'=>str_replace(',','',$_POST['uid']),'trigger_type'=>50,'content'=>'با تشکر از پرداخت شما، حساب شما ارتقا پیدا کرد.','sms_cost_type'=>50));
	echo 'اعتبار منتقل شد. ('.time().') '.$_POST['uid'];
	db_query('commit');
	exit;
}
$page['title']='Bemoghe شارژ';
$page['content']='
	<div class="alert alert-success"></div>
	<h2>انتقال شارژ</h2>
	<form action="/credit.php" class="ajaxpost" data-response=".alert:first">
		<input name="uid" class="number" value="" placeholder="کد کاربری"  size="15" />
		<input name="amount" class="number" value="" placeholder="مبلغ پرداخت"  size="15" />
		<input name="pack_id" class="number" value="" placeholder="کد بسته"  size="15" />
		<input name="credit" class="number" value="" placeholder="تعداد پیامک"  size="15" />
		<input name="days" class="number" value="" placeholder="تعداد روز سرویس"  size="15" />
		<input name="transfer_type" type="hidden" value="1" />
		<input name="memo" value="" placeholder="توضیحات اضافه پرداخت از قبیل شعبه پرداخت"  size="40" />
		<input type="submit" name="submit" value="انتقال" />
	</form>
	<h2>ارسال پیغام</h2>
	<form action="/credit.php" class="ajaxpost" data-response=".alert:first">
		<input name="uids" value="" placeholder="کد کاربری گیرنده ها"  size="50" />
		<input name="msg" value="" placeholder="توضیحات اضافه پرداخت از قبیل شعبه پرداخت"  size="50" />
		<input type="submit" name="submit" value="انتقال" />
	</form>
';
function send_msg(){
	$ids=explode(',',$_POST['uids']);
	if (is_array($ids)) foreach ($ids as $id){
		sms_insert(array('uid'=>$id,'trigger_type'=>50,'content'=>$_POST['msg'],'sms_cost_type'=>50));
	}
	echo 'پیغام ارسال شد.';
	db_query('commit');
	exit;
}*/
include "../private-notify/notify-page.php";