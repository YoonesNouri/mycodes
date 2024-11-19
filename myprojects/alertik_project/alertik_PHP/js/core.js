var lastup=timestamp(),focus=1,activity,aj_req=0,pg_timer,page_type='',page_loaded_on=timestamp(),
	google_pageview=0,prevent_update=false,timemachine_time=0;
$(function(){
	is_active=1;
	/*		Page Update	Setup	*/
	if ($("#type").val()) {
		$(document).bind("mousemove keydown DOMMouseScroll mousewheel mousedown touchstart touchmove",function(e){
			activity=timestamp();
			if (!focus) {				
				pageUpdate(); // Force update on delayed update due to inactivity
			}
		}); 
		$(window).focus(function() {
			focus=1;
			activity=timestamp();
			//analytics('e/focus');
			pageUpdate();
		}).blur(function() {
			focus=0;
		});
		setInterval(run_every_second, 1000);
		pgUpTime(pg_countdown);
		setTimeout(function() { $(".dynamic").fadeIn();}, 2000); // Show page countdown		
	}
	/*		Voting Display if they were not voted in last 3 days		*/
	if ($(".xvq").data("q")) {
		if (cookie.get("vote_"+$(".xvq").data("q"))==null) $(".xv").slideDown();
	}
	
	$("#header").delegate("a.manual","click",function(e){ // Manual page update
		$(".dynamic").fadeOut();
		analytics("e/forced");
		e.preventDefault();
		pageUpdate(1);
	});
	$("body").delegate(".track a","click",function(e){
		analytics($(this).attr("href"));
	}).delegate(".chart a","click",function(e){
		if ($("#chart_box").length==0) return;
		e.preventDefault();
		$("#chart_box").html('<img src="'+$(this).attr("href").replace(/stamp/,lastup)+'" />').fadeIn("slow");
	}).delegate(".ajaxpost","submit",function(e){
		e.preventDefault();
		$.post($(this).attr("action"), $(this).serialize() ,function(data){ 
			$("#container").html(data).fadeTo("slow", 1);
		});
	}).delegate(".xv a","click",function(e){
		e.preventDefault();
		analytics("v/"+$(".xvq").data("q")+"/"+$(this).data("a"));
		cookie.set("vote_"+$(".xvq").data("q"),1,300000000);
		$(".xv").slideUp();
	}).delegate(".ad a","click",function(e){
		var url=$(this).attr("href");
		var t=$(this).data("id");
		if (t) {
			if ($(this).data("url")) url=$(this).data("url"); else $(this).data("url",url);
		}
		analytics(url);
		if (t) $(this).attr("href","//re.aclick.com/"+t+url);	
	});
	/*
	Page Customize
	delegate("th","dblclick",function(e){
		var id=$(this).parents('table').attr("id")
		if(undefined==id) return;
		cookie.set("hd_"+id,((cookie.get("hd_"+id)==1)?"":1),0);
		customize_page();
	}).
	customize_page();
	*/
});
function customize_page(){
	if(!document.cookie) return;
	$(".custom").removeClass("hide custom");
	$("table").each(function(){
		var tmp=$(this);
		if (undefined!=tmp.attr("id")&&cookie.get("hd_"+tmp.attr("id"))==1){
			tmp.find("tr").not("tr:first").addClass("hide custom");
			tmp.nextUntil("table",".counter").addClass("hide custom");
		}		
	});
}
function pageUpdate(force_update){
	/*		Prevent update on time machine		*/
	if (prevent_update) return;
	if ((timestamp()-page_loaded_on)>200000000) { // 2 Days passed since page opened
		analytics('e/2days');
		location.reload(true);
	}
	var since_update=timestamp() - lastup - pg_countdown * 1000;
	
	if (since_update<0&&!force_update) return pgUpTime(5); // Too early to update
	
	if (!focus) {	
		if ((timestamp()-activity)>(3*1000*pg_countdown)) {  // If page is not focused and 3 times of countdown passed
			is_active=0;
			if ((timestamp()-activity)<(4*1000*pg_countdown)) analytics('e/inact'); // Just report inactivity once after inactivation and slept afterward
			//else analytics('e/slept');
			return pgUpTime(pg_countdown*15); // For each 15 times of countdown, alert google analytics, google report last 30 minutes as active
		}
		//analytics('e/key'); // Not focused but due to key press activity refresh data
	}
	is_active=1;
	if (aj_req) return pgUpTime(5); // Previous ajax request is not completed
	var type=((since_update>pg_countdown*1000*1.5)?'f':'s'); // Make decision to send request to full json or semi json
	if (force_update) type='f'; // On forced update use full json file
	var json_type=$("#type").val();
	return page_ajax_call("/ajax/"+json_type +type+ ".json?" + lastup).done(function(data) {
		lastup = timestamp();
		if (data['pg_countdown']) pg_countdown=data['pg_countdown'];
		$(".dynamic").data("time",pg_countdown);
		if (data['rev']) if (_rev != data['rev']) location.reload(true);
		if (type!="s" || --google_pageview<1){
			if (type=="s") google_pageview=5; // Count 1/5 of normal pageviews
			analytics(json_type+page_type+"/"+type);
		}
		pgUpTime(pg_countdown);			
	}).fail(function(jqXHR, textStatus) {				
		pgUpTime(5);		
	}).always(function(){	
		$(".dynamic").fadeIn();
	});
}
function page_ajax_call(req_url){
	var tmp;
	aj_req=1;
	return $.ajax({ //Return ajax request to use done/always/fail
		url: req_url,
		type: "GET",
		timeout: 7000,
		dataType: "JSON"
	}).done(function(data) {
		pageUpdate.failed=0;
		$(".notice .failed").remove();
		$.each(data,function(key,val){
			if(key.charAt(0)=='d') {				
				tmp=key.split('-');				
				$("."+tmp[0]).data(tmp[1],val);
			} else $("."+key).each(function(){
				var field=$(this);
				if (key.charAt(0)=='t') {
					field.data("time",val);
				} else {
					if (field.text()!=val){
						if (key.charAt(0)=='c') {							
							if (val.indexOf("-")!=-1) var cl="neg";
							else if (/([1-9]+)/.test(val)) var cl="pos";
							field.removeClass("neg pos").addClass(cl);
						}
						if (key.charAt(0)=='s' && field.parent().is("tr")){
							tmp=(parse_float(val)<parse_float(field.text()))?"chdn":"chup";
							field.parent().addClass(tmp);
							/* Archive prices module */
							if (typeof price_update == 'function' ) { 
								price_update(field);
							}
						}
						field.html(val);
					}
				}
			});
		});	
		setTimeout(function() { $(".chdn,.chup").removeClass("chup chdn");}, 4000);
		if(typeof page_update_trigger == 'function') page_update_trigger(data);
	}).fail(function(jqXHR, textStatus) {		
		pageUpdate.failed=pageUpdate.failed+1||0;		
		if ((pageUpdate.failed%30)==0) analytics('f/'+textStatus); //Report first failure and 30*5 after it
		$(".notice").html("<div class='failed'>"+l.connection_problem+"</div>");		
	}).always(function(){	
		aj_req=0;		
	});	
}
function parse_float(val){
	if (val!=undefined) {
		val=val.replace(/,/gi,'');
		var tmp=/([.,0-9]+)/;
		if (tmp.test(val)){
			return tmp.exec(val)[0];
		} 
	}
	return '';
	//return parseFloat(val.replace(/,/gi,''));
}
function timestamp(){
	return new Date().getTime();
}
function analytics(c){
	_gaq.push(['_trackPageview', c]);
}
function pgUpTime(t){
	clearTimeout(pg_timer);
	pg_timer=setTimeout(pageUpdate, t*1000);
}
function run_every_second(){
	if(typeof every_second_trigger == 'function')  every_second_trigger();
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
		str=day+l.day+l.comma;
		time=time-(day*60*60*24);
	}
	if (hour=Math.floor(time/(60*60))){
		str=str + hour + l.hour+l.comma;
		time=time-(hour*60*60);
	}
	if (minute=Math.floor(time/(60))){
		str=str + minute + l.minute+l.and;
		time=time-(minute*60);
	}
	return str+time+ l.second;
}
function addComma(nStr){
	var x,x1,x2,rgx;
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	rgx=/(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
/*		Cookie		*/
var cookie = {
	get:function(name){
		var tmp;
		if(document.cookie)
			if (tmp=document.cookie.match(new RegExp(name + '=([^;]+)')))
				return unescape(tmp[1]);
		return null;
	},
	set:function(name,value,expire){
		var expireDate = new Date ();
		expireDate.setTime(expireDate.getTime() + ((expire==0)?20000000000:expire));			
		return document.cookie = name + "=" + escape(value) +"; path=/; expires=" + ((value!="")?expireDate.toGMTString():-1);
	}
}
window['addComma'] = addComma;
/*		Google Analytics		*/
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

