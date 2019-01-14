<?php
if (isset($_GET['save'])) {
	include 'constants.php';
	$save = file_put_contents($_GET['save'],$_POST['content']);
	if ($save !== false) {
		echo '<script>window.parent.Save_success();</script>';
		exit;
	} else {
		$rand = rand();
		file_put_contents(FLY_ROOT.'temp-'.$rand.'.txt',$_POST['content']);
		echo '<script>window.top.shell.dialog("File not saved",\'The file "'.htmlentities(basename($_GET['save'])).'" could not be saved. A temporary copy of the file containing any changes made has been saved to "./temp-'.$rand.'.txt".\',"Save Error");</script>';
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
include 'Fly.Command.php';

echo FlyLoadExtension('SprocketComputers.FileManager','FileChooser');
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
		echo '<script>window.top.shell.dialog("File not found",\'The file "'.htmlentities(basename($_GET['file'])).'" could not be opened because the file could not be found.\',"Not Found");window.location.href="index.php?Fly_Id='.FLY_WINDOW_ID.'";</script>';
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

function onload() {
	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.top = '0px';
	Toolbar.style.left = '0px';
	Toolbar.style.right = '0px';
	
	Toolbar.add({text:'File',type:'dropdown',menu:[
		['New',New,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg'}],
		['Open',Open,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg'}],
		['Save',Save,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg'}],
		['Save As',SaveAs],
		[''],
		['Properties',Properties,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'}],
		[''],
		['Close',Close,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]
	]});
	Toolbar.add({text:'Edit',type:'dropdown',menu:[
		['Find',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg'}],
		['Find Next',function(){}]
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
		]]
	]});
	Toolbar.add({type:'divider'});
	Toolbar.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg',title:'Save',action:Save});
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
			Save();
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
		Fly.control.confirm('Close current file','Are you sure you want to close "'+Basename+'"? There are unsaved changes that will be lost.','Memo','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',function(){NewFile(true)},function(){NewFile(false)});
	} else {
		NewFile(true);
	}
}
function Close() {
	if (Changes) {
		Fly.control.confirm('Close current file','Are you sure you want to close "'+Basename+'"? There are unsaved changes that will be lost.','Memo','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',function(){setTimeout(Fly.window.close,10)});
	} else {
		Fly.window.close();
	}
}
function Save() {
	if (IsSaved) {
		document.getElementById('Form').submit();
	} else {
		SaveAs();
	}
}
function Save_success() {
	Changes = false;
	IsSaved = true;
	if (SaveAsName !== false) {
		Basename = SaveAsName;
	}

	Fly.window.title.set('Memo - '+Basename);
}
function SaveAs() {
	document.getElementById('LocationBrowser').browse();
	if (Changes) {
		var star = ' *';
	} else {
		var star = '';
	}
}
function SaveAs_check() {
	var frame = document.getElementById('Frame');
	var browser = document.getElementById('LocationBrowser');
	var name = browser.vars.basename;
	if (name.indexOf('.') == -1) {
		name = FlyFileStringReplace(name)+'.txt';
	} else {
		name = FlyFileStringReplace(name);
	}
	SaveAsName = name;
	FlyCommand('exists:'+browser.vars.bpath+'/'+SaveAsName,function(r) {
		if (r.return == true) {
			SaveAs_exists();
		} else {
			SaveAs_save();
		}
	});
}
function SaveAs_exists() {
	var browser = document.getElementById('LocationBrowser');
	Fly.control.confirm('File already exists','The file "'+SaveAsName+'" already exists in "'+browser.vars.pbasename+'". Do you want to overwrite it?','Memo - File Exists','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',SaveAs_save,function() {setTimeout(function(){document.getElementById('LocationBrowser').browse();},10)});
}
function SaveAs_save() {
	var form = document.getElementById('Form');
	var browser = document.getElementById('LocationBrowser');
	form.action = 'index.php?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&save='+encodeURIComponent(browser.vars.bpath+'/'+SaveAsName);
	form.submit();
}
function Open() {
	document.getElementById('FileBrowser').browse();
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
function CheckFile() {
	var browser = document.getElementById('FileBrowser');

	if (Changes) {
		Fly.control.confirm('Close current file','Are you sure you want to close "'+Basename+'"? There are unsaved changes that will be lost.','Memo','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',function(){OpenFile(true)},function(){OpenFile(false)});
	} else {
		OpenFile(true);
	}
}
function OpenFile(boolean) {
	var browser = document.getElementById('FileBrowser');
	if (boolean) {
		window.location.href = '<?php echo CURRENT_URL; ?>?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&file='+encodeURIComponent(browser.vars.path);
	}
}
function NewFile(boolean) {
	if (boolean) {
		window.location.href = '<?php echo CURRENT_URL; ?>?Fly_Id=<?php echo FLY_WINDOW_ID; ?>';
	}
}
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

<div class="main"><form style="width:100%;height:100%;" id="Form" target="frame" action="index.php?Fly_Id=<?php echo FLY_WINDOW_ID; ?>&save=<?php echo rawurlencode($_GET['file']); ?>" method="post"><textarea name="content" id="TextArea" oninput="if (!Changes) {Fly.window.title.set('Memo - '+Basename+' *');Changes = true;}" style="" spellcheck="false">
<?php echo $content; ?>
</textarea></form>

<iframe id="Frame" name="frame" style="display:none;"></iframe></div>

</body>
</html>