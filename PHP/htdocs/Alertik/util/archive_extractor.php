<?php
ini_set('max_execution_time','100000'); 
include "../private/db.php";
include "../private/backend.php";
$debug=false;
db_zone();

rate_archive();

function rate_archive(){
	mysql_select_db('exchange_archive');
	//mysql_select_db('exchange');
	$rs=db_query('SELECT sell,currency_id,rate_type,date(addedon) as addedon_date FROM exch_rate WHERE duration_type = 4 ORDER BY addedon');	
	while($row=db_array($rs)){
		if ($prev && $prev!=$row['addedon_date']){
			mysql_select_db('exchange');
			db_query('REPLACE INTO rate_archive (archive_date,archive_data) VALUES ("'.$row['addedon_date'].'",\''.json_encode($data).'\');');
			echo $row['addedon_date'].'<br />';flush();
			unset($data);
		}
		$prev=$row['addedon_date'];
		$data[$row['rate_type'].'_'.$row['currency_id']]=$row['sell'];
	}
}
