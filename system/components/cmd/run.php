<?php
for ($x = 1; $x < count($cmd); $x++) {
	$var = explode('=',$cmd[$x]);
	if ($var[0] !== '') {
		if ($var[1] == '') {
			$given .= ','.$var[0].'=true';
		} else {
			$given .= ','.$cmd[$x];
		}
	}
}
$process = FlyFileStringProcessor($cmd[0].$given);
$process1 = FlyFileStringProcessor(FLY_ROOT.$cmd[0].$given);
if ($process != false) {
} else if ($process1 != false) {
	$process = $process1;
} else {
	$process = false;
}

if ($process != false) {
	if ($process["action"]!=="") {
		FlyCommandExecute($process["action"]);
		FlyCommandDisplay('Opening "'.$process['name'].'"...');
	} else {
		if ($process['mime'] !== 'inode/x-empty') {
			if ($process['registered']) {
				//FlyCommandError('shell.dialog("No action specified",\'No action is specified for files of the type "'.htmlentities($process["description"]).'" ('.htmlentities($process["extension"]).').<br><small><pre>Fly error code 1d301</pre></small>\',"No Action","'.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg")');
				FlyCommandDisplay('No action is specified for files of the type "'.htmlentities($process["description"]).'" ('.htmlentities($process["type"]).'). (Fly error code 1d301)');
			} else {
				//FlyCommandError('shell.dialog("No action specified",\'No action is specified for files of the type "'.htmlentities($process["extension"]).'", but the system recognizes the MIME type "'.$process['mime'].'".<br><small><pre>Fly error code 1d300</pre></small>\',"No Action","'.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg")');
				FlyCommandDisplay('No action is specified for files of the type "'.$process["extension"].'", but the system recognizes the MIME type "'.$process['mime'].'. (Fly error code 1d300)');
			}
		} else {
			//FlyCommandError('shell.dialog("No action specified",\'No action is specified for files of the type "'.htmlentities($process["extension"]).'".<br><small><pre>Fly error code 1d300</pre></small>\',"No Action","'.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg")');
			FlyCommandDisplay('No action is specified for files of the type "'.$process["extension"].'". (Fly error code 1d300)');
		}
		FlyCommandError('system.command(\'run:SprocketComputers.Utilities.OpenWith,file='.$process['file'].'\');');
	}
} else {
	$notfound = str_replace($_SERVER['DOCUMENT_ROOT'],'.',$cmd[0]);
	FlyCommandError('shell.dialog("Item does not exist",\'The item "'.htmlentities($notfound).'" could not be found.<br><small><pre>Fly error code 1d200</pre></small>\',"Not Found","'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg")');
	FlyCommandDisplay('The item "'.$notfound.'" could not be found. (Fly error code 1d200)');
}
?>