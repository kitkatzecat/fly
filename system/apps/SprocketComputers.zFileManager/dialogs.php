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

<button onclick="window.top.system.command('run:SprocketComputers.zFileManager.ManageKeywords');" id="ButtonHelp"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg" class="button-image"></button>
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
?>
