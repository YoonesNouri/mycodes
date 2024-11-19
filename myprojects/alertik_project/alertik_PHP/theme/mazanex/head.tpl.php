<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
<meta charset="utf-8">
<title><?=$title?></title>
<link href="/static/s.css" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(['_setAccount','UA-26522562-2']);_gaq.push(['_setDomainName', 'mazanex.com']);_gaq.push(['_trackPageview']);<?=(($rev)?'var _rev="'.$rev.'";':'')?><?=(($js)?$js:'')?></script>
<body>
<div id="header">
	<div id="nav">
		<ul>
			<li id="logo"><a href="/"><img src="/static/logo.png" alt="Mazanex"></a></li>
			<li><a href="/">قیمت طلا، ارز و سکه</a></li>					
			<li><a href="/diamond/">قیمت برلیان</a></li>
			<li><a href="http://www.aclick.ir">آگهی</a></li>
			<li><a href="/contact.php">تماس با ما</a></li>
			<li id="nclose"></li>
		</ul>
		<?=(($date)?'<div class="adate tt"'.(($archive_timestamp)?' data-time="'.$archive_timestamp.'"':'').'>'.$date.'</div>':'')?>
		<?=(($countdown)?'<div class="counter dynamic" data-time="'.$countdown.'"><span></span> تا بارگذاری مجدد - <a class=manual href="/">بارگذاری دستی</a> / <a href="/" class="archive">آرشیو</a></div>':'')?>
	</div>
</div>
<div id="container">