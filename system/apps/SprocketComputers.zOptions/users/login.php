<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
include 'Fly.CommonScript.php';
include 'Fly.Dialog.php';
include 'Fly.File.php';
?>
<link rel="stylesheet" href="../style.css">
<?php
if (!file_exists($_FLY['RESOURCE']['PATH']['USERS'].$_GET['user'].'/data/user.xml')) {
	$user = $_FLY['USER']['ID'];
} else {
	$user = $_GET['user'];
}

$userXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['USERS'].$user.'/data/user.xml');

if ($user == $_FLY['USER']['ID']) {
	echo '
<script>
var OptionsTree = [
	{name:\'Users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/index.php\'},
	{name:\'Manage login\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'application.svg\'}
];
</script>
	';
	$p = 'your';
	$ps = 'you';
	$pr = '';
} else {
	echo '
<script>
var OptionsTree = [
	{name:\'Users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/index.php\'},
	{name:\'Other users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/others.php\'},
	{name:\'Manage user\',index:\'users/index.php?user='.$user.'\'},
	{name:\'Manage login\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'application.svg\'}
];
</script>
	';
	$p = htmlspecialchars($userXML->user->name).'\'s';
	$ps = htmlspecialchars($userXML->user->name);
	$pr = 's';
}
?>
<style>
</style>
<script>
window.addEventListener('load',function() {
	window.advancedToggle = new Fly.CS.toggle(function(){
		document.getElementById('advancedIcon').src = '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>chevron-up.svg';
		document.getElementById('advancedLabel').innerHTML = 'Hide advanced options';
		document.getElementById('advancedOptions').style.display = 'block';
	},function() {
		document.getElementById('advancedIcon').src = '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>chevron-down.svg';
		document.getElementById('advancedLabel').innerHTML = 'Show advanced options';
		document.getElementById('advancedOptions').style.display = 'none';
	},false);
});

var custom = function() {
	var value = document.getElementById('customScript');
	var button = document.getElementById('customFile');

	var select = function() {
		Fly.file.get(function(process) {
			if (process) {
				window.custom.process = process;

				button.innerHTML = `<img class="FlyCSButtonIcon FlyCSButtonIconText" src="${process['icon']}">${process['name']}`;
				value.value = process['file'];
			}
		},{
			path: '%FLY.USER.PATH%',
			types: ['jsc']
		});
	}
	var clear = function() {
		button.innerHTML = '<img class="FlyCSButtonIcon FlyCSButtonIconText" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg">No file selected';
		value.value = '';
	}
	if (value.value !== '') {
		Fly.dialog.custom({
			modal: true,
			title: 'Custom Script',
			message: 'Edit custom script',
			content: 'There is currently a file specified as the custom script. What do you want to do?',
			sound: "question",
			input: {
				type: "list",
				options: [
					{
						text: 'Choose a new file',
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>browse.svg',
						value: 'select'
					},{
						text: 'Clear the file selection',
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg',
						value: 'clear'
					},
				]
			},
			icon: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg",
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
					default: true,
					validate: true,
					onclick: function(i) {
						if (i == 'select') {
							select();
						}
						if (i == 'clear') {
							clear();
						}
					}
				},
				{
					align: "right",
					validate: false,
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"
				}
			]
		});
	} else {
		select();
	}
}
custom.process = {};
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Manage <?php echo $p; ?> login experience</div>
<p class="description">These settings determine what actions Fly will take when <?php echo $ps; ?> log<?php echo $pr; ?> in to the computer.</p>

<p><input class="FlyCSInlineIcon" type="checkbox" id="user"><label for="user">Run user autostart file on login</label></p>
<p class="FlyCSHint">On login, runs <?php echo $p; ?> autostart file (autostart.jsc located in <?php echo $p; ?> user folder). Autostart files can be used to do many things, such as open applications and run tasks.</p>

<p><a onclick="window.advancedToggle.toggle()"><img id="advancedIcon" class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>chevron-down.svg"><span id="advancedLabel">Show advanced options</span></a></p>

<div id="advancedOptions" style="display:none;">
<p class=""><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg" class="FlyCSInlineIcon">Changing these options is not recommended unless you are an advanced user.</p>

<p><input class="FlyCSInlineIcon" type="checkbox" id="system"><label for="system">Run system autostart file on login</label></p>
<p class="FlyCSHint">On login, runs the system autostart file (autostart.jsc located in system components folder). The system autostart starts critical user experience applications, including the toolbar and desktop file view.</p>

<p><input class="FlyCSInlineIcon" type="checkbox" id="custom"><label for="custom">Run custom script on login</label></p>
<p class="FlyCSHint">
	<button id="customFile" onclick="custom()"><img class="FlyCSButtonIcon FlyCSButtonIconText" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg">No file selected</button>
</p>
<input type="hidden" id="customScript" value="">

</div>

<div class="buttons"><button onclick="" id="save"><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button></div>

</body>
</html>