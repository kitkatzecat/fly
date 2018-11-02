<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'fileprocessor.php';

echo FlyLoadExtension('SprocketComputers.FileManager','FileChooser');
?>
<style>
button {
	box-sizing: border-box;
}
#Player-Progress {
	position: absolute;
	bottom: 56px;
	left: 4px;
	right: 4px;
	height: 4px;
	/* transition: background-size .1s linear; */
	background-image: linear-gradient(to bottom, #aebcbf 0%,#6e7774 50%,#0a0e0a 51%,#0a0809 100%),linear-gradient(to bottom, rgba(242,246,248,1) 0%,rgba(216,225,231,1) 50%,rgba(181,198,208,1) 51%,rgba(224,239,249,1) 100%);
	background-repeat: no-repeat;
	background-size: 0% 100%, 100% 100%;
	border-top: 6px solid rgba(0,0,0,0);
	border-bottom: 6px solid rgba(0,0,0,0);
	border-radius: 500px;
	cursor: pointer;
}
#Player-Seek {
	width: 10px;
	height: 10px;
	position: absolute;
	left: -4px;
	top: -3px;
	background-image: linear-gradient(to bottom, #aebcbf 0%,#6e7774 50%,#0a0e0a 51%,#0a0809 100%);
	background-repeat: no-repeat;
	background-size: 100% 100%;
	border-radius: 100px;
	cursor: ew-resize;
	pointer-events: none;
}
#ScrubAudio, #ScrubVolume {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	display: none;
	cursor: pointer;
	background-color: rgba(255,255,255,0);
}
#Volume-Progress {
	display: none;
	width: 80px;
	margin-left: 8px;
	margin-bottom: 3px;
	margin-top: 4px;
	height: 4px;
	position: relative;
	background-image: linear-gradient(to bottom, #aebcbf 0%,#6e7774 50%,#0a0e0a 51%,#0a0809 100%),linear-gradient(to bottom, rgba(242,246,248,1) 0%,rgba(216,225,231,1) 50%,rgba(181,198,208,1) 51%,rgba(224,239,249,1) 100%);
	background-repeat: no-repeat;
	background-size: 100% 100%, 100% 100%;
	border: .1px solid #C0C0C0;
	border-top: 6px solid rgba(0,0,0,0);
	border-bottom: 6px solid rgba(0,0,0,0);
	border-radius: 500px;
	cursor: pointer;
}
#Volume-Seek {
	width: 10px;
	height: 10px;
	position: absolute;
	left: calc(100% - 4px);
	top: -3px;
	background-image: linear-gradient(to bottom, #aebcbf 0%,#6e7774 50%,#0a0e0a 51%,#0a0809 100%);
	background-repeat: no-repeat;
	background-size: 100% 100%;
	border-radius: 100px;
	cursor: ew-resize;
	pointer-events: none;
}
#Button-Play {
	width: 48px !important;
	height: 48px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px !important;
	margin: 0px !important;
	position: absolute;
	bottom: 4px;
	left: 4px;
}
#Button-Stop {
	width: 32px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px;
	margin: 0px;
	position: absolute;
	bottom: 12px;
	left: 56px;
}
#Button-Volume {
	width: 32px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px;
	margin: 0px;
	position: absolute;
	bottom: 12px;
	left: 92px;
}
#Button-Volume-Progress {
	width: 128px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: right;
	padding: 0px;
	margin: 0px;
	position: absolute;
	bottom: 12px;
	left: 92px;
	display: none;
}
#Button-Repeat {
	width: 32px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px;
	margin: 0px;
	position: absolute;
	bottom: 12px;
	left: 128px;
}
#Button-Speed {
	width: 32px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px;
	margin: 0px;
	position: absolute;
	bottom: 12px;
	left: 296px;
	display: none;
}
#Button-Speed-Reverse {
	width: 32px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px;
	margin: 0px;
	position: absolute;
	bottom: 12px;
	left: 260px;
	display: none;
}
#Button-Mode {
	width: 32px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px;
	margin: 0px;
	position: absolute;
	bottom: 12px;
	left: 164px;
}
#Text-Duration {
	text-align: right;
	position: absolute;
	right: 8px;
	bottom: 20px;
	height: 16px;
}
#Main {
	display: none;
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 76px;
	background-image: url('background.nowplaying.svg'),url('background.header.static.svg'),url('background.header.repeat.svg');
	background-size: auto;
	background-repeat: no-repeat, no-repeat, repeat-x;
	background-position: right top, right top, left top;
	background-color: #ffffff;
	padding: 8px;
}
#Library {
	display: none;
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 76px;
}
#Text-NowPlaying {
    position: absolute;
    background-image: url('background.title.svg');
    background-position: 0px 0px;
   	padding-top: 56px;
	bottom: 0px;
	background-color: #cae1ef;
	left: 0px;
	right: 0px;
	padding-bottom: 12px;
	padding-left: 12px;
	font-size: 36px;
	padding-right: 180px;
   	background-repeat: repeat-x;
}
@keyframes Text-NowPlaying-Animation {
	from {background-position: 0px 0px;}
	to {background-position: 640px 0px;}
}
</style>
<script>
function getBasename(path) {
	return path.replace(/\\/g,'/').replace( /.*\//, '' ).split("?")[0];
}
var Toolbar;
var ToolbarButtons = {};
var Tabs;
var TabsButtons = {};
function ToolbarInit() {
	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.top = '0px';
	Toolbar.style.left = '0px';
	Toolbar.style.width = 'auto';
	
	ToolbarButtons.File = Toolbar.add({text:'File',type:'dropdown',menu:[
		['Open',Open,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg'}],
		['Open URL',OpenURL,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>earth.svg'}],
		[''],
		['Properties',Properties,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'}],
		[''],
		['Close',Fly.window.close,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}],
	]});
	
	ToolbarButtons.Play = Toolbar.add({text:'Play',type:'dropdown',menu:[
		['Play',ButtonPlay,{icon:'button.play.svg',disabled:true}],
		['Pause',ButtonPlay,{icon:'button.pause.svg',disabled:true}],
		['Stop',ButtonStop,{icon:'button.stop.svg'}],
		['Volume',[
			['100%',function(){SetVolume(100);}],
			['90%',function(){SetVolume(90);}],
			['80%',function(){SetVolume(80);}],
			['70%',function(){SetVolume(70);}],
			['60%',function(){SetVolume(60);}],
			['50%',function(){SetVolume(50);}],
			['40%',function(){SetVolume(40);}],
			['30%',function(){SetVolume(30);}],
			['20%',function(){SetVolume(20);}],
			['10%',function(){SetVolume(10);}],
			['0%',function(){SetVolume(0);}],
			[''],
			['Mute',ButtonVolume,{icon:'button.volume.mute.svg'}]
		],{icon:'button.volume.svg'}],
		['Repeat',[
			['Toggle',ButtonRepeat],
			[''],
			['On',function() {
				var audio = document.getElementById('Audio-Player');
				var img = document.getElementById('Button-Repeat-Img');
				
				audio.loop = true;
				img.src = 'button.repeat.svg';
			}],
			['Off',function() {
				var audio = document.getElementById('Audio-Player');
				var img = document.getElementById('Button-Repeat-Img');

				audio.loop = false;
				img.src = 'button.repeat.off.svg';
			}]
		],{icon:'button.repeat.svg'}],
		['Restart Track',ButtonRestartTrack,{icon:'button.speed.reverse.svg'}],
		['Speed',[
			['25%',function(){SetSpeed('0.25')}],
			['50%',function(){SetSpeed('0.5')}],
			['100%',function(){SetSpeed('1')}],
			['150%',function(){SetSpeed('1.5')}],
			['200%',function(){SetSpeed('2')}],
			['300%',function(){SetSpeed('3')}],
			['400%',function(){SetSpeed('4')}]
		],{icon:'button.speed.svg'}],
		['Mode',[
			['Toggle',ButtonMode],
			[''],
			['Full',ButtonModeFull],
			['Compact',ButtonModeCompact]
		],{icon:'button.arrow.compact.svg'}],
	]});
	
	ToolbarButtons.View = Toolbar.add({text:'View',type:'dropdown',menu:[
		['Library',TabLibrary,{icon:'library.svg'}],
		['Now Playing',TabNowplaying,{icon:'media.svg'}],
		[''],
		['Playlist',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>index.svg'}],
	]});
	
	
	Tabs = new Fly.actionbar();
	Tabs.style.position = 'absolute';
	Tabs.style.top = '0px';
	Tabs.style.right = '0px';
	Tabs.style.width = 'auto';
	
	TabsButtons.Library = Tabs.add({text:'Library',icon:'library.svg',action:TabLibrary});
	TabsButtons.NowPlaying = Tabs.add({text:'Now Playing',icon:'media.svg',action:TabNowplaying});
	Tabs.add({type:'divider'});
	TabsButtons.Playlist = Tabs.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>index.svg'});
	
	TabsButtons.NowPlaying.toggleOn();
	
	Toolbar.style.display = 'none';
	Tabs.style.display = 'none';
	
	document.body.appendChild(Tabs);
	document.body.appendChild(Toolbar);

	/*
	Fly.toolbar.init();
	Fly.toolbar.add('Open File','open',Open,'<?php echo FLY_ICONS_URL; ?>folder.svg');
	Fly.toolbar.add('Open URL','open-url',OpenURL,'<?php echo FLY_ICONS_URL; ?>earth.svg');
	Fly.toolbar.add.divider();
	Fly.toolbar.add('Properties','properties',Properties,'<?php echo FLY_ICONS_URL; ?>properties.svg');
	Fly.toolbar.add('Now Playing','tab-nowplaying',TabNowplaying,'media.svg','right');
	Fly.toolbar.add('Library','tab-library',TabLibrary,'library.svg','right');
	
	document.getElementById('FlyToolbarItem-tab-nowplaying').toggleOn();
	*/
}
function ShortcutInit() {
	document.addEventListener("keydown", function(e) {
		//Open (ctrl+o)
		if (e.keyCode == 79 && e.ctrlKey) {
			e.preventDefault();
			Open();
		}
		
		//Play/Pause (ctrl+p)
		if (e.keyCode == 80 && e.ctrlKey) {
			e.preventDefault();
			var audio = document.getElementById('Audio-Player');
			if (audio.readyState > 2) {
				ButtonPlay();
			}
		}
		
		//Play/Pause (space)
		if (e.keyCode == 32) {
			e.preventDefault();
			var audio = document.getElementById('Audio-Player');
			if (audio.readyState > 2) {
				ButtonPlay();
			}
		}
		
		//Close (ctrl+q)
		if (e.keyCode == 81 && e.ctrlKey) {
			e.preventDefault();
			Fly.window.close();
		}
	}, false);
}
function TabNowplaying() {
	var main = document.getElementById('Main');
	var mainTab = TabsButtons.NowPlaying;
	var library = document.getElementById('Library');
	var libraryTab = TabsButtons.Library;
	
	library.style.display = 'none';
	libraryTab.toggleOff();
	
	main.style.display = 'block';
	mainTab.toggleOn();
}
function TabLibrary() {
	var main = document.getElementById('Main');
	var mainTab = TabsButtons.NowPlaying;
	var library = document.getElementById('Library');
	var libraryTab = TabsButtons.Library;
	var libraryFrame = document.getElementById('Library-Frame');
	
	main.style.display = 'none';
	mainTab.toggleOff();
	
	library.style.display = 'block';
	libraryTab.toggleOn();
}
function Properties() {
	window.top.system.command('run:SprocketComputers.FileManager.Properties,file='+document.getElementById('Audio-Player').currentSrc);
}

function ButtonMode() {
	if (Fly.window.size.get()[0] == '640') {
		ButtonModeCompact();
	} else {
		ButtonModeFull();
	}
}
function ButtonModeFull() {
	img = document.getElementById('Button-Mode-Img');
	
	Fly.window.size.set(640,480);
	img.src = 'button.arrow.compact.svg';
	
	document.getElementById('Button-Volume-Progress').style.display = 'inline-block';
	document.getElementById('Button-Repeat').style.left = '224px';
	document.getElementById('Button-Mode').style.left = '332px';
	document.getElementById('Volume-Progress').style.display = 'inline-block';
	document.getElementById('Button-Speed').style.display = 'inline-block';
	document.getElementById('Button-Speed-Reverse').style.display = 'inline-block';
	document.getElementById('Main').style.display = 'block';
	Toolbar.style.display = 'block';
	Tabs.style.display = 'block';
	
	Fly.window.expand.enable();
}
function ButtonModeCompact() {
	img = document.getElementById('Button-Mode-Img');
	
	Fly.window.size.set(320,72);
	img.src = 'button.arrow.full.svg';
	
	document.getElementById('Button-Volume-Progress').style.display = 'none';
	document.getElementById('Button-Repeat').style.left = '128px';
	document.getElementById('Button-Mode').style.left = '164px';
	document.getElementById('Button-Speed').style.display = 'none';
	document.getElementById('Button-Speed-Reverse').style.display = 'none';
	document.getElementById('Volume-Progress').style.display = 'none';
	document.getElementById('Main').style.display = 'none';
	Toolbar.style.display = 'none';
	Tabs.style.display = 'none';
	
	Fly.window.expand.disable();
}
function Open() {
	document.getElementById('Control-Browser').browse();
}
function OpenURL() {
	Fly.control.input('Open URL','Enter the URL for Media Player to open.','Media Player - Open URL','<?php echo FLY_ICONS_URL; ?>earth.svg',LoadURL);
}
function SetSpeed(int) {
	var audio = document.getElementById('Audio-Player');
	var text = document.getElementById('Text-PlaySpeed');
	
	audio.playbackRate = (int);
	
	text.innerHTML = 'Play speed: '+(parseFloat(audio.playbackRate)*100)+'%';
}
function SetProgress(percent) {
	if (!PlayerScrubber.mDown) {
		var progress = document.getElementById('Player-Progress');
		var seek = document.getElementById('Player-Seek');
		progress.style.backgroundSize = percent+"% 100%, 100% 100%";
		seek.style.left = 'calc('+percent+'% - 4px)';
	}
}
function SetVolume(percent) {
	var progress = document.getElementById('Volume-Progress');
	var seek = document.getElementById('Volume-Seek');
	var audio = document.getElementById('Audio-Player');
	var img = document.getElementById('Button-Volume-Img');
	progress.style.backgroundSize = percent+"% 100%, 100% 100%";
	seek.style.left = "calc("+percent+"% - 4px)";
	audio.volume = (percent/100);
	if (percent < 0.1) {
		img.src = 'button.volume.mute.svg';
	} else {
		img.src = 'button.volume.svg';
	}
}
function SetTime(sec) {
	var audio = document.getElementById('Audio-Player');
	if (audio.readyState > 2) {
		document.getElementById('Text-Duration').innerHTML = FormatTime(Math.round(sec))+' / '+FormatTime(Math.round(audio.duration));
		SetProgress((sec/audio.duration)*100);
		audio.currentTime = sec;
	}
}
function FormatTime(sec) {
	var hr = Math.floor(sec / 3600);
	var min = Math.floor((sec - (hr * 3600))/60);
	sec -= ((hr * 3600) + (min * 60));
	sec += ''; min += '';
	while (min.length < 2) {min = '0' + min;}
	while (sec.length < 2) {sec = '0' + sec;}
	hr = (hr)?hr+':':'';
	return hr + min + ':' + sec;
}
function LoadURL(url) {
	if (url !== '') {
		Load(url);
		document.getElementById('Text-NowPlaying').innerHTML = decodeURIComponent(getBasename(url)).replace(/\.[^/.]+$/, "");
		Fly.window.title.set('Media Player - '+decodeURIComponent(getBasename(url)));
	}
}
function Load(url) {
	ButtonStop();
	document.getElementById('Text-Duration').innerHTML = 'Loading...';
	document.getElementById('Text-PlaySpeed').innerHTML = 'Play speed: 100%';
	document.getElementById('Text-NowPlaying').innerHTML = getBasename(url).replace(/\.[^/.]+$/, "");
	document.getElementById('Audio-Player').src = url;
	document.getElementById('Audio-Player').playbackRate = 1;
	Fly.window.title.set('Media Player - '+getBasename(url));
	document.getElementById('Button-Play').disabled = true;
	document.getElementById('Audio-Player').load();
}
function GotoTime() {
	Fly.control.input('Go to time','Enter the time in seconds for Media Player to go to.','Media Player - Go To Time','<?php echo WORKING_URL; ?>timer.svg',GotoTimeReturn);
}
function GotoTimeReturn(sec) {
	var audio = document.getElementById('Audio-Player');
	if (sec !== '' && !isNaN(sec) && audio.readyState > 2) {
		SetTime(parseInt(sec));
	}
}
</script>

</head>
<body onload="onload()">
<div id="Control-Browser" style="display:none;"></div>
<script>
Fly.extension.replace('Control-Browser','SprocketComputers.FileManager','FileChooser');
document.getElementById('Control-Browser').onchange = function() {
	var browser = document.getElementById('Control-Browser');
	if (['mp3','m4a','wav'].indexOf(browser.vars.extension) == -1) {
		window.top.shell.dialog('Selected file cannot be played','The selected file cannot be played because it is not a recognized audio or video file.','Media Player','<?php echo FLY_ICONS_URL; ?>warning.svg');
	} else {
		Load(browser.vars.URL);
	}
}
</script>
<?php
if (in_array($_GET['autoplay'],['true','on','yes'])) {
	$autoplay = 'audio.play();';
} else {
	$autoplay = '';
}
if (in_array($_GET['repeat'],['true','on','yes'])) {
	$repeat = 'ButtonRepeat();';
} else {
	$repeat = '';
}
if (in_array($_GET['full'],['true','on','yes'])) {
	$mode = 'ButtonModeFull();';
} else {
	$mode = '';
}
if ($_GET['file']=='' || !file_exists($_GET['file'])) {
	$autoplay = '';
	$mode = '';
	$load = 'document.getElementById(\'Text-Duration\').innerHTML = \'No file selected\';ButtonModeFull();TabLibrary();
		audio.oncanplay = function() {
			document.getElementById(\'Text-Duration\').innerHTML = \'00:00 / \'+FormatTime(Math.round(audio.duration));
			document.getElementById(\'Button-Play\').disabled = false;
			
			audio.play();
		}
	';
} else {
	$load = 'Load(\''.FlyConvertPathtoURL($_GET['file']).'\',\''.basename($_GET['file']).'\');Fly.window.title.set(\'Media Player - '.htmlentities(basename($_GET['file'])).'\');';
}

echo '
<audio preload="false" id="Audio-Player"><source src=""></source></audio>

<script>
function ButtonPlay() {
	var audio = document.getElementById(\'Audio-Player\');
	if (audio.paused) {
		audio.play();
	} else {
		audio.pause();
	}
}
function ButtonRepeat() {
	var audio = document.getElementById(\'Audio-Player\');
	var img = document.getElementById(\'Button-Repeat-Img\');
	
	if (audio.loop) {
		audio.loop = false;
		img.src = \'button.repeat.off.svg\';
	} else {
		audio.loop = true;
		img.src = \'button.repeat.svg\';
	}
}
function ButtonVolume() {
	var audio = document.getElementById(\'Audio-Player\');
	var img = document.getElementById(\'Button-Volume-Img\');
	
	if (audio.volume > 0) {
		audio.volume = 0;
		SetVolume(0);
		img.src = \'button.volume.mute.svg\';
	} else {
		audio.volume = 1;
		SetVolume(100);
		img.src = \'button.volume.svg\';
	}
}
function ButtonStop() {
	var audio = document.getElementById(\'Audio-Player\');
	var img = document.getElementById(\'Button-Play-Img\');
	
	img.src = \'button.play.svg\';
	
	audio.pause();
	audio.currentTime = 0;
	SetProgress(0);
}
function ButtonSpeed() {
	var audio = document.getElementById(\'Audio-Player\');
	var text = document.getElementById(\'Text-PlaySpeed\');
	
	if (parseFloat(audio.playbackRate) !== 4) {
		audio.playbackRate = (parseFloat(audio.playbackRate)+0.5);
	} else {
		audio.playbackRate = 1;
	}
	text.innerHTML = \'Play speed: \'+(parseFloat(audio.playbackRate)*100)+\'%\';
}
function ButtonRestartTrack() {
	var audio = document.getElementById(\'Audio-Player\');
	SetTime(0);
}

function onload() {
	var audio = document.getElementById(\'Audio-Player\');
	audio.loop = false;
	document.getElementById(\'Button-Repeat-Img\').src = \'button.repeat.off.svg\';
	document.getElementById(\'Button-Play\').disabled = true;
	
	var library = document.getElementById(\'Library-Frame\');
	library.src = \'library.php\'
	
	audio.onpause = function() {
		document.getElementById(\'Button-Play-Img\').src = \'button.play.svg\';
		document.getElementById(\'Text-NowPlaying\').style.animation = \'\';
		ToolbarButtons.Play.menu.options[0].enable();
		ToolbarButtons.Play.menu.options[1].disable();
	}
	audio.onplay = function() {
		document.getElementById(\'Button-Play-Img\').src = \'button.pause.svg\';
		document.getElementById(\'Text-NowPlaying\').style.animation = \'Text-NowPlaying-Animation 5s linear infinite\';
		ToolbarButtons.Play.menu.options[1].enable();
		ToolbarButtons.Play.menu.options[0].disable();
	}
	audio.ontimeupdate = function() {
		document.getElementById(\'Text-Duration\').innerHTML = FormatTime(Math.round(audio.currentTime))+\' / \'+FormatTime(Math.round(audio.duration));
		SetProgress((audio.currentTime/audio.duration)*100);
	}
	audio.oncanplay = function() {
		document.getElementById(\'Text-Duration\').innerHTML = \'00:00 / \'+FormatTime(Math.round(audio.duration));
		document.getElementById(\'Button-Play\').disabled = false;
		
		audio.oncanplay = function() {
			document.getElementById(\'Text-Duration\').innerHTML = \'00:00 / \'+FormatTime(Math.round(audio.duration));
			document.getElementById(\'Button-Play\').disabled = false;
			
			audio.play();
		}
		
		'.$autoplay.'
	}
	audio.onended = function() {
		document.getElementById(\'Text-Duration\').innerHTML = \'00:00 / \'+FormatTime(Math.round(audio.duration));
		SetProgress(0);
	}
	audio.onerror = function() {
		var errcode = audio.error.code;
		if (errcode == 1) {
			var errmsg = "MEDIA_ERR_ABORTED - fetching process aborted by user";
		}
		if (errcode == 2) {
			var errmsg = "MEDIA_ERR_NETWORK - error occurred when downloading";
		}
		if (errcode == 3) {
			var errmsg = "MEDIA_ERR_DECODE - error occurred when decoding";
		}
		if (errcode == 4) {
			var errmsg = "MEDIA_ERR_SRC_NOT_SUPPORTED - media type not supported";
		}

		window.top.shell.dialog("Play error","An error occured when attempting to play the media:<br>"+errmsg,"Media Player");
		ButtonStop();
		document.getElementById(\'Text-Duration\').innerHTML = \'<span class="FlyUiTextHover" style="cursor:pointer;" onclick="window.top.shell.dialog(\\\'Play error\\\',\\\'An error occured when attempting to play the media:<br>\'+errmsg+\'\\\',\\\'Media Player\\\');">Error</span>\';		
	}
	
	Fly.window.expand.disable();
	
	ToolbarInit();
	ShortcutInit();
	'.$mode.'
	'.$repeat.'
	'.$load.'
}
</script>
';
?>

<div id="Player-Progress" class="FlyUiNoSelect"><div id="Player-Seek"></div></div>
<div id="Main"><span class="FlyUiText" style="color: #ffffff; font-size: 24px;">Now Playing</span><div class="FlyUiText" id="Text-NowPlaying"></div><div class="FlyUiText" style="margin-top:32px;" id="Text-PlaySpeed">Play speed: 100%</div></div>
<div id="Library"><iframe id="Library-Frame" style="width:100%;height:100%;" allowtransparency="true" scrolling="auto" frameborder="0" src=""></iframe></div>
<button id="Button-Play" class="FlyUiNoSelect" onclick="ButtonPlay();"><img id="Button-Play-Img" style="width:32px;height:32px;vertical-align:middle;pointer-events:none;" src="button.play.svg"></button>
<button id="Button-Stop" class="FlyUiNoSelect" onclick="ButtonStop();"><img id="Button-Stop-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.stop.svg"></button>
<button id="Button-Volume-Progress" class="FlyUiNoSelect" disabled><div id="Volume-Progress"><div id="Volume-Seek"></div></div></button>
<button id="Button-Volume" class="FlyUiNoSelect" onclick="ButtonVolume();" class="Button-Volume-Compact"><img id="Button-Volume-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.volume.svg"></button>
<button id="Button-Repeat" class="FlyUiNoSelect" onclick="ButtonRepeat();"><img id="Button-Repeat-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.repeat.svg"></button>
<!--<button id="Button-Open" onclick="ButtonOpen();"><img id="Button-Open-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.open.svg"></button>-->
<button id="Button-Speed-Reverse" class="FlyUiNoSelect" onclick="ButtonRestartTrack();"><img id="Button-Speed-Reverse-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.speed.reverse.svg"></button>
<button id="Button-Speed" class="FlyUiNoSelect" onclick="ButtonSpeed();"><img id="Button-Speed-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.speed.svg"></button>
<button id="Button-Mode" class="FlyUiNoSelect" onclick="ButtonMode();"><img id="Button-Mode-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.arrow.full.svg"></button>
<div id="Text-Duration" onclick="GotoTime();" class="FlyUiTextHover FlyUiNoSelect">Loading...</div>

<div id="ScrubAudio"></div>
<div id="ScrubVolume"></div>

<script>
var PlayerScrubber = document.getElementById('Player-Progress');
PlayerScrubber.mDown = false;
PlayerScrubber.mTime = 0;
PlayerScrubber.onmousedown = function(e) {
	var audio = document.getElementById('Audio-Player');
	if (audio.src !== '') {
		PlayerScrubber.mDown = true;
		PlayerScrubber.mTime = (e.pageX/Fly.window.size.get()[0])*audio.duration;
		var progress = document.getElementById('Player-Progress');
		var seek = document.getElementById('Player-Seek');
		progress.style.backgroundSize = ((e.pageX/Fly.window.size.get()[0])*100)+"% 100%, 100% 100%";
		seek.style.left = 'calc('+((e.pageX/Fly.window.size.get()[0])*100)+'% - 4px)';
		document.getElementById('Audio-Player').pause();
	}
}
PlayerScrubber.onmousemove = function() {
	if (PlayerScrubber.mDown) {
		document.getElementById('ScrubAudio').style.display = 'block';
	}
}
PlayerScrubber.onmouseup = function() {
	var audio = document.getElementById('Audio-Player');
		if (audio.src !== '') {
		PlayerScrubber.mDown = false;
		audio.play();
		SetTime(PlayerScrubber.mTime);
	}
}
var ScrubberCover = document.getElementById('ScrubAudio');
ScrubberCover.onmouseup = function() {
	ScrubberCover.style.display = 'none';
	PlayerScrubber.mDown = false;
	document.getElementById('Audio-Player').play();
	SetTime(PlayerScrubber.mTime);
}
ScrubberCover.onmouseout = function() {
	ScrubberCover.style.display = 'none';
	PlayerScrubber.mDown = false;
	document.getElementById('Audio-Player').play();
	SetTime(PlayerScrubber.mTime);
}
ScrubberCover.onmousemove = function(e) {
	tempX = e.pageX;
	if (tempX < 0) {tempX = 0;}
	if (tempX > Fly.window.size.get()[0]) {tempX = Fly.window.size.get()[0];}
	
	var audio = document.getElementById('Audio-Player');
	var progress = document.getElementById('Player-Progress');
	var seek = document.getElementById('Player-Seek');
	progress.style.backgroundSize = ((tempX/Fly.window.size.get()[0])*100)+"% 100%, 100% 100%";
	seek.style.left = 'calc('+((tempX/Fly.window.size.get()[0])*100)+'% - 4px)';
	
	document.getElementById('Text-Duration').innerHTML = FormatTime(Math.round((tempX/Fly.window.size.get()[0])*audio.duration))+' / '+FormatTime(Math.round(audio.duration));
	
	PlayerScrubber.mTime = (tempX/Fly.window.size.get()[0])*audio.duration;
}
// when mouse is down, creates cover, cover tracks mouse movement, when mouse lifted position moves to X then divides to calculate time

var VolumeScrubber = document.getElementById('Volume-Progress');
VolumeScrubber.mDown = false;
VolumeScrubber.mTime = 0;
VolumeScrubber.onmousedown = function(e) {
	VolumeScrubber.mDown = true;
	var progress = document.getElementById('Volume-Progress');
	var button = document.getElementById('Button-Volume-Progress');
	var seek = document.getElementById('Volume-Seek');
	
	var left = button.offsetLeft+progress.offsetLeft;
	var width = progress.offsetWidth;
	
	tempX = (e.pageX - left);
	if (tempX < 0) {tempX = 0;}
	if (tempX > width) {tempX = width;}
	
	progress.style.backgroundSize = ((tempX/width)*100)+"% 100%, 100% 100%";
	seek.style.left = 'calc('+((tempX/width)*100)+'% - 4px)';
	
	VolumeScrubber.mTime = (tempX/width)*100;
	
	SetVolume(VolumeScrubber.mTime);
}
VolumeScrubber.onmousemove = function() {
	if (VolumeScrubber.mDown) {
		document.getElementById('ScrubVolume').style.display = 'block';
	}
}
VolumeScrubber.onmouseup = function() {
	VolumeScrubber.mDown = false;
	SetVolume(VolumeScrubber.mTime);
}
var VScrubberCover = document.getElementById('ScrubVolume');
VScrubberCover.onmouseup = function() {
	VScrubberCover.style.display = 'none';
	VolumeScrubber.mDown = false;
	SetVolume(VolumeScrubber.mTime);
}
VScrubberCover.onmouseout = function() {
	VScrubberCover.style.display = 'none';
	VolumeScrubber.mDown = false;
}
VScrubberCover.onmousemove = function(e) {
	var progress = document.getElementById('Volume-Progress');
	var button = document.getElementById('Button-Volume-Progress');
	var seek = document.getElementById('Volume-Seek');
	
	var left = button.offsetLeft+progress.offsetLeft;
	var width = progress.offsetWidth;
	
	tempX = (e.pageX - left);
	if (tempX < 0) {tempX = 0;}
	if (tempX > width) {tempX = width;}
	
	progress.style.backgroundSize = ((tempX/width)*100)+"% 100%, 100% 100%";
	seek.style.left = 'calc('+((tempX/width)*100)+'% - 4px)';
	
	VolumeScrubber.mTime = (tempX/width)*100;
	
	SetVolume(VolumeScrubber.mTime);
}
// when mouse is down, creates cover, cover tracks mouse movement, when mouse lifted position moves to X then divides to calculate volume
</script>
<?php
/* include 'Fly.Application.php';
echo FlyApplicationCheckAssociation('mp3'); */ ?>
</body>
</html>