<?php 
include 'libs.php';
include '../lib/leech-manage.php';

echo theme(array(1=>'leech-panel',0=>'head',10=>'foot'),monitors_list($_GET['search']),'theme/backend');
