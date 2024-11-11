/*		Date field		*/
$(function(){
	$("html").delegate(".date-field","keyup",function(e){
		var selector=$(this);
		$.get("entry-timestamp.php", {date_str:$(this).val()} ,function(date){ 
			selector.prev().val(date);
		});	
	}).delegate(".image-field img","click",function(e){
		var selector=$(this);
		var parent=$(this).parents(".image-field");
		$.post("entry-image.php", {"delete":selector.data("id"),"json":parent.find("textarea").html()} ,function(data){ 
			data=$.parseJSON(data);
			parent.find("textarea").html(data['textarea']);
			parent.find(".images").html(data['images']);
		});	
	}).delegate(".image-field .image-upload","click",function(e){
		e.preventDefault();
		var msg='';
		var selector=$(this);
		var parent=$(this).parents(".image-field");
		parent.find('.alert-box').html("");
		var files=parent.find(".files").get(0).files;		
		var upload_files = new FormData();
		$.each(files,function(key,val){
			if (250000<val.size) msg=msg+'<div class="alert alert-error">'+val.name+' is bigger than 250KB.</div>';
			if (1500>val.size) msg=msg+'<div class="alert alert-error">'+val.name+' is really small.</div>';
			upload_files.append(val.name, val);
		});
		upload_files.append("json", parent.find("textarea").html());
		upload_files.append("entity_id", $("#entity_id").val());
		if (msg){
			parent.find('.alert-box').html(msg);
			return;
		}
		
		$.ajax({
			url:"entry-image.php",
			data:upload_files,
			processData: false,
			contentType: false,
			type: 'POST',
			success:function(data){ 
				data=$.parseJSON(data);
				parent.find('.alert-box').html(data);
				parent.find("textarea").html(data['textarea']);
				parent.find(".images").html(data['images']);
				parent.find(".alert-box").html(data['msg']);
			}
		});
	});	
});