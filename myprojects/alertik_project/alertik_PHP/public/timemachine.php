<?php
/*$count=(int)apc_fetch('mxt'.$_SERVER['REMOTE_ADDR']);
$count++;
if ($count==120) {
	echo json_encode(array('notice'=>'<div>بیش از تعداد پیش فرض از این ابزار استفاده نموده اید. برای استفاده مجدد از این ابزار باید 24 ساعت بعد مراجعه نمایید.</div>'));
	exit;
}
apc_store ('mxt'.$_SERVER['REMOTE_ADDR'],$count,43200);*/

$timestamp=(int)$_GET['t'];
$name=str_split($timestamp,2);
$filename='ajax/i/'.$name[0].'/'.$name[1].'/'.$name[2].'/'.$name[3].'.json';
if (!file_exists($filename)) {
	echo json_encode(array('notice'=>'<div>اطلاعات زمان مشخص شده یافت نشد.</div>'));
	exit;
}
echo file_get_contents($filename);