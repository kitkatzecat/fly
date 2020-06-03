<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.FileProcessor.php';
include 'Fly.Command.php';
?>
<style>
body {
	padding: 8px;
	padding-bottom: 0px;
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
	goto bookmarks;
} else if ($_GET['pane'] == 'search') {
	echo '
	<span id="title" class="FlyUiTextHighlight">Search</span>
	';
} else if ($_GET['pane'] == 'properties') {
	?>
	<style>
	.head {
		font-size: 12px;
		font-weight: bold;
	}
	#image {
		filter: drop-shadow(0px 0px 4px rgba(0,0,0,0.5));
		background-size: contain;
		background-repeat: no-repeat;
		background-position: center;
		width: 100%;
		height: 138px;
		margin-top: 4px;
		margin-bottom: 4px;
	}
	#properties {
		box-sizing: border-box;
		width: 100%;
		height: 34px !important;
		text-align: center;
		white-space: nowrap;
		text-overflow: ellipsis;
		margin-top: 12px;
	}
	</style>
	<span id="title" class="FlyUiTextHighlight">Properties</span>
	<img class="FlyUiNoSelect" id="icon" style="width:100%;height:auto;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/unknown.svg">
	<div class="FlyUiNoSelect" id="image"></div>
	<br>
	<div style="width:100%;max-width:100%;text-align:center;word-wrap:break-word;" class="FlyUiTextHighlight"><span id="name-value">Unknown</span></div>
	
	<div style="margin-top:8px;" class="FlyUiTextHighlight"><div class="head FlyUiNoSelect">Type</div><span id="type-value">Unknown<span></div>
	<div style="margin-top:8px;" class="FlyUiTextHighlight"><div class="head FlyUiNoSelect">Size</div><span id="size-nice">Determining...</span><span style="display:none;" id="size-full"></span></div>
	<div style="margin-top:8px;" class="FlyUiTextHighlight"><div class="head FlyUiNoSelect">Accessed</div><span id="accessed-value">Determining...</span></div>
	<div style="margin-top:8px;" class="FlyUiTextHighlight"><div class="head FlyUiNoSelect">Modified</div><span id="modified-value">Determining...</span></div>
	
	<div id="properties" class="FlyUiToolbarItem FlyUiNoSelect"><img style="width:16px;height:16px;vertical-align:middle;margin-right:4px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg">All Properties</div>
	<iframe style="display:none;" id="frame" src=""></iframe>
	<script>
	var CurrentItem = '';
	function ShowProperties(Item=false) {
		var Name = document.getElementById("name-value");
		var Type = document.getElementById("type-value");
		var Size = document.getElementById("size-nice");
		var Modified = document.getElementById("modified-value");
		var Accessed = document.getElementById("accessed-value");
		var Icon = document.getElementById("icon");
		var Image = document.getElementById("image");
		var Properties = document.getElementById("properties");

		if (Item == false) {
			Item = window.parent.SelectedFile;
		}

		if (typeof Item !== "undefined" && Item['file'] !== CurrentItem) {
			CurrentItem = Item['file'];

			Properties.onclick = function() {
				window.top.system.command(`run:<?php echo $_FLY['APP']['ID']; ?>.Properties,file=${Item['file']}`);
			}

			Name.innerText = Item['fname'];
			Size.innerText = 'Determining...';
			Modified.innerText = 'Determining...';
			Accessed.innerText = 'Determining...';

			if (Item['mime'].indexOf('image/') !== -1) {
				Icon.style.display = 'none';
				Image.style.display = 'inline-block';
				Image.style.backgroundImage = `url('${Item['URL']}')`;
			} else {
				Image.style.display = 'none';
				Icon.style.display = 'inline-block';
				Icon.src = Item['icon'];
			}

			Type.innerText = Item['description'];
			if (Item.hasOwnProperty('extension') && Item['extension'].length > 0) {
				Type.innerText += ` (${Item['extension']})`;
			}

			document.getElementById('frame').src = `properties.php?properties_filesize=true&file=${Item['file']}`;

			Fly.command(`php:return date("M j, Y",filemtime('${Item['file']}'));`,function(r) {
				if (r.return != false) {
					Modified.innerText = r.return;
				}
			});

			Fly.command(`php:return date("M j, Y",fileatime('${Item['file']}'));`,function(r) {
				if (r.return != false) {
					Accessed.innerText = r.return;
				}
			});
		}
	}
	document.addEventListener('DOMContentLoaded',function() {ShowProperties()});
	</script>
	<?php
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
<li onclick="toggle(\'root-desktop\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick="window.parent.Nav(atob(\''.base64_encode($_FLY['USER']['PATH'].'Desktop').'\'));"><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'desktop.svg">Desktop</li>
<ul style="margin-left:12px;display:block;" id=\'root-desktop\'>';
echo getFolders($_FLY['USER']['PATH'].'/Desktop',4);
echo '</ul>
<li onclick="toggle(\'root-documents\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick="window.parent.Nav(atob(\''.base64_encode($_FLY['USER']['PATH'].'Documents').'\'));"><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'type/docsfolder.svg">Documents</li>
<ul style="margin-left:12px;display:block;" id=\'root-documents\'>';
echo getFolders($_FLY['USER']['PATH'].'/Documents',4);
echo '</ul>
<li onclick="toggle(\'root-media\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick="window.parent.Nav(atob(\''.base64_encode($_FLY['USER']['PATH'].'Media').'\'));"><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'type/mediafolder.svg">Media</li>
<ul style="margin-left:12px;display:block;" id=\'root-media\'>';
echo getFolders($_FLY['USER']['PATH'].'/Media',4);
echo '</ul><hr style="margin-top:10px;margin-bottom:10px;">
<li onclick="toggle(\'root-computer\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick="window.parent.Nav(atob(\''.base64_encode($_FLY['PATH']).'\'));"><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'computer.svg">Computer</li>
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

bookmarks:
echo '<ul style="margin-left:8px;margin-top:6px;" id="folders">';
if (file_exists($_FLY['APP']['DATA'].'bookmarks.json')) {
	$json = json_decode(file_get_contents($_FLY['APP']['DATA'].'bookmarks.json'),true);
	if (count($json) > 0) {
		foreach ($json as $b) {
			$process = FlyFileStringProcessor(FlyVarsReplace($b));
			if ($process) {
				echo '<li class="FlyUiTextHover FlyUiNoSelect item" onclick="window.parent.Nav(atob(\''.base64_encode($process['file']).'\'));"><img class="icon FlyUiNoSelect" src="'.$process['icon'].'">'.$process['name'].'</li>';
			}
		}
	}
} else {
	file_put_contents($_FLY['APP']['DATA'].'bookmarks.json','[]');
}
?>
</ul>
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
	margin-left: -8px;
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

?>