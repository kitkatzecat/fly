<?php
if (isset($_GET['save'])) {
	include 'Fly.Core.php';
	$save = file_put_contents(FlyVarsReplace($_GET['save']),$_POST['content']);
	if ($save !== false) {
		echo '<script>window.parent.Save.success();</script>';
		exit;
	} else {
		$rand = rand();
		file_put_contents($_FLY['USER']['PATH'].'Documents/temp-'.$rand.'.txt',$_POST['content']);
		echo '<script>window.parent.Fly.dialog.message({message:"File not saved",content:\'The file "'.htmlentities(basename($_GET['save'])).'" could not be saved. A temporary copy of the file containing any changes made has been saved to your Documents folder as "temp-'.$rand.'.txt".\',title:"Save Error",icon:"'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg");</script>';
		exit;
	}
	
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Registry.php';
include 'Fly.FileProcessor.php';
include 'Fly.File.php';
include 'Fly.Command.php';

echo FlyLoadExtension('SprocketComputers.FileManager','SaveDialog');
echo FlyLoadExtension('SprocketComputers.Utilities','ColorPicker');

echo $FlyFileStringFunction;

if ($_GET['file'] == '') {
	$title = 'Untitled';
	$content = '';
	$issaved = 'false';
} else {
	$_GET['file'] = FlyVarsReplace($_GET['file']);
	if (file_exists($_GET['file'])) {
		$process = FlyFileStringProcessor($_GET['file']);
		if ($process) {
			$title = htmlentities(basename($_GET['file']));
			$content = htmlentities(file_get_contents($_GET['file']));
			$issaved = 'true';
		} else {
			echo '<script>window.top.shell.dialog("File processing error",\'The file "'.htmlentities(basename($_GET['file'])).'" could not be opened because the file string could not be processed.\',"Process Error");window.location.href="index.php?Fly_Id='.FLY_WINDOW_ID.'";</script>';
			exit;
		}
	} else {
		echo '<script>Fly.control.modal("File not found",\'The file "'.htmlentities(basename($_GET['file'])).'" could not be opened because the file could not be found.\',"Not Found","'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg",function() {window.location.href="'.$_FLY['WORKING_URL'].'index.php?Fly_Id='.FLY_WINDOW_ID.'";});</script>';
		exit;
	}
}
if (in_array(FlyRegistryGet('WordWrap'),['yes','on','true'])) {
	$wordwrap = 'WordWrap();';
} else {
	$wordwrap = '';
}
?>
<script>
var File = '<?php echo $_GET['file']; ?>';
var Basename = '<?php echo $title; ?>';
var Changes = false;
var IsSaved = <?php echo $issaved; ?>;
var SaveAsName = false;
var Toolbar;
var ViewMenu;
var FileMenu;
var EditMenu;

function onload() {
	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.top = '0px';
	Toolbar.style.left = '0px';
	Toolbar.style.right = '0px';
	
	FileMenu = Toolbar.add({text:'File',type:'dropdown',menu:[
		['New',New,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg'}],
		['Open',Open,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg'}],
		['Save',Save.save,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg'}],
		['Save As',Save.opendialog],
		[''],
		['Properties',Properties,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'}],
		[''],
		['Close',Close,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]
	]});
	EditMenu = Toolbar.add({text:'Edit',type:'dropdown',menu:[
		['Select All', function() {
			document.getElementById('TextArea').select();
		}, {}],
		[''],
		['Find',Find.dialog,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg'}],
		['Find Next',Find.next,{disabled:true}]
	]});
	ViewMenu = Toolbar.add({text:'View',type:'dropdown',menu:[
		['Word Wrap',WordWrap,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/text.svg'}],
		['Font',[
			['<span style="font-family:monospace;">Monospace</span>',function(){FontSet('monospace')}],
			['<span style="font-family:sans-serif;">Sans Serif</span>',function(){FontSet('sans-serif')}],
			['<span style="font-family:serif;">Serif</span>',function(){FontSet('serif')}],
			['<span style="font-family:cursive;">Cursive</span>',function(){FontSet('cursive')}],
			['<span style="font-family:fantasy;">Fantasy</span>',function(){FontSet('fantasy')}],
			[''],
			['Color...',FontColor]
		],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>text.svg'}]
	]});
	Toolbar.add({type:'divider'});
	Toolbar.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg',title:'Save',action:Save.save});
	Toolbar.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg',title:'Open',action:Open});
	
	document.body.appendChild(Toolbar);
	
	Fly.window.title.set('Memo - <?php echo $title; ?>');
	<?php echo $wordwrap; ?>
	
	ShortcutInit();
	
	Fly.window.onclose = Close;
}
function ShortcutInit() {
	document.addEventListener("keydown", function(e) {
		
		//Save (ctrl+s)
		if (e.keyCode == 83 && e.ctrlKey) {
			Save.save();
			e.preventDefault();
		}
		
		//New (ctrl+n)
		if (e.keyCode == 78 && e.ctrlKey) {
			New();
			e.preventDefault();
		}
		
		//Open (ctrl+o)
		if (e.keyCode == 79 && e.ctrlKey) {
			Open();
			e.preventDefault();
		}
		
		//Close (ctrl+q)
		if (e.keyCode == 81 && e.ctrlKey) {
			Close();
			e.preventDefault();
		}
		
		//Word Wrap (ctrl+w)
		if (e.keyCode == 87 && e.ctrlKey) {
			WordWrap();
			e.preventDefault();
		}
		
		//Font (ctrl+f)
		if (e.keyCode == 70 && e.ctrlKey) {
			Font();
			e.preventDefault();
		}
	}, false);
}
function New() {
	if (Changes) {
		Fly.dialog.custom({
			title: 'Save Changes',
			message: 'Save changes?',
			content: `Do you want to save changes to the file "${Basename}" before creating a new file?`,
			sound: 'question',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg",
					default: true,
					onclick: function() {
						Save.save();
					},
				},{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg",
					onclick: function() {
						NewFile(true);
					},
				},{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
					onclick: function() {},
				}
			]
		});
	} else {
		NewFile(true);
	}
}
function Close() {
	if (Changes) {
		Fly.dialog.custom({
			title: 'Save Changes',
			message: 'Save changes?',
			content: `Do you want to save changes to the file "${Basename}"?`,
			sound: 'question',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg",
					default: true,
					onclick: function() {
						Save.save();
					},
				},{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg",
					onclick: function() {
						Fly.window.close();
					},
				},{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
					onclick: function() {},
				}
			]
		});
	} else {
		Fly.window.close();
	}
}

var Save = {
	save: function() {
		if (IsSaved) {
			document.getElementById('Form').submit();
		} else {
			Save.opendialog();
		}
	},
	success: function() {
		Changes = false;
		IsSaved = true;
		if (SaveAsName !== false) {
			Basename = SaveAsName;
		}

		Fly.window.title.set('Memo - '+Basename);
		Fly.window.message('Saved "'+Basename+'"');
	},
	opendialog: function() {
		Fly.file.set(Save.checkfile,{name:Fly.file.string.bname(Basename),extensions:['txt','htm','md','xml','json','js','php']})
	},
	checkfile: function(r) {
		if (r) {
			Save.checkexists(r);
		}
	},
	checkexists: function(r) {
		Fly.command('exists:'+r.file,function(a){
			if (a.return == true) {
				Save.confirmoverwrite(r);
			} else {
				Save.confirmwrite(r);
			}
		});
	},
	confirmoverwrite: function(r) {
		Fly.dialog.confirm({
			title: 'File Exists',
			message: 'Overwrite file?',
			content: `The file "${r.name}" already exists. Do you want to overwrite it?`,
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',
			callback: function(a) {
				if (a) {
					Save.confirmwrite(r);
				} else {
					Save.opendialog();
				}
			}
		});
	},
	confirmwrite: function(r) {
		File = r.file;
		FileName = r.name;
		Save.writefile(r);
	},
	writefile: function(r) {
		var form = document.getElementById('Form');
		form.action = 'index.php?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&save='+encodeURIComponent(r.file);
		form.submit();
		
		SaveAsName = r.name;
	}
}

function Open() {
	Fly.file.get(function(a) {
		if (a) {
			CheckFile(a);
		}
	});
}
function Font() {
	var font = document.getElementById('FlyToolbarItem-font');
	font.toggleOn();
	
	var coverDiv = document.createElement("div");
	coverDiv.style.position = 'fixed';
	coverDiv.style.top = '0px';
	coverDiv.style.bottom = '0px';
	coverDiv.style.left = '0px';
	coverDiv.style.right = '0px';
	coverDiv.style.backgroundColor = 'transparent';
	coverDiv.style.zIndex = '4';
	coverDiv.id = 'CoverDiv';
	coverDiv.onmouseover = function() {document.getElementById('FlyToolbarItem-font').toggleOff();document.getElementById('CoverDiv').parentNode.removeChild(document.getElementById('CoverDiv'));};
	document.body.appendChild(coverDiv);

	Fly.control.contextMenu([font.offsetLeft,(parseInt(font.offsetTop)+parseInt(font.offsetHeight))],['<span style="font-family:monospace;">Monospace</span>','<span style="font-family:sans-serif;">Sans Serif</span>','<span style="font-family:serif;">Serif</span>','<span style="font-family:cursive;">Cursive</span>','<span style="font-family:fantasy;">Fantasy</span>'],['FontSet(\'monospace\');document.getElementById(\'FlyToolbarItem-font\').toggleOff();','FontSet(\'sans-serif\');document.getElementById(\'FlyToolbarItem-font\').toggleOff();','FontSet(\'serif\');document.getElementById(\'FlyToolbarItem-font\').toggleOff();','FontSet(\'cursive\');document.getElementById(\'FlyToolbarItem-font\').toggleOff();','FontSet(\'fantasy\');document.getElementById(\'FlyToolbarItem-font\').toggleOff();']);
}
function FontSet(font) {
	var textarea = document.getElementById('TextArea');
	textarea.style.fontFamily = font+',monospace';
	FlyCommand('registry:set,FontFamily,'+font,function(a) {
		if (!a.return) {
			Fly.window.message.show('An error occurred while saving your options to the registry');
		}
	});
}
function FontColor() {
	document.getElementById('ColorPicker').choose();
}
function FontColor_set() {
	var picker = document.getElementById('ColorPicker');
	var textarea = document.getElementById('TextArea');
	
	FlyCommand('registry:set,FontColor,'+picker.color.hex.replace('#',''),function(a) {
		if (!a.return) {
			Fly.window.message.show('An error occurred while saving your options to the registry');
		}
	});
	textarea.style.color = picker.color.hex;
}
function Properties() {
	window.top.system.command('run:SprocketComputers.FileManager.Properties,file='+File);
}
var WordWrapEnabled = false;
function WordWrap() {
	var textarea = document.getElementById('TextArea');
	var wordwrap = ViewMenu.menu.options[0];
	if (WordWrapEnabled) {
		textarea.style.whiteSpace = 'nowrap';
		wordwrap.toggleOff();
		FlyCommand('registry:set,WordWrap,false',function(a) {
			if (!a.return) {
				Fly.window.message.show('An error occurred while saving your options to the registry');
			}
		});
		WordWrapEnabled = false;
	} else {
		textarea.style.whiteSpace = 'normal';
		wordwrap.toggleOn();
		FlyCommand('registry:set,WordWrap,true',function(a) {
			if (!a.return) {
				Fly.window.message.show('An error occurred while saving your options to the registry');
			}
		});
		WordWrapEnabled = true;
	}
}
function CheckFile(file) {
	if (Changes) {
		Fly.dialog.custom({
			title: 'Save Changes',
			message: 'Save changes?',
			content: `Do you want to save changes to the file "${Basename}" before opening "${file['name']}"?`,
			sound: 'question',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg",
					default: true,
					onclick: function() {
						Save.save();
					}
				},{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg",
					onclick: function() {
						OpenFile(file);
					}
				},{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
					onclick: function() {
						OpenFile(false);
					}
				}
			]
		});
	} else {
		OpenFile(file);
	}
}
function OpenFile(file) {
	if (file) {
		window.location.href = '<?php echo CURRENT_URL; ?>?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&file='+encodeURIComponent(file['file']);
	}
}
function NewFile(boolean) {
	if (boolean) {
		window.location.href = '<?php echo CURRENT_URL; ?>?Fly_Id=<?php echo FLY_WINDOW_ID; ?>';
	}
}

var Find = {
	dialog: function() {
		Fly.dialog.input({
			title: 'Find',
			message: 'Find',
			content: 'Enter the text to find:',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg',
			callback: function(i) {
				if (!!i) {
					EditMenu.menu.options[3].enable();
					Find.finding = i;
					Find.next();
				}
			}
		})
	},
	next: function() {
		if (!Find.finding) {
			Find.dialog();
		} else {
			var text = document.getElementById('TextArea');
			if (text.innerHTML.indexOf(Find.finding) == -1) {
				Fly.window.message('No matches found');
			} else {
				var pos = text.selectionEnd;
				var ind = text.innerHTML.indexOf(Find.finding,pos);
				if (ind == -1) {
					Fly.window.message('No more matches found');
				} else {
					text.focus();
					text.setSelectionRange(ind,ind+Find.finding.length);
				}
			}
		}
	},
	finding: false
};
</script>
<style>
.main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	background-color: #ffffff;
}
#TextArea {
	font-family: <?php echo FlyRegistryGet('FontFamily'); ?>,monospace;
	color: #<?php echo FlyRegistryGet('FontColor'); ?>;
	font-size: 14px;
	width: 100%;
	height: 100%;
	box-sizing: border-box;
	border: none;
	white-space: nowrap;
	overflow: auto;
	padding: 4px;
}
</style>
</head>
<body onload="onload()">
<div id="FileBrowser" style="display:none;"></div>
<script>
Fly.extension.replace('FileBrowser','SprocketComputers.FileManager','FileChooser');
document.getElementById('FileBrowser').onchange = CheckFile;
</script>

<div id="LocationBrowser" style="display:none;"></div>
<script>
Fly.extension.replace('LocationBrowser','SprocketComputers.FileManager','SaveDialog');
document.getElementById('LocationBrowser').onchange = SaveAs_check;
</script>

<div id="ColorPicker" style="display:none;"></div>
<script>
Fly.extension.replace('ColorPicker','SprocketComputers.Utilities','ColorPicker');
document.getElementById('ColorPicker').onchange = FontColor_set;
</script>

<div class="main"><form style="width:100%;height:100%;" id="Form" target="frame" action="index.php?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&save=<?php echo rawurlencode($_GET['file']); ?>" method="post"><textarea name="content" id="TextArea" oninput="if (!Changes) {Fly.window.title.set('Memo - '+Basename+' *');Changes = true;}" spellcheck="false">
<?php echo $content; ?>
</textarea></form>

<?php 
if ($content == '' && (filesize($_GET['file']) > 0) && $_GET['file'] != '') {
	echo '
	<script>
	Fly.dialog.message({
		title:"Unsupported Type",
		message:"Unsupported file type",
		content:\'"\'+Basename+\'" may be of a file type that is not supported by Memo, so the file\\\'s contents may not display correctly.\',
		icon:"'.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg"
	});
	</script>
	';
}
?>

<iframe id="Frame" name="frame" style="display:none;"></iframe></div>

</body>
</html>