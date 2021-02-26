<?php
// Fly.SingleFileCommand
function allVars($array,$str) {
	$return = '';
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			$string = $str.$key.'.';
			$return .= allVars($value,$string);
		} else {
			if ($str !== '%FLY.INCLUDES.') {
				if ($str == '%FLY.APP.' || $str == '%FLY.USER.') {
					$return .= $str.$key.'% (may not be available in all situations)'."\r\n";
				} else {
					$return .= $str.$key.'%'."\r\n";
				}
			}
		}
	}
	return $return;
}

FlyCommandDisplay(allVars($_FLY,'%FLY.'));
?>