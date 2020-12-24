<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.Background.php';
include 'Fly.FileProcess.php';
include 'Fly.Command.php';
include 'Fly.Dialog.php';

$ready = 'NewFolder.init';

$dir = FlyVarsReplace($_GET['p']);

if ($dir == '') {
	$ready = 'NewFolder.nodir';
	goto start;
}
if (!is_dir($dir)) {
	$ready = 'NewFolder.notdir';
	goto start;
}

$process = FlyFileStringProcessor(FlyVarsReplace($_GET['p']));

start:
?>
<script>
var NewFolder = {
	init: function() {
		<?php if ($ready != 'NewFolder.init') {goto skip_init;} ?>

		Fly.dialog.custom({
			modal: true,
			title: '',
			message: 'Create a new folder',
			content: 'Enter a name for the new folder to be created in this directory.</p><p><div class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="width:289px;"><img style="width:36px;height:36px;vertical-align:middle;margin-right:8px;" src="<?php echo $process['icon']; ?>"><span style="vertical-align:middle;"><?php echo $process['fname']; ?></span></div>',
			sound: "question",
			input: {
				type: "text",
				value: (NewFolder.f ? NewFolder.f : ''),
				validate: /^[^\\\/\'\"\?\+\=\&\|\*\:\<\>\,\%\`]+$/,
				validateMessage: 'The following characters cannot be part of a file name:<br>\\ / \' &quot; ? + = &amp; | * : &lt; &gt; , % `'
			},
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg',
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
					default: true,
					validate: true,
					onclick: NewFolder.check
				},
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
					onclick: Fly.window.close
				}
			]
		});

		<?php
		skip_init:
		?>
	},
	f: false,
	check: function(f) {
		<?php if ($ready != 'NewFolder.init') {goto skip_check;} ?>

		NewFolder.f = f;
		Fly.command('php:return ((file_exists(\'<?php echo $process['file']; ?>/'+f+'\') && is_dir(\'<?php echo $process['file']; ?>/'+f+'\')) ? \'true\' : \'false\');',function(r) {
			if (r['return']) {
				NewFolder.exists();
			} else {
				NewFolder.create();
			}
		});

		<?php
		skip_check:
		?>
	},
	create: function() {
		<?php if ($ready != 'NewFolder.init') {goto skip_create;} ?>

		Fly.command('php:return ((mkdir(\'<?php echo $process['file']; ?>/'+NewFolder.f+'\')) ? \'true\' : \'false\');',function(r) {
			if (r['return']) {
				Fly.window.close();
			} else {
				Fly.dialog.message({
					title: 'Not Created',
					message: 'Folder not created',
					content: `An error occurred when creating the folder "${NewFile.f}" in the directory "<?php echo $process['fname']; ?>". Please try again.`,
					icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
					callback: Fly.window.close
				});
			}
		});

		<?php
		skip_create:
		?>
	},
	exists: function() {
		<?php if ($ready != 'NewFolder.init') {goto skip_exists;} ?>

		Fly.dialog.confirm({
			title: 'Folder Exists',
			message: 'Folder already exists',
			content: 'A folder with the name "'+NewFolder.f+'" already exists in the directory "<?php echo $process['fname']; ?>". Would you like to try a different name?',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg',
			callback: function(r) {
				if (r) {
					NewFolder.init();
				} else {
					Fly.window.close();
				}
			}
		});	

		<?php
		skip_exists:
		?>
	},
	nodir: function() {
		Fly.dialog.message({
			title: 'No Directory',
			message: 'No directory specified',
			content: 'No directory was specified to create a new folder in.',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
			callback: Fly.window.close
		});	
	},
	notdir: function() {
		Fly.dialog.message({
			title: 'Not a Directory',
			message: 'Path not a directory',
			content: 'The path specified was not to a directory or does not exist.',
			icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
			callback: Fly.window.close
		});
	}
};
Fly.window.ready = <?php echo $ready; ?>;
</script>
</head>
</html>