<?php
if ($_GET['preview'] == 'true') {
	goto preview;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

$fonts = [];
$dir = $_FLY['RESOURCE']['PATH']['FONTS'];
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if (is_dir($dir.$file) && !in_array($file, ['.','..'])) {
				array_push($fonts,$file);
			}
		}
		closedir($dh);
	}
}

$content = '';
foreach ($fonts as $font) {
	if (file_exists($_FLY['RESOURCE']['PATH']['FONTS'].$font.'/font.json')) {
		$json = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['FONTS'].$font.'/font.json'),true);
		$name = $json['name'];
		$author = $json['author'];
		$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/font.svg';
	} else {
		$name = $font;
		$author = 'Unknown';
		$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/unknown.svg';
	}
	$content .= '<div class="FlyUiMenuItem FlyUiNoSelect" id="" ondblclick="window.top.system.command(\'run:'.$_FLY['RESOURCE']['PATH']['FONTS'].$font.'\');" onclick="preview(\''.$font.'\');select(this);"><img class="icon" src="'.$icon.'"><div style="display:inline-block;word-wrap:break-word;margin-top:5px;">'.htmlspecialchars($name).'<br><span style="opacity:0.7;">'.htmlspecialchars($author).'</span></div></div>';
}

?>
<style>
#preview {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	height: 240px;
	padding: 0px;
	overflow: hidden;
	box-sizing: border-box;
}
#main {
	position: absolute;
	top: 244px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	padding: 4px;
	overflow: auto;
	background-color: #fff;
}
.icon {
	width: 48px;
	height: 48px;
	margin-right: 8px;
	vertical-align: top;
}
#frame {
	display: none;
	width: 100%;
	height: 100%;
	border: 0;
	box-sizing: border-box;
}
</style>
<script>
function preview(font) {
	var frame = document.getElementById('frame');
	frame.style.display = 'block';
	frame.src = '?preview=true&font='+encodeURIComponent(font);
}
function select(obj) {
	var selected = document.getElementsByClassName('FlyUiMenuItemActive');
	for (i = 0; i < selected.length; i++) { 
		deselect(selected[i]);
	}
	obj.className = 'FlyUiMenuItemActive FlyUiText FlyUiNoSelect';
}
function deselect(obj) {
	obj.className = 'FlyUiMenuItem FlyUiText FlyUiNoSelect';
}
</script>
</head>
<body>

<div id="preview" class="FlyUiText FlyUiContent FlyUiNoSelect"><iframe id="frame" frameborder="0" src="" scroll="auto"></iframe><div style="padding: 8px;opacity: 0.7;">Pick a font to preview</div></div>
<div id="main" class="FlyUiText"><?php echo $content; ?></div>

</body>
</html>
<?php
exit;

preview:
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Fonts.php';

$font = FlyFontInfo($_GET['font']);
?>
</head>
<body style="padding:4px;">
<?php
if ($font !== false) {
	FlyFontLoad($_GET['font']);
	echo '<span style="font-size: 2em;font-family:\''.$font['name'].'\';">'.$font['name'].'</span><br><div style="margin-left:4px;font-size:1em;font-family:\''.$font['name'].'\';">'.$font['author'].'</div><div style="margin-left:4px;font-size:1em;font-family:\''.$font['name'].'\';">Version '.$font['version'].'</div><div style="margin-left:4px;font-size:1em;font-family:\''.$font['name'].'\';"><a href="'.$font['license'].'" target="_blank">License</a></div><span style="font-size: 3em;font-family:\''.$font['name'].'\';">AaBbCc</span><br>';
	foreach ($font['resources'] as $style => $file) {
		echo '<br><span style="font-size: 0.8em;font-family:\''.$font['name'].'\';'.FlyFontStyleToCss($style).'">'.$style.' ('.$file.')</span><br><div style="margin-left:4px;font-size: 1em;font-family:\''.$font['name'].'\';'.FlyFontStyleToCss($style).'">The quick brown fox jumps over the lazy dog.</div>';
	}
} else {
	echo '<div class="FlyUiText" style="padding: 4px;opacity: 0.7;">No preview available</div>';
}
?>
</body>
</html>