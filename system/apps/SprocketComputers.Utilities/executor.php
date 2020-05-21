<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.FileProcessor.php';
include 'Fly.File.php';

if ($_GET['file'] !== '') {
	$_GET['file'] = FlyVarsReplace($_GET['file']);
	if (file_exists($_GET['file']) && !is_dir($_GET['file'])) {
		$process = FlyFileStringProcessor($_GET['file']);
		echo '<script>
		window.top.document.getElementById(Fly.window.id).window.content.style.backgroundColor = \'#fff\';
		window.top.document.getElementById(Fly.window.id).window.content.scrolling = \'auto\';
		Fly.window.title.set(\''.basename($_GET['file']).'\');
		Fly.window.icon.set(\''.$process['icon'].'\');
		window.location.href = \''.FlyConvertPathtoURL($_GET['file']).'\';</script>';
	}
}

?>
<script>
function onload() {
	window.top.document.getElementById(Fly.window.id).window.content.style.backgroundColor = '#fff';
	window.top.document.getElementById(Fly.window.id).window.content.scrolling = 'auto';
	setTimeout(load,500);
}
function load() {
	Fly.file.get(function(file) {
			Fly.window.title.set(file.fname);
			Fly.window.icon.set(file.icon);
			window.location.href = file.URL;
	});
}
</script>
<style>
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 18px;
	padding-bottom: 16px;
	padding-left: 6%;
	padding-right: 6%;
}
img.button-image {
	width: 16px;
	height: 16px;
	vertical-align: middle;
	margin-right: 6px;
}
p.description {
	margin-top: -12px;
	padding-left: 6%;
	padding-right: 6%;
}
</style>
</head>
<body onload="onload()">
<div class="title">No file selected</div>
<p class="description"><button onclick="load()"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg" class="button-image">Open file</button></p>
</body>
</html>