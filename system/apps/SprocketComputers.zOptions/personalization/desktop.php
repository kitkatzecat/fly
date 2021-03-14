<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Personalization',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg',index:'personalization/index.php'},
	{name:'Manage desktop',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>desktop.svg',}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle">Manage your desktop</div>
<p><input class="FlyCSInlineIcon" type="checkbox" id="scroll"><label for="scroll">Enable desktop scrolling</label></p>
<p class="FlyCSHint">Allows the desktop to scroll to show more content when windows are placed at or below the bottom of the screen</p>

<p><br><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg" class="FlyCSInlineIcon">To change the size of the icons on your desktop, right click in an empty space and select the Icon View option from the menu.</p>

<div class="FlyCSSticky FlyCSStickyBottom" style="text-align:right;"><button><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button></div>

</body>
</html>