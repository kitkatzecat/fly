<!DOCTYPE html >
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Registry.php';
include 'Fly.FileProcessor.php';
?>
<style>
body {
	margin: 0px;
	padding: 0px;
}
</style>
</head>
<body onload="onload()">
<?php
if ($_GET['load']=='true') {
	if ($_GET['file']=='') {
		echo '
		<script>
		window.top.shell.dialog("No theme file specified","No theme file was specified to load.","Theme Loader","'.$_FLY['RESOURCE']['URL']['ICONS'].'personalization.svg");
		Fly.window.close();
		</script>
		';
		exit;
	}
	if (!file_exists($_GET['file'])) {
		echo '
		<script>
		window.top.shell.dialog("Theme could not be found","The specified theme file could not be found.","Theme Loader","'.$_FLY['RESOURCE']['URL']['ICONS'].'personalization.svg");
		Fly.window.close();
		</script>
		';
		exit;
	}
	
	include 'Fly.Theme.php';
	$themeXML = simpleXML_load_file($_GET['file']);
	$configXML = simpleXML_load_file($_FLY['USER']['DATA_PATH'].'user.xml');
	$configXML->visual->theme->url = str_replace($_FLY['PATH'],'',$_GET['file']);
	echo '
	<script>
	function onload() {
		window.top.document.getElementById(\'FlyStylesheet\').innerHTML = document.getElementById(\'stylesheet\').innerHTML;
		window.top.shell.dialog("Theme applied",\'The theme "'.htmlspecialchars((string)$themeXML->name).'" has been applied to the desktop. Applications will not show this change.\',"Theme Loader","'.$_FLY['RESOURCE']['URL']['ICONS'].'personalization.svg");
		Fly.window.close();
	}
	</script>
	<style id="stylesheet">
	';
	FlyLoadTheme('all',$configXML,false);
	echo '
	</style>
	';
	exit;	
}
if ($_GET['file']=='') {
	echo '
	<script>
	window.top.shell.dialog("No theme file specified","No theme file was specified to load.","Theme Loader","'.$_FLY['RESOURCE']['URL']['ICONS'].'personalization.svg");
	Fly.window.close();
	</script>
	';
	exit;
}
if (!file_exists($_GET['file'])) {
	echo '
	<script>
	window.top.shell.dialog("Theme could not be found","The specified theme file could not be found.","Theme Loader","'.$_FLY['RESOURCE']['URL']['ICONS'].'personalization.svg");
	Fly.window.close();
	</script>
	';
	exit;
}

$themeXML = simpleXML_load_file($_GET['file']);
?>
<script>
	function onload() {
		Fly.window.size.set(500,(148+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(((window.top.window.innerWidth/2)-258),((window.top.window.innerHeight/2)-154));
		Fly.window.title.set('Theme Loader - <?php echo htmlentities(basename($_GET['file'])); ?>');
		document.getElementById('Content').style.overflow = 'visible';
	}

	var dialog = function() {};
	dialog.submit = function() {
		window.location.href = 'themeloader.php?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&load=true&file=<?php echo $_GET['file']; ?>';
	}
	dialog.cancel = function() {
		Fly.window.close();
	}
	try {
		window.top.shell.sound.system('question');
	} catch(err) {}
</script>
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background-color:#FFFFFF;">
	<div style="padding:24px;">
		<img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg" style="line-height:24px;width:48px;height:48px;">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:80px;font-size:24px;font-weight:bold;word-wrap:break-word;overflow:hidden;">
			Load theme
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:80px;word-wrap:break-word;">
			Do you want to load the theme "<?php echo (string)$themeXML->name; ?>" by <?php echo (string)$themeXML->publisher; ?>?
		</div>
	</div>
</div>
<button onclick="dialog.submit();" id="ButtonOk" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button onclick="dialog.cancel();" id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

</body>
</html>