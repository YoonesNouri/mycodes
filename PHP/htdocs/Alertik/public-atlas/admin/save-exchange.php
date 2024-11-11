<?php
include '../../conf.php';
include '../../lib/web.php';
include '../../lib/mysql.php';
include '../../private/backend.php';

echo '<div class="alert alert-success">اطلاعات با موفقیت ذخیره شد.</div>';
set_json('data_atlas',$_POST);
