<?php
	$title=ucfirst($type).' CFG Editor';
	$js=array('local.raw-editor.js','diff.js');
?>
<h1><?= $title ?></h1>
<div id="list">
	<input id="search_list" placeholder="Search All Data" size="50">
	<table>
		<!-- search_results -->
		<tr>
			<th>Name</th>
			<th>Data</th>
			<th>Edit</th>
			<th>History</th>
		</tr>
		<?php if (is_array($items)) foreach ($items as $property){ ?>
			<tr class="<?= (($count++%2)?'even':'').(($property['disabled'])?' alert-error':'') ?>">
				<td width="250px"><?= $property['name'] ?></td>
				<td width="260px"><?= $property['data'] ?></td>
				<td width="40px"><a href="raw-edit-cfg.php?name=<?= $property['name'] ?>">Edit</a></td>
				<td width="40px"><a href="history.php?id=<?= $property['name'] ?>&id_type=3" class="new_page">History</a></td>
			</tr>
		<?php } ?>
		<tr>
			<th></th>
			<th><a href="raw-edit-cfg.php">Add a new config data</a></th>
			<th><?= ($page_prev)?'<a href="" class="pager-btn" data-page="'.$page_prev.'">previous</a>':'previous' ?></th>
			<th><?= ($page_next)?'<a href="" class="pager-btn" data-page="'.$page_next.'">next</a>':'next' ?></th>
		</tr>		
		<!-- search_results -->
	</table>
</div>
<div id="list_edit">
</div>