<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'fileprocessor.php';
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
	left: 260px;
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
	left: 224px;
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
	background-image: url('background.nowplaying.svg'),url('background.header.svg');
	background-size: 100% auto;
	background-repeat: no-repeat;
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
</head>
<body>

<div id="Player-Progress" class="FlyUiNoSelect"><div id="Player-Seek"></div></div>
<button id="Button-Play" class="FlyUiNoSelect" onclick="ButtonPlay();"><img id="Button-Play-Img" style="width:32px;height:32px;vertical-align:middle;pointer-events:none;" src="button.play.svg"></button>
<button id="Button-Stop" class="FlyUiNoSelect" onclick="ButtonStop();"><img id="Button-Stop-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.stop.svg"></button>
<button id="Button-Volume-Progress" class="FlyUiNoSelect" disabled><div id="Volume-Progress"><div id="Volume-Seek"></div></div></button>
<button id="Button-Volume" class="FlyUiNoSelect" onclick="ButtonVolume();" class="Button-Volume-Compact"><img id="Button-Volume-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.volume.svg"></button>
<button id="Button-Speed-Reverse" class="FlyUiNoSelect" onclick="ButtonRestartTrack();"><img id="Button-Speed-Reverse-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.speed.reverse.svg"></button>
<button id="Button-Speed" class="FlyUiNoSelect" onclick="ButtonSpeed();"><img id="Button-Speed-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.speed.svg"></button>
<div id="Text-Duration" onclick="GotoTime();" class="FlyUiTextHover FlyUiNoSelect">Loading...</div>

</body>
</html>