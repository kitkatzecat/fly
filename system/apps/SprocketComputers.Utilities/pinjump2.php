<?php
if ($_GET['mode'] == 'add') {
	goto add;
}
if ($_GET['mode'] == 'remove') {
	goto remove;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.Background.php';
include 'Fly.FileProcessor.php';
include 'Fly.Registry.php';
include 'Fly.Dialog.php';

$ready = 'Pin.init';

if ($_GET['file'] == '') {
	$ready = 'Pin.nofile';
	goto start;
}

$process = FlyFileStringProcessor(FlyVarsReplace($_GET['file']));
if (!$process) {
	$ready = 'Pin.notfound';
	goto start;
}

$reg = json_decode(FlyUserRegistryGet('Jump','SprocketComputers.Utilities'),true);

$message = '';

if ($process['extension'] == 'als' && !$_GET['noreplace'] == 'true') {
	$als = simpleXML_load_file($process['file']);
	if ($als) {
		$alsprocess = FlyFileStringProcessor(FlyVarsReplace((string)$als->link));
		if ($alsprocess) {
			$process = $alsprocess;
			$message = '<p class="FlyCSDescriptionHint">The file specified is an alias. The item that this alias links to will be ';
		}
	}
}

if (in_array($process['ffile'],$reg)) {
	$title = 'Unpin from Jump';
	$question = 'Do you want to unpin this '.$process['type'].' from your Jump menu?';
	$pin = 'pin-no.svg';
	$mode = 'remove';
	if ($message !== '') {
		$message .= 'unpinned from Jump.</p>';
	}
} else {
	$title = 'Pin to Jump';
	$question = 'Do you want to pin this '.$process['type'].' to your Jump menu?';
	$pin = 'pin.svg';
	$mode = 'add';
	if ($message !== '') {
		$message .= 'pinned to Jump.</p>';
	}
}

start:
?>

<script>
var Pin = {
	init: function() {
		<?php if ($ready != 'Pin.init') {goto skip_init;} ?>

		Fly.dialog.confirm({
			title: '',
			message: '<?php echo $title; ?>',
			content: '<?php echo $question; ?></p><?php echo $message; ?><p><div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="width:289px;"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo $process['icon']; ?>"><span style="vertical-align:middle;"><?php echo $process['fname']; ?></span></div>',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS'].$pin; ?>',
			callback: Pin.callback
		});

		<?php 
		skip_init:
		?>
	},
	callback: function(r) {
		<?php if ($ready != 'Pin.init') {goto skip_callback;} ?>

		if (!r) {
			Fly.window.close();
		} else {
			var frame = document.getElementById('frame');
			frame.src = '<?php echo $_FLY['CURRENT_URL']; ?>?mode=<?php echo $mode; ?>&file=<?php echo urlencode($process['ffile']); ?>';
		}

		<?php 
		skip_callback:
		?>
	},
	nofile: function() {
		Fly.dialog.message({
			title: 'No Item',
			message: 'No item specified',
			content: 'No item was specified to pin.',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
			callback: Fly.window.close
		});
	},
	notfound: function() {
		Fly.dialog.message({
			title: 'Not Found',
			message: 'Item could not be found',
			content: 'The specified item could not be found.',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
			callback: Fly.window.close
		});
	}
};

Fly.window.ready = <?php echo $ready; ?>;
</script>
</head>
<body>
	<iframe src="" id="frame"></iframe>
</body>
</html>
<?php
exit;

add:

include 'Fly.Registry.php';

if ($_GET['file'] !== '') {
	$reg = json_decode(FlyUserRegistryGet('Jump','SprocketComputers.Utilities'),true);
	array_push($reg,$_GET['file']);
	FlyUserRegistrySet('Jump',json_encode($reg));
	echo '<script>window.top.ui.jump.toggle();window.parent.Fly.window.close();</script>';
} 

exit;

remove:

include 'Fly.Registry.php';

if ($_GET['file'] !== '') {
	$reg = json_decode(FlyUserRegistryGet('Jump','SprocketComputers.Utilities'),true);
	$pos = array_search($_GET['file'],$reg);

	if ($pos !== false) {
		unset($reg[$pos]);
		$reg = array_values($reg);
	}

	FlyUserRegistrySet('Jump',json_encode($reg));
	echo '<script>window.top.ui.jump.toggle();window.parent.Fly.window.close();</script>';
} 

exit;
?>