<?php

include 'Fly.Registry.php';
include 'Fly.FileProcessor.php';

switch($cmd[0]) {
	case 'get':
		// 0: "get", 1: key, 2: applicaition
		$value = FlyRegistryGet($cmd[1],$cmd[2]);
		if (!$value) {
			$value = 'false';
		}
		FlyCommandDisplay($value);
		FlyCommandReturn($value);
		break;
	
	case 'gget':
		$value = FlyGlobalRegistryGet($cmd[1],$cmd[2]);
		if (!$value) {
			$value = 'false';
		}
		FlyCommandDisplay($value);
		FlyCommandReturn($value);
		break;

	case 'set':
		// 0: "set", 1: key, 2: value
		$return = FlyRegistrySet($cmd[1],$cmd[2]);
		if ($return) {
			FlyCommandDisplay('User registry key "'.$cmd[1].'" has been set to value "'.$cmd[2].'" for application "'.$_FLY['APP']['ID'].'" for user "'.$_FLY['USER']['NAME'].'" ('.$_FLY['USER']['ID'].').');
			FlyCommandReturn('true');
		} else {
			FlyCommandDisplay('An error occurred while setting the registry key.');
			FlyCommandReturn('false');
		}
		break;

	case 'gset':
		$return = FlyGlobalRegistrySet($cmd[1],$cmd[2]);
		if ($return) {
			FlyCommandDisplay('Global registry key "'.$cmd[1].'" has been set to value "'.$cmd[2].'" for application "'.$_FLY['APP']['ID'].'".');
			FlyCommandReturn('true');
		} else {
			FlyCommandDisplay('An error occurred while setting the registry key.');
			FlyCommandReturn('false');
		}
		break;

	case 'remove':
		// 0: "remove", 1: key, 2: application
		$return = FlyRegistryRemove($cmd[1],$cmd[2]);
		if (empty($cmd[2])) {
			$app = $_FLY['APP']['ID'];
		} else {
			$app = $cmd[2];
		}
		if ($return) {
			FlyCommandDisplay('User registry key "'.$cmd[1].'" has been removed from application "'.$app.'" for user "'.$_FLY['USER']['NAME'].'" ('.$_FLY['USER']['ID'].').');
			FlyCommandReturn('true');
		} else {
			FlyCommandDisplay('An error occurred while removing the registry key.');
			FlyCommandReturn('false');
		}
		break;


	case 'gremove':
		$return = FlyGlobalRegistryRemove($cmd[1],$cmd[2]);
		if (empty($cmd[2])) {
			$app = $_FLY['APP']['ID'];
		} else {
			$app = $cmd[2];
		}
		if ($return) {
			FlyCommandDisplay('Global registry key "'.$cmd[1].'" has been removed from application "'.$app.'".');
			FlyCommandReturn('true');
		} else {
			FlyCommandDisplay('An error occurred while removing the registry key.');
			FlyCommandReturn('false');
		}
		break;

	case 'listkeys':
		// 0: "listkeys", 1: application
		$keys = FlyRegistryListKeys($cmd[1]);
		if (empty($cmd[1])) {
			$app = $_FLY['APP']['ID'];
		} else {
			$app = $cmd[1];
		}
		if ($keys) {
			FlyCommandReturn(json_encode($keys));
			$display = '';
			foreach ($keys as $k) {
				$display .= $k.', ';
			}
			$display = str_lreplace(', ','',$display);
			FlyCommandDisplay($display);
		} else {
			FlyCommandDisplay('An error occurred while retrieving the user registry keys for the application "'.$app.'" for user "'.$_FLY['USER']['NAME'].'" ('.$_FLY['USER']['ID'].').');
			FlyCommandReturn('false');
		}
		break;

	case 'glistkeys':
		$keys = FlyGlobalRegistryListKeys($cmd[1]);
		if (empty($cmd[1])) {
			$app = $_FLY['APP']['ID'];
		} else {
			$app = $cmd[1];
		}
		if ($keys) {
			FlyCommandReturn(json_encode($keys));
			$display = '';
			foreach ($keys as $k) {
				$display .= $k.', ';
			}
			$display = str_lreplace(', ','',$display);
			FlyCommandDisplay($display);
		} else {
			FlyCommandDisplay('An error occurred while retrieving the global registry keys for the application "'.$app.'" for user "'.$_FLY['USER']['NAME'].'" ('.$_FLY['USER']['ID'].').');
			FlyCommandReturn('false');
		}
		break;

		case 'getkeys':
		// 0: "getkeys", 1: application
		$keys = FlyRegistryGetKeys($cmd[1]);
		if (empty($cmd[1])) {
			$app = $_FLY['APP']['ID'];
		} else {
			$app = $cmd[1];
		}
		if ($keys) {
			FlyCommandReturn(json_encode($keys));
			$display = str_freplace('Array',$cmd[1],print_r($keys,true));
			FlyCommandDisplay($display);
		} else {
			FlyCommandDisplay('An error occurred while retrieving the user registry keys for the application "'.$app.'" for user "'.$_FLY['USER']['NAME'].'" ('.$_FLY['USER']['ID'].').');
			FlyCommandReturn('false');
		}
		break;

	case 'ggetkeys':
		$keys = FlyGlobalRegistryGetKeys($cmd[1]);
		if (empty($cmd[1])) {
			$app = $_FLY['APP']['ID'];
		} else {
			$app = $cmd[1];
		}
		if ($keys) {
			foreach ($keys as $k => $v) {
				if ($v == 'true') {
					$v = true;
				}
				if ($v == 'false') {
					$v = false;
				}
			}
			FlyCommandReturn(json_encode($keys));
			$display = str_freplace('Array',$cmd[1],print_r($keys,true));
			FlyCommandDisplay($display);
		} else {
			FlyCommandDisplay('An error occurred while retrieving the global registry keys for the application "'.$app.'" for user "'.$_FLY['USER']['NAME'].'" ('.$_FLY['USER']['ID'].').');
			FlyCommandReturn('false');
		}
		break;

	default:
		FlyCommandDisplay('Invalid parameter! Expecting "get", "set", "remove", "getkeys", "listkeys", "gget", "gset", "gremove", "ggetkeys", or "glistkeys".');
		FlyCommandReturn('false');
		FlyCommandError('shell.dialog(\'Invalid parameter\',\'registry: Expecting "get", "set", "remove", "getkeys", "listkeys", "gget", "gset", "gremove", "ggetkeys", or "glistkeys".\',\'Invalid Parameter\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\');');
}

?>