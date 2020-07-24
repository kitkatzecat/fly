<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
include 'Fly.File.php';
include 'Fly.Dialog.php';

$THEME = FlyLoadThemeFile(FlyUserThemeGenerate(),false)[1];
?>
<link rel="stylesheet" href="../style.css">
<style>
.color {
	display: inline-block;
    width: 50px;
    height: 50px;
    border-top: 1px solid #fff;
    border-left: 1px solid #fff;
    border-bottom: 1px solid #f2f2f2;
    border-right: 1px solid #f2f2f2;
    border-radius: 6px;
    box-shadow: 0px 0px 4px #000;
	background-color: #808080;
	background-size: 50%;
	background-repeat: no-repeat;
	background-position: center;
	vertical-align: middle;
	margin: 2px;
}
.container {
	display: inline-block;
	width: auto;
	height: auto;
	vertical-align: middle;
	margin-right: -2px;
	margin-bottom: -1px;
	margin-top: -1px;
}
#colors,#bcolors {
	text-align: left;
	margin-left: -5px;
}
</style>
<script src="color-thief.umd.js"></script>
<script>
var OptionsTree = [
	{name:'Personalization',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg',index:'personalization/index.php'},
	{name:'Window color and transparency',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',}
];
var Colors = [
	[164,196,0],
	[96,169,23],
	[0,138,0],
	[0,171,169],
	[27,161,226],
	[0,80,239],
	[106,0,255],
	[170,0,255],
	[244,114,208],
	[216,0,115],
	[162,0,37],
	[229,20,0],
	[250,104,0],
	[240,163,10],
	[227,200,0],
	[130,90,44],
	[109,135,100],
	[100,118,135],
	[118,96,138],
	[135,121,78],
	[0,0,0]
];
function ShowColors() {
	var box = document.getElementById('colors');

	Colors.forEach(function(color) {
		let d = document.createElement('div');
		d.className = 'FlyUiMenuItem container';
		d.addEventListener('click',function() {
			SetColor(color);
		});

		let c = document.createElement('img');
		c.className = 'color';
		c.src = '<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg';
		c.style.backgroundColor = 'rgb('+color[0]+','+color[1]+','+color[2]+')';
		c.title = `${color[0]},${color[1]},${color[2]}`;

		d.appendChild(c);
		box.appendChild(d);
	});

	var auto = document.createElement('p');
	auto.innerHTML = '<a onclick="Auto()"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg" class="inline-icon">Choose colors based on your background</a>';
	auto.style.textAlign = 'left';
	auto.id = 'auto';
	document.getElementById('box').appendChild(auto);

	var custom = document.createElement('p');
	custom.innerHTML = '<a onclick="CustomColor()"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>colors.svg" class="inline-icon">Choose a custom color</a>';
	custom.style.textAlign = 'left';
	document.getElementById('box').appendChild(custom);

	SetColor([<?php echo $THEME['COLOR_R'].','.$THEME['COLOR_G'].','.$THEME['COLOR_B']; ?>]);
}
function CustomColor() {
	Fly.dialog.color({title:'Custom Color',value:[CurrentColor[0],CurrentColor[1],CurrentColor[2]],custom:true,callback:function(c) {
		if (c) {
			SetColor(c);
		}
	}});
}
function SetColor(color,auto=false) {
	var preview = document.getElementById('preview');
	if (auto == true) {
		CurrentColor = color;
		preview.style.backgroundImage = 'url(\'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg\')';
	} else {
		CurrentColor = color;
		preview.style.backgroundImage = 'none';
	}
	preview.style.backgroundColor = `rgb(${color[0]},${color[1]},${color[2]})`;
	preview.title = `${color[0]},${color[1]},${color[2]}`;
}
var CurrentColor = [0,0,0];
function Auto() {
	var colorThief = new ColorThief();
	var img = document.getElementById('background');

	if (img.complete) {
		ShowAutoColors(colorThief.getPalette(img),true);
	} else {
		image.addEventListener('load', function() {
			ShowAutoColors(colorThief.getPalette(img),true);
		});
	}
}
function ShowAutoColors(AutoColors) {
	var box = document.getElementById('bcolors');

	AutoColors.forEach(function(color) {
		let d = document.createElement('div');
		d.className = 'FlyUiMenuItem container';
		d.addEventListener('click',function() {
			SetColor(color);
		});

		let c = document.createElement('img');
		c.className = 'color';
		c.src = '<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg';
		c.style.backgroundColor = 'rgb('+color[0]+','+color[1]+','+color[2]+')';
		c.title = `${color[0]},${color[1]},${color[2]}`;

		d.appendChild(c);
		box.appendChild(d);
	});

	document.getElementById('auto').style.display = 'none';

	document.getElementById('bcolorshead').style.display = 'block';
	box.style.display = 'block';
}
/*
function Auto() {
	var check = document.getElementById('auto');
	if (check.checked) {
		document.getElementById('box').style.display = 'none';
		SetColor([211,225,254],true);
	} else {
		document.getElementById('box').style.display = 'block';
		SetColor(CurrentColor);
	}
}
*/
</script>
</head>
<body onload="ShowColors()" class="FlyUiText FlyUiNoSelect">

<div class="title">Change window color and transparency</div>
<p class="description">Choose a color to display on window borders and title bars, the toolbar, and Jump menu.</p>

<img src="<?php echo $THEME['BACKGROUND']; ?>" id="background" style="display:none;">

<p>
	<img id="preview" src="<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg" class="color" style="margin-right:12px;background-color:<?php echo $THEME['COLOR_OPAQUE'];?>"></img>
	Current color
</p>

<div id="box" class="box">
	<p class="shead" style="text-align:left;">Default Colors</p>
	<p id="colors"></p>
	<p class="shead" id="bcolorshead" style="text-align:left;display:none;">Background Colors</p>
	<p id="bcolors" style="display:none;"></p>
</div>
<p><input type="checkbox" checked class="inline-icon" id="transparency"><label for="transparency">Enable transparency</label></p>

<!--<p><input type="range" name="" id=""></p>-->

<div class="buttons">
	<button title="Preview"><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg"></button>
	<button title="Save"><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button>
</div>

</body>
</html>