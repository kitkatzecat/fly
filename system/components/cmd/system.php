<?php
	$output = shell_exec(implode(',',$cmd));
	FlyCommandDisplay($output);
	FlyCommandError('shell.dialog(\'System\',\'Please use the command prompt to view output from this command.\',\'System\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\')');
?>