<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

if ($_GET['windowid']=='' || $_GET['windowid']=='undefined') {
	echo '<script>window.top.shell.dialog("Control Error - No ID","No window ID was passed to the control, and the control can not function without a window ID.","Control Error");Fly.window.close();</script>';
	exit;
}


if ($_GET['type'] == 'applicationChooser') {
	goto control_applicationChooser;
}

control_applicationChooser:
include 'fileprocessor.php';
function getApps() {

$path = FLY_APPS_PATH;
$return = '';

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor($file);
			if ($process != false) {
				$return .= '<div id="'.$file.'" data-publisher="'.$process['publisher'].'" data-name="'.$process['name'].'" data-icon="'.$process['icon'].'" onclick="select(\''.$file.'\');" ondblclick="window.top.system.eval64(\''.base64_encode('system.command(\'run:'.$process["file"].'\')').'\');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process["icon"].'">'.htmlentities($process["name"]).'</div>';
			}
		}

    } 

} 
closedir( $dh ); 
return $return;
}
?>
<script>
var TargetWindow = window.top.document.getElementById('<?php echo $_GET['windowid']; ?>');
var Chosen;
var Icon;
var Name;
var Publisher;

function onload() {
	Fly.window.title.set('Application Chooser');
	Fly.window.size.set(240,320);
	Fly.window.position.set((parseInt(TargetWindow.style.left.replace(/\D/g,''))+32),(parseInt(TargetWindow.style.top.replace(/\D/g,''))+32));
}
function select(id) {
	var select = document.getElementById(id);
	var selected = document.getElementsByClassName('FlyUiMenuItemActive');
	for (i = 0; i < selected.length; i++) { 
		selected[i].className = 'FlyUiMenuItem FlyUiText FlyUiNoSelect';
	}
	select.className = 'FlyUiMenuItemActive FlyUiText FlyUiNoSelect';
	
	Chosen = id;
	Icon = select.getAttribute('data-icon');
	Name = select.getAttribute('data-name');
	Publisher = select.getAttribute('data-publisher');
	document.getElementById('button-choose').disabled = false;
}
function choose() {
	TargetWindow.jsWindow.content.contentWindow.Fly.control.applicationChooser.setApplication('<?php echo $_GET['controlid']; ?>',Chosen,Name,Publisher,Icon);
	Fly.window.close();
}
</script>
<style>
.appsList {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	background-color: #ffffff;
	overflow: auto;
	padding: 3px;
}
.body {
	overflow: hidden;
}
.choose {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
</style>
</head>
<body onload="onload();">
<div class="appsList"><?php echo getApps(); ?></div>
<button class="choose" id="button-choose" onclick="choose();" disabled>Choose</button>
</body>


</html>