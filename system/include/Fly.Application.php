<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if ($_FLY['IS_APP']) {

FlyIncludeRegister('FLY.APPLICATION');

function FlyApplicationCheckAssociation($extension=false) {
	global $_FLY;
	
	$types = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['FILETYPES']),true);
	
	if (isset($types[$extension])) {
		if (isset($types[$extension]['action'])) {
			$app = explode('?',explode(',',$types[$extension]['action'])[0])[0];
		} else if (isset($types[$extension]['app'])) {
			$app = $types[$extension]['app'];
		} else {
			return false;
		}
		if ($app == $_FLY['APP']['ID']) {
			return true;
		}
	} else {
		return false;
	}
}

}
?>