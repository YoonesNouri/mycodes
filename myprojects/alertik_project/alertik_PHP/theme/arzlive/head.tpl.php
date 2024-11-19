<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
<meta charset="utf-8">
<title><?=$title?></title>
<link href="/s/s.css?4" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(['_setAccount','UA-26522562-15']);_gaq.push(['_trackPageview']);<?=(($rev)?'var _rev="'.$rev.'";':'')?><?=(($js)?$js:'')?></script>
<body>
<div id="progress-bar"><div id="progress-fill"></div></div>
<div class="notice_wrapper"><div <?=ajk('notice','container')?>><?=ajv((($notice)?'<div>'.$notice.'</div>':''))?></div></div>
<div class="container">
	<div class="row" id="header">
		<div class="col12">
			<a href="/" id="logo"><img src="/s/logo2.png" alt="arzlive"></a>
			<?=(($date)?'<div class="adate tt">'.$date.'</div>':'')?>
			<?=(($countdown)?'<div class="counter dynamic" data-time="'.$countdown.'"><span></span> تا بارگذاری مجدد - <a class=manual href="/">بارگذاری دستی</a></div>':'')?>&nbsp;
		</div>
		<div class="clear"></div>
	</div>	
	<nav class="row">
		<div class="col12">
			<a href="/">صفحه اصلی</a>
			<a href="/app.html">نرم افزار تلفن همراه</a>
			<a href="/charts.html" id="">نمودار دلار سکه و طلا</a>
			<a href="http://www.aclick.ir">آگهی در ارزلایو</a>
			<a href="/webmaster.html">ابزار وب مستر</a>
			<a href="/contact.php">تماس با ما</a>
		</div>
		<div class="clear"></div>
	</nav>