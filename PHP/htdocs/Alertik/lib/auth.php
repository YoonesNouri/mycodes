<?php
/*
	v0.02
	user_cookie_authenticate
		return true if user is logged in
	user_register	
		return true if user is registered or array of errors
	user_signin
		return true if username and password is correct
	user_signout
		delete cookie
	user_pass_change
	email_to_uid

*/

/*		
	Check if user is authernticated using cookie		
*/
function user_cookie_authenticate() {
	global $user;
	if (isset($_COOKIE["uid"])) $uid = (int) $_COOKIE["uid"]; else $uid=false;
	if (!$uid) return false;
	if (!isset($_COOKIE["token"])) return false;
	if (strlen($_COOKIE["token"])!=32)	return false;
	if ($user=db_array(db_query("SELECT uid FROM user_sessions WHERE uid = '".$uid."' AND expire>".time()." AND token = '".db_safe($_COOKIE["token"])."'"))){
		return $user["uid"];
	}
	return false;
}

/*
	User registration
*/
function user_register($details='') {
	$pass=trim($_POST['pass']);
	$email=strtolower($_POST['email']);
	
	// Check Email Address
	if (filter_var($email, FILTER_VALIDATE_EMAIL)===false) return 'email-invalid';
	if (db_array(db_query('SELECT uid FROM users WHERE email = "'.$email.'"'))) return 'email-duplicate';
	
	// Check Password
	if (strlen($_POST["pass"])<5) return 'pass-short';

	db_query('INSERT INTO users (email, email_approved, pass, member_since'.(($details)?', user_details':'').') VALUES 
		("'.db_safe($email).'",'.mt_rand(1, 65535).',"'.md5(md5($pass)).'",NOW()'.(($details)?", '".db_json_encode($details)."'":'').')'); 

	$last_id=db_last_id();
	
	return register_session($last_id);	
}
function register_session($uid) { 
	global $user;
	$token=md5($uid+time());
	$expire=time()+8640000; // Expires in 100 days 	
	db_query('REPLACE INTO user_sessions (uid,token,expire) VALUES ('.$uid.',"'.$token.'",'.$expire.');');
	$user["uid"]=$uid;
	setcookie("token",$token,$expire,"/"); 
	setcookie("uid",$uid,$expire,"/");
	return true;
}
function user_signin(){
	$email=strtolower($_POST['email']);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
	if ($tmp=db_row('SELECT uid FROM users WHERE email = "'.db_safe($email).'" AND pass = "'.md5(md5(trim($_POST["pass"]))).'"')) {
		return register_session($tmp['uid']);
	}
	return false;
}
function email_to_uid($email){
	$email=strtolower($email);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
	if ($tmp=db_row('SELECT uid FROM users WHERE email = "'.db_safe($email).'"')) {
		return $tmp['uid'];
	}
	return false;
}
function user_signout() {
	setcookie("token",'',time(),"/"); //+add path
}
function user_info($uid=0){
	if (!$uid){
		global $user;
		$uid=$user['uid'];
	}	
	$info=db_row('SELECT * FROM users WHERE uid = '.((int)$uid));
	$info['user_details']=json_decode($info['user_details'],true);
	return $info;
}
function user_pass_change($old,$pass){
	global $user;
	if (strlen($pass)<5) return 'pass-short';
	$old=md5(md5(trim($old)));
	$pass=md5(md5(trim($pass)));
	if (!db_row('SELECT uid FROM users WHERE uid='.$user['uid'].' AND pass="'.$old.'"')['uid']) return 'wrong-pass';
	db_query('UPDATE users SET pass="'.$pass.'" WHERE uid='.$user['uid']);
}
function user_id(){
	global $user;
	return $user['uid'];
}