$(function(){
	$("body").delegate(".ad a","click",function(e){
		var url=$(this).attr("href");
		var t=$(this).data("id");
		if (t) {
			if ($(this).data("url")) url=$(this).data("url"); else $(this).data("url",url);
		}
		analytics(url);
		if (t) $(this).attr("href","//re.aclick.com/"+t+url);	
	});
});
var adv_count=0;
function elementInViewport(el) {
    var win = $(window);
     
    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();
     
    var bounds = el.offset();
    bounds.right = bounds.left + el.outerWidth();
    bounds.bottom = bounds.top + el.outerHeight();
     
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
}
function getRandomInt (min, max) {
	return Math.floor(Math.random() * (max - min + 1)) + min;
}