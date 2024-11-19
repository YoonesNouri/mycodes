<?php
/*
	v0.02
	Instruction Format
	name
	type
		for custom processor
	url
		array or var to process
	paths:[name:xpath,...
	url-generator url+<<<counter>>>,from, to
	url-get url,path,postfix
	parse-type:rss (predefined paths)
	parse-function:
 */
function run_leechers($monitor_id){
	if ($monitor_id){
		$rs=db_query('SELECT * FROM leech_monitor WHERE monitor_id='.$monitor_id);
	} else {
		$rs=db_query('SELECT * FROM leech_monitor WHERE next_run<>"0000-00-00 00:00:00" AND next_run<NOW()');
	}
	while ($row=db_array($rs)){
		unset($row['result']);
		$row=array_merge($row,json_decode($row['rules'],true));
		if ($row['result']) {
			$tmp=json_decode($row['result'],true);
			$row['data-md5']=$tmp['response']['data-md5'];
		}
		$url=_url_creator($url,$row);
	}
	db_query('begin');
	leech_urls($url,['max_connetion'=>20]);
	if ($_GET['monitor_id']) print_r($url);
	db_query('commit');
}
function _url_creator($url_list,$row){
	$default=['timeout'=>5,'data'=>$row,'callback'=>'_manager_leech_finish'];
	if (is_array($row['url-get'])){
		$response=leech_url(['url'=>$row['url-get']['url']]);
		$urls=xparse($response['content'],['url'=>$row['url-get']['path']]);
		if (is_array($urls)) foreach ($urls as $url) {
			$default['url']=($url['url'].$row['url-get']['postfix']);
			$url_list[]=$default;
		}
	} elseif (is_array($row['url-generator'])){
		for($i=$row['url-generator']['from'];$i<$row['url-generator']['to'];$i++){
			$default['url']=str_replace('<<<counter>>>',$i,$row['url-generator']['url']);
			$url_list[]=$default;
		}
	} elseif($row['url']) {
		if (is_array($row['url'])) $urls=$row['url']; else $urls[]=$row['url'];
		foreach($urls as $url){
			$default['url']=$url;
			$url_list[]=$default;
		}
	}
	return $url_list;
}
function _manager_leech_finish($request,$response){
	static $all_records;
	$all_required=true;

	/* Check if md5 is same skip if requested */
	$response['data-md5']=md5($response['content']);
	if ($response['data-md5']==$request['data']['data-md5']) return;

	/* Predefined formats */
	if ($request['data']['parse-type']=='rss') {
		$request['data']['paths']=['title'=>'/rss/channel/item/title','link'=>'/rss/channel/item/link','desc'=>'/rss/channel/item/description'];
		$all_required=false;
	}

	/* Parse data */
	if (is_array($request['data']['paths'])) $records=xparse_group($response['content'],$request['data']['paths'],$all_required); else $records=[];

	/* Parse custom function */
	if ($request['data']['parse-function']) {
		if (function_exists($request['data']['parse-function'])){
			$records=$request['data']['parse-function']($response['content']);
		}
	}

	/* Report problem if all of this monitors leeching failed */
	if (is_array($all_records[$request['data']['monitor_id']])&&is_array($records)) {
		$all_records[$request['data']['monitor_id']]=$records=array_merge($all_records[$request['data']['monitor_id']],$records);
		unset($response['content']);
	} elseif(is_array($records)){
		$all_records[$request['data']['monitor_id']]=$records;
		unset($response['content']);
	} elseif (is_array($all_records[$request['data']['monitor_id']])){
		$records=$all_records[$request['data']['monitor_id']];
		unset($response['content']);
	} else{
		$records=false;
	}

	/* Record results */
	if ($request['data']['interval']){
		$next_run=' , next_run = DATE_ADD(NOW(),INTERVAL '.$request['data']['interval'].')';
	}

	db_query("UPDATE leech_monitor
		SET result='".db_json_encode(['response'=>$response])."'".$next_run." WHERE monitor_id=".$request['data']['monitor_id']);

	/* General function to record Changes based on type of the leech */
	if (function_exists('process_results')) process_results($request['data'],$records);
}
function source_info($monitor_id=0){
	static $cache;
	if (is_array($cache[$monitor_id])) return $cache[$monitor_id];
	$rs=db_query('SELECT monitor_id,rules FROM leech_monitor');
	while ($row=db_array($rs)){
		$cache[$row['monitor_id']]=json_decode($row['rules'],true);
	}
	if ($monitor_id) return $cache[$monitor_id];
	return $cache;
}
function monitors_list($search){
	$item_per_page=50;
	$page=($_GET['page'])?($_GET['page']-1):0;

	if ($search) $search='WHERE rules like "%'.$search.'%" OR monitor_id = "'.$search.'"';
	$rs=db_query('SELECT *,
		TIME_FORMAT(SEC_TO_TIME(TIMESTAMPDIFF(SECOND,lastupdate,now())),"%Hh %im") as hours,
		TIME_FORMAT(SEC_TO_TIME(TIMESTAMPDIFF(SECOND,now(),next_run)),"%Hh %im") as next_run

		FROM leech_monitor '.$search
		.' ORDER BY monitor_id DESC LIMIT '.($page*$item_per_page).', '.($item_per_page+1).';');
	while($row=db_array($rs)){
		if (++$count==$item_per_page+1) break;
		$rules=json_decode($row['rules'],true);
		$result=json_decode($row['result'],true);
		if (!$row['next_run']) $row['next_run']='Manual';
		$out['items'][]=array('id'=>$row['monitor_id'],'name'=>'<sup>'.$rules['type'].'</sup> '.$rules['name'],'next_run'=>$row['next_run'],'result'=>$result,
			'since'=>$row['hours'],'disabled'=>(($row['disabled']!='N')?1:''));
	}
	if ($count>$item_per_page) $out['page_next']=(int)$page+2;
	if ($page>0) $out['page_prev']=(int)$page;
	return $out;
}
function edit_leecher(){
	if (!$_GET['id'])$_GET['id']=0;
	if ($rs=db_query('SELECT * FROM leech_monitor WHERE monitor_id = '.$_GET['id'])) $row=db_array($rs);
	$out['item']=json_encode(json_decode($row['rules'],true),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	$out['result']=json_encode(json_decode($row['result'],true),JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	$out['id']=$_GET['id'];
	$out['action']='raw-edit-entities.php?id='.$_GET['id'].(($_GET['clone'])?'&clone=1':'');
	$out['notice']=notice();
	if ($_GET['clone']){
		$out['type']='Clone entity no.'.$_GET['id'];
		$out['id']=0;
	} elseif ($_GET['id']){
		$out['type']='Edit entity no.'.$_GET['id'];
	} else {
		$out['type']='Adding a new entity';
	}
	return $out;
}
function save_leecher(){
	if ($_POST['raw_id']) $_GET['id']=$_POST['raw_id'];
	if (!$_POST['raw_content']) return;
	if ($_GET['clone']){
		notice('Clone saved successfully! You are cloning again. ('.time().')');
	}elseif ($_GET['id']){
		notice('Saved successfully! ('.time().')');
	}else{
		notice('Added successfully! ('.time().')');
	}
	if ($_GET['clone']) $id=0; else $id=$_GET['id'];
	$rules=db_safe($_POST['raw_content']);
	$disabled=$_POST['disabled'];
	if (!$id) {
		db_query("INSERT INTO leech_monitor (rules,disabled) VALUES (
			'$rules','$disabled')");
	} else {
		db_query("REPLACE INTO leech_monitor (monitor_id,rules,disabled) VALUES (
			$id,'$rules','$disabled')");
	}
}
/*
	source_monitor_statistics(
		['news'=>
			['query'=>'SELECT source_id as provider_id,count(*) as number FROM backlog WHERE addedon > DATE_SUB(NOW(),INTERVAL [day] DAY) GROUP BY source_id',
			'replace'=>['day',30,1]],
		]);
*/
function source_monitor_statistics($monitors){
	foreach ($monitors as $type => $monitor){
		$rs=db_query(str_replace('['.$monitor['replace'][0].']', $monitor['replace'][1], $monitor['query']));
		while($row=db_array($rs)){
			$list[$row['provider_id']]=$row['number'];
		}
		$rs=db_query(str_replace('['.$monitor['replace'][0].']', $monitor['replace'][2], $monitor['query']));
		while($row=db_array($rs)){
			unset($list[$row['provider_id']]);
		}
		if (is_array($list)) foreach ($list as $provider_id => $value) {
			backend_notice('Provider ID '.$provider_id.' '.$type.' number is down from '.round($value/30),'error');
		}
	}
}
