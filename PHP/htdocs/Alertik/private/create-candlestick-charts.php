<?php   
include 'db.php';
include 'backend.php';
include 'farsi_gd/persian_log2vis.php';
include 'libs/chart/class/pData.class.php';
include 'libs/chart/class/pDraw.class.php';
include 'libs/chart/class/pImage.class.php';
include 'libs/chart/class/pStock.class.php';
$path=dirname($_SERVER["SCRIPT_NAME"]).'/';

//db_zone();
$timestamp=time();
$last_render=getCFG('mx_candle_chart'); // Update charts just with new data
$latest_exchanges=getJSON('latest_exchanges');
db_query('SET time_zone = "+06:30";'); // Fix time problem

if ($data=monthly_data(3,40,12,$last_render)){
	render_candle_stick('20days.png',$data,['background'=>'candlestick-dollar.png']);
	move_image('20days.png','d/candlestick-dollar-m.png');
	render_candle_stick('20days.png',$data,['background'=>'acandlestick-dollar.png']);
	move_image2('20days.png','candlestick-dollar-m.png');
}
if ($data=monthly_data(3,2,12,$last_render)){
	render_candle_stick('20days.png',$data,['background'=>'candlestick-gold-17.png']);
	move_image('20days.png','d/candlestick-gold-m.png');
	render_candle_stick('20days.png',$data,['background'=>'acandlestick-gold-17.png']);
	move_image2('20days.png','candlestick-gold-m.png');
}
if ($data=monthly_data(3,11,12,$last_render)){
	render_candle_stick('20days.png',$data,['background'=>'candlestick-fcoin.png']);
	move_image('20days.png','d/candlestick-fcoin-m.png');
	render_candle_stick('20days.png',$data,['background'=>'acandlestick-fcoin.png']);
	move_image2('20days.png','candlestick-fcoin-m.png');
}
if ($data=daily_data(3,40,26,$last_render)){
	render_candle_stick('20days.png',$data,['background'=>'candlestick-dollar.png']);
	move_image('20days.png','d/candlestick-dollar.png');
	render_candle_stick('20days.png',$data,['background'=>'acandlestick-dollar.png']);
	move_image2('20days.png','candlestick-dollar.png');
}
if ($data=daily_data(3,2,26,$last_render)){
	render_candle_stick('20days.png',$data,['background'=>'candlestick-gold-17.png']);
	move_image('20days.png','d/candlestick-gold.png');
	render_candle_stick('20days.png',$data,['background'=>'acandlestick-gold-17.png']);
	move_image2('20days.png','candlestick-gold.png');
}
if ($data=daily_data(3,11,26,$last_render)){
	render_candle_stick('20days.png',$data,['background'=>'candlestick-fcoin.png']);
	move_image('20days.png','d/candlestick-fcoin.png');
	render_candle_stick('20days.png',$data,['background'=>'acandlestick-fcoin.png']);
	move_image2('20days.png','candlestick-fcoin.png');
}
setCFG('arzlive_line_chart',$timestamp);

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
function render_candle_stick($file,$data,$settings){
	echo '1';
	if (!$data) return;
	echo '3';
	global $path;	
	$width=680;
	$height=330;
	list($legend,$open,$close,$min,$max)=$data;
	$MyData = new pData();  
	$MyData->addPoints($open,'Open');
	$MyData->addPoints($close,'Close');
	$MyData->addPoints($min,'Min');
	$MyData->addPoints($max,'Max');

	$title=' ';

	$MyData->addPoints($legend,$title);
	$MyData->setAbscissa($title);
	$MyData->setAbscissaName($title);

	/* Create the pChart object */
	$myPicture = new pImage($width,$height,$MyData);

	if ($settings['background']) $myPicture->drawFromPNG(0,0,$path.'charts/'.$settings['background']);

	/* Turn of AAliasing */
	$myPicture->Antialias = FALSE;
	$myPicture->setFontProperties(array('FontName'=>$path.'farsi_gd/BHoma.ttf',"FontSize"=>9,"R"=>70,"G"=>70,"B"=>70));


	/* Define the chart area */
	$myPicture->setGraphArea(34,10,$width-10,$height-35);

	/* Draw the scale */
	$scaleSettings = array("GridR"=>100,"GridG"=>100,"GridB"=>100,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
	$myPicture->drawScale($scaleSettings);

	/* Create the pStock object */
	$mystockChart = new pStock($myPicture,$MyData);

	/* Draw the stock chart */
	$stockSettings = array("BoxUpR"=>108,"BoxUpG"=>228,"BoxUpB"=>108,"BoxDownR"=>255,"BoxDownG"=>124,"BoxDownB"=>124,"SerieMedian"=>"Median"); 
	$mystockChart->drawStockChart($stockSettings);

	$myPicture->render($path.'../tmp/'.$file);
	return true;
}
function daily_data($rate_type,$currency_id,$limit,$last_render){
	global $latest_exchanges;
	if ($latest_exchanges[$rate_type.':'.$currency_id]['timestamp']<$last_render) return false; // No new data added to render it	
	$prev_month=null;
	$today=db_array(db_query('SELECT YEAR(NOW()) as y,MONTH(NOW()) as m,DAY(NOW()) as d'));
	$rs=db_query('SELECT * FROM exch_archive WHERE rate_type='.$rate_type.' AND currency_id = '.$currency_id.' AND duration_type = 0 AND addedon<"'.$today['y'].'-'.$today['m'].'-'.$today['d'].' 00:00:00" ORDER BY addedon DESC LIMIT '.$limit.';');
	while ($row=db_array($rs)) { // Avg
		$date=$row['pday']."\n".jalali_month($row['pmonth']);
		persian_log2vis($date);
		$data[]=array('last'=>$row['end'],'max'=>$row['max'],'min'=>$row['min'],'start'=>$row['start'],'date'=>$date);
	}
	krsort($data);
	$key=0;
	foreach ($data as $d){
		$close[$key]=$d['last'];
		$min[$key]=$d['min'];
		$max[$key]=$d['max'];
		$open[$key]=$d['start'];
		list($day,$month)=explode("\n",$d['date']);
		if ($month!=$prev_month) $legend[$key]=$d['date'];
		else $legend[$key]=$day;
		$prev_month=$month;
		$key++;
	}
	return array($legend,$open,$close,$min,$max);
}
function monthly_data($rate_type,$currency_id,$limit){
	$rs=db_query('SELECT * FROM exch_archive WHERE rate_type='.$rate_type.' AND currency_id = '.$currency_id.' AND duration_type = 2 ORDER BY addedon DESC LIMIT '.$limit.';');
	while ($row=db_array($rs)) { 
		$date=jalali_month($row['pmonth']);
		persian_log2vis($date);
		$data[]=array('last'=>$row['end'],'max'=>$row['max'],'min'=>$row['min'],'start'=>$row['start'],'date'=>$date."\n".$row['pyear']);
	}
	krsort($data);
	$key=0;
	foreach ($data as $d){
		$close[$key]=$d['last'];
		$min[$key]=$d['min'];
		$max[$key]=$d['max'];
		$open[$key]=$d['start'];
		list($month,$year)=explode("\n",$d['date']);
		if ($year!=$prev_year) $legend[$key]=$d['date'];
		else $legend[$key]=$month;
		$prev_year=$year;
		$key++;
	}
	return array($legend,$open,$close,$min,$max);
}