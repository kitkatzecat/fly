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
	
	Toolbar.add({text:'File',type:'dropdown',menu:[
		['New',[
			['Message',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mail.svg'}],
			['Folder',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg'}],
		],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg'}],
		[''],
		['Options',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg'}],
		[''],
		['Close',function(){Fly.window.close()},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]
	]});
	Toolbar.add({text:'Account',type:'dropdown',menu:[
		['someone@example.com',function(){},{toggled:true,icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>person.svg'}],
		[''],
		['Manage...',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg'}],
	]});
	Toolbar.add({type:'divider'});
	Toolbar.add({text:'Refresh',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg',action:function(){}});
	
	document.body.appendChild(Toolbar);
}
</script>
<style>
#main {
	position: absolute;
	top: 34px;
	left: 160px;
	right: 0px;
	bottom: 0px;
	background: #fff;
}
</style>
</head>
<body onload="onload()">

<div id="main"></div>

</body>
</html>