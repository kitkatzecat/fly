<!DOCTYPE html>
<html>
<head>
<?php
include 'fileprocessor.php';
include 'constants.php';
?>
<style>
body {
	margin: 0px;
	cursor: zoom-in;
	color: #000000;
}
#image {
	width: 100%;
	height: 100%;
	max-width: 100%;
	max-height: 100%;
	transition: .2s ease-in-out width,.2s ease-in-out height,.2s ease-in-out max-width,.2s ease-in-out max-height;
	margin: 0 auto;
}
#error {
	padding: 3px;
	font-family: sans-serif;
}
</style>
<script>
function zoomIn() {
	var img = document.getElementById('image');
	var zoom = (parseInt(img.getAttribute('data-zoom'))+25);
	img.setAttribute('data-zoom',zoom);
	img.style.maxWidth = zoom+'%';
	img.style.width = zoom+'%';
	img.style.maxHeight = zoom+'%';
	img.style.height = zoom+'%';
	
	if (img.getAttribute('data-zoom') == '100') {
		window.parent.FitScrButton.toggleOn();
	}
	window.parent.ZoomOutButton.toggleOff();
}
function zoomOut() {
	var img = document.getElementById('image');
	var zoom = (parseInt(img.getAttribute('data-zoom'))-25);
	
	if (zoom > 24) {
		img.setAttribute('data-zoom',zoom);
		img.style.maxWidth = zoom+'%';
		img.style.width = zoom+'%';
		img.style.maxHeight = zoom+'%';
		img.style.height = zoom+'%';
		
		document.body.style.cursor = 'zoom-out';
		setTimeout(function(){document.body.style.cursor = 'zoom-in';},200);
		
		if (img.getAttribute('data-zoom') == '100') {
			window.parent.FitScrButton.toggleOn();
		}
		if (img.getAttribute('data-zoom') == '25') {
			window.parent.ZoomOutButton.toggleOn();
		}
	}
}
function zoomReset() {
	var img = document.getElementById('image');
	var zoom = 100;
	img.setAttribute('data-zoom',zoom);
	img.style.maxWidth = zoom+'%';
	img.style.width = zoom+'%';
	img.style.maxHeight = zoom+'%';
	img.style.height = zoom+'%';
	window.parent.ZoomOutButton.toggleOff();
}
function actualSize() {
	var img = document.getElementById('image');
	var zoom = 'auto';
	img.setAttribute('data-zoom','100');
	img.style.maxWidth = 'none';
	img.style.width = zoom;
	img.style.maxHeight = 'none';
	img.style.height = zoom;
	window.parent.ZoomOutButton.toggleOff();
}
function properties() {
	window.top.system.command('run:SprocketComputers.FileManager.Properties,file=<?php echo $_GET['file']; ?>');
}
</script>
</head>
<body onclick="window.parent.FitScrButton.toggleOff();window.parent.ActSizeButton.toggleOff();zoomIn();" oncontextmenu="window.parent.FitScrButton.toggleOff();window.parent.ActSizeButton.toggleOff();zoomOut();return false;">
<?php
if ($_GET['file']=='') {
	echo '<div id="error">No file specified</div>';
} else {
	$process = FlyFileStringProcessor($_GET['file']);
	if ($process) {
		if (in_array($process['extension'],['png','jpg','gif','bmp','svg'])) {
			if (file_exists($_GET['file'])) {
				$url = FlyConvertPathtoURL($_GET['file']);
				echo '<img data-zoom="100" id="image" src="'.$url.'"><script>window.parent.Fly.window.title.set(\'Photo Viewer - '.basename($_GET['file']).'\');setTimeout(function(){window.parent.FitScrButton.toggleOn();},500);window.parent.ActSizeButton.toggleOff();</script>';
			} else {
				echo '<div id="error">File does not exist: '.basename($_GET['file']).'</div>';
			}
		} else {
			echo '<div id="error">Unsupported format: '.$process['extension'].'</div>';
		}
	} else {
		echo '<div id="error">Unable to process file string: '.basename($_GET['file']).'</div>';
	}
}
?>
</div>
</body>
</html>