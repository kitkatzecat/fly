<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';

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

echo FlyLoadExtension('SprocketComputers.FileManager','FileChooser');
?>
<script>
function onload() {
	window.top.document.getElementById(Fly.window.id).window.content.style.backgroundColor = '#fff';
	window.top.document.getElementById(Fly.window.id).window.content.scrolling = 'auto';
	setTimeout(function() {document.getElementById('FileChooser').browse();},500);
}
</script>
</head>
<body onload="onload()">

<div id="FileChooser"></div>
<script>
Fly.extension.replace('FileChooser','SprocketComputers.FileManager','FileChooser');
document.getElementById('FileChooser').onchange = function() {
	Fly.window.title.set(document.getElementById('FileChooser').vars.basename);
	Fly.window.icon.set(document.getElementById('FileChooser').vars.icon);
	window.location.href = document.getElementById('FileChooser').vars.URL;
}
</script>

</body>
</html>