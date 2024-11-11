<?php   
include 'db.php';
include 'backend.php';
include 'farsi_gd/persian_log2vis.php';
include 'libs/chart/class/pData.class.php';
include 'libs/chart/class/pDraw.class.php';
include 'libs/chart/class/pImage.class.php';
include("libs/chart/class/pScatter.class.php");
$path=dirname($_SERVER["SCRIPT_NAME"]).'/';

db_zone();
$timestamp=time();
$last_render=getCFG('mx_line_chart'); // Update charts just with new data

if ($tmp=getCFG('latest_exchanges')) $latest_exchanges=json_decode($tmp,true);

if($data=per_minute_data(3,40,$last_render)){
	render_line_chart('minutes.png',$data,array('background'=>'candlestick-dollar.png'));
	move_image('minutes.png','s/linechart-dollar.png');
	render_line_chart('minutes.png',$data,array('background'=>'acandlestick-dollar.png'));
	move_image2('minutes.png','linechart-dollar.png');
}
if($data=per_minute_data(3,2,$last_render)){
	render_line_chart('minutes.png',$data,array('background'=>'candlestick-gold-17.png'));
	move_image('minutes.png','s/linechart-gold.png');
	render_line_chart('minutes.png',$data,array('background'=>'acandlestick-gold-17.png'));
	move_image2('minutes.png','linechart-gold.png');
}
if($data=per_minute_data(3,11,$last_render)){
	render_line_chart('minutes.png',$data,array('background'=>'candlestick-fcoin.png'));
	move_image('minutes.png','s/linechart-fcoin.png');
	render_line_chart('minutes.png',$data,array('background'=>'acandlestick-fcoin.png'));
	move_image2('minutes.png','linechart-fcoin.png');	
}

setCFG('mx_line_chart',$timestamp);

function move_image($source,$dest){
	global $path;
	exec('optipng -o7 '.$path.'../tmp/'.$source);
	copy($path.'../tmp/'.$source,$path.'../public/c/'.$dest);
}
function move_image2($source,$dest){
	global $path;
	exec('optipng -o7 '.$path.'../tmp/'.$source);
	copy($path.'../tmp/'.$source,$path.'../public-arzlive/c/'.$dest);
}
function render_line_chart($file,$data,$settings){
	if (!$data) return;
	global $path;	
	$width=680;
	$height=330;	
	$font=$path.'farsi_gd/BHoma.ttf';
	$font_size=9;
	$myData = new pData();  
	
	/* 		Legend data	 	*/
	if ($data['daysago_date']){
		$myData->addPoints($data['daysago_legend'],"daysago_legend");
		$myData->setSerieOnAxis("daysago_legend",0);
	}
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
	if ($data['daysago_date']){
		$myData->addPoints($data['daysago'],"2Days Ago");
		$myData->setSerieOnAxis("2Days Ago",1);
	}
	if ($data['yesterday_date']){
		$myData->addPoints($data['yesterday'],"Yesterday");
		$myData->setSerieOnAxis("Yesterday",1);
	}
	$myData->addPoints($data['today'],"Today");
	$myData->setSerieOnAxis("Today",1);
	
	$myData->setAxisXY(1,AXIS_Y);
	$myData->setAxisPosition(1,AXIS_POSITION_LEFT);
		

	/*		Render Line		*/
	if ($data['daysago_date']){
		$myData->setScatterSerie("daysago_legend","2Days Ago",3);
		$myData->setScatterSerieColor(3,array("R"=>0,"G"=>122,"B"=>160));
	}
	if ($data['yesterday_date']){
		$myData->setScatterSerie("yesterday_legend","Yesterday",2);
		$myData->setScatterSerieColor(2,array("R"=>200,"G"=>0,"B"=>0));
	}
	$myData->setScatterSerie("today_legend","Today",1);
	$myData->setScatterSerieColor(1,array("R"=>0,"G"=>160,"B"=>0));
	

	$myPicture = new pImage($width,$height,$myData);
	//$myPicture->Antialias = FALSE;
	
	if ($settings['background']) $myPicture->drawFromPNG(0,0,$path.'charts/'.$settings['background']);

	$myPicture->setFontProperties(array('FontName'=>$font,"FontSize"=>$font_size));
	$myPicture->setGraphArea(34,20,$width-10,$height-35);
	$myScatter = new pScatter($myPicture,$myData);
	$scaleSettings = array("GridTicks"=>5,"LABELING_DIFFERENT"=>true,"LabelSkip"=>5,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>FALSE,
	'LabelRotation'=>FALSE);
	$myScatter->drawScatterScale($scaleSettings);
	$myScatter->drawScatterLineChart();	

	/*		Render Text		*/
	$myPicture->drawText($width-right_align($font,$font_size,$data['today_date'])-5,15,$data['today_date'],array("R"=>0,"G"=>160,"B"=>0));
	$myData->setScatterSerieColor(1,array("R"=>136,"G"=>250,"B"=>135));
	if ($data['yesterday_date']){
		$myPicture->drawText($width-right_align($font,$font_size,$data['yesterday_date'])-5,30,$data['yesterday_date'],array("R"=>200,"G"=>0,"B"=>0));
		$myData->setScatterSerieColor(2,array("R"=>255,"G"=>135,"B"=>135));
	}
	if ($data['daysago_date']){
		$myPicture->drawText($width-right_align($font,$font_size,$data['daysago_date'])-5,45,$data['daysago_date'],array("R"=>0,"G"=>122,"B"=>160));
		$myData->setScatterSerieColor(3,array("R"=>135,"G"=>226,"B"=>255));
	}
	$myScatter->drawScatterBestFit();
	//$myScatter->drawScatterSplineChart();
	//$myScatter->drawScatterPlotChart();
	imagetruecolortopalette($myPicture->Picture, false, 256);
	$myPicture->render($path.'../tmp/'.$file);
	//$myPicture->stroke(); For debug
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
		if (!$prev) $last_price=$row['sell'];
		if ($prev<$row['h']&&$prev) {			
			$cdate=' - '.$d.' '.jalali_month($m).' آخرین قیمت '.((float)($last_price));					
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
	$m=$val-floor($val/60)*60;
	$h=floor($val/60);
	if (strlen($m)==1)$m='0'.$m;
	return $h.':'.$m;
} 