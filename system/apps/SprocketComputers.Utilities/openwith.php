<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';

$process = FlyFileStringProcessor($_GET['file']);
if ($process == false || $process['type'] !== 'file') {
	echo '
	<script>
	window.top.shell.dialog(\'Invalid file\',\'The file specified is invalid.\',\'Open With\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\');
	Fly.window.close();
	</script>
	';
	exit;
}

$typesXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['TYPES']);

$rec = '';
$arec = [];

$lookup = FlyFileTypeLookup($process['extension']);
$extension = $process['extension'];
if ($process['registered'] && $lookup['action'] !== '') {
	if ($lookup['app'] != false) {
		$processapp = FlyFileStringProcessor($lookup['app']);
	} else {
		$processapp = FlyFileStringProcessor($lookup['action']);
	}
	if ($processapp != false && ($lookup['action'] != false && $lookup['action'] !== '')) {
		$content = '"'.$process['description'].'" files are associated with "'.$processapp['name'].'".';
		array_push($arec,$processapp['file']);
		//$rec = '<div id="123456" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$processapp["icon"].'">'.htmlentities($processapp["name"]).'</div>';
	} else {
		if ((string)$typesXML->$extension->action == '' && isset($typesXML->$extension->app)) {
			$processapp = FlyFileStringProcessor((string)$typesXML->$extension->app);
			if ($processapp != false) {
				$content = '"'.$process['description'].'" files are associated with "'.$processapp['name'].'", but the application does not support the file type.';
			} else {
				$content = '"'.$process['description'].'" files are associated with an application that does not exist.';
			}
		} else {
			$content = '"'.$process['description'].'" files have an action associated with them.';
		}
	}
} else {
	
	if ($process['registered']) {
		$content = '"'.$process['description'].'" files don\'t have any action associated with them.';
	} else {
		$content = 'This type of file doesn\'t have any action associated with it.';
	}

	
	$mimelookup = [];
	
	foreach ($typesXML as $type) {
		if (isset($type->mime)) {
			$mime = explode(',',(string)$type->mime);
			foreach ($mime as $m) {
				if (fnmatch($m,$process['mime'])) {
					array_push($mimelookup,$type);
				}
			}
		}
	}
	
	if (count($mimelookup) > 0) {
		$rec = '<div class="header">Recommended</div>';
		foreach ($mimelookup as $m) {
			$rand = rand();
			$lookup = FlyFileTypeLookup((string)$m->getName());
			if ($lookup['app'] != false) {
				$processapp = FlyFileStringProcessor($lookup['app']);
			} else {
				$processapp = FlyFileStringProcessor($lookup['action']);
			}
			if ($processapp != false) {
				array_push($arec,$processapp['file']);
				//$rec .= '<div id="'.$rand.'" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$processapp['icon'].'">'.htmlentities($processapp['name']).'</div>';
			}
		}
	}
	
}

$apps = FlyGetApps();
foreach ($apps as $a) {
	$m = simpleXML_load_file($_FLY['RESOURCE']['PATH']['APPS'].$a.'/ApplicationManifest.xml');
	if (isset($m->types->$extension)) {
		if (isset($m->types->$extension->action)) {
			array_push($arec,$a);
		}
	}
	if (isset($m->mime)) {
		$mi = explode(',',(string)$m->mime);
		foreach($mi as $mm) {
			if (fnmatch($mm,$process['mime'])) {
				array_push($arec,$a);
			}
		}
	}
}

$arec = array_unique($arec);
sort($arec);
if (count($arec) > 0) {
	$rec = '<div class="header">Recommended</div>';
	foreach ($arec as $r) {
		$rand = rand();
		$processrapp = FlyFileStringProcessor($r);
		$rec .= '<div id="'.$rand.'" onclick="select(this)" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$processrapp['icon'].'">'.htmlentities($processrapp['name']).'</div>';
	}
}

function getApps() {
	$path = FLY_APPS_PATH;
	$return = array();

	$ignore = array( 'cgi-bin', '.', '..' ); 

	$dh = @opendir( $path ); 

	while( false !== ( $file = readdir( $dh ) ) ){ 

	    if( !in_array( $file, $ignore ) ){ 

	        if( is_dir( "$path/$file" ) ){ 
				$return[$file] = strtolower(explode('.',$file,2)[1]);
			}

	    } 

	} 
	closedir( $dh ); 
	asort($return);
	return $return;
}

function drawApps($array) {
	global $_FLY;
	
	$return = '';
	foreach($array as $file => $app) {
		$process = FlyFileStringProcessor($file);
		if ($process != false) {
			$rand = rand();
			$return .= '<div id="'.$rand.'" onclick="select(this)" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process["icon"].'">'.htmlentities($process["name"]).'</div>';
			$xml = simpleXML_load_file(FLY_APPS_PATH.'/'.$file.'/ApplicationManifest.xml');
			if (isset($xml->masks)) {
				$m = '';
				foreach ($xml->masks->children() as $mask) {
					if (!in_array((string)$mask['hidden'],['true','on','yes'])) {
						$process = FlyFileStringProcessor($file.'.'.$mask['id']);
						if ($process != false) {
							$rand = rand();
							$m .= '<div id="'.$rand.'" onclick="select(this)" class="mask FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process["icon"].'">'.htmlentities($process["name"]).'</div>';
						}
					}
				}
			}
		}
	}
	return $return;
}
?>
<style>
.main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 96px;
	padding: 6px;
	overflow: auto;
}
#list {
	position: absolute;
	top: 128px;
	left: 0px;
	right: 0px;
	bottom: 96px;
	padding: 6px;
	overflow: auto;
	padding-top: -4px;
}
#button-ok {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 9px;
}
#button-close {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 117px;
}
.header {
	padding-top: 8px;
	padding-bottom: 2px;
	margin-bottom: 4px;
	font-size: 12px;
	font-weight: bold;
	position: sticky;
	top: -1px;
	left: 0px;
	background-color: #fff;
}
#content {
	padding-bottom: 8px;
}
.mask {
	display: none;
}
</style>
<script>
function onload() {
	Fly.window.title.set('Open With - <?php echo $process['name']; ?>');
	Fly.window.position.set(((window.top.window.innerWidth/2)-(window.top.document.getElementById(Fly.window.id).offsetWidth/2)),((window.top.window.innerHeight/2)-(window.top.document.getElementById(Fly.window.id).offsetHeight/2)-24));
	Fly.window.minimize.disable();
	window.top.shell.sound.system('question');
	document.getElementById('list').style.top = document.getElementById('content').offsetHeight+'px';
}
window.onresize = function() {
	document.getElementById('list').style.top = document.getElementById('content').offsetHeight+'px';
}
</script>
<script>
function toggleMasks(id,button) {
	var element = document.getElementById(id);
	var button = document.getElementById(button);
	if (element.style.display == 'none') {
		element.style.display = 'block';
		button.innerHTML = '▲';
	} else {
		element.style.display = 'none';
		button.innerHTML = '▼';
	}
}
function select(obj) {
	var selected = document.getElementsByClassName('FlyUiMenuItemActive');
	for (i = 0; i < selected.length; i++) { 
		deselect(selected[i]);
	}
	obj.className = 'FlyUiMenuItemActive FlyUiText FlyUiNoSelect';
}
function deselect(obj) {
	obj.className = 'FlyUiMenuItem FlyUiText FlyUiNoSelect';
}
function toggleMasks() {
	var label = document.getElementById('masks-toggle');
	if (label.innerHTML == 'Show masks') {
		label.innerHTML = 'Hide masks';
	} else {
		label.innerHTML = 'Show masks';
	}
}
</script>

<body onload="onload()" class="FlyUiNoSelect">

<div class="main FlyUiText FlyUiNoSelect FlyUiContent">
<div id="content">
<div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="margin-bottom:4px;"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo $process['icon']; ?>"><?php echo $process['name']; ?></div>
<?php echo $content; ?> Choose an application from the list below to open the file with:
</div>
</div>

<div id="list" class="FlyUiText">

<?php echo $rec; ?>
<div class="header">All applications</div>
<?php echo drawApps(getApps()); ?>
<a id="masks-toggle">Show masks</a>
</div>

<div id="" style="position:absolute;bottom:62px;left:9px;" class="FlyUiTextHover"><input id="always" onchange="" type="checkbox"> <label for="always">Always use this application</label></div>
<button onclick="Fly.window.close();" id="button-close"><img src="<?php echo FLY_ICONS_URL; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;"></button>
<button onclick="Fly.window.close();" id="button-ok" disabled><img src="<?php echo FLY_ICONS_URL; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;"></button>

</body>
</head>
</html>