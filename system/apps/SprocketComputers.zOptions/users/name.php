<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
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
	{name:\'Change name\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'pencil.svg\'}
];
</script>
	';
	$p = 'your';
} else {
	echo '
<script>
var OptionsTree = [
	{name:\'Users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/index.php\'},
	{name:\'Other users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/others.php\'},
	{name:\'Manage user\',index:\'users/index.php?user='.$user.'\'},
	{name:\'Change name\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'pencil.svg\'}
];
</script>
	';
	$p = htmlspecialchars($userXML->user->name).'\'s';
}
?>
<style>
</style>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Change <?php echo $p; ?> name</div>
<p class="description">Enter a new new name for <?php echo $p; ?> user account.</p>

<p class="shead">Current Name</p>
<p><?php echo htmlspecialchars($userXML->user->name); ?></p>

<p class="shead">New Name</p>
<p><input onkeypress="" id="name" type="text"></p>

<div class="buttons"><button onclick="" id="save"><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button></div>

</body>
</html>