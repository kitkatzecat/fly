<!DOCTYPE html>
<html>
<head>
<?php
if (!empty($_GET['cmd'])) {
	header('Location: exec.php?Fly_Id='.$_GET['Fly_Id'].'&exec='.$_GET['cmd']);
	exit;
}
include 'fly.php';
if (!empty($_GET['shutdown'])) {
	echo '
	<script>
	var coverDiv = document.createElement("div");
	coverDiv.style.position = \'fixed\';
	coverDiv.style.top = \'0px\';
	coverDiv.style.bottom = \'0px\';
	coverDiv.style.left = \'0px\';
	coverDiv.style.right = \'0px\';
	coverDiv.style.backgroundColor = \'#000\';
	coverDiv.style.transition = \'opacity 1s linear\';
	coverDiv.style.zIndex = \'5000001\';
	coverDiv.style.cursor = \'none\';
	coverDiv.style.opacity = \'0\';
	window.top.document.body.appendChild(coverDiv);
	setTimeout(function(){coverDiv.style.opacity = \'1\';},100);
	setTimeout(function(){coverDiv.parentNode.removeChild(coverDiv);Fly.window.close();},10000);
	setTimeout(function(){window.top.system.command(\'run:SprocketComputers.LinuxTools,cmd=sudo+shutdown+-h+now\');},1500);
	</script>
	';
	exit;
}
if (!empty($_GET['reboot'])) {
	echo '
	<script>
	var coverDiv = document.createElement("div");
	coverDiv.style.position = \'fixed\';
	coverDiv.style.top = \'0px\';
	coverDiv.style.bottom = \'0px\';
	coverDiv.style.left = \'0px\';
	coverDiv.style.right = \'0px\';
	coverDiv.style.backgroundColor = \'#000\';
	coverDiv.style.transition = \'opacity 1s linear\';
	coverDiv.style.zIndex = \'5000001\';
	coverDiv.style.cursor = \'none\';
	coverDiv.style.opacity = \'0\';
	window.top.document.body.appendChild(coverDiv);
	setTimeout(function(){coverDiv.style.opacity = \'1\';},100);
	setTimeout(function(){coverDiv.parentNode.removeChild(coverDiv);Fly.window.close();},6000);
	setTimeout(function(){window.top.system.command(\'run:SprocketComputers.LinuxTools,cmd=sudo+reboot\');},1500);
	</script>
	';
	exit;
}
if (!empty($_GET['screenoff'])) {
	echo '
	<script>
	var coverDiv = document.createElement("div");
	coverDiv.style.position = \'fixed\';
	coverDiv.style.top = \'0px\';
	coverDiv.style.bottom = \'0px\';
	coverDiv.style.left = \'0px\';
	coverDiv.style.right = \'0px\';
	coverDiv.style.backgroundColor = \'#000\';
	coverDiv.style.transition = \'opacity 1s linear\';
	coverDiv.style.zIndex = \'5000001\';
	coverDiv.style.cursor = \'none\';
	coverDiv.style.opacity = \'0\';
	window.top.document.body.appendChild(coverDiv);
	setTimeout(function(){coverDiv.style.opacity = \'1\';},100);
	setTimeout(function(){coverDiv.parentNode.removeChild(coverDiv);Fly.window.close();},10000);
	setTimeout(function(){window.top.system.command(\'run:SprocketComputers.LinuxTools,cmd=sudo+-u+fly+xset+-display+:0.0+dpms+force+off\');},1500);
	</script>
	';
	exit;
}
if (!empty($_GET['volume'])) {
	echo '
	<script>
	var volumeReturn = function(volume) {window.top.system.command(\'run:SprocketComputers.LinuxTools,cmd=sudo+-u+fly+amixer+-D+pulse+sset+Master+\'+volume+\'%\');Fly.window.close();};
	Fly.control.input(\'Set volume\',\'Enter a new volume (0-100)\',\'Volume\',\''.FLY_APPS_URL.'SprocketComputers.MediaPlayer/button.volume.svg\',volumeReturn);
	</script>
	';
	exit;
}
if (!empty($_GET['unmute'])) {
	echo '
	<script>
	window.top.system.command(\'run:SprocketComputers.LinuxTools,cmd=sudo+-u+fly+amixer+-D+pulse+sset+Master+unmute\');
	Fly.window.close();
	</script>
	';
	exit;
}

?>
<style>
body {
	background-color: #fff;
	margin: 3px;
}
</style>
</head>
<body>
<div onclick="window.top.system.command('run:SprocketComputers.LinuxTools.Volume');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo FLY_APPS_URL;?>SprocketComputers.MediaPlayer/button.volume.svg">Volume</div>
<div onclick="window.top.system.command('run:SprocketComputers.LinuxTools.Unmute');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo FLY_APPS_URL;?>SprocketComputers.MediaPlayer/button.volume.mute.svg">Unmute</div>
<div onclick="window.top.system.command('run:SprocketComputers.LinuxTools.ScreenOff');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo FLY_ICONS_URL;?>mark-o.svg">Screen Off</div>
<div onclick="window.top.system.command('run:SprocketComputers.LinuxTools.Reboot');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo FLY_ICONS_URL;?>refresh.svg">Reboot</div>
<div onclick="window.top.system.command('run:SprocketComputers.LinuxTools.ShutDown');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;vertical-align:middle;margin-right:8px;" src="<?php echo FLY_ICONS_URL;?>mark-x.svg">Shut Down</div>
</body>
</html>