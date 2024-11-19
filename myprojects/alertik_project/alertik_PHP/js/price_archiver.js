function price_update(field){
	var row=field.parents('tr');
	if (row.length==0) return;
	var updated=row.find('.fa').html();
	if (!updated) return;
	var price=field.html();
	//var change=field.next().html();
	var archive=row.data('archive');
	if (archive == undefined) var archive = [];
	if (archive.unshift(price+' در '+updated)>10) archive.pop();
	row.data('archive',archive);
	render_tip_data(row,archive);
}
function render_tip_data(row,data){
	row.data("tip","قیمت های قبلی<br>"+data.join("<br>"));
	if (row.hasClass("tip")){} else{
		row.addClass("tip");
	}
}