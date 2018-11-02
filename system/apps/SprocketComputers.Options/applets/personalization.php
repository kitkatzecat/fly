<?php
if (in_array($_GET['query'],['true','yes','on'])) {
	echo '
	<applet>
	<name><![CDATA[Personalization]]></name>
	<icon><![CDATA[personalization.svg]]></icon>
	<publisher><![CDATA[Sprocket Computers]]></publisher>
	</applet>	
	';
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'fileprocessor.php';
include 'registry.php';
?>
<style>
body {
	background: #fff;
	margin: 4px;
	margin-top: 60px;
}
.FlyUiMenuItem {
	box-sizing: border-box;
	padding: 6px;
	width: 100%;
}
a {
	color: rgb(36,99,203);
	text-decoration: none;
}
a:hover {
	cursor: pointer;
	text-decoration: underline;
}
a:active {
	color: rgb(36,39,143);
}
.header {
	font-size: 18px;
	font-weight: bold;
	box-shadow: 0px 0px 16px #000000;
	background-color: #FFFFFF;
	position: fixed;
	top: 0px;
	left: 0px;
	right: 0px;
	padding: 8px;
	z-index: 2;
	background-image: linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(231,231,231,1) 50%,rgba(215,215,215,1) 51%,rgba(236,236,236,1) 100%);
	cursor: pointer;
}
.header:hover {
	background-image: linear-gradient(0deg, rgba(180, 180, 255, .5), rgba(220, 220, 255, .5));
}
.header:active {
	background-image: linear-gradient(0deg, rgba(150, 150, 225, .5), rgba(190, 190, 255, .5));
}
.heading {
	font-size: 18px;
	font-weight: bold;
	display: block;
	margin-bottom: 4px;
}
</style>
<script>
Fly.window.id = window.parent.Fly.window.id;
function navigate(id) {
	window.location.href = '<?php echo CURRENT_URL; ?>?action='+id;
}
</script>
</head>
<body class="FlyUiText">
<div onclick="window.location.href='<?php echo CURRENT_URL; ?>'" class="header FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;margin-right:6px;vertical-align:middle;" src="../icons/personalization.svg">Personalization</div>
<?php
if ($_GET['action']!=='') {
	if ($_GET['action']=='background') {
		goto background;
	}
	if ($_GET['action']=='window') {
		goto window;
	}
	if ($_GET['action']=='jump') {
		goto jump;
	}
	if ($_GET['action']=='sounds') {
		goto sounds;
	}
	if ($_GET['action']=='desktopscroll') {
		goto desktopscroll;
	}
}
if ($_GET['save']!=='') {
	if ($_GET['save']=='background') {
		goto background_save;
	}
	if ($_GET['save']=='window') {
		goto window_save;
	}
	if ($_GET['save']=='jump') {
		goto jump_save;
	}
	if ($_GET['save']=='sounds') {
		goto sounds_save;
	}
	if ($_GET['save']=='desktopscroll') {
		goto desktopscroll_save;
	}
}
?>

<div id="background" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Change your background
</div>

<div id="window" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Change window color and transparency
</div>

<div id="jump" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Customize your Jump menu
</div>

<div id="sounds" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Change system sounds
</div>

<div id="desktopscroll" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Desktop scrolling
</div>
</body>
</html>
<?php
exit;

/*-----------------*/

background:
$userXML = simpleXML_load_file(FLY_ROOT.'system/user.xml');
$process = FlyFileStringProcessor(FLY_ROOT.$userXML->visual->theme->backgroundImage);
?>
<span class="heading FlyUiText">Change your background</span>
Change the image that is displayed behind your windows and icons on your desktop.<hr>

<span class="heading FlyUiText">Current Background</span>
<div style="width:40%;position:relative;box-sizing:border-box;margin-top:8px;margin-left:8px;">
<img style="width:100%;height:100%;border-radius:8px;box-sizing:border-box;position:absolute;top:0px;left:0px;" src="../icons/light.png">
<img style="background-color:#C0C0C0;width:100%;height:100%;border-radius:8px;box-sizing:border-box;box-shadow:0px 0px 4px #000000;border-bottom-left-radius:0px;border-bottom-right-radius:0px;" src="<?php echo $process['URL']; ?>">
</div>
<div style="width:40%;margin-left:8px;border-radius:8px;border-top-left-radius:0px;border-top-right-radius:0px;box-shadow:0px 0px 4px #000000;margin-top:-4px;" onclick="window.top.system.command('run:<?php echo $process['file']; ?>')" class="FlyUiMenuItem FlyUiNoSelect">
<img style="width:48px;height:48px;vertical-align:middle;margin-right:8px;" src="<?php echo $process['icon']; ?>"><?php echo $process['name']; ?>
</div>
<br><br>
<span class="heading FlyUiText">Change Background</span>
Browse for a new background image.<br>
<div style="display:none;" id="Browser-Background"></div><script>Fly.control.replace('Browser-Background','Fly.control.fileBrowser');</script>
<button style="margin:8px;" onclick="document.getElementById('Browser-Background').browse();"><img src="<?php echo FLY_APPS_URL; ?>SprocketComputers.FileManager/fileman.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Browse...</button>
<script>
document.getElementById('Browser-Background').onchange = function() {
	var browser = document.getElementById('Browser-Background');
	if (['jpg','png','bmp','gif'].indexOf(browser.getAttribute('extension')) == -1) {
		window.top.shell.dialog('Selected file cannot be used','The selected file cannot be used as your background. Background images must be of the type "jpg", "png", "bmp", or "gif".','Options','<?php echo FLY_ICONS_URL; ?>warning.svg');
	} else {
		// change the background
	}
}
</script>
<?php
goto ext;

background_save:
exit;

/*-----------------*/

window:
$userXML = simpleXML_load_file(FLY_ROOT.'system/user.xml');
$color = 'rgba('.$userXML->visual->theme->color->r.','.$userXML->visual->theme->color->g.','.$userXML->visual->theme->color->b.','.$userXML->visual->theme->color->a.')';
?>
<span class="heading FlyUiText">Change window color and transparency</span>
Change the accent color used on UI elements such as window borders, and how transparent the color is (if the current theme uses transparency).<hr>

<span class="heading FlyUiText">Current Style</span>
<div style="display:inline-block;box-shadow:0px 0px 4px #000000;margin-left:8px;margin-top:8px;width:64px;height:64px;border-radius:8px;background-image:url('../icons/light.png');background-size:100% 100%;background-color:<?php echo $color; ?>;"></div>
<?php
goto ext;

window_save:
exit;

/*-----------------*/

jump:
$jumpXML = new DOMDocument();
$jumpXML->load(FLY_ROOT.'system/user.xml');
$feed = array();
foreach ($jumpXML->getElementsByTagName('item') as $node) {
	$item = array ( 
		'url' => $node->nodeValue,
		);
	array_push($feed, $item);
}

$list = '';

for ($x = 0; $x < count($feed); $x++) {
	$url = FlyStringReplaceConstants($feed[$x]['url']);
	if ($url == 'separator' || $url == 'divider') {
		$list .= '<hr>';
	} else {
		$process = FlyFileStringProcessor($url);
		$list .= '<div onclick="select(\''.$url.'\');" id="'.$url.'" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="'.$process["icon"].'">'.$process["name"].'</div>';
	}
}
?>
<span class="heading FlyUiText">Customize your Jump menu</span>
Add, remove, and rearrange the items that appear on your Jump menu. All changes made are saved automatically.<hr>

<span class="heading FlyUiText">Current Jump Menu</span>
<div style="width:40%;float:left;padding:3px;box-shadow:0px 0px 4px #000000;border-radius:3px;margin-left:8px;margin-top:8px;margin-bottom:8px;">
<?php
echo $list;
?>
<hr style="opacity:.6;">
<div style="opacity:.6;pointer-events:none;" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo FLY_ICONS_URL; ?>arrow-right.svg">All applications</div>
<hr style="opacity:.6;">
<div style="opacity:.6;pointer-events:none;" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo FLY_ICONS_URL; ?>fly.svg">About Fly</div>
</div>
<div style="float:right;display:inline-block;margin:8px;padding-top:8px;padding-right:8px;background-image:linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(225,225,225,1) 100%);border:.1px solid #C0C0C0;border-radius:3px;">
<button disabled onclick="" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-plus.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Add Item</button>
<button disabled onclick="" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-plus.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Add Separator</button>
<br>
<button disabled onclick="" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>arrow-up.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Move Item Up</button>
<button disabled onclick="" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>arrow-down.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Move Item Down</button>
<br>
<button disabled onclick="" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-minus.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Remove Item</button>
</div>
<?php
goto ext;

jump_save:
exit;

/*-----------------*/

sounds:
?>
<span class="heading FlyUiText">Change system sounds</span>
Change the sounds that play for system events, such as logging in or for an error message.<hr>

<span class="heading FlyUiText">Error Sound</span>
<audio controls><source src="<?php echo DOCUMENT_ROOT ?>system/resources/sounds/error.mp3"></source></audio>
<br>
<br>
<span class="heading FlyUiText">Login Sound</span>
<audio controls><source src="<?php echo DOCUMENT_ROOT ?>system/resources/sounds/login.mp3"></source></audio>
<br>
<br>
<span class="heading FlyUiText">Startup Sound</span>
<audio controls><source src="<?php echo DOCUMENT_ROOT ?>system/resources/sounds/startup.mp3"></source></audio>
<br>
<br>
<?php
goto ext;

sounds_save:
exit;

/*-----------------*/

desktopscroll:
if (in_array(FlyRegistryGet('DesktopAllowScrolling'),['true','on','yes'])) {
	$desktopscroll_checked = 'checked';
}
?>
<span class="heading FlyUiText">Desktop scrolling</span>
Enable or disable the desktop scrolling feature. This feature allows the desktop to scroll vertically, giving more space for windows and other desktop objects.<hr>
<div style="margin-top:24px;margin-left:8px;">
<form action="<?php echo CURRENT_URL; ?>" method="get">
<input type="hidden" name="save" value="desktopscroll">
<span><input type="checkbox" name="desktopscroll" <?php echo $desktopscroll_checked; ?> id="desktopscroll"><label class="FlyUiNoSelect FlyUiText" for="desktopscroll">Enable desktop scrolling</label></span><br>
<button style="margin-top:16px;"><img src="<?php echo FLY_ICONS_URL; ?>save.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Save</button>
</form>
</div>
<?php
goto ext;

desktopscroll_save:
if (in_array($_GET['desktopscroll'],['yes','true','on'])) {
	FlyRegistrySet('DesktopAllowScrolling','true');
} else {
	FlyRegistrySet('DesktopAllowScrolling','false');
}

echo '<script>window.top.shell.notification("Restart Required","Changes have been made to the system that require Fly to be restarted in order to take effect.<button style=\'width:100%;box-sizing:border-box;margin-top:6px;\' onclick=\'window.location.reload()\'>Restart Now</button>","'.FLY_APPS_URL.'SprocketComputers.Options/options.svg");window.location.href="'.CURRENT_URL.'?action=desktopscroll"</script>';
exit;

/*-----------------*/

ext:
echo '</body></html>';
?>