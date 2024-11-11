<?php
/*
	+ Implement Watch Dog
		+ Integrate Memcache, APC, Redis or Something Else
*/
/*
	user_cookie_authenticate
	user_register	
	user_signin
	user_signout
	
	register_session
*/


/*		Check if user is authernticated using cookie		*/
function user_cookie_authenticate() {
	global $user;
	if (isset($_COOKIE["uid"])) $uid = (int) $_COOKIE["uid"]; else $uid=false;
	if (!$uid) return false;
	if (!isset($_COOKIE["token"])) return false;
	if (strlen($_COOKIE["token"])!=32)	return false;
	if (db_array(db_query("SELECT uid FROM notify_sessions WHERE uid = '".$uid."' AND expire>".time()." AND token = '".mysql_real_escape_string($_COOKIE["token"])."'")))
		return $user["uid"]=$uid;		
	return false;
}

/*
	User registration
*/
function user_register() {
	
	preg_match("/([1-9][0-9]+)/", $_POST['phone_no'],$phone);
	$pass=trim($_POST['pass']);
	// Check Email Address
	if ($_POST["email"]){
		if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
			$error=array('#register .email','آدرس ایمیل شما صحیح نمی باشد.');		
		} else {
			if (db_array(db_query('SELECT uid FROM notify_users WHERE email = "'.$_POST["email"].'"'))) $error=array('#register .email','این آدرس ایمیل قبلا ثبت شده است.');		
		}
	}
	
	// Check Email Address
	if (strlen($phone[0])!=10){
		$error=array('#register .phone_no','شماره تلفن اشتباه می باشد.');		
	} else {
		if (db_array(db_query('SELECT uid FROM notify_users WHERE phone_no = "'.$phone[0].'"'))) $error=array('#register .phone_no','این شماره تلفن قبلا ثبت شده است.');		
	}
	
	// Check Password
	if (strlen($_POST["pass"])<5) $error=array('#register .pass','کلمه عبور وارد شده کوتاه می باشد.');
	
	if (!$_POST["agreement"]) $error=array('#register .agreement','شرایط عضویت و ثبت نام تیک نشده است.');
	
	if ($error) return $error;

	db_query('INSERT INTO notify_users (phone_no, phone_approved, email, email_approved, name, pass, member_since, credit, service_until) VALUES 
		("'.$phone[0].'",'.mt_rand(1, 65535).',"'.mysql_real_escape_string($_POST["email"]).'",'.mt_rand(1, 65535).',"'.mysql_real_escape_string($_POST["lname"].'|'.$_POST["fname"]).'","'.md5($_POST["pass"]).'",NOW(),0,NOW())'); 
	// 3,DATE_ADD(NOW(),INTERVAL 3 DAY) free 3 days 3 sms credit

	$last_id=mysql_insert_id();
	
	return register_session($last_id);	
}

function register_session($uid) { 
	global $user;
	$token=md5($uid+time());
	$expire=time()+8640000; // Expires in 100 days 
	db_query('REPLACE INTO notify_sessions (uid,token,expire) VALUES ('.$uid.',"'.$token.'",'.$expire.');');
	$user["uid"]=$uid;
	setcookie("token",$token,$expire,"/"); 
	setcookie("uid",$uid,$expire,"/");
	return true;
}

function user_signin(){
	preg_match("/([1-9][0-9]+)/", $_POST['phone_no'],$phone);
	if ($tmp=db_array(db_query('SELECT uid FROM notify_users WHERE phone_no = "'.$phone[0].'" AND pass = "'.md5(trim($_POST["pass"])).'"'))) {
		return register_session($tmp['uid']);
	}
	return array('#login .phone_no','شماره تلفن و یا کلمه عبور وارد شده صحیح نمی باشد.');		
}

function user_signout() {
	setcookie("token",'',time(),"/"); //+add path
}
function get_phone_no($uid){
	$rs=db_query('SELECT phone_no FROM notify_users WHERE uid="'.$uid.'"');
	if ($row=db_array($rs)){
		return $row['phone_no'];
	}
	return 0;
}