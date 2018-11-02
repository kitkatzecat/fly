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
</style>
</head>
<body onload="onload()">
<?php
if ($_GET['act']=='rename') {
	goto ren;
}
if ($_GET['act']=='delete') {
	goto del;
}

ren:
if (isset($_GET['new'])) {
	include 'fileprocessor.php';
	if ($_GET['file']=='') {
		echo '
		<script>
		window.top.shell.dialog("No item specified","No item was specified to rename.","Rename","'.FLY_ICONS_URL.'error.svg");
		Fly.window.close();
		</script>
		';
		exit;
	}
	if (!file_exists($_GET['file'])) {
		echo '
		<script>
		window.top.shell.dialog("Item could not be found","The specified item could not be found.","Rename","'.FLY_ICONS_URL.'error.svg");
		Fly.window.close();
		</script>
		';
		exit;
	}
	
	rename($_GET['file'],str_replace(basename($_GET['file']),FlyFileStringReplace($_GET['new']),$_GET['file']));
	echo '
	<script>
	Fly.window.close();
	</script>';
	exit;	
}
if ($_GET['file']=='') {
	echo '
	<script>
	window.top.shell.dialog("No item specified","No item was specified to rename.","Rename","'.FLY_ICONS_URL.'error.svg");
	Fly.window.close();
	</script>
	';
	exit;
}
if (!file_exists($_GET['file'])) {
	echo '
	<script>
	window.top.shell.dialog("Item could not be found","The specified item could not be found.","Rename","'.FLY_ICONS_URL.'error.svg");
	Fly.window.close();
	</script>
	';
	exit;
}
?>
<script>
	function onload() {
		Fly.window.size.set(500,(156+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(((window.top.window.innerWidth/2)-258),((window.top.window.innerHeight/2)-154));
		document.getElementById('Content').style.overflow = 'visible';
	}

	var dialog = function() {};
	dialog.submit = function() {
		window.location.href = 'fileact.php?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&act=rename&file=<?php echo $_GET['file']; ?>&new='+document.getElementById('TextInput').value;
	}
	dialog.cancel = function() {
		Fly.window.close();
	}
	if (typeof window.top.shell.sound !== "undefined") {
		window.top.shell.sound.system('question');
	}
</script>
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background-color:#FFFFFF;">
	<div style="padding:24px;">
		<img src="<?php echo FLY_ICONS_URL; ?>pencil.svg" style="line-height:24px;width:48px;height:48px;">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:80px;font-size:24px;font-weight:bold;word-wrap:break-word;overflow:hidden;">
			Rename
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:80px;word-wrap:break-word;">
			Enter a new name for "<?php echo basename($_GET['file']); ?>":<br>
			<input id="TextInput" onkeydown="if (event.keyCode == 13) {dialog.submit();}" type="text" style="height:32px;width:360px;margin-top:8px;" value="<?php echo basename($_GET['file']); ?>">
		</div>
	</div>
</div>
<button onclick="dialog.submit();" id="ButtonOk" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button onclick="dialog.cancel();" id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

</body>
</html>
<?php
exit;

del:
if ($_GET['del']=='true') {
	function unlink_dir($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir); 
			foreach ($objects as $object) { 
				if ($object != "." && $object != "..") { 
					if (filetype($dir."/".$object) == "dir") {
						unlink_dir($dir."/".$object);
					} else {
						unlink($dir."/".$object);
					} 
				} 
			} 
			reset($objects); 
			rmdir($dir); 
		} 
	}
	if ($_GET['file']=='') {
		echo '
		<script>
		window.top.shell.dialog("No item specified","No item was specified to delete.","Delete","'.FLY_ICONS_URL.'error.svg");
		Fly.window.close();
		</script>
		';
		exit;
	}
	if (!file_exists($_GET['file'])) {
		echo '
		<script>
		window.top.shell.dialog("Item could not be found","The specified item could not be found.","Delete","'.FLY_ICONS_URL.'error.svg");
		Fly.window.close();
		</script>
		';
		exit;
	}
	
	if (is_dir($_GET['file'])) {
		unlink_dir($_GET['file']);
	} else {
		unlink($_GET['file']);
	}
	echo '
	<script>
	Fly.window.close();
	</script>';
	exit;	
}
if ($_GET['file']=='') {
	echo '
	<script>
	window.top.shell.dialog("No item specified","No item was specified to delete.","Delete","'.FLY_ICONS_URL.'error.svg");
	Fly.window.close();
	</script>
	';
	exit;
}
if (!file_exists($_GET['file'])) {
	echo '
	<script>
	window.top.shell.dialog("Item could not be found","The specified item could not be found.","Delete","'.FLY_ICONS_URL.'error.svg");
	Fly.window.close();
	</script>
	';
	exit;
}
?>
<script>
	function onload() {
		Fly.window.size.set(500,(156+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(((window.top.window.innerWidth/2)-258),((window.top.window.innerHeight/2)-154));
		document.getElementById('Content').style.overflow = 'visible';
	}

	var dialog = function() {};
	dialog.submit = function() {
		window.location.href = 'fileact.php?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&act=delete&del=true&file=<?php echo $_GET['file']; ?>';
	}
	dialog.cancel = function() {
		Fly.window.close();
	}
	if (typeof window.top.shell.sound !== "undefined") {
		window.top.shell.sound.system('question');
	}
</script>
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background-color:#FFFFFF;">
	<div style="padding:24px;">
		<img src="<?php echo FLY_ICONS_URL; ?>trash.svg" style="line-height:24px;width:48px;height:48px;">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:80px;font-size:24px;font-weight:bold;word-wrap:break-word;overflow:hidden;">
			Delete
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:80px;word-wrap:break-word;">
			Are you sure you want to permanently delete "<?php echo basename($_GET['file']); ?>"?
		</div>
	</div>
</div>
<button onclick="dialog.submit();" id="ButtonOk" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button onclick="dialog.cancel();" id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

</body>
</html>

</body>
</html>