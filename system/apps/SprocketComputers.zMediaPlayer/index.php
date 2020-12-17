<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Dialog.php';
include 'Fly.File.php';
include 'Fly.CommonStyle.php';
?>
<style>
body,html {
	background-color: transparent;
}
button {
	box-sizing: border-box;
	min-width: 0;
}
</style>
<link rel="stylesheet" href="index.css">
<script src="iife.js"></script>
<script src="mutag.js"></script>
<script src="toolbars.js"></script>
<script src="player.js"></script>
<script>
Fly.window.ready = function() {
	Player.init();
	Toolbars.init();
}
</script>
</head>
<body>
<audio id="Audio"></audio>

<div id="Background">
	<div id="Background-Edge-Left"></div>
	<div id="Background-Middle-Left"></div>
	<div id="Background-Buttons-Left"></div>
	<div id="Background-Buttons"></div>
	<div id="Background-Buttons-Right"></div>
	<div id="Background-Middle-Right"></div>
	<div id="Background-Edge-Right"></div>
</div>

<div id="Main" style="display:block;">
	<video id="Video"></video>
	<div id="Canvas-Bg-Container"></div>
	<div id="Canvas-Container"></div>
	<div id="Text-Info">
		<img id="AlbumArt" src="img/art.default.svg">
		<div id="Text-Title" class="FlyCSTitle"></div>
		<p id="Text-Artist" class="FlyCSDescription"></p>
		<p id="Text-Subtitle" class="FlyCSDescription"></p>
	</div>
</div>

<!-- Seek bar -->
<div id="Player-Progress" class="FlyUiNoSelect"><div id="Player-Seek"></div></div>

<!-- Buttons -->
<button id="Button-Play" class="FlyUiNoSelect"><img id="Button-Play-Img" src="img/button.play.svg"></button>
<button id="Button-Stop" class="FlyUiNoSelect"><img id="Button-Stop-Img" src="img/button.stop.svg"></button>
<button id="Button-Repeat" class="FlyUiNoSelect"><img id="Button-Repeat-Img" src="img/button.repeat.svg"></button>
<button id="Button-Speed-Reverse" class="FlyUiNoSelect"><img id="Button-Speed-Reverse-Img" src="img/button.speed.reverse.svg"></button>
<button id="Button-Speed" class="FlyUiNoSelect"><img id="Button-Speed-Img" src="img/button.speed.svg"></button>
<button id="Button-Small" class="FlyUiNoSelect"><img id="Button-Small-Img" src="img/button.arrow.full.svg"></button>
<button id="Button-Big" class="FlyUiNoSelect"><img id="Button-Big-Img" src="img/button.arrow.compact.svg"></button>

<!-- Volume -->
<button id="Button-Volume-Progress" class="FlyUiNoSelect" disabled><div id="Volume-Progress"><div id="Volume-Seek"></div></div></button>
<button id="Button-Volume" class="FlyUiNoSelect" class="Button-Volume-Compact"><img id="Button-Volume-Img" src="img/button.volume.svg"></button>

<!-- Text -->
<div id="Text-Duration" class="FlyUiTextHover FlyUiNoSelect">Loading...</div>

<!-- Scrub cover -->
<div id="ScrubCover"></div>


</body>
</html>