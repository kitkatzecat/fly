<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Command.php';
include 'Fly.Registry.php';
include 'Fly.FileProcessor.php';

$today = [
	'SprocketComputers.zFileManager',
	'SprocketComputers.Memo',
	'SprocketComputers.MediaPlayer'
];
$yesterday = [
	'SprocketComputers.PhotoViewer',
	'SprocketComputers.zOptions',
	'SprocketComputers.Preview',
	'SprocketComputers.Utilities'
];

$recent = ['Today'];
foreach ($today as $app) {
	$process = FlyFileStringProcessor(FlyVarsReplace($app));
	if ($process) {
		array_push($recent,$process);
	}
}
array_push($recent,'Yesterday');
foreach ($yesterday as $app) {
	$process = FlyFileStringProcessor(FlyVarsReplace($app));
	if ($process) {
		array_push($recent,$process);
	}
}

//$showID = FlyRegistryGet('JumpShowApplicationID','SprocketComputers.Utilities');
?>
<link rel="stylesheet" href="items.css">
</head>
<body>
<div style="margin-top:32px;text-align:center;">Not yet implemented</div>
<?php
/*
foreach ($recent as $app) {
	if (is_string($app)) {
		echo '<div class="letter FlyUiNoSelect">'.$app.'</div>';
	} else {
		echo '<div class="FlyUiMenuItem FlyUiNoSelect"><img class="icon" src="'.$app['icon'].'">'.$app['fname'].'</div>';
	}
}
*/
?>

</body>
</html>