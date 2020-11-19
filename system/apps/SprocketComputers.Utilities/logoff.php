<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';
include 'Fly.Dialog.php';
?>
<script>
Fly.window.ready = function() {
	Fly.dialog.confirm({
		title: '',
		message: 'Log Off',
		content: 'Are you sure you want to log off?</p><p>Any unsaved work in any open applications will be lost.',
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>lock.svg',
		callback: function(r) {
			if (r) {
				window.top.setTimeout(window.top.system.logout,1000);
			} else {
				Fly.window.close();
			}
		}
	});
}
</script>
</head>
<body>

</body>
</html>