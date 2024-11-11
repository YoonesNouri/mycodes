<?php   
chdir(dirname(__FILE__));
include '../private/db.php';
include '../private/backend.php';
include '../private/farsi_gd/persian_log2vis.php';
include '../private/libs/chart/class/pData.class.php';
include '../private/libs/chart/class/pDraw.class.php';
include '../private/libs/chart/class/pImage.class.php';
include '../private/libs/chart/class/pScatter.class.php';
$path=dirname($_SERVER["SCRIPT_NAME"]).'/../private/';

db_zone();
$timestamp=time();
$last_render=getCFG('arzlive_line_chart'); // Update charts just with new data
$latest_exchanges=getJSON('latest_exchanges');
/* 	Daily 	*/
$daily_charts=[	
	['rate_type'=>3,'currency_id'=>40,'name'=>'اونس طلا','file'=>'d-dollar.png','width'=>195,'height'=>153,'font-size'=>9,'graph-right'=>165,'graph-bottom'=>118],
	['rate_type'=>3,'currency_id'=>2,'name'=>'مثقال طلا','file'=>'d-gold.png','width'=>195,'height'=>153,'font-size'=>9,'graph-right'=>165,'graph-bottom'=>118],
	['rate_type'=>3,'currency_id'=>11,'name'=>'سکه امامی','file'=>'d-coin.png','width'=>195,'height'=>153,'font-size'=>9,'graph-right'=>165,'graph-bottom'=>118],
	//['rate_type'=>3,'currency_id'=>40,'name'=>'اونس طلا','file'=>'db-dollar.png','width'=>600,'height'=>300,'font-size'=>11,'graph-right'=>560,'graph-bottom'=>260],
	//['rate_type'=>3,'currency_id'=>2,'name'=>'مثقال طلا','file'=>'db-gold.png','width'=>600,'height'=>300,'font-size'=>11,'graph-right'=>560,'graph-bottom'=>260],
	//['rate_type'=>3,'currency_id'=>11,'name'=>'سکه امامی','file'=>'db-coin.png','width'=>600,'height'=>300,'font-size'=>11,'graph-right'=>560,'graph-bottom'=>260],	
];
foreach ($daily_charts as $chart){
	if (render_daily_line_chart($chart['file'],daily_per_minute_data($chart['rate_type'],$chart['currency_id'],$last_render),$chart)) {
		move_image($chart['file'],$chart['file']);
	}
}

setCFG('arzlive_line_chart',$timestamp);


function move_image($source,$dest){
	global $path;
	exec('optipng -o7 '.$path.'../tmp/'.$source);
	copy($path.'../tmp/'.$source,$path.'../public-arzlive/c/'.$dest);
}
function render_daily_line_chart($file,$data,$settings){
	if (!$data) return;
	global $path;	
	$width=$settings['width'];
	$height=$settings['height'];	
	$font=$path.'farsi_gd/BHoma.ttf';
	$font_size=$settings['font-size'];
	$myData = new pData();  
	
	/* 		Legend data	 	*/
	if ($data['d1_date']){
		$myData->addPoints($data['d1_legend'],"d1_legend");
		$myData->setSerieOnAxis("d1_legend",0);
	}
	$myData->addPoints($data['d0_legend'],"d0_legend");
	$myData->setSerieOnAxis("d0_legend",0);	

	/*		Format		*/
	//$myData->setAxisDisplay(0,AXIS_FORMAT_TIME,"H:i");
	$myData->setAxisDisplay(0,AXIS_FORMAT_CUSTOM,"AxisFormat"); 
	
	//$myData->setAxisName(0,"X");
	$myData->setAxisXY(0,AXIS_X);
	$myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

	/*		Line's Data		*/	
	if ($data['d1_date']){
		$myData->addPoints($data['d1'],"d1");
		$myData->setSerieOnAxis("d1",1);
	}	
	$myData->addPoints($data['d0'],"Today");
	$myData->setSerieOnAxis("Today",1);
	
	$myData->setAxisXY(1,AXIS_Y);
	$myData->setAxisPosition(1,AXIS_POSITION_RIGHT);

	/*		Render Line		*/
	if ($data['d1_date']){
		$myData->setScatterSerie("d1_legend","d1",2);
		$myData->setScatterSerieColor(2,array("R"=>210,"G"=>210,"B"=>210));
		//$myData->setScatterSerieWeight(2,0.5);
	}	
	$myData->setScatterSerie("d0_legend","Today",1);
	$myData->setScatterSerieColor(1,array("R"=>0,"G"=>47,"B"=>99,"Surrounding"=>100));
	$myData->setScatterSerieWeight(1,0.5);

	$myPicture = new pImage($width,$height,$myData);
	//$myPicture->drawFilledRectangle(0,0,$width,$height,array("R"=>245,"G"=>228,"B"=>206));
	///$myPicture->drawFilledRectangle(0,0,$width,$height,array("R"=>255,"G"=>258,"B"=>246));
	 
	//$myPicture->Antialias = FALSE;
	
	if ($settings['background']) $myPicture->drawFromPNG(0,0,$path.'charts/'.$settings['background']);

	$myPicture->setFontProperties(array('FontName'=>$font,"FontSize"=>$font_size,"R"=>100,"G"=>100,"B"=>100,));
	$myPicture->setGraphArea(5,5,$settings['graph-right'],$settings['graph-bottom']);
	 
	$myScatter = new pScatter($myPicture,$myData);
	$scaleSettings = array("Mode"=>SCALE_MODE_FLOATING,"SkippedAxisAlpha"=>0,"XMargin"=>5,"YMargin"=>5,"Floating"=>false,
		"InnerTickWidth"=>0,"OuterTickWidth"=>0,"GridTicks"=>3,"LabelSkip"=>0,"GridR"=>150,"GridG"=>150,"GridB"=>150,"AxisAlpha"=>0,
		"CycleBackground"=>FALSE,"LabelRotation"=>30);
	$myScatter->drawScatterScale($scaleSettings);
	$myScatter->drawScatterLineChart();	

	//$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>100),1=>array("Min"=>10,"Max"=>20));

	/*		Render Text		*/
	$myPicture->setFontProperties(array('FontName'=>$path.'farsi_gd/TAHOMA.TTF',"FontSize"=>8));

	persian_log2vis($settings['name']);	
	$name='arzlive.com '.$settings['name'];
	$myPicture->drawText($settings['graph-right']-right_align($font,$font_size,$name),$settings['graph-bottom']-10,$name,array("R"=>180,"G"=>180,"B"=>180,"Angle"=>0));

	imagetruecolortopalette($myPicture->Picture, false, 256);
	$myPicture->render($path.'../tmp/'.$file);
	//$myPicture->stroke(); //For debug
	return true;
}
function right_align($font,$size,$text){
	$dimensions = imagettfbbox($size, 0, $font, $text);
	return abs($dimensions[4] - $dimensions[0]);
}
function daily_per_minute_data($rate_type,$currency_id,$last_render){
	global $latest_exchanges;
	if ($latest_exchanges[$rate_type.':'.$currency_id]['timestamp']<$last_render) return false; // No new data added to render it
	$count=0;
	$rs=db_query('SELECT sell,UNIX_TIMESTAMP(addedon) as timestamp,YEAR(addedon) as year,MONTH(addedon) as month,DAY(addedon) as day,HOUR(addedon) as h,MINUTE(addedon) as m FROM exch_rate WHERE rate_type='.$rate_type.' AND currency_id = '.$currency_id.' ORDER BY addedon DESC;');
	while ($row=db_array($rs)) { 
		if ($prev==null) $last_price=$row['sell'];
		elseif ($prev<$row['h']) {			
			$cdate=$d.' '.jalali_month($m);					
			$last_price=$row['sell'];
			persian_log2vis($cdate);	
			$date[$count]=$cdate;
			$out[]=$data;
			unset($data);
			if (++$count==3) break;
		}
		list($y,$m,$d)=gregorian_to_jalali($row['year'],$row['month'],$row['day']);
		$prev=$row['h'];
		$data[($row['m']+$row['h']*60)]=$row['sell'];
	}
	if (!is_array($out)) return;
	foreach ($out as $day=>$data) {
		ksort($data);
		unset($tmp);
		unset($legend);
		foreach ($data as $key=>$val){
			$tmp[]=$val;
			$legend[]=$key;
		}
		$return['d'.$day]=$tmp;
		$return['d'.$day.'_legend']=$legend;
		$return['d'.$day.'_date']=$date[$day];
	}
	return $return;
}
function AxisFormat($val) { 
	global $h_reserve;
	$m=$val-floor($val/60)*60;
	$h=floor($val/60);
	if (strlen($m)==1)$m='0'.$m;
	if ($h<0||$h>23) return '';
	return $h.':'.$m;
} 