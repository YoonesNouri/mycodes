<?php
if (strlen($_POST['message'])>10) {
	echo '<div class="alert alert-success">با تشکر پیغام شما دریافت شد</div>';
	$source_id=1001;
	include '../../sap/recorder.php';
	exit;
}
echo '<div class="alert alert-error">پیغام شما بسیار کوتاه می باشد.</div>';