<?php
if (!empty($_GET['file'])) {
	goto download;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

echo FlyLoadExtension('SprocketComputers.FileManager','LocationChooser');
?>
<script>
function onload() {
	Fly.extension.replace('SaveDialog','SprocketComputers.FileManager','LocationChooser');
	document.getElementById('SaveDialog').onchange = function() {
		document.getElementById('downloadbutton').innerHTML = 'Save in <img src="'+this.vars.icon+'" style="width:16px;height:16px;vertical-align:middle;margin-right:4px;margin-left:6px;pointer-events:none;">'+this.vars.basename;
	}
}
</script>
<style>
.main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 6px;
	background-color: #fff;
}
.button {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
</style>
</head>
<body onload="onload()">
<div id="SaveDialog" style="display:none;"></div>

<div class="main">
<input type="text" onkeypress="eva(event)" id="runbox" style="width:100%;box-sizing:border-box;font-family:Consolas,monospace;margin-bottom:6px;" placeholder="URL"><br>
<button id="downloadbutton" style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;width:100%;box-sizing:border-box;text-align:left;" onclick="document.getElementById('SaveDialog').browse();">Save in<img src="<?php echo FLY_ICONS_URL; ?>computer.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:4px;margin-left:6px;pointer-events:none;">Computer</button>
</div>
<button class="button"><img src="downloader.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:4px;pointer-events:none;">Download</button>

</body>
</html>
<?php
exit;

download:
exit;
?>