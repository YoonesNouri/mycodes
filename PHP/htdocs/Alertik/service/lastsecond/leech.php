<?php
@file_put_contents('json/d.json',preg_replace(array('/("ad".+?)+(,)/i','/("earnings".+?)+(},)/i','/("pg_countdown".+?)+(,)/i','/("notice".+?)+(,)/i','/("adt".+?)+(,)/i','/("atm".+?)+(,)/i'), '', @file_get_contents('http://service.mazanex.com/lastsecond.json')));

//@file_put_contents('json/d.json',json_encode(array_diff_key(json_decode(@file_get_contents('http://service.mazanex.com/lastsecond.json'),true),array('ad'=>1,'earnings'=>1,'pg_countdown'=>1,'notice'=>1,'adt'=>1,'atm'=>1))));
