<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Personalization',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg',index:'personalization/index.php'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title category">Personalization<img class="category-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg"></div>
<p><a onclick="window.location.href='background.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg">Change your background</a></p>
<p><a onclick="window.location.href='window.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Change window color and transparency</a></p>
<p><a onclick="window.location.href='jump.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg">Customize your Jump menu</a></p>
<p><a onclick="window.location.href='theme.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg">Change the theme</a></p>
<p><a onclick="window.location.href='sounds.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>sound.svg">Change system sounds</a></p>
<p><a onclick="window.location.href='desktop.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>desktop.svg">Manage your desktop</a></p>

</body>
</html>