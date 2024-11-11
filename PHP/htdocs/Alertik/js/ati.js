/*		Ati		*/
$(function(){
	/*$("#ime").delegate("a","click",function(e){ // Manual page update
		e.preventDefault();
		var tmp='';
		if ($("#ime_box").html().length<100){
			$.each($("#ime").data("m").split("|"),function(key,val){
				tmp=tmp+'<div class="frameholder '+val.toLowerCase()+'"><iframe frameborder="0" scrolling="no" class="imeframe" src="http://new.ime.co.ir/Futures_fix/Futures_MarketWatch_FIX_fa.aspx?Code='+val+'"></iframe></div>';
			});
			$("#ime_box").html(tmp).fadeIn("slow");
			$("#ime tr").removeClass("hide");
			$(this).html("حذف");
		} else if ($(this).hasClass("ime_disp")) {
			$("#ime_box").fadeOut("slow").html("");
			$("#ime tr:not(.th),#freespace").addClass("hide");
			$(this).html("نمایش");
		}
		
		if ($(this).parents("tr").data("pos")){
			if ($(this).data("ime_box")){ // Move to top
				$("#ime_box").insertAfter("#header");
				$("#freespace").insertAfter("#header");
			} else { // Move to bottom
				$("#ime_box").insertBefore("#footer");
				$("#freespace").insertBefore("#footer");
			}
		}
		if (!$(this).parents("tr").data("skip")){
			if ($(this).hasClass("selected"))return;			
			tmp=$(this).parents("tr").find(".selected").removeClass("selected").data();
			if (tmp) $.each(tmp,function(key,val){
				$("#"+key+",."+key).removeClass(val);
			});			
		} else {
			
			if ($(this).hasClass("selected")){
				$(this).removeClass("selected")
				$.each($(this).data(),function(key,val){
					$("#"+key+",."+key).removeClass(val);
				});	
				return;
			}
		}
		$(this).addClass("selected");
		$.each($(this).data(),function(key,val){
			$("#"+key+",."+key).addClass(val);
		});			
		
	});	*/
});

// Resizer + Draggable

/*
$('#ime').jqDrag();
(function ($) {
    $.fn.jqDrag = function (h) {
        return i(this, h, 'd');
    };
    $.fn.jqResize = function (h) {
        return i(this, h, 'r');
    };
    $.jqDnR = {
        dnr: {},
        e: 0,
        drag: function (v) {
            if (M.k == 'd') E.css({
                left: M.X + v.pageX - M.pX,
                top: M.Y + v.pageY - M.pY
            });
            else E.css({
                width: Math.max(v.pageX - M.pX + M.W, 0),
                height: Math.max(v.pageY - M.pY + M.H, 0)
            });
            return false;
        },
        stop: function () {
            E.css('opacity', M.o);
            $().unbind('mousemove', J.drag).unbind('mouseup', J.stop);
        }
    };
    var J = $.jqDnR,
        M = J.dnr,
        E = J.e,
        i = function (e, h, k) {
            return e.each(function () {
                h = (h) ? $(h, e) : e;
                h.bind('mousedown', {
                    e: e,
                    k: k
                }, function (v) {
                    var d = v.data,
                        p = {};
                    E = d.e;
                    M = {
                        X: p.left || f('left') || 0,
                        Y: p.top || f('top') || 0,
                        W: f('width') || E[0].scrollWidth || 0,
                        H: f('height') || E[0].scrollHeight || 0,
                        pX: v.pageX,
                        pY: v.pageY,
                        k: d.k,
                        o: E.css('opacity')
                    };
                    E.css({
                        opacity: 0.8
                    });
                    $().mousemove($.jqDnR.drag).mouseup($.jqDnR.stop);
                    return false;
                });
            });
        },
        f = function (k) {
            return parseInt(E.css(k)) || false;
        };
})(jQuery);
$('#ime').jqDrag();*/