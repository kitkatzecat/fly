<?php
// Fly.SingleFileCommand
	try {
		$output = eval(implode(',',$cmd));
	} catch(ParseError $e) {
		$output = 'PHP Parse Error: '.$e->getMessage();
	}
	FlyCommandDisplay($output);
	FlyCommandReturn($output);
	FlyCommandError('shell.dialog(\'PHP\',\'Please use the command prompt to view output from this command.\',\'PHP\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\')');
?>