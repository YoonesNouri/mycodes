$(function(){
	$("#container").delegate("#earning input,#earning select","keyup change",function(e){
		earning_update();
	}).delegate("#conversation_tool select,#conversation_tool input","keyup change",function(e){
		conversation_update($(this));
	}).delegate("#gold_tool select,#gold_tool input","keyup change",function(e){
		if ($(this).attr("id")=="gold_type") updateGoldFields($(this).attr("value"));
		else if ($(this).attr("id")=="gold_carat") _updateGoldThousand();
		else if ($(this).attr("id")=="gold_thousand") _updateGoldCarat();
		goldCalculateResult();		
	}).delegate("#custom_dollar","keyup change",function(e){
		update_custom_dollar();
	});
});
function earning_update(){
	var earn_type='';
	var earning_investment=$("#earning_investment");
	if (earning_investment.val()==""||earning_investment.val()==undefined) return;
	var invest=parse_float(earning_investment.val());
	if (isNaN(invest)) return;
	var earn=Math.round((earnings[$("#earning_type").val()][0]*invest)/earnings[$("#earning_type").val()][$("#earning_duration").val()])-invest;
	if (earn < 0) {earn_type="neg"; earn_name='زیان';}
	else {earn_type="pos";  earn_name='سود';}
	if (earn_type) $("#earning_conclusion").html('<span class="'+earn_type+'">'+addComma(Math.abs(earn))+' '+earn_name+'</span>');
	earning_investment.val(addComma(invest));
}
function conversation_update(tmp) {
	var base = 2,target = 1;
	if (!tmp || tmp.attr("id").indexOf('2')==-1 || tmp.attr("id")=="conv_currency_side2"){
		base = 1; 
		target = 2;		
	}
	if ($("#conv_amount_side"+base).val()=="") return;
	if (isNaN(base_amount=parse_float($("#conv_amount_side"+base).val()))) base_amount = 0;
	var tmp=((parse_float($(".s"+$("#conv_currency_side"+base).val()+":first").html())*base_amount)
		/parse_float($(".s"+$("#conv_currency_side"+target).val()+":first").html())).toFixed(2);
	$("#conv_amount_side"+target).val(addComma(tmp));
	$("#conv_amount_side"+base).val(addComma($("#conv_amount_side"+base).val().replace(/,/gi,'')));
}
function goldCalculateResult(){
	if (isNaN(weight = parse_float($('#gold_tool #gold_weight').val()))
		|| isNaN(fine = parseInt($('#gold_tool #gold_thousand').val()))) return;
	var value,currency;
	var target=$('#gold_tool #gold_target').val();
	if (target=="0_1") {
		value=parse_float($(".s"+target+":first").html())*fine*weight/(31.1034768*1000);
		currency="دلار";		
	} else if (target!=0) {
		value=parse_float($(".s"+target+":first").html())*fine*weight/750;	
		currency="ریال";
	} else {
		value=fine*weight/750;
		currency="گرم";	
	}
	$('#gold_tool .currency_disp').html(currency);	
	$('#gold_tool #gold_result').html(addComma(value.toFixed(2)));	
} 
function updateGoldFields(value){
	var x = value.split(',');
	if (x[0]=="manual"){
		$('#gold_tool input').removeAttr("disabled").removeAttr("readonly");
	}else if (x[1]){
		_updateGoldField(x[0],x[1]);
	}else {
		_updateGoldField(1,x[0]);
	}
	_updateGoldCarat();
}
function _updateGoldCarat(){
	var tmp = parseInt($('#gold_tool #gold_thousand').val());
	if (tmp>1000) {
		$('#gold_tool #gold_thousand').val(1000);
		tmp=1000;
	}
	tmp=fineToCarat(tmp);
	if (isNaN(tmp)) return;
	$('#gold_tool #gold_carat').val(tmp);
}
function _updateGoldThousand(){
	var tmp = caratToFine($('#gold_tool #gold_carat').val());
	if (isNaN(tmp)) return;
	if (tmp>1000) {
		tmp=1000;
		$('#gold_tool #gold_carat').val(24)
	}
	$('#gold_tool #gold_thousand').val(tmp);
}
function fineToCarat(i){return Math.round((parseInt(i)*24)/999);}
function caratToFine(i){return Math.ceil((parseInt(i)*999)/24);}
function _updateGoldField(weight,thousand) {
	$('#gold_tool #gold_thousand').val(thousand);
	$('#gold_tool #gold_weight').val(weight);
	$('#gold_tool #gold_carat,#gold_tool #gold_thousand,#gold_tool #gold_weight').attr("disabled","disabled").attr("readonly","readonly");	
}
function page_update_trigger(data){
	if (data['earnings']) earnings=data['earnings'];
	goldCalculateResult();
	earning_update();
	update_custom_dollar();
}
function update_custom_dollar(){
	var t=$("#custom_dollar");
	var dollar=parse_float(t.val());
	var selected=$("#ex0 tr:eq(1) th:eq(0)");
	if (dollar>1) {
	/*		Calculate custom values		*/
		t.val(addComma(dollar));		
		keep_val(selected);
		selected.html("نرخ محاسبه شده");
		$(".s1_3:first").html(addComma((parse_float($(".s0_1:first").html())*dollar*4.33/41.47).toFixed(0)));
		//$(".s1_3:first").html((parse_float($(".s0_1:first").html())*dollar*4.33/41.47).toFixed(0));
		$("#ex0 tr").each(function(){
			selected=$(this).find("td:eq(1)");
			var old_price=parse_float(keep_val(selected));
			if (old_price>0){
				var new_price=(dollar*(1/old_price));
				selected.html(addComma(new_price.toFixed(0)));

				selected=$(this).find("td:eq(2)");
				var str=keep_val(selected).split(" ");
				var change=parse_float(str[1]);
				if (change!=0) {
					selected.html(str[0]+" "+addComma(((change*new_price)/old_price).toFixed(0)));
				}
			}
		});
	} else {
		/*		Restore default values		*/
		$("#ex0 tr").find("td:eq(1),td:eq(2)").each(function(){
			restore_org($(this));
			restore_org(selected);
			$(".s1_3:first").html("-");
		});	
	}
}
function restore_org(t){	
	if (t.data("org")) t.html(t.data("org"));
}
function keep_val(t){
	var val=t.html();
	if (!t.data("org")) {
		t.data("org",val);
		return val;
	}
	return t.data("org");
}