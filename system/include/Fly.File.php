<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.FILE');

if (!FlyIncludeCheck('FLY.COMMAND')) {
	include 'Fly.Command.php';
}
if (!FlyIncludeCheck('FLY.WINDOW')) {
	include 'Fly.Window.php';
}
?>
<script>
if (typeof Fly == 'undefined') {
	var Fly = {};
}

Fly.file = {
	get: function(callback=function(){},options={}) { // TODO - Add options for type/mime filtering
		var pos = Fly.window.position.get();

		var attributes = {
			title: Fly.window.name.get() + ' - Choose a File',
			name: Fly.window.name.get(),
			icon: Fly.window.icon.get(),
			x: parseInt(pos[0]+32),
			y: parseInt(pos[1]+32),
			width: 500,
			height: 300,
			location: '<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.zFileManager/dialogs.php?dialog=file_get',
			expand: false,
			minimize: false,
			close: true,
			resize: true,
			background: false,
			minheight: 108,
			minwidth: 370,
			maxheight: false,
			maxwidth: false,
			maxinitheight: false,
			maxinitwidth: false
		}

		Fly.window.child.open({modal: true,attributes: attributes}, function(frame) {
			frame.window.Dialog.options = options;
			frame.window.Dialog.opener = window;
			frame.window.Dialog.callback = callback;
		});
	},
	set: function(callback=function(){},options={}) { // TODO - add more in-depth type specification, prompt to overwrite
		var pos = Fly.window.position.get();

		var attributes = {
			title: Fly.window.name.get() + ' - Save a File',
			name: Fly.window.name.get(),
			icon: Fly.window.icon.get(),
			x: parseInt(pos[0]+32),
			y: parseInt(pos[1]+32),
			width: 500,
			height: 300,
			location: '<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.zFileManager/dialogs.php?dialog=file_set',
			expand: false,
			minimize: false,
			close: true,
			resize: true,
			background: false,
			minheight: 108,
			minwidth: 370,
			maxheight: false,
			maxwidth: false,
			maxinitheight: false,
			maxinitwidth: false
		}

		Fly.window.child.open({modal: true,attributes: attributes}, function(frame) {
			frame.window.Dialog.options = options;
			frame.window.Dialog.opener = window;
			frame.window.Dialog.callback = callback;
		});
	},
	string: {
		name: function(file) {
			return file.replace(/^.*[\\\/]/,'');
		},
		path: function(file) {
			return file.substr(0,file.lastIndexOf(Fly.file.string.name(file)));
		},
		bname: function(file) {
			return file.substr(0,file.lastIndexOf('.'));
		},
		extension: function(file) {
			return file.substr(file.lastIndexOf('.')+1);
		},
		trimslashes: function(file) {
			return file.replace(/#\/+#/,'/');
		}
	}
}
</script>