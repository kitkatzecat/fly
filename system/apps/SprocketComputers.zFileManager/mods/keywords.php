<script>
function Remove(keyword) {
	Fly.control.confirm('Delete keyword','Are you sure you want to delete the keyword "'+keyword+'"?','Delete Keyword','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg',function(){
		document.getElementById('save').src = 'dialogs.php?dialog=keyword_remove&keyword='+encodeURIComponent(keyword);
	});
}
window.addEventListener('load',function() {
	Display.Title('Keywords');
	Display.Icon('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>go.svg');
	Display.Status('Ready');
	Display.Path('Keywords');
});
</script>
<?php
include 'Fly.CommonStyle.php';
?>
<style>
body {
	background-color: #fff;
}
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	padding: 4px;
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
<div class="FlyCSTitle FlyCSSectionTitle">Keywords<img class="FlyCSSectionIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>go.svg"></div>
<p>Keywords are a convenient way to get where you're trying to go quickly while using File Manager.</p>
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
		if (!$file) {
			echo '<tr><td class="FlyUiNoSelect">'.$k.'</td><td><div class="FlyUiMenuItem FlyUiNoSelect" onclick="window.top.system.command(\'run:SprocketComputers.zFileManager,p='.urlencode($p).'\')">'.$p.'</div></td></tr>';
		} else {
			echo '<tr><td class="FlyUiNoSelect">'.$k.'</td><td><div class="FlyUiMenuItem FlyUiNoSelect" onclick="window.top.system.command(\'run:SprocketComputers.zFileManager,p='.urlencode($file['file']).'\')"><img class="inline-icon" src="'.$file['icon'].'">'.$file['fname'].'</div></td></tr>';
		}
	}
}
?>
</table>
<br><br>
</div>

<iframe src="" style="display:none;" id="save"></iframe>