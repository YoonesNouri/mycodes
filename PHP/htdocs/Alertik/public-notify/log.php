<?php
include '../private-notify/notify-backend.php';
include '../private-notify/lib-user.php';
include '../private-notify/conf-packages.php';

if (!user_cookie_authenticate()) redirect('/');
if ($master_id[$user['uid']]&&$_GET['i']!=null) $user['uid']=$_GET['i'];

$page['title']='گزارش حساب';
$page['content']=msg_box('success','گزارش زیر محدود به 50 عملکرد آخر حساب کاربری شما می باشد.').'<h2>گزارش حساب کاربری شما</h2>'.logs($user['uid']);
include "../private-notify/notify-page.php";

function logs($uid){
	global $trigger_types,$packages_map;
	/*		Sent Messages		*/
	$rs=db_query('SELECT *,UNIX_TIMESTAMP(addedon) as added FROM notify_sms WHERE uid ='.$uid.' ORDER BY addedon DESC LIMIT 80;');
	while($row=db_array($rs)){
		$row['content']=str_replace("\n",'<br>',$row['content']);
		if ($row['trigger_type']==30) $msg='پاسخ پیامک دو سویه';
		else {
			/*		Normal & Package trigger		*/
			if (!$packages_map[$row['trigger_id']])	{
				$msg='رویداد از نوع '.$trigger_types[$row['trigger_type']].' به کد '.$row['trigger_id'];
			}else{
				$msg='سرویس عمومی';
			}
		}
		list(,$row['content'])=explode('<br>',$row['content'],2);
		$data[$row['added']+1001]=['<td>ارسال پیامک</td><td>'.$msg.'</td><td style="direction:ltr;padding-left:30px;font-weight:normal">'.$row['content'].'</td><td>'.to_jalali($row['addedon'],true).'</td><td>'.to_jalali($row['lastupdate'],true).'</td><td>'.($row['send_retry']+1).'</td>',(($row['current_status']==60)?1:0)];
	} 	
	
	/*		Money Transfer		*/
	$rs=db_query('SELECT *,UNIX_TIMESTAMP(addedon) as timestamp FROM notify_transfers WHERE uid ='.$uid.' ORDER BY addedon DESC LIMIT 10;');
	while($row=db_array($rs)){
		$i=json_decode($row['memo'],true);
		if ($i['memo']) $memo=' ('.$i['memo'].')';
		$data[$row['timestamp']+1002]=['<td>پرداخت</td><td colspan="2">'.(($row['valid']=='Y')?'واریز به مبلغ ':'واریز ناموفق به مبلغ ').number_format($row['amount']).$memo.'</td><td>'.to_jalali($row['addedon'],true).'</td><td>-</td><td>-</td>'];
	} 	
	
	/*		SMS Receive		*/
	$rs=db_query('SELECT *,UNIX_TIMESTAMP(addedon) as timestamp FROM notify_sms_incoming WHERE uid ='.$uid.' ORDER BY addedon DESC LIMIT 80;');
	while($row=db_array($rs)){
		$data[$row['timestamp']+1000]=['<td>دریافت پیامک</td><td>دریافت پیامک</td><td style="direction:ltr;padding-left:30px;font-weight:normal">'.str_replace("\n",'<br />',$row['message']).'</td><td>'.to_jalali($row['addedon'],true).'</td><td>-</td><td>-</td>'];//<td>-</td>
	} 	
	$out='<table class="common_tbl"><tr><th>نوع ردیف</th><th>نوع عملکرد</th><th>متن پیامک</th><th>ارسال اولیه</th><th>ارسال موفق</th><th>سعی برای ارسال</th></tr>';//<th>وضعیت فعلی</th>
	if (is_array($data)) {
		krsort($data);
		foreach ($data as $val){
			$out.='<tr'.(($count%2==0)?' class="even"':'').($val[1]?' style="background-color:#FFC2C8"':'').'>'.$val[0].'</tr>';
			if (++$count>50) break;
		}
	}
	return $out.'</table>';
}
