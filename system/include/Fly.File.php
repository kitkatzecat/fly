<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.FILE');

if (!FlyIncludeCheck('FLY.CONTROLS')) {
	include 'Fly.Controls.php';
}
if (!FlyIncludeCheck('FLY.COMMAND')) {
	include 'Fly.Command.php';
}
?>
<script>

Fly.file = {};

Fly.file.get = function(callback=function(){},options={}) {
	Fly.window.child({
		modal: true,
		attributes: {
			title:'Choose a File',
			name:Fly.window.name.get(),
			icon:Fly.window.icon.get(),
			x:(parseInt(Fly.window.position.get()[0])+32),
			y:(parseInt(Fly.window.position.get()[1])+32),
			width:500,
			height:300,
			location:'<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.zFileManager/dialogs.php?dialog=file_get',
			expand:false,
			minimize:false,
			close:true,
			resize:true,
			background:false,
			minheight:100,
			minwidth:200,
			maxheight:false,
			maxwidth:false,
			maxinitheight:false,
			maxinitwidth:false
		}
	},
	function(frame) {
		var win = frame.window.content.contentWindow;
		console.log(win);
	});
}

</script>