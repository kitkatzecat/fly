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