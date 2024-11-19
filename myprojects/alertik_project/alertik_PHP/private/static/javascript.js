var focus=1;
$(function(){
	$("html").delegate("tr","click",function(e){
		$("tr").removeClass("tr_selected");
		$(this).addClass("tr_selected");
	});
	setInterval(system_status, 15000);
	$(window).focus(function() {
		focus=1;
	}).blur(function() {
		focus=0;
	});	
});


function first_in_view(el) {
	return ($(window).scrollTop()<el.offset().top);
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



function scroll_to(selector){
	$( 'html, body' ).animate( { scrollTop: selector.offset().top-150 }, "slow" ,function(){
		
	});
}


var html_slice = function (html, seperator){
    return html.split('<!-- '+seperator+' -->')[1];
};


function system_status(){
	if (!focus) return;
	$.get('/?server_status=1',function(data){
		$('#sys').html(data);
	});
}