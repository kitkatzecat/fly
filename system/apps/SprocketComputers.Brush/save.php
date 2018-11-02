<?php
if (empty($_GET['check'])) {

$folder = str_replace(basename($_POST['filename']),'',$_POST['filename']);
if (file_exists($folder)) {
	$count = 1;
	$content = base64_decode(str_replace('data:image/png;base64,','',$_POST['content'],$count));
	if (file_put_contents($_POST['filename'],$content)) {
		echo '200';
	} else {
		echo '<script>window.top.shell.dialog(\'Save error\',\'The file "'.htmlspecialchars(basename($_POST['filename'])).'" could not be saved due to an unknown error.\',\'Brush - Save Error\');</script>';
	}
} else {
	echo '<script>window.top.shell.dialog(\'Save error\',\'The file "'.htmlspecialchars(basename($_POST['filename'])).'" could not be saved because the specified folder to save in could not be found.\',\'Brush - Save Error\');</script>';
}

} else {
	if (file_exists($_GET['file'])) {
		echo '<script>setTimeout(function(){window.parent.Save.confirmoverwrite();},100);</script>';
	} else {
		echo '<script>setTimeout(function(){window.parent.Save.confirmwrite();},100);</script>';
	}
}
?>