<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Constants.php';

$users = [];
$dir = $_FLY['RESOURCE']['PATH']['USERS'];
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if (is_dir($dir.$file) && !in_array($file, ['.','..'])) {
				if (file_exists($dir.$file.'/data/user.xml')) {
					array_push($users,$file);
				}
			}
		}
		closedir($dh);
	}
}
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Users',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>person.svg',index:'users/index.php'},
	{name:'Other users',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>person.svg'}
];
</script>
<style>
.userImage {
	vertical-align: middle;
	width: 56px;
	height: 56px;
	margin-bottom: 12px;
	border-top: 1px solid #fff;
	border-left: 1px solid #fff;
	border-bottom: 1px solid #f2f2f2;
	border-right: 1px solid #f2f2f2;
	border-radius: 6px;
	box-shadow: 0px 0px 4px #000;
	background-size: contain;
	background-color: #808080;
	background-repeat: no-repeat;
	background-position: center center;
}
.userCard {
	padding: 8px;
	display: inline-block;
	text-align: center;
	width: 88px;
	word-wrap: break-word;
	vertical-align: top;
}
</style>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Manage other users</div>
<p class="description">Select a user's account to manage:</p>

<div class="box"><div style="max-width:88%;margin:0 auto;">
<?php
foreach ($users as $user) {
	$userXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['USERS'].$user.'/data/user.xml');
	echo '<div class="FlyUiMenuItem userCard" onclick="window.location.href=\'index.php?user='.$userXML->user->id.'\';"><img src="'.$_FLY['RESOURCE']['URL']['OS'].'userimagelight.svg" style="background-image:url(\''.FlyVarsReplace(FlyStringReplaceConstants((string)$userXML->user->image)).'\'),linear-gradient(to bottom, rgb(140,152,165) 0%,rgb(175,192,206) 100%);" class="userImage"><br>'.htmlspecialchars($userXML->user->name).'</div>';
}
?>
</div></div>

</body>
</html>