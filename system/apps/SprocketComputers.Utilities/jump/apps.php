<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Command.php';
include 'Fly.Registry.php';
include 'Fly.FileProcessor.php';
include 'Fly.Actionmenu.php';

function getMasks($app) {
	global $_FLY;
	$masks = [];

	if (file_exists($_FLY['RESOURCE']['PATH']['APPS'].$app.'/ApplicationManifest.json')) {
		$json = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['APPS'].$app.'/ApplicationManifest.json'),true);
		if (isset($json['masks'])) {
			foreach ($json['masks'] as $id => $mask) {
				if (!isset($mask['hidden']) || $mask['hidden'] != true) {
					$process = FlyFileStringProcessor($app.'.'.$id);
					array_push($masks,$process);
				}
			}
		}
	} else {
		$xml = simplexml_load_file($_FLY['RESOURCE']['PATH']['APPS'].$app.'/ApplicationManifest.xml');
		if (isset($xml->masks)) {
			foreach ($xml->masks->children() as $mask) {
				if (!in_array((string)$mask['hidden'],['true','on','yes'])) {
					$process = FlyFileStringProcessor($app.'.'.$mask['id']);
					if ($process != false) {
						array_push($masks,$process);
					}
				}
			}
		}
}
	return $masks;
}

$apps = scandir($_FLY['RESOURCE']['PATH']['APPS']);
$apps = array_diff($apps,['.','..']);

?>
<link rel="stylesheet" href="items.css">
<script>
function toggleMasks(id,button,e) {
	e.stopPropagation();

	var element = document.getElementById(id);
	var button = document.getElementById(button);
	if (element.style.display == 'none') {
		element.style.display = 'block';
		button.innerHTML = '▲';
	} else {
		element.style.display = 'none';
		button.innerHTML = '▼';
	}
}
function contextMenu(file,item,e) {
	Fly.actionmenu(e,[
		[
			'<b>Open</b>',
			function() {
				item.onclick();
			},
			{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>run.svg'}
		],[
			'Application Options',
			function() {
				window.parent.run('SprocketComputers.zOptions,page=filesapps%2Fappdetails.php%3Fapp%3D'+file);
			},
			{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg'}
		]
	]);
	return false;
}
</script>
</head>
<body>

<?php
$appNames = [];
foreach ($apps as $app) {
	$process = FlyFileStringProcessor($app);
	if (!!$process) {
		$name = $process['fname'];
		while (isset($appNames[$name])) {
			$name .= 'A';
		}
		$appNames[$name] = $process;
	}
}
ksort($appNames);

$letter = '';
foreach ($appNames as $name => $app) {
	if (strtoupper(substr($name,0,1)) !== $letter) {
		$letter = strtoupper(substr($name,0,1));
		echo '<div class="letter FlyUiNoSelect">'.$letter.'</div>';
	}
	echo '<div oncontextmenu="return contextMenu(\''.$app['file'].'\',this,event);" onclick="window.parent.run(\''.$app['file'].'\')" class="FlyUiMenuItem FlyUiNoSelect"><img class="icon" src="'.$app['icon'].'">'.$app['fname'];
	$masks = getMasks($app['file']);
	if (sizeof($masks) > 0) {
		echo '<div onclick="toggleMasks(\'Masks-'.$app['file'].'\',\'Masks-button-'.$app['file'].'\',event);" class="FlyUiMenuItem masks-button" id="Masks-button-'.$app['file'].'">▼</div>';
		echo '<div class="masks" style="display:none;" id="Masks-'.$app['file'].'">';
		foreach ($masks as $id => $mask) {
			echo '<div onclick="window.parent.run(\''.$mask['file'].'\');event.stopPropagation();" class="FlyUiMenuItem FlyUiNoSelect"><img class="icon" src="'.$mask['icon'].'">'.$mask['fname'].'</div>';
		}
		echo '</div></div>';
	} else {
		echo '</div>';
	}
}
?>

</body>
</html>