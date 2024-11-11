<?php
include 'notify-backend.php';
include 'lib-user.php';
include 'lib-sms.php';
include 'lib-credit.php';

get_sms_gateway(2500);
//account_test();
//transaction_test();
//twoway_test();
//trigger_test();
//send_test();
function get_sms_gateway($number){
	$ch = curl_init();
	list($url,$content,$header)=sms_gateway_get($number);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$content);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	curl_setopt($ch, CURLOPT_TIMEOUT,15);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	$result= curl_exec ($ch);
	curl_close ($ch);

	$messages=explode('<MessagesBL>',$result);
	if (count($messages)<2) return false;
	foreach ($messages as $msg){
		$tmp=sms_gateway_get_parse($msg);
		$md5=md5($tmp['content']);
		echo 'Track:'.$tmp['track_id'].' MD5:'.$md5.' Status:'.$tmp['status'].'<br>';
		if (isset($dup[$md5])) echo $tmp['content'].'<br>';
		$dup[$md5]=$tmp['content'];
	}
}
function account_test(){
	echo '<h4>Register</h4>';
	echo '<h4>Login</h4>';
	echo '<h4>Authentication</h4>';
}
function transaction_test(){
	$uid=1;
	echo '<h4>Transfer Money</h4>';
	/*		Credit = 100 , days extend 40 days		*/
	credit_in(array('uid'=>$uid,'amount'=>49000,'service'=>'12S1','transfer_type'=>1,'memo'=>'testunit'));
	echo '<h4>Transfer again before expiration date</h4>';
	credit_in(array('uid'=>$uid,'amount'=>49000,'credit'=>70,'days'=>10,'transfer_type'=>1,'memo'=>'testunit'));
	echo '<h4>Credit use</h4>';
	credit_use($uid,-1); 
	/*		Make credit expire by hand		*/
	echo '<h4>Transfer again after expiration</h4>';	
	credit_in(array('uid'=>$uid,'amount'=>49000,'service'=>'12S1','transfer_type'=>1,'memo'=>'testunit'));
}
function twoway_test(){
	echo '<h4>SMS format detected</h4>';
	echo '<h4>SMS content create</h4>';
}
function trigger_test(){
	echo '<h4>Exact price test</h4>';
	// $latest_exchanges['3:40']['sell']=2200;
	// $latest_exchanges['3:40']['timestamp']=$latest_exchanges['3:40']['timestamp']+1000000;
	/*		1. Add target		*/
	/*		2. Check if its not executed in normal condition	*/
	/*		3. Make price reach target price		*/
	/*		4. It should be disabled		*/
	echo '<h4>Exact change percentage test</h4>';
	/*		1. Add percentage and change amount		*/
	/*		2. Check if they converted correctly	*/
	/*		3. It should correctly replaced with the next result	*/
	/*		4. Should not run for entered value		*/
	echo '<h4>Exact date check</h4>';
	/*		1. Get current date 		*/
	/*		2. Add trigger for soonest one		*/
	/*		3. It should not executed before reaching that time		*/
	/*		4. It should executed on time		*/
	/*		5. It should be checked for executed after time  */
	/*		6. Replaced correctly with new time		*/
	echo '<h4>Interval Check</h4>';
	/*		1. Is it correctly converted		*/
	/*		2. and is it replaced correctly with new value		*/
	echo '<h4>Misc</h4>';
	/*		Check time limits		*/
}
function send_test(){
	echo '<h4>SMS collected correctly to send again</h4>';
}
//credit_in(6,200,sms,days);
//credit_transfer(6,-500);
	/*	
	user_cookie_authenticate
	user_register()
	user_signin
	user_signout*/
	
//$_POST['phone_no']=9121250444;
//$_POST['pass']=1234567;
//$_POST['email']='sss@sss.com';
//user_signout();
//var_dump(user_signin());
//var_dump(user_cookie_authenticate());
//print_r($user);
//echo'OK';
