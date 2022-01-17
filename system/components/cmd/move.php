<?php
// Fly.SingleFileCommand
	include 'Fly.FileProcessor.php';
	$process = ($cmd[0] == '' ? false : FlyFileStringProcessor($cmd[0]));
	$process1 = ($cmd[1] == '' ? false : FlyFileStringProcessor($cmd[1]));
	if ($process) {
		if ($process['type'] == 'file' /*|| $process['type'] == 'folder'*/) {
			if ($process1) {
				if ($process1['type'] == 'folder') {
					if ($cmd[2] == 'true') {
						rename($process['file'],$process1['file'].'/'.$process['name']);
						FlyCommandDisplay('"'.$process['name'].'" has been moved to "'.$process1['name'].'".');
						FlyCommandReturn('true');
					} else {
						FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.Rename,file='.$cmd[0].'\')');
						FlyCommandDisplay('Are you sure you want to move the '.$process['type'].' "'.$process['name'].'" to "'.$process1['name'].'"? Repeat this command with the third parameter set to "true" to confirm.'."\r\n\r\n".'(move:'.$process['ffile'].','.$process1['ffile'].',true)'."\r\n");
					}
				} else {
					FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.MoveTo,file='.$cmd[0].'\')');
					FlyCommandDisplay('The specified location to move to is not a directory.');
				}
			} else {
				FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.MoveTo,file='.$cmd[0].'\')');
				FlyCommandDisplay('The specified location to move to could not be found.');
			}
		} else {
			FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.MoveTo,file='.$cmd[0].'\')');
			FlyCommandDisplay('The specified item cannot be moved. (Folders cannot yet be moved.)');
		}
	} else {
		FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.MoveTo,file='.$cmd[0].'\')');
		FlyCommandDisplay('The specified item could not be found.');
	}
?>