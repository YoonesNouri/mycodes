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
$last_render=getCFG('tg_line_chart'); // Update charts just with new data
$latest_exchanges=getJSON('latest_exchanges');
$render_charts=array(
	array(0,1,'اونس طلا','rt-ounce.png'),
	array(3,2,'مثقال طلا','rt-mesghal.png'),
	array(3,11,'سکه امامی','rt-fcoin.png'),
);
foreach ($render_charts as $chart){
	if (render_line_chart($chart[3],per_minute_data($chart[0],$chart[1],$last_render),array('name'=>$chart[2]))) move_image($chart[3],$chart[3]);
}

setCFG('tg_line_chart',$timestamp);


function move_image($source,$dest){
	global $path;
	exec('optipng -o7 '.$path.'../tmp/'.$source);
	copy($path.'../tmp/'.$source,$path.'../public-tg/c/'.$dest);
}
function render_line_chart($file,$data,$settings=array()){
	if (!$data) return;
	global $path;	
	$width=250;
	$height=150;	
	$font=$path.'farsi_gd/BHoma.ttf';
	$font_size=9;
	$myData = new pData();  
	
	/* 		Legend data	 	*/
	if ($data['yesterday_date']){
		$myData->addPoints($data['yesterday_legend'],"yesterday_legend");
		$myData->setSerieOnAxis("yesterday_legend",0);
	}
	$myData->addPoints($data['today_legend'],"today_legend");
	$myData->setSerieOnAxis("today_legend",0);	

	/*		Format		*/
	//$myData->setAxisDisplay(0,AXIS_FORMAT_TIME,"H:i");
	$myData->setAxisDisplay(0,AXIS_FORMAT_CUSTOM,"AxisFormat"); 
	
	//$myData->setAxisName(0,"X");
	$myData->setAxisXY(0,AXIS_X);
	$myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

	/*		Line's Data		*/	
	if ($data['yesterday_date']){
		$myData->addPoints($data['yesterday'],"Yesterday");
		$myData->setSerieOnAxis("Yesterday",1);
	}	
	$myData->addPoints($data['today'],"Today");
	$myData->setSerieOnAxis("Today",1);
	
	$myData->setAxisXY(1,AXIS_Y);
	$myData->setAxisPosition(1,AXIS_POSITION_RIGHT);

	/*		Render Line		*/
	if ($data['yesterday_date']){
		$myData->setScatterSerie("yesterday_legend","Yesterday",2);
		$myData->setScatterSerieColor(2,array("R"=>210,"G"=>210,"B"=>210));
		//$myData->setScatterSerieWeight(2,0.5);
	}	
	$myData->setScatterSerie("today_legend","Today",1);
	$myData->setScatterSerieColor(1,array("R"=>152,"G"=>0,"B"=>0,"Surrounding"=>100));
	$myData->setScatterSerieWeight(1,0.5);

	$myPicture = new pImage($width,$height,$myData);
	//$myPicture->drawFilledRectangle(0,0,$width,$height,array("R"=>245,"G"=>228,"B"=>206));
	///$myPicture->drawFilledRectangle(0,0,$width,$height,array("R"=>255,"G"=>258,"B"=>246));
	 
	//$myPicture->Antialias = FALSE;
	
	if ($settings['background']) $myPicture->drawFromPNG(0,0,$path.'charts/'.$settings['background']);

	$myPicture->setFontProperties(array('FontName'=>$font,"FontSize"=>$font_size,"R"=>100,"G"=>100,"B"=>100,));
	$myPicture->setGraphArea(5,5,$width-30,$height-35);
	 
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
	$name='tgju.org '.$settings['name'];
	$myPicture->drawText($width-right_align($font,$font_size,$name)-40,109,$name,array("R"=>210,"G"=>210,"B"=>210,"Angle"=>0));

	imagetruecolortopalette($myPicture->Picture, false, 256);
	$myPicture->render($path.'../tmp/'.$file);
	//$myPicture->stroke(); //For debug
	return true;
}
function right_align($font,$size,$text){
	$dimensions = imagettfbbox($size, 0, $font, $text);
	return abs($dimensions[4] - $dimensions[0]);
}
function per_minute_data($rate_type,$currency_id,$last_render){
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
		if ($day == 0) $key='today';
		elseif ($day == 1) $key='yesterday';
		elseif ($day == 2) $key='daysago';
		$return[$key]=$tmp;
		$return[$key.'_legend']=$legend;
		$return[$key.'_date']=$date[$day];
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