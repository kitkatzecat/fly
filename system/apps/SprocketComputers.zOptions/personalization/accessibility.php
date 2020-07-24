<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Dialog.php';
include 'Fly.CommonStyle.php';

include $_FLY['APP']['PATH'].'save_include.php';
?>
<script>
var OptionsTree = [
	{name:'Personalization',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg',index:'personalization/index.php'},
	{name:'Accessibility',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>accessibility.svg',}
];

function Save() {
	var params = [];
	params.push({
		type: 'registry',
		application: 'SprocketComputers.Options',
		key: 'ShowFocusOutlines',
		value: (document.getElementById('focus').checked ? 'true' : 'false')
	});
	params.push({
		type: 'registry',
		application: 'SprocketComputers.Options',
		key: 'HighContrastTheme',
		value: (document.getElementById('theme').checked ? 'true' : 'false')
	});
	params.push({
		type: 'registry',
		application: 'SprocketComputers.Options',
		key: 'LargeText',
		value: (document.getElementById('text').checked ? 'true' : 'false')
	});
	params.push({
		type: 'registry',
		application: 'SprocketComputers.Options',
		key: 'MagnifyWindows',
		value: (document.getElementById('windows').checked ? 'true' : 'false')
	});
	OptionsSave(params,function(r) {
		if (r) {
			Fly.dialog.confirm({
				title: 'Apply Changes',
				message: 'Restart to apply changes?',
				content: 'Your changes have been saved. You must log out and back in to see these changes. Do you want to log out now?',
				callback: function(r) {
					if (r) {
						window.top.system.logout();
					} else {
						Fly.window.message('Not logging out now may cause unexpected behavior');
					}
				}
			});
		} else {
			Fly.window.message('Error saving');
			window.location.reload();
		}
	});
}
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle FlyCSSectionTitle">Accessibility<img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>accessibility.svg" class="FlyCSSectionIcon"></div>
<p>These settings are designed to increase the ease of access for Fly.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('ShowFocusOutlines','SprocketComputers.Options') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="focus"><label for="focus">Show focus borders on input elements</label></p>
<p class="FlyCSHint">Shows a border around input elements such as text boxes and select boxes when the element is in focus.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('HighContrastTheme','SprocketComputers.Options') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="theme"><label for="theme">Use a high-contrast theme</label></p>
<p class="FlyCSHint">Uses a high-contrast theme for the desktop, windows, and applications. This will override your color and theme settings.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('LargeText','SprocketComputers.Options') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="text"><label for="text">Use large text</label></p>
<p class="FlyCSHint">Uses a larger font size for most text in Fly. Some applications may not look correct with this setting enabled.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('MagnifyWindows','SprocketComputers.Options') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="windows"><label for="windows">Magnify windows</label></p>
<p class="FlyCSHint">Scales the display of Fly windows.</p>


<div class="FlyCSSticky FlyCSStickyBottom" style="text-align:right;"><button onclick="Save()"><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button></div>

</body>
</html>