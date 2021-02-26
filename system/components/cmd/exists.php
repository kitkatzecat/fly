<?php
// Fly.SingleFileCommand

$process_error = '';
$process_display = '';
$process_return = '';

include 'Fly.FileProcessor.php';
$process = FlyFileStringProcessor($cmd[0]);

if ($process !== false) {
	if (in_array($cmd[1],['application','file','folder'])) {
		if ($cmd[1] == $process['type']) {
			$process_error = 'shell.dialog(\'Exists and Is\',\'The item "'.$process['file'].'" exists and is a(n) '.$cmd[1].'.\',\'Exists and Is\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')';
			$process_display = 'The item "'.$process['file'].'" exists and is a(n) '.$cmd[1].'.';
			$process_return = 'true';
		} else {
			$process_error = 'shell.dialog(\'Exists but Is Not\',\'The item "'.$process['file'].'" exists but is not a(n) '.$cmd[1].'.\',\'Exists but Is Not\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')';
			$process_display = 'The item "'.$process['file'].'" exists but is not a(n) '.$cmd[1].'.';
			$process_return = 'false';
		}
	} else {
		$process_error = 'shell.dialog(\'Exists\',\'The '.$process['type'].' "'.$process['file'].'" exists.\',\'Exists\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')';
		$process_display = 'The '.$process['type'].' "'.$process['file'].'" exists.';
		$process_return = 'true';
	}
} else {
	$process_error = 'shell.dialog(\'Exists\',\'The item"'.$cmd[0].'" could not be found.\',\'Exists\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\')';
	$process_display = 'The item "'.$cmd[0].'" could not be found.';
	$process_return = 'false';
}

FlyCommandError($process_error);
FlyCommandDisplay($process_display);
FlyCommandReturn($process_return);

?>