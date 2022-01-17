<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.Background.php';
include 'Fly.FileProcess.php';
include 'Fly.Command.php';
include 'Fly.Dialog.php';
include 'Fly.File.php';

$process = FlyFileStringProcessor(FlyVarsReplace($_GET['file']));
$file = base64_encode(json_encode($process));
$op = $_GET['d'];
?>
<script>
var FileOperation = {
	file: JSON.parse(atob('<?php echo $file; ?>')),
	op: '<?php echo $op; ?>',
	init: function() {
		if (!FileOperation.file) {
			Fly.dialog.message({
				title: 'Not Found',
				icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
				message: 'Item not found',
				content: 'The specified item could not be found.',
				callback: Fly.window.close
			});
		} else if (FileOperation.op == 'copy' || FileOperation.op == 'move') {
			Fly.file.dir(
				function(f) {
					if (f) {
						FileOperation.checkExist(f);
					} else {
						Fly.window.close();
					}
				},
				{
					path: FileOperation.file['fpath']
				}
			);
		} else {
			Fly.dialog.message({
				title: 'Invalid Operation',
				icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
				message: 'Invalid operation',
				content: 'No valid file operation was specified. Expecting "copy" or "move".',
				callback: Fly.window.close
			});
		}
	},
	checkExist: function(f) {
		Fly.command(`exists:${f['file']}/${FileOperation.file['name']}`,function(r) {
			if (r['return']) {
				Fly.dialog.confirm({
					title: FileOperation.file['fname'],
					message: `Overwrite ${FileOperation.file['type']}?`,
					icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg',
					content: `The ${FileOperation.file['type']} "${FileOperation.file['name']}" already exists in this directory. Do you want to overwrite it?`,
					callback: function(c) {
						if (c) {
							FileOperation.confirm(f);
						} else {
							Fly.window.close();
						}
					}
				});
			} else {
				FileOperation.confirm(f);
			}
		});
	},
	confirm: function(f) {
		if (FileOperation.op == 'copy') {
			FileOperation.confirmCopy(f);
		}
		if (FileOperation.op == 'move') {
			FileOperation.confirmMove(f);
		}
	},
	confirmCopy: function(f) {
		Fly.command(`copy:${FileOperation.file['file']},${f['file']},true`,function() {
			Fly.window.close();
		});
	},
	confirmMove: function(f) {
		Fly.command(`move:${FileOperation.file['file']},${f['file']},true`,function() {
			Fly.window.close();
		});
	}
};
Fly.window.ready = FileOperation.init;
</script>
</head>
<body>

</body>
</html>