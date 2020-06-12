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
	},
	write: function(options={}) {
		var options_base = {
			method: 'text',
			content: '',
			file: false,
			overwrite: true,
			progress: function() {},
			ready: function() {}
		}

		options = Object.assign(Object.assign({},options_base),options);

		var json = {
			method: options['method'],
			overwrite: options['overwrite'],
			content: options['content'],
			file: options['file']
		};

		var body = 'content='+encodeURIComponent(JSON.stringify(json));

		var request = new XMLHttpRequest();
		request.open('POST','<?php echo $_FLY['RESOURCE']['URL']['COMPONENTS']; ?>file.php?d=write');
		request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		request.setRequestHeader("Cache-Control", "no-cache");
		request.addEventListener('readystatechange',function() {
			if (request.readyState == 4) {
				if (request.status == 200) {
					var result = request.responseText;
					try {
						result = JSON.parse(result);
						try {
							options.ready(result['status'],result['message']);
						} catch (e) {
							console.log(e);
						}
					} catch(e) {
						options.ready(false,'Invalid response: '+e);
					}
				} else {
					options.ready(false,request.statusText);
				}
			}
		});
		request.upload.addEventListener('progress',function(e) {
			var percent = e.loaded/e.total;
			try {
				options.progress(percent,e.loaded,e.total);
			} catch(e) {
				console.log(e);
			}
		});
		request.send(body);
	},
	read: function(options={}) {
		var options_base = {
			method: 'text',
			file: false,
			progress: function() {},
			ready: function() {}
		}

		options = Object.assign(Object.assign({},options_base),options);

		var json = {
			method: options['method'],
			file: options['file']
		};

		var body = 'content='+encodeURIComponent(JSON.stringify(json));

		var request = new XMLHttpRequest();
		request.open('POST','<?php echo $_FLY['RESOURCE']['URL']['COMPONENTS']; ?>file.php?d=read');
		request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		request.setRequestHeader("Cache-Control", "no-cache");
		request.addEventListener('readystatechange',function() {
			if (request.readyState == 4) {
				if (request.status == 200) {
					var result = request.responseText;
					try {
						options.ready(result);
					} catch (e) {
						console.log(e);
					}
				} else {
					options.ready(false,request.statusText);
				}
			}
		});
		request.addEventListener('progress',function(e) {
			var percent = e.loaded/e.total;
			try {
				options.progress(percent,e.loaded,e.total);
			} catch(e) {
				console.log(e);
			}
		});
		request.send(body);
	}
}
</script>