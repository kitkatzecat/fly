<?php
/*$process = FlyFileStringProcessor($cmd[0]);
if($process != false) {
	if ($process["type"]=="file") {
		$filesize = '<br>Size: '.round((filesize($process["file"])/1000)).' KB';
	} else {
		$filesize = '';
	}
	$echo = 'shell.dialog(\'"'.htmlentities($process["name"]).'" Information\',"Type: '.htmlentities($process["type"]).'<br>Description: '.htmlentities($process["description"]).$filesize.'","Information","'.$process["icon"].'")';
}*/
	FlyCommandExecute('system.command(\'run:SprocketComputers.FileManager.Properties,file='.$cmd[0].'\')');
	FlyCommandDisplay('Opening Properties dialog for "'.basename($cmd[0]).'"...');
?>