<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<style>
.main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 6px;
	overflow: auto;
}
.button {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
</style>
<script>
function onload() {
	Fly.window.position.set(((window.top.window.innerWidth/2)-(window.top.document.getElementById(Fly.window.id).offsetWidth/2)),((window.top.window.innerHeight/2)-(window.top.document.getElementById(Fly.window.id).offsetHeight/2)-24));
	window.top.shell.sound.system('error');
}
</script>
<body onload="onload()" class="FlyUiNoSelect">

<div class="main FlyUiText FlyUiNoSelect FlyUiContent"><img style="width:100%;height:auto;" src="<?php echo $_FLY['VERSION_IMAGE']['URL']; ?>">
<div style="text-align:center;margin-top:32px;">
<b><?php echo $_FLY['VERSION_STRING']; ?></b><br>
Version <?php echo $_FLY['VERSION_MAJOR']; ?> Build  <?php echo $_FLY['VERSION_BUILD']; ?><br>
&copy; <?php echo substr($_FLY['VERSION_DATE'],0,4); ?> Sprocket Computers
</div>
</div>
<button onclick="Fly.window.close();" class="button"><img src="<?php echo FLY_ICONS_URL; ?>mark-o.svg" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;"></button>

</body>
</head>
</html>