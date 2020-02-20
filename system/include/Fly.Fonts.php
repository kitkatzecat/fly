<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.FONTS')) {
FlyIncludeRegister('FLY.FONTS');

// Loads a font installed in the fonts resources directory (FLY.RESOURCE.*.FONTS) (Uses folder ID, must get from FlyFontCheck)
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
				$css .= "/* font $name */\n";
				foreach ($json['resources'] as $style => $file) {
					$css .= '@font-face {font-family: \''.$json['name'].'\';src: url(\''.$_FLY['RESOURCE']['URL']['FONTS'].$name.'/'.$file.'\');';
					$css .= FlyFontStyleToCss($style);
					$css .= '}'."\n";
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

// Returns the information about a font as an array (from the font's font.json file)
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

// Checks if a font is installed, returns ID if is
function FlyFontCheck($name) {
	global $_FLY;

	$fonts = scandir($_FLY['RESOURCE']['PATH']['FONTS']);
	foreach ($fonts as $file) {
		if (is_dir($_FLY['RESOURCE']['PATH']['FONTS'].$file)) {
			if (file_exists($_FLY['RESOURCE']['PATH']['FONTS'].$file.'/font.json')) {
				$json = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['FONTS'].$file.'/font.json'),true);
				if ($json['name'] == $name) {
					return $file;
				}
			}
		}
	}
	
	return false;
}

// Returns the CSS font notation for a Fly font style
function FlyFontStyleToCss($style) {
	$css = '';
	$i = ['normal',	'bold','italic','bolditalic','oblique','condensed','expanded','100','200','300','400','500','600','700','800','900','italic100','italic200','italic300','italic400','italic500','italic600','italic700','italic800','italic900'];
	$o = ['font-style: normal;','font-weight: bold;','font-style: italic;','font-style: italic;font-weight: bold;','font-style: oblique;','font-stretch: condensed;','font-stretch: expanded;','font-weight: 100;','font-weight: 200;','font-weight: 300;','font-weight: 400;','font-weight: 500;','font-weight: 600;','font-weight: 700;','font-weight: 800;','font-weight: 900;','font-weight: 100;font-style: italic;','font-weight: 200;font-style: italic;','font-weight: 300;font-style: italic;','font-weight: 400;font-style: italic;','font-weight: 500;font-style: italic;','font-weight: 600;font-style: italic;','font-weight: 700;font-style: italic;','font-weight: 800;font-style: italic;','font-weight: 900;font-style: italic;',];
	$css = str_replace($i,$o,$style);
	return $css;
}

}
?>