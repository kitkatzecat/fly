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

function nicetime($date)
{
	if (empty($date)) {
		return "Not available";
	}
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");
	$now = time();
	$unix_date = $date;
	
	if (empty($unix_date)) {    
		return "Not available";
	}
	
	if ($now > $unix_date) {    
		$difference = $now - $unix_date;
		$tense = "ago";
	} else {
		$difference = $unix_date - $now;
		$tense = "from now";
	}
	for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}
	$difference = round($difference);

	if ($difference != 1) {
		$periods[$j].= "s";
	}
	return "$difference $periods[$j] {$tense}";
}

if ($_GET['properties_filesize'] == 'true') {
	goto size;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.FileProcessor.php';
include 'Fly.Actionmenu.php';
?>
<style>
body {
	background-color: #ffffff;
	margin: 0px;
}
@keyframes fadein-icon {
	0% {opacity: 0;}
	80% {opacity: 0;}
	100% {opacity: 1}
}
@keyframes fadein-img {
	0% {opacity: 0;}
	70% {opacity: 0;}
	100% {opacity: 1}
}
.file {
	min-height: 58px;
	position: relative;
	transition: height .5s ease-in-out, min-height .5s ease-in-out;
}
.item {
	display: inline-block;
	border: 1px solid transparent;
	text-align: left;
	padding: 4px;
	box-sizing: border-box;
	cursor: default;
	overflow: hidden;
	font-size: 14px;
	word-wrap: break-word;
}
.title {
	width: clamp(80px,30%,160px);
	float: left;
	height: 100%;
}
.icon {
	width: 48px;
	height: 48px;
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	transition: transform .5s ease-in-out, top .5s ease-in-out, width .5s ease-in-out, height .5s ease-in-out;
	animation: fadein-icon .4s linear;
}
.image {
	max-width: 48px;
	max-height: 48px;
	position: absolute;
	top: 50%;
	left: 28px;
	transform: translate(-50%,-50%);
	box-shadow: 0px 1px 4px #888;
	transition: transform .5s ease-in-out, top .5s ease-in-out, width .5s ease-in-out, height .5s ease-in-out, max-width .5s ease-in-out, max-height .5s ease-in-out;
	animation: fadein-img .4s linear;
}
.info {
	display: inline-block;
	width: calc(100% - clamp(80px,30%,160px) - 20px);
	word-wrap: break-word;
}
.name {
	display: inline-block;
	width: calc(100% - 80px);
	margin-top: 12px;
	margin-bottom: 12px;
	font-size: 20px;
	font-weight: bold;
	margin-left: 58px;
	text-align: left;
	transition: width .5s ease-in-out, margin-left .5s ease-in-out, margin-top .5s ease-in-out;
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
.toggle {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	float: right;
	padding-left: 2px;
	padding-top: 0px;
	font-size: 16px;
	margin: -2px;
}
.infos {
	display: none;
	margin-top: 6px;
	word-wrap: break-word;
	white-space: pre-wrap;
}
.category {
	width: calc(100% - 8px);
	box-sizing: border-box;
	margin-left: 4px;
	margin-right: 4px;
	font-weight: bold;
	font-size: 12px;
	border-bottom: 1px solid #808080;
	padding: 2px;
	margin-top: 8px;
	margin-bottom: 6px;
	padding-left: 1px;
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

if (strpos($process['mime'],'image/') !== false) {
	$icon_src = $process['URL'];
	$icon_class = 'image';
} else {
	$icon_src = $process['icon'];
	$icon_class = 'icon';
}
?>
<script>
function onload() {
	<?php
	echo 'Fly.window.title.set(\'Properties - '.$process['fname'].'\');';
	echo 'document.getElementById(\'frame\').src = \'properties.php?properties_filesize=true&file='.urlencode($process['file']).'\';';
	?>
}
Fly.window.ready = function() {
	Fly.window.size.set(Fly.window.size.get()[0],Math.min(window.top.window.innerHeight-128,Math.max(document.getElementById('page').scrollHeight,200)));
}
function contextMenu(e) {
	Fly.actionmenu(e,[
		[
			'Open',function() {
				window.top.system.command('run:<?php echo $process['file']; ?>');
			},{icon:`${Fly.core['RESOURCE']['URL']['ICONS']}run.svg`}
		],
		[
			'Open with...',function() {
				window.top.system.command('run:SprocketComputers.Utilities.OpenWith,file=<?php echo $process['file']; ?>');
			}
		]
	]);
}
function toggle(id,button) {
	id = document.getElementById(id);
	button = document.getElementById(button);
	if (id.style.display == 'block' ) {
		id.style.display = 'none';
		button.innerHTML = '▾';
	} else {
		id.style.display = 'block';
		button.innerHTML = '▴';
	}
}

var itemicon = '<?php echo $process['icon']; ?>';
var itemurl = '<?php echo $process['URL']; ?>';
<?php
if (strpos($process['mime'],'image/') !== false) {
echo '
function toggleicon() {
	var icon = document.getElementById("icon");
	if (icon.className.indexOf("image") != -1) {
		icon.className = icon.className.replace("image","icon");
		icon.src = itemicon;
	} else {
		icon.className = icon.className.replace("icon","image");
		icon.src = itemurl;
	}
}
';
$toggleicon = 'toggleicon()';
}
?>

var togglesized = false;
function togglesize() {
	var toggle = document.getElementById('toggleimg');
	var file = document.getElementById('file');
	var icon = document.getElementById('icon');
	var name = document.getElementById('name');
	if (!togglesized) {
		if (icon.className.indexOf("image") == -1) {
			icon.style.top = '4px';
			icon.style.width = '200px';
			icon.style.height = '200px';
			icon.style.transform = 'translate(0%,0%)';
			name.style.marginTop = '208px';
			name.style.marginLeft = '8px';
			name.style.width = '184px';
			name.style.textAlign = 'center';
			file.style.minHeight = `${216+name.offsetHeight}px`;
		} else {
			if (icon.offsetWidth > icon.offsetHeight || icon.offsetWidth == icon.offsetHeight) {
				icon.style.maxWidth = 'none';
				icon.style.maxHeight = 'none';
				icon.style.width = '192px';
				icon.style.left = '4px';
				icon.style.top = `${(icon.offsetHeight/2)+4}px`;
				icon.style.transform = 'translate(0%,-50%)';
				name.style.marginTop = `${icon.offsetHeight+8}px`;
				name.style.marginLeft = '8px';
				name.style.width = '184px';
				name.style.textAlign = 'center';
				file.style.minHeight = `${4+icon.offsetHeight}px`;
			} else {
				icon.style.maxWidth = 'none';
				icon.style.maxHeight = 'none';
				icon.style.height = '192px';
				icon.style.top = '4px';
				icon.style.left = '100px';
				icon.style.transform = 'translate(-50%,0%)';
				name.style.marginTop = '200px';
				name.style.marginLeft = '8px';
				name.style.width = '184px';
				name.style.textAlign = 'center';
				file.style.minHeight = '204px';
			}
		}

		icon.onclick = function() {};
		toggle.innerHTML = '<span style="position:absolute;top:50%;transform:translateY(-50%)">▴</span>';
		togglesized = true;
	} else {
		icon.style.top = '';
		icon.style.left = '';
		icon.style.width = '';
		icon.style.height = '';
		icon.style.maxHeight = '';
		icon.style.maxWidth = '';
		icon.style.transform = '';
		name.style.marginTop = '';
		name.style.marginLeft = '';
		name.style.width = '';
		name.style.textAlign = '';
		file.style.minHeight = '';

		icon.onclick = function() {<?php echo $toggleicon; ?>};
		toggle.innerHTML = '<span style="position:absolute;top:50%;transform:translateY(-50%)">▾</span>';
		togglesized = false;
	}
}
</script>
</head>
<body onload="onload()" oncontextmenu="return false;">

<div class="page" id="page">
<div oncontextmenu="contextMenu(event)" id="file" ondblclick="window.top.system.command('run:<?php echo htmlentities($process['file']); ?>')" class="file item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<img id="icon" onclick="<?php echo $toggleicon; ?>" class="<?php echo $icon_class; ?> FlyUiNoSelect" src="<?php echo $icon_src; ?>">
	<div id="toggleimg" style="position:absolute;top:4px;right:4px;height:calc(100% - 4px);" onclick="togglesize()" class="FlyUiMenuItem toggle"><span style="position:absolute;top:50%;transform:translateY(-50%)">▾</span></div>
	<div id="name" class="name"><?php echo $process['fname']; ?></div>
</div>

<div class="category FlyUiText FlyUiNoSelect">File</div>

<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Type</span>
	<span class="info"><?php echo htmlentities($process['description']); if ($process['type']=="file" && $process['extension'] !== '') {echo ' ('.$process['extension'].')';}?></span>
</div>

<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">MIME</span>
	<span class="info"><?php echo $process['mime']; ?></span>
</div>

<div ondblclick="toggle('size-full','togglesize');" class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Size</span>
	<div id="togglesize" onclick="toggle('size-full','togglesize');" class="FlyUiMenuItem toggle">▾</div>
	<span class="info">
		<span id="size-nice">Determining...</span>
		<span class="infos" id="size-full">Determining...</span>
	</span>
</div>

<div ondblclick="toggle('Adate','toggleAdate');" class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Accessed</span>
	<div id="toggleAdate" onclick="toggle('Adate','toggleAdate');" class="FlyUiMenuItem toggle">▾</div>
	<span class="info">
		<?php echo nicetime(fileatime($process['file'])); ?>
		<div class="infos" id="Adate"><?php echo date("l, F j, Y\ng:i A",fileatime($process['file'])); ?></div>
	</span>
</div>

<div ondblclick="toggle('Mdate','toggleMdate');" class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Modified</span>
	<div id="toggleMdate" onclick="toggle('Mdate','toggleMdate');" class="FlyUiMenuItem toggle">▾</div>
	<span class="info">
		<?php echo nicetime(filemtime($process['file'])); ?>
		<div class="infos" id="Mdate"><?php echo date("l, F j, Y\ng:i A",filemtime($process['file'])); ?></div>
	</span>
</div>

<!--
<div ondblclick="toggle('Cdate','toggleCdate');" class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Created</span>
	<div id="toggleCdate" onclick="toggle('Cdate','toggleCdate');" class="FlyUiMenuItem toggle">▾</div>
	<span class="info">
		<?php echo nicetime(filectime($process['file'])); ?>
		<div class="infos" id="Cdate"><?php echo date("l, F j, Y\ng:i A",filectime($process['file'])); ?></div>
	</span>
</div>
-->

<div style="cursor:pointer;" class="item FlyUiMenuItem FlyUiText FlyUiNoSelect" onclick="window.top.system.command('run:<?php echo htmlentities($process['fpath']); ?>')">
	<span class="title">Path</span>
	<span class="info"><?php echo preg_replace('#/+#','/',$process['fpath']); ?></span>
</div>

<?php

// Image - show width/height
if (strpos($process['mime'],'image/') !== false) {
	$size = getimagesize($process['file']);
	if ($size !== false) {
		?>
<div class="category FlyUiText FlyUiNoSelect">Image</div>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Width</span>
	<span class="info"><?php echo $size[0]; ?> pixels</span>
</div>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Height</span>
	<span class="info"><?php echo $size[1]; ?> pixels</span>
</div>
		<?php
		if (isset($size[5])) {
			?>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Color Channels</span>
	<span class="info"><?php echo $size[5]; ?></span>
</div>
			<?php
		}
		if (isset($size[6])) {
			?>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Bit Depth</span>
	<span class="info"><?php echo $size[6]; ?></span>
</div>
			<?php
		}
	}
}

// Video/Audio - get duration from HTML
if ((strpos($process['mime'],'audio/') !== false || strpos($process['mime'],'video/') !== false ) && in_array($process['extension'],['wav','ogg','mp3','m4a','mp4'])) {
	?>
<div class="category FlyUiText FlyUiNoSelect">Media</div>
<div class="item FlyUiMenuItem FlyUiText FlyUiNoSelect">
	<span class="title">Duration</span>
	<span class="info" id="media-duration">Determining...</span>
</div>
<script>
document.addEventListener('DOMContentLoaded',function() {
	function FormatTime(sec) {
		var hr = Math.floor(sec / 3600);
		var min = Math.floor((sec - (hr * 3600))/60);
		sec -= ((hr * 3600) + (min * 60));
		hr += ''; sec += ''; min += '';
		while (hr.length < 2) {hr = '0' + hr;}
		while (min.length < 2) {min = '0' + min;}
		while (sec.length < 2) {sec = '0' + sec;}
		hr = hr+':';
		return hr + min + ':' + sec;
	}

	var audio = new Audio();
	audio.preload = 'metadata';
	audio.src = '<?php echo $process['URL']; ?>';
	audio.onloadedmetadata = function() {
		document.getElementById('media-duration').innerText = FormatTime(Math.round(audio.duration));
	}
});
</script>
	<?php
}
?>

</div>
<iframe style="display:none;" id="frame" src=""></iframe>
</body>
</html>
<?php
exit;

size:
if (is_dir($_GET['file'])) {
	$fullsize = getFolderSize($_GET['file']);
	$filesize = formatFileSize($fullsize);
} else {
	$fullsize = filesize($_GET['file']);
	$filesize = formatFileSize($fullsize);
}
echo '
<script>
window.parent.document.getElementById(\'size-full\').innerHTML = \''.number_format($fullsize).' bytes\';
window.parent.document.getElementById(\'size-nice\').innerHTML = \''.$filesize.'\';
</script>
';
exit;
