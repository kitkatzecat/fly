<?php
	FlyCommandExecute('system.command(\'run:SprocketComputers.FileManager.Delete,file='.$cmd[0].'\')');
	FlyCommandDisplay('Opening Delete dialog for "'.basename($cmd[0]).'"...');
?>