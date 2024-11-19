<?php
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

page($page);
function page($pg){
	$content=$pg['content'].'</div><div class="clear"><input type="hidden" id="type" value="'.$pg['ajax'].'" />';
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {// If not ajax
		echo $content;
		return;
	}
	if (!$pg['title']) $pg['title']='قیمت سکه در بورس آتی';
	$page='<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
<meta charset="utf-8">
<title>'.$pg['title'].'</title>
<link href="/static/s.css?14" media="all" rel="stylesheet" type="text/css" />
<script type="text/javascript"> var _gaq = _gaq || []; _gaq.push([\'_setAccount\', \'UA-26522562-5\']); _gaq.push([\'_setDomainName\', \'atirate.com\']); _gaq.push([\'_trackPageview\']);'.(($pg['rev'])?'var _rev="'.$pg['rev'].'";':'').(($pg['js'])?$pg['js']:'').'</script>
</head><body>
<div id="header">
	<div id="nav">
		<ul>
			<li id="logo"><a href="/"><img src="/static/logo.png" alt="Mazanex" /></a></li>
			<li><a href="http://www.atirate.com">قیمت سکه در بورس آتی</a></li>	
			<li><a href="http://www.arzlive.com">قیمت طلا، ارز و سکه</a></li>
			<li><a href="http://www.aclick.ir/">آگهی</a></li>
			<li><a href="/contact.php">تماس با ما</a></li>
			<li id="nclose"></li>
		</ul>
		'.(($pg['date'])?'<div class="adate">'.$pg['date'].'</div>':'')
		.(($pg['countdown'])?'<div class="counter dynamic" data-time="'.$pg['countdown'].'"><span></span> تا بارگذاری مجدد - <a class="manual" href="/">بارگذاری دستی</a></div>':'').'
	</div>
</div>
<div id="container">'.$content.'</div><div class="hide" id="ime_box"></div><div id="freespace" class="hide"></div>
<div id="footer">تمام اطلاعات سایت از دو منبع <a href="http://www.mazanex.com">سایت مظنه</a> ،<a href="http://new.ime.co.ir/">بورس کالا</a> و <a href="http://www.arzlive.com">ارز لایو</a> دریافت می شود.<br />احتمال خطا در اطلاعات وجود دارد قبل از استفاده با منبع دیگری صحت اطلاعات را کنترل نمایید.</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="/static/s.js?23" type="text/javascript"></script>'.(($pg['js-file'])?'<script type="text/javascript" src="/static/'.$pg['js-file'].'"></script>':'').(($pg['js-footer'])?'<script type="text/javascript">'.$pg['js-footer'].'</script>':'').'</body></html>';
	if ($pg['save']) {		
		file_write('public-ati/'.$pg['save'],$page,true);
		if ($pg['ajax']) {
			if ($pg['ajax-content'])
				file_write('public-ati/ajax/'.$pg['ajax'].'.html',$pg['ajax-content']);
			if ($pg['ajax-content-full'])
				file_write('public-ati/ajax/'.$pg['ajax'].'f.json',$pg['ajax-content-full']);
			if ($pg['ajax-content-semi'])
				file_write('public-ati/ajax/'.$pg['ajax'].'s.json',$pg['ajax-content-semi']);
		}
	} else echo $page;
}//<li><a href="http://www.bemoghe.com">سرویس پیامک</a></li>