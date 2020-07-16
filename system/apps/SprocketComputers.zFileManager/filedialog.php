<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';
include 'Fly.FileProcessor.php';
include 'Fly.Dialog.php';

$protected = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['COMPONENTS'].'protected.json'),true);
$sysfiles = FlyRegistryGet('ShowSystemFiles','SprocketComputers.zFileManager');
?>
<script>
Fly.window.ready = function() {
	Fly.window.icon.set('<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.zFileManager/fileman.svg');
<?php

if ($_GET['file'] == '') {
	goto noitem;
} else {
	$process = FlyFileStringProcessor(FlyVarsReplace($_GET['file'],false,FlyCoreVars($_FLY['PATH'])));

	if (in_array($process['ffile'],$protected) && $sysfiles !== 'true') {
		goto nosysfiles;
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
?>
    Fly.dialog.confirm({
		title: '<?php echo $process['fname']; ?>',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg',
		message: 'Delete',
		content: 'Do you want to delete this <?php echo $process['type']; ?>?<div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="margin-top:8px;width:289px;"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo $process['icon']; ?>"><?php echo $process['fname']; ?></div>',
		callback: function() {
			Fly.window.close();
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
		content: 'Enter a new name for this <?php echo $process['type']; ?>.<div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="margin-top:8px;width:289px;"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo $process['icon']; ?>"><?php echo $process['fname']; ?></div>',
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
					Fly.window.close();
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

nosysfiles:
?>
    Fly.dialog.message({
		title: 'System File',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
		message: 'Item is a system file',
		content: 'The specified item is a system file. Modifying these items is disabled to protect your computer. To change this, turn on Show System Files in File Manager.',
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