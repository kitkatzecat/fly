var ImgVr = {
	init: function() {
		ImgVr.image = document.getElementById('image');
		ImgVr.imageView = document.getElementById('imageView');
		ImgVr.imageSource = false;
		
		ImgVr.image.addEventListener('load',ImgVr.imgLoad);
		ImgVr.image.addEventListener('error',ImgVr.imgErr);

		ImgVr.checkQuery();
	},
	checkQuery: function() {
		var query = new URLSearchParams(window.location.search);
		if (query.has('file')) {
			ImgVr.open(query.get('file'))
		} else {
			Fly.file.get(
				ImgVr.open,
				{
					path: '%FLY.USER.PATH%Media',
					types: ['jpg','png','gif','bmp','image/']
				}
			);
		}
	},
	open: function(file) {
		ImgVr.image.style.maxWidth = 'auto';
		ImgVr.image.style.maxHeight = 'auto';
		if (typeof file !== 'object') {
			Fly.command('fileprocess:'+file,function(result) {
				//Player.loadFileCheck(result['return']);
				if (!!result['return']) {
					ImgVr.imageSource = result['return'];
					ImgVr.image.src = result['return']['URL'];
				} else {
					ImgVr.imageSource = file;
					ImgVr.notFound();
				}
			});
		} else {
			ImgVr.imageSource = file;
			ImgVr.image.src = file['URL'];
		}
	},
	notFound: function() {
		Fly.window.hide();
		Fly.dialog.message({
			title: 'File Not Found',
			message: 'File not found',
			content: `The file "${ImgVr.imageSource}" could not be found.`,
			icon: `${Fly.core['RESOURCE']['URL']['ICONS']}error.svg`,
			callback: function() {
				Fly.window.close();
			}
		});
	},
	imgErr: function() {
		Fly.window.hide();
		Fly.dialog.message({
			title: 'Invalid File',
			message: 'Invalid file',
			content: `The file "${ImgVr.imageSource['fname']}" could not be displayed. It may be corrupt or of an unsupported type.`,
			icon: `${Fly.core['RESOURCE']['URL']['ICONS']}error.svg`,
			callback: function() {
				Fly.window.close();
			}
		});
},
	imgLoad: function() {
		var size = [ImgVr.image.offsetWidth,ImgVr.image.offsetHeight];
		var screen = [window.top.innerWidth,window.top.innerHeight];

		console.log(size);

		size.forEach(function(n,i) {
			if (n > (screen[i]*0.7)) {
				let p = (screen[i]*0.7)/n;
				size.forEach(function(m,i) {
					size[i] = m*p;
				});
			}
		});

		Fly.window.size.set(size[0]+16,size[1]+8);

		ImgVr.imageView.src = ImgVr.imageSource['URL'];
		ImgVr.imageView.style.visibility = 'visible';

		Fly.window.title.set(ImgVr.imageSource['fname']);
		Fly.window.icon.set(ImgVr.imageSource['icon']);
	}
}