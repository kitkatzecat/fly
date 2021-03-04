<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Constants.php';
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
	{name:\'Users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/index.php\'}
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
	{name:\'Manage user\'}
];
</script>
	';
	$p = htmlspecialchars($userXML->user->name).'\'s';
}
?>
<style>
.userImage {
    width: 48px;
    height: 48px;
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
    background-image: url("<?php echo FlyVarsReplace(FlyStringReplaceConstants($userXML->user->image)); ?>");
	vertical-align: middle;
	margin-right: 12px;
	margin-left: -12px;
	margin-top: -4px;
}
</style>
</head>
<body class="FlyUiText FlyUiNoSelect">

<?php
if ($user == $_FLY['USER']['ID']) {
	echo '<div class="title category">Users<img class="category-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg"></div>';
} else {
	echo '<div class="title">Manage user</div>';
}
?>
<div class="box"><h1><img class="userImage" src="<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg"><?php echo htmlentities($userXML->user->name); ?></h1></div>
<p><a onclick="window.location.href='name.php?user=<?php echo $user; ?>';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pencil.svg">Change <?php echo $p; ?> name</a></p>
<p><a onclick="window.location.href='image.php?user=<?php echo $user; ?>';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg">Change <?php echo $p; ?> picture</a></p>
<p><a onclick="window.location.href='password.php?user=<?php echo $user; ?>';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>lock.svg">Change <?php echo $p; ?> password</a></p>
<p><a onclick="window.location.href='login.php?user=<?php echo $user; ?>';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Manage <?php echo $p; ?> login experience</a></p>
<?php
if ($user == $_FLY['USER']['ID']) {
	echo '
<br>
<p><a onclick="window.location.href=\'others.php\';"><img class="inline-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg">Manage other users</a></p>
	';
}
?>

</body>
</html>