<?php
/*
	user_authenticate()
		return user details
	Sample config
		$users=array('username'=>array('pass'=>'321',));
*/
function user_authenticate(){
	global $users;
	if ($_SERVER['PHP_AUTH_PW']&&$users[$_SERVER['PHP_AUTH_USER']]['pass']==$_SERVER['PHP_AUTH_PW']) {
		$return=$users[$_SERVER['PHP_AUTH_USER']];
		$return['user']=$_SERVER['PHP_AUTH_USER'];
		return $return;
	}
	browser_pass();
}
function browser_pass(){
	Header("WWW-Authenticate: Basic realm=\"Limited Access\"");
	Header("HTTP/1.0 401 Unauthorized");
	echo 'Unauthorized';
	exit;
}
function authenticated_user_by_id($id){
	global $users;
	foreach ($users as $user) {
		if ($user['id']==$id) return $user['name'];
	}
}