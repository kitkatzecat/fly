<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.File.php';

?>
<script>
var Toolbar;
function onload() {
	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.left = '0px';
	Toolbar.style.right = '0px';
	Toolbar.style.top = '0px';
	
	Toolbar.add({text:'Add Track...',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg',action:function(){
		Fly.file.get(function(r) {
			if (r) {
				Audio.add(r.URL,r.fname);
			}
		},{types:['mp3','m4a','wav','ogg','mp4','audio/']});
	}});
	Toolbar.add({text:'Remove All Tracks',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg',align:'right',action:Audio.remove});
	
	document.body.appendChild(Toolbar);
}

var Audio = {};
Audio.add = function(url,name) {
	var track = {};
	track.container = document.createElement('div');
	track.container.className = 'Track-Container FlyUiNoSelect FlyUiTextHighlight';
	track.container.object = track;
	
	track.player = document.createElement('audio');
	track.player.innerHTML = '<source src="'+url+'"></source>';
	track.container.appendChild(track.player);
	
	track.playButton = document.createElement('button');
	track.playButton.className = 'FlyUiNoSelect Button-Large';
	track.playButton.playImg = '<img style="width:32px;height:32px;vertical-align:middle;pointer-events:none;" src="button.play.svg">';
	track.playButton.pauseImg = '<img style="width:32px;height:32px;vertical-align:middle;pointer-events:none;" src="button.pause.svg">';
	track.playButton.innerHTML = track.playButton.playImg;
	track.playButton.onclick = function() {
		if (track.player.paused) {
			track.player.play();
		} else {
			track.player.pause();
		}
	}
	track.container.appendChild(track.playButton);
	
	track.muteButton = document.createElement('button');
	track.muteButton.className = 'FlyUiNoSelect Button-Small';
	track.muteButton.muteImg = '<img style="width:32px;height:20px;vertical-align:middle;pointer-events:none;" src="button.volume.mute.svg">';
	track.muteButton.unmuteImg = '<img style="width:32px;height:20px;vertical-align:middle;pointer-events:none;" src="button.volume.svg">';
	track.muteButton.innerHTML = track.muteButton.unmuteImg;
	track.muteButton.onclick = function() {
		if (track.player.volume == 0) {
			track.player.volume = 1;
		} else {
			track.player.volume = 0;
		}
	}
	track.container.appendChild(track.muteButton);
	
	track.removeButton = document.createElement('div');
	track.removeButton.className = 'FlyUiNoSelect FlyUiTextHover Button-Remove';
	track.removeButton.innerHTML = '𐌗';
	track.removeButton.onclick = function() {
		track.remove();
	}
	track.container.appendChild(track.removeButton);
	
	track.player.onplay = function() {
		track.playButton.innerHTML = track.playButton.pauseImg;
	}
	track.player.onpause = function() {
		track.playButton.innerHTML = track.playButton.playImg;
	}
	track.player.onvolumechange = function() {
		if (track.player.volume == 0) {
			track.muteButton.innerHTML = track.muteButton.muteImg;
		} else {
			track.muteButton.innerHTML = track.muteButton.unmuteImg;
		}
	}
	
	track.title = document.createElement('div');
	track.title.className = 'FlyUiNoSelect FlyUiTextHighlight';
	track.title.style.display = 'inline-block';
	track.title.style.marginLeft = '8px';
	track.title.innerHTML = name;
	track.container.appendChild(track.title);
	
	track.remove = function() {
		track.container.parentNode.removeChild(track.container);
	}
	
	document.getElementById('main').appendChild(track.container);
}

Audio.play = function() {
	var audios = document.getElementsByClassName('Track-Container');
	for (i = 0; i < audios.length; i++) { 
		audios[i].object.player.play();
	}
}

Audio.pause = function() {
	var audios = document.getElementsByClassName('Track-Container');
	for (i = 0; i < audios.length; i++) { 
		audios[i].object.player.pause();
	}
}

Audio.remove = function() {
	var audios = document.getElementsByClassName('Track-Container');
	for (var i = audios.length-1; i >= 0; i--) {
		audios[i].object.remove();
	}
}

</script>
<style>
#main {
	border-top: .1px solid #a0a0a0;
	border-bottom: .1px solid #a0a0a0;
	padding: 4px;
	padding-top: 8px;
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 76px;
	overflow: auto;
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
#Button-Pause {
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
#Button-Stop {
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
#Button-Volume {
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
	left: 200px;
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
	left: 164px;
}

.Button-Large {
	width: 48px !important;
	height: 48px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px !important;
	margin-right: 4px !important;
	vertical-align: middle;
}
.Button-Small {
	width: 32px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: center;
	padding: 0px;
	margin-right: 4px !important;
	vertical-align: middle;
}
.Button-Wide {
	width: 128px !important;
	height: 32px !important;
	border-radius: 100px;
	text-align: right;
	padding: 0px;
	margin-right: 4px !important;
	vertical-align: middle;
}
.Button-Remove {
	display: inline-block;
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	right: 8px;
}
.Track-Container {
	display: block;
	margin-bottom: 16px;
	position: relative;
}

</style>
</head>
<body onload="onload()">

<div id="main"></div>

<button id="Button-Play" class="FlyUiNoSelect" onclick="Audio.play()"><img id="Button-Play-Img" style="width:32px;height:32px;vertical-align:middle;pointer-events:none;" src="button.play.svg"></button>
<button id="Button-Pause" class="FlyUiNoSelect" onclick="Audio.pause();"><img id="Button-Pause-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.pause.svg"></button>
<!--<button id="Button-Stop" class="FlyUiNoSelect" onclick="ButtonStop();"><img id="Button-Stop-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.stop.svg"></button>
<button id="Button-Volume" class="FlyUiNoSelect" onclick="ButtonVolume();" class="Button-Volume-Compact"><img id="Button-Volume-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.volume.svg"></button>
<button id="Button-Speed-Reverse" class="FlyUiNoSelect" onclick="ButtonRestartTrack();"><img id="Button-Speed-Reverse-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.speed.reverse.svg"></button>
<button id="Button-Speed" class="FlyUiNoSelect" onclick="ButtonSpeed();"><img id="Button-Speed-Img" style="width:20px;height:20px;vertical-align:middle;pointer-events:none;" src="button.speed.svg"></button>-->

<script>
Fly.extension.replace('FileBrowser','SprocketComputers.FileManager','FileChooser');
document.getElementById('FileBrowser').onchange = function() {
	Audio.add(this.vars.URL,this.vars.basename);
}
</script>

</body>
</html>