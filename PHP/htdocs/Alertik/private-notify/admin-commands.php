<?php
$admin_commands=[
	'phone_to_uid'=>[
		'title'=>'تبدیل تلفن به کد کاربری','submit'=>'تبدیل',
		'in'=>['phone_no'=>'شماره تلفن'],
		'out'=>['body'=>'کد کاربری <!--uid--><br><a href="/dashboard.php?i=<!--uid-->">ورود به دشبورد کاربر</a><br><a href="/log.php?i=<!--uid-->">ورود به صفحه لاگ کاربر</a>'],
		'sql'=>'SELECT uid FROM notify_users WHERE phone_no="<!--phone_no-->"',
	],	
	'pass_change'=>[
		'title'=>'تغییر پسورد','submit'=>'تغییر',
		'in'=>['uid'=>'کد کاربر','pass'=>'پسورد'],
		'out'=>['body'=>'<div class="alert alert-success">پسورد با موفقیت تغییر کرد</div>'],
		'sql'=>'UPDATE notify_users set pass = md5("<!--pass-->") where uid = <!--uid-->',
	],
	'daily_payments'=>[
		'title'=>'میزان پرداخت روزانه','submit'=>'نمایش',
		'out'=>['body'=>'<div style="width:200px;float:right"><!--date_--> <!--total_payment--> (<!--number_of_payment--> پرداخت)</div>','foot'=>'<div class="clear"></div>'],
		'sql'=>'SELECT date(addedon)as date_,count(*) as number_of_payment,round(sum(amount)/10000) as total_payment FROM notify_transfers WHERE valid = "Y" GROUP by date_ ORDER BY date_ DESC LIMIT 40',
	],
	'subscription_expires'=>[
		'title'=>'پایان دوره سرویس کاربران','submit'=>'نمایش',
		'out'=>['body'=>'<div style="width:150px;float:right"><!--expires_in--> روز <!--subscribers--> کاربر</div>','foot'=>'<div class="clear"></div>'],
		'sql'=>'SELECT TIMESTAMPDIFF(DAY,NOW(),upto) as expires_in,count(*) as subscribers FROM notify_packages GROUP BY expires_in ORDER BY expires_in LIMIT 20',
	],	
	'payed_customers'=>[
		'title'=>'تعداد کاربران فعال پرداختی','submit'=>'نمایش',
		'out'=>['body'=>'<!--total_count-->'],
		'sql'=>'SELECT count(*) as total_count FROM (SELECT DISTINCT phone_no FROM  notify_users LEFT OUTER JOIN notify_packages ON notify_packages.uid = notify_users.uid WHERE  (service_until > NOW() AND credit > 0) OR (upto > NOW())) as tt;',
	],	
	/*'total_payment'=>[
		'title'=>'کل مبلغ پرداختی','submit'=>'نمایش',
		'out'=>['body'=>'<!--number--> پرداخت به مبلغ <!--amount-->'],
		'sql'=>'SELECT count(*) as number,sum(amount) as amount FROM notify_transfers WHERE valid = "Y"',
	],*/	
	'sms_send_per_day'=>[
		'title'=>'پیامک های ارسالی روزانه','submit'=>'نمایش',
		'out'=>['body'=>'<div style="width:200px;float:right"><!--date_--> <!--number_of_sms--> (<!--try-->)</div>','foot'=>'<div class="clear"></div>'],
		'sql'=>'SELECT DATE(addedon) as date_,avg(send_retry)+1 as try,count(*) as number_of_sms FROM notify_sms GROUP BY date_ ORDER BY date_ DESC',
	],
	'sms_send'=>[
		'title'=>'20 پیامک ارسالی آخر','submit'=>'نمایش',
		'out'=>[
			'head'=>'<table><tr><th>کد پیامک<th>کد کاربر<th>نوع ارسال<th>وضعیت فعلی<th>سعی<th>محتوا</tr>',
			'body'=>'<tr><td><!--sms_id--><td><!--uid--><td><!--trigger_type--><td><!--current_status--><td><!--send_retry--><td><textarea cols="50"><!--content--></textarea></tr>',
			'foot'=>'</table>
			وضعیت: 40 رسیده, 50 تایم اوت, 60 برگشتی از مخابرات, 10-14 در حال ارسال, 0 آماده ارسال مجدد, 20 ارسال شده بدون نتیجه نهایی, 30 ارسال شده عدم اطمینان از ارسال<br>
			نوع ارسال: 30 پاسخ دوسویه, 100 بسته عمومی 3-7: تریگربیسد',
		],
		'sql'=>'SELECT * FROM notify_sms ORDER BY sms_id DESC LIMIT 20;',
	],		
	'sms_recieve'=>[
		'title'=>'20 پیامک دریافتی آخر','submit'=>'نمایش',
		'out'=>[
			'head'=>'<table><tr><th>کد پیامک<th>کد کاربر<th>تلفن<th>محتوا<th>نتیجه</tr>',
			'body'=>'<tr><td><!--sms_id--><td><!--uid--><td><!--phone_no--><td><!--message--><td><!--result--></tr>',
			'foot'=>'</table>
			نتیجه: 0 موفق, 5 مجانی موفق, 1و6 کمبود کردیت, 2و7 پیغام مورد دار, 3و8 بیش از یک مشکل ',
		],
		'sql'=>'SELECT * FROM notify_sms_incoming ORDER BY sms_id DESC LIMIT 20',
	],	
	'last_transfers'=>[
		'title'=>'20 پرداخت آخر','submit'=>'نمایش',
		'out'=>[
			'head'=>'<table><tr><th>کد پرداخت<th>کد کاربر<th>زمان<th>مبلغ<th>کد بسته<th>تعداد پیامک<th>روز<th>موفق<th>جزئیات</tr>',
			'body'=>'<tr><td><!--transfer_id--><td><!--uid--><td><!--addedon--><td><!--amount--><td><!--pack_id--><td><!--credit--><td><!--service_days--><td><!--valid--><td><textarea cols="80"><!--memo--></textarea></tr>',
			'foot'=>'</table>',
		],
		'sql'=>'SELECT * FROM notify_transfers ORDER BY transfer_id DESC LIMIT 20',
	],	
	'active_users_list'=>[
		'title'=>'لیست شماره تلفن کابران فعال','submit'=>'نمایش',
		'out'=>[
			'head'=>'<textarea rows="10">',
			'body'=>"0<!--phone_no-->\n",
			'foot'=>'</textarea>',
		],
		'sql'=>'SELECT DISTINCT phone_no FROM  notify_users LEFT OUTER JOIN notify_packages ON notify_packages.uid = notify_users.uid WHERE  (service_until > NOW() AND credit > 0) OR (upto > NOW());',
	],	
];
