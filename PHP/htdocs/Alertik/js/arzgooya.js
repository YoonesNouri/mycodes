jQuery(function(){
	jQuery("#conversation_tool select,#conversation_tool input").bind("keyup change",function(e){
		conversation_update();
	});
});
function conversation_update() {
	var base = 2,target = 1;
	var amount=parse_float(jQuery("#conv_input").val());
	if (isNaN(amount)) amount=0;
	//console.log();
	var tmp=((parse_float(jQuery("#v"+jQuery("#conv_currency_side1").val()+":first").html())*amount)/parse_float(jQuery("#v"+jQuery("#conv_currency_side2").val()+":first").html())).toFixed(2);
	jQuery("#con_result").html(addComma(tmp));
	jQuery("#conv_input").val(addComma(jQuery("#conv_input").val().replace(/,/gi,'')));
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