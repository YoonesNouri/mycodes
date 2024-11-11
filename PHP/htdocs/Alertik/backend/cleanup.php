<?php
chdir(dirname(__FILE__));
ini_set('memory_limit','100M');
ini_set('max_execution_time','10000'); 
ini_set('request_terminate_timeout','300'); 
include 'libs.php';

/* Remove blocked news after 3 hours */
db_query('UPDATE news SET status = -1 WHERE status = 0 AND TIMESTAMPDIFF(HOUR,addedon,NOW())>3');