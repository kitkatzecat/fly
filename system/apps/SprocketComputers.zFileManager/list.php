<!DOCTYPE html>
<html>
<head>
<?php

/* Query string:
	p : Path - The path to the directory to display the contents of
	v : View (Optional) - Override the registry setting to use a specific view
*/

include 'Fly.Standard.php';
include 'Fly.Actionmenu.php';
include 'Fly.Command.php';
include 'Fly.FileProcessor.php';
include 'Fly.Registry.php';

if (!empty($_GET['v']) && file_exists('view/'.$_GET['v'])) {
	$View = $_GET['v'];
} else {
	$View = FlyRegistryGet('View');
}

$Output = '';

$Path = $_GET['p'];

$Keywords = json_decode(file_get_contents($_FLY['WORKING_PATH'].'keywords.json'),true);
if ($_FLY['IS_USER'] && file_exists($_FLY['APP']['DATA_PATH'].'keywords.json')) {
	$Keywords = array_merge($Keywords,json_decode(file_get_contents($_FLY['APP']['DATA_PATH'].'keywords.json'),true));
}

if (array_key_exists($_GET['p'],$Keywords)) {
	echo '<script>window.parent.Nav(\''.$Keywords[$_GET['p']].'\');</script>';
	exit;
}

if ($Path == '.' || $Path == '..') {
	$Path = '%FLY.PATH%';
}
if (substr($Path,0,1) == '?') {
	$Path = substr_replace($Path,'',0,1);
	if (file_exists('mods/'.strtolower($Path).'.php')) {
		include 'mods/'.strtolower($Path).'.php';
	} else {
		$Output = '<script>window.parent.Fly.window.title.set(\'Not Found\');</script><div class="title"><img class="title-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg">The specified module could not be found.</div><p class="description">Try checking the spelling of the path.</p><p>'.trimslashes(str_freplace($_FLY['PATH'],'./',$Path)).'</p><p>Or, try going <a><img class="inline-icon" style="margin-right:4px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'home.svg">Home</a>';
		echo '
		<script>
		var Folder = false;
		var List = false;
		var Files = false;
		</script>
		';
	}
} else {
	include 'mods/folder.php';
}

echo '
<script>
var Output = atob(\''.base64_encode($Output).'\');
</script>
';

?>
<script>

if (typeof onLoad == 'undefined') {
	onLoad = [];
}
function Load() {
	window.ImagePreviews = (eval('<?php echo FlyRegistryGet('ShowImagePreviews'); ?>')) ? true : false;

	if (window.ImagePreviews) {
		try {
			window.parent.ImagePreviews.toggleOn();
		} catch(e) {console.log(e);}
	} else {
		try {
			window.parent.ImagePreviews.toggleOff();
		} catch(e) {console.log(e);}
	}
	try {
		window.parent.ImagePreviews.visible = window.ImagePreviews;
	} catch(e) {console.log(e);}


	window.SystemFiles = (eval('<?php echo FlyRegistryGet('ShowSystemFiles'); ?>')) ? true : false;

	if (window.SystemFiles) {
		try {
			window.parent.SystemFiles.toggleOn();
		} catch(e) {console.log(e);}
	} else {
		try {
			window.parent.SystemFiles.toggleOff();
		} catch(e) {console.log(e);}
	}
	try {
		window.parent.SystemFiles.visible = window.SystemFiles;
	} catch(e) {console.log(e);}

	CheckInterval = setInterval(Check,<?php echo FlyRegistryGet('RefreshInterval'); ?>);

	document.addEventListener('mousedown',function() {Deselect();});

	try {
		window.parent.SelectedFile = Folder;
		window.parent.OnSelectionChange();
	} catch(e) {
		console.log(e);
	}

	onLoad.forEach(function(f) {
		try {
			f();
		} catch(e) {
			console.log(e);
		}
	});
}

var Display = {
	Icon: function(src) {
		try {
			window.parent.Fly.window.icon.set(src);
		} catch(e) {
			console.log(e);
		}
	},
	Title: function(title) {
		try {
			window.parent.Fly.window.title.set(title);
		} catch(e) {
			console.log(e);
		}
	},
	Path: function(path) {
		try {
			window.parent.Addressbar.value = path;
		} catch(e) {
			console.log(e);
		}
	},
	Status: function(status) {
		try {
			window.parent.document.getElementById('statusbar').innerHTML = status;
		} catch(e) {
			console.log(e);
		}
	}
}

function Deselect(item=false) {
	if (!item) {
		var selected = document.getElementsByClassName('FlyUiMenuItemActive');
		for (i = 0; i < selected.length; i++) { 
			selected[i].className = selected[i].className.replace('FlyUiMenuItemActive','FlyUiMenuItem');
		}
		Selected.className = Selected.className.replace('FlyUiMenuItemActive','FlyUiMenuItem');
		Selected = false;
		try {
			window.parent.SelectedFile = Folder;
			window.parent.OnSelectionChange();
		} catch(e) {
			console.log(e);
		}
		try {
			View.ondeselect();
		} catch(e) {console.log(e);}
	} else {
		item.className = item.className.replace('FlyUiMenuItemActive','FlyUiMenuItem');
	}
}
function Select(item,e,obj) {
	if (Selected) {
		Deselect(Selected);
	}
	if (item.className.indexOf('FlyUiMenuItemActive') == -1) {
		item.className = item.className.replace('FlyUiMenuItem','FlyUiMenuItemActive');
	}
	Selected = item;
	try {
		window.parent.SelectedFile = obj;
		window.parent.OnSelectionChange();
	} catch(e) {console.log(e);}
	e.stopPropagation();
}
var Selected = false;

var SelectedFile = {};

function Click(obj) {
	if (obj['isdir']) {
		window.parent.Nav(obj['file']);
	} else if (obj['extension'] == 'als') {
		window.parent.Nav(obj['file']);
	} else {
		window.top.system.command('run:'+obj['file']);
	}
}

function ContextMenu(obj,e,ret=false) {
	var menu = [];

	var protected = <?php echo json_encode(json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['COMPONENTS'].'protected.json'),true)); ?>;

	// Opening/running
	if (obj['file'] != Folder['file']) {
		if (!obj['action'] || obj['action']=='') {
			menu.push([
				'<b>Open with...</b>',
				function() {Click(obj);},
				{
					icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>run.svg'
				}
			],['']);
		} else {
			if (obj['isdir']) {
				menu.push([
					'<b>Open</b>',
					function() {Click(obj);},
					{
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>run.svg'
					}
				],['']);
			} else {
				menu.push([
					'<b>Open</b>',
					function() {Click(obj);},
					{
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>run.svg'
					}
				],[
					'Open with...',
					function() {window.top.system.command('run:SprocketComputers.Utilities.OpenWith,file='+encodeURIComponent(obj['file']));}
				],['']);
			}
		}
	}

	if (obj['file'] == Folder['file']) {
		menu.push([
				'Refresh',
				function() {Refresh();},
				{
					icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg'
				}
			],['']);
	}

	// File/dir operations
	var fileoperations = false;
	if (protected.indexOf(obj['ffile']) != -1) {
		fileoperations = true;
	}
	menu.push([
		'Delete',
		function() {window.top.system.command('run:SprocketComputers.FileManager.Delete,file='+encodeURIComponent(obj['file']));},
		{
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg',
			disabled: fileoperations
		}
	],[
		'Rename',
		function() {window.top.system.command('run:SprocketComputers.FileManager.Rename,file='+encodeURIComponent(obj['file']));},
		{
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pencil.svg',
			disabled: fileoperations
		}
	]);

	// More
	var more = [];
	
	more.push([
		'Pin to Jump',
		function() {window.top.system.command('run:SprocketComputers.Utilities.PinJump,file='+encodeURIComponent(obj['file']));},
		{
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg'
		}
	],[
		'Create alias...',
		function() {},
		{
			icon: '<?php echo $_FLY['WORKING_URL']; ?>alias.svg',
			disabled: true
		}
	]);

	if (obj['isdir']) {
		more.push([
			'Create keyword...',
			function() {window.top.system.command('run:SprocketComputers.zFileManager.CreateKeyword,keyword='+encodeURIComponent(obj['fname'])+',path='+encodeURIComponent(obj['ffile']));},
			{
				icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>go.svg'
			}
		],[''],[
			'Open in new window',
			function() {window.top.system.command('run:'+obj['file']);},
			{
				icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>popout.svg'
			}
		]);
	}

	more.push([''],[
		'Open with Memo',
		function() {window.top.system.command('run:SprocketComputers.Memo,file='+encodeURIComponent(obj['file']));},
		{
			icon: '<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.Memo/memo.svg'
		}
	]); // Temporary - until actual Open With dialog is fully functional

	if (obj['mime'] == 'image/jpeg') {
		more.push(
			[''],
			[
				'Rotate Clockwise',
				function() {
					Fly.dialog.message({
						title: 'Rotate Image',
						message: 'Rotate Image Clockwise',
						content: '',
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>image-rotate-right.svg'
					})
				},
				{
					icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>image-rotate-right.svg'
				}
			],
			[
				'Rotate Counterclockwise',
				function() {
					Fly.dialog.message({
						title: 'Rotate Image',
						message: 'Rotate Image Counterclockwise',
						content: '',
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>image-rotate-left.svg'
					})
				},
				{
					icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>image-rotate-left.svg'
				}
			]
		);
	}

	if (more.length > 0) {
		menu.push([
			'More',
			more
		]);
	}

	// Properties
	menu.push([''],[
		'Properties',
		function() {window.top.system.command('run:SprocketComputers.FileManager.Properties,file='+encodeURIComponent(obj['file']));},
		{
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'
		}
	]);
	
	if (ret) {
		return menu;
	} else {
		Fly.actionmenu(e,menu);
	}
}

function Icon(file) {
	var icon = document.createElement('div');
	icon.className = 'FlyUiNoSelect';
	icon.style.display = 'inline-block';
	icon.style.position = 'relative';

	if (file.hasOwnProperty('mime') && file['mime'].indexOf('image/') !== -1 && window.ImagePreviews) {
		icon.style.boxShadow = '0px 1px 4px #888';
		icon.style.backgroundSize = 'contain';
		icon.style.backgroundRepeat = 'no-repeat';
		icon.style.backgroundPosition = 'center center';
		icon.style.backgroundImage = 'url(\''+file['URL']+'\')';
	} else if (file['extension'] == 'als') {
		icon.innerHTML = '<img style="position:absolute;top:0;left:0;width:100%;height:100%;" src="'+file['icon']+'"><img style="position:absolute;bottom:0;left:0;width:35%;height:35%;min-height:12px;min-width:12px;" src="<?php echo $_FLY['WORKING_URL']; ?>alias.svg">';
	} else {
		icon.innerHTML = '<img style="width:100%;height:100%;" src="'+file['icon']+'">';
	}
	return icon;
}

var CheckInterval;
function Check() {
	Fly.command('list:'+Folder['file'],function(a){
		if (JSON.stringify(a.return) != JSON.stringify(Files) && !!a.return) {
			Refresh(window.pageYOffset);
		}
	},{silent:true});
}

function Refresh(a) {
	window.parent.Refresh(a);
}
</script>
<script src="view/<?php echo $View; ?>?r=<?php echo rand(); ?>"></script>
<style>
html,body {
	min-height: 100%
}
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 28px;
	padding-bottom: 16px;
	padding-left: 6%;
	padding-right: 6%;
}
p.description {
	margin-top: -12px;
}
.title-icon {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
.inline-icon {
	width: 18px;
	height: 18px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
p {
	padding-left: 6%;
	padding-right: 6%;
}
</style>
</head>
<body class="FlyUiText FlyUiNoSelect" onload="Load()">

</body>
</html>