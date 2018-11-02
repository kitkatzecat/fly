<?php
	$output = eval(implode(',',$cmd));
	FlyCommandDisplay($output);
	FlyCommandReturn($output);
	FlyCommandError('shell.dialog(\'PHP\',\'Please use the command prompt to view output from this command.\',\'PHP\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\')');
?>