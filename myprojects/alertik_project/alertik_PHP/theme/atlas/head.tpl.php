<!DOCTYPE html>
<html lang="fa-IR">
	<head>
		<title><?=$title?></title>
		<link href="/s/s.css?1" rel="stylesheet" media="screen">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />

		<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(['_setAccount','UA-26522562-9']);_gaq.push(['_trackPageview']);<?=(($rev)?'var _rev="'.$rev.'";':'')?><?=(($js)?$js:'')?></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
		<script type='text/javascript' src='/s/lib.js'></script>
		<script type='text/javascript' src='/s/s.js'></script>
	</head>
	<body id='body'> 
		<div class='container' id="container">
			<div class='row topmenu'>
				<div class='span3 logo_area'>
					<img src='img/logo.png' />
				</div>
				<div class='span6 toplinks'>
					<a href='/' class='selected'>صفحه اصلی</a>
					<a href='/news'>خبرهای اقتصادی</a>
					<a href='/vip.php'>عضویت</a>
					<a href='#contactus'>تماس با ما</a>
					<div class='cls'></div>
				</div>
				<div class='span3'><div class='date'>
					<?=(($date)?'<div class="adate tt"'.(($archive_timestamp)?' data-time="'.floor($archive_timestamp/60).'"':'').'>'.$date.'</div>':'')?>
				</div></div>
			</div>

			<div class='row header'>
				<div class='span8 slider'>
					<div class="flexslider">
						<ul class="slides">
							<li><img src="/s/examples/1.jpg"></li>
							<li><img src="/s/examples/2.jpg"></li>
							<li><img src="/s/examples/3.jpg"></li>
							<li><img src="/s/examples/4.jpg"></li>
							<li><img src="/s/examples/5.jpg"></li>
						</ul>
					</div>
				</div>
				<div class='span4 contact'>
					<div class='contact_ways'>راه های ارتباط با ما</div>
					<div class='header_data'>
						<div class='header_title ouraddress'>نشانی ما</div>
						<div class='header_content address'>آدرس دفتر مرکزی: جزیره کیش، خیابان فردوسی، بازار مرکز تجاری کیش، طبقه همکف، پلاک 77 (دفتر مرکزي)</div>
					</div>

					<div class='header_data'>
						<div class='header_title phonenumbers'>شماره های تماس با ما</div>
						<div class="header_content phones">
							<span>
								<p>
									<span>تلفن: </span>
									<span class="left">۴۴۵۲۵۲۵ - ۰۷۶۴</span>
								</p>

								<p>
									<span>فکس: </span>
									<span class="left">۴۴۵۲۵۲۱ - ۰۷۶۴</span>
								</p>
						  
								<p>
									<span>تلفن همراه: </span>
									<span class="left">۲۸۰۷۷۷۷ - ۰۹۱۲</span>
								</p>

								<p>
									<span>تلفن مباشر تهران: </span>
									<span class="left">۲۲۲۲۸۱۸۱ - ۰۲۱</span>
								</p>

							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class='middle_area'>
			<div class='container'>