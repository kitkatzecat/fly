<?php
include 'Fly.Core.php';

if (!$_FLY['IS_USER']) {
	$ok = false;
	goto end;
}

$params = json_decode($_POST['content'],true);

$ok = true;

foreach ($params as $param) {
	if ($param['type'] == 'registry') {
		$app = $_FLY['APP']['ID'];
		if (isset($param['application'])) {
			$app = $param['application'];
		}

		$key = $param['key'];
		$value = $param['value'];
		$app = explode('.',$app,2);

		$path = $_FLY['USER']['DATA']."registry/$app[0]/$app[1]";

		if (!is_dir($path)) {
			mkdir($path,0777,true);
		}

		if (!file_put_contents("$path/$key",$value)) {
			$ok = false;
		}
	}
}

end:
if (!$ok) {
	http_response_code(500);
}
?>