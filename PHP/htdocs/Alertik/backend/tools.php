<?php
include 'libs.php';
include '../lib/commands-panel.php';

if ($_GET['command_panel']) $content=execute_admin_panel(commands_array(),$_GET['command_panel'],$_POST);
$content.=admin_panel_display(commands_array());
echo theme(array(1=>$content,0=>'head',10=>'foot'),[],$theme_folder);

function commands_array(){
	return [
		'change_news_category'=>[
			'title'=>'Change category','submit'=>'Change',
			'in'=>['news_ids'=>'News ID eg. 1,2,3,4','category_id'=>'Category ID',],
			'out'=>['body'=>'<div class="alert alert-success">Successfully saved!</div>'],
			'sql'=>['UPDATE news SET category_id =<!--category_id-->,similar_id=news_id,duplicate_id=news_id WHERE news_id IN(<!--news_ids-->)'
				,'UPDATE pubs SET category_id =<!--category_id--> WHERE pub_id IN(<!--news_ids-->)'],
			'func'=>'remove_categorizer_blocker'
		],
		'change_pub_category'=>[
			'title'=>'Change category','submit'=>'Change',
			'in'=>['similar_id'=>'Pub ID eg. 1,2,3,4','category_id'=>'Category ID',],
			'out'=>['body'=>'<div class="alert alert-success">Successfully saved!</div>'],
			'sql'=>['UPDATE news SET category_id =<!--category_id--> WHERE similar_id IN(<!--similar_id-->)'
				,'UPDATE pubs SET category_id =<!--category_id--> WHERE pub_id IN(<!--news_ids-->)'],
			'func'=>'remove_categorizer_blocker'
		],
		'resource_versioning'=>[
			'title'=>'Resource (CSS/JS) file versioning','submit'=>'Update',
			'in'=>['css_version'=>'CSS Version','js_version'=>'JS Version'],
			'out'=>['body'=>'<div class="alert alert-success">Successfully saved!</div>'],
			'func'=>['save_versioning'],
		],
		'flag_entity_to_benchmark'=>[
			'title'=>'Flag phones to benchmark','submit'=>'Update',
			'in'=>['ids'=>'Phone IDs eg. 5,10,2003,25'],
			'out'=>['body'=>'<div class="alert alert-success">Successfully has been flagged!</div>'],
			'sql'=>'UPDATE entities SET updated=NOW() WHERE id IN(<!--ids-->)',
		],
		'export_entity'=>[
			'title'=>'Export phone data','submit'=>'Get',
			'in'=>['id'=>'Phone ID'],
			'out'=>['body'=>'<textarea rows="10"><!--body--></textarea>'],
			'sql'=>'SELECT body FROM entities WHERE id = <!--id-->',
		],
		'import_entity'=>[
			'title'=>'Import phone data','submit'=>'Update',
			'in'=>['id'=>'Phone ID','body'=>'Phone Data'],
			'out'=>['body'=>'<div class="alert alert-success">Phone successfully has updated!</div>'],
			'sql'=>'UPDATE entities SET body = \'<!--body-->\' WHERE id = <!--id-->',
		],
		'remove_old_version'=>[
			'title'=>'Remove versioning data older than 100 days','submit'=>'Remove',
			'out'=>['body'=>'<div class="alert alert-success">Successfully has been removed!</div>'],
			'sql'=>'DELETE FROM versioning WHERE addedon < DATE_SUB(CURDATE(),INTERVAL 100 DAY)',
		],
	];
}
function remove_categorizer_blocker(){
	unlock_categorizer($_POST['news_ids']);
}
function save_versioning(){
	set_json('rc_version',['js_version'=>$_POST['js_version'],'css_version'=>$_POST['css_version']]);
}
