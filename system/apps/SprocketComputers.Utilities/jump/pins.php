<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Command.php';
include 'Fly.Registry.php';
include 'Fly.FileProcessor.php';
include 'Fly.Actionmenu.php';

$jump = json_decode(FlyRegistryGet('Jump','SprocketComputers.Utilities'),true);
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
			'Unpin from Jump',
			function() {
				window.parent.run('SprocketComputers.Utilities.PinJump,noreplace,file='+file);
			},
			{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pin-no.svg'}
		]
	]);
	return false;
}
</script>
</head>
<body>

<?php
foreach ($jump as $app) {
	$process = FlyFileStringProcessor(FlyVarsReplace($app));
	if (!$process) {
		$process = [
			'icon' => $_FLY['RESOURCE']['URL']['ICONS'].'type/unknown.svg',
			'fname' => basename($app),
			'file' => $app
		];
	}
	echo '<div oncontextmenu="return contextMenu(\''.$process['file'].'\',this,event);" onclick="window.parent.run(\''.$process['file'].'\');" class="FlyUiMenuItem FlyUiNoSelect"><img class="icon" src="'.$process['icon'].'">'.$process['fname'].'</div>';
}
?>

</body>
</html>