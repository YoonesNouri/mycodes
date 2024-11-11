<?php
$title='نمایش کاربران';
?>
<table>
	<tr>
		<th>کد کاربری</th>
		<th>نام کاربری</th>
		<th>شماره تلفن</th>
		<th>آدرس ایمیل</th>
		<th>زمان ثبت نام</th>		
	</tr>
<?php foreach($regs as $reg){ ?>
	<tr>
		<td><?= $reg['uid'] ?></td>
		<td><?= $reg['name'] ?></td>
		<td><?= $reg['phone'] ?></td>
		<td><?= $reg['email'] ?></td>
		<td style="direction:rtl;"><?= $reg['days'] ?> روز قبل</td>
	</tr>
<?php } ?>
</table>