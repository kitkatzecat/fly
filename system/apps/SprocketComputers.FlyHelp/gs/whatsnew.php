<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Dialog.php';
include 'Fly.CommonStyle.php';
?>

<script>
var OptionsTree = [
	{name:'Getting Started',icon:`${Fly.core['RESOURCE']['URL']['ICONS']}fly.svg`,index:'gs'},
	{name:'What\'s new',icon:`${Fly.core['RESOURCE']['URL']['ICONS']}fly.svg`,index:'gs/whatsnew.php'}
];

function ExampleDialog() {
	Fly.dialog.message({
		title: 'Example Dialog',
		message: 'This is a dialog',
		content: `This is a dialog box. To close this box and return to Fly Help, click the <img src="${Fly.core['RESOURCE']['URL']['ICONS']}mark-o.svg" style="width:16px;height:16px;vertical-align:middle;"> button below.`
	});
}
</script>

</head>
<body class="FlyUiText FlyUiNoSelect">
<div class="FlyCSTitle"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg">What's new in Fly Cerulean</div>

<p>
	Fly Cerulean introduces many new features over previous versions of Fly. For this version of Fly,
	the operating system has been entirely rewritten from the ground up on modern technologies such as
	JavaScript and HTML5, allowing the system to take advantage of decades of advancements in computing
	technology.
</p>
<p>
	Since we cannot cover every change from every version of Fly, this article will focus on the changes
	since the most recent previous version of Fly - Fly Window.
</p>
<p>
	Many of the features present in Fly Window are still here! However, there are many more major changes
	to how Fly works. For example, the File Manager is still present, but is vastly different from the
	File Manager present in Fly Window. See the <a>Using File Manager</a> article for more details on how
	to use File Manager. 
</p>
<p><a onclick="window.top.system.command('run:SprocketComputers.zFileManager');"><img src="<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.zFileManager/fileman.svg" class="FlyCSInlineIcon">Click here to open File Manager</a></p>
<p>
	Other
	major changes since Fly Window include the introduction of a truly windowed interface. What does that
	mean for you? Every application has one or more <i>windows</i>, or boxes of the screen that the
	application is displayed in. Generally, an application has a main window and, if necessary, opens
	<i>dialog boxes</i> (smaller windows generally linked to a main window) as secondary windows for
	additional input.
</p>
<p><a onclick="ExampleDialog()"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg" class="FlyCSInlineIcon">Click here to open a dialog box</a></p>
<p>
	More to come...
</p>
</body>
</html>