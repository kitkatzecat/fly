<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';

function getFolders( $path = '.' , $level = 3){

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

$margin = ($level*4);

$return = '';

$contents = array();
while( false !== ( $file = readdir( $dh ) ) ){ 
	array_push($contents,$file);
}
natcasesort($contents);

foreach ($contents as $file) {

    if( !in_array( $file, $ignore ) ){ 

        if( is_dir( "$path/$file" ) ){ 
			$rand = rand();
			$process = FlyFileStringProcessor("$path/$file");
			$return .= '<div onclick="toggle(\''."$path/$file".'\');select(\''."$path/$file".'-'.$rand.'\');" data-path="'."$path/$file".'" data-icon="'.$process['icon'].'" data-basename="'.basename($file).'" data-user="'.str_replace(FLY_ROOT,'./',"$path/$file").'" class="FlyUiMenuItem FlyUiNoSelect item" id=\''."$path/$file".'-'.$rand.'\'><img class="icon FlyUiNoSelect" src="'.$process['icon'].'">'.$file.'</div>';
			$return .= '<div style="margin-left:'.$margin.'px;display:none;" id=\''."$path/$file".'\'>';
			$return .= getFolders("$path/$file", ($level+1));
			$return .= '</div>';
        } 

    } 

} 
closedir( $dh ); 
return $return;
}
?>
<style>
body {
	background-color: #fff;
	overflow: auto;
}
.icon {
	width: 24px;
	height: 24px;
	margin-right: 4px;
	vertical-align: middle;
}
.item {
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	width: 256px;
}
#computer, #media, #documents, #desktop {
	width: auto;
}
</style>
<script>
function toggle(id) {
	var obj = document.getElementById(id);
	if (obj.style.display == 'none') {
		obj.style.display = 'block';
	} else {
		obj.style.display = 'none';
	}
}
var activeItem = {className:''};
function select(id) {
	var item = document.getElementById(id);
	if (item.className.indexOf('FlyUiMenuItemActive') == -1) {
		activeItem.className = 'FlyUiMenuItem FlyUiNoSelect item';
		activeItem = item;
		item.className = item.className.replace('FlyUiMenuItem','FlyUiMenuItemActive');
		
		var current = item.getAttribute('data-path');
		var user = item.getAttribute('data-user');
		var basename = item.getAttribute('data-basename');
		var icon = item.getAttribute('data-icon');
		window.parent.setLocation(current,user,basename,icon);
	}
}
</script>
</head>
<body onload="select('desktop')">
<div onclick="toggle('desktop-folders');select('desktop');" data-path="<?php echo $_FLY['USER']['PATH'].'Desktop/'; ?>" data-icon="<?php echo FLY_ICONS_URL; ?>desktop.svg" data-basename="Desktop" data-user="<?php echo str_replace(FLY_ROOT,'./',$_FLY['USER']['PATH'].'Desktop'); ?>" id="desktop" class="FlyUiMenuItem FlyUiNoSelect item"><img class="icon FlyUiNoSelect" src="<?php echo FLY_ICONS_URL; ?>desktop.svg">Desktop</div>
<div style="margin-left:12px;display:block;" id="desktop-folders">
<?php
echo getFolders($_FLY['USER']['PATH'].'Desktop/',4);
?>
</div>

<div onclick="toggle('documents-folders');select('documents');" data-path="<?php echo $_FLY['USER']['PATH'].'Documents/'; ?>" data-icon="<?php echo FLY_ICONS_URL; ?>type/docsfolder.svg" data-basename="Documents" data-user="<?php echo str_replace(FLY_ROOT,'./',$_FLY['USER']['PATH'].'Documents'); ?>" id="documents" class="FlyUiMenuItem FlyUiNoSelect item"><img class="icon FlyUiNoSelect" src="<?php echo FLY_ICONS_URL; ?>type/docsfolder.svg">Documents</div>
<div style="margin-left:12px;display:block;" id="documents-folders">
<?php
echo getFolders($_FLY['USER']['PATH'].'Documents/',4);
?>
</div>

<div onclick="toggle('media-folders');select('media');" data-path="<?php echo $_FLY['USER']['PATH'].'Media/'; ?>" data-icon="<?php echo FLY_ICONS_URL; ?>type/mediafolder.svg" data-basename="Media" data-user="<?php echo str_replace(FLY_ROOT,'./',$_FLY['USER']['PATH'].'Media'); ?>" id="media" class="FlyUiMenuItem FlyUiNoSelect item"><img class="icon FlyUiNoSelect" src="<?php echo FLY_ICONS_URL; ?>type/mediafolder.svg">Media</div>
<div style="margin-left:12px;display:block;" id="media-folders">
<?php
echo getFolders($_FLY['USER']['PATH'].'Media/',4);
?>
</div>
<hr style="width:95%;">
<div onclick="toggle('computer-folders');select('computer');" data-path="<?php echo FLY_ROOT; ?>" data-icon="<?php echo FLY_ICONS_URL; ?>computer.svg" data-basename="Computer" data-user="./" id="computer" class="FlyUiMenuItem FlyUiNoSelect item"><img class="icon FlyUiNoSelect" src="<?php echo FLY_ICONS_URL; ?>computer.svg">Computer</div>
<div style="margin-left:12px;display:none;" id="computer-folders">
<?php
echo getFolders($_SERVER['DOCUMENT_ROOT'],4);
?>
</div>
</body>
</html>