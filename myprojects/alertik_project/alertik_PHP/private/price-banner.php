<?php
include "db.php";
include "backend.php";
$count=getCFG("fetch:ct");
if (($count%5)) exit;

if ($tmp=getCFG('daily_rates')) {
	$daily=json_decode($tmp,true);
	$yesterday=$daily['prev'];
	$today=$daily['today'];
} else echo 'Nothing to create banner for.';

db_zone();
$tmp=db_array(db_query('SELECT CURDATE(),CURTIME();'));
list($y,$m,$d)=to_jalali($tmp[0]);
$tmp=explode(':',$tmp[1]);

//render_rfjewelry($today,$yesterday,$y.'-'.$m.'-'.$d.'   '.$tmp[0].':'.$tmp[1]);
//render_ciodo($today,$yesterday,$y.'-'.$m.'-'.$d.'   '.$tmp[0].':'.$tmp[1]);
render_warm($today,$yesterday,$y.'-'.$m.'-'.$d.'   '.$tmp[0].':'.$tmp[1]);
render_blue($today,$yesterday,$y.'-'.$m.'-'.$d.'   '.$tmp[0].':'.$tmp[1]);
render_blue_complete($today,$yesterday,$y.'-'.$m.'-'.$d.'   '.$tmp[0].':'.$tmp[1]);
render_blue_compact($today,$yesterday,$y.'-'.$m.'-'.$d.'   '.$tmp[0].':'.$tmp[1]);

function render_warm($today,$yesterday,$date){
	$data[]=array('text'=>'-','x'=>5,'y'=>43,'align'=>'right');
	//$data[]=array('text'=>number_format($today['3:40']['last_sell']*10),'x'=>5,'y'=>43,'align'=>'right');
	$tmp='-';
	//$tmp=change_perc($yesterday['3:40']['last_sell']*10,$today['3:40']['last_sell']*10,0,'img:percentage');
	$data[]=array('text'=>'('.$tmp[0].'%)','x'=>47,'y'=>43,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['3:41']['last_sell']*10),'x'=>122,'y'=>43,'align'=>'right');
	$tmp=change_perc($yesterday['3:41']['last_sell']*10,$today['3:41']['last_sell']*10,0,'img:percentage');
	$data[]=array('text'=>'('.$tmp[0].'%)','x'=>164,'y'=>43,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['2:40']['last_sell']*10),'x'=>240,'y'=>43,'align'=>'right');
	$tmp=change_perc($yesterday['2:40']['last_sell']*10,$today['2:40']['last_sell']*10,0,'img:percentage');
	$data[]=array('text'=>'('.$tmp[0].'%)','x'=>287,'y'=>43,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['3:3']['last_sell']*10000),'x'=>358,'y'=>43,'align'=>'right');
	$tmp=change_perc($yesterday['3:3']['last_sell']*10000,$today['3:3']['last_sell']*10000,0,'img:percentage');
	$data[]=array('text'=>'('.$tmp[0].'%)','x'=>413,'y'=>43,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>$date,'x'=>6,'y'=>43,'color'=>'gray','size'=>10);
	render_banner(array('background'=>'v-warm.png','font'=>'BHoma','format'=>'png','size'=>11,'save'=>'v-warm.png'),$data);
}
function render_blue($today,$yesterday,$date){
	$data[]=array('text'=>number_format($today['3:3']['last_sell']*10000),'x'=>15,'y'=>71,'align'=>'right');
	$tmp=change_perc($yesterday['3:3']['last_sell']*10000,$today['3:3']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>70,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['3:40']['last_sell']*10),'x'=>15,'y'=>120,'align'=>'right');
	$tmp=change_perc($yesterday['3:40']['last_sell']*10,$today['3:40']['last_sell']*10,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>119,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['3:41']['last_sell']*10),'x'=>15,'y'=>170,'align'=>'right');
	$tmp=change_perc($yesterday['3:41']['last_sell']*10,$today['3:41']['last_sell']*10,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>169,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['3:11']['last_sell']*10000),'x'=>15,'y'=>220,'align'=>'right');
	$tmp=change_perc($yesterday['3:11']['last_sell']*10000,$today['3:11']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>220,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>$date,'x'=>0,'y'=>244,'color'=>'gray','size'=>10);
	render_banner(array('background'=>'blue.png','font'=>'BHoma','format'=>'png','size'=>11,'save'=>'p-blue.png'),$data);
}
function render_blue_compact($today,$yesterday,$date){
	$data[]=array('text'=>number_format($today['3:3']['last_sell']*10000),'x'=>15,'y'=>71,'align'=>'right');
	$tmp=change_perc($yesterday['3:3']['last_sell']*10000,$today['3:3']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>65,'y'=>70,'align'=>'right','size'=>9,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>number_format($today['3:40']['last_sell']*10),'x'=>15,'y'=>120,'align'=>'right');
	$tmp=change_perc($yesterday['3:40']['last_sell']*10,$today['3:40']['last_sell']*10,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>65,'y'=>119,'align'=>'right','size'=>9,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>number_format($today['3:41']['last_sell']*10),'x'=>15,'y'=>170,'align'=>'right');
	$tmp=change_perc($yesterday['3:41']['last_sell']*10,$today['3:41']['last_sell']*10,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>65,'y'=>169,'align'=>'right','size'=>9,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>number_format($today['0:1']['last_sell']),'x'=>15,'y'=>220,'align'=>'right');
	$tmp=change_perc($yesterday['0:1']['last_sell'],$today['0:1']['last_sell'],0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>65,'y'=>220,'align'=>'right','size'=>9,'color'=>(($tmp[1]=='pos')?'green':'red'));

	$data[]=array('text'=>number_format($today['3:11']['last_sell']*10000),'x'=>10,'y'=>270,'align'=>'right');
	$tmp=change_perc($yesterday['3:11']['last_sell']*10000,$today['3:11']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>65,'y'=>270,'align'=>'right','size'=>9,'color'=>(($tmp[1]=='pos')?'green':'red'));

	$data[]=array('text'=>number_format($today['3:12']['last_sell']*10000),'x'=>10,'y'=>320,'align'=>'right');
	$tmp=change_perc($yesterday['3:12']['last_sell']*10000,$today['3:12']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>65,'y'=>320,'align'=>'right','size'=>9,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>number_format($today['3:13']['last_sell']*10000),'x'=>10,'y'=>370,'align'=>'right');
	$tmp=change_perc($yesterday['3:13']['last_sell']*10000,$today['3:13']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>65,'y'=>370,'align'=>'right','size'=>9,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>$date,'x'=>2,'y'=>389,'color'=>'gray','size'=>8.6);
	render_banner(array('background'=>'blue-cpt.png','font'=>'BHoma','format'=>'png','size'=>9,'save'=>'cpt-blue.png'),$data);
}
function render_blue_complete($today,$yesterday,$date){
	$data[]=array('text'=>number_format($today['3:3']['last_sell']*10000),'x'=>15,'y'=>71,'align'=>'right');
	$tmp=change_perc($yesterday['3:3']['last_sell']*10000,$today['3:3']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>70,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>number_format($today['3:40']['last_sell']*10),'x'=>15,'y'=>120,'align'=>'right');
	$tmp=change_perc($yesterday['3:40']['last_sell']*10,$today['3:40']['last_sell']*10,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>119,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>number_format($today['3:41']['last_sell']*10),'x'=>15,'y'=>170,'align'=>'right');
	$tmp=change_perc($yesterday['3:41']['last_sell']*10,$today['3:41']['last_sell']*10,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>169,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>number_format($today['3:11']['last_sell']*10000),'x'=>15,'y'=>220,'align'=>'right');
	$tmp=change_perc($yesterday['3:11']['last_sell']*10000,$today['3:11']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>220,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));

	$data[]=array('text'=>number_format($today['3:12']['last_sell']*10000),'x'=>15,'y'=>270,'align'=>'right');
	$tmp=change_perc($yesterday['3:12']['last_sell']*10000,$today['3:12']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>270,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>number_format($today['3:13']['last_sell']*10000),'x'=>15,'y'=>320,'align'=>'right');
	$tmp=change_perc($yesterday['3:13']['last_sell']*10000,$today['3:13']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>105,'y'=>320,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	
	$data[]=array('text'=>$date,'x'=>0,'y'=>347,'color'=>'gray','size'=>10);
	render_banner(array('background'=>'blue-complete.png','font'=>'BHoma','format'=>'png','size'=>11,'save'=>'pc-blue.png'),$data);
}
function render_rfjewelry($today,$yesterday,$date){
	$data[]=array('text'=>number_format($today['3:3']['last_sell']*10000),'x'=>15,'y'=>71,'align'=>'right','color'=>'gray');
	$tmp=change_perc($yesterday['3:3']['last_sell']*10000,$today['3:3']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>110,'y'=>70,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'graygreen':'grayred'));
	$data[]=array('text'=>number_format($today['3:2']['last_sell']*10000),'x'=>15,'y'=>120,'align'=>'right','color'=>'gray');
	$tmp=change_perc($yesterday['3:2']['last_sell']*10000,$today['3:2']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>110,'y'=>119,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'graygreen':'grayred'));
	$data[]=array('text'=>number_format($today['0:1']['last_sell'],2),'x'=>15,'y'=>170,'align'=>'right','color'=>'gray');
	$tmp=change_perc($yesterday['0:1']['last_sell'],$today['0:1']['last_sell'],2,'img');
	$data[]=array('text'=>$tmp[0],'x'=>110,'y'=>169,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'graygreen':'grayred'));
	$data[]=array('text'=>number_format($today['3:40']['last_sell']*10),'x'=>15,'y'=>222,'align'=>'right','color'=>'gray');
	$tmp=change_perc($yesterday['3:40']['last_sell']*10,$today['3:40']['last_sell']*10,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>110,'y'=>221,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'graygreen':'grayred'));
	$data[]=array('text'=>$date,'x'=>0,'y'=>244,'color'=>'gray','size'=>10);
	render_banner(array('background'=>'gold-blue2.png','font'=>'BHoma','format'=>'png','size'=>11,'save'=>'gp-blue.png'),$data);
}
function render_ciodo($today,$yesterday,$date){
	$data[]=array('text'=>number_format($today['3:3']['last_sell']*10000),'x'=>15,'y'=>71,'align'=>'right');
	$tmp=change_perc($yesterday['3:3']['last_sell']*10000,$today['3:3']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>110,'y'=>70,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['3:2']['last_sell']*10000),'x'=>15,'y'=>120,'align'=>'right');
	$tmp=change_perc($yesterday['3:2']['last_sell']*10000,$today['3:2']['last_sell']*10000,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>110,'y'=>119,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['0:1']['last_sell'],2),'x'=>15,'y'=>170,'align'=>'right');
	$tmp=change_perc($yesterday['0:1']['last_sell'],$today['0:1']['last_sell'],2,'img');
	$data[]=array('text'=>$tmp[0],'x'=>110,'y'=>169,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>number_format($today['3:40']['last_sell']*10),'x'=>15,'y'=>222,'align'=>'right');
	$tmp=change_perc($yesterday['3:40']['last_sell']*10,$today['3:40']['last_sell']*10,0,'img');
	$data[]=array('text'=>$tmp[0],'x'=>110,'y'=>221,'align'=>'right','size'=>10,'color'=>(($tmp[1]=='pos')?'green':'red'));
	$data[]=array('text'=>$date,'x'=>0,'y'=>244,'color'=>'gray','size'=>10);
	render_banner(array('background'=>'ciodo.png','font'=>'BHoma','format'=>'png','size'=>11,'save'=>'ciodo.png'),$data);
}
function render_banner($i,$d){	
	global $root;
	$image = imagecreatefrompng($root.'private/imgs/'.$i['background']); 	
	foreach ($d as $t){	
		if (!$t['color']) $t['color']='black';
		if (!$t['font']) $t['font']=$i['font'];
		if (!$t['size']) $t['size']=$i['size'];
		$t['font']= $root.'private/farsi_gd/'.$t['font'].'.ttf';
		if (!isset($color[$t['color']])){
			if ($t['color']=='black') $color[$t['color']] = imagecolorallocate($image, 0, 0, 0);
			elseif ($t['color']=='red') $color[$t['color']] = imagecolorallocate($image, 150, 0, 0);
			elseif ($t['color']=='green') $color[$t['color']] = imagecolorallocate($image, 0, 150, 0);
			elseif ($t['color']=='grayred') $color[$t['color']] = imagecolorallocate($image, 150, 100, 100);
			elseif ($t['color']=='graygreen') $color[$t['color']] = imagecolorallocate($image, 100, 150, 100);
			elseif ($t['color']=='gray') $color[$t['color']] = imagecolorallocate($image, 150, 150, 150);
		}
		if ($t['align']=='right') {
			$dimensions = imagettfbbox($t['size'], 0, $t['font'], $t['text']);
			$textWidth = abs($dimensions[4] - $dimensions[0]);
			$t['x'] = imagesx($image) - $textWidth-$t['x'];
		}
		imagettftext($image, $t['size'], 0, $t['x'], $t['y'], $color[$t['color']], $t['font'], $t['text']);		
	}
	imagepng($image,$root.'public/ext/'.$i['save']);
	imagedestroy($image);  
}