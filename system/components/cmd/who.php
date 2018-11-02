<?php
	if ($_FLY['IS_USER'] == true) {
		FlyCommandError('shell.dialog(\'Who\',\'Logged in as '.$_FLY['USER']['NAME'].' ('.$_FLY['USER']['ID'].')\',\'Who\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
		FlyCommandDisplay('Logged in as '.$_FLY['USER']['NAME'].' ('.$_FLY['USER']['ID'].')');
		FlyCommandReturn($_FLY['USER']['ID']);
	} else {
		FlyCommandError('shell.dialog(\'Who\',\'Not logged in\',\'Who\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
		FlyCommandDisplay('Not logged in');
		FlyCommandReturn('');
	}
?>