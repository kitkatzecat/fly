<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';

$q = '';
foreach ($_GET as $p => $v) {
	if ($p !== 'Fly_Id') {
		$q .= "$p=".urldecode($v).",";
	}
}
?>
<script>window.top.system.command('run:SprocketComputers.zFileManager,<?php echo $q; ?>');Fly.window.close()</script>