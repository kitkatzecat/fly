<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}

$file = FlyVarsReplace($_POST['filename']);
$folder = str_replace(basename($file),'',$file);
if (file_exists($folder)) {
	$count = 1;
	$content = base64_decode(str_replace('data:image/png;base64,','',$_POST['content'],$count));
	if (file_put_contents($file,$content)) {
		echo '200';
	} else {
		echo '<script>window.parent.Fly.dialog.message({message:\'Save error\',content:\'The file "'.htmlspecialchars(basename($file)).'" could not be saved due to an unknown error.\',title:\'Save Error\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\');</script>';
	}
} else {
	echo '<script>window.parent.Fly.dialog.message({message:\'Save error\',content:\'The file "'.htmlspecialchars(basename($file)).'" could not be saved because the specified folder to save in could not be found.\',title:\'Save Error\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\');</script>';
	}
?>