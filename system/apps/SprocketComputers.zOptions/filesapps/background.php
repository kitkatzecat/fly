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
	{name:'Files & Applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',index:'filesapps/index.php'},
	{name:'Background tasks',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle">View and manage background windows and tasks</div>
<p class="FlyCSDescription">Background windows and tasks are applications that run in the background to perform operations that keep Fly running smoothly.</p>

<p><a onclick="window.top.system.command('run:SprocketComputers.Utilities.WinMgr');"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg" class="FlyCSInlineIcon">View currently open background windows in Window Manager</a></p>

<p class="FlyCSParagraphTitle">Autostart</p>
<p>Some background windows and tasks may be started by an autostart script when you log in. For more information and to manage these files, see <a href="../users/login.php?user=<?php echo $_FLY['USER']['ID']; ?>">Manage your login experience</a>.</p>

<div class="FlyCSDivider"></div>

<p><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg" class="FlyCSInlineIcon">For more information about background windows and tasks, see <a  onclick="window.top.system.command('run:SprocketComputers.FlyHelp');">Background windows and tasks</a> in Fly Help.</p>
</body>
</html>