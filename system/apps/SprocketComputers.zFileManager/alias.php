<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';
include 'Fly.FileProcessor.php';
include 'Fly.Command.php';
include 'Fly.Dialog.php';
include 'Fly.File.php';

if ($_GET['create'] == 'true') {
	if (!isset($_GET['name'])) {
		$file = base64_encode(json_encode(FlyFileStringProcessor(FlyVarsReplace($_GET['file']))));
	} else {
		$file = base64_encode(json_encode($_GET['file']));
	}
	$ready = 'Create.open';
} else if ($_GET['icon'] == 'true') {
	$file = base64_encode(json_encode($_GET['file']));
	$ready = 'Icon.check';
} else {
	$ready = 'Fly.window.close';
}
?>
</head>
<script>
var Create = {
	file: JSON.parse(atob('<?php echo $file; ?>')),
	open: function() {
		Fly.window.name.set('Create Alias');
		Fly.window.title.set('Create Alias');
		if (!Create.file) {
			Fly.dialog.message({
				title: 'Not Found',
				icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
				message: 'Item not found',
				content: 'The specified item could not be found.',
				callback: Fly.window.close
			});
		} else {
			Fly.file.set(
				function(f) {
					if (f) {
						Create.save(f);
					} else {
						Fly.window.close();
					}
				},
				{
					path: (Create.file['type'] == 'application' ? '<?php echo $_FLY['USER']['PATH']; ?>' : Create.file['fpath']),
					name: Create.file['bname'],
					extensions: ['als']
				}
			);
		}
	},
	save: function(f) {
		var als = '<alias><link><![CDATA['+Create.file['ffile']+']]></link></alias>';
		Fly.file.write({
			method: 'text',
			content: als,
			file: f['file'],
			overwrite: true,
			ready: function(status,message) {
				if (!status) {
					Fly.dialog.message({
						title: 'Not Created',
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
						message: 'Alias not created',
						content: 'The alias could not be created: '+message,
						callback: Fly.window.close
					});
				} else {
					Fly.window.close();
				}
			}
		});
	},
};
var Icon = {
	file: atob('<?php echo $file; ?>'),
	check: function() {
		Fly.window.name.set('Change Alias Icon');
		Fly.window.title.set('Change Alias Icon');
		Fly.file.read({
			method: 'text',
			file: Icon.file,
			ready: function(result,message) {
				if (!!result) {
					Icon.fileName = Icon.file;
					Icon.file = result;
					Icon.open();
				} else {
					Fly.dialog.message({
						title: 'Cannot Load',
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
						message: 'Cannot load alias',
						content: 'The specified alias could not be loaded for modification: '+message,
						callback: Fly.window.close
					});
				}
			}
		});
	},
	open: function() {
		Fly.file.get(
			function(f) {
				if (!!f) {
					Icon.save(f);
				} else {
					Fly.window.close();
				}
			},
			{
				path: '<?php echo $_FLY['RESOURCE']['PATH']['ICONS']; ?>',
				types: ['svg','png','jpg','image/']
			}
		);
	},
	save: function(f) {
		var reg = /<icon>(.)+<\/icon>/g;
		var als = Icon.file;
console.log(als);
		if (als.indexOf('<icon>') != -1) {
			als = als.replace(reg,'<icon><![CDATA['+f['ffile']+']]></icon>');
		} else {
			als = als.replace('<alias>','<alias><icon><![CDATA['+f['ffile']+']]></icon>');
		}
		Fly.file.write({
			method: 'text',
			content: als,
			file: Icon.fileName,
			overwrite: true,
			ready: function(status,message) {
				if (!status) {
					Fly.dialog.message({
						title: 'Not Saved',
						icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',
						message: 'Alias not saved',
						content: 'The alias could not be saved: '+message,
						callback: Fly.window.close
					});
				} else {
					Fly.window.close();
				}
			}
		});
	},
};
Fly.window.ready = <?php echo $ready; ?>;
</script>
<body>

</body>
</html>