<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Registry.php';
include 'Fly.Dialog.php';

if (isset($_GET['show'])) {
	goto show;
}
?>
<style>
#main {
	position: absolute;
	top: 0px;
	left: min(220px,25%);
	right: 0px;
	bottom: 0px;
	background-color: #fff;
	overflow: hidden;
	padding: 0px;
}
#index {
	position: absolute;
	top: 0px;
	left: 0px;
	width: 25%;
	max-width: 220px;
	bottom: 0px;
	padding-top: 4px;
	padding-left: 8px;
	padding-right: 8px;
	padding-bottom: 4px;
	box-sizing: border-box;
	overflow: hidden;
}
#frame {
	width: 100%;
	height: 100%;
	box-sizing: border-box;
	border: none;
}
.index-option {
	cursor: pointer;
	font-size: 13px;
	padding-top: 6px;
	padding-bottom: 6px;
	margin-top: -4px;
	margin-bottom: -4px;
	word-wrap: nowrap;
	white-space: pre;
	max-width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
}
.index-icon {
	width: 16px;
	height: 16px;
	vertical-align: middle;
	margin-right: 6px;
}
</style>
<script>
var Toolbar;
var IndexToolbar;
function onload() {
	<?php
	if (file_exists($_GET['page'])) {
		echo 'nav(\''.$_GET['page'].'\')';
	} else {
		echo 'nav(\'library2.php?show=home\')';
	}
	?>
}
function nav(page) {
	var frame = document.getElementById('frame');
	frame.src = page;
}
var frameloadinit = false;
function frameload() {
	var frame = document.getElementById('frame');

	if (frameloadinit) {
		try {
			window.top.shell.sound.system('click');
		} catch(e) {}
	} else {
		frameloadinit = true;
	}
}
</script>
</head>
<body onload="onload()">

<div id="index">
<?php
$index = [
	[
		'name' => 'Home',
		'icon' => '%FLY.RESOURCE.URL.ICONS%home.svg',
		'index' => 'library2.php?show=home'
	]
];

foreach ($index as $item) {
	echo '<div class="FlyUiTextHover index-option" onclick="nav(\''.$item['index'].'\')"><img class="index-icon" src="'.FlyVarsReplace($item['icon']).'">'.htmlspecialchars($item['name']).'</div>';
}
?>
</div>

<div id="main" class="FlyUiText FlyUiNoSelect"><iframe onload="frameload()" src="" frameborder="0" id="frame"></iframe></div>

</body>
</html>

<?php
exit;

show:
include 'Fly.CommonStyle.php';

if ($_GET['show'] == 'home') {
	goto show_home;
}
exit;

show_home:
$firstrun = FlyRegistryGet('LibraryFirstRun');
if (false)/*($firstrun !== 'true')*/ {
?>
<script>
Fly.dialog.message({
	title: 'Library',
	message: 'Welcome to Library',
	content: 'This is the Media Player Library. All music and video files in folders you\'ve added to your library can be found here.',
	icon: '<?php echo $_FLY['WORKING_URL']; ?>library.svg',
	callback: function() {
		Fly.dialog.confirm({
			title: 'Find Media',
			message: 'Find media files',
			content: 'Since this is your first time in the Media Player Library, Media Player needs to add media files. Would you like Media Player to search for media files now?',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg',
			callback: function(r) {
				if (r) {
					
				} else {
					Fly.dialog.message({
						title: 'Find Media',
						message: 'Find media files later',
						content: 'To find media files later, select the "Find Media Files" option from the "Library" menu in the actionbar.',
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg'
					});
				}
			}
		});
	}
});
</script>
<?php
}
?>
<body>

<div class="FlyCSTitle FlyCSSectionTitle">Library<img src="library.svg"class="FlyCSSectionIcon"></div>

</body>
</html>
<?php
exit;
?>