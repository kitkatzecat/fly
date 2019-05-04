<!DOCTYPE html >
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.FileProcessor.php';
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
?>
<script>
	Fly.window.ready = function() {
		var height = (56+Math.max(document.getElementById('Content').scrollHeight,0));

		Fly.window.size.set(500,height);
		Fly.window.position.set(((window.top.window.innerWidth/2)-258),((window.top.window.innerHeight/2)-((height+64)/2)));
		console.log(height);
	}

	var dialog = function() {};
	dialog.submit = function() {
		
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
	margin-top: -16px;
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

<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg">Pin to Jump</div>
<p class="description">Do you want to pin this <?php echo $process['type']; ?> to your Jump menu?</p>
<p>
<div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="width:289px;margin-left:8%;" onclick="window.top.system.command('run:<?php echo $process['file']; ?>');"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo $process['icon']; ?>"><?php echo $process['fname']; ?></div>
</p>
</div>

<button onclick="dialog.submit();" id="ButtonOk" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button onclick="dialog.cancel();" id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

</body>
</html>

</body>
</html>