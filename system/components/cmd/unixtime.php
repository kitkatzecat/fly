<?php
	FlyCommandDisplay(date("U"));
	FlyCommandError('shell.dialog(\'Unix Time\',\'The current Unix time is '.date("U").'\',\'Unix Time\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
?>