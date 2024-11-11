var lastup=timestamp(),aj_req=0,pg_timer,pg_countdown;
$(function(){
	/*		Page Update	Setup	*/
	pg_countdown=$(".dynamic").data("timer");
	setInterval(countdownUpdate, 1000);
	pageUpdate(1);
	setTimeout(function() { $(".dynamic").fadeIn();}, 2000); // Show page countdown		
	$(".dynamic").delegate("a.manual","click",function(e){ // Manual page update
		$(".dynamic").fadeOut();
		e.preventDefault();
		pageUpdate(1);
	});
});
function pageUpdate(force_update){
	if (aj_req) return pgUpTime(5); // Previous ajax request is not completed
	aj_req=1;

	return $.ajax({ //Return ajax request to use done/always/fail
		url: "json/d.json?" + lastup,
		type: "GET",
		dataType: "JSON"
	}).done(function(data) {
		pageUpdate.failed=0;
		lastup = timestamp();
		//$(".notice .failed").remove();
		$.each(data,function(key,val){
			$("."+key).each(function(){
				var field=$(this);
				if (key.charAt(0)=='t') {
					field.data("time",val);
				} else {
					if (field.text()!=val){
						if (key.charAt(0)=='c') {							
							var tmp=val.split(" ");
							//if (is_defined(tmp[1])) tmp=parse_float(tmp[1]); else tmp=parse_float(tmp[0]);
							tmp=parse_float((undefined!=tmp[1])?tmp[1]:tmp[0]);
							if (tmp<0) var cl="neg";
							else if (tmp>0) var cl="pos";
							field.removeClass("neg pos").addClass(cl);
						}
						if (key.charAt(0)=='s' && field.parent().is("tr")){
							tmp=(parse_float(val)<parse_float(field.text()))?"chdn":"chup";
							field.parent().addClass(tmp);
						}
						field.html(val);
					}
				}
			});
		});	
		setTimeout(function() { $(".chdn,.chup").removeClass("chup chdn");}, 4000);

		/*if(typeof page_update_trigger == 'function') {
			page_update_trigger(data);
		}*/
		$(".dynamic").data("time",pg_countdown);
		pgUpTime(pg_countdown);	
	}).fail(function(jqXHR, textStatus) {		
		pageUpdate.failed=pageUpdate.failed+1||0;		
		//$(".notice").html("<div class='failed'>مشکلی در دریافت اطلاعات وجود دارد. ارتباط اینترنت خود را کنترل نمایید. پس از برقراری ارتباط بارگذاری مجددا به صورت خودکار انجام خواهد شد.</div>");		
		pgUpTime(5);		
	}).always(function(){	
		aj_req=0;
		$(".dynamic").fadeIn();		
	});	
}
function parse_float(val){
	if (val==undefined) return '';
	return parseFloat(val.replace(/,/gi,''));
}
function timestamp(){
	return new Date().getTime();
}
function pgUpTime(t){
	clearTimeout(pg_timer);
	pg_timer=setTimeout(pageUpdate, t*1000);
}
function countdownUpdate(){
	/*	
		dynamic class for countdown, others are counter
	*/
	$(".counter").each(function(){
		var counter=parseInt($(this).data("time"));
		if ($(this).hasClass("dynamic")) { // Countdown
			counter=(counter)?(counter-1):0;
		} else {	//	Counter
			counter=counter+1;
		}
		$(this).data("time",counter).find("span").html(createCountdown(counter));
	});
}
function createCountdown(time) {
	var str='',day,hour,minute;
	if (day=Math.floor(time/(60*60*24))){		
		str=day+' روز و ';
		time=time-(day*60*60*24);
	}
	if (hour=Math.floor(time/(60*60))){
		str=str + hour + ' ساعت و ';
		time=time-(hour*60*60);
	}
	if (minute=Math.floor(time/(60))){
		str=str + minute + ' دقیقه و ';
		time=time-(minute*60);
	}
	return str+time+' ثانیه ';
}
