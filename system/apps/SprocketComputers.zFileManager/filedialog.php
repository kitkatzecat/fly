<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';
include 'Fly.FileProcessor.php';
include 'Fly.Dialog.php';
include 'Fly.Command.php';

$protected = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['COMPONENTS'].'protected.json'),true);
$sysfiles = FlyRegistryGet('ShowSystemFiles','SprocketComputers.zFileManager');
$extensions = FlyRegistryGet('HideFileExtensions','SprocketComputers.zFileManager');
$extensionsALS = FlyRegistryGet('ShowExtensionALS','SprocketComputers.zFileManager');
?>
<script>
Fly.window.ready = function() {
	Fly.window.icon.set('<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.zFileManager/fileman.svg');
<?php

if ($_GET['file'] == '') {
	goto noitem;
} else {
	$process = FlyFileStringProcessor(FlyVarsReplace($_GET['file'],false,FlyCoreVars($_FLY['PATH'])));
	$extra = '';

	if (!$process) {
		goto noexist;
	}

	if ($process['extension'] == 'als') {
		$icon = '<div style="display:inline-block;width:36px;height:36px;vertical-align:middle;margin-right:8px;position:relative;background-size:100% 100%;background-image:url(\\\''.$process['icon'].'\\\');"><img style="position:absolute;bottom:0;left:0;width:14px;height:14px;" src="'.$_FLY['APP']['URL'].'alias.svg"></div>';
	} else {
		$icon = '<img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="'.$process['icon'].'">';
	}
	$item = '<div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="margin-top:8px;width:289px;">'.$icon.$process['fname'].'</div>';
	
	if (in_array($process['ffile'],$protected)) {
		$icon = '<div style="display:inline-block;width:36px;height:36px;vertical-align:middle;margin-right:8px;position:relative;background-size:100% 100%;background-image:url(\\\''.$process['icon'].'\\\');"><img style="position:absolute;bottom:0;left:0;width:14px;height:14px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'lock.svg"></div>';
		$item = '<div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="margin-top:8px;width:289px;">'.$icon.$process['fname'].'</div>';
		$extra .= '<p style="margin-top:4px;color:#f00;" class="FlyCSDescriptionHint">This item is a system '.$process['type'].'. Modifying this '.$process['type'].' can harm your computer.</p>';
		if ($sysfiles !== 'true') {
			goto nosysfiles;
		}
	}

	if ($_GET['d'] == 'delete') {
		goto delete;
	}
	if ($_GET['d'] == 'rename') {
		goto rename;
	}
}

delete:
if ($process['type'] == 'application') {
?>
    Fly.dialog.message({
		title: 'Cannot Delete',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
		message: 'Cannot delete application',
		content: 'Applications cannot be deleted from the File Manager Delete dialog. To uninstall an application, open Options.',
		callback: function() {
			Fly.window.close();
		}
	});
<?php
} else {
	if ($process['extension'] == 'als') {
		$extra .= '<p style="margin-top:4px" class="FlyCSDescriptionHint">The file specified is an alias. Deleting this alias will not delete the item it links to.</p>';
	}
?>
    Fly.dialog.confirm({
		title: '<?php echo $process['fname']; ?>',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg',
		message: 'Delete',
		content: 'Are you sure you want to permanently delete this <?php echo $process['type']; ?>?<?php echo $item.$extra; ?>',
		callback: function(r) {
			if (r) {
				Fly.command('delete:<?php echo $process['file']; ?>,true',function() {
					Fly.window.close();
				});
			} else {
				Fly.window.close();
			}
		}
	});
<?php
}
goto end;

rename:
if ($process['type'] == 'application') {
?>
	Fly.dialog.message({
		title: 'Cannot Rename',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
		message: 'Cannot rename application',
		content: 'Applications cannot be renamed.',
		callback: function() {
			Fly.window.close();
		}
	});
<?php
} else {
?>
	Fly.dialog.custom({
		modal: true,
		title: '<?php echo $process['fname']; ?>',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pencil.svg',
		message: 'Rename',
		content: 'Enter a new name for this <?php echo $process['type']; ?>.<?php echo $item.$extra; ?>',
		sound: "question",
		input: {
			type: "text",
			value: '<?php echo $process['fname']; ?>'
		},
		buttons: [
			{
				align: "right",
				image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
				default: true,
				onclick: function(i) {
					<?php
					if ($extensions == 'true' || ($extensionsALS !== 'true' && $process['extension'] == 'als')) {
						?>
						i += '.<?php echo $process['extension']; ?>';
						<?php
					}
					?>
					Fly.command('rename:<?php echo $process['file']; ?>,'+i+',true');
				}
			},
			{
				align: "right",
				image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
				onclick: function() {
					Fly.window.close();
				}
			}
		]
	});
<?php
}
goto end;

noitem:
?>
    Fly.dialog.message({
		title: 'No Item',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
		message: 'No item specified',
		content: 'No item was specified to modify.',
		callback: function() {
			Fly.window.close();
		}
	});
<?php
goto end;

noexist:
?>
    Fly.dialog.message({
		title: 'Not Found',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
		message: 'Item not found',
		content: 'The specified item could not be found.',
		callback: function() {
			Fly.window.close();
		}
	});
<?php
goto end;

nosysfiles:
?>
    Fly.dialog.message({
		title: 'System Item',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
		message: 'Item is a system <?php echo $process['type']; ?>',
		content: 'The specified item is a system <?php echo $process['type']; ?>. Modifying these items is disabled to protect your computer.<?php echo $item; ?><p class="FlyCSDescriptionHint" style="margin-top:4px">To change this, turn on Show System Files in File Manager.</p>',
		callback: function() {
			Fly.window.close();
		}
	});
<?php
goto end;

end:
?>
}
</script>