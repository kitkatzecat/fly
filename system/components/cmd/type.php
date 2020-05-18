<?php

$process_error = '';
$process_display = '';
$process_return = '';

include 'Fly.FileProcessor.php';

$process = FlyFileTypeLookup($cmd[0]);
if ($process !== false) {
	if (array_key_exists($cmd[1],$process)) {
		$process_error = 'shell.dialog(\'File Type Lookup\',\''.$cmd[0].'<br><pre>'.htmlspecialchars($process[$cmd[1]],ENT_QUOTES).'</pre>\',\'File Type Lookup\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')';
		$process_display = $cmd[0]."\r\n".$process[$cmd[1]];
		$process_return = $process[$cmd[1]];
	} else {
		$process_error = 'shell.dialog(\'File Type Lookup\',\'Please use the command prompt to view output from this command.\',\'File Type Lookup\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\')';
		$process_display .= substr_replace(print_r($process,true),$cmd[0],0,5);
		$process_return = json_encode($process);
	}
} else {
	$process_error = 'shell.dialog(\'File Type Lookup\',\'The file type\\\'s properties could not be retrieved.\',\'File Type Lookup\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\')';
	$process_display = 'The file type\'s properties could not be retrieved.';
	$process_return = '';
}

FlyCommandError($process_error);
FlyCommandDisplay($process_display);
FlyCommandReturn($process_return,true);

?>