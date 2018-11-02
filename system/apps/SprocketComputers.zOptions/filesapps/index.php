<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Files & Applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',index:'filesapps/index.php'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Files & Applications</div>
<p><a onclick="window.location.href='apps.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Manage installed applications</a></p>
<p><a onclick="window.location.href='filetypes.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg">Manage file types and associations</a></p>

</body>
</html>