<!DOCTYPE html>
<head>
<?php
include 'fly.php';

if ($_GET['dialog'] == 'bookmark_add') {
	goto bookmark_add;
}
if ($_GET['dialog'] == 'keywords_manage') {
	goto keywords_manage;
}
if ($_GET['dialog'] == 'keywords_add') {
	goto keywords_add;
}
if ($_GET['dialog'] == 'keyword_add') {
	goto keyword_add;
}
if ($_GET['dialog'] == 'keyword_remove') {
	goto keyword_remove;
}
if ($_GET['dialog'] == 'file_get') {
	goto file_get;
}
exit;

bookmark_add:
?>
<script>
function load() {

}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 4px;
	background-color: #fff;
}
</style>
</head>
<body onload="load()">

<div id="main">
Add bookmark
</div>

</body>
</html>
<?php
exit;

favorites:
?>
</head>
<body>



</body>
</html>
<?php
exit;

keywords_manage:
include 'Fly.FileProcessor.php';
?>
<script>
Fly.window.ready = function() {
	Fly.window.resize.enable();
	Fly.window.disableContext();
}
function Remove(keyword) {
	Fly.control.confirm('Delete keyword','Are you sure you want to delete the keyword "'+keyword+'"?','Delete Keyword','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg',function(){
		document.getElementById('save').src = 'dialogs.php?dialog=keyword_remove&keyword='+encodeURIComponent(keyword);
	});
}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	padding: 4px;
	background-color: #fff;
	overflow-y: auto;
}
#okButton {
	min-width: 100px;
	position: absolute;
	bottom: 8px;
	right: 10px;
}
#cancelButton {
	min-width: 100px;
	position: absolute;
	bottom: 8px;
	right: 120px;
}
h1,h2,h3,h4,h5,h6,p {
	padding-left: 6%;
	padding-right: 6%;
}
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 18px;
	padding-bottom: 16px;
	padding-left: 6%;
	padding-right: 6%;
}
p.description {
	margin-top: -12px;
}
p.hint {
	font-size: 0.8em;
	opacity: 0.8;
	margin-top: -16px;
	padding-left: 44px;
}
p.shead {
	font-size: 0.8em;
	opacity: 0.8;
	margin-bottom: -16px;
}
.inline-icon {
	width: 18px;
	height: 18px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
.title-icon {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
img.button-image {
	width: 16px;
	height: 16px;
	vertical-align: middle;
}
table {
	padding-left: 6%;
	padding-right: 6%;
	margin-top: -32px;
	width: 100%;
	table-layout: fixed;
	max-width: 600px;
}
td,tr {
	padding: 0;
}
td {
	min-width: 30%;
	width: 50%;
}
th {
	font-size: 0.8em;
	opacity: 0.8;
	font-weight: normal;
	text-align: left;
}
.FlyUiMenuItem {
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: pre;
}
</style>
</head>
<body class="FlyUiNoSelect">

<div id="main">
<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>go.svg">Keywords</div>
<p class="description">Keywords are a convenient way to get where you're trying to go quickly while using File Manager.</p>
<p>To use a keyword, just type it into the address bar in any File Manager window.</p>

<?php
if (!$_FLY['IS_USER']) {
	goto keywords_nouser;
}
?>

<p class="title"><?php echo $_FLY['USER']['NAME']; ?>&apos;s keywords</p>

<table cellspacing="0">
	<tr><th>Keyword:</th><th>Jumps to:</td></tr>
<?php
if (file_exists($_FLY['APP']['DATA_PATH'].'keywords.json')) {
	$keywords = json_decode(file_get_contents($_FLY['APP']['DATA_PATH'].'keywords.json'),true);
	if ($keywords == "" || count($keywords) < 1) {
		echo '<tr><td colspan="2">You haven\'t created any keywords.</td></tr>';
	} else {
		foreach ($keywords as $k => $p) {
			$file = FlyFileStringProcessor(FlyVarsReplace($p));
			echo '<tr><td><div class="FlyUiMenuItem FlyUiNoSelect" onclick="Remove(this.innerHTML);">'.$k.'</div></td><td><div class="FlyUiMenuItem FlyUiNoSelect" onclick="window.top.system.command(\'run:SprocketComputers.zFileManager,p='.urlencode($file['file']).'\')"><img class="inline-icon" src="'.$file['icon'].'">'.$file['fname'].'</div></td></tr>';
		}
	}
} else {
	file_put_contents($_FLY['APP']['DATA_PATH'].'keywords.json','{}');
	echo '<tr><td colspan="2">You haven\'t created any keywords.</td></tr>';
}
?>
</table>
<p><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg"><a onclick="window.top.system.command('run:<?php echo $_FLY['APP']['ID']; ?>.CreateKeyword');">Create a keyword</a></p>
<p style="margin-top:-10px;"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg"><a onclick="window.location.reload();">Refresh</a></p>
<p class="shead">Click on a keyword to remove it.<br>Click on the location shown in the "Jumps to" column to open that location.<br>Keywords are case sensitive.</p>

<?php
goto keywords_system;

keywords_nouser:
echo '<p style="color:#f00;">User keywords are not available because no user is currently logged in.</p>';
goto keywords_system;

keywords_system:
?>

<p class="title" style="margin-bottom:0;">System keywords</p>
<p class="description" style="margin-bottom:40px;">These keywords are created by Fly and cannot be removed.</p>
<table cellspacing="0">
	<tr><th>Keyword:</th><th>Jumps to:</td></tr>
<?php
if (!file_exists($_FLY['WORKING_PATH'].'keywords.json')) {
	file_put_contents($_FLY['WORKING_PATH'].'keywords.json','{}');
}

$keywords = json_decode(file_get_contents($_FLY['WORKING_PATH'].'keywords.json'),true);
if ($keywords == "" || count($keywords) < 1) {
	echo '<tr><td colspan="2">There aren\'t any system keywords available.</td></tr>';
} else {
	foreach ($keywords as $k => $p) {
		$file = FlyFileStringProcessor(FlyVarsReplace($p));
		echo '<tr><td class="FlyUiNoSelect">'.$k.'</td><td><div class="FlyUiMenuItem FlyUiNoSelect" onclick="window.top.system.command(\'run:SprocketComputers.zFileManager,p='.urlencode($file['file']).'\')"><img class="inline-icon" src="'.$file['icon'].'">'.$file['fname'].'</div></td></tr>';
	}
}
?>
</table>
<br><br>
</div>

<iframe src="" style="display:none;" id="save"></iframe>

</body>
</html>
<?php
exit;

keywords_add:
include 'Fly.Standard.php';
include 'Fly.FileProcessor.php';
include 'Fly.Registry.php';

if (file_exists($_FLY['APP']['DATA_PATH'].'keywords.json')) {
	$file = file_get_contents($_FLY['APP']['DATA_PATH'].'keywords.json');
} else {
	$file = '{}';
}
$keywords = json_decode($file,true);
$file = file_get_contents($_FLY['WORKING_PATH'].'keywords.json');
$keywords = array_merge($keywords,json_decode($file,true));
?>
<style>
body {
	margin: 0px;
	padding: 0px;
}
</style>
</head>
<body class="FlyUiNoSelect">
<script>
	Fly.window.ready = function() {
		var height = (56+Math.max(document.getElementById('Content').scrollHeight,0));

		Fly.window.size.set(500,height);
		Fly.window.position.set(((window.top.window.innerWidth/2)-258),((window.top.window.innerHeight/2)-((height+72)/2)));

		document.getElementById('ButtonOk').disabled = false;
		document.getElementById('ButtonCancel').disabled = false;

		document.getElementById('Keyword').focus();
		document.getElementById('Keyword').select();

		Fly.window.disableContext();
	}

	var Dialog = function() {};
	Dialog.submit = function() {
		document.getElementById('ButtonOk').disabled = true;
		document.getElementById('ButtonCancel').disabled = true;

		var Path = document.getElementById('Path').value;
		var Keyword = document.getElementById('Keyword').value;

		var cont = true;

		if (Keyword == '') {
			document.getElementById('KeywordHint').innerHTML = '<span style="color:#f00;">Please enter a keyword.</span>';
			cont = false;
		} else if (Existing.hasOwnProperty(Keyword)) {
			document.getElementById('KeywordHint').innerHTML = '<span style="color:#f00;">That keyword already exists.</span>';		
			cont = false;	
		}

		if (cont) {
			document.getElementById('Save').src = 'dialogs.php?dialog=keyword_add&path='+encodeURIComponent(Path)+'&keyword='+encodeURIComponent(Keyword);
		} else {
			if (typeof window.top.shell.sound !== "undefined") {
				window.top.shell.sound.system('alert');
			}
			Fly.window.flash();
			document.getElementById('ButtonOk').disabled = false;
			document.getElementById('ButtonCancel').disabled = false;
		}
	}
	Dialog.cancel = function() {
		Fly.window.close();
	}
	if (typeof window.top.shell.sound !== "undefined") {
		window.top.shell.sound.system('question');
	}

	var Existing = JSON.parse(atob('<?php echo base64_encode(json_encode($keywords)); ?>'));
</script>
<style>
h1,h2,h3,h4,h5,h6,p {
	padding-left: 9%;
	padding-right: 9%;
}
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 18px;
	padding-bottom: 16px;
	padding-left: 9%;
	padding-right: 9%;
}
p.description {
	margin-top: -12px;
}
p.hint {
	font-size: 0.8em;
	opacity: 0.8;
	margin-top: -12px;
	padding-left: 44px;
}
p.shead {
	font-size: 0.8em;
	opacity: 0.8;
	margin-bottom: -12px;
}
.inline-icon {
	width: 18px;
	height: 18px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
.title-icon {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
img.button-image {
	width: 16px;
	height: 16px;
	vertical-align: middle;
}
button#ButtonOk {
	width:100px;position:absolute;bottom:9px;right:9px;
}
button#ButtonCancel {
	width:100px;position:absolute;bottom:9px;right:117px;
}
button#ButtonHelp {
	position: absolute;
	width: 28px;
	bottom: 9px;
	left: 9px;
	box-sizing: border-box;
	padding-left: 0;
	padding-right: 0;
}
#Content {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 4px;
	background-color: #fff;
	overflow-y: auto;
}
</style>

<div id="Content">

<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>go.svg">Create a keyword</div>
<p class="description">Choose a keyword and the path for it to jump to.</p>

<p class="shead">Path</p>
<p><input id="Path" type="text" onkeypress="if (event.keyCode == 13) {document.getElementById('Keyword').focus();}" autocomplete="off" style="height:32px;width:360px;" value="<?php echo $_GET['path']; ?>"></p>

<p class="shead">Keyword</p>
<p><input id="Keyword" type="text" onkeypress="if (event.keyCode == 13) {Dialog.submit();}" autocomplete="off" style="height:32px;width:360px;" value="<?php echo $_GET['keyword']; ?>"></p>

<p class="hint" id="KeywordHint" style="margin-bottom:16px;">Keywords are case-sensitive.</p>
</div>

<button onclick="window.top.system.command('run:<?php echo $_FLY['APP']['ID']; ?>,p=?keywords');" id="ButtonHelp"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg" class="button-image"></button>
<button onclick="Dialog.submit();" disabled id="ButtonOk"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" class="button-image"></button>
<button onclick="Dialog.cancel();" disabled id="ButtonCancel"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" class="button-image"></button>

<iframe src="" style="display:none;" id="Save"></iframe>
</body>
</html>
<?php
exit;

keyword_add:

if (file_exists($_FLY['APP']['DATA_PATH'].'keywords.json')) {
	$file = file_get_contents($_FLY['APP']['DATA_PATH'].'keywords.json');
} else {
	$file = '{}';
}

$keywords = json_decode($file,true);
if (!$keywords) {
	$keywords = [];
}

$keywords[$_GET['keyword']] = $_GET['path'];

file_put_contents($_FLY['APP']['DATA_PATH'].'keywords.json',json_encode($keywords));
echo '<script>window.parent.Fly.window.close();</script>';

exit;

keyword_remove:

if (file_exists($_FLY['APP']['DATA_PATH'].'keywords.json')) {
	$file = file_get_contents($_FLY['APP']['DATA_PATH'].'keywords.json');
} else {
	$file = '{}';
}

$keywords = json_decode($file,true);
if (!$keywords) {
	$keywords = [];
}

if (isset($keywords[$_GET['keyword']])) {
	unset($keywords[$_GET['keyword']]);
}

file_put_contents($_FLY['APP']['DATA_PATH'].'keywords.json',json_encode($keywords));
echo '<script>window.parent.location.reload();</script>';

exit;

file_get:
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Actionbar.php';
include 'Fly.Command.php';

if (isset($_GET['p'])) {
	$p = $_GET['p'];
} else {
	$p = '%FLY.PATH%';
}
$p = base64_encode($p);

$views = json_decode(file_get_contents($_FLY['WORKING_PATH'].'view/views.json'),true);
$cv = FlyRegistryGet('View');
$vm = '';
$vl = '{';
$vc = 0;
foreach ($views as $k => $v) {
	if ($v['src'] == $cv) {
		$vm .= '[\''.$k.'\',function(){View.set(\''.$v['src'].'\');},{icon:\''.FlyVarsReplace($v['icon']).'\',toggled:true}],';
	} else {
		$vm .= '[\''.$k.'\',function(){View.set(\''.$v['src'].'\');},{icon:\''.FlyVarsReplace($v['icon']).'\'}],';
	}
	$vl .= '\''.$v['src'].'\':'.$vc.',';
	$vc++;
}
$vl .= '}';
$vl = str_lreplace(',','',$vl);

$vm = str_lreplace(',','',$vm);

?>
<script>
var Menubar;
var Navbar;
var Panebar;
var Addressbar;
var Panes = {};
Fly.window.ready = function() {
	ToolbarInit();
	Fly.window.disableContext();
	Dialog.ready();
}

function ToolbarInit() {
	Navbar = new Fly.actionbar();
	Navbar.style.position = 'absolute';
	Navbar.style.top = '0px';
	Navbar.style.right = '0px';
	Navbar.style.left = '0px';
	Navbar.style.width = 'auto';
	Navbar.style.transition = 'right 0.2s ease-in-out';
	
	Addressbar = document.createElement("input");
	Addressbar.type = 'text';
	Addressbar.className = 'addressbar transparent-white FlyUiTextHighlight';
	Addressbar.onkeydown = function(event) {
		if (event.keyCode == 13) {
			Go();
		}
	}
	Addressbar.onfocus = function() {
		Addressbar.className = 'addressbar-focus';
		setTimeout(function(){Addressbar.select()},100);
	}
	Addressbar.onblur = function() {
		Addressbar.className = 'addressbar transparent-white FlyUiTextHighlight';
		if (window.getSelection) {window.getSelection().removeAllRanges();}
		else if (document.selection) {document.selection.empty();}
	}
	
	Navbar.add({text:'',title:'Back',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-left.svg',action:function(){}});
	Navbar.add({text:'',title:'Up',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-up.svg',action:Up});
	var ab = Navbar.add({type:'custom',content:Addressbar});
	ab.style.width = 'calc(100% - 164px)';
	Navbar.add({text:'',title:'Refresh',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg',action:Refresh,align:'right'});
	Navbar.add({text:'Go',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>go.svg',action:Go,align:'right'});
	
	document.body.appendChild(Navbar);
}
function Close() {
	Fly.window.close();
}
function Go() {
	Nav(Addressbar.value);
	Addressbar.blur();
}

Fly.window.title.setDirect = Fly.window.title.set;
Fly.window.title.set = function(title) {
	Fly.window.title.setDirect(Fly.window.name.get() + ' - '+title);
}
Fly.window.icon.set = function() {};

function Nav(path) {
	window.top.shell.sound.system('click');
	Addressbar.value = '';
	document.getElementById('frame-main').style.display = 'none';
	Fly.command('fileprocess:'+path,function(pth){
		if (pth['return'].hasOwnProperty('ffile')) {
			Addressbar.value = pth['return']['ffile'];
			Fly.window.title.set(pth['return']['fname']);
		} else {
			Addressbar.value = path;
			Fly.window.title.set('Not Found');
		}
		Nav.current = pth['return'];
	})
	document.getElementById('frame-main').src = 'list.php?v=file.sm.js&p='+encodeURIComponent(path);
}
Nav.current = false;

function Up() {
	Nav(Nav.current['fpath']);
}
function Refresh(pos=false) {
	if (!!pos) {
		var frame = document.getElementById('frame-main');
		var a = function() {
			frame.contentWindow.scrollTo(0,pos);
			frame.removeEventListener('load',a);
		}
		frame.addEventListener('load',a);
	}
	Nav(Nav.current['ffile']);
}

var CurrentLocation = {
	basename: 'system',
	icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg',
	path: '<?php echo $_FLY['PATH']; ?>system',
	url: '<?php echo $_FLY['URL']; ?>system',
	fpath: './system',
};
var SelectedFile = CurrentLocation;
function SelectedFileOn() {
	if (SelectedFile['type'] !== 'folder') {
		document.getElementById('filename').innerHTML = SelectedFile['fname'];
		document.getElementById('fileicon').src = SelectedFile['icon'];
		ChosenFile = SelectedFile;
		document.getElementById('button-ok').disabled = false;
		document.getElementById('button-ok').onclick = function() {
			Dialog.select(SelectedFile);
		}
	}
}

var ChosenFile = false;

function FrameLoad() {
	var frame = document.getElementById('frame-main');

	frame.style.display = 'block';
}
</script>

<script>
var Dialog = {
	opener: {},
	options: {},
	callback: function() {},
	ready: function() {
		if (Dialog.options.hasOwnProperty('path')) {
			Nav(Dialog.options.path);		
		} else {
			Nav('%FLY.USER.PATH%');
		}
		Fly.window.onclose = function() {
			Dialog.callback(false);
			Fly.window.close();
		}
	},
	select: function(file) {
		Dialog.selected = true;

		Dialog.opener.Fly.window.focus.self();
		Dialog.opener.Fly.window.bringToFront();
		Fly.window.close();

		Dialog.callback(file);
	},
	selected: false
};
</script>

<style>
#main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	background: #fff;
	z-index: 2;
	cursor: wait;
}
.addressbar-focus {
	margin-left: 4px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	cursor: text;
	font-size: 14px;
	text-align: center;
	width: 100%;
	background-color: #fff !important;
	text-shadow: none !important;
	color: #000 !important;
	border-color: #000 !important;
}
.addressbar {
	margin-left: 4px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	background-color: transparent;
	cursor: text;
	font-size: 14px;
	text-align: center;
	width: 100%;
}
.addressbar:disabled {
	color: #808080 !important;
}
.transparent-white {
	border: 1px solid rgba(255,255,255,0.3) !important;
}
.transparent-white:hover {
	background-color: rgba(255,255,255,0.2);
}
.white {
	border: 1px solid rgb(255,255,255) !important;
}
.white:hover {
	background-color: rgba(255,255,255,0.2);
}
.black {
	border: 1px solid rgb(0,0,0) !important;
}
.black:hover {
	background-color: rgba(255,255,255,0.2);
}
#frame-main {
	width: 100%;
	height: 100%;
}
#filebar {
	position: absolute;
	left: 9px;
	bottom: 9px;
	right: 119px;
	border: 1px solid rgba(255,255,255,0.3) !important;
	border-radius: 4px;
	height: 28px;
	padding: 5px;
	box-sizing: border-box;
	font-size: 14px;
}
#filebar:hover {
	background-color: rgba(255,255,255,0.2);
}
#fileicon {
	width: 16px;
	height: 16px;
	margin-right: 4px;
	margin-bottom: -3px;
	margin-left: 2px;
}

img.button-image {
	width: 16px;
	height: 16px;
	vertical-align: middle;
}
button#button-ok {
	width:100px;position:absolute;bottom:9px;right:9px;
}
</style>
</head>
<body>
<div id="main">
<iframe id="frame-main" onload="FrameLoad();" frameborder="0" allowtransparency="true" scrolling="auto" src=""></iframe>
</div>

<div id="filebar" class="FlyUiTextHighlight"><img id="fileicon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg"><span id="filename">No file selected</span></div>
<button disabled id="button-ok"><img class="button-image" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg"></button>

</body>
</html>
<?php
exit;
?>
