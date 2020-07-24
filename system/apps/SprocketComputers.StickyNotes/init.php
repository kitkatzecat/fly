<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';

$files = scandir($_FLY['APP']['DATA_PATH']);
$files = array_diff($files,['.','..']);
sort($files);
?>
</head>
<script>
Fly.window.ready = function() {
	var windows = window.top.document.querySelectorAll('.FlyWindow');
	var open = false;
	windows.forEach(function(w) {
		if (w.id.indexOf('SprocketComputers.StickyNotes') != -1) {
			open = true;
		}
	});
	if (!open) {
		var files = <?php echo json_encode($files); ?>;
		console.log(files);
		if (files.length == 0) {
			if (<?php echo ($_GET['login'] == 'true') ? 'false' : 'true'; ?>) {
				window.top.system.command('run:SprocketComputers.StickyNotes.Note');
			}
		} else {
			files.forEach(function(f) {
				window.top.system.command('run:SprocketComputers.StickyNotes.Note,file='+encodeURIComponent(f));
			});
		}
	} else {
		window.top.system.command('run:SprocketComputers.StickyNotes.Note');
	}
	Fly.window.close();
}
</script>
<body>
</body>
</html>