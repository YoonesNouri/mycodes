<?php
/*
	v0.03

	To display result
		$content=admin_panel_display($admin_commands);
	To execute command
		if ($_GET['command_panel']) $page['content']=execute_admin_panel($admin_commands,$_GET['command_panel'],$_POST);

*/
function admin_panel_display($commands,$permission){
	foreach ($commands as $command_key=>$command){
		if ($command['permission']!=$permission && $permission) continue;
		if (is_array($command['in'])) {
			$out.='<h2>'.$command['title'].'</h2><form method="post" action="?command_panel='.$command_key.'">';
			foreach ($command['in'] as $input_key => $input_name){
				$out.='<input type="text" name="'.$input_key.'" value="" placeholder="'.$input_name.'">';
			}
			$out.='<input type="submit" name="execute" value="'.$command['submit'].'"></form>';
		} else {
			$out.='<h2><a href="?command_panel='.$command_key.'">'.$command['title'].'</a></h2>';
		}
	}
	return $out;
}
function execute_admin_panel($admin_commands,$command_type,$inputs){
	$command=$admin_commands[$command_type];
	if (!is_array($command)) return;
	$out=$command['out']['head'];

	if ($command['sql']) $out.=execute_sql($command['sql'],$inputs,$command['out']['body']);
	if ($command['func']) $out.=execute_func($command['func'],$inputs,$command['out']['body']);

	return $out.$command['out']['foot'];
}
function execute_func($commands,$inputs,$html){
	if (!is_array($commands)) $funcs[]=$commands; else $funcs=$commands;
	foreach ($funcs as $func) {
		if (function_exists($func)) $out.=$func($inputs);
		$out.=$command['out']['body'];
	}
	return $out;
}
function execute_sql($commands,$inputs,$html){
	if (!is_array($commands)) $sqls[]=$commands; else $sqls=$commands;
	foreach ($sqls as $query) {
		$sql=_admin_replace_data($query,$inputs);
		if (strstr($sql,'<!--')) return;
		$rs=db_query($sql);
		if (is_bool($rs)) {
			$out.=$html;
		} else {
			while($row=db_array($rs)){
				$out.=_admin_replace_data($html,$row);
			}
		}
	}
	return $out;
}
function _admin_replace_data($data,$changes){
	if (!is_array($changes)) return $data;
	foreach ($changes as $key=>$val){
		$data=str_replace('<!--'.$key.'-->',db_safe($val),$data);
	}
	return $data;
}