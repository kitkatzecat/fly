<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
?>
<style>
</style>
<script>
var OptionsTree = [
	{name:'Personalization',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg',index:'personalization/index.php'},
	{name:'Customize Jump',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle FlyCSSectionTitle">Customize your Jump menu<img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg" class="FlyCSSectionIcon"></div>
<p>The Jump menu is where you can jump in to anything you want to do in Fly. Here you can customize how your Jump menu looks.</p>

<p><a href="jumpleft.php"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>index.svg" class="FlyCSInlineIcon">Choose items to show on the left column in the Jump menu</a></p>
<p><a href="jumppins.php"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pin.svg" class="FlyCSInlineIcon">Add or remove pins from the Jump menu</a></p>
<p><br><a onclick="window.top.ui.jump.toggle()"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg">Open your Jump menu</a></p>

</body>
</html>