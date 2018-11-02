<?php

function getFolderSize( $path = '.' ){

$size = "0";
$ignore = array( 'cgi-bin', '.', '..' ); 
// Directories to ignore when listing output. Many hosts 
// will deny PHP access to the cgi-bin. 

$dh = @opendir( $path ); 
// Open the directory to the handle $dh 

while( false !== ( $file = readdir( $dh ) ) ){ 
// Loop through the directory 

    if( !in_array( $file, $ignore ) ){ 
    // Check that this file is not to be ignored 

        if( is_dir( "$path/$file" ) ){ 
			$size += getFolderSize("$path/$file");
		} else {        
			$size += filesize("$path/$file");
        } 

    } 

} 

closedir( $dh ); 
return $size;
// Close the directory handle 
}

function formatFileSize($filesize) {
	$filesize = abs(intval($filesize));
	if ($filesize > 1000000000) {
		$filesize = round($filesize/1000000000,2).' GB';
	} else if ($filesize > 1000000 && $filesize < 10000000000) {
		$filesize = round($filesize/1000000,2).' MB';
	} else if ($filesize > 1000 && $filesize < 10000000) {
		$filesize = round($filesize/1000).' KB';
	} else {
		$filesize = $filesize.' bytes';
	}
	
	return $filesize;
}

if ($_GET['properties_filesize'] == 'true') {
	goto size;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';
?>
<style>
body {
	background-color: #ffffff;
	margin: 0px;
}
.item {
	display: inline-block;
	border: 1px solid transparent;
	text-align: left;
	padding: 4px;
	box-sizing: border-box;
	cursor: default;
	overflow: hidden;
	font-size: 16px;
	word-wrap: break-word;
}
.head {
	font-size: 12px;
	font-weight: bold;
}
.icon {
	width: 48px;
	height: 48px;
	margin-bottom: 4px;
	pointer-events: none;
	vertical-align: middle;
	margin-right: 6px;
	float: left;
}
.name {
	display: inline-block;
	width: calc(100% - 54px);
	margin-top: 16px;
}
.FlyUiMenuItem {
	box-sizing: border-box;
}
.page {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	padding: 2px;
	overflow: auto;
}
</style>
<?php
$process = FlyFileStringProcessor($_GET['file']);
$sprocess = FlyFileStringProcessor(FlyVarsReplace($_GET['file']));

if (preg_replace('#/+#','/',$_GET['file']) == './') {
	$process = false;
}

if ($process !== false) {
	if ($process['type']!='folder' && $process['type']!='file') {
		echo '
		<script>
		window.top.shell.dialog("Can\'t get properties","Properties cannot be retrieved for items other than local files or folders, such as applications or online items.","Properties","'.FLY_ICONS_URL.'error.svg");
		Fly.window.close();
		</script>';
		exit;
	}
} else if ($sprocess !== false) {
	if ($sprocess['type']!='folder' && $sprocess['type']!='file') {
		echo '
		<script>
		window.top.shell.dialog("Can\'t get properties","Properties cannot be retrieved for items other than local files or folders, such as applications or online items.","Properties","'.FLY_ICONS_URL.'error.svg");
		Fly.window.close();
		</script>';
		exit;
	}
	$process = $sprocess;
} else {
	echo '
	<script>
	window.top.shell.dialog("Item not found","Properties cannot be retrieved for the specified item because it could not be found.","Properties","'.FLY_ICONS_URL.'error.svg");
	Fly.window.close();
	</script>';
	exit;
}
?>
<script>
function onload() {
	<?php
	echo 'Fly.window.title.set(\'Properties - '.$process['name'].'\');';
	?>
	Fly.window.size.set(240,320);
	<?php
	echo 'document.getElementById(\'frame\').src = \'properties.php?properties_filesize=true&file='.$process['file'].'\'';
	?>
}
</script>
</head>
<body onload="onload()" oncontextmenu="return false;">
<div id="appBrowser" style="display:none;"></div>
<script>
Fly.control.replace('appBrowser','Fly.control.applicationChooser');
document.getElementById('appBrowser').onchange = function() {
	window.top.system.command('run:'+document.getElementById('appBrowser').getAttribute('app')+',file=<?php echo $process['file']; ?>');
}
</script>

<div class="page">
<div style="cursor:pointer;" oncontextmenu="Fly.control.contextMenu(event,['<b>Open</b>','Rename','Open With...'],['window.top.system.command(\'run:<?php echo htmlentities($process['file']); ?>\')','window.top.system.command(\'run:SprocketComputers.FileManager.Rename,file=<?php echo htmlentities($process['file']); ?>\')','document.getElementById(\'appBrowser\').browse();']);return false;" class="item FlyUiMenuItem FlyUiText FlyUiNoSelect" onclick="window.top.system.command('run:<?php echo htmlentities($process['file']); ?>')"><img class="icon FlyUiNoSelect" src=" <?php echo $process['icon']; ?>"><div class="name"><?php echo $process['name']; ?></div></div>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect"><span class="head">Type</span><br><?php echo htmlentities($process['description']); if ($process['type']=="file" && $process['extension'] !== '') {echo ' ('.$process['extension'].')';}?></div>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect"><span class="head">Size</span><br><span id="size-value">Determining...</span></div>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect"><span class="head">Modified</span><br><?php echo date("l, F j, Y",filemtime($process['file'])); ?></div>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect"><span class="head">Created</span><br><?php echo date("l, F j, Y",filectime($process['file'])); ?></div>
<div style="cursor:pointer;" oncontextmenu="Fly.control.contextMenu(event,['<b>Open</b>'],['window.top.system.command(\'run:<?php echo htmlentities($process['fpath']); ?>\')']);return false;" class="item FlyUiMenuItem FlyUiText FlyUiNoSelect" onclick="window.top.system.command('run:<?php echo htmlentities($process['fpath']); ?>')"><span class="head">Path</span><br><?php echo preg_replace('#/+#','/',$process['fpath']); ?></div>
</div>
<iframe style="display:none;" id="frame" src=""></iframe>
</body>
</html>
<?php
exit;

size:
if (is_dir($_GET['file'])) {
	$filesize = formatFileSize(getFolderSize($_GET['file']));
} else {
	$filesize = formatFileSize(filesize($GET['file']));
}
echo '
<script>
window.parent.document.getElementById(\'size-value\').innerHTML = \''.$filesize.'\';
</script>
';
exit;
