/*		Tooltip Init 0.04		*/
$(function(){
	xOffset = -20;
	yOffset = 10;		
	$('body').delegate('.tip','mouseenter',function(e){
		$("#tip").remove();
		var selector=$(this);
		var data=selector.data("tip");
		if (!data) return;
		$("body").append("<p id='tip'>"+ data +"</p>");
		var timeout=new Date().getTime();
		$("#tip").data("timeout",timeout);
		setTimeout(function(){tooltip_display(selector,timeout)},500);		
	}).delegate('.tip','mouseleave',function(e){
		$("#tip").remove();
	}).delegate('.tip','mousemove',function(e){
		$("#tip").css({"top":(e.pageY - xOffset) + "px","left":(e.pageX + yOffset) + "px"});
	});	
	function tooltip_display(selector,timeout){
		if ($("#tip").data("timeout")!=timeout) return;
		$("#tip").css({"top":(selector.pageY - xOffset) + "px","left":(selector.pageX + yOffset) + "px"}).fadeIn("fast");
	}
});
