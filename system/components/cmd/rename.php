<?php
	FlyCommandExecute('system.command(\'run:SprocketComputers.FileManager.Rename,file='.$cmd[0].'\')');
	FlyCommandDisplay('Opening Rename dialog for "'.basename($cmd[0]).'"...');
?>