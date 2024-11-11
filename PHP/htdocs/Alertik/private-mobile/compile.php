<?php
$data=explode('<--!CSS-->',file_get_contents('index.html'));
$css=file_get_contents('compiled/style.css');
file_put_contents('../public-mobile/index.html',preg_replace('~>\s+<~', '><', $data[0].$css.$data[1]));

$data=explode('<--!CSS-->',file_get_contents('index-list.html'));
$css=file_get_contents('compiled/style.css');
file_put_contents('../public-mobile/list.html',preg_replace('~>\s+<~', '><', $data[0].$css.$data[1]));