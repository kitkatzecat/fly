<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

$userXML = simpleXML_load_file(FLY_ROOT.'system/user.xml');
?>
<script>
function onload() {
	Fly.window.title.set('Theme Customizer');
	Fly.window.icon.set('system/apps/SprocketComputers.Options/icons/personalization.svg');
	Fly.window.size.set(320,480);
}
function menu() {
	var containers = document.getElementsByClassName('container');
	for (i = 0; i < containers.length; i++) { 
		containers[i].style.display = 'none';
	}
	var container = document.getElementById('select').value;
	document.getElementById('container-'+container).style.display = 'block';
}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	background-color: #ffffff;
	overflow: hidden;
}
.title {
	background-image: linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(231,231,231,1) 50%,rgba(215,215,215,1) 51%,rgba(236,236,236,1) 100%);
	font-weight: bold;
	padding: 6px;
	font-size: 12px;
}
#previewContainer {
	position: absolute;
	top: 24px;
	left: 0px;
	right: 0px;
	height: 180px;
	box-shadow: 0px 0px 16px rgba(0,0,0,0.8) inset;
	background-color: <?php echo $userXML->visual->theme->backgroundColor; ?>;
	background-image: url('<?php echo FLY_URL.$userXML->visual->theme->backgroundImage; ?>');
	background-size: cover;
}
.container {
	position: absolute;
	top: 228px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	overflow-y: auto;
	overflow-x: hidden;
	display: none;
	padding: 6px;
}
</style>
</head>
<body onload="onload()">

<div id="main">
<div class="title FlyUiText" style="position:absolute;top:0px;left:0px;right:0px;">Preview</div>
<div id="previewContainer"></div>
<select id="select" onchange="menu()" class="FlyUiText" style="border-left:none;border-right:none;border-top:none;border-radius:0px;position:absolute;top:204px;left:0px;height:24px;width:320px;font-size:12px;font-weight:bold;"><option value="main">Welcome</option><option>Colors</option><option value="about">About</option></select>

<div id="container-main" class="container FlyUiText" style="padding:24px;display:block;"><span style="font-weight:bold;">Welcome to Theme Customizer</span><br>Choose an option from the dropdown menu above to start customizing your theme.</div>
<div id="container-about" class="container FlyUiText"><span style="font-weight:bold;">About Theme Customizer</span><br>Version 1.0<br><br><span style="font-weight:bold;">Help</span><br>Use the menu above to choose a category to edit. When you change a property of the theme, the live preview above the menu bar will automatically update to preview your changes.<br>To apply your changes to the whole system, click the "Apply" button below. Some changes may require that you restart Fly to be applied correctly.<br>To save a custom theme to a file, or load a theme from a file, use the "Open" and "Save" buttons in the bottom left of the window.</div>

</div>

<button style="position:absolute;bottom:8px;left:10px;width:28px;padding-left:6px;"><img style="width:16px;height:16px;" src="<?php echo FLY_ICONS_URL.'folder.svg'; ?>"></button>
<button style="position:absolute;bottom:8px;left:42px;width:28px;padding-left:6px;"><img style="width:16px;height:16px;" src="<?php echo FLY_ICONS_URL.'save.svg'; ?>"></button>
<button style="position:absolute;bottom:8px;right:10px;min-width:100px;">Apply</button>
</body>
</html>