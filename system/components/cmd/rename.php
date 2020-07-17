<?php
	include 'Fly.FileProcessor.php';
	$process = FlyFileStringProcessor($cmd[0]);
	if ($process) {
		if ($process['type'] == 'file' || $process['type'] == 'folder') {
			if ($cmd[2] == 'true') {
				rename($process['file'],str_replace($process['name'],FlyFileStringReplace($cmd[1]),$process['file']));
				FlyCommandDisplay('"'.$process['name'].'" has been renamed to "'.FlyFileStringReplace($cmd[1]).'".');
				if (FlyFileStringReplace($cmd[1]) !== $cmd[1]) {
					FlyCommandDisplay('The specified new name had disallowed characters that were removed.');
				}
				FlyCommandReturn('true');
			} else {
				FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.Rename,file='.$cmd[0].'\')');
				FlyCommandDisplay('Are you sure you want to rename the '.$process['type'].' "'.$process['name'].'" to "'.FlyFileStringReplace($cmd[1]).'"? Repeat this command with the third parameter set to "true" to confirm.'."\r\n\r\n".'(rename:'.$process['ffile'].','.$cmd[1].',true)'."\r\n");
				FlyCommandDisplay('The specified new name has disallowed characters that will be removed.');
			}
		} else {
			FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.Rename,file='.$cmd[0].'\')');
			FlyCommandDisplay('The specified item cannot be renamed.');
		}
	} else {
		FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.Rename,file='.$cmd[0].'\')');
		FlyCommandDisplay('The specified item could not be found.');
	}
?>