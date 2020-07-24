<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.File.php';
include 'Fly.Dialog.php';
?>
<style>
.button {
	position: absolute;
	top: 4px;
	width: 24px;
	height: 24px;
	background-size: 100% 100%;
	box-sizing: border-box;
	border: 1px solid transparent;
	opacity: 0.4;
	border-radius: 3px;
	padding: 5px;
	padding-top: 4px;
	line-height: 12px;
}
.button:hover {
	border: 1px solid rgba(0,0,0,0.3);
	background-image: linear-gradient(to bottom, rgba(255,255,255,0.25) 0%,rgba(0,0,0,0.1) 100%);
	opacity: 1;
}
.button:active {
	border: 1px solid rgba(0,0,0,0.5);
	background-image: linear-gradient(to top, rgba(255,255,255,0.1) 0%,rgba(0,0,0,0.25) 100%);
	opacity: 1;
	padding-top: 5px;
}
.button#x {
	right: 8px;
}
.button#plus {
	left: 8px;
}
.buttonImage {
	width: 12px;
	height: 12px;
	vertical-align: middle;
}
#top {
	position: absolute;
	top: 8px;
	left: 12px;
	right: 12px;
	height: 32px;
	background: linear-gradient(to bottom, rgb(250,244,182) 0%, rgb(230,224,162) 100%);
	z-index: 3;
}
#shadow {
	box-shadow: 0px 3px 10px rgba(0,0,0,0.8);
	position: absolute;
	top: 8px;
	left: 12px;
	right: 12px;
	bottom: 14px;
	z-index: 1;
}
#note {
	z-index: 2;
	background: linear-gradient(to bottom, rgb(250,244,182) 0%, rgb(230,224,162) 100%);
	overflow: auto;
	position: absolute;
	top: 40px;
	left: 12px;
	right: 12px;
	bottom: 14px;
	padding: 6px;
}
#note:focus {
	outline: none;
}
#grabber {
	position: absolute;
	bottom: -8px;
	right: -8px;
	transform: rotate(45deg);
	width: 16px;
	height: 16px;
	background-color: #888;
	opacity: 0;
	transition: opacity .1s linear;
}
</style>
<script>
Fly.window.ready = function() {
	Fly.window.title.hide();
	Fly.window.border.hide();
	movement();
	window.addEventListener('resize',movement);
	document.body.addEventListener('mouseover',function() {
		clearTimeout(grabberTimeout);
		document.getElementById('grabber').style.opacity = '1';
	});
	document.body.addEventListener('mouseleave',function() {
		clearTimeout(grabberTimeout);
		grabberTimeout = setTimeout(function() {document.getElementById('grabber').style.opacity = '0'},1000);
	});
	document.getElementById('x').addEventListener('click',close);
	document.getElementById('plus').addEventListener('click',newNote);
	document.getElementById('note').addEventListener('keyup',save.autosave);
	save.load();
	setInterval(save.checkPositionSize,2000);
	document.getElementById('note').focus();
}
var grabberTimeout;
function movement() {
	Fly.window.movement.set(46,8,window.innerWidth-24-64,32)
	document.getElementById('grabber').style.opacity = '1';
	clearTimeout(grabberTimeout);
	grabberTimeout = setTimeout(function() {document.getElementById('grabber').style.opacity = '0'},1000);
}
function close() {
	if (!!save.current) {
		Fly.dialog.confirm({
			title: 'Delete',
			message: 'Delete note?',
			content: 'Are you sure you want to permanently delete this note?',
			callback: function(r) {
				if (r) {
					save.delete();
				}
			}
		});
	} else {
		Fly.window.close();
	}
}
function newNote() {
	window.top.system.command('run:SprocketComputers.StickyNotes.Note');
}
var save = {
	delete: function() {
		Fly.command(`delete:${Fly.core['APP']['DATA_PATH']}${save.current},true`,function() {
			Fly.window.close();
		});
	},
	current: <?php echo (isset($_GET['file']) ? "'".$_GET['file']."'" : 'false'); ?>,
	lastPositionSize: '',
	checkPositionSize: function() {
		var position = Fly.window.position.get();
		var size = Fly.window.size.get();
		var positionSize = `${position[0]},${position[1]},${size[0]},${size[1]}`;
		if (positionSize != save.lastPositionSize) {
			save.lastPositionSize = positionSize;
			if (!save.timeout) {
				save.save();
			}
		}
	},
	load: function() {
		if (!!save.current) {
			Fly.file.read({
			method: 'text',
			file: `${Fly.core['APP']['DATA_PATH']}${save.current}`,
			ready: function(result,message) {
				if (!!result) {
					try {
						result = JSON.parse(result);
						document.getElementById('note').innerHTML = result['html'];
						Fly.window.size.set(result['w'],result['h']);
						Fly.window.position.set(result['x'],result['y']);
					} catch(e) {
						Fly.window.message('Error loading: '+e);
					}
				} else {
					Fly.window.message('Error loading: '+message);
				}
			}
		});
		}
	},
	timeout: false,
	autosave: function() {
		if (!!save.timeout) {
			clearTimeout(save.timeout);
		}
		save.timeout = setTimeout(save.save,2000)
	},
	save: function() {
		if (!!save.timeout) {
			clearTimeout(save.timeout);
		}
		save.timeout = false;
		if (document.getElementById('note').innerHTML == '' && !save.current) {
			return;
		}
		if (!save.current) {
			save.current = Date.now()+'.json';
		}
		var position = Fly.window.position.get();
		var size = Fly.window.size.get();
		var content = {
			html: document.getElementById('note').innerHTML,
			x: position[0],
			y: position[1],
			w: size[0],
			h: size[1],
			date: Date.now()
		}
		Fly.file.write({
				method: 'text',
				content: JSON.stringify(content),
				file: `${Fly.core['APP']['DATA_PATH']}${save.current}`,
				overwrite: true,
				ready: function(status,message) {
					if (!status) {
						Fly.window.message('Error saving: '+message);
					}
				}
			});
	}
}
</script>
</head>
<body>
<div id="shadow"></div>
<div id="note" contenteditable="true"></div>
<div id="top">
	<div title="Delete Sticky Note" class="button FlyUiNoSelect" id="x"><img src="sticky-x.svg" class="buttonImage"></div>
	<div title="New Sticky Note" class="button FlyUiNoSelect" id="plus"><img src="sticky-plus.svg" class="buttonImage"></div>
</div>
<div id="grabber"></div>
</body>
</html>