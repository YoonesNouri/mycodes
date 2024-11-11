<div class='reload_area row' id="header">
	<?=(($countdown)?'<div class="span2 counter dynamic" data-time="'.$countdown.'" ><a class=manual href="/"><img class="reload" src="/s/img/reload.png"></a> <span>57 ثانیه</span> تا بارگذاری مجدد</div>':'')?>
	<div class='span10 reloadborder'></div>
</div>
<div class="notice" id="notice"></div>
<div class='row'>
	<div class='span8'>
		<div class='row'>
			<div class='span8 table_title'>برابری ریال در برابر ارزهای رایج در بازار تهران</div>
		</div>
		<div class='row'>
			<div class='span8'>
				<div class='well'>
					<?= top_right($exchange,$sensored,1) ?>
					<div class='note'>* کلیه قیمت های ارائه شده بر اساس ریال می باشد.</div>
				</div>
			</div>
		</div>
		<div class='row'>
			<div class='span8 '>
				<div class='chart well' id="chart" data-dollar='<?=$dollar_chart?>' data-euro='<?=$euro_chart?>'></div>
			</div>
		</div>
	</div>
	<div class='span4'>
		<div class='row'>
			<div class='span4 table_title'>قیمت طلا و سکه در بازار تهران</div>
		</div>
		<div class='row'>
			<div class='span4'>
			<div class='well'><?= top_left($exchange,$sensored) ?></div>
			</div>
		</div>
		<div class='row'>
			<div class='span4'>
				<a targer='_blank'>
					<img class='banner' src='img/banner.png' />
				</a>
			</div>
		</div>
	</div>
</div> 

<div class='row'> 
	<div class='span6'>
		<div class='well'><?= bottom_left($exchange,$sensored) ?></div>
	</div>
	<div class='span6'>
		<div class='well'><?= bottom_right($exchange,$sensored) ?></div>
	</div>
</div> 

<div class='row clocks'>
	<div class='span12 well allclocks'>
		<?= analog_clock('تهران','Asia/Tehran') ?>
		<?= analog_clock('لندن','Europe/London') ?>
		<?= analog_clock('نیویورک','America/New_York') ?>
		<?= analog_clock('دوبی','Asia/Dubai') ?>
		<?= analog_clock('استانبول','Europe/Istanbul') ?>
		<div class='cls'></div>
	</div> 
</div>

</div>
</div>
<input type="hidden" id="type" value="i">
<?php
$title='صرافی اندیشه';

