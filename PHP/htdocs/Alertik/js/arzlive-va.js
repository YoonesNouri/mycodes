/*		Advertisement		*/
$(function(){
	if ($("#money").val()!=undefined) ads_update();
	$("body").delegate("#ads input,#ads select","keyup change",function(e){
		ads_update();
	});		
});
function ads_update() {
	var max_price=6.5;
	var min_price=5.2;
	var view_discount=0.60;
	var money;
	if (isNaN(money=parseFloat($("#money").val().replace(/,/gi,'')))) money = 0;
	var costs=Math.ceil((max_price-money/14000000)*100)/100;
	if (costs<min_price) costs=min_price;
	var views_click=Math.floor(money/(costs*10000))*10000;
	var views=Math.floor(money/(costs*10000*view_discount))*10000;
	var clicks=Math.floor(views_click/(1000*parseFloat($("#category").val())))*10;
	var discount=Math.round(((max_price-costs)*100)/max_price) + "%";
	$("#views").val(addComma(views));
	$("#discount").val(discount);
	$("#clicks").val(addComma(clicks));
	if (money<1000) return;
	$("#money").val(addComma(money));
}
/*		Webmaster		*/
$(function(){
	$("body").delegate(".tools","change keyup",function(){
		render_table();
	}).delegate("#code","change keyup",function(){
		$("#preview").html($("#code").val());
		mx_run();
	});
	render_table();
});
function render_table(){
	var content;
	var row="even";
	$(".colors").each(function(){
		$(this).css("border-left",$(this).val()+" solid 20px");
	});
	content="<style type=\"text/css\">";
	if ($("#width_perc").val()) var width=$("#width_perc").val()+"%"; else var width=$("#width_px").val()+"px";
	if ($("#border-width").val()) var tmp="padding:"+$("#border-padding").val()+"px;direction:rtl;border:"+$("#border-width").val()+"px solid "+$("#border-color").val()+";"; else var tmp;
	content=content + "#arzlive table,#arzlive td,#arzlive th,#arzlive tr{padding:0;margin:0;border:0;white-space:nowrap;}";
	content=content + "#arzlive .mxv a{display:block;}";
	if ($("#height_px").val()) var height = "height:"+$("#height_px").val()+"px;"; else var height="";
	content=content + "#arzlive{display:none;width:"+width+";overflow:auto;"+height+"}#arzlive table{margin:0;"+tmp+"}";
	var padding;
	if ($("#td-padding").val()) padding=$("#td-padding").val(); else padding=5; 
	content=content + "#arzlive td,#arzlive th{padding:"+padding+"px;direction: rtl;text-align:right;}";
	var tmp;
	if ($("#time-color").val()) var tmp="color:"+$("#time-color").val()+";";
	if ($("#time-size").val()) var tmp=tmp+"size:"+$("#time-size").val()+"px;";
	if (tmp) content=content + "#arzlive #mx_update,#arzlive #mx_update {"+tmp+"}";
	content=content + "#arzlive table{width:"+width+";margin:0;"+tmp+"}";
	if ($("#header-backcolor").val()) var tmp = "background-color:"+$("#header-backcolor").val()+";"; else var tmp;
	content=content + "#arzlive th,#arzlive th a{font-size:"+$("#font_header").val()+"px;color:"+$("#head-color").val()+";"+tmp+"}";
	content=content + "#arzlive th a{padding:0;margin:0;text-decoration:none;background:none;}";
	content=content + "#arzlive td{font-size:"+$("#font_size").val()+"px;color:"+$("#font-color").val()+";}";
	content=content + "#arzlive .odd{background-color:"+$("#price-title-color").val()+";}#arzlive .odd td{font-size:"+$("#price-title-size").val()+"px;}";
	content=content + "#arzlive .even{background-color:"+$("#price-color").val()+";}#arzlive .even td{font-size:"+$("#price-size").val()+"px;}";
	content=content + "#arzlive .pos{color:"+$("#pos").val()+";}#arzlive .neg{color:"+$("#neg").val()+";}#arzlive .same{color:"+$("#same").val()+";}";
	content=content + "</style>";
	content=content + "<div id=\"arzlive\">";
	if ($("input:radio[name=type]:checked").val()=="h"){
		content=content + "<table><tr><th><a href=\"http://www.arzlive.com\" id=\"mx_lnk\">نرخ طلا و ارز</a></th><th>تغییر</th></tr>";
		$("#currencies input").each(function(){
			if ($(this).prop("checked")) {
				//if (row=="odd") row="even"; else row="odd";
				content=content+"<tr class=\"odd\"><td colspan=\"2\">"+$(this).parent().text()+"</td></tr>";
				content=content+"<tr class=\"even\"><td id=\"v"+$(this).attr("id")+"\"></td><td id=\"c"+$(this).attr("id")+"\"></td></tr>";
			}
		});
		content=content+"</table><span id=\"mx_update\"></span></div>";
	} else {
		var one,two;
		content=content + "<table class=\"mxv\">";
		one = "<th><a href=\"http://www.arzlive.com\" id=\"mx_lnk\">نرخ طلا و ارز</a></th>"
		two = "<td class=\""+row+"\" id=\"mx_update\"></td>"
		$("#currencies input").each(function(){
			if ($(this).prop("checked")) {
				if (row=="odd") row="even"; else row="odd";
				one=one+"<th>"+$(this).parent().text()+"</th>";
				two=two+"<td class=\""+row+"\"> <span id=\"v"+$(this).attr("id")+"\"></span><div id=\"c"+$(this).attr("id")+"\"></div></td>";
			}
		});
		content=content+"<tr>"+one+"</tr>";
		content=content+"<tr>"+two+"</tr>";		
		content=content+"</table></div>";
	}
	$("#code").val(content).trigger("change");	
}