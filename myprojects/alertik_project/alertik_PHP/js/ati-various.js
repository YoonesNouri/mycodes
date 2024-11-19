/*		Advertisement		*/
$(function(){
	if ($("#money").val()!=undefined) ads_update();
	$("#container").delegate("#ads input,#ads select","keyup change",function(e){
		ads_update();
	});		
});
function ads_update() {
	var max_price=5.3;
	var min_price=4.2;
	var money;
	if (isNaN(money=parseFloat($("#money").val().replace(/,/gi,'')))) money = 0;
	var costs=Math.ceil((max_price-money/4600000)*100)/100;
	if (costs<min_price) costs=min_price;
	var views=Math.floor(money/(costs*10000))*10000;
	var clicks=Math.floor(views/(1000*parseFloat($("#category").val())))*10;
	var discount=Math.round(((max_price-costs)*100)/max_price) + "%";
	$("#views").val(addComma(views));
	$("#discount").val(discount);
	$("#clicks").val(addComma(clicks));
	if (money<1000) return;
	$("#money").val(addComma(money));
}