<!DOCTYPE html>
<head>
<?php
include 'fly.php';

echo '
<script>
Fly.window.focus.take(\''.$_GET['parent_id'].'\');
function CloseDialog() {
	Fly.window.focus.give(\''.$_GET['parent_id'].'\');
	window.top.document.getElementById(\''.$_GET['parent_id'].'\').window.bringToFront();
	Fly.window.close();
}
Fly.window.onclose = CloseDialog;
</script>

';
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
include 'Fly.Registry.php';
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
#okButton {
	min-width: 100px;
	position: absolute;
	bottom: 8px;
	right: 10px;
}
</style>
</head>
<body onload="load()">

<div id="main" class="FlyUiText">
Max undos: <input maxlength="2" type="text" style="width:32px;" value="<?php echo FlyRegistryGet('MaxUndos'); ?>"><br>
<input type="checkbox" id="optimizespeed"><label for="optimizespeed">Optimize speed</label>
</div>

<button onclick="Fly.window.onclose()" id="okButton"><img style="width:16px;height:16px;vertical-align:middle;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg"></button>

</body>
</html>
<?php
exit;
?>
