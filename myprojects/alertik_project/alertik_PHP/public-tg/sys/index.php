<?php
include '../../private/db.php';
include '../../private/backend.php';
if(!$user=user_pass_check()) browser_pass();

if($_GET['i']=='advertisement'){
	if ($_POST['advertisement']) advertisement_save($_POST);
	$page['content']=advertisement_show();
	$page['js-footer']=advertisement_js();
}elseif($_GET['i']=='settings'){
	if ($_POST['settings']) settings_save($_POST);
	$page['content']=settings_show();
} else {
	if ($_POST['price']) price_save($_POST);
	$page['content']=price_show();
	$page['js-footer']=advertisement_js();
}
$page['content']='<a href="?i=price">قیمت دستی</a> | <a href="?i=settings">کنترل سایت</a> | <a href="?i=advertisement">اگهی های سایت</a>'.$page['content'];

include "../../private-tg/page.php";
function user_pass_check(){
	$master='amah19100';
	if ($_SERVER['PHP_AUTH_USER'] == $master && $_SERVER['PHP_AUTH_PW'] == $master) return 1;
	/*if ($_SERVER['PHP_AUTH_USER'] == 'ads' && $_SERVER['PHP_AUTH_PW'] == getCFG('tgju_data_user')){
		$_GET['i']='advertisement';
		return 2;
	}*/
}
function price_save($i){
	setJSON('tgju_manual',$i);
}
function price_show(){
	$strs=array(40=>'دلار',43=>'درهم',2=>'مثقال طلا',10=>'سکه قدیم',11=>'سکه جدید',12=>'نیم سکه',13=>'ربع سکه',14=>'سکه گرمی');
	$data=getJSON('tgju_manual');
	foreach ($strs as $key => $name){
		$val=$data['i3_'.$key];
		$out.=$name.'<br /><input name="i3_'.$key.'" size="10" value="'.(($val)?$val:'').'" /><br />';
	}
	return '<form>'.$out.'<input name="price" type="hidden" value="1" /><input name="" type="submit" value="ذخیره" /></form>';
}
function advertisement_save($i){
	$data=getJSON('tg_ad_'.$i['advertisement']);
	if ($i['del']){
		unset($data[$i['index']]);
	} else {
		if (!$i['index']) {		
			$i['index']='t'.time();
			echo ad_input('',$i).ad_input($i['advertisement']);
		} else {
			echo ad_input('',$i);
		}	
		$data[$i['index']]=array('url'=>$i['url'],'img'=>$i['img'],'act'=>$i['act'],'index'=>$i['index']);
	}
	ksort($data);
	setJSON('tg_ad_'.$i['advertisement'],$data);
	exit;
}
function advertisement_js(){
	return '$(function(){
		$("#container").delegate("form","submit",function(e){
		e.preventDefault();
		var replace_target=$(this);
		$.post("", replace_target.serialize() ,function(data){ 
			replace_target.replaceWith(data);
		});
	});
	});';
}
function advertisement_show(){
	$out='<h2>صفحه اصلی - افقی بالا</h2>'.display_ads('main_top_right2').
		'<h2>صفحه اصلی - افقی پایین</h2>'.display_ads('main_bottom_right').
		'<h2>باکس سمت چپ بالا</h2>'.display_ads('main_top_left').
		'<h2>باکس سمت چپ بالا دومی</h2>'.display_ads('main_top2_left').
		'<h2>باکس سمت چپ پایین</h2>'.display_ads('main_bottom_left');
	return $out.'</table>';
}
function display_ads($type){
	$ads=getJSON('tg_ad_'.$type);
	foreach ($ads as $index=>$ad){
		$out.=ad_input($type,$ad);
	}
	return $out.ad_input($type);
}
function ad_input($type,$i=array('url'=>'','img'=>'','act'=>1,'index'=>0)){
	if ($type) $i['advertisement']=$type;
	$out.='<form>'
		.(($i['img'])?'<img src="http://66.228.58.196/'.$i['img'].'" height="50px"/><br />':'')
		.'<input name="index" size="12" value="'.$i['index'].'" /> ' 
		.'<input name="url" value="'.$i['url'].'" size="30" dir="ltr" placeholder="HTML آدرس سایت و یا کد" />'
		.'<input name="img" value="'.$i['img'].'" size="30" dir="ltr" placeholder="آدرس تصویر" />'
		.'<input name="act" type="checkbox" value="1" '.(($i['act'])?'checked="checked"':'').' /> فعال  &nbsp; &nbsp;'
		.'<input name="del" type="checkbox" value="1" /> حذف &nbsp; &nbsp;'
		//.'<input name="type" type="hidden" name="del" value="'.$weight.'" />'
		.'<input name="advertisement" type="hidden" value="'.$i['advertisement'].'" />'
		.'<input name="" type="submit" value="ذخیره" />'		
		.'</form>';
	return $out;
}
function settings_show(){
	$elements=array(
		'سکه'=>'title',
		'3:10'=>'بهار آزادی','3:11'=>'امامی','3:12'=>'نیم','3:13'=>'ربع','3:14'=>'گرمی',
		'طلا'=>'title',
		'0:1'=>'انس طلا','3:2'=>'مثقال طلا','3:3'=>'گرم طلای 18','0:5'=>'انس نقره',
		'غیره'=>'title',
		'13:114'=>'شاخص بورس','ati'=>'سکه آتی',
		'نرخ برابری'=>'title',
		'0:40'=>'دلار','0:41'=>'یورو','0:42'=>'پوند','0:43'=>'درهم امارات','0:44'=>'دلار کانادا','0:45'=>'یوان چین','0:46'=>'لیره ترکیه',
		'بازار تهران'=>'title',
		'3:40'=>'دلار','3:41'=>'یورو','3:42'=>'پوند','3:43'=>'درهم امارات','3:44'=>'دلار کانادا','3:45'=>'یوان چین','3:46'=>'لیره ترکیه',
		'ارز غیر مرجع'=>'title',
		'15:40'=>'دلار','15:41'=>'یورو','15:42'=>'پوند','15:43'=>'درهم امارات','15:44'=>'دلار کانادا','15:45'=>'یوان چین','15:46'=>'لیره ترکیه'
	);
	$settings=getJSON('settings_tg');
	$out='<form method="post">
		<h2>پسورد کاربر قیمت</h2>
		<input name="tgju_data_user" type="text" value="'.getCFG('tgju_data_user').'" />
		<h2>نمایش پیغام در بالای سایت - برای عدم نمایش پیغام باکس زیر را به طور کامل خالی نمایید</h2>
		<textarea cols=80 rows=5 name="notice" placeholder="متن مورد نظر خود را برای نمایش در بالای سایت وارد نمایید.">'.$settings['notice'].'</textarea><br /><br />'
		.'<h2>عدم نمایش</h2>برای عدم نمایش گزینه ها موارد را در زیر انتخاب نمایید.<br><table>';
	foreach ($elements as $key=>$val){
		if ($val=='title'){
			$out.='<tr><th colspan=5>'.$key.'<tr>';
			$count=0;
		} else {
			if (++$count==6){
				$count=0;
				$out.='</tr><tr>';
			}
			$out.='<td>'.$val.' <input type="checkbox" name="hide[]" value="'.$key.'" '.(($settings['sensored'][$key])?'checked="checked"':'').' />';
		}
	}
	return $out.'</table><input type="submit" value="ذخیره نمایش" name="settings" /></form>';
}
function settings_save($i){
	if (is_array($i['hide'])) foreach ($i['hide'] as $item){
		$sensor[$item]=1;
	}
	setJSON('settings_tg',array('sensored'=>$sensor,'notice'=>$i['notice']));
	setCFG('tgju_data_user',$i['tgju_data_user']);
}
function browser_pass(){
	Header("WWW-Authenticate: Basic realm=\"TGJU Control Panel\"");
	Header("HTTP/1.0 401 Unauthorized");
	echo 'رمز عبور و یا کلمه کاربری شما اشتباه میباشد';
	exit;
}