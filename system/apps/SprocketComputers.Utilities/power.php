<!DOCTYPE html >
<html>
<head>
<?php
include 'fly.php';
?>
<style>
body {
	margin: 0px;
	padding: 0px;
}
.image {
	vertical-align: middle;
	width: 56px;
	height: 56px;
	margin-bottom: 6px;
	border-top: 1px solid transparent;
	border-left: 1px solid transparent;
	border-bottom: 1px solid transparent;
	border-right: 1px solid transparent;
	background-size: contain;
	background-color: transparent;
	background-repeat: no-repeat;
	background-position: center center;
}
.card {
	padding: 8px;
	display: inline-block;
	text-align: center;
	width: 88px;
	word-wrap: break-word;
	vertical-align: top;
}
</style>
<script>
	function onload() {
		Fly.window.size.set(500,(136+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(((window.top.window.innerWidth/2)-258),((window.top.window.innerHeight/2)-154));
		document.getElementById('Content').style.overflow = 'visible';
	}

	var dialog = function() {};
	dialog.cancel = function() {
		Fly.window.close();
	}
	if (typeof window.top.shell.sound !== "undefined") {
		window.top.shell.sound.system('question');
	}
</script>
</head>
<body onload="onload()">
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background-color:#FFFFFF;">
	<div style="padding:24px;">
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="overflow:auto;position:absolute;top:36px;left:32px;right:32px;text-align:center;word-wrap:break-word;">
			<div class="FlyUiMenuItem card" onclick=""><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" class="image"><br>Shut Down</div>
			<div class="FlyUiMenuItem card" onclick=""><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg" class="image"><br>Restart</div>
			<div class="FlyUiMenuItem card" onclick=""><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-o.svg" class="image"><br>Sleep</div>
		</div>
	</div>
</div>
<button onclick="dialog.cancel();" id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

</body>
</html>

</body>
</html>