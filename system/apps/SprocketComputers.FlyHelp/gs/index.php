<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
?>

<script>
var OptionsTree = [
	{name:'Getting Started',icon:`${Fly.core['RESOURCE']['URL']['ICONS']}fly.svg`,index:'gs'}
];
</script>

</head>
<body class="FlyUiText FlyUiNoSelect">
<div class="FlyCSTitle FlyCSSectionTitle">Getting Started with Fly<img class="FlyCSSectionIcon" style="filter:contrast(150%) grayscale(100%);" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg">
</div>

<p><a href="whatsnew.php"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg" class="FlyCSInlineIcon">What's new in Fly Cerulean</a></p>
<p class="FlyCSHint">
	This article gives a brief overview of the changes made from the previous verson of Fly with text,
	images, and interactive examples.
</p>

<p><a onclick="window.top.system.command('run:SprocketComputers.Utilities.AboutFly');"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg" class="FlyCSInlineIcon">What version of Fly am I running?</a></p>

</body>
</html>