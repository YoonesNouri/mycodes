$(function(){
	form_submit_init();
	dashboard_triggers_tab();
	ui_delegate();
	tooltip_init();
	dashboard_actions_init();
	input_format_init();
	preview_box_update();
	trigger_box_update();
	order_page();
});
function order_page(){
	$("body").delegate("#price_table a","click",function(e){
		e.preventDefault();
		$("#payment_finalize #type").val($(this).data("type"));
		$("#payment_finalize #title").val($(this).data("title"));
		$("#payment_finalize #sms").val($(this).data("sms"));
		$("#payment_finalize #days").val($(this).data("days"));
		$("#payment_finalize #price").val($(this).data("price"));
		$("#price_table").slideUp("slow");
		$("#payment_finalize").slideDown("slow");
		//hide
	}).delegate("#payment_finalize #choose_another","click",function(e){
		e.preventDefault();
		$("#price_table").slideDown("slow");
		$("#payment_finalize").slideUp("slow");
	}).delegate("#payment_finalize form","submit",function(e){
		e.preventDefault();
		$("#payment_finalize").slideUp("slow");
		$("#payment_finalize .alert_box").html('');
		$.ajax({ 
			type: "POST",
			dataType: "JSON",
			data: $("#payment_finalize input,#payment_finalize select").serialize()
		}).done(function(data) {
			if (data["redirect"]){
				$("#content").append('<div class="alert alert-info">در حال انتقال خودکار شما به درگاه بانک هستیم. در صورت عدم نمایش سایت بانک. روی '+'<a href="'+data["redirect"]+'">این نوشته</a> کلیک نمایید.'+'</div>');	
				window.location.href=data["redirect"];
			} else {
				$("#payment_finalize .alert_box").html(data['error']);	
				$("#payment_finalize").slideDown("slow");	
			}
		}).fail(function(jqXHR, textStatus) {		
			$("#payment_finalize .alert_box").html('<div class="alert alert-error">مشکلی در ارتباط اینترنت شما مانع ارسال اطلاعات میگردد. لطفا مجددا سعی نمایید.</div>');	
			$("#payment_finalize").slideDown("slow");			
		});			
	});
}
function input_format_init(){
	$("body").delegate(".number","keyup change",function(e){
		$(this).val(addComma($(this).val().replace(/,/gi,'')));
	});
}
var prev_req;
function preview_box_update(){
	$("body").delegate(".trigger_target input,.currency_addition","change keyup",function(e){
		if (typeof prev_req === 'object') prev_req.abort();	
		prev_req=$.ajax({ 
			type: "GET",
			url:"/preview.php",
			data: $("#trigger_edit input,#trigger_edit select,#trigger_edit textarea").serialize()
		}).done(function(data) {
			$("#preview").html(data);
		}).always(function(){	
			prev_req=0;
		});			
	});
}
function trigger_box_update(){
	$("body").delegate(".trigger_target input","change",function(e){
		$(".cur_prefix").html($(this).next().html());
		$(".cur_postfix").html($(this).data("postfix"));
	});
}
/*		Dashboard Functions		*/
function dashboard_actions_init(){
	$("body").delegate("#triggers_list .delete","click",function(e){
		e.preventDefault();
		if (typeof aj_req === 'object') aj_req.abort();	
		aj_req=$.ajax({ 
			type: "POST",
			dataType: "JSON",
			data: {"del": $(this).data("id")}
		}).done(function(data) {
			if (data["redirect"]){
				window.location.href="."+data["redirect"];
			} else {
				$.each(data,function(key,val){
					$(key).html(val);
				});
			}
		}).fail(function(jqXHR, textStatus) {		
			display_alert($(this).find(".submit"),"مشکلی در ارتباط اینترنت شما مانع ارسال اطلاعات میگردد. لطفا مجددا سعی نمایید.","error");		
		}).always(function(){	
			aj_req=0;
		});			
	}).delegate("#triggers_list .activate","click",function(e){
		e.preventDefault();
		if (typeof aj_req === 'object') aj_req.abort();	
		aj_req=$.ajax({ 
			type: "POST",
			dataType: "JSON",
			data: {"activate": $(this).data("id"),"action": $(this).data("action")}
		}).done(function(data) {			
			if (data["redirect"]){
				window.location.href="."+data["redirect"];
			} else {
				$.each(data,function(key,val){
					$(key).html(val);
				});
			}
		}).fail(function(jqXHR, textStatus) {		
			display_alert($(this).find(".submit"),"مشکلی در ارتباط اینترنت شما مانع ارسال اطلاعات میگردد. لطفا مجددا سعی نمایید.","error");		
		}).always(function(){	
			aj_req=0;
		});			
	});
}

/*		Trigger tab on the dashboard page		*/
function ui_delegate(){
	$("body").delegate(".command","click",function(e){
		e.preventDefault();
		var tmp=$(this).data("reveal");
		if (tmp!=undefined) {
			$("#"+tmp).slideDown();
			scroll_to("#"+tmp);
		}
	});
}
/*		Trigger tab on the dashboard page		*/
function dashboard_triggers_tab(){
	$("body").delegate(".trigger_type","click",function(e){
		e.preventDefault();
		$(".trigger_box").slideUp();
		$(".trigger_type").removeClass("selected");
		$("#"+$(this).data("type")+"_box").slideDown();
		$("#trigger_type").val($(this).data("type"));
		$(this).addClass("selected");
	});
	$("body").delegate(".currency_target","click",function(e){
		e.preventDefault();
		$(".currency_target_box").slideUp();
		$(".currency_target").removeClass("selected");
		$("#"+$(this).data("type")+"_box").slideDown();
		$(this).addClass("selected");
	});
}


var aj_req;
function form_ajax_submit(select){
	if (typeof aj_req === 'object') aj_req.abort();	
	aj_req=$.ajax({ 
		type: "POST",
		dataType: "JSON",
		data: select.find("input,select,textarea").serialize()
	}).done(function(data) {
		//analytics(get_str);
		if (data["error"]){			
			display_alert($(data["error"][0]),data["error"][1],"error");
		} else if (data["redirect"]){
			window.location.href="."+data["redirect"];
		} else {
			$.each(data,function(key,val){
				$(key).html(val);
			});
			$("#trigger_add_wrapper").slideUp();
			scroll_to(".alert_box:first");
		}	
	}).fail(function(jqXHR, textStatus) {		
		display_alert(select.find(".submit"),"مشکلی در ارتباط اینترنت شما مانع ارسال اطلاعات میگردد. لطفا مجددا سعی نمایید.","error");
		//analytics('f/'+textStatus);			
	}).always(function(){	
		aj_req=0;
	});	
}
function scroll_to(where,focus){
	$( 'html, body' ).animate( { scrollTop: $(where).offset().top-10 }, "slow" ,function(){
		if (focus!=undefined) $(focus).focus();
	});	
}
function remove_alerts(){
	$(".alert_box").html("");
	$(".error").removeClass("error");
}
function display_alert(location,msg,type,context){
	if (type != undefined) type = " alert-"+type; else type = "";
	location.addClass("error").parents("form").find(".alert_box").prepend('<div class="alert'+type+'">'+msg+'</div>');
	location.focus();
	return false;
}
function is_email(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}
function is_phone(phone_no) {
    var pattern = new RegExp(/^\d{11,11}$/i);
    return pattern.test(phone_no);
}

/*		Form Interaction and Validator		*/
function form_submit_init(){	
	$("body").delegate("form","submit",function(e){
		remove_alerts();
		form_submit_init.validated=true;
		$(this).find("input,textarea,select").each(function(){
			if ($(this).attr("required")&&($(this).val()==""||$(this).val()==$(this).attr("placeholder"))){
				if ($(this).attr("placeholder")){
					var tmp=$(this).attr("placeholder").split(" (");
					display_alert($(this),"”"+tmp[0]+"“ وارد نشده است.","error");
				} else {
					alert("اطلاعات به صورت کامل وارد نشده است.");
				}
				return form_submit_init.validated=false;
			}
		});
		if (form_submit_init.validated &&$(this).data("validator")){
			if (validator[$(this).data("validator")]) validator[$(this).data("validator")]($(this));
		}
		if (!form_submit_init.validated) {
			return false;
		}
		if ($(this).hasClass("ajax")){
			e.preventDefault();
			analytics('trigger');
			form_ajax_submit($(this));
		}
		if ($(this).hasClass("ajaxpost")){
			e.preventDefault();
			var select=$(this);
			analytics(select.data("response"));
			$.post($(this).attr("action"), $(this).serialize() ,function(data){ 
				if (data=='<!--refresh-->') location.reload();
				$(select.data("response")).html(data);
			});
		}
	});
}

/*		Custom Form Validators		*/
var validator = {
	register: function(e){
		if (!is_email(e.find(".email").val())){
			display_alert(e.find(".email"),"آدرس ایمیل اشتباه می باشد.","error");
			form_submit_init.validated=false;
		} else if (!is_phone(e.find(".phone_no").val())){
			display_alert(e.find(".phone_no"),"شماره تلفن اشتباه می باشد.","error");
			form_submit_init.validated=false;
		} else if (e.find(".pass").val()!=e.find(".repass").val()) {
			display_alert(e.find(".pass"),"کلمه عبور و تکرار کلمه عبور یکی نیستند.","error");
			form_submit_init.validated=false;
		} else if (e.find(".pass").val().length < 5 || e.find(".pass").val().length > 16 ) {
			display_alert(e.find(".pass"),"کلمه عبور باید بین 5 تا 16 حرف باشد.","error");
			form_submit_init.validated=false;
		} 
	},	
	login: function(e){
		if (!is_phone(e.find(".phone_no").val())){
			display_alert(e.find(".phone_no"),"شماره تلفن اشتباه می باشد.","error");
			form_submit_init.validated=false;
		} else if (e.find(".pass").val().length < 5 || e.find(".pass").val().length > 16 ) {
			display_alert(e.find(".pass"),"کلمه عبور باید بین 5 تا 16 حرف باشد.","error");
			form_submit_init.validated=false;
		} 	
	},
	trigger_save: function(e){
		if (!e.find("#trigger_type").val()){
			form_submit_init.validated=false;
			display_alert($(".trigger_box_wrapper"),e.find("#trigger_type").data("validator-data"),"error");
		}
	}
};
/*		Tooltip Init		*/
function tooltip_init(){
	xOffset = 10;
	yOffset = 20;		
	$(".tip").hover(function(e){											  
		$("body").append("<p id='tip'>"+ $(this).data("tip") +"</p>");
		$("#tip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px")
			.fadeIn("fast");		
    },
	function(){
		$("#tip").remove();
    });	
	$(".tip").mousemove(function(e){
		$("#tip")
			.css("top",(e.pageY - xOffset) + "px")
			.css("left",(e.pageX + yOffset) + "px");
	});			
}

function addComma(nStr){
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}

/*
	Hide and display text on demand
	Samples
		Show <span class="hide">me</span><a href="#" class="display_more">Display more</a>
		Show <span class="hide ssss">me</span><a href="#" class="display_more" data-target=".ssss">Display more</a>
	v0.01
*/
$(function(){
	$("body").delegate(".display_more","click",function (e){
		e.preventDefault();
		var target=$(this).data("target");
		if (target) $(target).slideDown("slow"); else $(this).prev().removeClass("hide");
		$(this).remove();
	});
});

/*		Google Analytics		*/
function analytics(c){
	_gaq.push(['_trackPageview', c]);
}
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);