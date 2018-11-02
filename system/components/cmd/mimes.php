<?php
include 'Fly.FileProcessor.php';

$process = FlyFileStringProcessor($cmd[0]);

if ($process != false && $process['type'] == 'application') {
	$p = simpleXML_load_file($process['manifest']);
	if (isset($p->mime)) {
		$mimes = explode(',',(string)$p->mime);
		$mime = '';
		foreach ($mimes as $m) {
			$mime .= $m.', ';
		}
		$mime = str_lreplace(', ','',$mime);
		FlyCommandDisplay($mime);
	}
} else {
	FlyCommandDisplay('Application not found or string provided is not an application');
}
?>