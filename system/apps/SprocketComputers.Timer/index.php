<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
function onload() {
	Fly.toolbar.init();
	Fly.toolbar.add('Timer','timer',timer,'','right');
	Fly.toolbar.add('Stopwatch','stopwatch',stopwatch,'','right');
	
	Fly.toolbar.add('Start','start',timer.start,'<?php echo FLY_ICONS_URL; ?>play.svg');
	
	document.getElementById('FlyToolbarItem-timer').toggleOn();
	
	timer.initTime(0);
	
}

function FormatTime(sec) {
	var hr = Math.floor(sec / 3600);
	var min = Math.floor((sec - (hr * 3600))/60);
	sec -= ((hr * 3600) + (min * 60));
	sec += ''; min += ''; hr += '';
	while (min.length < 2) {min = '0' + min;}
	while (sec.length < 2) {sec = '0' + sec;}
	while (hr.length < 2) {hr = '0' + hr;}
	return hr + ':' + min + '<small>:' + sec + '</small>';
}


function stopwatch() {
	if (timer.running) {
		timer.stop();
	}
	
	document.getElementById('timer-time').innerHTML = FormatTime(0);
	
	document.getElementById('FlyToolbarItem-stopwatch').toggleOn();
	document.getElementById('FlyToolbarItem-timer').toggleOff();
}

function timer() {
	document.getElementById('timer-time').innerHTML = FormatTime(0);
	
	document.getElementById('FlyToolbarItem-stopwatch').toggleOff();
	document.getElementById('FlyToolbarItem-timer').toggleOn();
}
timer.time = 0;
timer.init = 0;
timer.running = false;
timer.setTime = function(sec) {
	sec = parseInt(sec);
	timer.time = sec;
	document.getElementById('timer-time').innerHTML = FormatTime(sec);
}
timer.initTime = function(sec) {
	timer.stop();
	sec = parseInt(sec);
	timer.time = sec;
	timer.init = sec;
	document.getElementById('timer-time').innerHTML = FormatTime(sec);
}
timer.start = function() {
	if (timer.time > 0) {
		timer.interval = setInterval(function(){timer.setInterval()},1000);
		Fly.toolbar.remove('start');
		Fly.toolbar.add('Stop','stop',timer.stop,'<?php echo FLY_ICONS_URL; ?>stop.svg');
		Fly.toolbar.add('Pause','pause',timer.pause,'<?php echo FLY_ICONS_URL; ?>pause.svg');
		timer.running = true;
	}
}
timer.stop = function() {
	timer.setTime(timer.init);
	clearInterval(timer.interval);
	if (timer.running == true) {
		Fly.toolbar.remove('stop');
		Fly.toolbar.remove('pause');
		Fly.toolbar.add('Start','start',timer.start,'<?php echo FLY_ICONS_URL; ?>play.svg');
	}
}
timer.pause = function() {
	clearInterval(timer.interval);
	document.getElementById('FlyToolbarItem-pause').toggleOn();
	document.getElementById('FlyToolbarItem-pause').onclick = function() {
		timer.resume();
	}
}
timer.resume = function() {
	timer.interval = setInterval(function(){timer.setInterval()},1000);
	document.getElementById('FlyToolbarItem-pause').toggleOff();
	document.getElementById('FlyToolbarItem-pause').onclick = function() {
		timer.pause();
	}
}
timer.inputTime = function() {
	Fly.control.input('Enter time','Enter a new time in seconds.','Timer - Enter Time','<?php echo FLY_APPS_URL; ?>SprocketComputers.Timer/timer.svg',timer.initTime);
}
timer.setInterval = function() {
	if (timer.time == 1) {
		timer.stop();
		document.getElementById('audio').play();
	} else {
		timer.setTime(timer.time-1);
	}
}
</script>
</head>
<body onload="onload();">
<audio id="audio"><source src="alarm.mp3"></source></audio>
<div style="position:absolute;top:34px;left:0px;right:0px;bottom:0px;background:transparent;">
<div onclick="timer.inputTime()" id="timer-time" class="FlyUiTextHover" style="text-align:center;font-size:56px;">00:00<small>:00</small></div>
</div>
</body>
</html>