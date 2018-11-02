<?php
if (in_array($_GET['query'],['true','yes','on'])) {
	echo '
	<applet>
	<name><![CDATA[Time & Date]]></name>
	<icon><![CDATA[clock.svg]]></icon>
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
<div onclick="window.location.href='<?php echo CURRENT_URL; ?>'" class="header FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;margin-right:6px;vertical-align:middle;" src="../icons/clock.svg">Time &amp; Date</div>
<?php
if ($_GET['action']!=='') {
	if ($_GET['action']=='timezone') {
		goto timezone;
	}
	if ($_GET['action']=='display') {
		goto display;
	}
}
if ($_GET['save']!=='') {
	if ($_GET['save']=='timezone') {
		goto timezone_save;
	}
	if ($_GET['save']=='display') {
		goto display_save;
	}
}
?>

<div id="timezone" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Set the system time zone
</div>

<div id="display" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Change time display settings
</div>

</body>
</html>
<?php
exit;

/*-----------------*/

timezone:
?>
<span class="heading FlyUiText">Set the system time zone</span>
Set the default time zone that is used for file operations. This does not affect the current time.<hr>

<?php
goto ext;

timezone_save:
exit;

/*-----------------*/

display:
if (in_array(FlyRegistryGet('TimeShowMilitary'),['true','on','yes'])) {
	$tfhr_checked = 'checked';
}
if (in_array(FlyRegistryGet('TimeShowSeconds'),['true','on','yes'])) {
	$seconds_checked = 'checked';
}
?>
<script>
function updatePreview() {
	var tfhr = document.getElementById('24hr');
	var seconds = document.getElementById('seconds');
	var preview = document.getElementById('preview');
	
	if (tfhr.checked == true && seconds.checked == true) {
		preview.innerHTML = '15:45:15';
	} else if (tfhr.checked == true && seconds.checked == false) {
		preview.innerHTML = '15:45';
	} else if (tfhr.checked == false && seconds.checked == true) {
		preview.innerHTML = '3:45:15 PM';
	} else if (tfhr.checked == false && seconds.checked == false) {
		preview.innerHTML = '3:45 PM';
	}
}
</script>
<span class="heading FlyUiText">Change time display settings</span>
Change how the current time is displayed. This does not affect the time shown in file operations.<hr>

<span class="heading FlyUiText">Preview</span>
<div id="preview" style="display:inline-block;margin-left:8px;border-radius:4px;box-shadow:0px 0px 4px #000000;padding:12px;font-weight:bold;font-size:36px;" class="FlyUiText FlyUiNoSelect">...</div>
<div style="margin-left:8px;margin-top:16px;">
<form action="<?php echo CURRENT_URL; ?>" method="get">
<input type="hidden" name="save" value="display">
<span style="margin-bottom:8px;"><input type="checkbox" <?php echo $tfhr_checked; ?> name="24hr" id="24hr" onchange="updatePreview();"><label class="FlyUiNoSelect FlyUiText" for="24hr">Show 24-hour time</label></span><br>
<span><input type="checkbox" <?php echo $seconds_checked; ?> name="seconds" id="seconds" onchange="updatePreview();"><label class="FlyUiNoSelect FlyUiText" for="seconds">Show seconds</label></span><br>
<button style="margin-top:16px;"><img src="<?php echo FLY_ICONS_URL; ?>save.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Save</button>
</form>
</div>
<script>updatePreview();</script>
<?php
goto ext;

display_save:
if (in_array($_GET['24hr'],['yes','true','on'])) {
	FlyRegistrySet('TimeShowMilitary','true');
} else {
	FlyRegistrySet('TimeShowMilitary','false');
}

if (in_array($_GET['seconds'],['yes','true','on'])) {
	FlyRegistrySet('TimeShowSeconds','true');
} else {
	FlyRegistrySet('TimeShowSeconds','false');
}

echo '<script>window.top.shell.notification("Restart Required","Changes have been made to the system that require Fly to be restarted in order to take effect.<button style=\'width:100%;box-sizing:border-box;margin-top:6px;\' onclick=\'window.location.reload()\'>Restart Now</button>","'.FLY_APPS_URL.'SprocketComputers.Options/options.svg");window.location.href="'.CURRENT_URL.'?action=display"</script>';
exit;

/*-----------------*/

ext:
echo '</body></html>';
?>