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
	{name:'Customize Jump',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg',}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle">Customize your Jump menu</div>
<p>Customizing your Jump menu can now be done directly from the menu itself!</p>
<p>To remove an item from your Jump menu, right click on it and select "Unpin from Jump".</p>
<p>To add an application to your Jump menu, right click on it on the All Applications list and select "Pin to Jump".</p>
<p>To add a file to your Jump menu, right click on it in File Manager and select "More" > "Pin to Jump".</p>
<p><a onclick="window.top.ui.jump.toggle()"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg">Open your Jump menu</a></p>
</body>
</html>