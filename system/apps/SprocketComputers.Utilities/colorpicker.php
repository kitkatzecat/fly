<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

$color = explode(',',$_GET['color']);
?>
<script>
var OpenerWindow;
var ret = function() {};
var init = function(id) {
	OpenerWindow = id;
	Fly.window.focus.take(id);
	Fly.window.bringToFront();
}
var cancel = function() {
	Fly.window.focus.give(OpenerWindow);
	window.top.document.getElementById(OpenerWindow).window.bringToFront();
	Fly.window.close();
}

function toggleAdvanced() {
	var main = document.getElementById('main');
	var advanced = document.getElementById('advanced');
	var advancedButton = document.getElementById('advancedButton');
	if (Fly.window.size.get()[0] == 240) {
		advanced.style.display = 'block';
		advancedButton.innerHTML = 'Hide custom colors <img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-left.svg" style="width:12px;height:12px;vertical-align:middle;margin-top:-2px;pointer-events:none;">';
		Fly.window.size.set(480,324);
	} else {
		advanced.style.display = 'none';
		advancedButton.innerHTML = 'Show custom colors <img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg" style="width:12px;height:12px;vertical-align:middle;margin-top:-2px;pointer-events:none;">';
		Fly.window.size.set(240,324);
	}
}
function setColor(r,g,b) {
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	var lightness = document.getElementById('lightness');
	var arrow = document.getElementById('arrow');
	var crosshair = document.getElementById('crosshair');
	var current = document.getElementById('currentColor');
	
	var hsl = rgbToHsl(r,g,b);
	hsl[0] = parseFloat(hsl[0]);
	hsl[1] = parseFloat(hsl[1]);
	hsl[2] = parseFloat(hsl[2]);
	if (isNaN(hsl[0])) {hsl[0] = 0};
	if (isNaN(hsl[1])) {hsl[1] = 0};
	if (isNaN(hsl[2])) {hsl[2] = 0};
	
	ir.value = Math.round(r);
	ig.value = Math.round(g);
	ib.value = Math.round(b);
	ih.value = Math.round(hsl[0]*100);
	is.value = Math.round(hsl[1]*100);
	il.value = Math.round(hsl[2]*100);
	
	arrow.style.left = (hsl[2]*100)+'%';
	crosshair.style.left = (hsl[0]*222)+'px';
	crosshair.style.top = ((1-hsl[1])*124)+'px';
	
	var hslrgb = hslToRgb(hsl[0],hsl[1],0.5);
	hslrgb[0] = parseFloat(hslrgb[0]);
	hslrgb[1] = parseFloat(hslrgb[1]);
	hslrgb[2] = parseFloat(hslrgb[2]);
	if (isNaN(hslrgb[0])) {hslrgb[0] = 0};
	if (isNaN(hslrgb[1])) {hslrgb[1] = 0};
	if (isNaN(hslrgb[2])) {hslrgb[2] = 0};
	
	lightness.style.backgroundImage = 'linear-gradient(to right, rgb(0,0,0) 0%,rgb('+hslrgb[0]+','+hslrgb[1]+','+hslrgb[2]+') 50%,rgb(255,255,255) 100%)';
	current.style.backgroundColor = 'rgb('+r+','+g+','+b+')';
}
function previewColorHueSat(r,g,b) {
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	if (+ih.value > 100) {
		ih.value = 100;
	}
	if (+is.value > 100) {
		is.value = 100;
	}
	if (+il.value > 100) {
		il.value = 100;
	}
	var color = hslToRgb((ih.value/100),(is.value/100),(il.value/100));
	
	var r = color[0];
	var g = color[1];
	var b = color[2];
	
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	var lightness = document.getElementById('lightness');
	var arrow = document.getElementById('arrow');
	var current = document.getElementById('currentColor');
	var hsl = rgbToHsl(r,g,b);
	ir.value = Math.round(r);
	ig.value = Math.round(g);
	ib.value = Math.round(b);
	ih.value = Math.round(hsl[0]*100);
	is.value = Math.round(hsl[1]*100);
	il.value = Math.round(hsl[2]*100);
	arrow.style.left = (hsl[2]*100)+'%';
	var hslrgb = hslToRgb(hsl[0],hsl[1],0.5);
	lightness.style.backgroundImage = 'linear-gradient(to right, rgb(0,0,0) 0%,rgb('+hslrgb[0]+','+hslrgb[1]+','+hslrgb[2]+') 50%,rgb(255,255,255) 100%)';
	current.style.backgroundColor = 'rgb('+r+','+g+','+b+')';
}
function previewColorLightness() {
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	if (+ih.value > 100) {
		ih.value = 100;
	}
	if (+is.value > 100) {
		is.value = 100;
	}
	if (+il.value > 100) {
		il.value = 100;
	}
	var color = hslToRgb((ih.value/100),(is.value/100),(il.value/100));
	
	var r = color[0];
	var g = color[1];
	var b = color[2];
	
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	var lightness = document.getElementById('lightness');
	var crosshair = document.getElementById('crosshair');
	var current = document.getElementById('currentColor');
	var hsl = rgbToHsl(r,g,b);
	ir.value = Math.round(r);
	ig.value = Math.round(g);
	ib.value = Math.round(b);
	ih.value = Math.round(hsl[0]*100);
	is.value = Math.round(hsl[1]*100);
	il.value = Math.round(hsl[2]*100);
	crosshair.style.left = (hsl[0]*222)+'px';
	crosshair.style.top = ((1-hsl[1])*124)+'px';
	var hslrgb = hslToRgb(hsl[0],hsl[1],0.5);
	lightness.style.backgroundImage = 'linear-gradient(to right, rgb(0,0,0) 0%,rgb('+hslrgb[0]+','+hslrgb[1]+','+hslrgb[2]+') 50%,rgb(255,255,255) 100%)';
	current.style.backgroundColor = 'rgb('+r+','+g+','+b+')';
}
function checkColorRGB() {
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	if (+ir.value > 255) {
		ir.value = 255;
	}
	if (+ig.value > 255) {
		ig.value = 255;
	}
	if (+ib.value > 255) {
		ib.value = 255;
	}
	setColor(ir.value,ig.value,ib.value);
}
function checkColorHSL() {
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	if (+ih.value > 100) {
		ih.value = 100;
	}
	if (+is.value > 100) {
		is.value = 100;
	}
	if (+il.value > 100) {
		il.value = 100;
	}
	var color = hslToRgb((ih.value/100),(is.value/100),(il.value/100));
	setColor(color[0],color[1],color[2]);
}
function chooseColor() {
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	var rhex = (+ir.value).toString(16);
	if (rhex.length < 2) {
		rhex = '0'+rhex;
	}
	var ghex = (+ig.value).toString(16);
	if (ghex.length < 2) {
		ghex = '0'+ghex;
	}
	var bhex = (+ib.value).toString(16);
	if (bhex.length < 2) {
		bhex = '0'+bhex;
	}
	var ihex = '#'+rhex+ghex+bhex;
	Fly.window.focus.give(OpenerWindow);
	window.top.document.getElementById(OpenerWindow).window.bringToFront();
	ret({r:ir.value,g:ig.value,b:ib.value,hex:ihex,h:ih.value,s:is.value,l:il.value});
	Fly.window.close();
}
function hslToRgb(h, s, l){
	var r, g, b;

	if(s == 0){
		r = g = b = l; // achromatic
	} else {
		var hue2rgb = function hue2rgb(p, q, t){
			if(t < 0) t += 1;
			if(t > 1) t -= 1;
			if(t < 1/6) return p + (q - p) * 6 * t;
			if(t < 1/2) return q;
			if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
			return p;
		}

		var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
		var p = 2 * l - q;
		r = hue2rgb(p, q, h + 1/3);
		g = hue2rgb(p, q, h);
		b = hue2rgb(p, q, h - 1/3);
	}

	return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
}
function rgbToHsl(r, g, b){
	r /= 255, g /= 255, b /= 255;
	var max = Math.max(r, g, b), min = Math.min(r, g, b);
	var h, s, l = (max + min) / 2;

	if(max == min){
		h = s = 0; // achromatic
	} else {
		var d = max - min;
		s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
		switch(max){
			case r: h = (g - b) / d + (g < b ? 6 : 0); break;
			case g: h = (b - r) / d + 2; break;
			case b: h = (r - g) / d + 4; break;
		}
		h /= 6;
	}

	return [h, s, l];
}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 8px;
	width: 224px;
	bottom: 48px;
}
#advanced {
	display: none;
	position: absolute;
	top: 0px;
	left: 248px;
	width: 224px;
	bottom: 48px;
}
#advancedButton {
	width: 100%;
	position: absolute;
	bottom: 4px;
	left: 0px;
}
.choose {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 8px;
}
.cancel {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 115px;
}
.head {
	display: block;
	font-size: 12px;
	margin-bottom: 4px;
	margin-top: 10px;
}
.preset {
	width: 24px;
	height: 24px;
	border: 1px solid #000;
	margin: 1px;
	display: inline-block;
	cursor: pointer;
}
.preset:active {
	border: 2px solid #000;
	margin: 0px;
}
#background {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
}
#r, #g, #b, #h, #s, #l {
	width: 36px;
}
#huesat {
	position: relative;
	width: 222px;
	height: 124px;
	border: 1px solid #000;
	background-image: url('huesat.png');
	background-size: 100% 100%;
	cursor: crosshair;
}
#huesat:active {
	cursor: none;
}
#arrowcontainer {
	position: relative;
	width: 224px;
	height: 42px;
	margin-bottom: -22px;
	cursor: pointer;
	z-index: 2;
}
#lightness {
	position: relative;
	width: 222px;
	height: 20px;
	border: 1px solid #000;
	background-image: linear-gradient(to right, rgb(0,0,0) 0%,rgb(<?php echo $color[0]; ?>,<?php echo $color[1]; ?>,<?php echo $color[2]; ?>) 50%,rgb(255,255,255) 100%);
	background-size: 100% 100%;
	cursor: pointer;
}
#crosshair {
	position: absolute;
	top: 0px;
	left: 0px;
	width: 1px;
	height: 1px;
	overflow: visible;
	pointer-events: none;
}
#crosshair-img {
	position: absolute;
	top: -8px;
	left: -8px;
	width: 16px;
	height: 16px;
}
#arrow {
	position: absolute;
	bottom: 22px;
	left: 0%;
	width: 1px;
	height: 1px;
	cursor: pointer;
	pointer-events: none;
}
#arrow-img {
	position: absolute;
	top: -12px;
	left: -6px;
	width: 12px;
	height: 12px;
}
</style>
<script>
function onload() {
	Fly.window.minimize.disable();
	Fly.window.onclose = cancel;
	checkColorRGB();
}
</script>
</head>
<body onload="onload()">
<div id="background" class="FlyUiContent"></div>
<div id="main">
<span class="head FlyUiText FlyUiNoSelect">Current color:</span>
<div id="currentColor" style="width:100%;height:28px;box-sizing:border-box;border:1px solid #000;background-color:rgb(<?php echo $color[0]; ?>,<?php echo $color[1]; ?>,<?php echo $color[2]; ?>);"></div>

<span class="head FlyUiText FlyUiNoSelect">Preset colors:</span>
<div style="text-align:center;" class="FlyUiNoSelect">
<div class="preset" onclick="setColor(255,128,128)" style="background-color:rgb(255,128,128);"></div>		<div class="preset" onclick="setColor(255,255,128)" style="background-color:rgb(255,255,128);"></div>	<div class="preset" onclick="setColor(128,255,128)" style="background-color:rgb(128,255,128);"></div>	<div class="preset" onclick="setColor(128,255,255)" style="background-color:rgb(128,255,255);"></div>	<div class="preset" onclick="setColor(0,128,255)" style="background-color:rgb(0,128,255);"></div>		<div class="preset" onclick="setColor(255,128,192)" style="background-color:rgb(255,128,192);"></div>	<div class="preset" onclick="setColor(255,128,255)" style="background-color:rgb(255,128,255);"></div>
<div class="preset" onclick="setColor(255,000,000)" style="background-color:rgb(255,0,0);"></div>			<div class="preset" onclick="setColor(255,255,0)" style="background-color:rgb(255,255,0);"></div>		<div class="preset" onclick="setColor(128,255,0)" style="background-color:rgb(128,255,0);"></div>		<div class="preset" onclick="setColor(0,255,255)" style="background-color:rgb(0,255,255);"></div>		<div class="preset" onclick="setColor(0,128,192)" style="background-color:rgb(0,128,192);"></div>		<div class="preset" onclick="setColor(128,128,192)" style="background-color:rgb(128,128,192);"></div>	<div class="preset" onclick="setColor(255,0,255)" style="background-color:rgb(255,0,255);"></div>
<div class="preset" onclick="setColor(128,0,0)" style="background-color:rgb(128,0,0);"></div>				<div class="preset" onclick="setColor(255,128,0)" style="background-color:rgb(255,128,0);"></div>		<div class="preset" onclick="setColor(0,255,0)" style="background-color:rgb(0,255,0);"></div>			<div class="preset" onclick="setColor(0,128,128)" style="background-color:rgb(0,128,128);"></div>		<div class="preset" onclick="setColor(128,128,255)" style="background-color:rgb(128,128,255);"></div>	<div class="preset" onclick="setColor(128,0,64)" style="background-color:rgb(128,0,64);"></div>			<div class="preset" onclick="setColor(128,0,255)" style="background-color:rgb(128,0,255);"></div>
<div class="preset" onclick="setColor(64,0,0)" style="background-color:rgb(64,0,0);"></div>					<div class="preset" onclick="setColor(128,128,0)" style="background-color:rgb(128,128,0);"></div>		<div class="preset" onclick="setColor(0,128,0)" style="background-color:rgb(0,128,0);"></div>			<div class="preset" onclick="setColor(0,0,255)" style="background-color:rgb(0,0,255);"></div>			<div class="preset" onclick="setColor(0,0,160)" style="background-color:rgb(0,0,160);"></div>			<div class="preset" onclick="setColor(128,0,128)" style="background-color:rgb(128,0,128);"></div>		<div class="preset" onclick="setColor(64,0,128)" style="background-color:rgb(64,0,128);"></div>
<div class="preset" onclick="setColor(0,0,0)" style="background-color:rgb(0,0,0);"></div>				<div class="preset" onclick="setColor(128,64,0)" style="background-color:rgb(128,64,0);"></div>			<div class="preset" onclick="setColor(0,64,0)" style="background-color:rgb(0,64,0);"></div>				<div class="preset" onclick="setColor(0,0,128)" style="background-color:rgb(0,0,128);"></div>			<div class="preset" onclick="setColor(128,128,128)" style="background-color:rgb(128,128,128);"></div>	<div class="preset" onclick="setColor(192,192,192)" style="background-color:rgb(192,192,192);"></div>	<div class="preset" onclick="setColor(255,255,255)" style="background-color:rgb(255,255,255);"></div> 
<button id="advancedButton" class="FlyUiNoSelect" onclick="toggleAdvanced()">Show custom colors <img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg" style="width:12px;height:12px;vertical-align:middle;margin-top:-2px;pointer-events:none;"></button>
</div>
</div>

<div id="advanced">
<span class="head FlyUiText FlyUiNoSelect">Custom color:</span>
<div class="FlyUiNoSelect" id="huesat"><div class="FlyUiNoSelect" id="crosshair"><img class="FlyUiNoSelect" src="huesat-crosshair.png" id="crosshair-img"></div></div>
<div class="FlyUiNoSelect" id="arrowcontainer"><div class="FlyUiNoSelect" id="arrow"><img class="FlyUiNoSelect" src="lightness-arrow.png" id="arrow-img"></div></div>
<div class="FlyUiNoSelect" id="lightness"></div>

<span class="head FlyUiText FlyUiNoSelect"></span>
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">R: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorRGB()" id="r" value="<?php echo $color[0]; ?>">
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">G: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorRGB()" id="g" value="<?php echo $color[1]; ?>">
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">B: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorRGB()" id="b" value="<?php echo $color[2]; ?>">
<div style="margin-top:8px;"></div>
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">H: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorHSL()" id="h" value="<?php echo $color[0]; ?>">
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">S: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorHSL()" id="s" value="<?php echo $color[1]; ?>">
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">L: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorHSL()" id="l" value="<?php echo $color[2]; ?>">
</div>

<button class="choose FlyUiNoSelect" onclick="chooseColor()"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button class="cancel FlyUiNoSelect" onclick="cancel()"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

<script>
var colorPicker = function() {};
colorPicker.container = document.getElementById('advanced');
colorPicker.picker = document.getElementById('huesat');
colorPicker.crosshair = document.getElementById('crosshair');
colorPicker.h = document.getElementById('h');
colorPicker.s = document.getElementById('s');

colorPicker.picker.mouse = {x: 0, y: 0};

colorPicker.picker.addEventListener('mousemove', function(e) {
	colorPicker.picker.mouse.x = e.pageX - (colorPicker.container.offsetLeft + this.offsetLeft);
	colorPicker.picker.mouse.y = e.pageY - (colorPicker.container.offsetTop + this.offsetTop);
}, false);

colorPicker.picker.addEventListener('mousedown', function(e) {
	colorPicker.crosshair.style.top = colorPicker.picker.mouse.y+'px';
	colorPicker.crosshair.style.left = colorPicker.picker.mouse.x+'px';
 	
	if (lightnessSlider.l.value == '0') {
		lightnessSlider.l.value = 1;
	}
	if (lightnessSlider.l.value == '100') {
		lightnessSlider.l.value = 99;
	}
	
	lightnessSlider.slider.style.pointerEvents = 'none';
	
	colorPicker.picker.addEventListener('mousemove', colorPicker.picker.move, false);
	document.body.addEventListener('mouseup', colorPicker.picker.up, false);
	colorPicker.picker.addEventListener('mouseup', function(e) {
		colorPicker.picker.mouse.x = e.pageX - (colorPicker.container.offsetLeft + colorPicker.picker.offsetLeft);
		colorPicker.picker.mouse.y = e.pageY - (colorPicker.container.offsetTop + colorPicker.picker.offsetTop);
		colorPicker.h.value = Math.round((colorPicker.picker.mouse.x / 222)*100)
		colorPicker.s.value = Math.round((1-(colorPicker.picker.mouse.y / 124))*100);
	}, false);
}, false);
colorPicker.picker.move = function() {
	colorPicker.crosshair.style.top = colorPicker.picker.mouse.y+'px';
	colorPicker.crosshair.style.left = colorPicker.picker.mouse.x+'px';
	
	colorPicker.h.value = Math.round((colorPicker.picker.mouse.x / 222)*100)
	colorPicker.s.value = Math.round((1-(colorPicker.picker.mouse.y / 124))*100);
	
	previewColorHueSat();
}
colorPicker.picker.up = function(e) {	
	colorPicker.picker.removeEventListener('mousemove', colorPicker.picker.move, false);
	document.body.removeEventListener('mouseup', colorPicker.picker.up, false);
	previewColorHueSat();
	
	lightnessSlider.slider.style.pointerEvents = 'auto';
}

var lightnessSlider = function() {};
lightnessSlider.container = document.getElementById('advanced');
lightnessSlider.slider = document.getElementById('arrowcontainer');
lightnessSlider.arrow = document.getElementById('arrow');
lightnessSlider.l = document.getElementById('l');

lightnessSlider.slider.mouse = {x: 0};

lightnessSlider.slider.addEventListener('mousemove', function(e) {
	lightnessSlider.slider.mouse.x = e.pageX - (lightnessSlider.container.offsetLeft + this.offsetLeft);
}, false);

lightnessSlider.slider.addEventListener('mousedown', function(e) {
	lightnessSlider.arrow.style.left = lightnessSlider.slider.mouse.x+'px';
	
	colorPicker.picker.style.pointerEvents = 'none';

	lightnessSlider.slider.addEventListener('mousemove', lightnessSlider.slider.move, false);
	document.body.addEventListener('mouseup', lightnessSlider.slider.up, false);
	lightnessSlider.slider.addEventListener('mouseup', function(e) {
		lightnessSlider.slider.mouse.x = e.pageX - (lightnessSlider.container.offsetLeft + lightnessSlider.slider.offsetLeft);
		lightnessSlider.l.value = Math.round((lightnessSlider.slider.mouse.x / 222)*100)
	}, false);
}, false);
lightnessSlider.slider.move = function() {
	lightnessSlider.arrow.style.left = lightnessSlider.slider.mouse.x+'px';
	
	lightnessSlider.l.value = Math.round((lightnessSlider.slider.mouse.x / 222)*100)
	
	previewColorLightness();
}
lightnessSlider.slider.up = function(e) {
	lightnessSlider.slider.removeEventListener('mousemove', lightnessSlider.slider.move, false);
	document.body.removeEventListener('mouseup', lightnessSlider.slider.up, false);
	previewColorLightness();
	
	colorPicker.picker.style.pointerEvents = 'auto';
}
</script>
</body>
</html>