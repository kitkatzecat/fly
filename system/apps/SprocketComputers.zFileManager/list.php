<!DOCTYPE html>
<html>
<head>
<?php

/* Query string:
	p : Path - The path to the directory to display the contents of
	v : View (Optional) - Override the registry setting to use a specific view
*/

include 'Fly.Standard.php';
include 'Fly.Command.php';
include 'Fly.FileProcessor.php';
include 'Fly.Registry.php';

if (file_exists('views/'.$_GET['v'].'.js')) {
	$View = $_GET['v'];
} else {
	$View = FlyUserRegistryGet('View','SprocketComputers.FileManager');
}

$Output = '';

$Path = $_GET['p'];

$Keywords = json_decode(file_get_contents($_FLY['WORKING_PATH'].'keywords.json'),true);

if (array_key_exists($_GET['p'],$Keywords)) {
	echo '<script>window.parent.Nav(\''.$Keywords[$_GET['p']].'\');</script>';
	exit;
}

if ($Path == '.' || $Path == '..') {
	$Path = '%FLY.PATH%';
}
$Path = FlyVarsReplace($Path,false,FlyCoreVars($_FLY['PATH']));
$FolderProcess = FlyFileStringProcessor($Path);
if (is_dir($Path)) {
	$FolderList = FlyCommand('list:'.$Path);
	$FolderListArray = json_decode($FolderList['return']);
	if (count($FolderListArray) == 0) {
		// Directory is empty
		$Output = '<div class="title"><img class="title-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'folder.svg">This directory is empty.</div>';
		echo '
		<script>
		var Folder = JSON.parse(atob(\''.base64_encode(json_encode($FolderProcess)).'\'));
		var List = false;
		var Files = [];
		</script>
		';
	} else {
		// Directory is not empty
		foreach ($FolderListArray as &$Item) {
			$process = FlyFileStringProcessor($Path.'/'.$Item);
			$Item = $process;
		}
		echo '
		<script>
		var Folder = JSON.parse(atob(\''.base64_encode(json_encode($FolderProcess)).'\'));
		var List = JSON.parse(atob(\''.base64_encode(json_encode($FolderListArray)).'\'));
		var Files = JSON.parse(atob(\''.base64_encode($FolderList['return']).'\'));
		</script>
		';
	}
} else {
	// Path given is not to a directory
	if ($FolderProcess['type'] == 'file') {
		// Path given is to a file
		echo '<script>window.top.eval(atob(\''.base64_encode('system.command(\'run:'.$FolderProcess['file'].'\');').'\'));window.parent.Fly.window.message(\'"\'+atob(\''.base64_encode($FolderProcess['fname']).'\')+\'" has been opened\');window.parent.Nav(\''.$FolderProcess['path'].'\');</script>';
	} else {
		// Directory does not exist
		$Output = '<script>window.parent.Fly.window.title.set(\'File Manager - Not Found\');</script><div class="title"><img class="title-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg">The specified directory could not be found.</div><p class="description">Try checking the spelling of the path.</p><p>'.trimslashes(str_freplace($_FLY['PATH'],'./',$Path)).'</p><p>Or, try going <a><img class="inline-icon" style="margin-right:4px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'home.svg">Home</a>';
		echo '
		<script>
		var Folder = false;
		var List = false;
		var Files = false;
		</script>
		';
	}
}

echo '
<script>
var Output = atob(\''.base64_encode($Output).'\');
</script>
';

?>
<script>
function Load() {
	try {
		window.View();
	} catch(e) {
		console.log(e);
		document.body.innerHTML += '<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg">An error occurred while loading this view.</div><p class="description">Try refreshing. If the problem persists, change the view.</p><p>'+e+'</p>';
	}
	if (ImagePreviews) {
		window.parent.ImagePreviews.toggleOn();
	} else {
		window.parent.ImagePreviews.toggleOff();
	}
	window.parent.ImagePreviews.visible = ImagePreviews;

	CheckInterval = setInterval(Check,<?php echo FlyRegistryGet('RefreshInterval'); ?>);

	document.body.addEventListener('mousedown',function() {Deselect();});
}

function Deselect(item=false) {
	if (!item) {
		var selected = document.getElementsByClassName('FlyUiMenuItemActive');
		for (i = 0; i < selected.length; i++) { 
			selected[i].className = selected[i].className.replace('FlyUiMenuItemActive','FlyUiMenuItem');
		}
		Selected.className = Selected.className.replace('FlyUiMenuItemActive','FlyUiMenuItem');
		Selected = false;
		window.parent.SelectedFile = window.parent.CurrentLocation;
	} else {
		item.className = item.className.replace('FlyUiMenuItemActive','FlyUiMenuItem');
	}
}
function Select(item,e,obj) {
	if (Selected) {
		Deselect(Selected);
	}
	if (item.className.indexOf('FlyUiMenuItemActive') == -1) {
		item.className = item.className.replace('FlyUiMenuItem','FlyUiMenuItemActive');
	}
	Selected = item;
	window.parent.SelectedFile = obj;
	e.stopPropagation();
}
var Selected = false;

function Click(obj) {
	if (obj['isdir']) {
		window.parent.Nav(obj['file']);
	} else {
		window.top.system.command('run:'+obj['file']);
	}
}

ImagePreviews = eval('<?php echo FlyRegistryGet('ShowImagePreviews'); ?>');

function Icon(file) {
	var icon = document.createElement('div');
	icon.className = 'FlyUiNoSelect';
	icon.style.display = 'inline-block';

	if (file['mime'].indexOf('image/') != -1 && !!ImagePreviews) {
		icon.style.boxShadow = '0px 1px 4px #888';
		icon.style.backgroundSize = 'contain';
		icon.style.backgroundRepeat = 'no-repeat';
		icon.style.backgroundPosition = 'center center';
		icon.style.backgroundImage = 'url(\''+file['URL']+'\')';
	} else {
		icon.innerHTML = '<img style="width:100%;height:100%;" src="'+file['icon']+'">';
	}
	return icon;
}

var CheckInterval;
function Check() {
	Fly.command('list:'+Folder['file'],function(a){
		if (JSON.stringify(a.return) != JSON.stringify(Files) && !!a.return) {
			window.parent.Refresh(window.pageYOffset);
		}
	},{silent:true});
}
</script>
<script src="view/<?php echo $View; ?>.js?r=<?php echo rand(); ?>"></script>
<style>
html,body {
	min-height: 100%
}
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 28px;
	padding-bottom: 16px;
	padding-left: 6%;
	padding-right: 6%;
}
p.description {
	margin-top: -12px;
}
.title-icon {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
.inline-icon {
	width: 18px;
	height: 18px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
p {
	padding-left: 6%;
	padding-right: 6%;
}
</style>
</head>
<body class="FlyUiText FlyUiNoSelect" onload="Load()">

</body>
</html>