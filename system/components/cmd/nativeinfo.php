<?php
$process = FlyFileStringProcessor($cmd[0]);
if($process != false) {
	if ($process["type"]=="file") {
		$filesize = '<br>Size: '.round((filesize($process["file"])/1000)).' KB';
	} else {
		$filesize = '';
	}
	FlyCommandError('shell.dialog(\''.htmlentities($process["name"]).'\',"Type: '.htmlentities($process["type"]).'<br>Description: '.htmlentities($process["description"]).$filesize.'",\'Information - '.htmlentities($process["name"]).'\',"'.$process["icon"].'")');
	
	FlyCommandDisplay($process['name']);
	FlyCommandDisplay('Type: '.$process['type']);
	FlyCommandDisplay('Description: '.$process['description']);
	if ($filesize !== '') {
		FlyCommandDisplay(str_replace('<br>','',$filesize));
	}
}
?>