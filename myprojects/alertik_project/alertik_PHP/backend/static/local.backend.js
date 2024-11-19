$(function(){
	$("html").delegate("#pg_backend a","click",function(e){
		if ($(this).hasClass("blank")) return true;
		e.preventDefault();
		var selector=$(this);
		var id='id'+new Date().getTime();
		var text = "<h2>"+selector.text()+'</h2><div class="'+id+'"><div class="alert alert-info">Processing...</div></div>';
		$('.results').prepend(text);
		var url=selector.attr("href");
		if (url.indexOf('?') == -1) url += "?";
		url+="&"+id;
		$.get(url,function(data){ 
			$("."+id).html(data);
		});	
	});	
});