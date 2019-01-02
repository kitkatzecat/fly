<!DOCTYPE html>
<head>
<?php
include 'Fly.Standard.php';

if ($_GET['dialog'] == 'test') {
	goto test;
}
if ($_GET['dialog'] == 'options') {
	goto options;
}
exit;

test:
?>
<script>
function load() {

}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 4px;
	background-color: #fff;
}
</style>
</head>
<body onload="load()">

<div id="main">
Test dialog
</div>

</body>
</html>
<?php
exit;

options:
include 'Fly.Command.php';
include 'Fly.Registry.php';
?>
<script>
var Options = {
	MaxUndos: 0,
	OptimizeSpeed: false
}
function Update() {
	var maxundos = document.getElementById('maxundos');
	var optimizespeed = document.getElementById('optimizespeed');

	if (optimizespeed.checked) {
		maxundos.disabled = true;
		Options.OptimizeSpeed = true;
		document.getElementById('maxundos-optimizehint').style.display = 'block';
	} else {
		maxundos.disabled = false;
		Options.OptimizeSpeed = false;
		Options.MaxUndos = parseInt(maxundos.value);
		document.getElementById('maxundos-optimizehint').style.display = 'none';
	}
}
<?php
echo 'Options.MaxUndos = '.FlyRegistryGet('MaxUndos').';';
echo 'Options.OptimizeSpeed = '.FlyRegistryGet('OptimizeSpeed').';';
?>
Fly.window.ready = function() {
	var maxundos = document.getElementById('maxundos');
	var optimizespeed = document.getElementById('optimizespeed');

	maxundos.value = Options.MaxUndos;
	if (Options.OptimizeSpeed) {
		optimizespeed.checked = true;
	} else {
		optimizespeed.checked = false;
	}

	maxundos.onkeydown = ValidateNumber;

	maxundos.disabled = false;
	optimizespeed.disabled = false;
	document.getElementById('okButton').disabled = false;
	document.getElementById('cancelButton').disabled = false;
}
function ValidateNumber(e) {
	if (['0','1','2','3','4','5','6','7','8','9'].indexOf(e.key) == -1 && [8,46,37,38,39,40,45,35,36,144,9].indexOf(e.keyCode) == -1) {
		return false;
	}
	return true;
}
function Save() {
	Fly.window.message.show('Saving...')
	Fly.command('registry:set,MaxUndos,'+Options.MaxUndos,function(){
		Parent.MaxUndos = Options.MaxUndos;
		Fly.command('registry:set,OptimizeSpeed,'+Options.OptimizeSpeed,function() {
			Parent.Fly.window.message.show('Your options have been saved');
			Fly.window.onclose();
		});
	});
}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 4px;
	background-color: #fff;
	padding-top: 8px;
	overflow: auto;
}
#okButton {
	min-width: 100px;
	position: absolute;
	bottom: 8px;
	right: 10px;
}
#cancelButton {
	min-width: 100px;
	position: absolute;
	bottom: 8px;
	right: 120px;
}
h1,h2,h3,h4,h5,h6,p {
	padding-left: 6%;
	padding-right: 6%;
}
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 18px;
	padding-bottom: 16px;
	padding-left: 6%;
	padding-right: 6%;
}
p.description {
	margin-top: -12px;
}
p.hint {
	font-size: 0.8em;
	opacity: 0.8;
	margin-top: -16px;
	padding-left: 44px;
}
p.shead {
	font-size: 0.8em;
	opacity: 0.8;
	margin-bottom: -16px;
}
.inline-icon {
	width: 18px;
	height: 18px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
.title-icon {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
img.button-image {
	width: 16px;
	height: 16px;
	vertical-align: middle;
}
</style>
</head>
<body>

<div id="main" class="FlyUiText">
	<p><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>undo.svg">Max undos: <input disabled id="maxundos" onkeyup="Update()" maxlength="2" type="text" style="width:32px;" value=""><br></p>
	<p class="hint">The maximum length of the history that Brush will store for the Undo command. Larger numbers can reduce performance.</p>
	<p class="hint" id="maxundos-optimizehint" style="color:#f00;margin-top:-8px;display:none;">This option is disabled because the Optimize Speed option is enabled.</p>

	<p style="display:none;"><input type="checkbox" onchange="Update()" disabled class="inline-icon" id="optimizespeed"><label for="optimizespeed">Optimize speed</label></p>
	<p style="display:none;" class="hint">With this enabled, Brush will manage certain features such as Max Undos to make Brush faster and more responsive when working with larger images.</p>
</div>

<button onclick="Save()" disabled id="okButton"><img class="button-image" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button>
<button onclick="Fly.window.onclose()" disabled id="cancelButton"><img class="button-image" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"></button>

</body>
</html>
<?php
exit;
?>
