<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="expires" content="0" />
<title><?= $title; ?></title>
<script src="//code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
<script src="//code.jquery.com/jquery-migrate-1.1.1.js" type="text/javascript"></script>
<script src="static/javascript.js" type="text/javascript"></script>
<?php
	if (is_array($js)) foreach($js as $j){
		?><script src="static/<?=$j?>" type="text/javascript"></script><?php
	}
?>
<link href="static/style.css" media="all" rel="stylesheet" type="text/css" />
</head><body>
<div id="header">
	<div id="sys"></div>
	<div id="nav">
		<ul>
			<li><a href="/">Home</a></li>
			<li><a href="set-diff.php">Difference Monitor</a></li>
			<li id="nclose"></li>
		</ul>
	</div>	
</div>
<br><br>
<div id="container">
<!--a-->