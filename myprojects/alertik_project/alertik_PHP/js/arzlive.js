$(function(){
	chart_run();
	$("body").delegate("#conversation_tool select,#conversation_tool input","keyup change",function(e){
		conversation_update();
	})
});
function page_update_trigger(data){
	chart_run();
}
function chart_run(){
	$('.spark').each(function(){
		var data=$(this).data("chart");
		//console.log(data);
		if (typeof data=="string") $(this).sparkline(data.split(","), {"width": '60px',"height":"26px", "fillColor":false,"minSpotColor":false,"maxSpotColor":false,'lineColor':'#000',"tooltipFormatter":custom_tooltip});
		/*$(this).sparkline("html", {"tagValuesAttribute":"data-min","width": '60px',"height":"26px", "composite": true,"minSpotColor":false,"maxSpotColor":false,"fillColor":"#fff", 'lineColor':'#bbb',"chartRangeClip":true, "numberFormatter": chart_min,"chartRangeMax":Math.max.apply("",max),"chartRangeMin":Math.min.apply("",min)});*/
	});
}
function format_number(val){
	var tmp=(val+"").split('.');
  	if (tmp[0].length > 3) return tmp[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, ',')+(tmp[1]?'.'+tmp[1]:'');
  	return val;	
}
function custom_tooltip(sparklines, options, point){
	var names=sparklines["$el"].data("t").split(",");
	//console.log()
	//point.y
	return "<div class=\"jqsfield\">" +format_number(point.y)+ ' در '+names[point.offset]+"</div>";
		//+"<div class=\"jqsfield\"><span style=\"color: " + point.color + "\">&#9679;</span>" + " حداکثر قیمت "+format_number(point.y)+"</div>";
	//console.log(sparklines);
	//console.log(options);
	//console.log(point);
}
function conversation_update() {
	var base = 2,target = 1;
	var amount=parse_float($("#conv_input").val());
	if (isNaN(amount)) amount=0;
	//console.log();
	var tmp=((parse_float($(".s"+$("#conv_currency_side1").val()+":first").html())*amount)/parse_float($(".s"+$("#conv_currency_side2").val()+":first").html())).toFixed(2);
	$("#con_result").html(addComma(tmp));
	$("#conv_input").val(addComma($("#conv_input").val().replace(/,/gi,'')));
}