<?php
include 'settings.php';
function last_update($key,$set=false) {
	$now=db_array(db_query('SELECT now() as _now'));
	if ($set) {
		set_cfg('lu_'.$key,$now['_now']);
		return;
	}
	$last=get_cfg('lu_'.$key);
	if (!$last) return '0000-00-00 00:00:00'; else return $last;
}
function get_benchmark_min_max($properties){
	global $_min_max_cache;
	if (is_array($_min_max_cache)){
		return $_min_max_cache;
	}
	foreach ($properties as $property_id) {
		$out[$property_id]=get_property_min_max($property_id);
	}
	$_min_max_cache=$out;
	return $out;
}
function get_property_min_max($property_id){
	if ($points=get_property($property_id)['points']){
		$vals=array_keys($points);
		asort($vals);
		$min=$vals[0];
		$max=$vals[count($vals)-1];
	}
	return ['min'=>$min,'max'=>$max];
}
function get_backend_cache($type_id,$item_id,$string){
	$rs=db_query("SELECT cached FROM backend_cache WHERE type_id = $type_id AND item_id = $item_id AND md5 = '".md5($string)."'");
	if ($row=db_array($rs)) return json_decode($row['cached'],true); else return false;
}
function set_backend_cache($type_id,$column_id,$string,$content){
	db_query("REPLACE INTO backend_cache (type_id,item_id,md5,cached) VALUES($type_id,$column_id,'".md5($string)."','".db_safe(json_encode($content))."');");
}
function preload_entities(){
	global $_cached_entities;
	/* 	Cached before 	*/
	if (count($_cached_entities)>1000) return;
	$rs=db_query('SELECT id,body,disabled FROM entities');
	while ($row=db_array($rs)) {
		$_cached_entities[$row['id']]=json_decode($row['body'],true);
		$_cached_entities[$row['id']]['_disabled']=$row['disabled'];
	}
}
function remove_entity_cache($entity_id){
	global $_cached_entities;
	unset($_cached_entities[$entity_id]);
}
function get_entity($entity_id,$property_id=0){
	global $_cached_entities,$cache_disable;
	if (!$entity_id) return array();

	if (is_array($_cached_entities[$entity_id])) {
		/*		Serve from cache		*/
		if (!$property_id) return $_cached_entities[$entity_id];
		$entity=$_cached_entities[$entity_id];
	}
	if (!$entity) {
		/*		Serve from database		*/
		$rs=db_query("SELECT body,disabled FROM entities WHERE id = $entity_id");
		while ($row=db_array($rs)) {
			$entity=json_decode($row['body'],true);
			$entity['_disabled']=$row['disabled'];
			if (!$cache_disable){
				$_cached_entities[$entity_id]=$entity;
			}
			if (!$property_id) return $entity;
		}
	}
	$values=array();
	if (!is_array($entity['values'])) $entity['values']=array();
	foreach ($entity['values'] as $value){
		if ($value['property_id']==$property_id) $values[]=$value;
	}
	return $values;
}
function set_entity($entity_id,$array,$skip_versioning=false){
	global $_cached_entities;
	/*		Cache Properties		*/
	$disabled=$array['_disabled'];
	if (!$disabled) $disabled='N';

	$array['values']=array_values($array['values']);

	db_query('REPLACE INTO entities ('.(($entity_id)?'id,':'').'body,disabled) values
		('.(($entity_id)?$entity_id.",'":"'").db_safe(json_encode($array))."','".$disabled."');");

	/*		New ID		*/
	if (!$entity_id) $entity_id=db_last_id();
	/*		Cache update	*/
	//$_cached_entities[$entity_id]=$array;
	unset($_cached_entities[$entity_id]);

	if (!$skip_versioning) add_version($entity_id,2,json_encode(get_entity($entity_id)));

	return $entity_id;
}
function add_value_to_entity($entity_id,$property_id,$value_id){
	$entity=get_entity($entity_id);
	$entity['values'][]=['property_id'=>$property_id,'value_id'=>$value_id];
	set_entity($entity_id,$entity);
}
function replace_entity_property($entity_id,$property){
	$property_id=$property['property_id'];
	$entity=get_entity($entity_id);
	foreach ($entity['values'] as $index => $value) {
		if ($value['property_id']==$property_id) unset($entity['values'][$index]);
	}
	$entity['values'][]=$property;
	set_entity($entity_id,$entity);
}
function add_version($id,$type,$data){
	global $current_user;
	if (!$user_id=$current_user['id']) $user_id=0;
	if (isset($data['rendered'])) unset($data['rendered']);
	db_query('REPLACE INTO versioning(id,id_type,addedon,body,user_id) values
		("'.$id.'",'.$type.',NOW(),"'.db_safe($data).'",'.$user_id.');');
}
function preload_properties(){
	global $_cached_properties;
	static $preloaded;
	if ($preloaded) return;
	$preloaded=true;
	$rs=db_query('SELECT id,body FROM properties');
	while ($row=db_array($rs)) {
		$_cached_properties[$row['id']]=json_decode($row['body'],true);
	}
}
function get_property($property_id,$value_id=0){
	global $_cached_properties;
	if (!$property_id) return [];

	if (is_array($_cached_properties[$property_id])) {
		/*		Serve from cache		*/
		if (!$value_id) return $_cached_properties[$property_id];
		$property=$_cached_properties[$property_id];
	} else {
		/*		Serve from database		*/
		$rs=db_query("SELECT body FROM properties WHERE id = $property_id");
		while ($row=db_array($rs)) {
			$_cached_properties[$property_id]=json_decode($row['body'],true);
			if (!$value_id) return $_cached_properties[$property_id];
			$property=$_cached_properties[$property_id];
		}
	}
	if (is_array($property['values'])) foreach ($property['values'] as $value){
		if ($value['value_id']==$value_id) return $value;
	}
}
function set_property($property_id,$array,$skip_versioning=false){
	global $_cached_properties;

	if (is_array($array['values'])){
		$array['values']=array_values($array['values']);
	}
	/*		Cache Properties		*/
	db_query('REPLACE INTO properties ('.(($property_id)?'id,':'').'body) values ('.(($property_id)?$property_id.",'":"'").db_safe(json_encode($array))."');");

	/*		New ID		*/
	if (!$property_id) $property_id=db_last_id();

	/*		Cache update	*/
	$_cached_properties[$property_id]=$array;

	if (!$skip_versioning) if (!$skip_versioning) add_version($property_id,1,json_encode(get_property($property_id)));

	return $property_id;
}
function get_entity_name($entity_id,$separated=false){
	$entity_name=get_entity($entity_id,1);
	$brand_name=get_brand_name($entity_name[0]['value_id']);
	if ($separated) return [$brand_name,$entity_name[0]['model']];
	return $brand_name.' '.$entity_name[0]['model'];
}
function get_brand_name($brand_id){
	return get_property(1,$brand_id)['content'];
}
function get_unique_entity_name($entity_id){
	if ($name=get_group_name($entity_id)) return $name;
	return get_entity_name($entity_id);
}
function get_group_name($entity_id){
	return db_row('SELECT group_name FROM resource_share WHERE entity_id = '.$entity_id)['group_name'];
}
function get_property_name($property_id){
	$property=get_property($property_id);
	return $property['name'];
}
function get_property_value_id($property_id,$target_value,$search_string=false){
	$property=get_property($property_id);
	if (is_array($property['values'])) foreach ($property['values'] as $value){
		if ($value['content']==$target_value) return $value['value_id'];
		if ($search_string&&stristr($target_value, $value['content'])) return $value['value_id'];
	}
}
function content_location($property_id){
	$property=get_property($property_id);
	if ($property['key_settings']['content']['location']=='entity') return 'entity';
	return 'property';
}
function cal_min_max($id){
	$property=get_property($id);
	if (!$property['settings']['min_max']) return;
	if ($property['key_settings']['content']['location']=='entity'){
		/*		raw_value is on entity table		*/
		$rs=db_query('SELECT id FROM entities WHERE disabled="N"');
		while ($row=db_array($rs)){
			$values=get_entity($row['id'],$id);
			if (is_array($values)) foreach ($values as $value){
				if (!$min||($min>$value['content']&&$value['content'])) $min=$value['content'];
				if ($max<$value['content']) $max=$value['content'];
			}
		}
	} else {
		/*		raw_value is on property table		*/
		if (is_array($property['values'])) foreach ($property['values'] as $value){
			if (!$min||($min>$value['content']&&$value['content'])) $min=$value['content'];
			if ($max<$value['content']) $max=$value['content'];
		}
	}
	$property['settings']['min']=$min;
	$property['settings']['max']=$max;
	set_property($id,$property);
}
function cal_min_max_all(){
	$rs=db_query('SELECT id FROM properties');
	while ($row=db_array($rs)){
		$property=get_property($row['id']);
		if ($property['settings']['min_max']) cal_min_max($row['id']);
	}
}
function string_math($input){
	$parts = preg_split('/ *([\^+\-\/*]) */',$input,-1,PREG_SPLIT_DELIM_CAPTURE);
	$out=0;
	foreach ($parts as $key=>$part){
		if ($key%2) {
			$index = $key;
			continue;
		}
		elseif ($index=='-') $out=$out-$part;
		elseif ($index=='*') $out=$out*$part;
		elseif ($index=='/') $out=$out/$part;
		elseif ($index=='^') $out=pow($out,$part);
		elseif (is_numeric($part)) $out=$out+$part;
	}
	return $out;
}
function auto_calculation($formula,$content=''){
	return (int)string_math(str_replace('[content]',$content,$formula));
}
function add_to_properties($property_id,$content){
	$property=get_property($property_id);
	/*		Find first empty space		*/
	if (is_array($property['values'])){
		foreach ($property['values'] as $arr){
			$find_empty[$arr['value_id']]=true;
		}
		if (count($find_empty)>1) ksort($find_empty);
		foreach ($find_empty as $key => $tmp){
			$value_id++;
			if ($key!=$value_id) {
				$empty_found=true;
				break;
			}
		}
	}
	/*		All capacity is full		*/
	if (!$empty_found) $value_id++;
	if ($property['settings']['auto_calculation']){
		$formula=auto_calculation($property['settings']['auto_calculation'],$content);
		$property['values'][]=array('content'=>$content,'value_id'=>$value_id,'raw_value'=>$formula);
	}else{
		$property['values'][]=array('content'=>$content,'value_id'=>$value_id);
	}
	notice('New property '.$content.' has been added.','notice');
	$property=set_property($property_id,$property);
	cal_min_max($property_id);
	return $value_id;
}
function diffable($array){
	if (!is_array($array)) return array();
	foreach ($array as $arr){
		$position=$count[$arr['property_id'].'-'.$arr['value_id']]++;
		$new_array[$arr['property_id']][$arr['value_id']*1000+$position]=$arr;
	}
	/*		Sort based on property_id 		*/
	ksort($new_array);
	foreach ($new_array as $array){
		/*		Sort based on value_id 		*/
		ksort($array);
		foreach ($array as $arr){
			/*		Sort based on content name 		*/
			ksort($arr);
			$final_array[]=$arr;
		}
	}
	return $final_array;
}
function notice($msg='',$type='success'){
	static $_notice_message;
	if ($msg) $_notice_message.='<div class="alert alert-'.$type.'">'.$msg.'</div>';
	return $_notice_message;
}
function entity_name_update($id){
	$entity=get_entity($id);
	$entity_name=get_entity($id,1);
	$entity['entity_name']=$entity['name']=get_brand_name($entity_name[0]['value_id']).' '.$entity_name[0]['model'];
	set_entity($id,$entity);
}
function closest_entity($string,$limit=0){
	global $_closest_entites;
	$matches=_closest_entity_data($string);
	if (!is_array($matches)) return [];
	foreach($matches as $key=>$tmp){
		list($id,$name)=explode('-',$key,2);
		if (!$limit) return [$id,$name];

		if (++$count>$limit) break;
		$out[]=[$id,$name];
	}
	return $out;
}
function _closest_entity_data($string){
	static $entities;
	$spliters=['.','_','?','!','؟',':','(',')',',','،',' ','-'];
	if (!is_array($entities)){
		$rs=db_query('SELECT id FROM entities');
		while ($row=db_array($rs)){
			// TODO: Select multi names
			$name=get_entity_name($row['id']);
			$sentences=preg_split('/([_'.implode('',$spliters).']{1})/u',strtolower($name),null,PREG_SPLIT_NO_EMPTY);
			$entities[]=[$row['id'],$sentences,$name];
		}
	}
	$parts=preg_split('/([_'.implode('',$spliters).']{1})/u',strtolower($string),null,PREG_SPLIT_NO_EMPTY);
	foreach ($entities as $entity){
		$matched=count(array_intersect($entity[1], $parts));
		$key=$entity[0].'-'.$entity[2];
		if ($matched>$matches[$key])$matches[$key]=$matched;
	}
	if (is_array($matches)) arsort($matches);
	return $matches;
}
/*
	$country_variables[string_key][country_key]=string
*/
function country_html_write($filename,$theme_files,$variables,$country_variables=''){
	global $country_conf;
	foreach ($country_conf as $country){
		/* Extract country specific variables */
		if (is_array($country_variables)) foreach($country_variables as $key=>$value){
			if (isset($value[$country['country']])) $variables[$key]=$value[$country['country']];
		}
		set_locale($country['iso'],'../'.CONF_TYPE.'/translations/');
		$variables['rtl']=$country['rtl'];
		$variables['country']=$country;
		html_write($country['file_folder'].'/'.$filename,theme($theme_files,$variables
			,'theme/'.$country['theme_folder'],'theme/'.$country['default_theme_folder']));
	}
}
function entities_popularity(){
	/* Collect entity hits for last 60 days */
	static $_popularity;
	if (is_array($_popularity)) return $_popularity;
	$rs=db_query('SELECT * FROM resource_share WHERE group_id>0;');
	while($row=db_array($rs)){
		$links[$row['entity_id']]=$row['group_id'];
	}
	$rs=db_query('SELECT entity_id,sum(hits) as hits FROM log_entity
		WHERE TIMESTAMPDIFF(DAY,addedon,now())<60 GROUP BY entity_id;');
	while($row=db_array($rs)){
		/* Using logaritmic value */
		$_popularity[$row['entity_id']]=log($row['hits']);
		if ($group_id=$links[$row['entity_id']]) $group_hits[$group_id]+=$row['hits'];
	}
	if(is_array($links)) foreach ($links as $entity_id => $group_id) {
		$_popularity[$entity_id]=log($group_hits[$group_id]);
	}
	if(is_array($_popularity)) arsort($_popularity);
	return $_popularity;
}
function group_entity_id($id){
	static $cache;
	if (!$cache){
		$rs=db_query('SELECT entity_id,group_id FROM resource_share');
		while($row=db_array($rs)){
			$cache['entities'][$row['entity_id']]=$row['group_id'];
			$cache['groups'][$row['group_id']]=$row['entity_id'];
		}
	}
	if ($lead=$cache['groups'][$cache['entities'][$id]]) return $lead;
	return $id;
}
function translation_property($country_code,$property_id,$content){
	static $cache;
	if (!$country_code) return $content;

	if (!$cache[$country_code]) $cache[$country_code]=get_json('translation_'.$country_code);
	$trans=$cache[$country_code][$property_id];
	// For variety and similar ones run whole translation
	if ($trans=='all') return translate_all($content,$cache[$country_code]);

	if (!is_array($trans)) return $content;

	$content=translate($content,$trans);
	return $content;
}
function translation_content($property_id,$content,$translate_table){
	$trans=$translate_table[$property_id];
	// For variety and similar ones run whole translation
	if ($trans=='all') return translate_all($content,$translate_table);

	if (!is_array($trans)) return $content;

	$content=translate($content,$trans);
	return $content;
}
function translate_all($content,$trans){
	foreach ($trans as $propert_strs) {
		$content=translate($content,$propert_strs);
	}
	return $content;
}
function translate($content,$strs){
	if(is_array($strs)) foreach ($strs as $t){
		$content=str_ireplace($t, _($t), $content);
	}
	return $content;
}
function bechmark_block(){
	if (!get_lock('','benchmark')) exit(notice('Still benchmarking...','error'));
}
function select_entities($property_id,$value_id=0,$enable=false){
	if (!$property_id) return [];
	$results=[];
	if ($value_id){
		$rs=db_query('SELECT id FROM entities WHERE (body LIKE \'%"property_id":'.$property_id.',"value_id":'.$value_id.',%\'
			OR body LIKE \'%"property_id":'.$property_id.',"value_id":'.$value_id.'}%\')'.($enable?' AND disabled="N"':''));
	} else {
		$rs=db_query('SELECT id FROM entities WHERE (body LIKE \'%"property_id":'.$property_id.',%\' OR body LIKE \'%"property_id":'.$property_id.'}%\')'.($enable?' AND disabled="N"':''));
	}
	while($row=db_array($rs)){
		$results[]=$row['id'];
	}
	return $results;
}
function get_brand_id($entity_id){
	static $brands;
	if (!$brands){
		$rs=db_query('SELECT id,body FROM entities');
		while($row=db_array($rs)){
			preg_match('/"property_id":1,"value_id":([\d]+)[,}]+|"value_id":([\d]+),"property_id":1[,}]+/', $row['body'], $matches);
			if ($matches[1]) $brands[$row['id']]=$matches[1];
		}
	}
	return $brands[$entity_id];
}
function item_image_url($id){
	return MEDIA_DOMAIN.floor($id/100).'/'.$id.'.png';
}
function search_check($id,$name,$term){
	if (!$term) return true;
	if ((int)$term>0){
		if ($id==$term) return true;
		return false;
	}
	$terms=explode(' ',$term);
	foreach ($terms as $term){
		if (!stristr($name,$term)){
			return false;
		}
	}
	return true;
}

function update_name_table(){
	$last_update=last_update('entities_search');
	$names=get_json('entities_name');
	$rs=db_query('SELECT id,body FROM entities WHERE updated >="'.$last_update.'"');
	$new=false;
	while($row=db_array($rs)){
		$i=json_decode($row['body'],true);
		$names[$row['id']]=($i['entity_name']?$i['entity_name']:$i['name']);
		$new=true;
	}
	if ($new){
		last_update('entities_search',true);
		krsort($names);
		set_json('entities_name',$names);
	}
}

function tag_entity_for_update($entity_id){
	db_query('UPDATE entities SET updated=NOW() WHERE id = '.$entity_id);
}
