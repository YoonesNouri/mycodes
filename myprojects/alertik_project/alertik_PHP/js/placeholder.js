/*
	This function require jquery
	It runs differently if there are no placeholder support in browser (eg. ie)
	Add default to any input with default content
	v0.01
*/
$(function(){
	placeholder_init();
});
function placeholder_default_value_set(selected){
	if (selected.val()==""&&selected.attr("placeholder")) {
		if (selected.val(selected.attr("placeholder")).addClass("default").attr("type")=="password"){
			selected.after('<input type="text" class="default fake '+selected.attr("class")+'" value="'+selected.attr("placeholder")+'">').addClass("hide");
		}
	}
}
function placeholder_support(){
	return 'placeholder' in document.createElement('input');
}
function placeholder_init(){
	if (placeholder_support()) {
		$("input,textarea").each(function(){
			if ($(this).val()=="") $(this).addClass("default");
		});	
		$("body").delegate("input,textarea","focus",function(e){
			$(this).removeClass("default");
		}).delegate("input,textarea","blur",function(e){
			if ($(this).val()=="") {
				$(this).addClass("default");
			}
		});
	} else {
		$("input,textarea").each(function(){
			placeholder_default_value_set($(this));
		});
		$("body").delegate("input,textarea","blur",function(e){
			placeholder_default_value_set($(this));
		}).delegate("input,textarea","focus",function(e){
			if ($(this).val()==$(this).attr("placeholder")) {
				$(this).val("").removeClass("default");
			}
			if ($(this).hasClass("fake")){
				$(this).prev().removeClass("hide").focus();
				$(this).remove();
			}
		});
	}
}