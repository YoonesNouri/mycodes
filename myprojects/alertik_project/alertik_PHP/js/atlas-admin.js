$(function(){
	$("#container").delegate("input","keyup change",function(e){ 
		box_number_edit($(this));
	}).delegate("#link_buttons a","click",function(e){ 
		e.preventDefault();
		var click=$(this).data("click");
		if (click){
			$("."+click).trigger('click');
			return;
		}
		var loc=$(this).data("loc");
		var price=$(this).html();
		if (!price||price==0) return;
		$('.'+loc).val(price);
		$('.'+loc).trigger('change');
	}).delegate(".buy,.sell","change keyup",function(e){ 
		calculate_spread($(this));
	}).delegate(".s_40,.b_40","change keyup",function(e){ 
		calculate_dollar_based();
	}).delegate(".add_spread","change keyup",function(e){ 
		calculate_dollar_based();
	}).delegate("#link_buttons","submit",function(e){
		e.preventDefault();
		$("#notice").slideUp("slow");
		$.post('save-exchange.php', $(this).serialize() ,function(data){ 
			$("#notice").html(data).slideDown("slow");
		});
	});
	calculate_dollar_based();
	$(".buy,.sell").each(function(){
		calculate_spread($(this));
	});
});
function box_number_edit(t){
	var price=parse_float(t.val());
	t.val(addComma(price));
	var previous_price=parse_float(t.data("price"));
	if (price==0) return;
	var change=price-previous_price;
	var change_percentage=Math.round(change*10000/price)/100;
	if (price==0) change_percentage=0;
	$("."+t.data('change')).html(addComma(change+" (%"+change_percentage+")")).removeClass("neg pos").addClass(number_sign(change));	
}
function calculate_dollar_based(){
	var sell=parse_float($(".s_40").val());
	var buy=parse_float($(".b_40").val());
	$(".dollar_buy").each(function(){
		var exchange=parse_float($(this).data('exchange'));
		if (exchange==0) return;
		var spread=parse_float($(".p_"+$(this).data('id')).val());
		var val=(buy/exchange)-spread;
		$(this).html(addComma(currency_rounding(val)));
	});
	$(".dollar_sell").each(function(){
		var exchange=parse_float($(this).data('exchange'));
		if (exchange==0) return;
		var spread=parse_float($(".p_"+$(this).data('id')).val());
		if (!spread)spread=0;
		var val=(sell/exchange)+spread;
		$(this).html(addComma(currency_rounding(val)));
	});
}
function currency_rounding(number){
	var add_zero=4-number.toString().split('.')[0].length;
	if (add_zero<1) return Math.round(number);
	var add_decimal=Math.pow(10,add_zero);
	return Math.round(number*add_decimal)/add_decimal;
}
function calculate_spread(t){
	var id=t.data('spread');
	var sell=parse_float($(".s_"+id).val());
	var buy=parse_float($(".b_"+id).val());
	var spread=sell-buy;
	var change_percentage=Math.round(spread*10000/sell)/100;
	if (sell==0)change_percentage=0;
	$(".sp_"+id).html(addComma(currency_rounding(spread))+" (%"+change_percentage+")").removeClass("neg pos").addClass(number_sign(spread));
}
function number_sign(number){
	if (number.toString().indexOf("-")!=-1) return "neg";
	else if (/([1-9]+)/.test(number.toString())) return "pos";
	return '';
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
function parse_float(val){
	if (val!=undefined&&val!="") {
		val=val.toString().replace(/,/gi,'');
		var tmp=/([.,0-9]+)/;
		if (tmp.test(val)){
			return tmp.exec(val)[0]*1;
		} 
	}
	return '';
}