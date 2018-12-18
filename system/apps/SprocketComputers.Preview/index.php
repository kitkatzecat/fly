<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'fileprocessor.php';
if ($_GET['file'] == '') {
	goto no_file;
}
$_GET['file'] = FlyVarsReplace($_GET['file']);
$process = FlyFileStringProcessor($_GET['file']);

$filesize = intval(filesize($_GET['file']));
if ($filesize > 1000000) {
	$filesize = round($filesize/1000000,2).' MB';
} else if ($filesize > 1000 && $filesize < 10000000) {
	$filesize = round($filesize/1000).' KB';
} else {
	$filesize = $filesize.' bytes';
}
?>
<style>
#main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	overflow: auto;
	background-color: #fff;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	transition: left .2s ease-in-out;
}
#properties-pane {
	position: absolute;
	top: 34px;
	left: 0px;
	width: 160px;
	bottom: 0px;
	overflow: auto;
}
.head {
	font-size: 12px;
	font-weight: bold;
	margin-bottom: 4px;
}
.infoblock {
	margin-bottom: 8px;
}
</style>
<script>
function onload() {
	Fly.window.title.set('Preview - <?php echo basename($_GET['file']); ?>');
	
	Fly.toolbar.init();
	Fly.toolbar.add('Properties','properties',toggleProperties,'<?php echo FLY_ICONS_URL; ?>properties.svg');
}
function toggleProperties() {
	var propertiesPane = document.getElementById('properties-pane');
	var propertiesButton = document.getElementById('FlyToolbarItem-properties');
	var main = document.getElementById('main');
	
	propertiesButton.toggle();
	if (propertiesPane.style.display == 'none') {
		main.style.left = '160px';
		setTimeout(function(){document.getElementById('properties-pane').style.display='block';},300);
	} else {
		propertiesPane.style.display = 'none';
		main.style.left = '0px';
	}
}
</script>
</head>
<body onload="onload()">
<div id="properties-pane" style="display:none;" class="FlyUiTextHighlight">
	<img style="width:160px;height:160px;" src="<?php echo $process['icon']; ?>"><br>
	<div style="text-align:center;"><?php echo htmlentities($process['name']); ?></div><br>
	<div class="infoblock"><span class="head">Type</span><br><?php echo htmlentities($process['description']).' ('.$process['extension'].')'; ?></div>
	<div class="infoblock"><span class="head">Size</span><br><?php echo $filesize; ?></div>
	<div class="infoblock"><span class="head">Modified</span><br><?php echo date("M j, Y",filemtime($_GET['file'])); ?></div>
	<div class="infoblock"><span class="head">Created</span><br><?php echo date("M j, Y",filectime($_GET['file'])); ?></div>
	<div class="FlyUiToolbarItem" onclick="window.top.system.command('run:SprocketComputers.FileManager.Properties,file=<?php echo $process['file']; ?>')"><img src="<?php echo FLY_ICONS_URL ?>arrow-right.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">More Properties</div>
</div>
<?php
if (in_array($process['extension'],['png','bmp','gif','jpg','svg'])){
	echo '<div id="main" style="background-image:url(\''.$process["URL"].'\');"></div>';
} else {
	echo '<div id="main" style="padding:4px;font-size:12px;font-family:monospace;white-space:pre;">'.htmlentities(file_get_contents($_GET['file'])).'</div>';
}
if (in_array($process['extension'],['txt','htm','php','ini','log','js'])) {
	echo '
	<script>
	setTimeout(function(){Fly.toolbar.add(\'Word wrap\',\'word-wrap\',wordWrap,\''.FLY_ICONS_URL.'type/text.svg\');},1000);
	
	function wordWrap() {
		var main = document.getElementById(\'main\');
		var tbutton = document.getElementById(\'FlyToolbarItem-word-wrap\');
		if(main.style.whiteSpace == \'pre\') {
			main.style.whiteSpace = \'pre-wrap\';
			tbutton.toggle();
		} else {
			main.style.whiteSpace = \'pre\';
			tbutton.toggle();
		}
	}
	</script>
	';
}
?>
</body>
</html>
<?php
exit;
no_file:
echo '
<script>
window.top.shell.dialog("No file specified","No file was specified to preview.","Preview","'.WORKING_URL.'preview.svg");
Fly.window.close();
</script>
';
?>