<!DOCTYPE html>
<html>
<head>
<?php
function ListMedia($path = '.', $level = 0) {
	$return = '';
	$return .= ListMediaFiles($path, $level);
	$return .= ListMediaFolder($path, $level);
	return $return;
}
function ListMediaFolder( $path = '.', $level = 0 ){
$ignore = array( 'cgi-bin', '.', '..' ); 
$dh = @opendir( $path ); 

$contents = array();
while( false !== ( $file = readdir( $dh ) ) ){ 
	array_push($contents,$file);
}
natcasesort($contents);

foreach ($contents as $file) {
    if( !in_array( $file, $ignore ) ){ 
        $spaces = str_repeat( '&nbsp;', ( $level * 4 ) ); 
        if( is_dir( "$path/$file" ) ){ 
            $return .= ListMedia("$path/$file", 1);
        }
    } 
} 
closedir( $dh ); 
return $return;
}
function ListMediaFiles( $path = '.', $level = 0 ){

global $searched;

if (!in_array(preg_replace('#/+#','/',$path),$searched)) {
array_push($searched,preg_replace('#/+#','/',$path));

$ignore = array( 'cgi-bin', '.', '..' ); 
$dh = @opendir( $path ); 

$contents = array();
while( false !== ( $file = readdir( $dh ) ) ){ 
	array_push($contents,$file);
}
natcasesort($contents);

foreach ($contents as $file) {
    if( !in_array( $file, $ignore ) ){ 
        $spaces = str_repeat( '&nbsp;', ( $level * 4 ) ); 
        if( !is_dir( "$path/$file" ) ){
			$process = FlyFileStringProcessor("$path/$file");
			if ($path == FLY_PATH) {
				$fname = 'Computer';
			} else {
				$fname = basename($path);
			}
			if ($process !== false && in_array($process['extension'],['wav','mp3','m4a'])) {
         	   $return .= '<div oncontextmenu="Fly.control.contextMenu(event,[\'<b>Play</b>\',\'Properties\'],[\'window.parent.window.parent.Load(\\\''.$process['URL'].'\\\',\\\''.$process['name'].'\\\')\',\'window.top.system.command(\\\'run:SprocketComputers.FileManager.Properties,file='."$path/$file".'\\\')\']);return false;" onclick="window.parent.window.parent.Load(\''.$process['URL'].'\',\''.$process['name'].'\');" class="FlyUiMenuItem FlyUiNoSelect"><img style="width:24px;height:24px;vertical-align:middle;margin-right:8px;" src="'.$process['icon'].'">'.$process['name'].'<span style="margin-left:8px;opacity:0.8;font-size:0.8em;">in '.$fname.'</span></div>'; 
			}
			if ($process !== false && in_array($process['extension'],['als'])) {
				$xml = simpleXML_load_file("$path/$file");
				if (is_dir(FlyVarsReplace(FlyStringReplaceConstants($xml->link)))) {
					$lsmedia = ListMedia(FlyVarsReplace(FlyStringReplaceConstants($xml->link)));
					if (strpos($lsmedia,'No media files were found in') === false) {
						$return .= $lsmedia;
					}
				}
			}
        } 
    } 
} 
closedir( $dh ); 
if ($return == '' && $level == 0) {
	return '<div class="FlyUiMenuItem" style="pointer-events:none;cursor:default;">No media files were found in "'.basename($path).'" or any subfolders</div>';
} else {
	return $return;
}
	
}

}


include 'fly.php';
include 'Fly.FileProcessor.php';
include 'Fly.Registry.php';

$location = FlyRegistryGet('Library');
$location = FLY_ROOT.$location;

$searched = [];
?>
<style>
body {
	margin: 4px;
	background: #ffffff;
}
.FlyUiMenuItem {
	padding: 4px;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
}
</style>
</head>
<body class="FlyUiText" onload="window.parent.document.getElementById('Media-List').style.opacity='1';">

<?php 
echo ListMedia($location);
?>

</body>
</html>