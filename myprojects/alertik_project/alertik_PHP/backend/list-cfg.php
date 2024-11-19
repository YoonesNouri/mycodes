<?php 
include 'libs.php';

$i=cfg_list($_GET['search']);

echo theme(array(1=>'cfg',0=>'head',10=>'foot'),$i,'theme/backend');

function cfg_list($search){
	$item_per_page=20;
	$page=($_GET['page'])?($_GET['page']-1):0;

	if ($search) $search='WHERE name like "%'.$search;	
	$rs=db_query('SELECT * FROM cfg '.$search
		.' ORDER BY name LIMIT '.($page*$item_per_page).', '.($item_per_page+1).';');
	while($row=db_array($rs)){
		if (++$count==$item_per_page+1) break;
		$out['items'][]=array('name'=>$row['name'],'data'=>substr($row['value'],0,60));		
	}
	if ($count>$item_per_page) $out['page_next']=(int)$page+2;
	if ($page>0) $out['page_prev']=(int)$page;
	return $out;
}
