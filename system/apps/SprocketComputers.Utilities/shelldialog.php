<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';
include 'Fly.Dialog.php';

$dialog = json_decode(base64_decode($_GET['content']),true);

?>
<script>
Fly.window.ready = function() {
	Fly.dialog.message({
		title: atob('<?php echo base64_encode($dialog['title']); ?>'),
		message: atob('<?php echo base64_encode($dialog['message']); ?>'),
		content: atob('<?php echo base64_encode($dialog['content']); ?>'),
		icon: atob('<?php echo base64_encode($dialog['icon']); ?>'),
		callback: function() {
			Fly.window.close();
		}
	})
}
</script>