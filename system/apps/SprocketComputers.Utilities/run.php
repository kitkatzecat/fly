<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
function onload() {
	Fly.window.size.set(240,120);
	Fly.window.title.set('Run');
}
function check() {
	if (document.getElementById('cmdbox').checked) {
		document.getElementById('runbutton').innerHTML = "Execute";
	} else {
		document.getElementById('runbutton').innerHTML = "Run";
	}
}
function run() {
	if (document.getElementById('cmdbox').checked) {
		window.top.system.command(document.getElementById('runbox').value+'');
	} else {
		window.top.system.command('run:'+document.getElementById('runbox').value+'');
	}
}
function eva(event) {
    if (event.which == 13 || event.keyCode == 13) {
        run();
        return false;
    }
    return true;
}

</script>
<style>
.main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 6px;
}
.button {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
</style>
</head>
<body onload="onload();">
<div class="main FlyUiContent">
<input type="text" onkeypress="eva(event)" id="runbox" style="width:100%;box-sizing:border-box;font-family:Consolas,monospace;margin-bottom:8px;"><br>
<input type="checkbox" onchange="check()" id="cmdbox">&nbsp;<label class="FlyUiText FlyUiNoSelect" for="cmdbox">Execute as command</label>
</div>
<button class="button" id="runbutton" onclick="run()">Run</button>
</body>
</html>