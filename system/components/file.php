<?php
include 'Fly.Core.php';

$status = 'false';
$message = '';

if ($_GET['d'] == 'read') {
	$json = json_decode($_POST['content'],true);

	$file = FlyVarsReplace($json['file']);

	if (!file_exists($file)) {
		http_response_code(404);
		exit;
	}

	if ($json['method'] == 'base64') {
		$content = file_get_contents($file);
		if (!$content) {
			http_response_code(500);
		} else {
			$content = base64_encode($content);
			if (!$content) {
				http_response_code(500);
			} else {
				echo $content;
			}
		}
		exit;
	} else if ($json['method'] == 'text') {
		$content = file_get_contents($file);
		if (!$content) {
			http_response_code(500);
		} else {
			echo $content;
		}
		exit;
	} else {
		http_response_code(400);
		exit;
	}
exit;
}

if ($_GET['d'] == 'write') {
	$json = json_decode($_POST['content'],true);

	$file = FlyVarsReplace($json['file']);

	if (file_exists($file) && $json['overwrite'] != true) {
		$message = 'File exists';
		goto end;
	}

	if (!is_dir(str_lreplace(basename($file),'',$file))) {
		$message = 'Parent directory does not exist';
		goto end;
	}

	if ($json['method'] == 'base64') {
		$content = str_replace(' ','+',$json['content']);
		$content = preg_replace('/data:([A-z])+\\/([A-z]|:|-|\+)+;base64,/','',$content);
		$data = base64_decode($content,true);
		if ($data == false) {
			$message = 'Invalid base64 data';
			goto end;
		}
		$write = file_put_contents($file,$data);
		if ($write == false) {
			$message = 'Write error';
			goto end;
		}
		$status = 'true';
		$message = 'Success';
		goto end;

	} else if ($json['method'] == 'text') {
		$write = file_put_contents($file,$json['content']);
		if ($write == false) {
			$message = 'Write error';
			goto end;
		}
		$status = 'true';
		$message = 'Success';
		goto end;
	} else {
		$message = 'Unsupported method';
		goto end;
	}
}

end:
echo '{"status":'.$status.',"message":"'.$message.'"}';
exit;
?>