<?php
// Fly.SingleFileCommand
	FlyCommandDisplay(date("g:i:s A e"));
	FlyCommandError('shell.dialog(\'Time\',\'The current time is '.date("g:i:s A e").'\',\'Time\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
?>