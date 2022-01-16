<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonScript.php';
include 'Fly.Dialog.php';
include 'Fly.File.php';
include 'Fly.Actionbar.php';
include 'Fly.Registry.php';

$i = '';
$save = FlyUserRegistryGet('Save');
if (!$save || !is_dir(FlyVarsReplace($save))) {
	$save = '%FLY.USER.PATH%/Media/Camera';
	if (!is_dir(FlyVarsReplace($save))) {
		mkdir(FlyVarsReplace($save));
	}
	FlyUserRegistrySet('Save',$save);
	$i = 'Fly.dialog.message({title:"Save Location",message:"Save location not found",content:"The specified photo save location could not be found. The photo save location has been reset to \"Camera\" in your Media folder.",icon:"'.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg"});';
}
?>
<script>
var Toolbar;
var Camera = {
	init: function() {
		<?php echo $i; ?>

		Camera.initToolbar();

		Camera.startVideo();

		Camera.buttons.takePhoto = document.getElementById('takePhoto');
		Camera.buttons.pause = document.getElementById('pause');
		Camera.buttons.viewPhotos = document.getElementById('viewPhotos');

		Camera.toggleStream = new Fly.CS.toggle(function() {
			Camera.buttons.pause.innerHTML = '<img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>play.svg" class="secondaryButtonImage">';
			Camera.buttons.pause.title = 'Play';
			Camera.video.pause();
		},function() {
			Camera.buttons.pause.innerHTML = '<img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pause.svg" class="secondaryButtonImage">';
			Camera.buttons.pause.title = 'Freeze';
			Camera.video.play();
		},false);

		Camera.timerOverlay = document.querySelector('#timerOverlay');
	},
	initToolbar: function() {
		Toolbar = new Fly.actionbar();
		Toolbar.style.position = 'absolute';
		Toolbar.style.top = '0px';
		Toolbar.style.left = '0px';
		Toolbar.id = 'Toolbar';
		Toolbar.btns = {};

		Toolbar.btns.Options = Toolbar.add({text:'Options',type:'dropdown',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg',menu:[
			['Change photo save location...',Camera.changeSaveLoc,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>browse.svg'}],
			[''],
			['Resolution: Unavailable',function(){},{disabled:true}]
		]});
		Toolbar.btns.Timer = Toolbar.add({text:'Timer',type:'dropdown',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg',menu:[
			['None', function() {Camera.setTimer=0;Camera.timer=0;Toolbar.btns.Timer.clearSelections();Toolbar.btns.Timer.menu.options[0].toggleOn();},{toggled:true}],
			['3 seconds', function() {Camera.setTimer=3;Camera.timer=3;Toolbar.btns.Timer.clearSelections();Toolbar.btns.Timer.menu.options[1].toggleOn();}],
			['5 seconds', function() {Camera.setTimer=5;Camera.timer=5;Toolbar.btns.Timer.clearSelections();Toolbar.btns.Timer.menu.options[2].toggleOn();}],
			['10 seconds', function() {Camera.setTimer=10;Camera.timer=10;Toolbar.btns.Timer.clearSelections();Toolbar.btns.Timer.menu.options[3].toggleOn();}],
			['20 seconds', function() {Camera.setTimer=20;Camera.timer=20;Toolbar.btns.Timer.clearSelections();Toolbar.btns.Timer.menu.options[4].toggleOn();}]
		]});
		Toolbar.btns.Timer.clearSelections = function() {
			Toolbar.btns.Timer.menu.options[0].toggleOff();
			Toolbar.btns.Timer.menu.options[1].toggleOff();
			Toolbar.btns.Timer.menu.options[2].toggleOff();
			Toolbar.btns.Timer.menu.options[3].toggleOff();
			Toolbar.btns.Timer.menu.options[4].toggleOff();
		}
		/*
		Toolbar.btns.Filters = Toolbar.add({text:'Filters',type:'dropdown',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>wand.svg',menu:[['']
		]});
		*/

		document.body.appendChild(Toolbar);
	},
	changeSaveLoc: function() {
		Fly.file.dir(function(a){
			if (!!a) {
				Fly.command('registry:set,Save,'+a['ffile'],function(r) {
					if (!r.return) {
						Fly.window.message.show('An error occurred while saving your options to the registry',4);
					} else {
						Camera.saveLoc = a['ffile'];
						Camera.saveLocBname = a['fname'];
						Fly.window.message(`Photos will now be saved in "${a['fname']}"`,3);
					}
				});
			}
		}, {path:Camera.saveLoc});
	},
	saveLoc: '<?php echo $save; ?>',
	saveLocBname: '<?php echo basename($save); ?>',
	video: false,
	canvas: false,
	startVideo: function() {
		Camera.canvas = document.querySelector('#canvasElement');
		Camera.canvas.ctx = Camera.canvas.getContext('2d');
		Camera.canvas.container = document.querySelector('#canvasContainer');

		Camera.video = document.querySelector("#videoElement");
		if (navigator.mediaDevices.getUserMedia) {
			navigator.mediaDevices.getUserMedia({ video: {
				width: {
					ideal: 4096
				},
				height: {
					ideal: 2160
				}
			} })
				.then(function (stream) {
					Camera.video.srcObject = stream;
				})
				.catch(function (err) {
					console.log("Something went wrong: "+err);
				});
		}
		Camera.video.addEventListener('loadedmetadata',function() {
			console.log(`width: ${this.videoWidth}, height: ${this.videoHeight}`);
			Camera.resizeWindow();
			Camera.buttons.takePhoto.disabled = false;
			Camera.buttons.pause.disabled = false;
		});
	},
	startPhoto: function() {
		Camera.buttons.takePhoto.disabled = true;
		if (Camera.timer == 0) {
			Camera.takePhoto();
		} else {
			Camera.timerTick();
		}
	},
	setTimer: 0,
	timer: 0,
	timerOverlay: false,
	timerTimeout: false,
	timerTick: function() {
		Camera.timer--;
		if (Camera.timer+1 > 0) {
			Camera.timerOverlay.style.display = 'block';
			Camera.timerOverlay.innerHTML = Camera.timer+1;
			Camera.timerTimeout = setTimeout(Camera.timerTick,1000);
			try {
				window.top.shell.sound.system('click');
			} catch(e) {}
		} else {
			Camera.timerTimeout = false;
			Camera.timerCancel();
			Camera.takePhoto();
		}
	},
	timerCancel: function() {
		if (!!Camera.timerTimeout) {clearTimeout(Camera.timerTimeout);}
		Camera.timer = Camera.setTimer;
		Camera.timerOverlay.style.display = 'none';
	},
	takePhoto: function() {
		Camera.canvas.ctx.drawImage(Camera.video,0,0,Camera.canvas.width,Camera.canvas.height);
		Camera.savePhoto();
		Camera.previewFlash();
		Camera.timer = Camera.setTimer;
	},
	previewFlash: function() {
		/*Camera.video.style.animation = 'flash .2s ease-out 1';
		setTimeout(function() {
			Camera.video.style.animation = 'none';
		}, 250);*/
		Camera.canvas.container.style.display = 'block';
		try {
			window.top.shell.sound('<?php echo $_FLY['WORKING_URL']; ?>shutter.mp3');
		} catch(e) {}
		setTimeout(function() {
			Camera.canvas.container.style.display = 'none';
			Camera.buttons.takePhoto.disabled = false;
		},1000);
	},
	savePhoto: function() {
		var content = Camera.canvas.toDataURL('image/jpeg');
		var name = Camera.getPhotoName();
		Fly.file.write({
			method: 'base64',
			content: content,
			file: `${Camera.saveLoc}/${name}`,
			overwrite: false,
			ready: function(r,m) {
				console.log(m);
				setTimeout(function() {
					if (r) {
						Fly.window.message(`"${name}" saved in "${Camera.saveLocBname}"`,3)
					} else {
						Fly.window.message('Failed to save photo: '+m,4);
					}
				},1000);
			}
		});
	},
	getPhotoName: function() {
		var date = new Date();
		var name = `IMG_${date.getFullYear()}${((date.getMonth()+1)+'').padStart(2,'0')}${((date.getDate())+'').padStart(2,'0')}_${((date.getHours())+'').padStart(2,'0')}${((date.getMinutes())+'').padStart(2,'0')}${((date.getSeconds())+'').padStart(2,'0')}.jpg`;
		return name;
	},
	viewPhotos: function() {
		try {
			window.top.system.command('run:'+Camera.saveLoc);
		} catch(e) {

		}
	},
	resizeWindow: function() {
		var videoWidth = Camera.video.videoWidth;
		var videoHeight = Camera.video.videoHeight;
		var width = 550;
		var height = (videoHeight*width)/videoWidth;
		Camera.video.style.width = `${width}px`;
		Camera.video.style.height = `${height}px`;
		Camera.canvas.width = Camera.video.videoWidth;
		Camera.canvas.height = Camera.video.videoHeight;
		Toolbar.btns.Options.menu.options[2].innerHTML = Toolbar.btns.Options.menu.options[2].innerHTML.replace('Unavailable',`${videoWidth}x${videoHeight}`);
		Fly.window.size.set(width,height+64+34);
	},
	buttons: {},
	toggleStream: false
}
Fly.window.ready = function() {
	Camera.init();
}
</script>
<style>
body,html {
	overflow: hidden;
}
@keyframes flash {
	from {filter:brightness(1000%) contrast(0%) }
	to {filter:brightness(100%) contrast(100%)}
}
#videoElement {
	position: absolute;
	top: 34px;
	left: 0;
	width: 550px;
	height: 412.5px;
	background-color: #000;
}
#buttons {
	position: absolute;
	bottom: 3px;
	left: 0;
	right: 0;
	height: 54px;
	text-align: center;
}
.primaryButton {
	width: 54px !important;
	height: 54px !important;
	min-width: 54px;
	min-height: 54px;
	border-radius: 100% !important;
	padding: 0 !important;
}
.primaryButtonImage {
	width: 32px;
	height: 32px;
	vertical-align: middle;
}
.secondaryButton {
	width: 42px !important;
	height: 42px !important;
	min-width: 42px;
	min-height: 42px;
	border-radius: 100% !important;
	padding: 0 !important;
}
.secondaryButtonImage {
	width: 20px;
	height: 20px;
	vertical-align: middle;
}
@keyframes canvasPhoto {
	from {
		transform: translate(-50%,-50%) rotate(10deg) scale(1.5);
		box-shadow: 0px 15px 40px #000;
	}
	to {
		transform: translate(-50%,-50%) rotate(-2deg) scale(1);
		box-shadow: 0px 5px 20px #000;
	}
}
#canvasContainer {
	position: absolute;
	top: 50%;
	left: 50%;
	width: 90%;
	background-color: #fff;
	padding: 18px;
	transform: translate(-50%,-50%) rotate(-2deg);
	box-sizing: border-box;
	box-shadow: 0px 5px 20px #000;
	animation: canvasPhoto .2s ease-out 1;
	z-index: 100;
}
#canvasElement {
	width: 100%;
	height: 100%;
}
#timerOverlay {
	position: absolute;
	top: 50%;
	left: 0;
	right: 0;
	text-align: center;
	transform: translateY(-50%);
	font-weight: bold;
	font-size: 90px;
	color: #fff;
	text-shadow: 0px 5px 15px #000;
}
</style>
</head>
<body class="FlyUiNoSelect">
	<video id="videoElement" autoplay="true"></video>
	<div id="buttons">
		<button onclick="Camera.viewPhotos();" class="secondaryButton" id="viewPhotos" title="View Photos"><img src="<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.PhotoViewer/photoviewer.svg" class="secondaryButtonImage"></button>
		<button disabled onclick="Camera.startPhoto();" class="primaryButton" id="takePhoto" title="Take Photo"><img class="primaryButtonImage" src="camera.svg"></button>
		<button disabled onclick="Camera.toggleStream.toggle();" class="secondaryButton" id="pause"></button>
	</div>
	<div id="canvasContainer" style="display:none;"><canvas id="canvasElement"></canvas></div>
	<div id="timerOverlay"></div>
</body>
</html>
