<?php
if (in_array($_GET['properties'],['true','on','yes'])) {
	echo '<script>window.location.href=\'properties.php?'.$_SERVER['QUERY_STRING'].'\';</script>';
	exit;
}
if (in_array($_GET['rename'],['true','on','yes']) || in_array($_GET['delete'],['true','on','yes'])) {
	echo '<script>window.location.href=\'fileact.php?'.$_SERVER['QUERY_STRING'].'\';</script>';
	exit;
}
if (in_array($_GET['control'],['true','on','yes'])) {
	echo '<script>window.location.href=\'control.php?'.$_SERVER['QUERY_STRING'].'\';</script>';
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

if ($_GET['path']=="") {
	$path = $_SERVER['DOCUMENT_ROOT'];
} else {
	$path = $_GET['path'];
}

if (in_array($_GET['seamless'],['true','yes','on'])) {
	echo '<script>window.location = \'filelist.php?'.$_SERVER['QUERY_STRING'].'&path='.$path.'\';</script>';
	exit;
}

if ($_GET['folders']=="") {
	$folders = '';
} else {
	$folders = 'toggleFolders();';
}
if ($_GET['views']=="") {
	$views = '';
} else {
	$views = 'setTimeout(toggleViews,400);';
}
?>
<style>
.pathbar-focus {
	margin-left: 4px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	cursor: text;
	font-size: 14px;
	width: 385px;
	text-align: center;
	<?php if (!empty($_GET['partytime'])) { echo 'width: 360px;'; } ?>
}
.pathbar {
	margin-left: 4px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	background-color: transparent;
	cursor: text;
	font-size: 14px;
	width: 385px;
	text-align: center;
	<?php if (!empty($_GET['partytime'])) { echo 'width: 360px;'; } ?>
}
.pathbar:disabled {
	color: #808080 !important;
}
.transparent-white {
	border: .1px solid rgba(255,255,255,0.3) !important;
}
.transparent-white:hover {
	background-color: rgba(255,255,255,0.2);
}
.white {
	border: .1px solid rgb(255,255,255) !important;
}
.white:hover {
	background-color: rgba(255,255,255,0.2);
}
.black {
	border: .1px solid rgb(0,0,0) !important;
}
.black:hover {
	background-color: rgba(255,255,255,0.2);
}
</style>
<script>
var CurrentPath;
var UserPath;
var UpPath;
var ForwardPath;
var BackPath;
var Title;
function onload() {
	toolbarInit();
	<?php echo $folders; ?>
	<?php echo $views; ?>
	
	setTimeout(function(){document.getElementById("File-List-Frame").src = 'filelist.php?path=<?php echo $path; ?>&from=<?php echo $path; ?>&to=<?php echo $path; ?>';},1000);
}
function toolbarInit() {
	var theme = window.top.shell.getThemeInfo()[2];
	if (theme == "E794H6r0rTF8bY45PfVYjFfi7j9w89sP") {
		var pclass = "transparent-white";
	} else if (theme == "m8k52VTgGFubX7Hz11LFDb3gYQ63P21p" || theme == "94XUFDNIhkA0BsX9B11vmscxDkXeeFUL") {
		var pclass = "black";
	} else {
		var pclass = "white";
	}
	
	Fly.toolbar.init();
	Fly.toolbar.add('','folders',toggleFolders,'<?php echo FLY_ICONS_URL; ?>/folder.svg');
	Fly.toolbar.add.divider('div1');
	Fly.toolbar.add('','back',back,'<?php echo FLY_ICONS_URL; ?>/arrow-left.svg');
	Fly.toolbar.add('','up',up,'<?php echo FLY_ICONS_URL; ?>/arrow-up.svg');
	<?php if (!empty($_GET['partytime'])) { echo 'Fly.toolbar.add(\'\',\'battle\',partytime,\''.FLY_ICONS_URL.'party.svg\');'; } ?>
	Fly.toolbar.add.custom('path','<input disabled onkeydown="if (event.keyCode == 13) {go();}" id="pathbar" onfocus="this.className=\'pathbar-focus\';this.select();" onblur="this.setSelectionRange(0, 0);this.className=\''+pclass+' pathbar FlyUiTextHighlight\';" type="text" value="" class="'+pclass+' pathbar FlyUiTextHighlight">');
	Fly.toolbar.add('Go','go',go,'<?php echo FLY_ICONS_URL; ?>/go.svg');
	Fly.toolbar.add('','refresh',refresh,'<?php echo FLY_ICONS_URL; ?>/refresh.svg');
	Fly.toolbar.add('','views',toggleViews,'<?php echo FLY_ICONS_URL; ?>/search.svg','right');
	Fly.toolbar.add.divider('div2','right');
	
	Fly.toolbar.onhide = function() {
		var frame = document.getElementById('File-List-Frame');
		frame.style.top = '0px';
		frame.style.height = '480px';
	}
	Fly.toolbar.onshow = function() {
		var frame = document.getElementById('File-List-Frame');
		frame.style.top = '34px';
		frame.style.height = '446px';
	}
}
function toggleFolders() {
	var FileList = document.getElementById("File-List-Frame");
	var ViewPane = document.getElementById("View-Pane-Frame");
	var FolderTree = document.getElementById("Folder-Tree-Frame");
	if (FolderTree.style.display == "none") {
		if (ViewPane.style.display == "none") {
			FileList.style.left = "160px";
			FileList.style.width = "calc(100% - 160px)";
			FolderTree.src = '';
			setTimeout(function(){document.getElementById("Folder-Tree-Frame").src = 'foldertree.php';},200);
			setTimeout(function(){document.getElementById("Folder-Tree-Frame").style.display="block";},300);
		} else {
			FileList.style.left = "160px";
			FileList.style.width = "calc(100% - 320px)";
			FolderTree.src = '';
			setTimeout(function(){document.getElementById("Folder-Tree-Frame").src = 'foldertree.php';},200);
			setTimeout(function(){document.getElementById("Folder-Tree-Frame").style.display="block";},300);
		}
		document.getElementById('FlyToolbarItem-folders').toggle();
	} else {
		if (ViewPane.style.display == "none") {
			FileList.style.left = "0px";
			FileList.style.width = "100%";
			FolderTree.style.display = "none";
		} else {
			FileList.style.left = "0px";
			FileList.style.width = "calc(100% - 160px)";
			FolderTree.style.display = "none";
		}
		document.getElementById('FlyToolbarItem-folders').toggle();
	}
}
function refresh() {
	var frame = document.getElementById('File-List-Frame');
	frame.contentWindow.location.reload();
}
function up() {
	var frame = document.getElementById('File-List-Frame');
	frame.contentWindow.navigate(UpPath);
}
function back() {
	var frame = document.getElementById('File-List-Frame');
	frame.contentWindow.navigate(BackPath);
}
function go() {
	var pathbar = document.getElementById('pathbar');
	var nav = pathbar.value;
	var frame = document.getElementById('File-List-Frame');
	document.getElementById('FlyToolbarItem-go').toggle();
	setTimeout(function() {document.getElementById('FlyToolbarItem-go').toggle();},100);
	pathbar.onblur();
	pathbar.disabled = true;
	frame.contentWindow.navigate(nav);
}
function setLocation(current,up,user,basename,back) {
	CurrentPath = current;
	UserPath = user;
	UpPath = up;
	BackPath = back;
	Title = basename;
	Fly.window.title.set('File Manager - '+Title);
	var pathbar = document.getElementById('pathbar');
	pathbar.value = UserPath;
	pathbar.disabled = false;
}
function toggleViews() {
	var FileList = document.getElementById("File-List-Frame");
	var ViewPane = document.getElementById("View-Pane-Frame");
	var FolderTree = document.getElementById("Folder-Tree-Frame");
	if (ViewPane.style.display == "none") {
		if (FolderTree.style.display == "none") {
			FileList.style.right = "160px";
			FileList.style.width = "calc(100% - 160px)";
			ViewPane.src = '';
			setTimeout(function(){document.getElementById("View-Pane-Frame").src = 'views.php';},200);
			setTimeout(function(){document.getElementById("View-Pane-Frame").style.display="block";},300);
		} else {
			FileList.style.right = "160px";
			FileList.style.width = "calc(100% - 320px)";
			ViewPane.src = '';
			setTimeout(function(){document.getElementById("View-Pane-Frame").src = 'views.php';},200);
			setTimeout(function(){document.getElementById("View-Pane-Frame").style.display="block";},300);
		}
		document.getElementById('FlyToolbarItem-views').toggle();
	} else {
		if (FolderTree.style.display == "none") {
			FileList.style.right = "0px";
			FileList.style.width = "100%";
			ViewPane.style.display = "none";
		} else {
			FileList.style.right = "0px";
			FileList.style.width = "calc(100% - 160px)";
			ViewPane.style.display = "none";
		}
		document.getElementById('FlyToolbarItem-views').toggle();
	}
}
<?php if (!empty($_GET['partytime'])) { echo '
function partytime() {
	var frame = document.getElementById("File-List-Frame");
	frame.src = frame.contentWindow.location.href+"&partytime=true";
}
'; } ?>
</script>
</head>
<body onload="onload()">
<iframe allowtransparency="true" frameborder="0" scrolling="yes" style="position:absolute;top:34px;left:0px;bottom:0px;width:160px;height:446px;display:none;" id="Folder-Tree-Frame" src=""></iframe>
<iframe allowtransparency="true" frameborder="0" scrolling="yes" style="position:absolute;top:34px;right:0px;bottom:0px;width:160px;height:446px;display:none;" id="View-Pane-Frame" src=""></iframe>
<iframe allowtransparency="true" id="File-List-Frame" frameborder="0" scrolling="yes" style="transition:left .2s ease-in-out,right .2s ease-in-out,width .2s ease-in-out;position:absolute;top:34px;left:0px;width:100%;height:calc(100% - 34px);background-color:#FFFFFF;" src=""></iframe>
</body>
</html>