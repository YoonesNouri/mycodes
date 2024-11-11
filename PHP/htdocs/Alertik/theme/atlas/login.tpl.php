<?php
$title='ورود به بخش وی آی پی';
echo $notice;
?>
<div class="middle_area">
	<div class="container">
		<div class="row">
			<div style="margin-top:30px" class="span12 well">

				<div id="SignIn" class="span5">
					<form class="login" method="post" action="?">

						<h2>ورود</h2>

						<label for="lemail">آدرس ایمیل</label>
						<input type="email" id="lemail" name="email" placeholder="آدرس ایمیل" required="">

						<label for="lpassword">رمز عبور</label>
						<input type="password" id="lpassword" name="pass" placeholder="رمز عبور" required="">

						<input type="submit" name="login" value="ورود">
					</form>
				</div>

				<div id="SignUp" class="span7" style="margin-right:0">

					<form class="register" method="post" action="?">

						<h2>ثبت نام</h2>

						<label for="name">نام و نام خانوادگی</label>
						<input type="text" id="name" name="name" placeholder="نام و نام خانوادگی" required="">

						<label for="email">آدرس ایمیل</label>
						<input type="email" id="email" name="email" placeholder="آدرس ایمیل" required="">

						<label for="password">رمز عبور</label>
						<input type="password" id="password" name="pass" placeholder="رمز عبور" required="">

						<label for="repassword">تکرار رمز عبور</label>
						<input type="password" id="repassword" name="repass" placeholder="تکرار رمز عبور" required="">

						<label for="phone">شماره تلفن همراه</label>
						<input type="tel" id="phone" name="phone" pattern="[0-9]{5,}" placeholder="شماره تلفن همراه">

						<input type="submit" name="register" value="ثبت نام">
					</form>
				</div>
			</div>
		</div>
</div>
</div>