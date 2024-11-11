<?php
function get_promoted_news_list($limit){
	return _get_news_list('SELECT *,TIMESTAMPDIFF(MINUTE,addedon,NOW()) as mins_passed
		FROM khabarist.news WHERE status > 0 AND duplicate_id = news_id AND category_id <> 2 AND promoted=2
		ORDER BY news_id DESC LIMIT '.$limit.';',$limit);
}
function get_news_list($limit){
	return _get_news_list('SELECT *,TIMESTAMPDIFF(MINUTE,addedon,NOW()) as mins_passed
		FROM khabarist.news WHERE status > 0 AND duplicate_id = news_id AND category_id = 2
		ORDER BY news_id DESC LIMIT '.$limit.';',$limit);
}
function _get_news_list($query,$limit){
	$rs=db_query($query);
	while ($row=db_array($rs)){
		$row['content']=json_decoder($row['content'],true);
		$score=$row['positive']-$row['negative'];
		if ($score<0) $score=0;
		list(,$url)=explode('//',$row['content']['link']);
		$title=smart_title_cut($row['content']['title'],140);
		$url='http://www.khabarist.com/e/'.$row['news_id'];
		$news[]=['news_id'=>$row['news_id'],'source'=>source_name($row['source_id']),'time_txt'=>time_passed($row['mins_passed'])
			,'time'=>$row['mins_passed'],'title'=>$title,'link'=>$row['content']['link'],
			'url'=>$url,'score'=>$score,'last'=>((++$count==$limit)?1:0)];
	}
	return $news;
}
function json_decoder($data,$is_array=true){
	if ($data[0]!="{"&&strlen($data)>20){
		$data=gzuncompress(base64_decode($data));
	}
	return json_decode($data,$is_array);
}
function source_name($source_id){
	$source=source_info($source_id);
	return $source['name-fa'];
}
function source_info($monitor_id=0){
	static $cache;
	if (is_array($cache[$monitor_id])) return $cache[$monitor_id];
	$rs=db_query('SELECT monitor_id,rules FROM khabarist.leech_monitor');
	while ($row=db_array($rs)){
		$cache[$row['monitor_id']]=json_decode($row['rules'],true);
	}
	if ($monitor_id) return $cache[$monitor_id];
	return $cache;
}
function time_passed($mins=0){
	if (floor($mins/1440)>0){
		return floor($mins/1440).' روز قبل';
	} elseif (floor($mins/60)>0){
		return floor($mins/60).' ساعت قبل';
	} elseif ($mins>0){
		return $mins.' دقیقه قبل';
	}
	return 'لحظاتی قبل';
}

function smart_title_cut($str,$limit=120){
	$str=cut_long_title($str);
	if (strlen($str)<$limit) return $str;
	$words=explode(' ',$str);
	foreach ($words as $word) {
		$title.=(($title)?' ':'').$word;
		if (strlen($title)>$limit) return $title.(($title==$str)?'':' ...');
	}
	return $title;
}
function cut_long_title($str){
	if (strlen($str)<80) return $str;
	$parts=explode('/',$str);
	if (count($parts)==0) $parts=explode(';',$str);
	if (count($parts)==0) $parts=explode('؛',$str);
	if (strlen($parts[1])>strlen($parts[0]) &&strlen($parts[1])>30) return $parts[1];
	if (strlen($parts[0])>30) return $parts[0];
	return $str;
}