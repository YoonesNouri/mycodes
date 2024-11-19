<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-user.php';
include '../private-notify/lib-credit.php';

preg_match("/([1-9][0-9]+)/", $_POST['phone_to_check'],$phone);
$phone_no=$phone[0];

if ($uid=user_cookie_authenticate()) {
	$credit=check_credit($uid);
	$phone_no=get_phone_no($uid);
} else {
	list($uid,$credit)=check_phone_and_credit($phone_no);
	if (!$credit) $credit=check_free_credit($phone_no);
}

echo '<br /><h4>بررسی وضعیت دریافت پیامک از این خط</h4>'.last_received_msg($phone_no)
	.'<h4>بررسی وضعیت آخرین پیامک ارسالی به این خط</h4>'.last_sent_message($uid,$phone_no).(($uid)?'<input type="hidden" name="phone_to_check" value="0'.$phone_no.'" /><input type="submit" name="submit" value="بررسی مجدد" class="submit btn-green" />':'');

function last_sent_message($uid,$phone_no){
	if ($uid) $query='SELECT current_status,send_retry,track_id,send_by,UNIX_TIMESTAMP(addedon) as timestamp FROM notify_sms WHERE uid = "'.$uid.'" ORDER BY addedon DESC LIMIT 1;';
	else $query='SELECT notify_sms.current_status,send_retry,send_by,notify_sms.track_id,UNIX_TIMESTAMP(notify_sms.addedon) as timestamp FROM notify_sms 
			INNER JOIN notify_sms_incoming ON (notify_sms.trigger_id = notify_sms_incoming.sms_id)
			WHERE notify_sms.uid = 0 AND trigger_type = 30 AND send_by = 1 AND phone_no = "'.$phone_no.'" ORDER BY notify_sms.addedon DESC LIMIT 1;';
	$rs=db_query($query);
	while ($row=db_array($rs)){	
		$count++;
		$time=second_to_readable_format(time()-$row['timestamp']);
		if ($row['track_id']&&$row['current_status']>59) {
			return msg_box('error','آخرین پیامک '.$time.' قبل ارسال شد. اما احتمالا بدلیل فعال بودن عدم دریافت پیامک تبلیغاتی، به دست شما نرسید. برای استفاده از خدمات وب سایت عدم دریافت پیغام تبلیغاتی نباید فعال باشد. ممکن است مشکل از اختلال در سیستم مخابراتی باشد. دوباره سعی نمایید.');
		}
		if ($row['current_status']>49){
			return msg_box('info','آخرین پیامک '.$time.' قبل با موفقیت به مخابرات منتقل شد. اما مخابرات اطلاعات کافی در مورد رسیدن ویا نرسیدن آن در اختیار ما قرار نداده است. و به دلیل گذشت زمان از روی ارسال آن مجددا تلاش برای ارسال و یا دریافت اطلاعات در مورد آن صورت نخواهد گرفت.');
		}
		if ($row['current_status']>39) {
			return msg_box('success','آخرین پیامک '.$time.' قبل با موفقیت به مخابرات منتقل شد.');
		}
		if ($row['current_status']>19) {
			return msg_box('info','آخرین پیامک '.$time.' قبل ارسال شده است. وضعیت پیامک تا لحظاتی بعد مشخص خواهد شد.');
		}
		if ($row['current_status']>9) {
			return msg_box('info','در حال ارسال آخرین پیامک برای بار '.($row['send_retry']+1).' هستیم. تا لحظاتی دیگر ارسال به پایان می رسد.');
		}
		if ($row['current_status']<10) {
			return msg_box('info','آخرین پیام آماده ارسال برای بار '.($row['send_retry']+1).' می باشد. تا لحظاتی بعد ارسال مجدد آن صورت خواهد گرفت.');
		}
		//if ($row['track_id']) return $msg.msg_box('success','قبلا پیامک به خط شما ارسال شده است و مشکلی با خط شما وجود ندارد.');
	}
	if ($count) return $msg.msg_box('notice','هنوز هیچ اطلاعاتی در قبال ارسال موفق به خط شما وجود ندارد.');
	else return msg_box('notice','تا این لحظه هیچ پیامکی به این خط ارسال نشده است.');
}
function last_received_msg($phone_no){
	$rs=db_query('SELECT result,UNIX_TIMESTAMP(addedon) as timestamp FROM notify_sms_incoming WHERE phone_no = "'.$phone_no.'" ORDER BY addedon DESC LIMIT 1;');
	if ($row=db_array($rs)){
		$time=second_to_readable_format(time()-$row['timestamp']);
		if (!$result) return msg_box('success','آخرین پیامک '.$time.' قبل دریافت شد. و پاسخ آن ارسال شد.');
		if ($result==7) return msg_box('success','پیامک فعال سازی '.$time.' قبل دریافت شد. و پاسخ آن ارسال شد.');
		if ($result==1 || $result==3)  return msg_box('notice','آخرین پیامک '.$time.' قبل دریافت شد. و به دلیل نبود اعتبار کافی در حساب شما پاسخ داده نشد.');
		if ($result==2)  return msg_box('notice','آخرین پیامک '.$time.' قبل دریافت شد. و به دلیل عدم توانایی سیستم در تشخیص نرخ درخواستی پاسخی داده نشد.');
	}
	return msg_box('notice','هیچ پیغامی تا این لحظه از این خط دریافت نشده است.');
}