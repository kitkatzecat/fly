<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<style>
body {
	background-color: #fff;
	margin: 3px;
}
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	overflow: auto;
}
</style>
</head>
<body>
<div id="main">
<div onclick="window.top.toggleFullScreen()" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>popout.svg">Toggle Full Screen</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.WinMgr')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="winmgr.svg">Window Manager</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.Run')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="run.svg">Run</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.CommandPrompt')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="command.svg">Command Prompt</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.Executor')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="executor.svg">Executor</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.FileDownloader')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="downloader.svg">File Downloader</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.SystemVariables')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>index.svg">System Variables</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.ThemeLoader')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg">Theme Loader</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.AboutFly')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg">About Fly</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities.Applications')" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Applications</div>
</div>
</body>
</html>