<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Files & Applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',index:'filesapps/index.php'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle FlyCSSectionTitle">Files & Applications<img class="FlyCSSectionIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg"></div>
<p><a onclick="window.location.href='apps.php';"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Manage installed applications</a></p>
<p><a onclick="window.location.href='filetypes.php';"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg">Manage file types and associations</a></p>
<p><a onclick="window.location.href='background.php';"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg">View and manage background windows and tasks</a></p>

<p><br><a onclick="window.top.system.command('run:SprocketComputers.zFileManager,p=?options');"><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.zFileManager/fileman.svg">Change File Manager options</a></p>
</body>
</html>