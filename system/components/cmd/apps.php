<?php

function match($s,$pattern) {
	if ($pattern !== "") {
		return (fnmatch($pattern,$s));
	} else {
		return true;
	}
}

$path = $_FLY['RESOURCE']['PATH']['APPS'];
$return = array();

$ignore = array('.', '..');

$dh = @opendir($path);

while (false !== ($file = readdir($dh))) {
	if (!in_array($file, $ignore)) {
		if (is_dir("$path/$file")) {
			if (match($file,$cmd[0])) {
				array_push($return,$file);
			}
			$xml = simpleXML_load_file($path.'/'.$file.'/ApplicationManifest.xml');
			if (isset($xml->masks)) {
				foreach ($xml->masks->children() as $mask) {
					if (match($file.'.'.(string)$mask['id'],$cmd[0])) {
						array_push($return,$file.'.'.(string)$mask['id']);
					}
				}
			}
		}
	}
}
closedir($dh);
asort($return);

$cmdreturn = '[';

FlyCommandDisplay('');
foreach ($return as $a) {
	FlyCommandDisplay($a);
	$cmdreturn .= '"'.$a.'",';
}

$cmdreturn = str_lreplace(',','',$cmdreturn);
$cmdreturn .= ']';

FlyCommandReturn($cmdreturn,true);
?>