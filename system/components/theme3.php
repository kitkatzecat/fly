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
			if (file_exists($_FLY['USER']['DATA'].'theme.thm')) {
				$file = $_FLY['USER']['DATA'].'theme.thm';
			} else {
				$file = $_FLY['RESOURCE']['PATH']['THEMES'].'default3.thm';
			}
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
		$THEME['SHADE_COLOR'] = '#ffffff';
		$THEME['SHADE_RGB'] = '255';
	} else {
		$THEME['SHADE_COLOR'] = '#000000';
		$THEME['SHADE_RGB'] = '0';
	}

	return([$json_raw, $THEME]);
}

function FlyThemeVarsReplace($string,$THEME=[]) {
	$pattern = '/(\%[A-Za-z1-9\.\_]*?\%)/';
	$matches = [];
	$return = $string;

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

	return $return;
}

function FlyThemeCSS($json,$THEME,$categories=['controls','text','toolbar','window','body'],$enclosure=true) {

	if (!function_exists('loopProperties')) {
		function loopProperties($array,&$print) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					$print .= "\t$key: {\n";
					loopProperties($value,$print);
					$print .= "\t}\n";
				} else {
					$print .= "\t$key: ".FlyVarsReplace($value).";\n";
				}
			}
		}
	}
	if (!function_exists('loopAnimation')) {
		function loopAnimation($array,&$print) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					$print .= "\t$key {\n";
					loopProperties($value,$print);
					$print .= "\t}\n";
				}
			}
		}
	}
	if (!function_exists('addRule')) {
		function addRule($name,$array,&$print) {
			$print .= "$name {\n";
			if (substr($name,0,10) == '@keyframes') {
				loopAnimation($array,$print);
			} else {
				loopProperties($array,$print);
			}
			$print .= "}\n";
		}
	}

	// Replace ThemeVars in raw json
	$json = FlyThemeVarsReplace($json,$THEME);

	$css = '';

	if ($enclosure) {
		$css .= '<style id="FlyStylesheet">'."\n";
	}

	$json = json_decode($json,true);

	foreach ($json['resources']['fonts'] as $font) {
		$css .= FlyFontLoad(FlyFontCheck($font),false,false)."\n";
	}

	$css .= "\n/* (font) */\n";
	addRule('*',['font-family'=>$THEME['FONTS']],$css);
	if (in_array('body',$categories)) {
		$css .= "\n/* body */\n";

		addRule('html',$json['style']['body']['html'],$css);
		addRule('body',$json['style']['body']['body'],$css);
	}

	// Remove large focus outlines
	$css .= 'input:focus,button:focus,select:focus,textarea:focus {outline:none;}';

	if (in_array('window',$categories)) {
		$css .= "\n/* window */\n";

		// .FlyWindow
		$css .= ".FlyWindow {}\n";

		addRule('@keyframes FlyWindowOpenAnimation',$json['style']['window']['animations']['open']['style'],$css);
		addRule('@keyframes FlyWindowCloseAnimation',$json['style']['window']['animations']['close']['style'],$css);
		addRule('@keyframes FlyWindowMinimizeAnimation',$json['style']['window']['animations']['minimize']['style'],$css);

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

		addRule('.FlyUiControlScaled',$json['style']['window']['control'],$css);
		addRule('.FlyUiControlNonScaled',$json['style']['window']['control'],$css);
	}
	if (in_array('toolbar',$categories)) {
		$css .= "\n/* toolbar */\n";

		addRule('.FlyToolbar',$json['style']['toolbar']['normal'],$css);

		addRule('.FlyUiTip',$json['style']['toolbar']['tip']['normal'],$css);

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
		addRule('button[disabled], input[type=submit][disabled], input[type=reset][disabled], input[type=button][disabled]',array_merge($json['style']['controls']['button']['normal'],$json['style']['controls']['button']['disabled']),$css);

		addRule('input[type=checkbox]',$json['style']['controls']['checkbox']['normal']['normal'],$css);
		addRule('input[type=checkbox]:hover',$json['style']['controls']['checkbox']['normal']['hover'],$css);
		addRule('input[type=checkbox]:active',$json['style']['controls']['checkbox']['normal']['active'],$css);
		addRule('input[type=checkbox]:checked',$json['style']['controls']['checkbox']['checked']['normal'],$css);
		addRule('input[type=checkbox]:checked:hover',$json['style']['controls']['checkbox']['checked']['hover'],$css);
		addRule('input[type=checkbox]:checked:active',$json['style']['controls']['checkbox']['checked']['active'],$css);
		
		addRule('input[type=text], input[type=password], input[type=date], input[type=time], input[type=number]',$json['style']['controls']['text']['normal'],$css);
		addRule('input[type=text]:hover, input[type=password]:hover, input[type=date]:hover, input[type=time]:hover, input[type=number]:hover',$json['style']['controls']['text']['hover'],$css);
		addRule('input[type=text]:active, input[type=password]:active, input[type=date]:active, input[type=time]:active, input[type=number]:active',$json['style']['controls']['text']['active'],$css);
		
		addRule('select',$json['style']['controls']['select']['normal'],$css);
		addRule('select:hover',$json['style']['controls']['select']['hover'],$css);
		addRule('select:active',$json['style']['controls']['select']['active'],$css);

		addRule('.FlyUiToolbarItem',$json['style']['controls']['toolbar_item']['normal'],$css);
		addRule('.FlyUiToolbarItem:hover',$json['style']['controls']['toolbar_item']['hover'],$css);
		addRule('.FlyUiToolbarItem:active',$json['style']['controls']['toolbar_item']['active'],$css);
		addRule('.FlyUiToolbarItemToggle',array_merge($json['style']['controls']['toolbar_item']['normal'],$json['style']['controls']['toolbar_item']['toggle']),$css);

		addRule('@keyframes FlyUiMenuAnimation',$json['style']['controls']['menu']['animations']['open']['style'],$css);
		addRule('.FlyUiMenu',array_merge($json['style']['controls']['menu']['normal'],['animation'=>'FlyUiMenuAnimation '.$json['style']['controls']['menu']['animations']['open']['length'].'s '.$json['style']['controls']['menu']['animations']['open']['timing'].' '.$json['style']['controls']['menu']['animations']['open']['delay'].'s '.$json['style']['controls']['menu']['animations']['open']['repeat']]),$css);
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

function FlyTheme($categories=['text','controls'],$echo=true,$enclosure=true,$file=false) {
	global $_FLY;

	$base = FlyLoadThemeFile($_FLY['RESOURCE']['PATH']['OS'].'base.thm');
	$base[0] = json_decode($base[0],true);
	$user = FlyLoadThemeFile($file);
	$user[0] = json_decode($user[0],true);

	$theme = [
		array_replace_recursive($base[0],$user[0]),
		array_replace($base[1],$user[1])
	];

	$theme[0] = json_encode($theme[0]);

	$css = FlyThemeCSS($theme[0],$theme[1],$categories,$enclosure);
	if ($echo) {
		echo $css;
	} else {
		return $css;
	}
}

}

/*
$a = FlyLoadThemeFile($_FLY['RESOURCE']['PATH']['THEMES'].'base.thm');
echo '<xmp>'.FlyThemeCSS($a[0],$a[1]);
*/