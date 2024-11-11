<?php
$title='صفحه خبری';
?>
<div class="news">
<?php
foreach ($news as $new){
	$content=json_decode($new['content'],true);
	if ($new['days']) $time=$new['days'].' روز';
	elseif ($new['hours']) $time=$new['hours'].' ساعت';
	else $time=$new['mins'].' دقیقه';
	?>
	<div class="item">
		<span class="source<?= ($new['source_id']==9)?' kho':'' ?>"><?= ($new['source_id']==9)?'خبر آنلاین':'تابناک' ?></span> 
		<a href="<?= $content['link'] ?>"><?= $content['title'] ?></a> 
		<span class="addedon"><?= $time ?> قبل</span> 
	</div>
	<?php
}
?>
</div>