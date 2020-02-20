<?php
if (!function_exists('FlyLoadThemeFile')) {

if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.FONTS')) {
	include 'Fly.Fonts.php';
}


// Load a Fly Theme file, takes a theme filename, returns array with [0] theme array, [1] $THEME ThemeVars
function FlyLoadThemeFile($file = false) {
	global $_FLY;

	// If no file is specified, load the user's theme file; if no user logged in, load default theme
	if (!$file) {
		if ($_FLY['IS_USER']) {
			$file = $_FLY['USER']['DATA'].'theme.thm';
		} else {
			$file = $_FLY['RESOURCE']['PATH']['THEMES'].'default3.thm';
		}
	}

	// Get raw content of theme file (store this, we'll use it later), replace FlyVars
	$json_raw = file_get_contents($file);
	$json_raw = FlyVarsReplace($json_raw,true);
	// Parse theme file (first time, no ThemeVars replacement)
	$json_parsed = json_decode($json_raw,true);

	// Object for ThemeVars
	$THEME = [];

	// Create ThemeVars FONT property (font family)
	$THEME['FONTS'] = '';
	foreach ($json_parsed['resources']['fonts'] as $font) {
		$THEME['FONTS'] .= $font.', ';
	}
	$THEME['FONTS'] .= 'sans-serif';

	// Create ThemeVars BACKGROUND property (background image)
	$THEME['BACKGROUND'] = FlyVarsReplace($json_parsed['resources']['background'],true);

	// Create ThemeVars BACKGROUND_COLOR property (#rrggbb)
	$THEME['BACKGROUND_COLOR'] = '#'.str_pad(dechex($json_parsed['resources']['background_color'][0]),2,"0",STR_PAD_LEFT).str_pad(dechex($json_parsed['resources']['background_color'][1]),2,"0",STR_PAD_LEFT).str_pad(dechex($json_parsed['resources']['background_color'][2]),2,"0",STR_PAD_LEFT);

	// Create ThemeVars COLOR property (rgba color, a is transparency)
	$THEME['COLOR'] = 'rgba('.$json_parsed['resources']['color'][0].','.$json_parsed['resources']['color'][1].','.$json_parsed['resources']['color'][2].','.$json_parsed['resources']['transparency'].')';

	// Create ThemeVars COLOR_OPAQUE property (#rrggbb color, transparency value omitted)
	$THEME['COLOR_OPAQUE'] = '#'.str_pad(dechex($json_parsed['resources']['color'][0]),2,"0",STR_PAD_LEFT).str_pad(dechex($json_parsed['resources']['color'][1]),2,"0",STR_PAD_LEFT).str_pad(dechex($json_parsed['resources']['color'][2]),2,"0",STR_PAD_LEFT);

	// Create ThemeVars TRANSPARENCY property (transparency value, 0-1)
	$THEME['TRANSPARENCY'] = $json_parsed['resources']['transparency'];

	// Create ThemeVars DESKTOP_OVERFLOW property (desktop scrolling) ---- NEED TO MAKE LOAD FROM USER REGISTRY/CONFIG
	$THEME['DESKTOP_OVERFLOW'] = 'hidden';

	// Create ThemeVars SHADE_RGB and SHADE_COLOR properties (used for shadows, etc)
	if ( (0.2126*$json_parsed['resources']['color'][0] + 0.7152*$json_parsed['resources']['color'][1] + 0.0722*$json_parsed['resources']['color'][2]) < 128 && $json_parsed['resources']['transparency'] > 0.2) {
		$THEME['SHADE_RGB'] = '#ffffff';
		$THEME['SHADE_COLOR'] = '255';
	} else {
		$THEME['SHADE_RGB'] = '#000000';
		$THEME['SHADE_COLOR'] = '0';
	}

	// Replace ThemeVars in raw json
	$pattern = '/(\%.[^ ]*?\%)/';
	$matches = [];
	$return = $json_raw;

	preg_match_all($pattern,$return,$matches);
	foreach ($matches[0] as $m) {
		$m = substr($m,1,-1);
		$ma = explode('.',$m);
		if ($ma[0] == 'THEME') {
			array_shift($ma);
			$var = '$THEME';
			foreach($ma as $a) {
				$var .= '[\''.$a.'\']';
			}
			if (eval('return '.$var.';') !== null) {
			    $return = str_replace('%'.$m.'%',eval('return '.$var.';'),$return);
			}
		}
	}

	return([$return, $THEME]);
}

function FlyThemeCSS($json,$THEME,$categories=['controls','text','toolbar','window','body'],$enclosure=true) {
	function loopProperties($array,&$print) {
		foreach ($array as $key => $value) {
			$print .= "\t$key: $value;\n";
		}
	}
	function addRule($name,$array,&$print) {
		$print .= "$name {\n";
		loopProperties($array,$print);
		$print .= "}\n";
	}

	$css = '';

	if ($enclosure) {
		$css .= '<style id="FlyStylesheet">'."\n";
	}

	$json = json_decode($json,true);

	foreach ($json['resources']['fonts'] as $font) {
		$css .= FlyFontLoad(FlyFontCheck($font),false,false)."\n";
	}

	if (in_array('body',$categories)) {
		$css .= "\n/* body */\n";

		addRule('html',$json['style']['body']['html'],$css);
		addRule('body',$json['style']['body']['body'],$css);
	}
	if (in_array('window',$categories)) {
		$css .= "\n/* window */\n";

		// .FlyWindow
		$css .= ".FlyWindow {}\n";

		addRule('.FlyWindowActive',$json['style']['window']['active'],$css);
		addRule('.FlyWindowInactive',$json['style']['window']['inactive'],$css);
		addRule('.FlyWindowTransparent',$json['style']['window']['transparent'],$css);
		addRule('.FlyWindowExpand',$json['style']['window']['expand'],$css);

		addRule('.FlyWindowTitleActive',$json['style']['window']['titlebar']['active']['normal'],$css);
		addRule('.FlyWindowTitleActive:hover',$json['style']['window']['titlebar']['active']['hover'],$css);
		addRule('.FlyWindowTitleActive:active',$json['style']['window']['titlebar']['active']['active'],$css);
		addRule('.FlyWindowTitleInactive',$json['style']['window']['titlebar']['inactive']['normal'],$css);
		addRule('.FlyWindowTitleInactive:hover',$json['style']['window']['titlebar']['inactive']['hover'],$css);
		addRule('.FlyWindowTitleInactive:active',$json['style']['window']['titlebar']['inactive']['active'],$css);

		addRule('.FlyWindowTitleRowActive',$json['style']['window']['titlerow']['active'],$css);
		addRule('.FlyWindowTitleRowInactive',$json['style']['window']['titlerow']['inactive'],$css);

		addRule('.FlyWindowButtonActive',$json['style']['window']['button']['active']['normal'],$css);
		addRule('.FlyWindowButtonActive:hover',$json['style']['window']['button']['active']['hover'],$css);
		addRule('.FlyWindowButtonActive:active',$json['style']['window']['button']['active']['active'],$css);
		addRule('.FlyWindowButtonInactive',$json['style']['window']['button']['inactive']['normal'],$css);
		addRule('.FlyWindowButtonInactive:hover',$json['style']['window']['button']['inactive']['hover'],$css);
		addRule('.FlyWindowButtonInactive:active',$json['style']['window']['button']['inactive']['active'],$css);

		// ------------------ Window animations?

		addRule('.FlyUiControlScaled',$json['style']['window']['control'],$css);
		addRule('.FlyUiControlNonScaled',$json['style']['window']['control'],$css);
	}
	if (in_array('toolbar',$categories)) {
		$css .= "\n/* toolbar */\n";

		addRule('.FlyToolbar',$json['style']['toolbar']['normal'],$css);

		addRule('.FlyUiTip',$json['style']['toolbar']['tip'],$css);

		addRule('.FlyTrayIcon',$json['style']['toolbar']['tray_icon']['normal'],$css);
		addRule('.FlyTrayIcon:hover',$json['style']['toolbar']['tray_icon']['hover'],$css);
		addRule('.FlyTrayIcon:active',$json['style']['toolbar']['tray_icon']['active'],$css);
		// ------------------ Tray icon animations?

		addRule('.FlyJumpButton',$json['style']['toolbar']['jump_button']['normal'],$css);
		addRule('.FlyJumpButton:hover',$json['style']['toolbar']['jump_button']['hover'],$css);
		addRule('.FlyJumpButton:active',$json['style']['toolbar']['jump_button']['active'],$css);
	}
	if (in_array('text',$categories)) {
		$css .= "\n/* text */\n";
		
		addRule('.FlyUiContent',$json['style']['text']['content'],$css);

		addRule('.FlyUiTextHighlight',$json['style']['text']['highlight']['normal'],$css);
		addRule('.FlyUiTextHighlightControl',$json['style']['text']['highlight']['control'],$css);

		addRule('.FlyUiTextHover',$json['style']['text']['hover']['normal']['normal'],$css);
		addRule('.FlyUiTextHover:hover',$json['style']['text']['hover']['normal']['hover'],$css);
		addRule('.FlyUiTextHover:active',$json['style']['text']['hover']['normal']['active'],$css);
		addRule('.FlyUiTextHoverControl',$json['style']['text']['hover']['control']['normal'],$css);
		addRule('.FlyUiTextHoverControl:hover',$json['style']['text']['hover']['control']['hover'],$css);
		addRule('.FlyUiTextHoverControl:active',$json['style']['text']['hover']['control']['active'],$css);

		addRule('.FlyUiText',$json['style']['text']['normal'],$css);
		addRule('.FlyUiNoSelect',$json['style']['text']['noselect'],$css);

		addRule('a',$json['style']['text']['link']['normal'],$css);
		addRule('a:hover',$json['style']['text']['link']['hover'],$css);
		addRule('a:active',$json['style']['text']['link']['active'],$css);
		addRule('a:visited',$json['style']['text']['link']['visited'],$css);
	}
	if (in_array('controls',$categories)) {
		$css .= "\n/* controls */\n";

		addRule('button, input[type=submit], input[type=reset], input[type=button]',$json['style']['controls']['button']['normal'],$css);
		addRule('button:hover, input[type=submit]:hover, input[type=reset]:hover, input[type=button]:hover',$json['style']['controls']['button']['hover'],$css);
		addRule('button:active, input[type=submit]:active, input[type=reset]:active, input[type=button]:active',$json['style']['controls']['button']['active'],$css);
		
		addRule('input[type=text], input[type=password]',$json['style']['controls']['text']['normal'],$css);
		addRule('input[type=text]:hover, input[type=password]:hover',$json['style']['controls']['text']['hover'],$css);
		addRule('input[type=text]:active, input[type=password]:active',$json['style']['controls']['text']['active'],$css);
		
		addRule('select',$json['style']['controls']['select']['normal'],$css);
		addRule('select:hover',$json['style']['controls']['select']['hover'],$css);
		addRule('select:active',$json['style']['controls']['select']['active'],$css);

		addRule('.FlyUiToolbarItem',$json['style']['controls']['toolbar_item']['normal'],$css);
		addRule('.FlyUiToolbarItem:hover',$json['style']['controls']['toolbar_item']['hover'],$css);
		addRule('.FlyUiToolbarItem:active',$json['style']['controls']['toolbar_item']['active'],$css);
		addRule('.FlyUiToolbarItemToggle',array_merge($json['style']['controls']['toolbar_item']['normal'],$json['style']['controls']['toolbar_item']['toggle']),$css);

		addRule('.FlyUiMenu',$json['style']['controls']['menu']['normal'],$css);
		addRule('.FlyUiMenuItem',$json['style']['controls']['menu_item']['normal']['normal'],$css);
		addRule('.FlyUiMenuItem:hover',$json['style']['controls']['menu_item']['normal']['hover'],$css);
		addRule('.FlyUiMenuItem:active',$json['style']['controls']['menu_item']['normal']['active'],$css);
		addRule('.FlyUiMenuItemDisabled',array_merge($json['style']['controls']['menu_item']['normal']['normal'],$json['style']['controls']['menu_item']['disabled']['normal']),$css);
		addRule('.FlyUiMenuItemDisabled:hover',array_merge($json['style']['controls']['menu_item']['normal']['hover'],$json['style']['controls']['menu_item']['disabled']['hover']),$css);
		addRule('.FlyUiMenuItemDisabled:active',array_merge($json['style']['controls']['menu_item']['normal']['active'],$json['style']['controls']['menu_item']['disabled']['active']),$css);
		addRule('.FlyUiMenuItemActive',array_merge($json['style']['controls']['menu_item']['normal']['normal'],$json['style']['controls']['menu_item']['active']['normal']),$css);
		addRule('.FlyUiMenuItemActive:hover',array_merge($json['style']['controls']['menu_item']['normal']['hover'],$json['style']['controls']['menu_item']['active']['hover']),$css);
		addRule('.FlyUiMenuItemActive:active',array_merge($json['style']['controls']['menu_item']['normal']['active'],$json['style']['controls']['menu_item']['active']['active']),$css);
	
		addRule('.FlyUiProgressWaitContainer',$json['style']['controls']['progress_wait']['container'],$css);
		addRule('.FlyUiProgressBar',$json['style']['controls']['progress_wait']['progress'],$css);
		addRule('.FlyUiWaitBar',$json['style']['controls']['progress_wait']['wait'],$css);
	}

	if ($enclosure) {
		$css .= '</style>';
	}

	return $css;

}

}

/*
$a = FlyLoadThemeFile($_FLY['RESOURCE']['PATH']['THEMES'].'base.thm');
echo '<xmp>'.FlyThemeCSS($a[0],$a[1]);
*/