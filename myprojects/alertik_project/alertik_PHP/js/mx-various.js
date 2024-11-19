/*		Advertisement		*/
$(function(){
	if ($("#money").val()!=undefined) ads_update();
	$("#container").delegate("#ads input,#ads select","keyup change",function(e){
		ads_update();
	});		
});
function ads_update() {
	var max_price=5.1;
	var min_price=4.2;
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
/*		Archive		*/
$(function(){
	var archive_text;	
	$("#container").delegate("#archive #archive_get","click",function(e){
		archive_text=$("#result").html();
		e.preventDefault();
		var continue_run=true;
		$("#archive select").each(function(){
			if ($(this).val()==0) {
				if (continue_run) alert("لطفا مقادیر تاریخ و نرخ را به طور کامل انتخاب نمایید.");
				continue_run=false;
			}
		});
		if (continue_run) $.post("archive.php",$("input,select").serialize(),function(data){
			var parts=data.split("|");
			$("#remaining").html(parts[1]);
			$("#result").html(parts[0]);
		});
	});	
	$("#container").delegate("#archive select","change",function(e){
		$("#result").html(archive_text);
	});
	
});

/*		Diamond		*/
$(function(){
	$("#container").delegate("#diamond input,#diamond select","keyup change",function(e){
		diamond_update();
	})
});
function diamond_update(){
	var correct_range="";
	var weight=parseFloat($("#diamond_weight").val());
	if (weight>0) {} else return;
	$.each(diamonds,function (index,value){
		var tmp=parseFloat(value);
		if (tmp<=weight && weight < 6) correct_range = tmp;
	});
	if (correct_range) {
		tmp="#dia"+correct_range+"_"+$("#diamond_color").val()+"_"+$("#diamond_clarity").val();
		tmp=tmp.replace(/\./gi,'p');
		var price=parseInt($(tmp).text().replace(/,/gi,''));
		if (price>0){
			price=price*weight*10000*parseInt($("#diamond_number").val())			
		}
	}
	if (price>0) $("#diamond_price").text(addComma(Math.round(price)));
	else $("#diamond_price").text("قیمت یافت نشد");	
}

/*		Webmaster		*/
$(function(){
	$("#container").delegate(".tools","change keyup",function(){
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
	content=content + "#mazanex table,#mazanex td,#mazanex th,#mazanex tr{padding:0;margin:0;border:0;white-space:nowrap;}";
	content=content + "#mazanex .mxv a{display:block;}";
	if ($("#height_px").val()) var height = "height:"+$("#height_px").val()+"px;"; else var height="";
	content=content + "#mazanex{display:none;width:"+width+";overflow:auto;"+height+"}#mazanex table{margin:0;"+tmp+"}";
	var padding;
	if ($("#td-padding").val()) padding=$("#td-padding").val(); else padding=5; 
	content=content + "#mazanex td,#mazanex th{padding:"+padding+"px;direction: rtl;text-align:right;}";
	var tmp;
	if ($("#time-color").val()) var tmp="color:"+$("#time-color").val()+";";
	if ($("#time-size").val()) var tmp=tmp+"size:"+$("#time-size").val()+"px;";
	if (tmp) content=content + "#mazanex #mx_update,#mazanex #mx_update {"+tmp+"}";
	content=content + "#mazanex table{width:"+width+";margin:0;"+tmp+"}";
	if ($("#header-backcolor").val()) var tmp = "background-color:"+$("#header-backcolor").val()+";"; else var tmp;
	content=content + "#mazanex th,#mazanex th a{font-size:"+$("#font_header").val()+"px;color:"+$("#head-color").val()+";"+tmp+"}";
	content=content + "#mazanex th a{padding:0;margin:0;text-decoration:none;background:none;}";
	content=content + "#mazanex td{font-size:"+$("#font_size").val()+"px;color:"+$("#font-color").val()+";}";
	content=content + "#mazanex .odd{background-color:"+$("#price-title-color").val()+";}#mazanex .odd td{font-size:"+$("#price-title-size").val()+"px;}";
	content=content + "#mazanex .even{background-color:"+$("#price-color").val()+";}#mazanex .even td{font-size:"+$("#price-size").val()+"px;}";
	content=content + "#mazanex .pos{color:"+$("#pos").val()+";}#mazanex .neg{color:"+$("#neg").val()+";}#mazanex .same{color:"+$("#same").val()+";}";
	content=content + "</style>";
	content=content + "<div id=\"mazanex\">";
	if ($("input:radio[name=type]:checked").val()=="h"){
		content=content + "<table><tr><th><a href=\"http://www.mazanex.com\" id=\"mx_lnk\">نرخ طلا و ارز</a></th><th>تغییر</th></tr>";
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
		one = "<th><a href=\"http://www.mazanex.com\" id=\"mx_lnk\">نرخ طلا و ارز</a></th>"
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