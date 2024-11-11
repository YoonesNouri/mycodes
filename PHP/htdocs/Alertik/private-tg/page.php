<?php
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
page($page);
function page($pg){
	$content=$pg['content'].'</div><div class=clear><input type="hidden" id="type" value="'.$pg['ajax'].'">';
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {// If not ajax
		echo $content;
		return;
	}
	if (!$pg['title']) $pg['title']='قیمت زنده سکه و طلا - اتحادیه طلا و جواهر';
	$page='<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
<meta charset="utf-8">
<title>'.$pg['title'].'</title>
<link href="/s/s.css?5" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript">var _gaq=_gaq||[];_gaq.push([\'_setAccount\',\'UA-26522562-9\']);_gaq.push([\'_trackPageview\']);'.(($pg['rev'])?'var _rev="'.$pg['rev'].'";':'').(($pg['js'])?$pg['js']:'').'</script>
<body>
<div id="header">
	<div id="logo_area"><a href="/"><img src="/s/h.png" alt="TGJU"></a>'.(($pg['countdown'])?'<div class="counter dynamic" data-time="'.$pg['countdown'].'"><span></span> تا بارگذاری مجدد - <a class=manual href="/">بارگذاری دستی</a></div>':'').'</div>
</div>
<div id="nav_wrapper">
	<div id="nav">
		<a href="/">قیمت سکه و طلا</a>
		<a href="/advertise.html">درج آگهی</a>
		<a href="/contact.php">تماس با ما</a>
		'.(($pg['date'])?'<div class="adate tt"'.(($pg['archive_timestamp'])?' data-time="'.floor($pg['archive_timestamp']/60).'"':'').'>'.$pg['date'].'</div>':'')
	.'</div>
</div>
<div id="container">'.$content.'</div>
<div id="footer"><div id="fnav_wrapper"><div id="fnav"><a href="http://www.tgju.ir"></a><a href="http://www.tgju.org"></a><a href="http://www.tgju.net"></a><a href="http://www.nimset.ir"></a></div></div>هر گونه کپی برداری از محتوا، تولیدات، شکل و سایر اجزای سایت صرفا با موافقت مکتوب مجاز می باشد <br>
1391 - پیاده سازی توسط <a href="http://cvas.ir" id="cvas">سیوس ϡ</a></div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="/s/s.js?7" type="text/javascript"></script>'.(($pg['js-file'])?'<script type="text/javascript" src="/s/'.$pg['js-file'].'"></script>':'').(($pg['js-footer'])?'<script type="text/javascript">'.$pg['js-footer'].'</script>':'');//.'</body></html>'
	if ($pg['save']) {		
		file_write('public-tg/'.$pg['save'],$page,true);
		if ($pg['ajax']) {
			if ($pg['ajax-content'])
				file_write('public-tg/ajax/'.$pg['ajax'].'.html',$pg['ajax-content'],true);
			if ($pg['ajax-content-full'])
				file_write('public-tg/ajax/'.$pg['ajax'].'f.json',$pg['ajax-content-full']);
			if ($pg['ajax-content-semi'])
				file_write('public-tg/ajax/'.$pg['ajax'].'s.json',$pg['ajax-content-semi']);
		}	
	} else echo $page;
}//<a href="//m.mazanex.com">نسخه موبایل</a> |