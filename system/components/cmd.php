<?php
include 'Fly.Session.php';
include 'Fly.Constants.php';
include 'Fly.Core.php';
include 'Fly.FileProcessor.php';

$FlyCommandVersion = '1.2';

$COMMAND_ECHO = '';
$COMMAND_DISPLAY = '';
$COMMAND_ERROR = '';
$COMMAND_RETURN = '';
function FlyCommandError($string) {
	global $COMMAND_ERROR;
	$COMMAND_ERROR .= $string.';';
}
function FlyCommandExecute($execute) {
	global $COMMAND_ECHO;
	$COMMAND_ECHO .= $execute.';';
}
function FlyCommandDisplay($string) {
	global $COMMAND_DISPLAY;
	$COMMAND_DISPLAY .= $string."\r\n";
}
function FlyCommandReturn($string,$overwrite=false) {
	global $COMMAND_RETURN;
	if ($overwrite) {
		$COMMAND_RETURN = $string;
	} else {
		$COMMAND_RETURN .= $string;
	}
}

if (!isset($FlyCommandIncluded)) {
if ($_GET['cmd']==="" || $_GET['cmd']=="undefined") {
	FlyCommandError('shell.dialog("No command","No command was received by the interpreter.<br><small><pre>Fly error code 1d100</pre></small>","Command Interpreter","'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg")');
	FlyCommandDisplay('No command was received by the interpreter.');
} else {
	$cmd = $_GET['cmd'];
	if ($_GET['req']=='true') {
		$_FLY = FlyCoreVars($_GET['cp']);
	}
	if ($_GET['silent'] == 'true') {
		$silent = true;
	} else {
		$silent = false;
	}
	if ($_GET['json']=='true') {
		$command = FlyCommand($cmd,false,false,$silent);
		echo $command['json'];
	} else {
		FlyCommand($cmd,true,true,$silent);
	}
}
}

function FlyCommand($cmd,$execute=false,$error=false,$silent=false) {
	global $COMMAND_ECHO;
	global $COMMAND_DISPLAY;
	global $COMMAND_ERROR;
	global $COMMAND_RETURN;
	global $_FLY;
	global $FlyCommandVersion;
	
	$COMMAND_VERSION = $FlyCommandVersion;
	
	if ($_FLY['IS_APP']) {
		$COMMAND_APP = $_FLY['APP']['ID']."\r\n";
	} else {
		$COMMAND_APP = '';
	}
	if (!$silent) {
		file_put_contents($_FLY['RESOURCE']['PATH']['SYSTEM'].'cmd.log',"\r\n"."\r\n".date("m-d-Y h:i:s A e")."\r\n".$COMMAND_APP.$cmd,FILE_APPEND);
	}
	
	$cmd = explode(':',$cmd,2);
	$do = $cmd[0];
	if (!empty($cmd[1])) {
		$cmd = FlyVarsReplace(FlyStringReplaceConstants($cmd[1]));
		$cmd = explode(',',$cmd);
	} else {
		$cmd = [];
	}

	$cmd = array_diff($cmd,[""]);
	
	$COMMAND = $cmd;
	$ARGUMENTS = $cmd;

	$process = FlyFileStringProcessor($do);
	
	if (file_exists($_FLY['RESOURCE']['PATH']['CMD'].$do.'.php')) {
		include $_FLY['RESOURCE']['PATH']['CMD'].$do.'.php';
	} else if ($process !== false && $process['type'] == 'application') {
		FlyCommandError('shell.dialog("Command not available",\'Using an application\\\'s name as a command is not yet available.<br><small><pre>Fly error code 1d500</pre></small>\',"Command Not Available","'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg")');
		FlyCommandDisplay('Using an application\'s name as a command is not yet available. (Fly error code 1d500)');
	} else {
		FlyCommandError('shell.dialog("Command not recognized",\'"'.htmlentities($do).'" is not a recognized command.<br><small><pre>Fly error code 1d400</pre></small>\',"Invalid Command","'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg")');
		FlyCommandDisplay('"'.$do.'" is not a recognized command. (Fly error code 1d400)');
	}
	ext:
	if (!$silent) {
		file_put_contents($_FLY['RESOURCE']['PATH']['SYSTEM'].'cmd.log',"\r\n".$COMMAND_ECHO."\r\n".$COMMAND_DISPLAY,FILE_APPEND);
	}
	if ($execute == true) {
		echo $COMMAND_ECHO;	
	}
	if ($error == true) {
		echo $COMMAND_ERROR;	
	}

	$COMMAND_RETURN = array(
		'command' => $do.':'.implode(',',$cmd),
		'arguments' => $cmd,
		'date' => (string)time(),
		'display' => $COMMAND_DISPLAY,
		'execute' => $COMMAND_ECHO,
		'error' => $COMMAND_ERROR,
		'return' => $COMMAND_RETURN,
	);
	$COMMAND_RETURN['json'] = json_encode($COMMAND_RETURN);
	return $COMMAND_RETURN;
}
	
	/*
	if ($do=="run") {
		for ($x = 1; $x < count($cmd); $x++) {
			$var = explode('=',$cmd[$x]);
			if ($var[1] == '') {
				$given .= ','.$var[0].'=true';
			} else {
				$given .= ','.$cmd[$x];
			}
		}
		$process = FlyFileStringProcessor($cmd[0].$given);
		$process1 = FlyFileStringProcessor(FLY_ROOT.$cmd[0].$given);
		if ($process != false) {
			if ($process["action"]!=="") {
				$echo = $process["action"];
			} else {
				$echo = 'shell.dialog("No action specified",\'No action is specified for files of the type "'.htmlentities($process["extension"]).'".<br><small><pre>Fly error code 1d300</pre></small>\',"No Action","'.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg")';
			}
		} else if ($process1 != false) {
			if ($process1["action"]!=="") {
				$echo = $process1["action"];
			} else {
				$echo = 'shell.dialog("No action specified",\'No action is specified for files of the type "'.htmlentities($process1["extension"]).'".<br><small><pre>Fly error code 1d300</pre></small>\',"No Action","'.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg")';
			}
		} else {
			$notfound = str_replace($_SERVER['DOCUMENT_ROOT'],'.',$cmd[0]);
			$echo = 'shell.dialog("Item does not exist",\'The item "'.htmlentities($notfound).'" could not be found.<br><small><pre>Fly error code 1d200</pre></small>\',"Not Found","'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg")';
		}
		goto ext;
	}
	
	if ($do=="info" || $do=="properties") {
		/*$process = FlyFileStringProcessor($cmd[0]);
		if($process != false) {
			if ($process["type"]=="file") {
				$filesize = '<br>Size: '.round((filesize($process["file"])/1000)).' KB';
			} else {
				$filesize = '';
			}
			$echo = 'shell.dialog(\'"'.htmlentities($process["name"]).'" Information\',"Type: '.htmlentities($process["type"]).'<br>Description: '.htmlentities($process["description"]).$filesize.'","Information","'.$process["icon"].'")';
		}
		$echo = 'system.FlyCommand(\'run:SprocketComputers.FileManager.Properties,file='.$cmd[0].'\')';
		goto ext;
	}
	
	if ($do=="nativeinfo") {
		$process = FlyFileStringProcessor($cmd[0]);
		if($process != false) {
			if ($process["type"]=="file") {
				$filesize = '<br>Size: '.round((filesize($process["file"])/1000)).' KB';
			} else {
				$filesize = '';
			}
			$echo = 'shell.dialog(\''.htmlentities($process["name"]).'\',"Type: '.htmlentities($process["type"]).'<br>Description: '.htmlentities($process["description"]).$filesize.'",\'Information - '.htmlentities($process["name"]).'\',"'.$process["icon"].'")';
		}
		goto ext;
	}
	
	if ($do=="rename") {
		$echo = 'system.FlyCommand(\'run:SprocketComputers.FileManager.Rename,file='.$cmd[0].'\')';
		goto ext;
	}
	
	if ($do=="delete") {
		$echo = 'system.FlyCommand(\'run:SprocketComputers.FileManager.Delete,file='.$cmd[0].'\')';
		goto ext;
	}
	
	if ($do=="exec") {
		$echo = implode(',',$cmd);
		$echo = FlyVarsReplace($echo);
		goto ext;
	}
	
	if ($do=="ver") {
		if ($_GET['log']=="" || $_GET['log']=="false" || $_GET['log']=="undefined") {
			$echo = 'shell.dialog("Version","Fly Command Interpreter '.$COMMAND_VERSION.'<br>'.$_FLY['VERSION_STRING'].' (Version '.$_FLY['VERSION'].')","Version","'.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg")';
		}
		goto ext;
	}
	
	if ($do=="flyver") {
		$echo = 'system.FlyCommand(\'run:SprocketComputers.Utilities.AboutFly\')';
		goto ext;
	}
	
	if ($do=="syscheck") {
		$echo = 'System connected.';
		goto ext;
	}
	
	if ($do=="clearlog") {
		unlink($_FLY['RESOURCE']['PATH']['SYSTEM'].'cmd.log');
		$echo = $_FLY['VERSION_STRING'].' (version '.$_FLY['VERSION_MAJOR'].' build '.$_FLY['VERSION_BUILD'].') - Logged in as '.$_FLY['USER']['NAME'].' ('.$_FLY['USER']['ID'].')';
		goto ext;
	}
	
	/*if ($do=="loadphpcomponent") {
		if (file_exists($_FLY['PATH'].'/system/components/'.$cmd[0].'.php')) {
			$file = file_get_contents($_FLY['PATH'].'/system/components/'.$cmd[0].'.php');
			$content = eval("QUESTIONMARK>$file");
			$echo = $content;			
		}
	}
	*/
	
?>