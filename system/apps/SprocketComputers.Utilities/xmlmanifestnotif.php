<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';
?>
<script>
Fly.window.ready = function() {
	window.top.shell.notification.create(
		'Application Uses XML Manifest',
		'The application "<?php echo $_GET['id']; ?>" uses the deprecated XML manifest format. This format will no longer be supported in the future.',
		'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>warning.svg'
	);
	Fly.window.close();
}
</script>