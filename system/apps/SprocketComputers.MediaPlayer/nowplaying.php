<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
?>
<style>
html,body {
	height: 100%;
	overflow: hidden;
}
body {
    background-image: url('background.title.svg');
    background-position: 0px bottom;
   	background-repeat: repeat-x;
}
@keyframes Body-Animation {
	from {background-position: 0px bottom;}
	to {background-position: 640px bottom;}
}
</style>
</head>
<body>
<div class="FlyCSTitle FlyCSSectionTitle">Now Playing <img src="media.svg" class="FlyCSSectionIcon"></div>
<div class="FlyCSTitle"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/audio.svg" class="FlyCSInlineIcon"><span id="Text-NowPlaying">No media</span></div>
<p class="FlyCSDescription" id="Text-PlaySpeed">Play speed: 100%</p>
<p><a>Create a playlist</a></p>
<p class="FlyCSDescription"><a>Add to playlist</a></p>
</body>
</html>