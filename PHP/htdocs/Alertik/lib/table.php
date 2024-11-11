<?php
/* 	v0.01 		*/
function sql_table($sql,$options){

}
function html_table($captions,$data,$options=[]){
	$out='<table'.(($options['class'])?' class="'.$options['class'].'"':'').'>';
		$out.='<tr>';
		foreach ($captions as $caption_id=>$caption){
			$out.='<th>'.$caption['name'].'</th>';
		}
		$out.='</tr>';
		if (is_array($data)) foreach ($data as $row){
			$out.='<tr'.($row['options']['class']?' class="'.$row['options']['class'].'"':'').'>';
			foreach ($captions as $caption_id=>$caption){
				$val=$row[$caption_id];
				if (isset($caption['number_format'])) $val=number_format($val,$caption['number_format']);
				$out.='<td>'.$val.'</td>';
			}
			$out.='</tr>';
		}
	return $out.'</table>';
}