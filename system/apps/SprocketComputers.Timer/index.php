<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Actionbar.php';
include 'Fly.Fonts.php';
include 'Fly.Dialog.php';
FlyFontLoad('Droid_Sans');
?>
<script>
var Toolbar;
var Setbar;
function onload() {

	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.top = '0px';
	Toolbar.style.left = '0px';
	Toolbar.style.width = 'auto';

	Toolbar.add({text:'Start',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>play.svg',action:timer.start});

	document.body.appendChild(Toolbar);


	Setbar = new Fly.actionbar();
	Setbar.style.position = 'absolute';
	Setbar.style.top = '0px';
	Setbar.style.right = '0px';
	Setbar.style.width = 'auto';

	Setbar.add({text:'Set',icon:'<?php echo $_FLY['WORKING_URL']; ?>timer.svg',action:timer.set.show});

	document.body.appendChild(Setbar);

}

function FormatTime(sec) {
	var hr = Math.floor(sec / 3600);
	var min = Math.floor((sec - (hr * 3600))/60);
	sec -= ((hr * 3600) + (min * 60));
	sec += ''; min += ''; hr += '';
	while (min.length < 2) {min = '0' + min;}
	while (sec.length < 2) {sec = '0' + sec;}
	while (hr.length < 2) {hr = '0' + hr;}
	return [hr,min,sec];
}

/*
function stopwatch() {
	if (timer.running) {
		timer.stop();
	}
	
	document.getElementById('timer-time').innerHTML = FormatTime(0);
	
	document.getElementById('FlyToolbarItem-stopwatch').toggleOn();
	document.getElementById('FlyToolbarItem-timer').toggleOff();
}
*/

var timer = {};
timer.time = 0;
timer.init = 0;
timer.running = false;
timer.start = function() {
	if (timer.time > 0) {
		timer.init = timer.time;
		timer.interval = setInterval(function(){timer.setInterval()},1000);
		Toolbar.buttons[0].toggleOn();
		Toolbar.buttons[0].setAction(timer.stop);
		timer.running = true;
	}
}
timer.stop = function() {
	Toolbar.buttons[0].toggleOff();
	Toolbar.buttons[0].setAction(timer.start);
	timer.running = false;
	clearInterval(timer.interval);
}
timer.setInterval = function() {
	if (timer.time == 1) {
		timer.time = 0;
		timer.draw();
		timer.stop();
		var itime = FormatTime(timer.init);
		Fly.dialog.custom({
			modal: true,
			title: 'Done',
			message: 'Timer done',
			content: 'The timer for '+itime[0]+':'+itime[1]+':'+itime[2]+' has finished.<audio style="display:none;" autoplay><source src="<?php echo $_FLY['WORKING_URL']; ?>alarm.mp3"></audio>',
			sound: "",
			input: false,
			icon: "<?php echo $_FLY['WORKING_URL']; ?>timer.svg",
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-o.svg",
					default: true,
				}
			]
		});
	} else {
		timer.time--;
		timer.draw();
	}
}
timer.draw = function() {
	var h1 = document.getElementById('h1');
	var h2 = document.getElementById('h2');
	var m1 = document.getElementById('m1');
	var m2 = document.getElementById('m2');
	var s1 = document.getElementById('s1');
	var s2 = document.getElementById('s2');

	var time = FormatTime(timer.time);

	h1.innerHTML = time[0][0];
	h2.innerHTML = time[0][1];
	m1.innerHTML = time[1][0];
	m2.innerHTML = time[1][1];
	s1.innerHTML = time[2][0];
	s2.innerHTML = time[2][1];
}
timer.set = function(place,add) {
	var unit;
	if (place == 's') {
		unit = 1;
	} else if (place == 'm') {
		unit = 60;
	} else if (place == 'h') {
		unit = 3600;
	}
	if (add) {
		timer.time += unit;
	} else {
		timer.time -= unit;
		if (timer.time < 0) {
			timer.time = 0;
		}
	}

	timer.draw();
}
timer.set.show = function() {
	if (timer.running) {
		timer.stop();
	}
	var arrows = document.querySelectorAll('.arrow');
	arrows.forEach(function(arrow) {
		arrow.style.display = 'block';
	});
	Toolbar.style.display = 'none';
	Setbar.buttons[0].toggleOn();
	Setbar.buttons[0].setAction(timer.set.hide);
}
timer.set.hide = function() {
	var arrows = document.querySelectorAll('.arrow');
	arrows.forEach(function(arrow) {
		arrow.style.display = 'none';
	});
	Toolbar.style.display = 'block';
	Setbar.buttons[0].toggleOff();
	Setbar.buttons[0].setAction(timer.set.show);
}
</script>
<style>
.number {
	position: absolute;
	font-family: 'Droid Sans', sans-serif;
	top: 36px;
	font-size: 56px;
	width: 31px;
	text-align: center;
}
.number_small {
	top: 46px;
	font-size: 46px;
	width: 26px;
}
.arrow_up {
	top: 28px;
	font-size: 16px;
	z-index: 10;
}
.arrow_down {
	top: 86px;
	font-size: 16px;
	z-index: 10;
}
.arrow {
	display: none;
}
</style>
</head>
<body onload="onload();">

<div id="h1" class="FlyUiTextHighlight number" style="left:57px;">0</div>
<div id="h2" class="FlyUiTextHighlight number" style="left:88px;">0</div>
<div class="FlyUiTextHighlight number colon" style="left:119px;width:16px;">:</div>
<div id="m1" class="FlyUiTextHighlight number" style="left:135px;">0</div>
<div id="m2" class="FlyUiTextHighlight number" style="left:166px;">0</div>
<div class="FlyUiTextHighlight number number_small colon" style="left:197px;width:13px;">:</div>
<div id="s1" class="FlyUiTextHighlight number number_small" style="left:210px;">0</div>
<div id="s2" class="FlyUiTextHighlight number number_small" style="left:236px;">0</div>

<div onclick="timer.set('h',true);" class="FlyUiTextHover number arrow arrow_up" style="left:57px;width:62px;">⯅</div>
<div onclick="timer.set('m',true);" class="FlyUiTextHover number arrow arrow_up" style="left:135px;width:62px;">⯅</div>
<div onclick="timer.set('s',true);" class="FlyUiTextHover number arrow number_small arrow_up" style="left:210px;top:38px;width:52px;">⯅</div>

<div onclick="timer.set('h',false);" class="FlyUiTextHover number arrow arrow_down" style="left:57px;width:62px;">⯆</div>
<div onclick="timer.set('m',false);" class="FlyUiTextHover number arrow arrow_down" style="left:135px;width:62px;">⯆</div>
<div onclick="timer.set('s',false);" class="FlyUiTextHover number arrow number_small arrow_down" style="left:210px;width:52px;">⯆</div>

</body>
</html>