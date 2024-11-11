$(function(){
	time_machine_init();
});
function time_machine_init(){
	$("#header").delegate("a.archive","click",function(e){ 
		$(".dynamic").fadeOut();
		e.preventDefault();
		$("#tmachine").slideDown("slow");
		prevent_update=true;
		$("#machinedisp span").html($(".adate:first").html());
		timemachine_time=0;
		$("#machinetime").data("upto",($(".adate:first").data("time")));
		timemachine_controls_arrange(0);
		$("#machinetime span").html(createCountdown(timemachine_time*60));
	});
	$("body").delegate("#machineclose","click",function(e){
		e.preventDefault();
		$(".dynamic").fadeIn();
		$("#tmachine").slideUp("slow");
		prevent_update=false;
		pageUpdate(1);
	}).delegate("#tmachine .control a","click",function(e){
		e.preventDefault();
		if ($(this).hasClass("disable")) return;
		var timemachine_steps=$(this).parent().data("steps");
		if ($(this).data("type")!='neg')timemachine_steps=timemachine_steps*-1 ;
		timemachine_time=timemachine_time+timemachine_steps;
		$("#machinetime span").html(createCountdown(timemachine_time*60));
		timemachine_controls_arrange(timemachine_time);
		clearTimeout(timemachine_delay);
		timemachine_delay=setTimeout(execute_timemachine, 2000);
	});

}
var timemachine_delay;
function execute_timemachine(){
	page_ajax_call("/timemachine.php?t="+($("#machinetime").data("upto")-timemachine_time));
}
function timemachine_controls_arrange(timemachine_time){
		/*		Disable Enable Keys		*/
		var max_timestamp=$("#machinetime").data("upto");
		$("#tmachine .control a").removeClass("disable");
		$("#tmachine .control").each(function(){
			var step=$(this).data("steps");	
			if (timemachine_time-step<0) $(this).find("a").eq(0).addClass("disable");
			if (22559086>$("#machinetime").data("upto")-timemachine_time-step) $(this).find("a").eq(1).addClass("disable");
		});	
}
