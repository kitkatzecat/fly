<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.FONTS')) {
FlyIncludeRegister('FLY.FONTS');

function FlyFontLoad($name,$echo=true,$enclosure=true) {
	global $_FLY;
	
	if (file_exists($_FLY['RESOURCE']['PATH']['FONTS'].$name)) {
		if (file_exists($_FLY['RESOURCE']['PATH']['FONTS'].$name.'/font.json')) {
			$json = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['FONTS'].$name.'/font.json'),true);
			if ($json) {
				$css = '';
				if ($enclosure) {
					$css .= '<style>';
				}
				foreach ($json['resources'] as $style => $file) {
					$css .= '@font-face {font-family: \''.$json['name'].'\';src: url(\''.$_FLY['RESOURCE']['URL']['FONTS'].$name.'/'.$file.'\');';
					$css .= FlyFontStyleToCss($style);
					$css .= '}'."\r\n";
				}
				if ($enclosure) {
					$css .= '</style>';
				}
				if ($echo) {
					echo $css;
				}
				return $css;
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function FlyFontInfo($name) {
	global $_FLY;
	
	if (file_exists($_FLY['RESOURCE']['PATH']['FONTS'].$name)) {
		if (file_exists($_FLY['RESOURCE']['PATH']['FONTS'].$name.'/font.json')) {
			$json = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['FONTS'].$name.'/font.json'),true);
			if ($json) {
				return $json;
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function FlyFontStyleToCss($style) {
	$css = '';
	if ($style == 'normal') {
		$css .= 'font-style: normal;';
	} else if ($style == 'bold') {
		$css .= 'font-weight: bold;';
	} else if ($style == 'italic') {
		$css .= 'font-style: italic;';
	} else if ($style == 'bolditalic') {
		$css .= 'font-style: italic;font-weight: bold;';
	} else if ($style == 'oblique') {
		$css .= 'font-style: oblique;';
	} else if ($style == 'condensed') {
		$css .= 'font-stretch: condensed;';
	} else if ($style == 'expanded') {
		$css .= 'font-stretch: expanded;';
	} else if ($style == '100') {
		$css .= 'font-weight: 100;';
	} else if ($style == '200') {
		$css .= 'font-weight: 200;';
	} else if ($style == '300') {
		$css .= 'font-weight: 300;';
	} else if ($style == '400') {
		$css .= 'font-weight: 400;';
	} else if ($style == '500') {
		$css .= 'font-weight: 500;';
	} else if ($style == '600') {
		$css .= 'font-weight: 600;';
	} else if ($style == '700') {
		$css .= 'font-weight: 700;';
	} else if ($style == '800') {
		$css .= 'font-weight: 800;';
	} else if ($style == '900') {
		$css .= 'font-weight: 900;';
	} else if ($style == 'italic100') {
		$css .= 'font-weight: 100;font-style: italic;';
	} else if ($style == 'italic200') {
		$css .= 'font-weight: 200;font-style: italic;';
	} else if ($style == 'italic300') {
		$css .= 'font-weight: 300;font-style: italic;';
	} else if ($style == 'italic400') {
		$css .= 'font-weight: 400;font-style: italic;';
	} else if ($style == 'italic500') {
		$css .= 'font-weight: 500;font-style: italic;';
	} else if ($style == 'italic600') {
		$css .= 'font-weight: 600;font-style: italic;';
	} else if ($style == 'italic700') {
		$css .= 'font-weight: 700;font-style: italic;';
	} else if ($style == 'italic800') {
		$css .= 'font-weight: 800;font-style: italic;';
	} else if ($style == 'italic900') {
		$css .= 'font-weight: 900;font-style: italic;';
	} else {}
	return $css;
}

}
?>