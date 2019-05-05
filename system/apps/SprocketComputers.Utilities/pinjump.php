<?php
if ($_GET['mode'] == 'add') {
	goto add;
}
if ($_GET['mode'] == 'remove') {
	goto remove;
}
?>
<!DOCTYPE html >
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.FileProcessor.php';
include 'Fly.Registry.php';
?>
<style>
body {
	margin: 0px;
	padding: 0px;
}
</style>
</head>
<body class="FlyUiNoSelect">
<?php
if ($_GET['file']=='') {
	echo '
	<script>
	window.top.shell.dialog("No item specified","No item was specified to pin.","Pin to Jump","'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg");
	Fly.window.close();
	</script>
	';
	exit;
}

$reg = json_decode(FlyUserRegistryGet('Jump','SprocketComputers.Utilities'),true);

$process = FlyFileStringProcessor(FlyVarsReplace($_GET['file']));
if (!$process) {
	echo '
	<script>
	window.top.shell.dialog("Item could not be found","The specified item could not be found.","Pin to Jump","'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg");
	Fly.window.close();
	</script>
	';
	exit;
}

$message = '';

if ($process['extension'] == 'als' && !$_GET['noreplace'] == 'true') {
	$als = simpleXML_load_file($process['file']);
	if ($als) {
		$alsprocess = FlyFileStringProcessor(FlyVarsReplace((string)$als->link));
		if ($alsprocess) {
			$process = $alsprocess;
			$message = '<p class="hint">The file specified is an alias. The item that this alias links to will be ';
		}
	}
}

if (in_array($process['ffile'],$reg)) {
	$title = 'Unpin from Jump';
	$question = 'Do you want to unpin this '.$process['type'].' from your Jump menu?';
	$pin = 'pin-no.svg';
	$mode = 'remove';
	if ($message !== '') {
		$message .= 'unpinned from Jump.</p>';
	}
} else {
	$title = 'Pin to Jump';
	$question = 'Do you want to pin this '.$process['type'].' to your Jump menu?';
	$pin = 'pin.svg';
	$mode = 'add';
	if ($message !== '') {
		$message .= 'pinned to Jump.</p>';
	}
}

?>
<script>
	Fly.window.ready = function() {
		var height = (56+Math.max(document.getElementById('Content').scrollHeight,0));

		Fly.window.size.set(500,height);
		Fly.window.position.set(((window.top.window.innerWidth/2)-258),((window.top.window.innerHeight/2)-((height+64)/2)));

		document.getElementById('ButtonOk').disabled = false;
		document.getElementById('ButtonCancel').disabled = false;
	}

	var dialog = function() {};
	dialog.submit = function() {
		document.getElementById('ButtonOk').disabled = true;
		document.getElementById('ButtonCancel').disabled = true;

		document.getElementById('save').src = 'pinjump.php?mode=<?php echo $mode; ?>&file=<?php echo urlencode($process['ffile']); ?>';
	}
	dialog.cancel = function() {
		Fly.window.close();
	}
	if (typeof window.top.shell.sound !== "undefined") {
		window.top.shell.sound.system('question');
	}
</script>
<style>
h1,h2,h3,h4,h5,h6,p {
	padding-left: 9%;
	padding-right: 9%;
}
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 18px;
	padding-bottom: 16px;
	padding-left: 9%;
	padding-right: 9%;
}
p.description {
	margin-top: -12px;
}
p.hint {
	font-size: 0.8em;
	opacity: 0.8;
	margin-top: -12px;
	padding-left: 44px;
}
p.shead {
	font-size: 0.8em;
	opacity: 0.8;
	margin-bottom: -16px;
}
.inline-icon {
	width: 18px;
	height: 18px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
.title-icon {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
img.button-image {
	width: 16px;
	height: 16px;
	vertical-align: middle;
}
#Content {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 4px;
	background-color: #fff;
	overflow-y: auto;
}
</style>

<div id="Content">

<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS'].$pin; ?>"><?php echo $title; ?></div>
<p class="description"><?php echo $question; ?></p><?php echo $message; ?>
<p><div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="width:289px;margin-left:8%;" onclick="window.top.system.command('run:<?php echo $process['file']; ?>');"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo $process['icon']; ?>"><?php echo $process['fname']; ?></div></p>
</div>

<button onclick="dialog.submit();" disabled id="ButtonOk" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button onclick="dialog.cancel();" disabled id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

<iframe src="" style="display:none;" id="save"></iframe>
</body>
</html>
<?php
exit;

add:

include 'Fly.Registry.php';

if ($_GET['file'] !== '') {
	$reg = json_decode(FlyUserRegistryGet('Jump','SprocketComputers.Utilities'),true);
	array_push($reg,$_GET['file']);
	FlyUserRegistrySet('Jump',json_encode($reg));
	echo '<script>window.top.ui.jump.toggle();window.parent.Fly.window.close();</script>';
} 

exit;

remove:

include 'Fly.Registry.php';

if ($_GET['file'] !== '') {
	$reg = json_decode(FlyUserRegistryGet('Jump','SprocketComputers.Utilities'),true);
	$pos = array_search($_GET['file'],$reg);

	if ($pos !== false) {
		unset($reg[$pos]);
		$reg = array_values($reg);
	}

	FlyUserRegistrySet('Jump',json_encode($reg));
	echo '<script>window.top.ui.jump.toggle();window.parent.Fly.window.close();</script>';
} 

exit;
?>