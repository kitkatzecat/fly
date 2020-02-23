<?php
if (in_array($_GET['list'],["true","on","yes"])) {
	goto alist;
}
?>
<!DOCTYPE html>
<html>
<style>
body {
	margin: 3px;
}
</style>
<script>
function toggle() {
	var jump = window.top.ui.jump;
	jump.style.display = "block";
	var varheight = document.getElementById('list').offsetHeight;
	jump.style.height = (varheight+48)+'px';
	jump.style.maxHeight = (window.top.window.innerHeight-44)+'px';
	window.top.document.getElementById('fly-ui-jump-list').style.height = (varheight+6)+'px';
	window.top.document.getElementById('fly-ui-jump-list').style.maxHeight = (window.top.window.innerHeight-86)+'px';
	document.getElementById('list').style.maxHeight = (window.top.window.innerHeight-89)+'px';
	document.getElementById('applications-back').style.top = (window.innerHeight-67)+'px';
	document.getElementById('applications').style.height = (window.innerHeight-67)+'px';
	setTimeout(function() {window.top.ui.jump.coverDiv.style.backgroundColor='rgba(0,0,0,.3)';}, 10);
}
</script>
<body onload="toggle()" class="FlyUiNoSelect">
<?php
include 'Fly.Core.php';
include 'Fly.Constants.php';
include 'Fly.FileProcessor.php';
include 'Fly.Standard.php';
include 'Fly.Actionmenu.php';
include 'Fly.Registry.php';

/*$jumpXML = new DOMDocument();
$jumpXML->load($_FLY['USER']['XML']);
$feed = array();
foreach ($jumpXML->getElementsByTagName('item') as $node) {
	$item = array ( 
		'url' => $node->nodeValue,
		);
	array_push($feed, $item);
}*/
$list = '';

$pinned = json_decode(FlyUserRegistryGet('Jump','SprocketComputers.Utilities'),true);
foreach ($pinned as $item) {
	if ($item == 'separator' || $item == 'divider' || $item == '') {
		$list .= '<hr>';
	} else {
		$process = FlyFileStringProcessor(FlyVarsReplace(FlyStringReplaceConstants($item)));
		$rand = rand();
		if ($process) {
			$list .= '<div id="'.$rand.'" oncontextmenu="Fly.actionmenu(event,[[\'<b>Open</b>\',function() {document.getElementById(\''.$rand.'\').onclick();},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'run.svg\'}],[\'Unpin from Jump\',function() {window.top.ui.jump.toggle();window.top.system.command(\'run:SprocketComputers.Utilities.PinJump,noreplace,file='.$item.'\');},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'pin-no.svg\'}],[\'\'],[\'Applications Options\',function(){window.top.system.command(\'run:SprocketComputers.Options,page=filesapps,action=installed\');window.top.ui.jump.toggle();},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'options.svg\'}]]);return false;" onclick="window.top.ui.jump.toggle();window.top.system.eval64(\''.base64_encode('system.command(\'run:'.$item.'\')').'\');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="'.$process["icon"].'">'.htmlentities($process["fname"]).'</div>';
		} else {
			$list .= '<div id="'.$rand.'" oncontextmenu="Fly.actionmenu(event,[[\'<b>Open</b>\',function() {document.getElementById(\''.$rand.'\').onclick();},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'run.svg\'}],[\'Unpin from Jump\',function() {window.top.ui.jump.toggle();window.top.system.command(\'run:SprocketComputers.Utilities.PinJump,noreplace,file='.$item.'\');},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'pin-no.svg\'}],[\'\'],[\'Applications Options\',function(){window.top.system.command(\'run:SprocketComputers.Options,page=filesapps,action=installed\');window.top.ui.jump.toggle();},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'options.svg\'}]]);return false;" onclick="window.top.ui.jump.toggle();window.top.system.eval64(\''.base64_encode('system.command(\'run:'.$item.'\')').'\');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'type/unknown.svg">'.htmlentities(basename($item)).'</div>';
		}
	}
}
if (count($pinned) > 0) {
	$list .= '<hr>';
}

date_default_timezone_set("America/Chicago");
$flyconfig = $_FLY_CONFIG;
$list .= '<div oncontextmenu="return false;" onclick="window.scrollTo(295,0);document.getElementById(\'applications\').src=\''.CURRENT_URL.'?list=true\'" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'arrow-right.svg">All applications</div>';
$list .= '<hr><div oncontextmenu="return false;" onclick="window.top.ui.jump.toggle();window.top.system.logout();" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'lock.svg">Log out '.htmlspecialchars($_FLY['USER']['NAME']).'</div>';
$list .= '<div oncontextmenu="return false;" onclick="window.top.ui.jump.toggle();window.top.system.command(\'run:SprocketComputers.Utilities.AboutFly\');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'fly.svg">About Fly</div>';

echo '<div id="list" style="overflow:auto;">'.$list.'</div>';
echo '
<iframe style="position:absolute;top:0px;left:295px;width:295px;height:300px;z-index:5;" frameborder="0" id="applications" scrolling="auto" src=""></iframe>
<div style="position:absolute;top:32px;left:295px;width:295px;height:40px;z-index:1;text-align:center;" class="FlyUiText">Loading...</div>
<div style="position:absolute;left:298px;width:289px;top:0px;" id="applications-back"><hr><div onclick="window.scrollTo(0,0);" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'arrow-left.svg">Back</div></div>
';
?>
</html>
<?php
exit;
alist:
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
			$return .= '<div id="'.$rand.'" oncontextmenu="Fly.actionmenu(event,[[\'<b>Open</b>\',function() {document.getElementById(\''.$rand.'\').onclick();},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'run.svg\'}],[\'Pin to Jump\',function() {window.top.ui.jump.toggle();window.top.system.command(\'run:SprocketComputers.Utilities.PinJump,file='.$file.'\');},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'pin.svg\'}],[\'\'],[\'Applications Options\',function(){window.top.system.command(\'run:SprocketComputers.Options,page=filesapps,action=installed\');window.top.ui.jump.toggle();},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'options.svg\'}]]);return false;" onclick="window.top.ui.jump.toggle();window.top.system.eval64(\''.base64_encode('system.command(\'run:'.$process["file"].'\')').'\');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process["icon"].'">'.htmlentities($process["name"]).'';
			$xml = simpleXML_load_file(FLY_APPS_PATH.'/'.$file.'/ApplicationManifest.xml');
			if (isset($xml->masks)) {
				$m = '';
				foreach ($xml->masks->children() as $mask) {
					if (!in_array((string)$mask['hidden'],['true','on','yes'])) {
						$process = FlyFileStringProcessor($file.'.'.$mask['id']);
						if ($process != false) {
							$rand = rand();
							$m .= '<div id="'.$rand.'" oncontextmenu="Fly.actionmenu(event,[[\'<b>Open</b>\',function() {document.getElementById(\''.$rand.'\').onclick();},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'run.svg\'}],[\'Pin to Jump\',function() {window.top.ui.jump.toggle();window.top.system.command(\'run:SprocketComputers.Utilities.PinJump,file='.$file.'\');},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'pin.svg\'}],[\'\'],[\'Applications Options\',function(){window.top.system.command(\'run:SprocketComputers.Options,page=filesapps,action=installed\');window.top.ui.jump.toggle();},{icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'options.svg\'}]]);event.stopPropagation();return false;" onclick="window.top.ui.jump.toggle();window.top.system.eval64(\''.base64_encode('system.command(\'run:'.$process["file"].'\')').'\');event.stopPropagation();" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process["icon"].'">'.htmlentities($process["name"]).'</div>';
						}
					}
				}
				if ($m !== '') {
					$return .= '<div class="FlyUiMenuItem" id="'.$file.'-masks-button" style="width:22px;height:24px;float:right;line-height:16px;font-size:12px;" onclick="toggleMasks(\''.$file.'-masks\',this.id);event.stopPropagation();">▼</div><div style="display:none;" id="'.$file.'-masks">'.$m.'</div></div>';
				} else {
					$return .= '</div>';
				}
			} else {
				$return .= '</div>';
			}
		}
	}
	return $return;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Core.php';
include 'Fly.Constants.php';
include 'Fly.Theme.php';
include 'Fly.FileProcessor.php';
include 'Fly.Actionmenu.php';
FlyTheme(['text','controls']);

?>
<style>
body {
	margin: 3px;
	background-color: #ffffff;
}
</style>
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

</script>
</head>
<body class="FlyUiText">
<?php echo drawApps(getApps()); ?>
<hr>
<div onclick="window.top.ui.jump.toggle();window.top.system.command('run:SprocketComputers.Utilities.Applications');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Applications</div>
</body>
</html>
