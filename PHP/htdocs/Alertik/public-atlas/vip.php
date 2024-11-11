<?php
include "../private-atlas/rc.php";
include "../lib/auth.php";

if ($_GET['a']=='logout') user_signout();
elseif (user_cookie_authenticate()) exit(theme([1=>'vip',0=>'head',10=>'foot'],[],$theme_folder));

if ($_POST['repass']) { //Register
	if (!$msg=user_register()) exit(theme([1=>'vip',0=>'head',10=>'foot'],['notice'=>notice('با موفقیت ثبت نام شدید','success')],$theme_folder));
}elseif ($_POST['email']) { //Login
	if (user_signin()) exit(theme([1=>'vip',0=>'head',10=>'foot'],[],$theme_folder));
	$msg='آدرس ایمیل و یا کلمه عبور اشتباه می باشد.';
} 

if ($msg) $notice=notice($msg,'error');
echo theme([1=>'login',0=>'head',10=>'foot'],['notice'=>$notice],$theme_folder);

function notice($msg,$type){
  	return '<br><div class="alert'.(($type)?' alert-'.$type:'').'">'.$msg.'</div>';
}