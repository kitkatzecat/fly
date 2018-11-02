<?php
if (in_array($_GET['customizer'],['yes','true','on'])) {
	echo '<script>window.location="customizer?'.$_SERVER['QUERY_STRING'].'";</script>';
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
function onload() {
	Fly.toolbar.init();
	Fly.toolbar.add('','back',back,'<?php echo FLY_ICONS_URL; ?>arrow-left.svg');
	Fly.toolbar.add.divider('div1');
	Fly.toolbar.add('Home','home',home,'<?php echo FLY_ICONS_URL; ?>home.svg');
	Fly.toolbar.add('View All','icon-view',iconView,'<?php echo FLY_ICONS_URL; ?>index.svg');
	
	<?php
	if (!$_GET['page']=='') {
		echo 'document.getElementById("Display-Frame").src="applets/'.$_GET['page'].'.php?'.$_SERVER['QUERY_STRING'].'"';
	}
	?>
}
function home() {
	document.getElementById('Display-Frame').src = 'categories.php';
}
function back() {
	document.getElementById('Display-Frame').contentWindow.history.go(-1);
}
function iconView() {
	document.getElementById('Display-Frame').src = 'all.php';
}
Fly.control.fileBrowser.setFile = function(id,path,basename,url,extension,icon) {
	document.getElementById('Display-Frame').contentWindow.Fly.control.fileBrowser.setFile(id,path,basename,url,extension,icon);
}
Fly.control.applicationChooser.setApplication = function(id,app,name,publisher,icon) {
	document.getElementById('Display-Frame').contentWindow.Fly.control.applicationChooser.setApplication(id,app,name,publisher,icon);
}
</script>
</head>
<body onload="onload()">

<iframe allowtransparency="true" id="Display-Frame" frameborder="0" scrolling="yes" style="transition:left .2s ease-in-out,width .2s ease-in-out;position:absolute;top:34px;left:0px;width:640px;height:446px;background-color:#FFFFFF;" src="categories.php"></iframe>

</body>
</html>