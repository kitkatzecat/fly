<?php
if (!empty($_GET['window'])) {
	goto window;
}
if (!empty($_GET['resize'])) {
	goto resize;
}
if (!empty($_GET['reposition'])) {
	goto reposition;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
function onload() {
	Fly.window.title.set('Window Manager');
	setTimeout(function(){
		getWindows();
		document.getElementById('activeWindows-i').innerHTML = '-';
		document.getElementById('expandWindows-i').innerHTML = '-';
		document.getElementById('inactiveWindows-i').innerHTML = '-';
		document.getElementById('minimizedWindows-i').innerHTML = '-';
		document.getElementById('borderlessWindows-i').innerHTML = '-';
		document.getElementById('backgroundWindows-i').innerHTML = '-';
	},500);
}
function getWindows() {
	var activeWindows = window.top.document.getElementsByClassName('FlyWindowActive');
	var act = '';
	
	for (i = 0; i < activeWindows.length; i++) { 
		act += '<div style="font-size:12px;" title="'+activeWindows[i].window.id+'" oncontextmenu="windowContext(event,\''+activeWindows[i].id+'\');return false;" onclick="activateWindow(\''+activeWindows[i].id+'\')" id="TaskButton-'+activeWindows[i].id+'" class="FlyUiMenuItem FlyUiNoSelect"><img src="'+activeWindows[i].window.icon+'" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;margin-top:-8px;"><div style="width: 180px; overflow: hidden; display: inline-block; white-space: nowrap; text-overflow: ellipsis;">'+activeWindows[i].window.title+'</div><div style="width:16px;height:16px;float:right;line-height:8px;font-size:8px;text-align:center;" onclick="closeWindow(\''+activeWindows[i].id+'\');event.stopPropagation();" class="FlyUiMenuItem"><img style="width:8px;height:8px;vertical-align:middle;pointer-events:none;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"></div></div>';
	}
	
	var expandWindows = window.top.document.getElementsByClassName('FlyWindowExpand');
	var exp = '';
	
	for (i = 0; i < expandWindows.length; i++) { 
		exp += '<div style="font-size:12px;" title="'+expandWindows[i].window.id+'" oncontextmenu="windowContext(event,\''+expandWindows[i].id+'\');return false;" onclick="activateWindow(\''+expandWindows[i].id+'\')" id="TaskButton-'+expandWindows[i].id+'" class="FlyUiMenuItem FlyUiNoSelect"><img src="'+expandWindows[i].window.icon+'" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;margin-top:-8px;"><div style="width: 180px; overflow: hidden; display: inline-block; white-space: nowrap; text-overflow: ellipsis;">'+expandWindows[i].window.title+'</div><div style="width:16px;height:16px;float:right;line-height:8px;font-size:8px;text-align:center;" onclick="closeWindow(\''+expandWindows[i].id+'\');event.stopPropagation();" class="FlyUiMenuItem"><img style="width:8px;height:8px;vertical-align:middle;pointer-events:none;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"></div></div>';
	}
	
	var inactiveWindows = window.top.document.getElementsByClassName('FlyWindowInactive');
	var inact = '';
	
	for (i = 0; i < inactiveWindows.length; i++) { 
		if (inactiveWindows[i].style.position !== 'static') {
			inact += '<div style="font-size:12px;" title="'+inactiveWindows[i].window.id+'" oncontextmenu="windowContext(event,\''+inactiveWindows[i].id+'\');return false;" onclick="activateWindow(\''+inactiveWindows[i].id+'\')" id="TaskButton-'+inactiveWindows[i].id+'" class="FlyUiMenuItem FlyUiNoSelect"><img src="'+inactiveWindows[i].window.icon+'" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;opacity:.7;margin-top:-8px;"><div style="opacity:.7;width: 180px; overflow: hidden; display: inline-block; white-space: nowrap; text-overflow: ellipsis;">'+inactiveWindows[i].window.title+'</div><div style="width:16px;height:16px;float:right;line-height:8px;font-size:8px;text-align:center;" onclick="closeWindow(\''+inactiveWindows[i].id+'\');event.stopPropagation();" class="FlyUiMenuItem"><img style="width:8px;height:8px;vertical-align:middle;pointer-events:none;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"></div></div>';
		}
	}
	
	var min = '';
	
	for (i = 0; i < inactiveWindows.length; i++) { 
		if (inactiveWindows[i].style.position == 'static') {
			min += '<div style="font-size:12px;" oncontextmenu="windowContext(event,\''+inactiveWindows[i].id+'\');return false;" onclick="activateWindow(\''+inactiveWindows[i].id+'\')" id="TaskButton-'+inactiveWindows[i].id+'" class="FlyUiMenuItem FlyUiNoSelect"><img src="'+inactiveWindows[i].window.icon+'" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;opacity:.7;margin-top:-8px;"><div style="opacity:.7;width: 180px; overflow: hidden; display: inline-block; white-space: nowrap; text-overflow: ellipsis;">'+inactiveWindows[i].window.title+'</div><div style="width:16px;height:16px;float:right;line-height:8px;font-size:8px;text-align:center;" onclick="closeWindow(\''+inactiveWindows[i].id+'\');event.stopPropagation();" class="FlyUiMenuItem"><img style="width:8px;height:8px;vertical-align:middle;pointer-events:none;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"></div></div>';
		}
	}
	
	var borderlessWindows = window.top.document.getElementsByClassName('FlyWindowTransparent');
	var bdl = '';
	
	for (i = 0; i < borderlessWindows.length; i++) { 
		bdl += '<div style="font-size:12px;" title="'+borderlessWindows[i].window.id+'" oncontextmenu="windowContext(event,\''+borderlessWindows[i].id+'\');return false;" onclick="" id="TaskButton-'+borderlessWindows[i].id+'" class="FlyUiMenuItem FlyUiNoSelect"><img src="'+borderlessWindows[i].window.icon+'" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;margin-top:-8px;"><div style="width: 180px; overflow: hidden; display: inline-block; white-space: nowrap; text-overflow: ellipsis;">'+borderlessWindows[i].window.title+'</div><div style="width:16px;height:16px;float:right;line-height:8px;font-size:8px;text-align:center;" onclick="closeWindow(\''+borderlessWindows[i].id+'\');event.stopPropagation();" class="FlyUiMenuItem"><img style="width:8px;height:8px;vertical-align:middle;pointer-events:none;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"></div></div>';
	}

	var backgroundWindows = window.top.document.getElementsByClassName('FlyWindowBackground');
	var bkg = '';
	
	for (i = 0; i < backgroundWindows.length; i++) { 
		bkg += '<div style="font-size:12px;" title="'+backgroundWindows[i].window.id+'" oncontextmenu="windowContext(event,\''+backgroundWindows[i].id+'\');return false;" onclick="" id="TaskButton-'+backgroundWindows[i].id+'" class="FlyUiMenuItem FlyUiNoSelect"><img src="'+backgroundWindows[i].window.icon+'" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;margin-top:-8px;"><div style="width: 180px; overflow: hidden; display: inline-block; white-space: nowrap; text-overflow: ellipsis;">'+backgroundWindows[i].window.title+'</div><div style="width:16px;height:16px;float:right;line-height:8px;font-size:8px;text-align:center;" onclick="closeWindow(\''+backgroundWindows[i].id+'\');event.stopPropagation();" class="FlyUiMenuItem"><img style="width:8px;height:8px;vertical-align:middle;pointer-events:none;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"></div></div>';
	}

	document.getElementById('activeWindows').innerHTML = act;
	document.getElementById('expandWindows').innerHTML = exp;
	document.getElementById('inactiveWindows').innerHTML = inact;
	document.getElementById('minimizedWindows').innerHTML = min;
	document.getElementById('borderlessWindows').innerHTML = bdl;
	document.getElementById('backgroundWindows').innerHTML = bkg;
	
	setTimeout(getWindows,1000);
}
function activateWindow(id) {
	if (window.top.document.getElementById(id).style.position == 'static') {
		if (typeof(window.top.ui) !== 'undefined') {
			window.top.ui.tray.open(id);
		}
	} else {
		window.top.document.getElementById(id).window.bringToFront();
	}
}
function backWindow(id) {
	if (window.top.document.getElementById(id).style.position == 'absolute') {
		window.top.document.getElementById(id).window.sendToBack();
	}
}
function maximizeWindow(id) {
	if (window.top.document.getElementById(id).style.position == 'static') {
		window.top.document.getElementById(id).window.maximize();
	}
}
function expandWindow(id) {
	if (window.top.document.getElementById(id).style.position == 'absolute') {
		window.top.document.getElementById(id).window.expand();
	}
}
function restoreWindow(id) {
	if (window.top.document.getElementById(id).style.position == 'absolute') {
		window.top.document.getElementById(id).window.restore();
	}
}
function minimizeWindow(id) {
	if (window.top.document.getElementById(id).style.position == 'absolute') {
		window.top.document.getElementById(id).window.minimize();
	}
}
function flashWindow(id) {
	if (window.top.document.getElementById(id).style.position == 'absolute') {
		window.top.document.getElementById(id).window.bringToFront();
		setTimeout(function() {window.top.document.getElementById(id).window.flash();}, 250);
	}
}
function closeWindow(id) {
	window.top.document.getElementById(id).window.close();
}
function forceCloseWindow(id) {
	Fly.control.confirm('Force Close window','Are you sure you want to force close "'+window.top.document.getElementById(id).window.title+'"? You may lose any unsaved data.','Window Manager - Force Close','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',function(){window.top.document.getElementById(id).window.forceClose()});
}
function windowProperties(id) {
	window.top.task.create('SprocketComputers.Utilities.WinMgr',{title:'Window Details',name:'Window Details',x:(parseInt(Fly.window.position.get()[0])+32),y:(parseInt(Fly.window.position.get()[1])+32),width:240,height:320,icon:'<?php echo FLY_ICONS_URL; ?>info.svg',location:'<?php echo FLY_APPS_URL; ?>SprocketComputers.Utilities/winmgr.php?window='+id});
}
function windowContext(e,id) {
	//Fly.control.contextMenu(e,['Bring to Front','Send to Back','Minimize','Maximize','Expand','Restore','Flash','Force Close','Close','Details'],['activateWindow(\''+id+'\')','backWindow(\''+id+'\')','minimizeWindow(\''+id+'\')','maximizeWindow(\''+id+'\')','expandWindow(\''+id+'\')','restoreWindow(\''+id+'\')','flashWindow(\''+id+'\')','forceCloseWindow(\''+id+'\')','closeWindow(\''+id+'\')','windowProperties(\''+id+'\')'])
	Fly.actionmenu(e,[
		['Arrange',[
			['Bring to Front',function() {activateWindow(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-up.svg'}],
			['Send to Back',function() {backWindow(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-down.svg'}]
		],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-down-up.svg'}],
		['Window',[
			['Minimize',function() {minimizeWindow(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right-up.svg'}],
			['Maximize',function() {maximizeWindow(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-left-down.svg'}],
			[''],
			['Expand',function() {expandWindow(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-out.svg'}],
			['Restore',function() {restoreWindow(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-in.svg'}],
			[''],
			['Flash',function() {flashWindow(id);}],
			[''],
			['Buttons',[
				['Minimize',[
					['Hide',function(){window.top.document.getElementById(id).window.composition.minimize.status.hide();}],
					['Show',function(){window.top.document.getElementById(id).window.composition.minimize.status.show();}]
				],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right-up.svg'}],
				['Expand',[
					['Hide',function(){window.top.document.getElementById(id).window.composition.expand.status.hide();}],
					['Show',function(){window.top.document.getElementById(id).window.composition.expand.status.show();}]
				],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-out.svg'}],
				['Resize',[
					['Disable',function(){window.top.document.getElementById(id).window.composition.isResizable = false;}],
					['Enable',function(){window.top.document.getElementById(id).window.isResizable = true;}]
				],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-up-down.svg'}],
				['Close',[
					['Hide',function(){window.top.document.getElementById(id).window.composition.close.status.hide();}],
					['Show',function(){window.top.document.getElementById(id).window.composition.close.status.show();}]
				],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}],
			]],
			[''],
			['Force Close',function() {forceCloseWindow(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg'}]
		],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg'}],
		[''],
		['Close',function() {closeWindow(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}],
		['Details',function() {windowProperties(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'}]
	]);
}
function sectionContext(id,evt) {
	var element = document.getElementById(id);
	var name = element.getAttribute('data-title');
	if (element.style.display == 'none') {
		var m = 'Show '+name+' windows';
		var i = 'mark-plus.svg';
	} else {
		var m = 'Hide '+name+' windows';
		var i = 'mark-minus.svg';
	}
	Fly.actionmenu(evt,[
		[m,function() {toggle(id);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>'+i}]
	]);
}
function toggle(id) {
	var element = document.getElementById(id);
	var i = document.getElementById(id+'-i');
	if (element.style.display == 'none') {
		element.style.display = 'block';
		i.innerHTML = '-';
	} else {
		element.style.display = 'none';
		i.innerHTML = '+';
	}
}
</script>
<style>
.divider {
	font-size: 10px;
	color: #808080;
	cursor: default;
}
.page {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	overflow: auto;
	margin: 3px;
}
.i {
	float: right;
	margin-right: 4px;
}
</style>
</head>
<body id="body" style="margin:0px;" class="FlyUiContent" onload="onload();">
<div class="page" oncontextmenu="return false;">
	<div oncontextmenu="sectionContext('activeWindows',event);return false;" ondblclick="toggle('activeWindows')" class="divider FlyUiMenuItem FlyUiNoSelect">Active<span id="activeWindows-i" class="i">+</span></div><div data-title="Active" id="activeWindows">
	</div><div oncontextmenu="sectionContext('expandWindows',event);return false;" ondblclick="toggle('expandWindows')" class="divider FlyUiMenuItem FlyUiNoSelect">Expanded<span id="expandWindows-i" class="i">+</span></div><div data-title="Expanded" id="expandWindows">
	</div><div oncontextmenu="sectionContext('inactiveWindows',event);return false;" ondblclick="toggle('inactiveWindows')" class="divider FlyUiMenuItem FlyUiNoSelect">Inactive<span id="inactiveWindows-i" class="i">+</span></div><div data-title="Inactive" id="inactiveWindows">
	</div><div oncontextmenu="sectionContext('minimizedWindows',event);return false;" ondblclick="toggle('minimizedWindows')" class="divider FlyUiMenuItem FlyUiNoSelect">Minimized<span id="minimizedWindows-i" class="i">+</span></div><div data-title="Minimized" id="minimizedWindows">
	</div><div oncontextmenu="sectionContext('borderlessWindows',event);return false;" ondblclick="toggle('borderlessWindows')" class="divider FlyUiMenuItem FlyUiNoSelect">Borderless<span id="borderlessWindows-i" class="i">+</span></div><div data-title="Borderless" id="borderlessWindows">
	</div><div oncontextmenu="sectionContext('backgroundWindows',event);return false;" ondblclick="toggle('backgroundWindows')" class="divider FlyUiMenuItem FlyUiNoSelect">Background<span id="backgroundWindows-i" class="i">+</span></div><div data-title="Background" id="backgroundWindows">
	</div>
</div>
</body>
</html>
<?php
exit;

window:
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
var windowTitle;
var windowIcon;
var windowHeader;
var windowID;
var windowUniqueID;
var windowName;
var windowStatus;
var windowSize;
var windowPosition;
var windowInterval;
var frame;

function onload() {
	windowTitle = document.getElementById('window-title');
	windowIcon = document.getElementById('window-icon');
	windowHeader = document.getElementById('window-header');
	windowID = document.getElementById('window-id');
	windowUniqueID = document.getElementById('window-uniqueid');
	windowName = document.getElementById('window-name');
	windowStatus = document.getElementById('window-status');
	windowSize = document.getElementById('window-size');
	windowPosition = document.getElementById('window-position');
	windowIndex = document.getElementById('window-index');

	var win = window.top.document.getElementById('<?php echo $_GET["window"]; ?>');
	if (win) {
		Fly.window.title.set('Window Details - '+win.window.name);
				
		windowTitle.innerText = win.window.title;
		windowIcon.src = win.window.icon;
		windowHeader.onclick = win.window.bringToFront;
		windowID.innerText = win.window.id;
		windowUniqueID.innerText = win.window.uniqueid;
		windowName.innerText = win.window.name;
		if (win.window.isMinimized) {
			windowStatus.innerText = 'minimized';
		} else if (win.className.indexOf('FlyWindowActive') != -1) {
			windowStatus.innerText = 'active';
		} else if (win.className.indexOf('FlyWindowInactive') != -1) {
			windowStatus.innerText = 'inactive';
		} else if (win.className.indexOf('FlyWindowExpand') != -1) {
			windowStatus.innerText = 'expanded';
		} else {
			windowStatus.innerText = 'unknown';
		}
		windowSize.innerText = win.window.content.offsetWidth+'px, '+win.window.content.offsetHeight+'px';
		if (win.window.isExpand) {
			windowPosition.innerText = '0px, 0px';
		} else {
			windowPosition.innerText = win.style.left+', '+win.style.top;
		}
		windowIndex.innerText = win.style.zIndex;
		
		windowInterval = setInterval(interval,100);
	} else {
		window.top.shell.dialog('Window not found','The selected window could not be found. It may have been closed.','Window Details');
		Fly.window.close();
	}
}
function interval() {
	var win = window.top.document.getElementById('<?php echo $_GET["window"]; ?>');
	if (win) {
		if ('Window Details - '+win.window.name !== Fly.window.title.get()) {
			Fly.window.title.set('Window Details - '+win.window.name);
		}
				
		if (win.window.title !== windowTitle.innerText) {
			windowTitle.innerText = win.window.title;
		}
		if (win.window.icon !== windowIcon.src) {
			windowIcon.src = win.window.icon;
		}
		if (win.window.id !== windowID.innerText) {
			windowID.innerText = win.window.id;
		}
		if (win.window.uniqueid !== windowUniqueID.innerText) {
			windowUniqueID.innerText = win.window.uniqueid;
		}
		if (win.window.name !== windowName.innerText) {
			windowName.innerText = win.window.name;
		}
		if (win.style.position == 'static') {
			windowStatus.innerText = 'minimized';
		} else if (win.className.indexOf('FlyWindowActive') != -1) {
			windowStatus.innerText = 'active';
		} else if (win.className.indexOf('FlyWindowInactive') != -1) {
			windowStatus.innerText = 'inactive';
		} else if (win.className.indexOf('FlyWindowExpand') != -1) {
			windowStatus.innerText = 'expanded';
		} else {
			windowStatus.innerText = 'unknown';
		}
		windowSize.innerText = win.window.content.offsetWidth+'px, '+win.window.content.offsetHeight+'px';
		windowIndex.innerText = win.style.zIndex;
		if (win.window.isExpand) {
			windowPosition.innerText = '0px, 0px';
		} else {
			windowPosition.innerText = win.style.left+', '+win.style.top;
		}
	} else {
		clearInterval(windowInterval);
		windowHeader.onclick = function() {};
		windowStatus.innerText = 'closed';
	}
}

function init() {
	try {
		frame.window.content.contentWindow.init(Fly.window.id);
	}
	catch(err) {
		window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		frame.window.close();
	}
}


function resize() {
	frame = window.top.task.create('SprocketComputers.Utilities.WinMgr',{title:'Resize',name:'Resize',x:(parseInt(Fly.window.position.get()[0])+32),y:(parseInt(Fly.window.position.get()[1])+32),width:200,height:120,icon:'<?php echo FLY_ICONS_URL; ?>question.svg',location:'<?php echo FLY_APPS_URL; ?>SprocketComputers.Utilities/winmgr.php?resize=<?php echo $_GET["window"]; ?>',load:init});
}
function reposition() {
	frame = window.top.task.create('SprocketComputers.Utilities.WinMgr',{title:'Reposition',name:'Reposition',x:(parseInt(Fly.window.position.get()[0])+32),y:(parseInt(Fly.window.position.get()[1])+32),width:200,height:120,icon:'<?php echo FLY_ICONS_URL; ?>question.svg',location:'<?php echo FLY_APPS_URL; ?>SprocketComputers.Utilities/winmgr.php?reposition=<?php echo $_GET["window"]; ?>',load:init});
}
</script>
<style>
body {
	margin: 0px;
	padding: 0px;
}
.page {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	background-color: #fff;
	overflow: auto;
	padding: 2px;
}
.icon {
	width: 32px;
	height: 32px;
	margin-right: 6px;
	vertical-align: top;
}
.name {
	display: inline-block;
	width: calc(100% - 38px);
	margin-top: 6px;
}
.head {
	font-size: 12px;
	font-weight: bold;
	cursor: default;
}
.item {
	display: inline-block;
	border: 1px solid transparent;
	text-align: left;
	padding: 4px;
	box-sizing: border-box;
	cursor: text;
	overflow: hidden;
	font-size: 14px;
	word-wrap: break-word;
}
</style>
</head>
<body id="body" style="background:#fff;margin:0px;" onload="onload()">
<div class="page FlyUiText">
	<div class="FlyUiMenuItem" id="window-header" style="font-size:16px;" oncontextmenu="Fly.control.contextMenu(event,['Resize','Reposition'],['resize()','reposition()']);return false;"><span class="FlyUiNoSelect"><img id="window-icon" class="icon FlyUiNoSelect" src="<?php echo FLY_ICONS_URL; ?>application.svg"></span><div class="name" id="window-title">Loading...</div></div>
	<div class="item FlyUiMenuItem FlyUiText"><span class="head FlyUiNoSelect">ID</span><br><span id="window-id">Loading...</span></div>
	<div class="item FlyUiMenuItem FlyUiText"><span class="head FlyUiNoSelect">Unique ID</span><br><span id="window-uniqueid">Loading...</span></div>
	<div class="item FlyUiMenuItem FlyUiText"><span class="head FlyUiNoSelect">Name</span><br><span id="window-name">Loading...</span></div>
	<div class="item FlyUiMenuItem FlyUiText"><span class="head FlyUiNoSelect">Status</span><br><span id="window-status">Loading...</span></div>
	<div class="item FlyUiMenuItem FlyUiText"><span class="head FlyUiNoSelect">Size</span><br><span id="window-size">Loading...</span></div>
	<div class="item FlyUiMenuItem FlyUiText"><span class="head FlyUiNoSelect">Position</span><br><span id="window-position">Loading...</span></div>
	<div class="item FlyUiMenuItem FlyUiText"><span class="head FlyUiNoSelect">Index</span><br><span id="window-index">Loading...</span></div>
</div>
</body>
</html>
<?php
exit;

resize:
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<style>
.page {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 6px;
	background-color: #fff;
}
.button {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
</style>
<script>
var opener;
function init(winid) {
	if (window.top.document.getElementById(winid)) {
		document.getElementById('resize-w').disabled = false;
		document.getElementById('resize-h').disabled = false;	
		document.getElementById('resize-go').disabled = false;
		
		Fly.window.focus.take(winid);
		opener = winid;
		
		var win = window.top.document.getElementById('<?php echo $_GET["resize"]; ?>');
		if (win) {
			Fly.window.title.set('Resize - '+win.window.name);
			
			document.getElementById('resize-w').value = parseInt(win.window.content.style.width);
			document.getElementById('resize-h').value = parseInt(win.window.content.style.height);
		
		if (typeof window.top.shell.sound !== "undefined") {
			window.top.shell.sound.system('question');
		}
		
		} else {
			if (typeof window.top.shell.sound !== "undefined") {
				window.top.shell.sound.system('alert');
			}
			try {
				Fly.window.focus.give(opener);
				window.top.document.getElementById(opener).window.bringToFront();
			} catch(err) {}
			Fly.window.close();
		}
	} else {
		if (typeof window.top.shell.sound !== "undefined") {
			window.top.shell.sound.system('alert');
		}
		try {
			window.top.document.getElementById(winid).window.bringToFront();
		} catch(err) {}
		Fly.window.close();
	}
}
function resize() {
	var win = window.top.document.getElementById('<?php echo $_GET["resize"]; ?>');
	if (win) {
		try {
			win.window.setSize(document.getElementById('resize-w').value,document.getElementById('resize-h').value);
		} catch(err) {
		}
		Fly.window.focus.give(opener);
		window.top.document.getElementById(opener).window.bringToFront();
		Fly.window.close();
	} else {
		Fly.window.close();
	}
}

</script>
</head>
<body>
<div class="page FlyUiText">
	Width: <input id="resize-w" disabled style="width:48px;" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57">px<br>
	Height: <input id="resize-h" disabled style="width:48px;" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57">px
</div>
<button class="button" id="resize-go" onclick="resize()" disabled><img style="width:16px;height:16px;vertical-align:middle;pointer-events:none;" src="<?php echo FLY_ICONS_URL; ?>mark-check.svg"></button>
</body>
</html><?php
exit;

reposition:
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<style>
.page {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 6px;
	background-color: #fff;
}
.button {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
</style>
<script>
var opener;
function init(winid) {
	if (window.top.document.getElementById(winid)) {
		document.getElementById('reposition-x').disabled = false;
		document.getElementById('reposition-y').disabled = false;	
		document.getElementById('reposition-go').disabled = false;
		
		Fly.window.focus.take(winid);
		opener = winid;
		
		var win = window.top.document.getElementById('<?php echo $_GET["reposition"]; ?>');
		if (win) {
			Fly.window.title.set('Reposition - '+win.window.name);
			
			document.getElementById('reposition-x').value = parseInt(win.style.left);
			document.getElementById('reposition-y').value = parseInt(win.style.top);
		if (typeof window.top.shell.sound !== "undefined") {
			window.top.shell.sound.system('question');
		}
		
		} else {
			if (typeof window.top.shell.sound !== "undefined") {
				window.top.shell.sound.system('alert');
			}
			try {
				Fly.window.focus.give(opener);
				window.top.document.getElementById(opener).window.bringToFront();
			} catch(err) {}
			Fly.window.close();
		}
	} else {
		if (typeof window.top.shell.sound !== "undefined") {
			window.top.shell.sound.system('alert');
		}
		try {
			window.top.document.getElementById(winid).window.bringToFront();
		} catch(err) {}
		Fly.window.close();
	}
}
function reposition() {
	var win = window.top.document.getElementById('<?php echo $_GET["reposition"]; ?>');
	if (win) {
		try {
			win.window.setPosition(document.getElementById('reposition-x').value,document.getElementById('reposition-y').value);
		} catch(err) {
		}
		Fly.window.focus.give(opener);
		window.top.document.getElementById(opener).window.bringToFront();
		Fly.window.close();
	} else {
		Fly.window.close();
	}
}
</script>
</head>
<body>
<div class="page FlyUiText">
	X: <input id="reposition-x" disabled style="width:48px;" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57">px<br>
	Y: <input id="reposition-y" disabled style="width:48px;" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57">px
</div>
<button class="button" id="reposition-go" onclick="reposition()" disabled><img style="width:16px;height:16px;vertical-align:middle;pointer-events:none;" src="<?php echo FLY_ICONS_URL; ?>mark-check.svg"></button>
</body>
</html>