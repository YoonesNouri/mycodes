<?php
	$title='Backend';
	$js=array('local.backend.js');
?>
<h1><?= $title ?></h1>
<div id='pg_backend'>
	<div class="links">
		<?php
		foreach ($commands as $val) {
			if ($val['type']=='section'){
				echo '<h2>'.$val['title'].'</h2>';
			} elseif ($val['url']) {
				echo '<a href="'.$val['url'].'" '.(($val['blank'])?'class="blank"':'').'>'.$val['title'].'</a>';
			}
		}
		?>
	</div>
	<div class="results">
		<?= backend_notice() ?>
		<?= ((file_exists('../logs/cron.txt'))?'<h2>Crontab Results</h2>'.file_get_contents('../logs/cron.txt'):''); ?>
	</div>
</div>