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
	{name:'Customize Jump',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg',index:'personalization/jump.php'},
	{name:'Choose items'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle">Choose items to show on the left column in the Jump menu</div>
<p class="FlyCSDescription">If checked, the following items will be shown on the left column in the Jump menu.</p>

<p><input class="FlyCSInlineIcon" type="checkbox" id="pins"><label for="pins"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pin.svg">Pins</label></p>
<p class="FlyCSHint">Shows items that have been pinned by you to your Jump menu.</p>

<p><input class="FlyCSInlineIcon" type="checkbox" id="recent"><label for="recent"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg">Recent</label></p>
<p class="FlyCSHint">Gives quick access to recent applications that you have used.</p>

<p><input class="FlyCSInlineIcon" type="checkbox" id="files"><label for="files"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>browse.svg">Files</label></p>
<p class="FlyCSHint">Provides links to commonly-used File Manager places such as Home, Documents, and Media.</p>

<p><input class="FlyCSInlineIcon" type="checkbox" checked disabled id="apps"><label for="apps"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Applications</label></p>
<p class="FlyCSHint">Displays an alphabetical list of all applications installed. This item cannot be hidden.</p>

<p><input class="FlyCSInlineIcon" type="checkbox" id="options"><label for="options"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg">Options</label></p>
<p class="FlyCSHint">Opens the Options application.</p>

<div class="FlyCSSticky FlyCSStickyBottom" style="text-align:right;"><button onclick="Save()"><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button></div>
</body>
</html>