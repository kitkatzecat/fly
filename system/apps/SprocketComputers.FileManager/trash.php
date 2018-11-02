<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
function onload() {
	Fly.window.title.set('Trash');
	Fly.window.icon.set('<?php echo FLY_ICONS_URL;?>trash.svg');
	
	Fly.toolbar.init();
	Fly.toolbar.add('Empty Trash','emptytrash',EmptyTrash,'<?php echo FLY_ICONS_URL; ?>mark-x.svg');
	Fly.toolbar.add('Refresh','refresh',Refresh,'<?php echo FLY_ICONS_URL; ?>refresh.svg');

	Fly.toolbar.add('','views',Views,'<?php echo FLY_ICONS_URL; ?>search.svg','right');
	Fly.toolbar.add.divider('divider1','right');
	Fly.toolbar.add('Trash Options','options',Options,'<?php echo FLY_ICONS_URL; ?>options.svg','right');
}
function EmptyTrash() {
	
}
function Refresh() {
	
}
function Options() {
	window.top.system.command('run:SprocketComputers.Options');
}
function Views() {
	var FileList = document.getElementById("File-List-Frame");
	var ViewPane = document.getElementById("View-Pane-Frame");
	if (ViewPane.style.display == "none") {
		FileList.style.right = "160px";
		FileList.style.width = "480px";
		ViewPane.src = '';
		setTimeout(function(){document.getElementById("View-Pane-Frame").src = 'views.php';},200);
		setTimeout(function(){document.getElementById("View-Pane-Frame").style.display="block";},300);
		document.getElementById('FlyToolbarItem-views').toggle();
	} else {
		ViewPane.style.display = "none";
		FileList.style.right = "0px";
		FileList.style.width = "640px";
		document.getElementById('FlyToolbarItem-views').toggle();
	}
}
</script>
</head>
<body onload="onload()">
<iframe allowtransparency="true" frameborder="0" scrolling="yes" style="position:absolute;top:34px;right:0px;bottom:0px;width:160px;height:446px;display:none;" id="View-Pane-Frame" src=""></iframe>
<div style="position:absolute;top:34px;left:0px;right:0px;bottom:0px;background:#fff;transition:left .2s ease-in-out,width .2s ease-in-out;" id="File-List-Frame">
</div>

</body>
</html>