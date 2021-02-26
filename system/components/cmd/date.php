<?php
// Fly.SingleFileCommand
	FlyCommandDisplay(date("j F Y"));
	FlyCommandError('shell.dialog(\'Date\',\'Today\'s date is '.date("j F Y").'\',\'Date\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
?>