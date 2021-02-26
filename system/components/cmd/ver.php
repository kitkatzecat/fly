<?php
// Fly.SingleFileCommand
if ($_GET['log']=="" || $_GET['log']=="false" || $_GET['log']=="undefined") {
	FlyCommandError('shell.dialog("Version","Fly Command Interpreter '.$FlyCommandVersion.'<br>'.$_FLY['VERSION_STRING'].' (Version '.$_FLY['VERSION'].')","Version","'.FLY_ICONS_URL.'info.svg")');
	FlyCommandDisplay('Fly Command Interpreter '.$FlyCommandVersion);
	FlyCommandDisplay($_FLY['VERSION_STRING']);
}
?>