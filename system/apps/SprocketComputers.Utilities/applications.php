<html>
<head>
<?php
include 'fly.php';
include 'fileprocessor.php';
include 'registry.php';

function getApps() {
	$path = FLY_APPS_PATH;
	$return = array();

	$ignore = array( 'cgi-bin', '.', '..' ); 

	$dh = @opendir( $path ); 

	while( false !== ( $file = readdir( $dh ) ) ){ 

	    if( !in_array( $file, $ignore ) ){ 

	        if( is_dir( "$path/$file" ) ){ 
				$return[$file] = explode('.',$file,2)[1];
			}

	    } 

	} 
	closedir( $dh ); 
	asort($return);
	return $return;
}

function drawApps($array) {
	$iconSize = FlyRegistryGet('IconSize','SprocketComputers.FileManager');
	if ($iconSize == 'list') {
		$br = '';
	} else if ($iconSize == 'slist') {
		$br = '';
	} else {
		$br = '<br>';
	}
	$return = '';
	foreach($array as $file => $app) {
		$process = FlyFileStringProcessor($file);
		if ($process != false) {
			$return .= '<div class="item FlyUiText FlyUiNoSelect" ondblclick="window.top.system.eval64(\''.base64_encode('system.command(\'run:'.$process["file"].'\')').'\');"><img class="icon FlyUiNoSelect" src="'.$process["icon"].'">'.$br.htmlentities($process["name"]).'</div>';
			$xml = simpleXML_load_file(FLY_APPS_PATH.'/'.$file.'/ApplicationManifest.xml');
			if (isset($xml->masks)) {
				foreach ($xml->masks->children() as $mask) {
					if (!in_array((string)$mask['hidden'],['true','on','yes'])) {
						$process = FlyFileStringProcessor($file.'.'.$mask['id']);
						if ($process != false) {
							$return .= '<div mask="'.$file.'" class="item FlyUiText FlyUiNoSelect" ondblclick="window.top.system.eval64(\''.base64_encode('system.command(\'run:'.$process["file"].'\')').'\');"><img class="icon FlyUiNoSelect" src="'.$process["icon"].'">'.$br.htmlentities($process["name"]).'</div>';
						}
					}
				}
			}
		}
	}
	return $return;
}

$iconSize = FlyRegistryGet('IconSize','SprocketComputers.FileManager');
if ($iconSize == 'list') {
	$fontSize = '16';
	$itemWidth = '50%; min-width: 302px; white-space: nowrap; text-overflow: ellipsis';
	$itemHeight = '58px';
	$iconSize = '48';
	$itemAlign = 'left';
	$iconStyles = 'vertical-align: middle;margin-right: 6px;';
} else if ($iconSize == 'slist') {
	$fontSize = '14';
	$itemWidth = '100%; white-space: nowrap; text-overflow: ellipsis';
	$itemHeight = '42px';
	$iconSize = '32';
	$itemAlign = 'left';
	$iconStyles = 'vertical-align: middle;margin-right: 6px;';
} else {
	if (intval($iconSize) < 32) {
		$fontSize = '11';
	} else {
		$fontSize = '16';
	}
	$itemWidth = ($iconSize+52).'px';
	$itemHeight = ($iconSize+48).'px';
	$itemAlign = 'center';
	$iconStyles = '';
}
?>
<script>
var masksButton;
function onload() {
	var Actionbar = new Fly.actionbar();
	Actionbar.style.position = 'absolute';
	Actionbar.style.top = '0px';
	Actionbar.style.left = '0px';
	Actionbar.style.right = '0px';	
	Actionbar.add({type:'button',text:'Manage Applications',icon:'<?php echo FLY_ICONS_URL; ?>options.svg',action:function(){window.top.system.command('run:SprocketComputers.Options,page=filesapps,action=installed');}});
	Actionbar.add({type:'button',align:'right',text:'Refresh',icon:'<?php echo FLY_ICONS_URL; ?>refresh.svg',action:function(){window.location.reload();}});
	masksButton = Actionbar.add({type:'button',align:'right',text:'Show Masks',action:toggleMasks});
	document.body.appendChild(Actionbar);
	
	masksButton.toggleOn();
}
var masksVisible = true;
function toggleMasks() {
	var masks = document.querySelectorAll('div[mask]');
	if (masksVisible) {
		for (i = 0; i < masks.length; i++) { 
			masks[i].style.display = 'none';
		}
		masksButton.toggleOff();
		masksVisible = false;
	} else {
		for (i = 0; i < masks.length; i++) { 
			masks[i].style.display = 'inline-block';
		}
		masksButton.toggleOn();
		masksVisible = true;
	}
}
</script>
<style>
#list {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	overflow: auto;
	background-color: #fff;
}
<?php echo '
.item {
	display: inline-block;
	float: left;
	width: '.$itemWidth.';
	height: '.$itemHeight.';
	border: 1px solid transparent;
	text-align: '.$itemAlign.';
	padding: 4px;
	word-wrap: break-word;
	box-sizing: border-box;
	cursor: default;
	overflow: hidden;
	font-size: '.$fontSize.'px;
}
.item:hover {
	border-radius: 5px;
	border-color: rgba(180, 180, 255, .5);
	border-width: 1px;
	border-style: solid;
	background: linear-gradient(0deg, rgba(180, 180, 255, .5), rgba(220, 220, 255, .5));
}
.item:active {
	border-radius: 5px;
	border-color: rgba(150, 150, 225, .5);
	border-width: 1px;
	border-style: solid;
	background: linear-gradient(0deg, rgba(150, 150, 225, .5), rgba(190, 190, 255, .5));
	cursor: alias;
}
.icon {
	width: '.$iconSize.'px;
	height: '.$iconSize.'px;
	margin-bottom: 4px;
	pointer-events: none;
	'.$iconStyles.'
}
'; ?>
</style>
</head>
<body onload="onload()">

<div id="list" class="FlyUiText FlyUiNoSelect">
<?php echo drawApps(getApps()); ?>
</div>

</body>
</html>