<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'registry.php';
include 'fileprocessor.php';
?>
<style>
.FlyUiMenuItem {
	box-sizing: border-box;
	cursor: default;
}
.head {
	font-weight: bold;
	font-size: 24px;
	margin-bottom: 4px;
	display: block;
}
li {
	margin-left: 16px;
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
body {
	background-image: linear-gradient(135deg, rgba(191,219,255,0) 0%, rgba(191,219,255,0) 33%, rgba(191,219,255,1) 100%);
	background-attachment: fixed;
	background-size: 100% 100%;
	margin: 4px;
	margin-top: 12px;
	padding-bottom: 64px;
}
</style>
</head>
<body>
<table style="width:100%;" ondblclick="window.location.href='applets/personalization.php';" class="FlyUiMenuItem FlyUiNoSelect">
<tr>
	<td rowspan="2" style="width:96px;vertical-align:top;"><img src="icons/personalization.svg" style="width:96px;height:96px;"></td>
	<td style="padding-left:8px;">
		<span class="head">Personalization</span>
		<a onclick="window.location.href='applets/personalization.php?action=background'"><li>Change your background</a>
		<a onclick="window.location.href='applets/personalization.php?action=window'"><li>Change window color and transparency</a>
		<a onclick="window.location.href='applets/personalization.php?action=jump'"><li>Customize your Jump menu</a>
		<a onclick="window.location.href='applets/personalization.php?action=sounds'"><li>Change system sounds</a>
	</td>
</tr>
</table>

<table style="width:100%;" ondblclick="window.location.href='applets/filesapps.php';" class="FlyUiMenuItem FlyUiNoSelect">
<tr>
	<td rowspan="2" style="width:96px;vertical-align:top;"><img src="icons/application.svg" style="width:96px;height:96px;"></td>
	<td style="padding-left:8px;">
		<span class="head">Files & Applications</span>
		<a onclick="window.location.href='applets/filesapps.php?action=installed'"><li>Manage installed applications</a>
		<a onclick="window.location.href='applets/filesapps.php?action=extensions'"><li>View installed application extensions</a>
		<a onclick="window.location.href='applets/filesapps.php?action=filetypes'"><li>Manage file types and associations</a>
	</td>
</tr>
</table>

<table style="width:100%;" ondblclick="window.location.href='applets/timedate.php';" class="FlyUiMenuItem FlyUiNoSelect">
<tr>
	<td rowspan="2" style="width:96px;vertical-align:top;"><img src="icons/clock.svg" style="width:96px;height:96px;"></td>
	<td style="padding-left:8px;">
		<span class="head">Time &amp; Date</span>
		<a onclick="window.location.href='applets/timedate.php?action=timezone'"><li>Set the system time zone</a>
		<a onclick="window.location.href='applets/timedate.php?action=display'"><li>Change time display settings</a>
	</td>
</tr>
</table>

<table ondblclick="window.top.system.command('run:SprocketComputers.Utilities');" style="width:100%;" class="FlyUiMenuItem FlyUiNoSelect">
<tr>
	<td rowspan="2" style="width:96px;vertical-align:top;"><img src="<?php echo FlyFileStringProcessor('SprocketComputers.Utilities')['icon']; ?>" style="width:96px;height:96px;"></td>
	<td style="padding-left:8px;">
		<span class="head">Utilities</span>
		<a onclick="window.top.system.command('run:SprocketComputers.Utilities.WinMgr');"><li>Open Window Manager</a>
		<a onclick="window.top.system.command('run:SprocketComputers.Utilities.Run');"><li>Open Run</a>
	</td>
</tr>
</table>

<table style="width:100%;" class="FlyUiMenuItem FlyUiNoSelect">
<tr>
	<td rowspan="2" style="width:96px;vertical-align:top;"><img src="icons/accessibility.svg" style="width:96px;height:96px;"></td>
	<td style="padding-left:8px;">
		<span class="head">Accessibility</span>
		<a><li>Change window scaling</a>
	</td>
</tr>
</table>

</body>
</html>