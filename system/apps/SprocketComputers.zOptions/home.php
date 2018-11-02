<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Constants.php';
?>
<link rel="stylesheet" href="style.css">
<?php
$user = $_FLY['USER']['ID'];

$userXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['USERS'].$user.'/data/user.xml');

echo '
<script>
var OptionsTree = [
	{name:\'Home\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'home.svg\',index:\'home.php\'}
];
</script>
	';
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
echo '<div class="title"><img class="title-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'home.svg">Home</div>';
?>
<div class="box"><h1><img class="userImage" src="https://localhost/system/resources/os/userimagelight.svg"><?php echo htmlentities($userXML->user->name); ?></h1></div>
<div class="title">Common tasks</div>
<p><a onclick="window.location.href='personalization/background.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg">Change your background</a></p>
<p><a onclick="window.location.href='users/name.php?user=<?php echo $user; ?>';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pencil.svg">Change your name</a></p>
<p><a onclick="window.location.href='users/image.php?user=<?php echo $user; ?>';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg">Change your picture</a></p>
<p><a onclick="window.location.href='users/password.php?user=<?php echo $user; ?>';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>lock.svg">Change your password</a></p>

</body>
</html>