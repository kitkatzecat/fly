<?php
// Fly.SingleFileCommand
	$local_ip = getHostByName(getHostName());
	FlyCommandDisplay($local_ip);
	FlyCommandError('shell.dialog(\'IP address\',\'This device\\\'s IP address is '.$local_ip.'.\',\'IP Address\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
?>