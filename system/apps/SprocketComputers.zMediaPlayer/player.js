var Player = {
	init: function() {
		Player.c.progress = document.getElementById('Player-Progress');
		Player.c.seek = document.getElementById('Player-Seek');
		Player.c.duration = document.getElementById('Text-Duration');
		Player.c.play = document.getElementById('Button-Play');
		Player.c.playImg = document.getElementById('Button-Play-Img');
		Player.c.next = document.getElementById('Button-Speed');
		Player.c.previous = document.getElementById('Button-Speed-Reverse');
		Player.c.stop = document.getElementById('Button-Stop');
		Player.c.repeat = document.getElementById('Button-Repeat');
		Player.c.mute = document.getElementById('Button-Volume');
		Player.c.big = document.getElementById('Button-Big');
		Player.c.small = document.getElementById('Button-Small');
		Player.c.canvasContainer = document.getElementById('Canvas-Container');
		Player.c.canvasBgContainer = document.getElementById('Canvas-Bg-Container');
		Player.c.canvasBg = document.getElementById('Canvas-Bg');
		Player.c.title = document.getElementById('Text-Title');
		Player.c.artist = document.getElementById('Text-Artist');
		Player.c.subtitle = document.getElementById('Text-Subtitle');
		Player.c.albumArt = document.getElementById('AlbumArt');
		Player.c.audio = document.getElementById('Audio');
		Player.c.video = document.getElementById('Video');
		Player.stream = document.getElementById('Audio');

		Player.c.audio.ontimeupdate = Player.streamEvents.ontimeupdate;
		Player.c.video.ontimeupdate = Player.streamEvents.ontimeupdate;

		Player.stream.onended = Player.streamEvents.onended;
		Player.c.video.onended = Player.streamEvents.onended;

		Player.c.audio.onplay = Player.streamEvents.onplay;
		Player.c.video.onplay = Player.streamEvents.onplay;

		Player.c.audio.onpause = Player.streamEvents.onpause;
		Player.c.video.onpause = Player.streamEvents.onpause;

		Player.c.audio.oncanplay = Player.streamEvents.oncanplay;
		Player.c.video.oncanplay = Player.streamEvents.oncanplay;

		Player.initVisualize('bars blocks');

		window.addEventListener('resize',Player.sizeCanvas);

		Player.c.stop.onclick = Player.button.stop;
		Player.c.play.onclick = Player.button.play;
		Player.c.big.onclick = Player.button.big;
		Player.c.small.onclick = Player.button.small;

		Player.close();

		Player.checkQuery();
	},
	loadingFromQuery: true,
	checkQuery: function() {
		var query = new URLSearchParams(window.location.search);
		if (query.has('file')) {
			Fly.command('fileprocess:'+query.get('file'),function(result) {
				Player.loadFileCheck(result['return']);
			});
		} else {
			Player.loadingFromQuery = false;
		}
	},
	streamEvents: {
		ontimeupdate: function() {
			if (!isNaN(Player.stream.duration)) {
				Player.setProgress((Player.stream.currentTime/Player.stream.duration)*100);
				Player.c.duration.innerText = `${FormatTime(Math.round(Player.stream.currentTime))} / ${FormatTime(Math.round(Player.stream.duration))}`;
			}
		},
		onended: function() {
			Player.setProgress(0);
			Player.c.duration.innerText = `00:00 / ${FormatTime(Math.round(Player.stream.duration))}`;
		},
		onplay: function() {
			if (Player.c.play.disabled) {
				Player.c.play.disabled = false;
			}
			Player.c.playImg.src = 'img/button.pause.svg';
		},
		onpause: function() {
			Player.c.playImg.src = 'img/button.play.svg';
		},
		oncanplay: function() {
			Player.c.play.disabled = false;
			Player.stream.play();
		}
	},
	initVisualize: function(visual) {
		if (!!Player.c.canvas) {
			Player.c.canvas.remove();
		}
		if (!!Player.c.canvasBg) {
			Player.c.canvasBg.remove();
		}
		var date = Date.now();

		Player.c.canvas = document.createElement('canvas');
		Player.c.canvas.id = 'Canvas-Visualize-'+date;
		Player.c.canvasContainer.appendChild(Player.c.canvas);

		Player.c.canvasBg = document.createElement('canvas');
		Player.c.canvasBg.style.filter = 'blur(40px) brightness(50%) saturate(200%)';
		Player.c.canvasBg.id = 'Canvas-Bg-Visualize-'+date;
		Player.c.canvasBg.className = 'Canvas-Bg';
		Player.c.canvasBgContainer.appendChild(Player.c.canvasBg);
		Player.visualizeBg(Player.c.canvas.id);

		Player.sizeCanvas();

		Player.wave = new Wave();
		Player.wave.fromElement("Audio",Player.c.canvas.id,{type:visual,skipUserEventsWatcher:true,colors:['rgb(180,180,255)','rgb(80,80,155)','rgb(40,40,115)']});
	},
	visualizeBg: function(canvas) {
		var bg = Player.c.canvasBg;
		if (Player.c.canvas.id == canvas) {
			var ctx = bg.getContext('2d');
			try {
				ctx.drawImage(Player.c.canvas,0,0);
			} catch(e) {console.log(e)}
			ctx.fillStyle = 'rgba(0,0,0,0.05)';
			ctx.fillRect(0,0,Player.c.canvasBg.width,Player.c.canvasBg.height);

			window.requestAnimationFrame(function() {
				Player.visualizeBg(canvas);
			});
		}
	},
	defaultAlbumArt: 'img/art.default.svg',
	c: {
		progress: false,
		seek: false,
		duration: false,
		play: false,
		playImg: false,
		next: false,
		previous: false,
		stop: false,
		repeat: false,
		mute: false,
		big: false,
		small: false,
		canvas: false,
		canvasContainer: false,
		title: false,
		artist: false,
		subtitle: false,
		albumArt: false
	},
	button: {
		stop: function() {
			Player.close();
		},
		play: function() {
			if (Player.stream.paused) {
				Player.stream.play();
			} else {
				Player.stream.pause();
			}
		},
		small: function() {
			Fly.window.size.set(window.top.innerWidth*0.6,window.top.innerHeight*0.6);
		},
		big: function() {
			Fly.window.size.set(375,325);
		}
	},
	setProgress: function(percent) {
		if (!/*currently scrubbing*/false) {
			Player.c.progress.style.backgroundSize = `${percent}% 100%, 100% 100%`;
			Player.c.seek.style.left = `calc(${percent}% - 4px)`;
		}
	},
	wave: false,
	stream: false,
	blob: false,
	file: false,
	loadFile: function() {
		Fly.file.get(function(result) {
			if (result) {
				Player.loadFileCheck(result);
			}
		},{
			path: '%FLY.USER.PATH%Media',
			types: ['audio/','video/','mp3','m4a','mp4','wav','ogg','ogv']
		});
	},
	loadFileCheck: function(file) {
		var type = Player.fileType(file);
		if (type == 'audio') {
			Player.loadAudio(file['URL']);
		} else if (type == 'video') {
			Player.loadVideo(file['URL']);
		} else {
			Fly.dialog.message({
				title: 'Unsupported File',
				message: 'Unsupported file',
				content: 'The file chosen to open is not supported by Media Player.',
				icon: `${Fly.core['RESOURCE']['URL']['ICONS']}error.svg`
			});
		}
},
	url: false,
	loadAudio: function(url) {
		if (Player.file) {
			Player.close();
		}
		if (Player.loadingFromQuery) {
			Player.loadingFromQuery = false;
			Fly.window.size.set(375,75);
		}
		Player.url = url;
		Player.c.title.innerText = Fly.file.string.bname(url);
		Player.c.artist.innerText = 'Loading...';
		Player.c.duration.innerText = 'Loading...';

		fetch(url).then(res => res.blob()).then(blob => {
			Player.blob = blob;

			const mutag = window.mutag;
			mutag.fetch(blob).then(tags => {
				Player.file = tags;
				Player.file['bname'] = Fly.file.string.bname(url);
				Player.loadReady();
			}).catch(e => console.log(e));
		});
	},
	loadVideo: function(url) {
		if (Player.file) {
			Player.close();
		}
		if (Player.loadingFromQuery) {
			Player.loadingFromQuery = false;
		}
		
		Player.c.title.innerText = Fly.file.string.bname(url);
		Player.stream = Player.c.video;
		Player.c.artist.innerText = 'Loading...';
		Player.c.duration.innerText = 'Loading...';
		Player.c.albumArt.src = 'img/art.video.svg';
		Player.file = {'bname':Fly.file.string.bname(url)};

		fetch(url).then(res => res.blob()).then(blob => {
			Player.blob = blob;
			Player.c.video.style.display = 'block';
			Player.loadReady();
		});
	},
	loadReady: function() {
		if ('APIC' in Player.file) {
			Player.c.albumArt.src = URL.createObjectURL(Player.file['APIC']);
		}
		if ('TIT2' in Player.file) {
			Player.c.title.innerText = Player.file['TIT2'];
		} else if ('TSOT' in Player.file) {
			Player.c.subtitle.innerText = Player.file['TSOT'];
		} else {
			Player.c.title.innerText = Player.file['bname'];
		}
		if ('TPE1' in Player.file) {
			Player.c.artist.innerText = Player.file['TPE1'];
		} else if ('TPE2' in Player.file) {
			Player.c.artist.innerText = Player.file['TPE2'];
		} else if ('TSOP' in Player.file) {
			Player.c.artist.innerText = Player.file['TSOP'];
		} else if ('TSO2' in Player.file) {
			Player.c.artist.innerText = Player.file['TPUB'];
		} else {
			Player.c.artist.innerText = '';
		}

		if ('TALB' in Player.file) {
			Player.c.subtitle.innerText = Player.file['TALB'];
		} else if ('TSOA' in Player.file) {
			Player.c.subtitle.innerText = Player.file['TSOA'];
		} else if ('TPUB' in Player.file) {
			Player.c.subtitle.innerText = Player.file['TPUB'];
		} else if ('TCOP' in Player.file) {
			Player.c.subtitle.innerText = Player.file['TCOP'];
		} else if ('TPUB' in Player.file) {
			Player.c.subtitle.innerText = Player.file['TPUB'];
		} else {
			Player.c.subtitle.innerText = '';
		}

		Fly.window.title.set(`${Player.c.title.innerText}`);
		if (Player.c.artist.innerText != '') {
			Fly.window.title.set(`${Player.c.title.innerText} by ${Player.c.artist.innerText}`);
		}
		Player.stream.src = URL.createObjectURL(Player.blob);
	},
	fileType: function(process) {
		var audio = ['m4a','mp3','wav'];
		var video = ['mp4','ogv','webm'];
		if (audio.indexOf(process['extension'].toLowerCase()) != -1) {
			return 'audio';
		} else if (video.indexOf(process['extension'].toLowerCase()) != -1) {
			return 'video';
		} else if (process['mime'].indexOf('audio/') != -1) {
			return 'audio';
		} else if (process['mime'].indexOf('video/') != -1) {
			return 'video';
		} else {
			return false;
		}
	},
	sizeCanvas: function() {
		Player.c.canvas.width = Player.c.canvasContainer.offsetWidth;
		Player.c.canvas.height = Player.c.canvasContainer.offsetHeight;

		Player.c.canvasBg.width = Player.c.canvasBgContainer.offsetWidth;
		Player.c.canvasBg.height = Player.c.canvasBgContainer.offsetHeight;
		Player.c.canvasBg.getContext('2d').clearRect(0,0,Player.c.canvasBg.width,Player.c.canvasBg.height);
	},
	filter: function() {
		Fly.dialog.select({
			title: 'Video Filter',
			message: 'Change video filter',
			content: 'Select a filter to apply to the video:',
			icon: `${Fly.core['RESOURCE']['URL']['ICONS']}colors.svg`,
			options: [
				{text:'None',value:false},
				{text:'Grayscale',value:'Grayscale'},
				{text:'Invert',value:'Invert'},
				{text:'Saturate',value:'Saturate'},
				{text:'Sepia',value:'Sepia'},
				{text:'Brightness 50%',value:'Brightness-50'},
				{text:'Brightness 150%',value:'Brightness-150'},
				{text:'Brightness 200%',value:'Brightness-200'}
			],
			callback: Player.setFilter
		});
	},
	setFilter: function(filter) {
		Player.c.video.className = '';
		if (!!filter) {
			Player.c.video.className = 'Filter-'+filter;
		}
	},
	visualization: function() {
		Fly.dialog.select({
			title: 'Audio Visualization',
			message: 'Change visualization',
			content: 'Select a visualization to show:',
			icon: `${Fly.core['RESOURCE']['URL']['ICONS']}type/image.svg`,
			options: [
				{text:"bars",value:"bars"},
				{text:"bars blocks",value:"bars blocks"},
				{text:"big bars",value:"big bars"},
				{text:"cubes",value:"cubes"},
				{text:"dualbars",value:"dualbars"},
				{text:"dualbars blocks",value:"dualbars blocks"},
				{text:"fireworks",value:"fireworks"},
				{text:"flower",value:"flower"},
				{text:"flower blocks",value:"flower blocks"},
				{text:"orbs",value:"orbs"},
				{text:"ring",value:"ring"},
				{text:"rings",value:"rings"},
				{text:"round layers",value:"round layers"},
				{text:"round wave",value:"round wave"},
				{text:"shine",value:"shine"},
				{text:"shine rings",value:"shine rings"},
				{text:"shockwave",value:"shockwave"},
				{text:"star",value:"star"},
				{text:"static",value:"static"},
				{text:"stitches",value:"stitches"},
				{text:"web",value:"web"},
				{text:"wave",value:"wave"}
			],
			callback: Player.initVisualize
		});
	},
	close: function() {
		Player.c.albumArt.src = Player.defaultAlbumArt;
		Player.c.title.innerText = 'Now Playing';
		Player.c.artist.innerText = 'Open a media file to see it here.';
		Player.c.subtitle.innerText = '';
		Player.c.duration.innerText = 'Stopped';
		Player.c.play.disabled = true;

		Fly.window.title.set(Fly.core['APP']['NAME']);
		
		Player.stream.pause();
		Player.setProgress(0);
		Player.stream.src = '';

		Player.stream = Player.c.audio;
		Player.c.video.style.display = 'none';

		Player.file = false;
		Player.url = false;
		Player.blob = false;
	}
}
function FormatTime(sec) {
	var hr = Math.floor(sec / 3600);
	var min = Math.floor((sec - (hr * 3600))/60);
	sec -= ((hr * 3600) + (min * 60));
	sec += ''; min += '';
	while (min.length < 2) {min = '0' + min;}
	while (sec.length < 2) {sec = '0' + sec;}
	hr = (hr)?hr+':':'';
	return hr + min + ':' + sec;
}