function every_second_trigger(){

}

function page_update_trigger(data){
	conversation_update(0);
}
function conversation_update(i){
	if (i){
		$("#gld input").removeClass("act");
		i.addClass("act");
	}
	var val;
	var org_val=parse_float($("#gld .act").val());
	if (org_val>0)val=org_val; else return;
	var type=$("#gld .act").data("t");
	var gold_price=parse_float($(".s3_3:first").html());
	if (type){
		val=org_val*type;
	}else{
		val=org_val/gold_price;
	}
	$("#gld input:not(.act)").each(function(){
		var current_type=$(this).data("t");
		if (current_type){
			$(this).val(addComma(Math.round((val/current_type)*10000)/10000));
		} else {
			$(this).val(addComma(Math.round(val*gold_price/100)*100));
		}
	});
	$("#gld .act").val(addComma(org_val));
}
