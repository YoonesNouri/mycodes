<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
<meta charset="utf-8">
<title><?=$title?></title>
<link href="/s/s.css?5" media="all" rel="stylesheet" type="text/css">
<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(['_setAccount','UA-26522562-9']);_gaq.push(['_trackPageview']);<?=(($rev)?'var _rev="'.$rev.'";':'')?><?=(($js)?$js:'')?></script>
<body>
<div id="header">
	<div id="logo_area"><a href="/"><img src="/s/h.png" alt="TGJU"></a><?=(($countdown)?'<div class="counter dynamic" data-time="'.$countdown.'"><span></span> تا بارگذاری مجدد - <a class=manual href="/">بارگذاری دستی</a></div>':'')?></div>
</div>
<div id="nav_wrapper">
	<div id="nav">
		<a href="/">قیمت سکه و طلا</a>
		<!--<a href="/advertise.html">درج آگهی</a>
		<a href="/contact.php">تماس با ما</a>-->
		<?=(($date)?'<div class="adate tt"'.(($archive_timestamp)?' data-time="'.floor($archive_timestamp/60).'"':'').'>'.$date.'</div>':'')?>
	</div>
</div>
<div id="container">