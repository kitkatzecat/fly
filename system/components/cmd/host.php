<?php
// Fly.SingleFileCommand
	FlyCommandDisplay($_FLY['URL']);
	FlyCommandError('shell.dialog(\'Host address\',\'The current host address is '.$_FLY['URL'].'.\',\'Host Address\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
?>