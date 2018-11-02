<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';
?>
<style>
body {
	padding: 8px;
}
#title {
	font-size: 1.2em;
}
#main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	overflow: auto;
}
#close {
	position: fixed;
	top: 2px;
	right: 0px;
	width: 16px;
	height: 16px;
	padding-top: 6px;
	padding-right: 4px;
	text-align: center;
}
</style>
</head>
<body>

<?php
if ($_GET['pane'] == 'folders') {
	echo '
	<span id="title" class="FlyUiTextHighlight">Folders</span>
	';
	goto folders;
} else if ($_GET['pane'] == 'bookmarks') {
	echo '
	<span id="title" class="FlyUiTextHighlight">Bookmarks</span>
	<div onclick="window.parent.Dialog.open(\'dialogs.php?dialog=bookmark_add\',\'Add Bookmark\');" class="FlyUiToolbarItem FlyUiNoSelect" style="position:fixed;bottom:2px;left:2px;right:2px;text-align:center;"><img style="width:16px;height:16px;vertical-align:middle;margin-right:4px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'mark-plus.svg">Add bookmark</div>
	';
} else if ($_GET['pane'] == 'search') {
	echo '
	<span id="title" class="FlyUiTextHighlight">Search</span>
	';
} else if ($_GET['pane'] == 'properties') {
	echo '
	<style>
	.head {
		font-size: 12px;
		font-weight: bold;
	}
	</style>
	<span id="title" class="FlyUiTextHighlight">Properties</span>
	<img class="FlyUiNoSelect" style="width:100%;height:auto;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'type/mediafolder.svg"><br><div style="width:100%;max-width:100%;text-align:center;word-wrap:break-word;" class="FlyUiTextHighlight"><span id="name-value">Media</span></div>
	
	<div style="margin-top:8px;" class="FlyUiTextHighlight"><div class="head FlyUiNoSelect">Type</div><span id="type-value">Folder<span></div>
	<div style="margin-top:8px;" class="FlyUiTextHighlight"><div class="head FlyUiNoSelect">Size</div><span id="size-value">100 MB</span></div>
	<div style="margin-top:8px;" class="FlyUiTextHighlight"><div class="head FlyUiNoSelect">Modified</div><span id="modified-value">Jun 20, 2017</span></div>
	<div style="margin-top:8px;" class="FlyUiTextHighlight"><div class="head FlyUiNoSelect">Created</div><span id="created-value">Jun 20, 2017</span></div>
	
	<div onclick="" class="FlyUiToolbarItem FlyUiNoSelect" style="position:fixed;bottom:2px;left:2px;right:2px;text-align:center;"><img style="width:16px;height:16px;vertical-align:middle;margin-right:4px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'arrow-right.svg">More Properties</div>
	<script>
	var Values = {
		Name: document.getElementById("name-value"),
		Type: document.getElementById("type-value"),
		Size: document.getElementById("size-value"),
		Modified: document.getElementById("modified-value"),
		Created: document.getElementById("created-value")
	};
	if (typeof window.parent.SelectedFile !== "undefined") {
		
	}
	</script>
	';
} else {
	echo '<div style="padding:6px;" class="FlyUiTextHighlight">Pane not found</div>';
}

ext:
?>

<div class="FlyUiTextHover" id="close" onclick="window.parent.Pane.hide();">𐌗</div>
</body>
</html>

<?php
exit;

folders:
function getFolders( $path = '.' , $level = 3){

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

$margin = ($level*4);

$return = '';

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor("$path/$file");
			if ($process != false) {
				$action = 'window.parent.Nav(atob(\''.base64_encode($process['file']).'\'));';
			} else {
				$action = '';
			}
			$rand = rand();
			$return .= '<li onclick="toggle(\''.$rand.'\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick="'.$action.'"><img class="icon FlyUiNoSelect" src="'.$process['icon'].'">'.$file.'</li>';
			$return .= '<ul style="margin-left:'.$margin.'px;display:none;" id=\''.$rand.'\'>';
			$return .= getFolders("$path/$file", ($level+1));
			$return .= '</ul>';
        } 

    } 

} 
closedir( $dh ); 
return $return;
}
echo '<div id="main">
<ul style="margin-left:8px;margin-top:6px;" id="folders">
<li class="FlyUiTextHover FlyUiNoSelect item" ondblclick=""><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'home.svg">Home</li>
<hr style="margin-top:10px;margin-bottom:10px;">
<li onclick="toggle(\'root-desktop\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick=""><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'desktop.svg">Desktop</li>
<ul style="margin-left:12px;display:block;" id=\'root-desktop\'>';
echo getFolders($_FLY['USER']['PATH'].'/Desktop',4);
echo '</ul>
<li onclick="toggle(\'root-documents\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick=""><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'type/docsfolder.svg">Documents</li>
<ul style="margin-left:12px;display:block;" id=\'root-documents\'>';
echo getFolders($_FLY['USER']['PATH'].'/Documents',4);
echo '</ul>
<li onclick="toggle(\'root-media\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick=""><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'type/mediafolder.svg">Media</li>
<ul style="margin-left:12px;display:block;" id=\'root-media\'>';
echo getFolders($_FLY['USER']['PATH'].'/Media',4);
echo '</ul><hr style="margin-top:10px;margin-bottom:10px;">
<li onclick="toggle(\'root-computer\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick=""><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'computer.svg">Computer</li>
<ul style="margin-left:12px;display:none;" id=\'root-computer\'>';
echo getFolders($_FLY['PATH'],4);
echo '</ul>
</ul></div>';
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
</script>
<style>
ul {
	padding-left: 0px;
}
.item {
	display: block;
	list-style-type: none;
	font-size: 14px;
	line-height: 20px;
	cursor: pointer;
	white-space: nowrap;
	padding-top: 6px;
	margin-top: -8px;
	padding-bottom: 6px;
	margin-bottom: -8px;
}
.icon {
	width: 16px;
	height: 16px;
	margin-right: 2px;
	vertical-align: middle;
}
</style>
<?php
goto ext;