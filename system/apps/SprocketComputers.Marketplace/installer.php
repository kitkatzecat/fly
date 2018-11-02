<?php
if (!empty($_GET['page'])) {
	if ($_GET['page'] == '1') {
		goto page1;
	}
	if ($_GET['page'] == '2') {
		goto page2;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
function onload() {
	Fly.window.position.set(((window.top.window.innerWidth/2)-204),((window.top.window.innerHeight/2)-186));
}
function close() {
	Fly.control.confirm('Cancel installation','Are you sure you want to cancel installation? No files on your computer will be affected if you cancel.','Cancel Installation','<?php echo FLY_ICONS_URL; ?>question.svg',closeconfirm);
}
function closeconfirm() {
	Fly.window.close();
}
Fly.window.onclose = close;
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	width: 100%;
	height: calc(100% - 48px);
}
</style>
</head>
<body onload="onload()">

<iframe id="main" frameborder="0" src="installer.php?page=1&file=<?php echo $_GET['file']; ?>"></iframe>

<button onclick="document.getElementById('main').contentWindow.next()" id="ButtonNext" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo FLY_ICONS_URL; ?>arrow-right.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button id="ButtonCancel" onclick="Fly.window.onclose()" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

</body>
</html>
<?php
exit;

page1:
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';
date_default_timezone_set("America/Chicago");

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

if (file_exists($_GET['file'])) {
	$zip = new ZipArchive;    
	if($zip -> open ($_GET['file']) === TRUE ) {
	    $xml = $zip -> getFromName('ApplicationManifest.xml');
			$xml = simpleXML_load_string($xml);
			if (strpos((string)$xml->icon,'%app_path%') !== false) {
				$time = time();
				mkdir(FLY_ROOT.'system/tmp/ApplicationInstaller_'.$time);
			    $zip -> extractTo(FLY_ROOT.'system/tmp/ApplicationInstaller_'.$time.'/',str_replace('%app_path%','',$xml->icon));
			    $zip -> extractTo(FLY_ROOT.'system/tmp/ApplicationInstaller_'.$time.'/','ApplicationManifest.xml');
				$icon = FLY_URL.'system/tmp/ApplicationInstaller_'.$time.'/'.str_replace('%icon_path',FLY_ICONS_URL,str_replace('%app_path%','',$xml->icon));
			} else {
				$icon = FlyStringReplaceConstants(str_replace('%icon_path%','%FLY_ICONS_URL%',$xml->icon));
			}
			if (isset($xml->types)) {
				foreach ($xml->types->children() as $itype) {
					if (strpos((string)$itype->icon,'%app_path%') !== false) {
					    $zip -> extractTo(FLY_ROOT.'system/tmp/ApplicationInstaller_'.$time.'/',str_replace('%app_path%','',$itype->icon));
					}
				}
			}
	}   
} else {
	echo '<script>window.top.shell.dialog("No package specified","No package was specified to install on the computer.","No Package");window.parent.Fly.window.close();';
	exit;
}
$process = FlyFileStringProcessor($_GET['file']);

if (isset($xml->masks)) {
	$m = '';
	foreach ($xml->masks->children() as $mask) {
		if (in_array((string)$mask['hidden'],['true','on','yes'])) {
			$m .= ' '.$mask->name.' (hidden),';
		} else {
			$m .= ' '.$mask->name.',';
		}
	}
	if ($m !== '') {
		$masks = str_lreplace(',','',$m);
	} else {
		$masks = ' none';
	}
} else {
	$masks = ' none';
}

if (isset($xml->extensions)) {
	$e = '';
	foreach ($xml->extensions->children() as $ext) {
		$e .= ' '.$ext['id'].',';
	}
	if ($e !== '') {
		$exts = str_lreplace(',','',$e);
	} else {
		$exts = ' none';
	}
} else {
	$exts = ' none';
}

if (isset($xml->types)) {
	$t = '';
	foreach ($xml->types->children() as $type) {
		$t .= ' '.$type->getName().',';
	}
	if ($t !== '') {
		$types = str_lreplace(',','',$t);
		$next = 'next_FileTypes();';
	} else {
		$types = ' none';
		$next = 'next_1();';
	}
} else {
	$types = ' none';
	$next = 'next_1();';
}

?>
<script>
function onload() {
	window.parent.Fly.window.title.set('Install "<?php echo htmlspecialchars($xml->name); ?>"');
}
function next() {
	<?php echo $next; ?>
}
function next_FileTypes() {
	window.location.href = 'installer.php?page=2&file=<?php echo $_GET['file']; ?>&time=<?php echo $time; ?>';
}
function next_1() {}
</script>
<style>
body {
	background-color: #fff;
	padding: 6px;
	overflow: auto;
}
#bgicon {
	float: right;
	width: 48px;
	height: 48px;
	margin-left: 8px;
	margin-bottom: 8px;
	opacity: 0.5;
}
#icon {
	width: 48px;
	height: 48px;
	margin-right: 8px;
	vertical-align: top;
}
.heading {
	font-weight: bold;
	font-size: 1.4em;
	margin-bottom: 8px;
}
</style>
</head>
<body onload="onload()" class="FlyUiText FlyUiNoSelect">

<span class="heading">Install "<?php echo htmlspecialchars($xml->name); ?>"<img id="bgicon" src="<?php echo FLY_ICONS_URL; ?>package.svg"></span>
<p>This will install the following application on your computer:</p>
<div class="FlyUiMenuItem" style="cursor:default;">
<img id="icon" src="<?php echo $icon; ?>">
<div style="display:inline-block;word-wrap:break-word;margin-top:5px;"><?php echo htmlspecialchars($xml->name); ?><br><span style="opacity:0.7;"><?php echo htmlspecialchars($xml->publisher); ?></span></div>
</div>

<p><?php echo htmlspecialchars($xml->description); ?></p>

<p id="moreinfo" style="display:none;">
<b>ID:</b> <?php echo $xml->id; ?><br>
<b>Version:</b> <?php echo htmlspecialchars($xml->version); ?><br>
<b>Publisher:</b> <?php echo htmlspecialchars($xml->publisher); ?><br>
<b>Date:</b> <?php echo date("F j, Y",(string)$xml->date); ?><br>
<b>Size:</b> ≈ <?php echo formatFileSize(filesize($_GET['file'])); ?>*<br>
<b>Masks:</b><?php echo $masks; ?><br>
<b>Extensions:</b><?php echo $exts; ?><br>
<b>File Types:</b><?php echo $types; ?><br>
<b>Package:</b> <?php echo $process['fpath'].'/'.$process['name']; ?><br>
<br>
<small>* Size is an estimate and may not accurately reflect the application's size when installed.</small>
</p>
<p><a onclick="document.getElementById('moreinfo').style.display='block';this.parentNode.removeChild(this);">More...</a></p>

</body>
</html><?php
exit;

page2:
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';
date_default_timezone_set("America/Chicago");

$xml = simpleXML_load_file(FLY_ROOT.'system/tmp/ApplicationInstaller_'.$_GET['time'].'/ApplicationManifest.xml');
$typesXML = simpleXML_load_file($_SERVER['DOCUMENT_ROOT'].'/system/reg/SprocketComputers.Fly/System.FileTypes.xml');

$types = '';
foreach ($xml->types->children() as $type) {
	$tprocess = FlyFileTypeLookup($type->getName());
	if ((string)$typesXML->$tprocess['type']->action == '') {
		$checked = ' checked';
	} else {
		$checked = '';
	}
	$types .= '
	<div onclick="toggleCheck(\''.$tprocess['type'].'\');" class="FlyUiMenuItem" style="cursor:default;position:relative;">
	<input id="'.$tprocess['type'].'" type="checkbox"'.$checked.' style="position:absolute;top:calc(50% - 8px);pointer-events:none;">
	<img class="icon" src="'.FlyStringReplaceConstants(str_replace('%app_path%',FLY_URL.'system/tmp/ApplicationInstaller_'.$_GET['time'].'/',$type->icon)).'">
	<div style="display:inline-block;word-wrap:break-word;margin-top:5px;">'.$type->description.'<br><span style="opacity:0.7;">'.$tprocess['type'].'</span></div>
	</div>
	';
	$selectall .= 'document.getElementById(\''.$tprocess['type'].'\').checked = true;';
	$deselectall .= 'document.getElementById(\''.$tprocess['type'].'\').checked = false;';
}
?>
<script>
function onload() {
	window.parent.Fly.window.title.set('Install "<?php echo htmlspecialchars($xml->name); ?>"');
}
function next() {
	
}
function toggleCheck(id) {
	var checkbox = document.getElementById(id);
	if (checkbox.checked) {
		checkbox.checked = false;
	} else {
		checkbox.checked = true;
	}
}
function selectAll() {
	<?php echo $selectall; ?>
}
function deselectAll() {
	<?php echo $deselectall; ?>
}
</script>
<style>
body {
	background-color: #fff;
	padding: 6px;
	overflow: auto;
}
#bgicon {
	float: right;
	width: 48px;
	height: 48px;
	margin-left: 8px;
	margin-bottom: 8px;
	opacity: 0.5;
}
.icon {
	width: 48px;
	height: 48px;
	margin-right: 4px;
	margin-left: 24px;
	vertical-align: top;
}
.heading {
	font-weight: bold;
	font-size: 1.4em;
	margin-bottom: 8px;
}
</style>
</head>
<body onload="onload()" class="FlyUiText FlyUiNoSelect">

<span class="heading">File Types<img id="bgicon" src="<?php echo FLY_ICONS_URL; ?>properties.svg"></span>
<p>Select file types to associate with "<?php echo str_replace(' ','&nbsp;',htmlspecialchars($xml->name)); ?>":</p>
<p><small><a style="margin-right:12px;" onclick="selectAll()">Select all</a><a onclick="deselectAll()">Deselect all</a></small></p>
<?php echo $types; ?>
<p><small>File types are selected by default if there is not already an application associated with that type. Associating this application with
an existing file type will overwrite the existing association.</small></p>
</body>
</html>