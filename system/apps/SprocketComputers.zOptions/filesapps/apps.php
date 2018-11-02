<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Files & Applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',index:'filesapps/index.php'},
	{name:'Installed applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Manage installed applications</div>
<p class="description">Click an application's name for more details.</p>
<?php
foreach (FlyGetApps() as $app) {
	$aprocess = FlyFileStringProcessor($app);
	echo '<p><a onclick="window.location.href=\'appdetails.php?app='.$app.'\';"><img class="inline-icon" src="'.$aprocess['icon'].'">'.htmlspecialchars($aprocess['name']).'</a></p>';
}
?>

</body>
</html>