<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
var Toolbar;
function onload() {
	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.top = '0px';
	Toolbar.style.left = '0px';
	Toolbar.style.right = '0px';
	
	Toolbar.add({text:'Main Section',type:'dropdown',menu:[
		['Main Section',function(){},{toggled:true}],
		['Secure Section',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>lock.svg'}],
		[''],
		['New Section',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg'}],
		['Manage...',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg'}]
	]});
	Toolbar.add({text:'New Note',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg',align:'right'});
	
	document.body.appendChild(Toolbar);
	
	Fly.window.title.set('Notebook - Main Section');
}
</script>
<style>
#main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	background-color: #fff;
	overflow: auto;
	padding: 4px;
}

</style>
</head>
<body onload="onload()">

<div id="main">

<div class="FlyUiMenuItem FlyUiNoSelect" style="margin-bottom:4px;">
<b>My First Note</b><br>
Tuesday, July 3, 2017 at 6:22 PM
</div>

<div class="FlyUiMenuItem FlyUiNoSelect" style="margin-bottom:4px;">
<b>My Second Note</b><br>
Tuesday, July 3, 2017 at 6:23 PM
</div>

<div class="FlyUiMenuItem FlyUiNoSelect" style="margin-bottom:4px;">
<b>My Third Note</b><br>
Tuesday, July 3, 2017 at 6:24 PM
</div>

<div class="FlyUiMenuItem FlyUiNoSelect" style="margin-bottom:4px;">
<b>My Fourth Note</b><br>
Tuesday, July 3, 2017 at 6:25 PM
</div>

</div>

</body>
</html>