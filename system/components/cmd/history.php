<?php
// Fly.SingleFileCommand
if (!$_FLY['IS_USER']) {
	FlyCommandDisplay('This command can only be used when a user is logged in.');
	FlyCommandReturn('false');
} else {

$HistoryPath = $_FLY['USER']['DATA'].'registry/root/public/ApplicationHistory';
$HistoryDate = date("Y").str_pad(date("z"),3,"0",STR_PAD_LEFT);
$HistoryPast = date("Y",strtotime('-60 days')).str_pad(date("z",strtotime('-60 days')),3,"0",STR_PAD_LEFT);

function HistoryClean(&$file,$date,$past) {
	if (isset($file[$date])) {
		$today = $file[$date];
	} else {
		$today = [];
	}
	foreach ($file as $day => &$apps) {
		if ($day < $past) {
			unset($file[$day]);
		} else {
			$apps = array_values(array_unique($apps));
			if ($day != $date) {
				$apps = array_values(array_diff($apps,$today));
			}
			if (sizeof($apps) == 0) {
				unset($file[$day]);
			}
		}
	}
}
function HistoryAdd(&$file,$id,$date) {
	if (!isset($file[$date])) {
		$file[$date] = [];
	}
	array_unshift($file[$date],$id);
}
function HistoryRemove(&$file,$id) {
	foreach ($file as $day => &$apps) {
		foreach($apps as $i => &$app) {
			if ($app == $id) {
				unset($apps[$i]);
			}
			array_values($apps);
		}
		if (sizeof($apps) == 0) {
			unset($file[$day]);
		}
	}
}
function HistoryWrite(&$file,$path) {
	file_put_contents($path,json_encode($file));
}

if (file_exists($HistoryPath)) {
	$HistoryFile = json_decode(file_get_contents($HistoryPath),true);
} else {
	$HistoryFile = [];
}

switch($ARGUMENTS[0]) {
	case 'add':
		// 0: "add", 1: id
		HistoryAdd($HistoryFile,$ARGUMENTS[1],$HistoryDate);
		HistoryClean($HistoryFile,$HistoryDate,$HistoryPast);
		HistoryWrite($HistoryFile,$HistoryPath);

		FlyCommandDisplay('Item "'.$ARGUMENTS[1].'" has been added to user '.$_FLY['USER']['NAME'].' ('.$_FLY['USER']['ID'].') \'s history and history has been cleaned.');
		FlyCommandReturn('true');
	break;

	case 'clean':
		// 0: "clean"
		HistoryClean($HistoryFile,$HistoryDate,$HistoryPast);
		HistoryWrite($HistoryFile,$HistoryPath);

		FlyCommandDisplay('History for user '.$_FLY['USER']['NAME'].' ('.$_FLY['USER']['ID'].') has been cleaned.');
		FlyCommandReturn('true');
	break;

	case 'remove':
		// 0: "remove", 1: id
		HistoryRemove($HistoryFile,$ARGUMENTS[1]);
		HistoryWrite($HistoryFile,$HistoryPath);

		FlyCommandDisplay('Item "'.$ARGUMENTS[1].'" has been removed from user '.$_FLY['USER']['NAME'].' ('.$_FLY['USER']['ID'].') \'s history.');
		FlyCommandReturn('true');
	break;

	case 'clear':
		// 0: "clear"
		$HistoryFile = [];
		HistoryWrite($HistoryFile,$HistoryPath);

		FlyCommandDisplay('History for user '.$_FLY['USER']['NAME'].' ('.$_FLY['USER']['ID'].') has been cleared.');
		FlyCommandReturn('true');
	break;

	default:
		FlyCommandDisplay('Invalid parameter! Expecting "add", "clean", "remove", or "clear".');
		FlyCommandReturn('false');
		FlyCommandError('shell.dialog(\'Invalid parameter\',\'history: Expecting "add", "clean", "remove", or "clear".\',\'Invalid Parameter\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\');');
}

}
?>