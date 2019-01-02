<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';
include 'Fly.Registry.php';
include 'Fly.Actionmenu.php';
include 'Fly.Command.php';

echo FlyLoadExtension('SprocketComputers.FileManager','FileChooser');
echo FlyLoadExtension('SprocketComputers.FileManager','SaveDialog');
echo FlyLoadExtension('SprocketComputers.Utilities','ColorPicker');

echo $FlyFileStringFunction;

$canvasinit = '
function CanvasInit() {
	canvas.width = parseInt(sketch_style.getPropertyValue(\'width\'))-16;
	canvas.height = parseInt(sketch_style.getPropertyValue(\'height\'))-16;
	ovrlay.style.width = canvas.width+\'px\';
	ovrlay.style.height = canvas.height+\'px\';
	
	ctx.fillStyle = \'rgb(255,255,255)\';
	ctx.fillRect(0,0,canvas.width,canvas.height);
	
	CurrentState = {canvas:canvas.toDataURL(),width:canvas.width,height:canvas.height};

';

if (!empty($_GET['file'])) {
	$process = FlyFileStringProcessor($_GET['file']);
	if (!$process) {
		$canvasinitother = '
		window.top.shell.dialog(\'File not found\',\'The file "'.basename($_GET['file']).'" could not be loaded for editing because it does not exist.\',\'Not Found\');
		';
	} else {
		if (in_array($process['extension'],['jpg','jpeg','bmp','gif','png'])) {
			$img = getimagesize($process["file"]);
			$canvasinit = '
			function CanvasInit() {
				canvas.width = '.$img[0].';
				canvas.height = '.$img[1].';
				ovrlay.style.width = canvas.width+\'px\';
				ovrlay.style.height = canvas.height+\'px\';
				
				if (('.$img[0].'*'.$img[1].') > 1000000) {
					setTimeout(function(){window.top.shell.dialog(\'Large image\',\'The image loaded for editing is a large resolution, which may result in delays when using Brush. To minimize delays, enable the "Optimize speed" setting in Options.\',\'Brush - Large Image\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg\')},1000);
				}
				
				var img = new Image;
				img.src = \''.$process["URL"].'\';
				img.onload = function() {
					var pat = ctx.createPattern(img,\'no-repeat\');
					
					ctx.rect(0,0,canvas.width,canvas.height);
					ctx.fillStyle=pat;
					ctx.fill();
					
					CurrentState = {canvas:canvas.toDataURL(),width:canvas.width,height:canvas.height};
				}
			';
			if (in_array($_GET['new'],['true','on','yes'])) {
				$canvasinitother = '
				FileName = \'Untitled\';
				';
			} else {
				$canvasinitother = '
				FileName = \''.$process['name'].'\';
				File = \''.$process['file'].'\';
				';
			}
		} else {
			$canvasinitother = '
			window.top.shell.dialog(\'Unsupported file type\',\'The file "'.basename($_GET['file']).'" could not be loaded for editing because it is not a file type supported by Brush.\',\'Brush - Unsupported Type\');
			';
		}
	}
}
?>
<script>
var Actionbars = {};
var Tools = {};
var CurrentTool = {
	reset: function() {}
};
var Vars = {
	workingUrl: '<?php echo WORKING_URL; ?>',
	iconsUrl: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>',
}
</script>

<!-- Tools scripts -->
<script src="tools.pencil.js"></script>
<script src="tools.brush.js"></script>
<script src="tools.bucket.js"></script>
<script src="tools.eyedropper.js"></script>
<script src="tools.text.js"></script>
<script src="tools.watercolor.js"></script>

<script>

function OnLoad() {
	
	CanvasInit();
		
	Actionbars.Menubar = new Fly.actionbar();
	Actionbars.Menubar.style.position = 'absolute';
	Actionbars.Menubar.style.top = '0px';
	Actionbars.Menubar.style.width = 'auto';
	Actionbars.Menubar.style.left = '0px';
	Actionbars.Menubar.buttonsList = {};
	
	Actionbars.Menubar.buttonsList.file = Actionbars.Menubar.add({text:'File',type:'dropdown',menu:[
		['New',[
			['Blank image',New.blank],
			['From file...',New.file]
		],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg'}],
		['Open',Open.file,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg'}],
		['Save',Save.save,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg'}],
		['Save As',Save.opendialog],
		[''],
		['Properties',Properties,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'}],
		[''],
		['Close',Close,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}],
	]});
	Actionbars.Menubar.buttonsList.edit = Actionbars.Menubar.add({text:'Edit',type:'dropdown',menu:[
		['Undo',Undo,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>undo.svg'}],
		['Redo',Redo,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>redo.svg'}],
		[''],
		['Colors...'],
		[''],
		['Options',function(){Dialog.open('dialogs.php?dialog=options','Brush - Options');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg'}],
	]});
	Actionbars.Menubar.buttonsList.edit.menu.options[0].disable();
	Actionbars.Menubar.buttonsList.edit.menu.options[1].disable();
	
	Actionbars.Menubar.buttonsList.canvas = Actionbars.Menubar.add({	'text':'Canvas','type':'dropdown','menu':[
		['Clear',ClearCanvas,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}],
		['Resize...',function(){ResizeCanvas(200,200);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-left-up.svg'}],
		['Scale...',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-in.svg'}],
		[''],
		['Tool',[
			['Pencil',Tools.Pencil,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pencil.svg'}],
			['Brush', [
				['Watercolor',Tools.Watercolor],
				['Paint',Tools.Brush]
			],{icon:'<?php echo WORKING_URL; ?>paint.svg'}],
			['Bucket',Tools.Bucket,{icon:'<?php echo WORKING_URL; ?>brush.svg'}],
			['Text',Tools.Text,{icon:'<?php echo WORKING_URL; ?>txt.svg'}],
			['Eyedropper',Tools.Eyedropper,{icon:'<?php echo WORKING_URL; ?>eyedropper.svg'}],
		]],
		['Transparency', [
			['Set transparent color',function(){
				var transparentColor = hexToRgb(Color);
				SetTransparentColor(transparentColor['r'],transparentColor['g'],transparentColor['b']);
			},{icon:'transparent.svg'}],
			['Make canvas opaque',function(){
				SetOpaque();
			},{icon:'opaque.svg'}],
		]],
		['Filter', [
			['Invert',Filter.invert,{icon:'<?php echo WORKING_URL; ?>filter.invert.svg'}],
			['Grayscale',Filter.grayscale,{icon:'<?php echo WORKING_URL; ?>filter.grayscale.svg'}],
			['Sepia',Filter.sepia,{icon:'<?php echo WORKING_URL; ?>filter.sepia.svg'}],
		]],
		['Overlay',[
			['Set...',Overlay.open],
			['Clear',Overlay.clear],
			['Flatten',Overlay.flatten],
			['Opacity', [
				['100%',function() {Overlay.setOpacity(1);}],
				['90%',function() {Overlay.setOpacity(0.9);}],
				['80%',function() {Overlay.setOpacity(0.8);}],
				['70%',function() {Overlay.setOpacity(0.7);}],
				['60%',function() {Overlay.setOpacity(0.6);}],
				['50%',function() {Overlay.setOpacity(0.5);}],
				['40%',function() {Overlay.setOpacity(0.4);}],
				['30%',function() {Overlay.setOpacity(0.3);}],
				['20%',function() {Overlay.setOpacity(0.2);}],
				['10%',function() {Overlay.setOpacity(0.1);}],
				['0%',function() {Overlay.setOpacity(0);}],
			]]
		]],
	]});
		
	Actionbars.Menubar.buttonsList.canvas.overlayOpacity = [
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[0], // 100%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[1], // 90%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[2], // 80%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[3], // 70%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[4], // 60%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[5], // 50%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[6], // 40%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[7], // 30%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[8], // 20%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[9],  // 10%
		Actionbars.Menubar.buttonsList.canvas.menu.options[7].menu.options[3].menu.options[10]  // 0%
	];
	Actionbars.Menubar.buttonsList.canvas.overlayOpacityReset = function () {
		Actionbars.Menubar.buttonsList.canvas.overlayOpacity.forEach(function(option) {
			option.toggleOff();
		});
	}
	Actionbars.Menubar.buttonsList.canvas.overlayOpacity[7].toggleOn();

	Actionbars.Menubar.buttonsList.view = Actionbars.Menubar.add({text:'View',type:'dropdown',menu:[
		['Zoom In',ZoomIn,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg'}],
		['Zoom Out',ZoomOut,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-minus.svg'}],
		['Actual Size',ZoomActual],
		[''],
		['Zoom...',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg'}],
		[''],
		['Window',[
			['Fit to canvas',WindowFitCanvas],
			['Reset size',function(){Fly.window.size.set(640,480);}]
		],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg'}],
	]});
	
	
	Actionbars.Colorbar = new Fly.actionbar();
	Actionbars.Colorbar.style.position = 'absolute';
	Actionbars.Colorbar.style.top = '0px';
	Actionbars.Colorbar.style.width = 'auto';
	Actionbars.Colorbar.style.right = '0px';
	Actionbars.Colorbar.buttonsList = {};
	
	Actionbars.Colorbar.buttonsList.black = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#000000;"></div>',action:function(){SetColor('#000000');}});
	Actionbars.Colorbar.buttonsList.white = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#ffffff;"></div>',action:function(){SetColor('#ffffff');}});
	Actionbars.Colorbar.buttonsList.red = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#ff0000;"></div>',action:function(){SetColor('#ff0000');}});
	Actionbars.Colorbar.buttonsList.yellow = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#ffff00;"></div>',action:function(){SetColor('#ffff00');}});
	Actionbars.Colorbar.buttonsList.green = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#00ff00;"></div>',action:function(){SetColor('#00ff00');}});
	Actionbars.Colorbar.buttonsList.cyan = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#00ffff;"></div>',action:function(){SetColor('#00ffff');}});
	Actionbars.Colorbar.buttonsList.blue = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#0000ff;"></div>',action:function(){SetColor('#0000ff');}});
	Actionbars.Colorbar.buttonsList.magenta = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#ff00ff;"></div>',action:function(){SetColor('#ff00ff');}});
	Actionbars.Colorbar.add({type:'divider'});
	Actionbars.Colorbar.buttonsList.custom1 = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#c0c0c0;"></div>',action:function(){SetColor(Actionbars.Colorbar.buttonsList.custom1.hex);}});
	Actionbars.Colorbar.buttonsList.custom1.hex = '#c0c0c0';
	Actionbars.Colorbar.buttonsList.custom2 = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#c0c0c0;"></div>',action:function(){SetColor(Actionbars.Colorbar.buttonsList.custom2.hex);}});
	Actionbars.Colorbar.buttonsList.custom2.hex = '#c0c0c0';
	Actionbars.Colorbar.buttonsList.custom3 = Actionbars.Colorbar.add({text:'<div class="color-button" style="background-color:#c0c0c0;"></div>',action:function(){SetColor(Actionbars.Colorbar.buttonsList.custom3.hex);}});
	Actionbars.Colorbar.buttonsList.custom3.hex = '#c0c0c0';
	Actionbars.Colorbar.add({type:'divider'});
	Actionbars.Colorbar.buttonsList.color = Actionbars.Colorbar.add({text:'Custom',action:function(){document.getElementById('ColorPicker').choose()}});
	
	Actionbars.Colorbar.buttonsList.red.toggleOn();
	
	Actionbars.Colorbar.toggleAllOff = function() {
		Object.keys(Actionbars.Colorbar.buttonsList).forEach(function(key,index) {
		    Actionbars.Colorbar.buttonsList[key].toggleOff();
		});
	}
	
	Actionbars.Toolsbar = new Fly.actionbar();
	Actionbars.Toolsbar.style.position = 'absolute';
	Actionbars.Toolsbar.style.bottom = '0px';
	Actionbars.Toolsbar.style.width = 'auto';
	Actionbars.Toolsbar.style.left = '0px';
	Actionbars.Toolsbar.buttonsList = {};
	
	Actionbars.Toolsbar.buttonsList.pencil = Actionbars.Toolsbar.add({type:'button',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pencil.svg',action:Tools.Pencil});
	Actionbars.Toolsbar.buttonsList.paint = Actionbars.Toolsbar.add({type:'dropdown',icon:'<?php echo WORKING_URL; ?>paint.svg',menu:[
		['Paint',Tools.Brush],
		['Watercolor',Tools.Watercolor]
	]});
	Actionbars.Toolsbar.buttonsList.brush = Actionbars.Toolsbar.buttonsList.paint.menu.options[0];
	Actionbars.Toolsbar.buttonsList.watercolor = Actionbars.Toolsbar.buttonsList.paint.menu.options[1];
	Actionbars.Toolsbar.buttonsList.bucket = Actionbars.Toolsbar.add({type:'button',icon:'<?php echo WORKING_URL; ?>brush.svg',action:Tools.Bucket});
	Actionbars.Toolsbar.buttonsList.text = Actionbars.Toolsbar.add({type:'button',icon:'<?php echo WORKING_URL; ?>txt.svg',action:Tools.Text});
	Actionbars.Toolsbar.buttonsList.eyedropper = Actionbars.Toolsbar.add({type:'button',icon:'<?php echo WORKING_URL; ?>eyedropper.svg',action:Tools.Eyedropper});
	

	Actionbars.Toolsbar.toggleAllOff = function() {
		Object.keys(Actionbars.Toolsbar.buttonsList).forEach(function(key,index) {
		    Actionbars.Toolsbar.buttonsList[key].toggleOff();
		});
	}
	
	document.body.appendChild(Actionbars.Menubar);
	document.body.appendChild(Actionbars.Colorbar);
	document.body.appendChild(Actionbars.Toolsbar);
	
	Tools.Brush();
	
	ShortcutInit();
	
	Fly.window.title.set('Brush - '+FileName);
	Fly.window.onclose = Close;
}

var File = '';
var FileName = 'Untitled';
var Color = '#ff0000';
var ColorChange = function() {};
var Changes = false;

function ShortcutInit() {
	document.addEventListener("keydown", function(e) {
		//New (ctrl+n)
		if (e.keyCode == 78 && e.ctrlKey) {
			e.preventDefault();
			Actionbars.Menubar.buttonsList.file.onclick();
			setTimeout(Actionbars.Menubar.buttonsList.file.menu.options[0].onclick,100);
		}
		
		//Open (ctrl+o)
		if (e.keyCode == 79 && e.ctrlKey) {
			e.preventDefault();
			Open.file();
		}
		
		//Save (ctrl+s)
		if (e.keyCode == 83 && e.ctrlKey) {
			e.preventDefault();
			Save.save();
		}
		
		//Close (ctrl+q)
		if (e.keyCode == 81 && e.ctrlKey) {
			e.preventDefault();
			Close();
		}
		
		//Undo (ctrl+z)
		if (e.keyCode == 90 && e.ctrlKey) {
			e.preventDefault();
			Undo();
		}
		
		//Redo (ctrl+y)
		if (e.keyCode == 89 && e.ctrlKey) {
			e.preventDefault();
			Redo();
		}
		
		//Zoom in (ctrl++)
		if (e.keyCode == 187 && e.ctrlKey) {
			e.preventDefault();
			ZoomIn();
		}
		
		//Zoom out (ctrl+-)
		if (e.keyCode == 189 && e.ctrlKey) {
			e.preventDefault();
			ZoomOut();
		}
		
		//Zoom zero (ctrl+-)
		if (e.keyCode == 48 && e.ctrlKey) {
			e.preventDefault();
			ZoomActual();
		}
	}, false);
}

var New = {
	blank: function() {
		if (Changes) {
			Fly.control.confirm('Close current file','Are you sure you want to close the file "'+FileName+'"? Any unsaved changes will be lost.','Brush - Close File','<?php echo $_FLY['RESOURCE']['URL']['ICONS'];?>warning.svg',function(){window.location.href = '<?php echo WORKING_URL;?>index.php?Fly_Id=<?php echo $_GET['Fly_Id'];?>'});
		} else {
			window.location.href = '<?php echo WORKING_URL;?>index.php?Fly_Id=<?php echo $_GET['Fly_Id'];?>';
		}
	},
	file: function() {
		var browser = document.getElementById('FileBrowser');
		browser.onchange = New.filereturn;
		browser.browse();
	},
	filereturn: function() {
		var browser = document.getElementById('FileBrowser');
		browser.onchange = function(){};
		if (Changes) {
			Fly.control.confirm('Close current file','Are you sure you want to close the file "'+FileName+'"? Any unsaved changes will be lost.','Brush - Close File','<?php echo $_FLY['RESOURCE']['URL']['ICONS'];?>warning.svg',New.fileconfirm);
		} else {
			New.fileconfirm();
		}
	},
	fileconfirm: function() {
		var browser = document.getElementById('FileBrowser');
		window.location.href = '<?php echo WORKING_URL; ?>index.php?Fly_Id=<?php echo $_GET['Fly_Id']; ?>&new=true&file='+encodeURIComponent(browser.vars.path);
	}
};
var Open = {
	file: function() {
		var browser = document.getElementById('FileBrowser');
		browser.onchange = Open.filereturn;
		browser.browse();
	},
	filereturn: function() {
		var browser = document.getElementById('FileBrowser');
		browser.onchange = function(){};
		if (Changes) {
			Fly.control.confirm('Close current file','Are you sure you want to close the file "'+FileName+'"? Any unsaved changes will be lost.','Brush - Close File','<?php echo $_FLY['RESOURCE']['URL']['ICONS'];?>warning.svg',Open.fileconfirm);
		} else {
			Open.fileconfirm();
		}
	},
	fileconfirm: function() {
		var browser = document.getElementById('FileBrowser');
		window.location.href = '<?php echo WORKING_URL; ?>index.php?Fly_Id=<?php echo $_GET['Fly_Id']; ?>&file='+encodeURIComponent(browser.vars.path);
	}
};
var Save = {
	save: function() {
		if (File !== '') {
			Save.writefile();
		} else {
			Save.opendialog();
		}
	},
	opendialog: function() {
		document.getElementById('SaveDialog').browse();
	},
	checkfile: function() {
		var browser = document.getElementById('SaveDialog');
		var name = browser.vars.basename;
		if (name.indexOf('.') == -1) {
			name = FlyFileStringReplace(name)+'.png';
		} else {
			name = FlyFileStringReplace(name);
		}
		Save.temp.name = name;
		Save.temp.path = browser.vars.bpath+'/'+name;
		FlyCommand('exists:'+browser.vars.bpath+'/'+name,function(r){
			if (r.return == true) {
				Save.confirmoverwrite();
			} else {
				Save.confirmwrite();
			}
		});
	},
	confirmoverwrite: function() {
		var browser = document.getElementById('SaveDialog');
		Fly.control.confirm('File already exists','The file "'+Save.temp.name+'" already exists in "'+browser.vars.pbasename+'". Do you want to overwrite it?','Brush - File Exists','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',Save.confirmwrite);
	},
	confirmwrite: function() {
		File = Save.temp.path;
		FileName = Save.temp.name;
		Save.writefile();
	},
	writefile: function() {
		var data = canvas.toDataURL();
		document.getElementById('form-content').value = data;
		document.getElementById('form-filename').value = File;
		Form.submit();
		
		Fly.window.title.set('Brush - '+FileName);
		Changes = false;
		Fly.window.message('Saved "'+FileName+'"');
	},
	temp: {
		name: '',
		path: '',
	}
}
function Properties() {
	window.top.system.command('run:SprocketComputers.FileManager.Properties,file='+File);
}
function Close() {
	if (Changes) {
		Fly.control.confirm('Close current file','Are you sure you want to close the file "'+FileName+'"? Any unsaved changes will be lost.','Brush','<?php echo $_FLY['RESOURCE']['URL']['ICONS'];?>warning.svg',function(){setTimeout(Fly.window.close,10)});
	} else {
		Fly.window.close();
	}
}
var Filter = {
	invert: function() {
		var imgData = ctx.getImageData(0,0,canvas.width,canvas.height);
		
		// progress bar
		Fly.control.progress('Applying filter...','Applying Filter',function(dialog) {
			// invert colors
			setTimeout(function(){
				for (var i=0;i<imgData.data.length;i+=4) {
					imgData.data[i] = 255-imgData.data[i];
					imgData.data[i+1] = 255-imgData.data[i+1];
					imgData.data[i+2] = 255-imgData.data[i+2];
				}
				ctx.putImageData(imgData,0,0);
				try {
					dialog.position(100);
					setTimeout(dialog.complete,1000);
				} catch(err) {}
				UpdateState();
			}, 1000);
		});
	},
	grayscale: function() {
		var imgData = ctx.getImageData(0,0,canvas.width,canvas.height);
		
		// progress bar
		Fly.control.progress('Applying filter...','Applying Filter',function(dialog) {
			// invert colors
			setTimeout(function(){
				for (var i=0;i<imgData.data.length;i+=4) {
					var color = (imgData.data[i]+imgData.data[i+1]+imgData.data[i+2])/3;
					imgData.data[i] = color;
					imgData.data[i+1] = color;
					imgData.data[i+2] = color;
				}
				ctx.putImageData(imgData,0,0);
				try {
					dialog.position(100);
					setTimeout(dialog.complete,1000);
				} catch(err) {}
				UpdateState();
			}, 1000);
		});
	},
	sepia: function() {
		var imgData = ctx.getImageData(0,0,canvas.width,canvas.height);
		
		// progress bar
		Fly.control.progress('Applying filter...','Applying Filter',function(dialog) {
			// invert colors
			setTimeout(function(){
				for (var i=0;i<imgData.data.length;i+=4) {
					var color = (imgData.data[i]+imgData.data[i+1]+imgData.data[i+2])/3;
					imgData.data[i] = Math.floor(color+30,255);
					imgData.data[i+1] = Math.max(imgData.data[i]-10,color);
					imgData.data[i+2] = color;
				}
				ctx.putImageData(imgData,0,0);
				try {
					dialog.position(100);
					setTimeout(dialog.complete,1000);
				} catch(err) {}
				UpdateState();
			}, 1000);
		});

	},
}
var Overlay = {
	file: '',
	opacity: 0.3,
	open: function() {
		var browser = document.getElementById('FileBrowser');
		browser.onchange = Overlay.filereturn;
		browser.browse();
	},
	filereturn: function() {
		var browser = document.getElementById('FileBrowser');
		if (['jpg','png','bmp','gif','jpeg'].indexOf(browser.vars.extension) !== -1) {
			ovrlay.style.backgroundImage = 'url(\''+browser.vars.URL+'\')';
			Overlay.file = browser.vars.URL;
			if((parseFloat(ovrlay.style.opacity)*100) < 10) {
				ovrlay.style.opacity = '0.1';
			}
		} else {
			window.top.shell.dialog('Can\'t set overlay','The selected image could not be used for the overlay as it is of a file type not supported by Brush.','Brush - Overlay Error');
		}
	},
	setOpacity: function(opacity) {
		ovrlay.style.opacity = opacity;
		Overlay.opacity = opacity;
		Actionbars.Menubar.buttonsList.canvas.overlayOpacityReset();
		if (opacity == 1) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[0].toggleOn();
		} else if (opacity == 0.9) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[1].toggleOn();
		} else if (opacity == 0.8) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[2].toggleOn();
		} else if (opacity == 0.7) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[3].toggleOn();
		} else if (opacity == 0.6) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[4].toggleOn();
		} else if (opacity == 0.5) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[5].toggleOn();
		} else if (opacity == 0.4) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[6].toggleOn();
		} else if (opacity == 0.3) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[7].toggleOn();
		} else if (opacity == 0.2) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[8].toggleOn();
		} else if (opacity == 0.1) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[9].toggleOn();
		} else if (opacity == 0) {
			Actionbars.Menubar.buttonsList.canvas.overlayOpacity[10].toggleOn();
		}
	},
	clear: function() {
		ovrlay.style.backgroundImage = 'none';
	},
	flatten: function() {
		Fly.control.confirm('Flatten overlay','Are you sure you want to flatten the overlay? This will make it part of the image, and you will not be able to change the opacity or clear it.','Brush - Flatten Overlay','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg',Overlay.flattenconfirm);
	},
	flattenconfirm: function() {		
		Fly.control.progress('Flattening overlay...','Flattening Overlay',function(dialog) {
			setTimeout(function(){
				ctx.globalAlpha = Overlay.opacity;
				dialog.position(10);
				var img = new Image;
				img.src = Overlay.file;
				dialog.position(20);
				img.onload = function() {
					dialog.position(70);
					ctx.drawImage(img,0,0,canvas.width,canvas.height);
					dialog.position(80);
					
					ctx.globalAlpha = 1;
					Overlay.clear();
					dialog.position(90);
					
					UpdateState();
					CurrentTool();
					dialog.position(100);
					
					setTimeout(dialog.complete,1000);
				}
			}, 1000);
		});

	}
}
var Dialog = {
	open: function(url='dialogs.php?',title='<?php echo $_FLY['APP']['NAME']; ?>',width=300,height=300) {
		var pos = Fly.window.position.get();
		var size = Fly.window.size.get();
		var x = parseInt(pos[0])+parseInt((size[0]/2)-(width/2));
		var y = parseInt(pos[1])+parseInt((size[1]/2)-(height/2));
		url = '<?php echo $_FLY['WORKING_URL'];?>'+url+'&parent_id='+Fly.window.id;
		return window.top.task.create('<?php echo $_FLY['APP']['ID']; ?>',{name:title,title:title,width:width,height:height,icon:'<?php echo $_FLY['APP']['ICON_URL']; ?>',x:x,y:y,location:url});
	}
};
function CanvasContext(pos) {
	var options = [];
	
	if (Undos.length > 0) {
		options.push(['Undo',Undo,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>undo.svg'}]);
	} else {
		options.push(['Undo',Undo,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>undo.svg',disabled:true}]);
	}
	
	if (Redos.length > 0) {
		options.push(['Redo',Redo,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>redo.svg'}]);
	} else {
		options.push(['Redo',Redo,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>redo.svg',disabled:true}]);
	}
	
	options.push(['']);
	
	options.push(['Pencil',Tools.Pencil,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pencil.svg'}]);
	options.push(['Brush', [
			['Watercolor',Tools.Watercolor],
			['Paint',Tools.Brush]
		],{icon:'<?php echo WORKING_URL; ?>paint.svg'}]);
	options.push(['Bucket',Tools.Bucket,{icon:'<?php echo WORKING_URL; ?>brush.svg'}]);
	options.push(['Text',Tools.Text,{icon:'<?php echo WORKING_URL; ?>txt.svg'}]);
	options.push(['Eyedropper',Tools.Eyedropper,{icon:'<?php echo WORKING_URL; ?>eyedropper.svg'}]);
	
	Fly.actionmenu(pos,options);
}

<?php
echo $canvasinit;
echo $canvasinitother;
echo '}';
?>

function hexToRgb(hex) {
	var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
	hex = hex.replace(shorthandRegex, function(m, r, g, b) {
		return r + r + g + g + b + b;
	});

	var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	return result ? {
		r: parseInt(result[1], 16),
		g: parseInt(result[2], 16),
		b: parseInt(result[3], 16)
	} : null;
}
function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
    return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function SetColor(hex) {
	Actionbars.Colorbar.toggleAllOff()

	Color = hex;
	ColorChange();
	
	if (hex == '#000000') {
		Actionbars.Colorbar.buttonsList.black.toggleOn();
	} else if (hex == '#ffffff') {
		Actionbars.Colorbar.buttonsList.white.toggleOn();
	} else if (hex == '#ff0000') {
		Actionbars.Colorbar.buttonsList.red.toggleOn();
	} else if (hex == '#ffff00') {
		Actionbars.Colorbar.buttonsList.yellow.toggleOn();
	} else if (hex == '#00ff00') {
		Actionbars.Colorbar.buttonsList.green.toggleOn();
	} else if (hex == '#00ffff') {
		Actionbars.Colorbar.buttonsList.cyan.toggleOn();
	} else if (hex == '#0000ff') {
		Actionbars.Colorbar.buttonsList.blue.toggleOn();
	} else if (hex == '#ff00ff') {
		Actionbars.Colorbar.buttonsList.magenta.toggleOn();
	} else if (hex == Actionbars.Colorbar.buttonsList.custom1.hex) {
		Actionbars.Colorbar.buttonsList.custom1.toggleOn();
	} else if (hex == Actionbars.Colorbar.buttonsList.custom2.hex) {
		Actionbars.Colorbar.buttonsList.custom2.toggleOn();
	} else if (hex == Actionbars.Colorbar.buttonsList.custom3.hex) {
		Actionbars.Colorbar.buttonsList.custom3.toggleOn();
	} else {
		Actionbars.Colorbar.buttonsList.custom3.innerHTML = Actionbars.Colorbar.buttonsList.custom2.innerHTML;
		Actionbars.Colorbar.buttonsList.custom3.hex = Actionbars.Colorbar.buttonsList.custom2.hex;
		Actionbars.Colorbar.buttonsList.custom2.innerHTML = Actionbars.Colorbar.buttonsList.custom1.innerHTML;
		Actionbars.Colorbar.buttonsList.custom2.hex = Actionbars.Colorbar.buttonsList.custom1.hex;
		Actionbars.Colorbar.buttonsList.custom1.innerHTML = '<div class="color-button" style="background-color:'+hex+';"></div>';
		Actionbars.Colorbar.buttonsList.custom1.hex = hex;
		Actionbars.Colorbar.buttonsList.custom1.toggleOn();
	}
}
function SetTransparentColor(r,g,b) {
	var imgData = ctx.getImageData(0,0,canvas.width,canvas.height);

	for (var i=0;i<imgData.data.length;i+=4) {
		var color = (imgData.data[i]+imgData.data[i+1]+imgData.data[i+2])/3;
		if (imgData.data[i] == r && imgData.data[i+1] == g && imgData.data[i+2] == b) {
			imgData.data[i] = 0;
			imgData.data[i+1] = 0;
			imgData.data[i+2] = 0;
			imgData.data[i+3] = 0;
		}
	}
	ctx.putImageData(imgData,0,0);
	UpdateState();
}
function SetOpaque() {
	var imgData = ctx.getImageData(0,0,canvas.width,canvas.height);
	var opaqueColor = hexToRgb(Color);
	
	for (var i=0;i<imgData.data.length;i+=4) {
		var color = (imgData.data[i]+imgData.data[i+1]+imgData.data[i+2])/3;
		if (imgData.data[i+3] == 0) {
			imgData.data[i] = opaqueColor['r'];
			imgData.data[i+1] = opaqueColor['g'];
			imgData.data[i+2] = opaqueColor['b'];
			imgData.data[i+3] = 255;
		} else if (imgData.data[i+3] < 255) {
			imgData.data[i+3] = 255;
		}
	}
	ctx.putImageData(imgData,0,0);
	UpdateState();
}


var Zoom = 100;
function ZoomIn() {
	if (Zoom < 300) {
		Zoom += 25;
	} else if (Zoom < 1000) {
		Zoom += 100;
	} else {
		Zoom += 250;
	}
	canvas.style.transform = 'scale('+(Zoom/100)+')';
	ovrlay.style.transform = 'scale('+(Zoom/100)+')';
}
function ZoomOut() {
	if (Zoom > 25) {
		if (Zoom > 1000) {
			Zoom -= 250;
		} else if (Zoom > 1000) {
			Zoom -= 100;
		} else {
			Zoom -= 25;
		}
	}
	canvas.style.transform = 'scale('+(Zoom/100)+')';
	ovrlay.style.transform = 'scale('+(Zoom/100)+')';
}
function ZoomActual() {
	Zoom = 100;
	canvas.style.transform = 'scale('+(Zoom/100)+')';
	ovrlay.style.transform = 'scale('+(Zoom/100)+')';
}

function WindowFitCanvas() {
	var height = ((canvas.height*(Zoom/100))+86);
	var width = ((canvas.width*(Zoom/100))+16);
	
	if (width < 640) {
		width = 640;
	}
	if (height < 240) {
		height = 240;
	}
	document.getElementById('main').style.overflow = 'hidden';
	Fly.window.size.set(width,height);
	document.getElementById('main').style.overflow = 'auto';
}

function ClearCanvas() {
	canvas.width = canvas.width;
	ctx.fillStyle = Color;
	ctx.fillRect(0,0,canvas.width,canvas.height);

	CurrentTool();
	UpdateState();
}
function ResetCanvas() {
	ctx.fillStyle = Color;
	ctx.lineWidth = 1;
	ctx.lineJoin = 'miter';
	ctx.lineCap = 'butt';
	ctx.strokeStyle = Color;
}
function ResizeCanvas(width,height) {
	var oldwidth = canvas.width;
	var oldheight = canvas.height;
	var img = new Image;
	img.src = canvas.toDataURL();
	img.onload = function() {
		canvas.width = width;
		canvas.height = height;
		ctx.drawImage(img,0,0,oldwidth,oldheight);
		ovrlay.style.width = width+'px';
		ovrlay.style.height = height+'px';
		CurrentTool();
		UpdateState();
	}
}

var MaxUndos = <?php echo FlyRegistryGet('MaxUndos'); ?>;
var Undos = [];
var Redos = [];
var CurrentState = {};
function UpdateState() {
	if (!Changes) {
		Changes = true;
		Fly.window.title.set('Brush - '+FileName+' *');
	}
	Redos = [];
	Undos.unshift(CurrentState);
	CurrentState = {canvas:canvas.toDataURL(),width:canvas.width,height:canvas.height};
	Actionbars.Menubar.buttonsList.edit.menu.options[0].enable();
	Actionbars.Menubar.buttonsList.edit.menu.options[1].disable();
	while (Undos.length > MaxUndos) {
		Undos.pop();
	}
}
function Undo() {
	if (Undos.length > 0) {
		Redos.unshift(CurrentState);
		CurrentState = Undos.shift();
		
		Actionbars.Menubar.buttonsList.edit.menu.options[1].enable();
		
		if (Undos.length == 0) {
			Actionbars.Menubar.buttonsList.edit.menu.options[0].disable();
		}
		
		var img = new Image;
		img.src = CurrentState.canvas;
		img.onload = function() {
			canvas.width = CurrentState.width;
			canvas.height = CurrentState.height;
			ovrlay.style.width = CurrentState.width+'px';
			ovrlay.style.height = CurrentState.height+'px';
			ctx.drawImage(img,0,0,CurrentState.width,CurrentState.height);
			CurrentTool();
		}
	}
}
function Redo() {
	if (Redos.length > 0) {
		Undos.unshift(CurrentState);
		while (Undos.length > MaxUndos) {
			Undos.pop();
		}

		CurrentState = Redos.shift();
		Actionbars.Menubar.buttonsList.edit.menu.options[0].enable();
		if (Redos.length == 0) {
			Actionbars.Menubar.buttonsList.edit.menu.options[1].disable();
		}
				
		var img = new Image;
		img.src = CurrentState.canvas;
		img.onload = function() {
			canvas.width = CurrentState.width;
			canvas.height = CurrentState.height;
			ovrlay.style.width = CurrentState.width+'px';
			ovrlay.style.height = CurrentState.height+'px';
			ctx.drawImage(img,0,0,CurrentState.width,CurrentState.height);
			CurrentTool();
		}
	}
}
</script>
<style>
body {
	margin-right: 8px;
	margin-bottom: 8px;
	overflow-behavior: contain;
}
#main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 36px;
	background-color: #808080;
	overflow: auto;
	padding: 0px;
	margin: 0px;
	overflow-behavior: contain;
}
#paint {
	cursor: crosshair;
	transform-origin: 0px 0px;
	box-shadow: 0px 0px 6px #000000;
	transition: transform .3s ease-in-out;
	background-image: url('transparency.svg');
}
.color-button {
	width: 16px;
	height: 16px;
	display: inline-block;
	vertical-align: middle;
	border: .1px solid rgba(255,255,255,0.3);
	border-radius: 2px;
	background-image: linear-gradient(to bottom, rgba(255,255,255,0.4) 0%,rgba(255,255,255,0.3) 49%,rgba(255,255,255,0.2) 50%,rgba(255,255,255,0.3) 100%);
}
#overlay {
	position: absolute;
	top: 0px;
	left: 0px;
	background-image: none;
	background-size: 100% 100%;
	opacity: 0.3;
	pointer-events: none;
	transform-origin: 0px 0px;
	transition: transform .3s ease-in-out, opacity .2s ease-in-out;
}
</style>
</head>
<body onload="OnLoad()">
<div id="ColorPicker" style="display:none;"></div>
<div id="FileBrowser" style="display:none;"></div>

<div id="main">
	<canvas oncontextmenu="CanvasContext(event);event.stopPropagation();return false;" id="paint" class="FlyUiNoSelect"></canvas>
	<div id="overlay" class="FlyUiNoSelect"></div>
</div>

<script>
Fly.extension.replace('ColorPicker','SprocketComputers.Utilities','ColorPicker');
document.getElementById('ColorPicker').onchange = function() {
	SetColor(this.color.hex);
}

Fly.extension.replace('FileBrowser','SprocketComputers.FileManager','FileChooser');
document.getElementById('FileBrowser').onchange = function() {
}

var ovrlay = document.getElementById('overlay');
var canvas = document.getElementById('paint');
var ctx = canvas.getContext('2d');

var sketch = document.getElementById('main');
var sketch_style = getComputedStyle(sketch);
canvas.width = parseInt(sketch_style.getPropertyValue('width'))-16;
canvas.height = parseInt(sketch_style.getPropertyValue('height'))-16;

var mouse = {x: 0, y: 0};
var bodymouse = {x: 0, y: 0};
var mousedown = false;
 
/* Mouse Capturing Work */
canvas.addEventListener('mousemove', function(e) {
	mouse.x = e.offsetX - this.offsetLeft;
	mouse.y = e.offsetY - this.offsetTop;
}, false);

document.body.addEventListener('mousemove', function(e) {
	bodymouse.x = e.pageX;
	bodymouse.y = e.pageY;
}, false);

/* Drawing on Paint App */
canvas.addEventListener('mousedown', function(e) {
	mousedown = true;
	canvas.oncontextmenu = function() {};
}, false);
 
canvas.addEventListener('mouseup', function() {
	mousedown = false;
	canvas.oncontextmenu = function(event) {CanvasContext(event);event.stopPropagation();return false;};
}, false);

canvas.addEventListener('mouseout', function() {
	if (mousedown) {
		UpdateState();
	}
	mousedown = false;
}, false);
 
</script>

<form id="form" action="save.php" method="POST" target="iframe">
	<input type="hidden" name="filename" id="form-filename">
	<input type="hidden" name="content" id="form-content">
</form>

<iframe id="iframe" src="" name="iframe" style="display:none;"></iframe>
<script>
var iFrame = document.getElementById('iframe');
var Form = document.getElementById('form');
</script>

<div id="SaveDialog" style="display:none;"></div>
<script>
Fly.extension.replace('SaveDialog','SprocketComputers.FileManager','SaveDialog');
document.getElementById('SaveDialog').onchange = function() {Save.checkfile();};
</script>

</body>
</html>