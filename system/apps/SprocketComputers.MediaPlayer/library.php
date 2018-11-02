<?php
if (!empty($_GET['library'])) {
	goto set_location;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'registry.php';

$location = FlyRegistryGet('Library');
if ($location == '') {
	$location_name = 'Computer';
} else {
	$location_name = basename($location);
}

echo FlyLoadExtension('SprocketComputers.FileManager','LocationChooser');
?>
<style>
body {
	background-image: url('background.library.svg'),url('background.header.static.svg'),url('background.header.repeat.svg');
	background-size: auto;
	background-repeat: no-repeat, no-repeat, repeat-x;
	background-position: right top, right top, left top;
	background-color: #ffffff;
	padding: 8px;
}
#Media {
	position: absolute;
	box-sizing: border-box;
	top: 96px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	overflow: hidden;
}
</style>
<script>
var ChangeLocation = function() {
	document.getElementById('Location-Browser').browse()
}
var SetLocation = function() {
	var browser = document.getElementById('Location-Browser');
	window.location.href = '<?php echo WORKING_URL; ?>library.php?library='+browser.vars.path+'&Fly_Id='+Fly.window.id;
}
</script>
</head>
<body>
<span class="FlyUiText" style="color: #ffffff; font-size: 24px;">Library</span>
<div class="FlyUiText" style="margin-top:32px;">Media files in <?php echo $location_name; ?><button style="margin-left:24px;" onclick="document.getElementById('Media-List').style.opacity='0';document.getElementById('Media-List').contentWindow.window.location.reload();"><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo FLY_ICONS_URL; ?>refresh.svg">Refresh</button> <button style="margin-left:4px;" onclick="ChangeLocation()"><img style="width:16px;height:16px;vertical-align:middle;" src="<?php echo FLY_ICONS_URL; ?>folder.svg"></button><br><br>Loading...</div>
<div id="Media"><iframe id="Media-List" style="width:100%;height:100%;opacity:0;" frameborder="0" allowtransparency="true" scrolling="auto" src="list.php"></iframe></div>
<div style="display:none;" id="Location-Browser"></div>
<script>
Fly.extension.replace('Location-Browser','SprocketComputers.FileManager','LocationChooser');
document.getElementById('Location-Browser').onchange = SetLocation;
</script>
</body>
</html>
<?php
exit;

set_location:
include 'constants.php';
include 'registry.php';

if (file_exists($_GET['library']) && is_dir($_GET['library'])) {
	FlyRegistrySet('Library',str_replace(FLY_ROOT,'',$_GET['library']));
}

echo '<script>window.location.href=\''.WORKING_URL.'library.php?Fly_Id='.$_GET['Fly_Id'].'\';</script>';
?>