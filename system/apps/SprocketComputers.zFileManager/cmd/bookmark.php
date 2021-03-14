<?php
// Fly.SingleFileCommand

$return = [
	'status' => false,
	'message' => 'Unknown error'
];

$bookmarks_path = $ARGUMENTS['path'];

if (file_exists($bookmarks_path)) {
	$bookmarks = file_get_contents($bookmarks_path);
	$bookmarks = json_decode($bookmarks,true);
} else {
	$bookmarks = [];
}

if ($ARGUMENTS['mode'] == 'add') {
	array_push($bookmarks,$ARGUMENTS[0]);
	$return['message'] = '"'.$ARGUMENTS[0].'" has been added to bookmarks';
} else if ($ARGUMENTS['mode'] == 'remove') {
	if (($key = array_search($ARGUMENTS[0], $bookmarks)) !== false) {
		unset($bookmarks[$key]);
		$bookmarks = array_values($bookmarks);
		$return['message'] = '"'.$ARGUMENTS[0].'" has been removed from bookmarks';
	} else {
		$return['message'] = '"'.$ARGUMENTS[0].'" does not exist in bookmarks';
		goto r;
	}
} else {
	$return['message'] = 'Unknown mode: '.$ARGUMENTS['mode'];
	goto r;
}

if (file_put_contents($bookmarks_path,json_encode($bookmarks))) {
	$return['status'] = true;
	goto r;
} else {
	$return['message'] = 'Unable to save bookmarks file';
	goto r;
}

r:
FlyCommandReturn($return,true);
FlyCommandDisplay($return['message']);
?>