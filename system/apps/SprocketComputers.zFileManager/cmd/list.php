<?php
// Fly.SingleFileCommand

$return = [
	'status' => false,
	'message' => 'Unknown error'
];

$path = FlyVarsReplace($ARGUMENTS[0]);

if (file_exists($path)) {
	$contents = scandir($path,SCANDIR_SORT_ASCENDING);
	$contents = array_diff($contents, ['..', '.']);
	$contents = array_values($contents);

	$folders = [];
	$files = [];

	foreach ($contents as $item) {
		if (is_dir("$path/$item")) {
			array_push($folders,$item);
		} else {
			array_push($files,$item);
		}
	}

	if ($ARGUMENTS['sort'] == 'mime') {
		natcasesort($folders);

		$files_mime = [];
		foreach ($files as $i => $file) {
			$mime = mime_content_type("$path/$file");
			$files_mime[$i] = $mime;
		}
		natcasesort($files_mime);
		$files_new = [];
		foreach ($files_mime as $file => $mime) {
			array_push($files_new,$files[$file]);
		}
		$files = $files_new;
	} else {
		natcasesort($folders);
		natcasesort($files);
	}

	$contents = array_merge($folders,$files);

	$return = json_encode($contents);
	goto r;
} else {
	$return['message'] = 'Cannot find directory "'.$path.'"';
	goto r;
}

r:
FlyCommandReturn($return,true);
FlyCommandDisplay((is_array($return) ? $return['message'] : $return));
?>