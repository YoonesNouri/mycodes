<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-user.php';
include '../private-notify/lib-credit.php';
include 'order-html.php';
//benchmark();
if (!user_cookie_authenticate()) redirect('/');
if (validated_user()) redirect('/validation.php');
if ($master_id[$user['uid']]&&$_GET['i']!=null) $user['uid']=$_GET['i'];

$account_details=account_details($user['uid']);

trigger_frontend_logic($user);

$page['title']='دشبورد حساب کاربری';
$triggers_list=triggers_list($user['uid']);
$page['content']='<div id="msg"></div>'.user_basic_info($account_details).'
<h1>سرویس عمومی <a href="/faq.html" data-target="#general_more" class="display_more">راهنما</a></h1>
'.general_header($account_details).'

<h1>سرویس دوسویه (آنلاین) <a href="/faq.html" data-target="#twoway_more" class="display_more">راهنما</a></h1>'
.two_way_header($account_details).'<div class="clear"></div>'
.two_way_help()
.'<h1>سرویس اختصاصی (مبتنی بر رویداد) <a href="/faq.html" data-target="#trigger_more" class="display_more">راهنما</a></h1>'
.trigger_header($account_details)
.'<h2>لیست رویداد ها</h2>
<form><div class="alert_box"></div><div id="triggers_list">'.$triggers_list.'</div></form>
<a href="#trigger_add_wrapper" class="command" data-reveal="trigger_add_wrapper">اضافه کردن رویداد جدید</a>

<div id="trigger_add_wrapper"'.(strstr($triggers_list,'notice')?'':' class="hide"').'>
<h2>اضافه کردن رویداد جدید</h2>
<form method="post" class="ajax" id="trigger_edit" data-validator="trigger_save" action=""><input type="hidden" name="trigger_id" id="trigger_id" />
<div class="alert_box"></div>
<div class="trigger_target right">
	<h4>1. انتخاب مورد درخواستی</h4>
	لطفا مورد درخواستی خود را انتخاب نمایید.
	'.trigger_currencies().'
</div>
<div class="trigger_box_wrapper left">
	<h4>2. تنظیمات رویداد</h4>
	'.trigger_box().'
</div>
<div class="clear"></div><br />
<div class="right">
	<h4>3. اطلاعات اضافی پیامک</h4>
	'.sms_settings_box().'
</div>
<div class="left">
	<h4>4. پیش نمایش و ذخیره رویداد</h4>
	'.preview_and_save().'
</div><div class="clear"></div>
</form>
</div>
<h1>پشتیبانی سایت</h1>
<div class="right">
	<h2>بررسی وضعیت آخرین پیامک ها</h2>
	<p>به دلیل مشکلات موجود در سرویس ها ارائه شده از طرف مخابرات در مواردی، دریافت و یا ارسال پیامک با مشکل مواجه می شوند. برای بررسی وضعیت آخرین پیامک ارسالی و دریافتی باکلیک بر روی دکمه بررسی. از دلایل احتمالی مشکل مطلع شوید.</p>
	'.line_status_form().'	
	<br />
	<div class="alert alert-success">شماره تلفن پشتیبانی سایت : 88307981 - 88834685</div>
</div>
<div class="left">
	
	<img src="s/sms-guide.png">
</div>
<div class="clear"></div>
';//.benchmark();

include '../private-notify/notify-page.php';

function general_header($account){
	$msg=msg_box('notice','شما اشتراک فعال عمومی ندارید.<a href="/order.php" style="float:left;">برای خرید کلیک کنید</a>');
	if ($account['packages']) $msg=msg_box('success','از بسته عضویت شما '.$account['packages'].' روز باقی مانده است.');
	return $msg.'<div class="hide" id="general_more"><p>در این سرویس، تعداد معینی از موارد نظیر سکه جدید، سکه قدیم، طلای 18 عیار و مثقال طلا بر اساس انتخاب مشترک، یا درساعات مشخص و یا بر مبنای نوسانات قیمت برای وی ارسال می گردد. برای این سرویس 6 حالت مختلف پیش بینی شده است .</p>

<p>سرویس عمومی به 2 نوع تقسیم می شود.</p>

<p>شیوه نخست که طی آن موارد مشخص مثل سکه جدید، سکه قدیم، انس، طلای 18، مثقال و ........ در بازه های زمانی مشخص فرضا ظهر(حوالی ساعت 12) و در طول روز (بین ساعت 2 تا 4 ) و بعد از ظهر (بین ساعت 4 تا 6) برای شما ارسال می گردد.</p>

<p>شیوه دوم که طی آن موارد مذکور، پس از وقوع نوسان فرضا افزایش قیمت سکه یا کاهش قیمت طلا برای شما ارسال خواهد شد. لازم به توضیح است که در صورت عدم وقوع نوسان، یقینا 2 پیامک، یکی ظهر و دیگری در طول بعد از ظهر برای شما ارسال می گردد.</p>

<p>در این نوع اشتراک، بدون نیاز به هیچ نوع تنظیمی شروع به دریافت پیامک خواهید کرد. همچنین در این نوع اشتراک، فقط مدت اشتراک فرضا 1 ماه یا 3 ماه و ....... مهم می باشد و هزینه سرویس بر اساس تعداد پیامک دریافتی کاربر محاسبه نمی شود بلکه برای یک دوره مشخص تعیین و قطعی شده است.</p></div>'
;
}
function two_way_header($account){	
	$msg=msg_box('notice','شما پیامک قابل استفاده برای سرویس دو سویه ندارید.<a href="/order.php" style="float:left;">برای خرید کلیک کنید</a>');
	if ($account['credit']>0&&$account['days']>0) $msg=msg_box('success','از اشتراک شما '.$account['credit'].' عدد پیامک قابل تنظیم باقیمانده است که در '.$account['days'].' روز آینده می توانید استفاده نمایید.');
	return  $msg.'<div class="hide" id="twoway_more"><p>در این سرویس، برای هر یک از نرخ ها نظیر نرخ مثقال، سکه جدید و ... یک عدد تعیین شده که نقش مخفف را دارد و کاربر با ارسال آن عدد به ما، همان لحظه نرخ مورد نظرش را دریافت می کند فرضا با ارسال کلمه azadi که برای سکه جدید مقرر گردیده، بلافاصله پیامکی حاوی قیمت سکه جدید در آن لحظه دریافت خواهد نمود.</p>
<p>در سرویس دوسویه، شما در هر لحظه می توانید تنها با ارسال یک پیامک به اطلاعات مورد نیاز خود دسترسی داشته باشید. این فرآیند با محوریت واسطه هایی که کلید یا کلمات کلیدی خوانده می شوند عملی می شود. در این نوع سرویس، با ارسال “کلید” ها به اطلاعات آن کلید دسترسی خواهید داشت.</p>

<p>از جمله امکانات دیگر این نوع سرویس (دو سویه)، امکان ارسال چند ”کلید“ به طور هم زمان و دریافت نتایج آن در یک پیامک است. بدین منظور، باید اقدام به جداسازی کلیدها به وسیله نشان "نقطه" یا "ویرگول" نمایید. برای مثال برای دریافت نتایج هم زمان کلیدهای ”سکه جدید “ و ”مثقال طلا“ باید این گونه عمل نمایید:</p>

<p>سکه.طلا ( کلید اول.کلید دوم)</p>

<p>نکته 1: در صورت خطا در تایپ کلمه کلیدی یا “کلید”، سیستم به صورت خودکار نزدیک ترین کلمه کلیدی به متن ارسالی شما را یافته و اطلاعات به روز مرتبط به آن را ارسال می نماید.</p>

<p>نکته 2: به دلیل محدودیت تعداد کارکتر های هر پیامک، ناگزیر به ارسال تعداد مواردی می شویم که در یک پیامک گنجانده شوند. مجددا اعلام می گردد که این سرویس صرفا محتوایی برابر با یک پیامک در پاسخ به درخواست شما ارسال می نماید.</p></div>';
}
function trigger_header($account){	
	$msg=msg_box('notice','شما پیامک قابل استفاده برای سرویس اختصاصی ندارید.<a href="/order.php" style="float:left;">برای خرید کلیک کنید</a>');
	if ($account['credit']>0&&$account['days']>0) $msg=msg_box('success','از اشتراک شما '.$account['credit'].' عدد پیامک قابل تنظیم باقیمانده است که در '.$account['days'].' روز آینده می توانید استفاده نمایید.');
	return  $msg.'<div class="hide" id="trigger_more"><p>در این سرویس، کلیه مشخصات اعم از موارد دریافت نرخ، تعداد پیامک دریافتی، حدفاصل پیامک ها،دریافت پیامک در صورت نوسان 
و میزان این نوسان، بالا رفتن قیمت ها از یک نرخ مشخص، ساعات دلخواه دریافت پیامک و سایر موارد را بر اساس نیاز و علایق خود تنظیم نمائید.
</p>
<p>این نوع سرویس با هدف برآورده نمودن نیازهای افراد حرفه ای که در اموری همچون واردات کالا، سرمایه گذاری در بازار سکه، طلا و بازار آتی، فروش طلا و جواهرات و... فعالیت دارند طراحی شده و بر این دسته از امور تمرکز بیشتری دارد. در مقایسه با سرویس دو سویه (TWO-WAY)، این نوع سرویس پیامک را به صورت خودکار در زمان بروز رویداد تعریف شده در حساب کاربری، برایتان ارسال می نماید.</p>
<p>منظور از تغییر در این سرویس، تغییر نرخ نسبت به آخرین پیامک دریافتی کاربر می باشد. همچنین شایان ذکر است که در صورت عدم تغییر قیمت نسبت به آخرین پیامک ارسالی، از ارسال پیامک جدید صرف نظر می شود تا پیامک کمتری از سرویس شما کسر گردد.</p></div>';
}
function trigger_frontend_logic($user){
	if ($_POST['del']||$_POST['activate']||$_POST['action']){
		if ($_POST['del']) {
			echo json_encode(array('.alert_box:first'=>trigger_delete(),'#triggers_list'=>triggers_list($user['uid'])));	
		} elseif ($_POST['activate']) {
			echo json_encode(array('.alert_box:first'=>trigger_toggle(),'#triggers_list'=>triggers_list($user['uid'])));	
		} elseif ($_POST['action']=='trigger_add') {
			if (!is_array($msg=trigger_replace())) {
				if ($msg=='Y') echo json_encode(array('.alert_box:first'=>msg_box('success','رویداد با موفقیت ایجاد و فعال شد.'),'#triggers_list'=>triggers_list($user['uid']))); 
				else echo json_encode(array('.alert_box:first'=>msg_box('notice','رویداد با موفقیت اضافه شد اما به دلیل کمبود میزان اعتبار حساب فعال نشده است.'),'#triggers_list'=>triggers_list($user['uid']))); 
			} else echo json_encode(array('error'=>$msg));
		}	
		exit;
	}
}


function user_basic_info($account){
	return msg_box('info','با سلام '.str_replace('|',' ',$account['name'])
		.'<a style="float:left;font-weight:bold;margin-right:20px" href="/?a=logout">خروج از حساب</a>
		<a style="float:left;font-weight:bold;margin-right:20px" href="/order.php">خرید اشتراک</a>
		<a style="float:left;font-weight:bold" href="/log.php">گزارش حساب</a>'
		);
}
function user_sum_info(){
	global $user;
	$account=account_details($user['uid']);
	if ($account['credit']<1)$account['credit']=0;
	if ($account['days']<1)$account['days']=0;
	if (!$account['credit']||!$account['days']) {
		$out=msg_box('error','مدت اعتبار و یا تعداد پیامک قابل استفاده برای سرویس دوسویه و مبنی بر رویداد به پایان رسیده است. برای استفاده از این نوع سرویس <a href="/order.php">اشتراک خریداری</a> نمایید.');
	} else {
		$remaining='پیامک قابل تنظیم '.$account['credit'].' عدد مدت اعتبار '.$account['days'].' روز &nbsp; &nbsp; &nbsp; ';
	}
	if ($account['packages']) $services='از بسته عضویت شما '.$account['packages'].' روز باقی مانده است.'.' &nbsp; &nbsp; &nbsp; ';
	return msg_box('info','با سلام '.str_replace('|',' ',$account['name']).' &nbsp; &nbsp; &nbsp; '.$remaining.$services
		.'<a href="/order.php">خرید اشتراک</a>'.' &nbsp; &nbsp; &nbsp; '
		.'<a href="/log.php">گزارش حساب</a>'
		//.' &nbsp; &nbsp; &nbsp; &nbsp; '.'<a href="/?a=logout">خروج از حساب</a>'
		).$out;
		//.(($row['line_status']==200)?msg_box('error','متاسفانه امکان ارسال پیامک از روی خطوط تبلیغاتی به شماره شما مقدور نمی باشد. برای فعال سازی دریافت پیامک با اپراتور همراه خود تماس حاصل نمایید. ارسال پیامک از روی خطوط متفرقه به کندی به شما مقدور می باشد.'):'')
}
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
function preview_and_save(){
	return 
		'<div id="preview">----- نمونه پیامک -----<br>
		<span class="tip" data-tip="نرخ اصلی انتخاب شده.">US$</span> 21,940<br>
		<span class="tip" data-tip="نرخ اضافه انتخاب شده.">Bours</span> 246,490 <span class="tip" data-tip="در صورتی که اطلاعت مربوط امروز نباشد، تاریخ بروز رسانی نمایش داده می شود.">9/6</span><br>
		<span class="tip" data-tip="به دلیل محدودیت حجم پیامک نام تمام نرخ ها به صورت مختصر قید می شوند.">Azadi</span> 8,380,000 <span class="tip" data-tip="در صورتی که بیش از 5 دقیقه از روی به روزرسانی نرخ گذشته باشد. زمان به روز رسانی نماش داده می شود.">15:15</span><br>
		<span class="tip" data-tip="زمان ارسال پیامک">15:20٭</span></div><br>
		<input type="hidden" name="action" value="trigger_add" /><input type="submit" class="btn-green" value="ذخیره رویداد" />';
}
function sms_settings_box(){
	return 'شما می توانید برای دریافت موارد دیگر در انتهای پیامک، گزینه های زیر را انتخاب نمایید.<br />'.target_rate('currency_addition[]',1).'<br />'
	.target_rate('currency_addition[]',1).'<br />'
	.'ارسال پیامک فقط بین ساعت <br />'
	.hour_select('time_start',8).' الی '.hour_select('time_end',20)
	.'';
}
function trigger_box(){
	return 'لطفا شرایط مورد نظر خود را برای ارسال پیامک مشخص کنید.<input type="hidden" name="trigger_type" id="trigger_type" data-validator-data="نوع رویداد انتخاب نشده است." />'
	.'<a href="#" class="trigger_type tip" data-tip="در صورت رسیدن نرخ به  رقم مورد نظر خود پیامک دریافت نمایید." data-type="price">رسیدن به نرخ مشخص</a>'.trigger_box_price()
	.'<a href="#" class="trigger_type tip" data-tip="هر بار نرخ به میزان مورد نظر شما تغییر کرد پیامک دریافت کنید." data-type="change">تغییرات نرخ</a>'.trigger_box_change()
	.'<a href="#" class="trigger_type tip" data-tip="در ساعت خاصی طی روز پیامک دریافت کنید." data-type="time">زمان مشخص</a>'.trigger_box_time()
	.'<a href="#" class="trigger_type tip" data-tip="بعد از گذشت زمان مشخصی پیامک دریافت کنید." data-type="timegap">بازه های زمانی</a>'.trigger_box_timegap();
}
function trigger_box_price(){
	return '<div id="price_box" class="trigger_box">'.'ارسال پیامک در صورت رسیدن <span class="cur_prefix">نرخ</span> به <input name="price_target" id="price_target" type="text" size="9" class="number" />  <span class="cur_postfix">ریال</span>.'.'</div>';
}
function trigger_box_change(){
	return '<div id="change_box" class="trigger_box">'.'ارسال پیامک در صورت تغییر <span class="cur_prefix">نرخ</span> بیش از'
		.'<input name="change_amount" class="number" id="change_amount" type="text" size="5" />'
		.'<select name="change_amount_type" id="change_amount_type" ><option value="amount" class="cur_postfix">مقدار</option><option value="percentage">درصد</option></select>'
		.'، با فاصله زمانی حداقل <input name="change_delay" id="change_delay" type="text" size="1" value="20" /> دقیقه.'
		.'</div>';
}
function trigger_box_time(){
	return '<div id="time_box" class="trigger_box">'.'ارسال پیامک در ساعت '.hour_select('time_hour',0)
		.'<select name="time_min" id="time_min">
			<option value="0">00</option><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option></select>'
		.' دقیقه  '
		.'<select name="time_type"><option value="before">در صورت تغییر نرخ</option><option value="after">بعد از تغییر نرخ</option></select>'
		.'.'.'</div>';
}
function trigger_box_timegap(){
	return '<div id="timegap_box" class="trigger_box">'.'ارسال پیامک هر '
		.'<input name="timegap" id="timegap" type="text" size="5" class="number" />'
		.' دقیقه یک بار '
		.'<select name="timegap_type"><option value="before">در صورت تغییر نرخ</option><option value="after">بعد از تغییر نرخ</option></select>'
		.'.'.'</div>';
}
function hour_select($name,$default){
	if (!$default){
		return '<select name="'.$name.'" id="'.$name.'"><option value="0">تمام ساعت ها</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option></select>';
	} elseif ($default==8){
		return '<select name="'.$name.'" id="'.$name.'"><option value="7">07</option><option value="8" selected="selected">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option></select>';
	} elseif ($default==20){
		return '<select name="'.$name.'" id="'.$name.'"><option value="7">07</option><option value="8"">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20" selected="selected">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option></select>';
	}
}
function triggers_list($uid){
	global $trigger_types;
	//<th>اطلاعات تکمیلی</th>
	$rs=db_query('SELECT * FROM notify_triggers WHERE uid ='.$uid);
	while($row=db_array($rs)){
		$details=json_decode($row['details'],true);
		$out.='<tr '.((++$count%2)?'class="even"':'').'><td>'.$row['trigger_id'].'</td><td>'.rate_name_fa($row['source_type'],$row['rate_type'],$row['currency_id']).'</td><td>'.$trigger_types[$row['trigger_type']]
			.'</td><td>'.trigger_details($row,$details).'</td><td>بین '.$row['time_start'].' الی '.$row['time_end'].'</td><td>'
			.(($row['active']=='Y')?'فعال':'غیر فعال').'</td><td><a href="#" class="activate" data-action="'.(($row['active']=='Y')?'N':'Y').'" data-id="'.$row['trigger_id'].'">'.(($row['active']=='N')?'فعال':'غیر فعال').' سازی </a></td><td><a href="#" class="delete" data-id="'.$row['trigger_id'].'">حذف</a></td></tr>';
	} 	//<td><a href="#" class="edit" data-load=\''.json_encode(array_merge($details,$row)).'\'>ویرایش</a></td>
	if ($out) return '<table width="100%"><tr><th>کد رویداد</th><th>نوع نرخ</th><th>نوع رویداد</th><th>تنظیمات ارسال پیامک</th><th>ساعات ارسال</th><th>وضعیت فعلی</th><th colspan="2">تغییر رویداد</th></tr>'.$out.'</table>';
	else return msg_box('notice','هیچ رویدادی اضافه نشده است. برای اضافه کردن رویداد جدید، لطفا فرم زیر را پر نمایید.');
}
function target_rate($name,$size=1){
	global $sms_keywords_legend;
	foreach ($sms_keywords_legend as $key => $text){
		if (strstr($key,'cat')) $options.=(($options)?'</optgroup>':'').'<optgroup style="font-family:tahoma" label="'.$text.'">';
		else $options.='<option value="'.$key.'">'.$text.'</option>';
	}
	list($class)=explode('[',$name);
	return '<select name="'.$name.'" class="'.$class.'" size="'.$size.'"><option value=""></option>'.$options.'</optgroup></select>';
}
function trigger_currencies(){
	global $notify_rates;
	foreach ($notify_rates as $key => $text){
		$i=explode(':',$key);
		if (strstr($key,'cat')) $options.=(($options)?'<div class="clear"></div></div>':'').'<a href="#" class="currency_target" data-type="'.$key.'">'.$text.'</a><div id="'.$key.'_box" class="currency_target_box">';
		else $options.='<div class="select_org"><input name="trigger_target" data-postfix=" '.currency_postfix($i[0], $i[1], $i[2]).'" value="'.$key.'" id="tt'.$key.'" type="radio" /><label for="tt'.$key.'">'.$text.'</label></div>';
	}
	return $options.'<div class="clear"></div></div>';
}
/*
	Activate or inactivate trigger
*/
function trigger_toggle(){
	global $user;
	if ($trigger_id=(int)$_POST['activate']){
		if (!trigger_owner_check($trigger_id)) return msg_box('error','مشکل در حذف رویداد لطفا با بخش فنی تماس حاصل نمایید.');
	} else  return msg_box('error','رویداد مورد نظر شما یافت نشد.');	
	
	if ($_POST['action']=='Y') {
		$active='Y'; 
		if (!check_credit($user["uid"])) return msg_box('error','میزان اعتبار حساب شما برای فعال سازی رویداد کافی نیست.');
	} else $active='N'; 

	db_query('UPDATE notify_triggers SET active = "'.$active.'" WHERE trigger_id = '.$trigger_id.' AND uid = '.$user['uid']);

	if ($active=='Y') { // Create detection rows
		detection_create($user['uid'],$trigger_id,0,'skip-delay');
		return msg_box('success','رویداد مورد نظر شما با موفقیت فعال شد.');
	} else { // Remove detection rows
		db_query('DELETE FROM notify_detect_change WHERE trigger_id = '.$trigger_id);
		db_query('DELETE FROM notify_detect_time WHERE trigger_id = '.$trigger_id);
		return msg_box('success','رویداد مورد نظر شما با موفقیت غیر فعال شد.');
	}
}
/*
	Remove Trigger
*/
function trigger_delete(){
	global $user;
	if ($trigger_id=(int)$_POST['del']){
		if (!trigger_owner_check($trigger_id)) return msg_box('error','مشکل در حذف رویداد لطفا با بخش فنی تماس حاصل نمایید.');
	} else  return msg_box('error','رویداد مورد نظر شما یافت نشد.');
	
	db_query('DELETE FROM notify_triggers WHERE trigger_id = '.$trigger_id.' AND uid = '.$user['uid']);
	db_query('DELETE FROM notify_detect_change WHERE trigger_id = '.$trigger_id);
	db_query('DELETE FROM notify_detect_time WHERE trigger_id = '.$trigger_id);
	return msg_box('success','رویداد مورد نظر شما با موفقیت حذف شد.');
}
function trigger_details($i,$details){
	if ($i['trigger_type']==1){
		return 'در نرخ '.number_format($details['price_target']*zero_decision($i['source_type'], $i['rate_type'], $i['currency_id'])).' '.currency_postfix($i['source_type'], $i['rate_type'], $i['currency_id']);
	} elseif ($i['trigger_type']==2){
		return 'بعد از هر تغییر '.$details['change_amount'].' درصدی';
	} elseif ($i['trigger_type']==3){
		return 'بعد از هر تغییر به میزان '.number_format($details['change_amount']*zero_decision($i['source_type'], $i['rate_type'], $i['currency_id'])).' '.currency_postfix($i['source_type'], $i['rate_type'], $i['currency_id']);
	} elseif ($i['trigger_type']==4){
		if ($details['time_hour']==0) $hour=(($details['time_min']>0)?' دقیقه '.$details['time_min']:'').' تمام ساعات ';
		elseif ($details['time_hour']<10) $hour='ساعت 0'.$details['time_hour'].':'.$details['time_min']; else $hour='ساعت '.$details['time_hour'].':'.$details['time_min'];
		return 'در اولین تغییر بعد از '.$hour;
	} elseif ($i['trigger_type']==5){
		if ($details['time_hour']==0) $hour=(($details['time_min']>0)?' دقیقه '.$details['time_min']:'').' تمام ساعات ';
		elseif ($details['time_hour']<10) $hour='ساعت 0'.$details['time_hour'].':'.$details['time_min']; else $hour='ساعت '.$details['time_hour'].':'.$details['time_min'];
		return 'در '.$hour.' در صورت تغییر نرخ';
	} elseif ($i['trigger_type']==6){
		return 'هر '.$details['timegap'].' دقیقه یکبار بعد از تغییر';
	} elseif ($i['trigger_type']==7){
		return 'هر '.$details['timegap'].' دقیقه یکبار در صورت تغییر';
	} 
}
/*
	Insert new trigger or update previous triggers
*/
function trigger_replace(){
	global $user;
	if ($trigger_id=(int)$_POST['trigger_id']){
		if (!trigger_owner_check($trigger_id)) return array('#xyz','مشکلی در ذخیره گیره وجود دارد. لطفا با بخش فنی تماس حاصل نمایید.'); 
	}
	foreach ($_POST['currency_addition'] as $val){
		$details['currency_addition'][]=parse_currency($val,true);
		if (++$count>1) break;
	}
	list($source_type,$rate_type,$currency_id)=explode(':',$_POST['trigger_target']);
	if (!$currency_id)  return array('.trigger_target','هیچ نرخی انتخاب نشده است.');
	if ($_POST['trigger_type']=='price'){
		$trigger_type=1; 
		if (!$details['price_target']=(float)(str_replace(',','',$_POST['price_target']))) return array('#price_target','هدف نرخ مورد نظر مشخص نشده است.');
		$current_price=get_price($source_type, $rate_type, $currency_id);
		$tmp=abs(($current_price-$details['price_target'])*100/$details['price_target']);
		if ($tmp>20||$current_price==$details['price_target']) return array('#price_target','نرخ هدف ('.number_format($details['price_target']).') نمی تواند بیش از 20% با نرخ فعلی ('.number_format($current_price).') اختلاف  داشته و یا با  آن یکی باشد.');
		$details['price_target']=$details['price_target']/zero_decision($source_type, $rate_type, $currency_id);
	} elseif ($_POST['trigger_type']=='change'){
		if ($_POST['change_amount_type']=='percentage')	$trigger_type=2;
		elseif ($_POST['change_amount_type']=='amount') $trigger_type=3;
		$details['change_delay']=abs((int)$_POST['change_delay']);
		$details['change_amount']=abs((float)str_replace(',','',$_POST['change_amount']));
		if (!$details['change_amount']) return array('#change_amount','میزان تغییرات مورد نظر وارد نشده است.');
		if ($trigger_type==2 && ($details['change_amount']==0||$details['change_amount']>20)) return array('#change_amount','درصد تغییر مشخص شده برای رویداد باید بین 0% و 20% باشد.');
		$current_price=get_price($source_type, $rate_type, $currency_id);
		if ($trigger_type==3 && (100*$details['change_amount']/$current_price>20||100*$details['change_amount']/$current_price==5)) 
			return array('#price_target','میزان تغییر مشخص شده برای رویداد نباید 0% و بیش از 20% نرخ فعلی باشد.');
		$details['change_amount']=$details['change_amount']/(($trigger_type==3)?zero_decision($source_type, $rate_type, $currency_id):1);
		
		if ($details['change_delay']<10) return array('#change_delay','حداقل زمان بین هر پیامک باید بیش از 10 دقیقه باشد.');
	} elseif ($_POST['trigger_type']=='time'){
		if ($_POST['time_type']=='after') $trigger_type=4;
		elseif ($_POST['time_type']=='before') $trigger_type=5;
		$details['time_hour']=(int)$_POST['time_hour'];		
		$details['time_min']=(int)$_POST['time_min'];
	} elseif ($_POST['trigger_type']=='timegap'){
		if ($_POST['timegap_type']=='after') $trigger_type=6;
		elseif ($_POST['timegap_type']=='before') $trigger_type=7;
		$details['timegap']=(int)str_replace(',','',$_POST['timegap']);
		if ($details['timegap']<10) return array('#timegap','حداقل زمان بین هر پیامک باید بیش از 10 دقیقه باشد.');
	}
	
	if (!$trigger_type) return array('.trigger_box_wrapper','نوع گیره مشخص نشده است.');

	/*		If credit is enough activate it		*/
	if (check_credit($user["uid"])) $active='Y'; else $active='N';
	
	db_query('REPLACE notify_triggers ('.(($trigger_id)?'trigger_id,':'').' addedon, uid, source_type, rate_type, currency_id, trigger_type, last_value, details, time_start, time_end, active) VALUES
	('.(($trigger_id)?$trigger_id.',':'').' NOW(),'.$user['uid'].','.((int)$source_type).','.((int)$rate_type).','.((int)$currency_id).','
	.$trigger_type.','.get_price($source_type, $rate_type, $currency_id,'sell-raw').',"'
	.mysql_real_escape_string(json_encode($details)).'",'.((int)$_POST['time_start']).','.((int)$_POST['time_end']).',"'.$active.'");');		
	if ($active=='Y') detection_create($user['uid'],mysql_insert_id(),0,'skip-delay');
	return $active;
}

function trigger_owner_check($trigger_id){
	global $user;
	$rs=db_query('SELECT uid FROM notify_triggers WHERE trigger_id ='.$trigger_id);
	if ($row=db_array($rs)){
		if ($row['uid']==$user['uid']) return true;
	} 
	return false;
}
function line_status_form(){
	return '<form action="/line-status.php" class="ajaxpost" data-response="#line_check" id="line_check"><input type="submit" name="submit" value="بررسی کن" class="submit btn-green" /></form>';
}
function two_way_help(){
	return '
<div class="right">
	<h4>نحوه استفاده سرویس</h4>
	<p class="info">با ارسال نام نرخ مورد نظر از آخرین قیمت آن مطلع شوید.<br />10007880</p>
	<p>برای دریافت چند نرخ در کنار هم می توانید نام نرخ ها را به شکل زیر ارسال نمایید:</p>
	<p class="info">سکه.طلا  ( کلید اول.کلید دوم)</p>
	<br />
	<h4>جدول کلمات کلیدی سکه</h4>
	<table class="keywords" width="100%">	
		<tr><td class="legend">”Azadi“ یا   ”سکه بهار آزادی“</td><td>اعلام نرخ سکه بهار آزادی در بازار تهران</td></tr>
		<tr class="even"><td class="legend">”Tamam“ یا   ”سکه تمام“</td><td>اعلام نرخ سکه تمام در بازار تهران</td></tr>
		<tr><td class="legend">”Nim“ یا   ”سکه نیم“</td><td>اعلام نرخ سکه نیم در بازار تهران</td></tr>
		<tr class="even"><td class="legend">”Rob“ یا   ”سکه ربع“</td><td>اعلام نرخ سکه ربع در بازار تهران</td></tr>
		<tr><td class="legend">”Gram“ یا   ”سکه گرمی“</td><td>اعلام نرخ سکه گرمی در بازار تهران</td></tr>
		<tr><td class="legend">”Ati“ یا   ”آتی“</td><td>اعلام نرخ سکه در بورس آتی</td></tr>
	</table>
</div>
<div class="left">
	<h4>جدول کلمات کلیدی طلا</h4>
	<table class="keywords" width="100%">
		<tr class="even"><td class="legend">”Mesghal“ یا   ”مثقال“</td><td>اعلام نرخ مثقال طلای 17 عیار در بازار تهران</td></tr>
		<tr><td class="legend">”18k Gold“ یا   ”طلای 18“</td><td>اعلام نرخ طلای 18 عیار در بازار تهران</td></tr>
		<tr class="even"><td class="legend">”Ounce“ یا   ”اونس“</td><td>اعلام نرخ اونس در بازار جهانی</td></tr>
	</table><br />
	<h4>جدول کلمات کلیدی دیگر</h4>
	<table class="keywords" width="100%">	
		<tr><td class="legend">”Bourse“ یا   ”بورس“</td><td>اعلام شاخص بورس تهران</td></tr>
		<tr><td class="legend">”Spider“ یا   ”اسپایدر“</td><td>اعلام موجودی صندوق اسپایدر</td></tr>
		<tr><td class="legend">”Silver“ یا   ”نقره“</td><td>قیمت جهانی انس نقره</td></tr>
		<tr><td class="legend">”Platinium“ یا   ”پلاتینیوم“</td><td>قیمت جهانی پلاتینیوم</td></tr>
		<tr><td class="legend">”Palladium“ یا   ”پالادیوم“</td><td>قیمت جهانی پالادیوم</td></tr>
		<tr><td class="legend">”Oil“ یا   ”نفت“</td><td>قیمت نفت سبک</td></tr>
	</table>	
	<br />
	<div class="alert alert-info">به دلیل استفاده از استانداردهای متفاوت برای تایپ فارسی در بعضی از گوشی ها و احتمال عدم تشخیص کلمات کلیدی در این موارد. پیشنهاد می شود جهت اطمینان از کلمات کلیدی انگلیسی استفاده نمایید.</div>	
</div>
<div class="clear"></div>';
}