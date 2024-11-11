<?php   
include 'db.php';
include 'backend.php';
include "ati-cfg.php";
include 'farsi_gd/persian_log2vis.php';
include 'libs/chart/class/pData.class.php';
include 'libs/chart/class/pDraw.class.php';
include 'libs/chart/class/pImage.class.php';
include("libs/chart/class/pPie.class.php");
$path=dirname($_SERVER["SCRIPT_NAME"]).'/';

db_zone();
$timestamp=time();
$last_render=getCFG('create_ati_pie_chart'); // Update charts just with new data

if (render_pie_chart('pie.png',pie_data(getJSON('last_ime'),$last_render),array('background'=>'candlestick-dollar.png'))) move_image('minutes.png','s/linechart-dollar.png');

//setCFG('create_ati_pie_chart',$timestamp);

function move_image($source,$dest){
	global $path;
	exec('optipng -o7 '.$path.'../tmp/'.$source);
	copy($path.'../tmp/'.$source,$path.'../public/c/'.$dest);
}
function render_pie_chart($file,$data,$settings){
	if (!$data) return;
	global $path;	
	$width=285;
	$height=160;	
	$font=$path.'farsi_gd/BHoma.ttf';
	$font_size=9;

	/* Create and populate the pData object */
	$MyData = new pData();   
	$MyData->addPoints($data[1],"ScoreA");  
	$MyData->setSerieDescription("ScoreA","Application A");

	/* Define the absissa serie */
	$MyData->addPoints($data[0],"Labels");
	$MyData->setAbscissa("Labels");

	/* Create the pChart object */
	$myPicture = new pImage($width,$height,$MyData);
	$myPicture->setFontProperties(array('FontName'=>$font,"FontSize"=>$font_size));
	
	if ($settings['background']) $myPicture->drawFromPNG(0,0,$path.'charts/'.$settings['background']);
	$myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>50));
	/* Create the pPie object */ 
	$PieChart = new pPie($myPicture,$MyData);

	/* Draw an AA pie chart */ 
	$PieChart->draw2DRing(190,80,array("WriteValues"=>TRUE,"ValueR"=>80,"ValueG"=>80,"ValueB"=>80,"Border"=>TRUE));

	/* Write the legend box */ 
	$myPicture->setShadow(FALSE);
	$PieChart->drawPieLegend(15,90,array("Alpha"=>20));

	
	

	//
	//$myPicture->setGraphArea(34,20,$width-10,$height-35);
	//$scaleSettings = array("GridTicks"=>5,"LABELING_DIFFERENT"=>true,"LabelSkip"=>5,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>FALSE,'LabelRotation'=>FALSE);

	//$myScatter->drawScatterSplineChart();
	//$myScatter->drawScatterPlotChart();
	imagetruecolortopalette($myPicture->Picture, false, 256);
	//$myPicture->render($path.'../tmp/'.$file);
	$myPicture->stroke(); //For debug
	exit;
	//return true;
}
function right_align($font,$size,$text){
	$dimensions = imagettfbbox($size, 0, $font, $text);
	return abs($dimensions[4] - $dimensions[0]);
}
function pie_data($data,$last_render){
	global $ati_months;
	foreach ($ati_months as $key => $val){
		$cdate=$val['title'];					
		persian_log2vis($cdate);	
		$text[]=$cdate;
		$out[]=$data[$key]['volume']*100;
	}
	return array($text,$out);
}
function AxisFormat($val) { 
	$m=$val-floor($val/60)*60;
	$h=floor($val/60);
	if (strlen($m)==1)$m='0'.$m;
	return $h.':'.$m;
} 