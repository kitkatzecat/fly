<?php
include 'fly.php';
include 'registry.php';

if ($_GET['mode']=='ui') {
	goto ui;
}
if ($_GET['mode']=='icons') {
	goto icons;
}
if ($_GET['mode']=='previews') {
	goto previews;
}
if ($_GET['mode']=='extensions') {
	goto extensions;
}

ui:
$iconSize = FlyRegistryGet('IconSize');
if ($iconSize == 'list') {
	$id = 'ls';
} else if ($iconSize == 'slist') {
	$id = 'sl';
} else {
	$iconSize = intval($iconSize);
	if ($iconSize < 32) {
		$id = 'tn';
	}
	if ($iconSize > 31 && $iconSize < 48) {
		$id = 'sm';
	}
	if ($iconSize > 47 && $iconSize < 64) {
		$id = 'md';
	}
	if ($iconSize > 63 && $iconSize < 128) {
		$id = 'lg';
	}
	if ($iconSize > 127 && $iconSize < 256) {
		$id = 'xl';
	}
	if ($iconSize > 255) {
		$id = 'hg';
	}
}

$imagePreview = FlyRegistryGet('ImagePreview');
if (in_array($imagePreview,["true","on","yes"])) {
	$mg = 'on';
} else {
	$mg = 'off';
}

$fileExtensions = FlyRegistryGet('HideFileExtensions');
if (in_array($fileExtensions,["true","on","yes"])) {
	$ex = 'hide';
} else {
	$ex = 'show';
}
?>
<style>
body {
	margin: 12px;
	background: transparent;
}
.view {
	width: 100%;
	height: 32px !important;
	box-sizing: border-box;
	margin-bottom: 4px;
}
.head {
	font-size: 12px;
	font-weight: bold;
	margin-top: 8px;
	margin-bottom: 4px;
}
</style>
<script>
function onload() {
	document.getElementById('icon-<?php echo $id; ?>').className = 'FlyUiToolbarItemToggle FlyUiNoSelect view';
	document.getElementById('preview-<?php echo $mg; ?>').className = 'FlyUiToolbarItemToggle FlyUiNoSelect view';
	document.getElementById('extensions-<?php echo $ex; ?>').className = 'FlyUiToolbarItemToggle FlyUiNoSelect view';
}
function icon(size) {
	var rand = Math.floor( Math.random() * 1000 );
	window.location.href = 'views.php?mode=icons&iconSize='+size+'&rand='+rand;
}
function preview(value) {
	var rand = Math.floor( Math.random() * 1000 );
	window.location.href = 'views.php?mode=previews&imagePreview='+value+'&rand='+rand;
}
function extensions(value) {
	var rand = Math.floor( Math.random() * 1000 );
	window.location.href = 'views.php?mode=extensions&fileExtensions='+value+'&rand='+rand;
}
</script>
<body onload="onload()">
<div class="FlyUiTextHighlight" style="height:24px;">Views</div>
<div class="head FlyUiTextHighlight">Icon Style</div>
<div onclick="icon('256')" id="icon-hg" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="icon.hg.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">Huge Icons</div>
<div onclick="icon('128')" id="icon-xl" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="icon.xl.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">XL Icons</div>
<div onclick="icon('64')" id="icon-lg" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="icon.lg.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">Large Icons</div>
<div onclick="icon('48')" id="icon-md" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="icon.md.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">Med Icons</div>
<div onclick="icon('32')" id="icon-sm" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="icon.sm.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">Small Icons</div>
<div onclick="icon('16')" id="icon-tn" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="icon.tn.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">Tiny Icons</div>
<div onclick="icon('list')" id="icon-ls" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="icon.ls.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">Tiles</div>
<div onclick="icon('slist')" id="icon-sl" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="icon.ls.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">List</div>

<div class="head FlyUiTextHighlight">Image Previews</div>
<div onclick="preview('true')" id="preview-on" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="<?php echo FLY_ICONS_URL ?>type/image.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">Previews</div>
<div onclick="preview('false')" id="preview-off" class="FlyUiToolbarItem FlyUiNoSelect view"><img src="<?php echo FLY_ICONS_URL ?>file.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;">Icons</div>

<div class="head FlyUiTextHighlight">File Extensions</div>
<div onclick="extensions('false')" id="extensions-show" class="FlyUiToolbarItem FlyUiNoSelect view">Show extensions</div>
<div onclick="extensions('true')" id="extensions-hide" class="FlyUiToolbarItem FlyUiNoSelect view">Hide extensions</div>
</body>
<?php
exit;

icons:
if (FlyRegistrySet('IconSize',$_GET['iconSize'])) {
	echo '<script>window.parent.refresh();window.parent.toggleViews();</script>';
}
exit;

previews:
if (FlyRegistrySet('ImagePreview',$_GET['imagePreview'])) {
	echo '<script>window.parent.refresh();window.parent.toggleViews();</script>';
}
exit;

extensions:
if (FlyRegistrySet('HideFileExtensions',$_GET['fileExtensions'])) {
	echo '<script>window.parent.refresh();window.parent.toggleViews();</script>';
}
exit;
?>