<?php
include '../../conf.php';
include '../../lib/web.php';
include '../../lib/mysql.php';
include '../../lib/auth-browser.php';
include '../../private/backend.php';

$users=array('atlas'=>array('pass'=>'31a544',));
$user=user_authenticate();

if ($_GET['pg']=='msg') messages_display($user);
if ($_GET['pg']=='reg') users_display($user);

$settings=get_json('settings_atlas');
$sensored=$settings['sensored'];
$notice=$settings['notice'];

echo theme(array(1=>'admin',0=>'admin-head',10=>'admin-foot'),
	['exchange'=>get_json('latest_exchanges'),'default'=>get_json('data_atlas'),'js_file'=>'admin.js'],
	'../theme/atlas');

function messages_display($user){
	echo theme(array(1=>'admin-messages',0=>'admin-head',10=>'admin-foot'),['user'=>$user],'../theme/atlas');
	exit;
}
function users_display($user){
	echo theme(array(1=>'admin-users',0=>'admin-head',10=>'admin-foot'),['user'=>$user,
		'regs'=>db_all('SELECT *,TIMESTAMPDIFF(DAY,member_since,now()) as days FROM atlas_users ORDER BY uid DESC')],'../theme/atlas');
	exit;
}