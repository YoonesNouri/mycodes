<?php
$title='اختلاف قیمت';
?>
<form method="post">
	<?= html_table($currencies['captions'],$currencies['data'],['class'=>'rtl']); ?>
	<input name="save" value="ذخیره" type="submit">
</form>