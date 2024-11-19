<?php
chdir(dirname(__FILE__));
ini_set('memory_limit','100M');
ini_set('max_execution_time','100'); 
ini_set('request_terminate_timeout','100'); 
include 'libs.php';
include 'blacklists.php';
include 'vars.php';
include 'lib-similarity.php';
include '../lib/f.leech.php';
include '../lib/f.parse.php';
include '../lib/leech-manage.php';
include '../lib/linguistic.php';
include '../lib/performance.php';

if (!get_lock(200)) exit(notice('Leeching has been terminated, another instance of this script still running...','error'));
performance::track('start');
run_leechers($_GET['monitor_id']);

if ($_GET['monitor_id']) exit('<!--a--><!-- raw editor --><pre>'.notice($leech_counter.' added').'</pre><!-- raw editor --><!--a-->');
if ($debug) echo notice(performance::output());
function process_results($request,$records){
	global $leech_counter,$categories;
	if (!is_array($records)) return;
	if ($request['prevent_old']) $records=prevent_old_records($request['monitor_id'],$request['prevent_old'],$records);
	$records=remove_duplicate_links($records,$request);
	foreach($records as $record){		
		$record['desc']=prepare_desc($record['desc']);
		$record['title']=prepare_title($record['title']);
		if (strlen($record['title'])<10) continue;
		$title_words=matchable_title($record['title']);
		list($category_id)=find_category($title_words,$request['category_id']);
		$leech_counter++;
		if ($category_id!=$request['category_id']) $record['org_cat']=$request['category_id'];
		db_query('INSERT INTO news (source_id,category_id,title_words,last_update,addedon,content)
			VALUES('.$request['monitor_id'].','.$category_id.',"'.$title_words.'",NOW(),NOW(),"'.db_json_encode($record).'")');
		$ids[]=$last_id=db_last_id();
		db_query('INSERT INTO news_md5 (news_id,url_md5) VALUES('.$last_id.',"'.url_md5($record['link']).'")');		
		if ($category_id!=$request['category_id']) {
			if ($request['category_id']) echo $categories[$request['category_id']]['name'].' => '.$categories[$category_id]['name']
				.' <a href="/moderate.php?i='.$last_id.'" class="blank" target="_blank">'.$record['title'].'</a><br>';
		}
	}
	if (is_array($ids)) db_query('UPDATE news SET duplicate_id=news_id,similar_id=news_id WHERE news_id IN('.implode(',',$ids).')');
	if ($_GET['monitor_id']) {
		print_r($records);
		print_r($request);
	}	 
}
function prevent_old_records($monitor_id,$prevent,$records){
	/* 	Setup lock on first run   	*/
	if (!is_array($prevent)){	
		$row=db_row('SELECT rules FROM leech_monitor WHERE monitor_id='.$monitor_id);
		$rules=json_decode($row['rules'],true);
		$data['stop_on']=time()+$prevent*60*60*24;
		foreach ($records as $index => $record) {
			$data['records'][]=$record['link'];
		}
		$rules['prevent_old']=$data;
		db_query("UPDATE leech_monitor SET rules='".db_json_encode($rules)."' WHERE monitor_id = ".$monitor_id);
		return [];
	}
	/* 	Remove lock 		  	*/
	if ($prevent['stop_on']<time()){
		$row=db_row('SELECT rules FROM leech_monitor WHERE monitor_id='.$monitor_id);
		$rules=json_decode($row['rules'],true);
		unset($rules['prevent_old']);
		db_query("UPDATE leech_monitor SET rules='".db_json_encode($rules)."' WHERE monitor_id = ".$monitor_id);
		return $records;
	}
	/*	Remove matching records 	*/
	foreach ($records as $index => $record) {
		if (in_array($record['link'], $prevent['records'])) unset($records[$index]);
	}
	return $records;
}
function remove_duplicate_links($records,$request){
	foreach($records as $key=>$record){
		if (blacklist($record,$request)) {
			unset($records[$key]);
			continue;
		}
		$duplicate_detect[url_md5($record['link'])][]=$key;
		$links[]=url_md5($record['link']);
	}
	if (count($links)==0) return [];
	$rs=db_query('SELECT url_md5 FROM news_md5 WHERE url_md5 IN("'.implode('","',$links).'")');
	while($row=db_array($rs)){
		if (isset($duplicate_detect[$row['url_md5']])){
			foreach ($duplicate_detect[$row['url_md5']] as $key => $value) {
				unset($records[$value]);
			}
		}
	}
	return $records;
}