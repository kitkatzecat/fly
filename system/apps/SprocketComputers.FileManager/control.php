<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

if ($_GET['type']=='fileBrowser') {
	goto control_fileBrowser;
}
if ($_GET['type']=='saveBrowser') {
	goto control_saveBrowser;
}
if ($_GET['type']=='saveDialog') {
	goto control_saveDialog;
}

control_fileBrowser:
?>
<script>
var CurrentPath;
var UserPath;
var UpPath;
var ForwardPath;
var BackPath;
var Basename;
var Url;
var Extension;
var Icon;
var TargetWindow = window.top.document.getElementById('<?php echo $_GET['windowid']; ?>');

var theme = window.top.shell.getThemeInfo()[2];
if (theme == "E794H6r0rTF8bY45PfVYjFfi7j9w89sP") {
	var pclass = "transparent-white";
} else if (theme == "m8k52VTgGFubX7Hz11LFDb3gYQ63P21p") {
	var pclass = "black";
} else {
	var pclass = "white";
}


function onload() {
	toolbarInit();
	
	document.getElementById('chosenFile').className += ' '+pclass;

	setTimeout(function() {document.getElementById('File-List-Frame').src='filelist.php?path=<?php echo FLY_ROOT; ?>&from=<?php echo FLY_ROOT; ?>&to=<?php echo FLY_ROOT; ?>&FileManagerFileBrowser=true'},500);
}
function toolbarInit() {
	Fly.toolbar.init();
	Fly.toolbar.add('','back',back,'<?php echo FLY_ICONS_URL; ?>/arrow-left.svg');
	Fly.toolbar.add('','up',up,'<?php echo FLY_ICONS_URL; ?>/arrow-up.svg');
	Fly.toolbar.add.custom('path','<input disabled onkeydown="if (event.keyCode == 13) {go();}" onclick="this.select();" id="pathbar" onfocus="this.className=\'pathbar-focus\';" onblur="this.setSelectionRange(0, 0);this.className=\''+pclass+' pathbar FlyUiTextHighlight\';" type="text" value="" class="'+pclass+' pathbar FlyUiTextHighlight">');
	Fly.toolbar.add('Go','go',go,'<?php echo FLY_ICONS_URL; ?>/go.svg');
	Fly.toolbar.add('','refresh',refresh,'<?php echo FLY_ICONS_URL; ?>/refresh.svg');
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
	var pathbar = document.getElementById('pathbar');
	pathbar.value = UserPath;
	pathbar.disabled = false;
}
function select(path,basename,url,extension,icon) {
	var chosenPath = document.getElementById('chosenPath');
	var chosenFile = document.getElementById('chosenFile');
	var chooseButton = document.getElementById('chooseButton');
	Basename = basename;
	Url = url;
	Extension = extension;
	Icon = icon;
	
	chosenPath.value = path;
	chosenFile.innerHTML = '<img class="smicon" src="'+icon+'">'+Basename;
	chooseButton.disabled = false;
}
function choose() {
	Fly.window.focus.give(OpenerWindow);
	window.top.document.getElementById(OpenerWindow).window.bringToFront();
	ret({path:document.getElementById('chosenPath').value,basename:Basename,url:Url,extension:Extension,icon:Icon});
	Fly.window.close();
}
var ret = function() {};
var OpenerWindow;
var init = function(id) {
	OpenerWindow = id;
	Fly.window.focus.take(id);
	Fly.window.bringToFront();
}
</script>
<style>
.main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	background-color: #ffffff;
}
.open {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
.file {
	position: absolute;
	bottom: 8px;
	left: 10px;
	width: 350px;
	height: 29px;
	box-sizing: border-box;
	font-size: 14px;
	background-color: transparent;
}
.pathbar-focus {
	margin-left: 4px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	cursor: text;
	font-size: 14px;
	width: 310px;
	text-align: center;
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
	width: 310px;
	text-align: center;
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
.smicon {
	width: 16px;
	height: 16px;
	margin-right: 6px;
	vertical-align: middle;
	pointer-events: none;
	margin-top: -4px;
}
</style>
</head>
<body onload="onload()">
<div class="main">
<iframe style="width:100%;height:100%;" frameborder="0" allowtransparency="yes" scrolling="auto" src="" id="File-List-Frame"></iframe>
</div>
<input type="hidden" id="chosenPath" value="">
<div id="chosenFile" style="border-radius:4px;padding:6px;cursor:default;" class="file FlyUiTextHighlight">No file selected</div>
<button onclick="choose()" id="chooseButton" disabled class="open">Choose</button>
</body>
</html>
<?php
exit;

control_saveBrowser:
?>
<script>
var CurrentPath;
var UserPath;
var Basename;
var Icon;
var TargetWindow = window.top.document.getElementById('<?php echo $_GET['windowid']; ?>');

var theme = window.top.shell.getThemeInfo()[2];
if (theme == "E794H6r0rTF8bY45PfVYjFfi7j9w89sP") {
	var pclass = "transparent-white";
} else if (theme == "m8k52VTgGFubX7Hz11LFDb3gYQ63P21p") {
	var pclass = "black";
} else {
	var pclass = "white";
}


function onload() {
	document.getElementById('chosenFile').className += ' '+pclass;

	document.getElementById('File-List-Frame').src='locationchooser_list.php';
}
function refresh() {
	var frame = document.getElementById('File-List-Frame');
	frame.contentWindow.location.reload();
}
var setLocation = function(current,user,basename,icon) {
	CurrentPath = current;
	UserPath = user;
	Basename = basename;
	Icon = icon;
	var chosenPath = document.getElementById('chosenPath');
	chosenPath.value = current;
	var chosenFile = document.getElementById('chosenFile');
	chosenFile.innerHTML = '<img class="smicon" src="'+icon+'">'+basename;
	var chooseButton = document.getElementById('chooseButton');
	chooseButton.disabled = false;
}
function choose() {
	Fly.window.focus.give(OpenerWindow);
	window.top.document.getElementById(OpenerWindow).window.bringToFront();
	ret({path:document.getElementById('chosenPath').value,basename:Basename,fpath:UserPath,icon:Icon});
	Fly.window.close();
}
var ret = function() {};
var OpenerWindow;
var init = function(id) {
	OpenerWindow = id;
	Fly.window.focus.take(id);
	Fly.window.bringToFront();
}
</script>
<style>
.main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 86px;
	background-color: #ffffff;
}
.open {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
.file {
	position: absolute;
	bottom: 48px;
	left: 0px;
	right: 0px;
	height: 29px;
	box-sizing: border-box;
	font-size: 14px;
	background-color: transparent;
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
.smicon {
	width: 16px;
	height: 16px;
	margin-right: 6px;
	vertical-align: middle;
	pointer-events: none;
	margin-top: -4px;
}
</style>
</head>
<body onload="onload()">
<div class="main">
<iframe style="width:100%;height:100%;" frameborder="0" allowtransparency="yes" scrolling="auto" src="" id="File-List-Frame"></iframe>
</div>
<input type="hidden" id="chosenPath" value="">
<div id="chosenFile" style="border-radius:4px;padding:6px;cursor:default;" class="file FlyUiTextHighlight">No location selected</div>
<button onclick="choose()" id="chooseButton" disabled class="open">Choose</button>
</body>
</html><?php
exit;

control_saveDialog:
?>
<script>
var CurrentPath;
var UserPath;
var PathBasename;
var Icon;
var TargetWindow = window.top.document.getElementById('<?php echo $_GET['windowid']; ?>');

var theme = window.top.shell.getThemeInfo()[2];
if (theme == "E794H6r0rTF8bY45PfVYjFfi7j9w89sP") {
	var pclass = "transparent-white";
} else if (theme == "m8k52VTgGFubX7Hz11LFDb3gYQ63P21p") {
	var pclass = "black";
} else {
	var pclass = "white";
}


function onload() {
	document.getElementById('chosenFolder').className += ' '+pclass;

	document.getElementById('File-List-Frame').src='locationchooser_list.php';
}
function refresh() {
	var frame = document.getElementById('File-List-Frame');
	frame.contentWindow.location.reload();
}
var setLocation = function(current,user,basename,icon) {
	CurrentPath = current;
	UserPath = user;
	PathBasename = basename;
	Icon = icon;
	var chosenPath = document.getElementById('chosenPath');
	chosenPath.value = current;
	var chosenFile = document.getElementById('chosenFolder');
	chosenFile.innerHTML = 'in<img class="smicon" src="'+icon+'">'+basename;
	var chooseButton = document.getElementById('chooseButton');
	chooseButton.disabled = false;
}
function choose() {
	Fly.window.focus.give(OpenerWindow);
	window.top.document.getElementById(OpenerWindow).window.bringToFront();
	ret({path:document.getElementById('chosenPath').value+'/'+document.getElementById('chosenFile').value,basename:document.getElementById('chosenFile').value,fpath:UserPath+'/'+document.getElementById('chosenFile').value,pbasename:PathBasename,icon:Icon});
	Fly.window.close();
}
var ret = function() {};
var OpenerWindow;
var init = function(id) {
	OpenerWindow = id;
	Fly.window.focus.take(id);
	Fly.window.bringToFront();
}
</script>
<style>
.main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 114px;
	background-color: #ffffff;
}
.open {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
.file {
	position: absolute;
	bottom: 80px;
	left: 0px;
	width: 100%;
	height: 29px;
	box-sizing: border-box;
	font-size: 14px;
	border-radius: 4px;
	padding: 6px;
}
.folder {
	position: absolute;
	bottom: 48px;
	left: 0px;
	right: 0px;
	height: 29px;
	box-sizing: border-box;
	font-size: 14px;
	background-color: transparent;
	border-radius: 4px;
	padding: 6px;
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
.smicon {
	width: 16px;
	height: 16px;
	margin-right: 4px;
	margin-left: 8px;
	vertical-align: middle;
	pointer-events: none;
	margin-top: -4px;
}
</style>
</head>
<body onload="onload()">
<div class="main">
<iframe style="width:100%;height:100%;" frameborder="0" allowtransparency="yes" scrolling="auto" src="" id="File-List-Frame"></iframe>
</div>
<input type="hidden" id="chosenPath" value="">
<input id="chosenFile"  onkeydown="if (event.keyCode == 13) {choose()}" class="file" type="text" value="Untitled">
<div id="chosenFolder" class="folder FlyUiTextHighlight">No location selected</div>
<button onclick="choose()" id="chooseButton" disabled class="open">Save</button>
</body>
</html>