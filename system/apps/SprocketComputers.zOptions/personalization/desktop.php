<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
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

<div class="title">Manage your desktop</div>
<p><input class="inline-icon" type="checkbox" id="scroll"><label for="scroll">Enable desktop scrolling</label></p>
<p class="hint">Allows the desktop to scroll to show more content when windows are placed at or below the bottom of the screen</p>
<div class="buttons"><button><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg">Save</button></div>

</body>
</html>