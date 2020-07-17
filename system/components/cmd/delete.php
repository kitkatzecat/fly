<?php
	include 'Fly.FileProcessor.php';
	function unlink_dir($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir); 
			foreach ($objects as $object) { 
				if ($object != "." && $object != "..") { 
					if (filetype($dir."/".$object) == "dir") {
						unlink_dir($dir."/".$object);
					} else {
						unlink($dir."/".$object);
					} 
				} 
			} 
			reset($objects); 
			rmdir($dir); 
		} 
	}
	$process = FlyFileStringProcessor($cmd[0]);
	if ($process) {
		if ($process['type'] == 'file' || $process['type'] == 'folder') {
			if ($cmd[1] == 'true') {
				if (is_dir($process['file'])) {
					unlink_dir($process['file']);
				} else {
					unlink($process['file']);
				}
				FlyCommandDisplay('"'.$process['name'].'" has been deleted.');
				FlyCommandReturn('true');
			} else {
				FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.Delete,file='.$cmd[0].'\')');
				FlyCommandDisplay('Are you sure you want to delete the '.$process['type'].' "'.$process['name'].'"? Repeat this command with the second parameter set to "true" to confirm.'."\r\n".'(delete:'.$process['ffile'].',true)');
			}
		} else {
			FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.Delete,file='.$cmd[0].'\')');
			FlyCommandDisplay('The specified item cannot be deleted here.');
		}
	} else {
		FlyCommandError('system.command(\'run:SprocketComputers.zFileManager.Delete,file='.$cmd[0].'\')');
		FlyCommandDisplay('The specified item could not be found.');
	}
?>