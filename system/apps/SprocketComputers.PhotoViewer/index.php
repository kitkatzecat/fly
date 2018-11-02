<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Actionbar.php';

echo FlyLoadExtension('SprocketComputers.FileManager','FileChooser');
?>
<script>
var Menubar;
var Toolbar;
var ZoomInButton;
var ZoomOutButton;
var FitScrButton;
var ActSizeButton;
function onload() {
	/*
	Fly.toolbar.init();
	Fly.toolbar.add('Open File','openfile',open,'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg');
	Fly.toolbar.add.divider();
	Fly.toolbar.add('Properties','properties',properties,'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg')
	Fly.toolbar.add.divider();
	Fly.toolbar.add('Zoom In','zoomin',zoomIn,'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg');
	Fly.toolbar.add('Zoom Out','zoomout',zoomOut,'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-minus.svg');
	Fly.toolbar.add.divider();
	Fly.toolbar.add('Fit to Screen','zoomreset',zoomReset,'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg');
	Fly.toolbar.add('Actual Size','actualsize',actualSize,'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right-up.svg');
	*/
	
	Menubar = new Fly.actionbar();
	Menubar.style.position = 'absolute';
	Menubar.style.left = '0px';
	Menubar.style.width = 'auto';
	Menubar.style.bottom = '0px';
	
	Menubar.add({text:'File',type:'dropdown',menu:[
		['Open',open,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg'}],
		['Open URL',openurl,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>earth.svg'}],
		[''],
		['Properties',properties,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'}],
		[''],
		['Close',Fly.window.close,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]
	]});
	Menubar.add({text:'View',type:'dropdown',menu:[
		['Zoom In',zoomIn,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg'}],
		['Zoom Out',zoomOut,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-minus.svg'}],
		[''],
		['Fit to Screen',zoomReset,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-in.svg'}],
		['Actual Size',actualSize,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-out.svg'}]
	]});
	
	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.right = '0px';
	Toolbar.style.width = 'auto';
	Toolbar.style.bottom = '0px';
	
	ZoomInButton = Toolbar.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg',action:zoomIn});
	ZoomOutButton = Toolbar.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-minus.svg',action:zoomOut});
	Toolbar.add({type:'divider'});
	FitScrButton = Toolbar.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-in.svg',action:zoomReset});
	ActSizeButton = Toolbar.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-out.svg',action:actualSize});
	
	document.body.appendChild(Menubar);
	document.body.appendChild(Toolbar);
}
function open() {
	document.getElementById('OpenBrowser').browse();
}
function openurl() {
	Fly.control.input('Open URL','Enter the URL for Photo Viewer to open.','Photo Viewer - Open URL','<?php echo FLY_ICONS_URL; ?>earth.svg',function(){});
}
function zoomIn() {
	FitScrButton.toggleOff();
	ActSizeButton.toggleOff();

	frame = document.getElementById('Display-Frame');
	frame.contentWindow.zoomIn();
}
function zoomOut() {
	FitScrButton.toggleOff();
	ActSizeButton.toggleOff();

	frame = document.getElementById('Display-Frame');
	frame.contentWindow.zoomOut();
}
function zoomReset() {
	FitScrButton.toggleOn();
	ActSizeButton.toggleOff();

	frame = document.getElementById('Display-Frame');
	frame.contentWindow.zoomReset();
}
function actualSize() {
	FitScrButton.toggleOff();
	ActSizeButton.toggleOn();

	frame = document.getElementById('Display-Frame');
	frame.contentWindow.actualSize();
}
function properties() {
	frame = document.getElementById('Display-Frame');
	frame.contentWindow.properties();
}
</script>
<style>
#Display-Frame {
	position: absolute;
	bottom: 36px;
	left: 0px;
	width: 100%;
	height: calc(100% - 36px);
	background: #ffffff;
}
</style>
</head>
<body onload="onload()">
<div id="OpenBrowser" style="display:none;"></div><script>Fly.extension.replace('OpenBrowser','SprocketComputers.FileManager','FileChooser');</script>

<script>
document.getElementById('OpenBrowser').onchange = function() {
	var browser = document.getElementById('OpenBrowser');
	ZoomOutButton.toggleOff();
	document.getElementById('Display-Frame').src= 'view.php?file='+browser.vars.path;
}
</script>


<iframe allowtransparency="true" id="Display-Frame" frameborder="0" scrolling="yes" src="view.php?file=<?php echo $_GET['file']; ?>"></iframe>
</body>
</html>