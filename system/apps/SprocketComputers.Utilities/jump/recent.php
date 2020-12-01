<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Command.php';
include 'Fly.Registry.php';
include 'Fly.FileProcessor.php';

$apps = json_decode(FlyRegistryGet('ApplicationHistory','root.public'),true);

$todayD = date("Y").str_pad(date("z"),3,'0',STR_PAD_LEFT);
$yesterdayD = date("Y",strtotime('-1 day')).str_pad(date("z",strtotime('-1 day')),3,'0',STR_PAD_LEFT);

if (isset($apps[$todayD])) {
	$today = $apps[$todayD];
} else {
	$today = [];
}

if (isset($apps[$yesterdayD])) {
	$yesterday = $apps[$yesterdayD];
} else {
	$yesterday = [];
}

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

?>
<link rel="stylesheet" href="items.css">
<script>
function contextMenu(file,item,e) {
	Fly.actionmenu(e,[
		[
			'<b>Open</b>',
			function() {
				item.onclick();
			},
			{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>run.svg'}
		],[
			'Pin to Jump',
			function() {
				window.parent.run('SprocketComputers.Utilities.PinJump,file='+file);
			},
			{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pin.svg'}
		],[
			'Remove from Recent',
			function() {
				Fly.command('history:remove,'+file,function() {
					window.parent.nav('recent.php');
				});
			},
			{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}
		]
	]);
	return false;
}
</script>
</head>
<body>
<?php

foreach ($recent as $app) {
	if (is_string($app)) {
		echo '<div class="letter FlyUiNoSelect">'.$app.'</div>';
	} else {
		if (!$app['hidden']) {
			echo '<div oncontextmenu="return contextMenu(\''.$app['file'].'\',this,event);" onclick="window.parent.run(\''.$app['file'].'\');" class="FlyUiMenuItem FlyUiNoSelect"><img class="icon" src="'.$app['icon'].'">'.$app['fname'].'</div>';
		}
	}
}

?>

</body>
</html>