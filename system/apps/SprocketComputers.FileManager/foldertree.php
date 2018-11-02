<?php
include 'fly.php';
include 'fileprocessor.php';
function getFolders( $path = '.' , $level = 3){

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

$margin = ($level*4);

$return = '';

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor("$path/$file");
			if ($_GET['popout']=="") {
				$action = 'window.parent.document.getElementById(\'File-List-Frame\').contentWindow.navigate(\''."$path/$file".'\')';
			} else {
				if ($process != false) {
					$action = 'window.top.'.$process['action'];
				} else {
					$action = '';
				}
			}
			$return .= '<li onclick="toggle(\''."$path/$file".'\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick="'.$action.'"><img class="icon FlyUiNoSelect" src="'.$process['icon'].'">'.$file.'</li>';
			$return .= '<ul style="margin-left:'.$margin.'px;display:none;" id=\''."$path/$file".'\'>';
			$return .= getFolders("$path/$file", ($level+1));
			$return .= '</ul>';
        } 

    } 

} 
closedir( $dh ); 
return $return;
}
echo '<body>';
if ($_GET['popout']=="") {
	echo '<div style="height:24px;"></div><p class="FlyUiTextHighlight" style="position:fixed;top:0px;left:6px;padding-top:0px;">Folders</p><div class="FlyUiToolbarItem popout" onclick="popout()"><img src="'.FLY_ICONS_URL.'popout.svg" style="width:16px;height:16px;vertical-align:middle"></div>';
} else {
	echo '<div class="page">';
}
echo '<ul style="margin-left:12px;" id="folders">';
echo getFolders($_SERVER['DOCUMENT_ROOT']);
echo '</ul></div></body>';
?>
<script>
function toggle(id) {
	var obj = document.getElementById(id);
	if (obj.style.display == 'none') {
		obj.style.display = 'block';
	} else {
		obj.style.display = 'none';
	}
}
function popout() {
	window.parent.toggleFolders();
	window.top.system.command('run:SprocketComputers.FileManager.Folders,Fly_x='+(parseInt(window.parent.Fly.window.position.get()[0])+32)+',Fly_y='+(parseInt(window.parent.Fly.window.position.get()[1])+32), 240, 320, window.location.href);
}
</script>
<style>
ul {
	padding-left: 0px;
}
.item {
	list-style-type: none;
	font-size: 14px;
	line-height: 20px;
	cursor: pointer;
	white-space: nowrap;
}
.icon {
	width: 16px;
	height: 16px;
	margin-right: 2px;
	vertical-align: middle;
}
.popout {
	position: fixed;
	top: 6px;
	right: 10px;
}
.page {
	position: absolute;
	top: 0px;
	left: 0px;
	bottom: 0px;
	right: 0px;
	overflow: auto;
}
</style>