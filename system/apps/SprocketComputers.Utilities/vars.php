<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	background-color: #fff;
	overflow: auto;
	padding: 4px;
}
.item {
	cursor: default;
	font-family: monospace;
	word-wrap: break-word;
}
.button {
	width: 22px;
	height: 20px;
	float: right;
	line-height: 12px;
	font-size: 10px;
}
</style>
<script>
function onload() {
	Fly.window.expand.enable();
	Fly.window.resize.enable();
}
function toggleSection(id,button) {
	var element = document.getElementById(id);
	var button = document.getElementById(button);
	if (element.style.display == 'none') {
		element.style.display = 'block';
		button.innerHTML = '▲';
	} else {
		element.style.display = 'none';
		button.innerHTML = '▼';
	}
}
</script>
</head>
<body onload="onload()">
<div id="main" class="FlyUiText">
<?php
function recursiveArray($array) {
	$return = '';
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$rand = rand();
			$return .= '<div class="FlyUiMenuItem item" ondblclick="toggleSection(\''.$rand.'\',\''.$rand.'-button\');event.stopPropagation();"><span class="FlyUiNoSelect" style="font-weight:bold;">'.$key.'</span><div class="FlyUiMenuItem FlyUiNoSelect button" id="'.$rand.'-button" onclick="toggleSection(\''.$rand.'\',this.id);event.stopPropagation();">▼</div><div style="display:none;" id="'.$rand.'">'.recursiveArray($value).'</div></div>';
		} else {
			$return .= '<div class="FlyUiMenuItem item"><span class="FlyUiNoSelect" style="font-weight:bold;">'.$key.'</span><br>'.$value.'</div>';
		}
	}
	return $return;
}

echo '<h3 class="FlyUiNoSelect">All Fly System Variables</h3>';

echo recursiveArray($_FLY);

echo '<hr>';

function allVars($array,$str) {
	$return = '';
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$string = $str.$key.'.';
			$return .= allVars($value,$string);
		} else {
			if ($str !== '%FLY.INCLUDES.') {
				if ($str == '%FLY.APP.' || $str == '%FLY.USER.') {
					$return .= '<div class="FlyUiMenuItem item">'.$str.$key.'%<span style="opacity:0.7;margin-left:8px;" class="FlyUiText FlyUiNoSelect"><small>(may not be available in all situations)</small></span></div>';
				} else {
					$return .= '<div class="FlyUiMenuItem item">'.$str.$key.'%</div>';
				}
			}
		}
	}
	return $return;
}

echo '<h3 class="FlyUiNoSelect">All Available Percent-Variables</h3>';

echo allVars($_FLY,'%FLY.');
?>
</div>
</body>
</html>