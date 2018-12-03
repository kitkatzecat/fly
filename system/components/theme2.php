<?php
// theme.php - Fly Theme loader 2
// body,window,text,toolbar,controls
if (!function_exists(FlyLoadTheme)) {
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.FONTS')) {
	include 'Fly.Fonts.php';
}
function FlyLoadTheme($settings='all',$xml=false,$enclosure=true) {
	global $_FLY;
	if ($xml == false) {
		if ($_FLY['USER']) {
			$config = simpleXML_load_file($_FLY['RESOURCE']['PATH']['USERS'].$_FLY['USER']['ID'].'/data/user.xml');
		} else {
			$config = simpleXML_load_file($_FLY['RESOURCE']['PATH']['RESOURCES'].'/os/user.xml');
		}
	} else {
		$config = $xml;
	}
	if (file_exists(FlyVarsReplace($config->visual->theme->url))) {
		$theme = simpleXML_load_file(FlyVarsReplace($config->visual->theme->url));
	} else {
		$theme = simpleXML_load_file($_FLY['RESOURCE']['PATH']['THEMES'].'/default.thm');
	}
	$window_color = 'rgba('.$config->visual->theme->color->r.','.$config->visual->theme->color->g.','.$config->visual->theme->color->b.','.$config->visual->theme->color->a.')';
	$r = dechex($config->visual->theme->color->r);
	$g = dechex($config->visual->theme->color->g);
	$b = dechex($config->visual->theme->color->b);
	if (strlen($r)<2) {
		$r = "0".$r;
	}
	if (strlen($g)<2) {
		$g = "0".$g;
	}
	if (strlen($b)<2) {
		$b = "0".$b;
	}
	$window_opaque = '#'.$r.$g.$b;
	if ($config->visual->theme->transparency == "false") {
		$window_color = $window_opaque;
	}
	$ui_scale = $config->visual->scale;
	
	$y = 0.2126*$config->visual->theme->color->r + 0.7152*$config->visual->theme->color->g + 0.0722*$config->visual->theme->color->b;
	if ($y < 128 && $config->visual->theme->color->a > 0.2) {
		$light = '#FFFFFF';
		$light_rgb = '255';
	} else {
		$light = '#000000';
		$light_rgb = '0';
	}
	
	if ($_FLY['IS_USER']) {
		if (in_array(file_get_contents($_FLY['USER']['DATA_PATH'].'registry/SprocketComputers/Options/DesktopAllowScrolling'),['true','on','yes'])) {
			$bodyOverflow = 'auto';
		} else {
			$bodyOverflow = 'hidden';
		}
	} else {
		$bodyOverflow = 'hidden';
	}
	
	$vars = ['%shade_rgb%','%scale_value%','%shade_color%','%opaque_color%','%transparent_color%'];
	$values = [$light_rgb,$ui_scale,$light,$window_opaque,$window_color];

if ($enclosure) {
	echo '
<style id="FlyStylesheet">
::selection {
	background: rgb(153, 204, 255);
}
* {
	font-family: sans-serif;
}
';
}

// Checks if fonts on theme's font list are installed and loads them if they are
$fonts = explode(', ', (string)$theme->desktop->font);
foreach ($fonts as $key) {
	if (FlyFontCheck(trim($key))) {
		FlyFontLoad(trim($key),true,false);
	}
}

if (strpos($settings, 'body') !== false || strpos($settings, 'all') !== false) {
echo '
	html {
		overflow-x: hidden;
		overflow-y: '.$bodyOverflow.';
		height: 100%;
		overscroll-behavior: none;
	}
	
	body {
		background-image: url("'.FlyVarsReplace((string)$config->visual->theme->backgroundImage).'");
		background-color: '.$config->visual->theme->backgroundColor.';
	
		background-size: cover;
		background-position: center center;
		background-repeat: no-repeat;
		background-attachment: fixed;
		margin-top: 0px;
		margin-right: 0px;
		margin-left: 0px;
		margin-bottom: 0px;
		padding: 0px;
		overflow-x: hidden;
		overflow-y: auto;
		overscroll-behavior: none;
	}
';
}
if (strpos($settings, 'window') !== false || strpos($settings, 'all') !== false) {
	echo '
	.FlyWindow {
	}
	.FlyWindowActive {
		background: #c0c0c0;
		'.str_replace($vars,$values,$theme->window->active).'
		font-family: '.$theme->desktop->font.', sans-serif;
		
		overflow: visible !important;
		transform-origin: 0px 0px !important;
		transform: scale('.$ui_scale.');
	}
	.FlyWindowInactive {
		background: #c0c0c0;
		'.str_replace($vars,$values,$theme->window->inactive).'
		font-family: '.$theme->desktop->font.', sans-serif;

		overflow: hidden !important;
		transform-origin: 0px 0px !important;
		transform: scale('.$ui_scale.');
	}
	.FlyWindowTransparent {
		background: transparent;
		font-family: '.$theme->desktop->font.', sans-serif;

		overflow: visible !important;
		transform-origin: 0px 0px !important;
		transform: scale('.$ui_scale.');
	}
	.FlyWindowExpand {
		background: #c0c0c0;
		'.str_replace($vars,$values,$theme->window->expand).'
		font-family: '.$theme->desktop->font.', sans-serif;
		
		overflow: hidden !important;
		transform-origin: 0px 0px !important;
		transform: scale('.$ui_scale.');
		position: fixed !important;
		top: 0px !important;
		left: 0px !important;
		width: 100% !important;
		height: 100%; !important;
	}
	.FlyUiControlScaled {
		background: #c0c0c0;
		'.str_replace($vars,$values,$theme->window->control).'
		font-family: '.$theme->desktop->font.', sans-serif;

		opacity: 100;
		overflow: hidden !important;
		transform: scale('.$ui_scale.');
	}
	.FlyUiControlNonScaled {
		background: #c0c0c0;
		'.str_replace($vars,$values,$theme->window->control).'
		font-family: '.$theme->desktop->font.', sans-serif;

		opacity: 100;
		overflow: hidden !important;
	}
	.FlyWindowTitleActive {
		'.str_replace($vars,$values,$theme->titlebar->active).'
		font-family: '.$theme->desktop->font.', sans-serif;

		overflow: hidden !important;
		white-space: nowrap;
		text-overflow: ellipsis;
		word-wrap: break-word !important;
		-webkit-touch-callout: none !important;
		-webkit-user-select: none !important;
		-khtml-user-select: none !important;
		-moz-user-select: none !important;
		-ms-user-select: none !important;
		user-select: none !important;
		cursor: move !important;
	}
	.FlyWindowTitleInactive {
		'.str_replace($vars,$values,$theme->titlebar->inactive).'
		font-family: '.$theme->desktop->font.', sans-serif;

		overflow: hidden !important;
		white-space: nowrap;
		text-overflow: ellipsis;
		word-wrap: break-word !important;
		-webkit-touch-callout: none !important;
		-webkit-user-select: none !important;
		-khtml-user-select: none !important;
		-moz-user-select: none !important;
		-ms-user-select: none !important;
		user-select: none !important;
		cursor: move !important;
	}
	.FlyWindowTitleRowActive {
		'.str_replace($vars,$values,$theme->titlerow->active).'
		
		padding: 4px;
		box-sizing: border-box;
	}
	.FlyWindowTitleRowInactive {
		'.str_replace($vars,$values,$theme->titlerow->inactive).'
		
		padding: 4px;
		box-sizing: border-box;
	}
	.FlyWindowButtonActive {
		'.str_replace($vars,$values,$theme->buttons->active->normal).'

		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: default;
		width: 30px;
		height: 28px;
		line-height: 28px;
		padding: 0px;
		box-sizing: border-box;
		font-family: Symbols, Arial, sans-serif;
	}
	.FlyWindowButtonActive:hover {
		'.str_replace($vars,$values,$theme->buttons->active->hover).'
	}
	.FlyWindowButtonActive:active {
		'.str_replace($vars,$values,$theme->buttons->active->active).'
	}
	.FlyWindowButtonInactive {
		'.str_replace($vars,$values,$theme->buttons->inactive->normal).'

		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: default;
		width: 30px;
		height: 28px;
		line-height: 28px;
		padding: 0px;
		box-sizing: border-box;
		font-family: Symbols, Arial, sans-serif;
	}
	.FlyWindowButtonInactive:hover {
		'.str_replace($vars,$values,$theme->buttons->inactive->hover).'
	}
	.FlyWindowButtonInactive:active {
		'.str_replace($vars,$values,$theme->buttons->inactive->active).'
	}
	
	@keyframes FlyWindowMinimizeAnimation {
		'.str_replace($vars,$values,$theme->window->animations->minimize).'
	}
	@keyframes FlyWindowOpenAnimation {
		'.str_replace($vars,$values,$theme->window->animations->open).'
	}
	@keyframes FlyWindowCloseAnimation {
		'.str_replace($vars,$values,$theme->window->animations->close).'
	}
';
}
if (strpos($settings, 'text') !== false || strpos($settings, 'all') !== false) {
	echo '
	
	.FlyUiContent {
		background-color: #fff;
		'.str_replace($vars,$values,$theme->content).'
		font-family: '.$theme->desktop->font.', sans-serif;
	}
	.FlyUiTextHighlightControl {
		'.str_replace($vars,$values,$theme->highlight->control).'
		font-family: '.$theme->desktop->font.', sans-serif;

		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: default;
	}
	.FlyUiTextHoverControl {
		'.str_replace($vars,$values,$theme->hover->control->normal).'
		font-family: '.$theme->desktop->font.', sans-serif;

		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: default;
	}
	.FlyUiTextHoverControl:hover {
		'.str_replace($vars,$values,$theme->hover->control->hover).'
	}
	.FlyUiTextHoverControl:active {
		'.str_replace($vars,$values,$theme->hover->control->active).'
	}
	.FlyUiTextHighlight {
		'.str_replace($vars,$values,$theme->highlight->normal).'
		font-family: '.$theme->desktop->font.', sans-serif;

		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: default;
	}
	.FlyUiTextHover {
		'.str_replace($vars,$values,$theme->hover->normal->normal).'
		font-family: '.$theme->desktop->font.', sans-serif;

		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: default;
	}
	.FlyUiTextHover:hover {
		'.str_replace($vars,$values,$theme->hover->normal->hover).'
	}
	.FlyUiTextHover:active {
		'.str_replace($vars,$values,$theme->hover->normal->active).'
	}
	.FlyUiText {
		font-family: '.$theme->desktop->font.', sans-serif;
	}
	.FlyUiNoSelect {
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		cursor: default;
	}
	a {
		color: rgb(36,99,203);
		text-decoration: none;
	}
	a:hover {
		cursor: pointer;
		text-decoration: underline;
	}
	a:active {
		color: rgb(36,39,143);
	}
';
}
if (strpos($settings, 'toolbar') !== false || strpos($settings, 'all') !== false) {
	echo '
	.FlyToolbar {
		background: #c0c0c0;
		'.str_replace($vars,$values,$theme->toolbar->normal).'
		font-family: '.$theme->desktop->font.', sans-serif;

		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		cursor: default;
		padding: 5;
		height: 36px;
		overflow: hidden;
		z-index: 5000000;
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	.FlyUiTip {
		'.str_replace($vars,$values,$theme->tip->normal).'
		font-family: '.$theme->desktop->font.', sans-serif;

		position: fixed; !important;
		z-index: 5000002; !important;
	}
	
	@keyframes FlyTrayIconAnimation {
		from {opacity: 0;}
		to {opacity: 1;}
	}
	
	.FlyTrayIcon {
		width: 24px;
		height: 24px;
		padding-left: 4px;
		transition: filter .1s linear;
		cursor: pointer;
		animation: FlyTrayIconAnimation .1s linear;
	}
	
	.FlyTrayIcon:hover {
		filter: brightness(1.15);
	}
	
	.FlyTrayIcon:active {
		filter: brightness(.85);
	}
	
	.FlyJumpButton {
		width: 44px;
		height: 44px;
		position: fixed;
		top: -5px;
		left: 0px;
		padding-left: 6px;
		padding-right: 6px;
		z-index: 5000001;
		transition: filter .1s linear;
	}
	.FlyJumpButton:hover {
		filter: brightness(1.15);
	}
	
	.FlyJumpButton:active {
		filter: brightness(.85);
	}
';
}
if (strpos($settings, 'controls') !== false || strpos($settings, 'all') !== false) {
	echo '
	button, input[type=submit], input[type=reset], input[type=button] {
		'.str_replace($vars,$values,$theme->controls->button->normal).'
	
		height: 28px;
		padding-left: 12px;
		padding-right: 12px;
		font-family: '.$theme->desktop->font.', sans-serf;
		overflow: hidden !important;
		word-break: keep-all;
	}
	button[disabled] {
		'.str_replace($vars,$values,$theme->controls->button->disabled).'
		
		height: 28px !important;
		padding-left: 12px !important;
		padding-right: 12px !important;
		font-family: '.$theme->desktop->font.', sans-serf;
		overflow: hidden !important;
		word-break: keep-all;
	}
	button:active, input[type=submit]:active, input[type=reset]:active, input[type=button]:active {
		'.str_replace($vars,$values,$theme->controls->button->active).'
	}
	button:hover, input[type=submit]:active, input[type=reset]:active, input[type=button]:active {
		'.str_replace($vars,$values,$theme->controls->button->hover).'
	}
	input[type=text], input[type=password] {
		padding-left: 4px;
		padding-right: 4px;
		border: .1px solid #000000;
		border-radius: 4px;
		height: 28px;
		box-sizing: border-box;
		font-family: '.$theme->desktop->font.', sans-serif;
	}
	select {
		height: 28px;
		background-image: linear-gradient(to bottom, rgb(255,255,255) 0%,rgb(241,241,241) 50%,rgb(225,225,225) 51%,rgb(246,246,246) 100%);
		background-color: rgb(241,241,241);
		padding: 4px;
		border: .1px solid #c0c0c0;
		border-radius: 4px;
		font-family: '.$theme->desktop->font.', sans-serif;
	}
	
	.FlyUiToolbarItem {
		'.str_replace($vars,$values,$theme->controls->toolbaritem->normal).'

		font-family: '.$theme->desktop->font.', sans-serif;
		font-size: 14px;
		display: inline-block;
		height: 16px !important;
		cursor: pointer;
		padding: 8px !important;
		overflow: hidden;
		line-height: 1.3;
		text-align: left;
	}
	.FlyUiToolbarItem:hover {
		'.str_replace($vars,$values,$theme->controls->toolbaritem->hover).'

	}
	.FlyUiToolbarItem:active {
		'.str_replace($vars,$values,$theme->controls->toolbaritem->active).'
	
		line-height: 1.5;
	}
	.FlyUiToolbarItemToggle {
		'.str_replace($vars,$values,$theme->controls->toolbaritem->normal).'
		'.str_replace($vars,$values,$theme->controls->toolbaritem->active).'

		font-family: '.$theme->desktop->font.', sans-serif;
		font-size: 14px;
		display: inline-block;
		height: 16px !important;
		cursor: pointer;
		padding: 8px !important;
		overflow: hidden;
		text-align: left;
		line-height: 1.5;
	}
	@keyframes FlyUiMenuAnimation {
		'.str_replace($vars,$values,$theme->controls->menu->animation).'
	}
	.FlyUiMenu {
		min-width: 96px;
		position: absolute;
		overflow: auto;
		animation: FlyUiMenuAnimation '.$theme->controls->menu->animation['length'].'s '.$theme->controls->menu->animation['timing'].' '.$theme->controls->menu->animation['delay'].' '.$theme->controls->menu->animation['repeat'].';
		z-index: 5000002;
		cursor: default;
		font-size: 12px;
		background-color: #FFFFFF;

		'.str_replace($vars,$values,$theme->controls->menu->normal).'
	} 
	.FlyUiMenuItemDisabled {
		padding: 4px;
		width: 100%;
		cursor: normal;
		box-sizing: border-box;
		
		font-family: '.$theme->desktop->font.', sans-serif;

		'.str_replace($vars,$values,$theme->controls->menuitem->disabled).'
	}
	.FlyUiMenuItemDisabled:hover {
		
	}
	.FlyUiMenuItemDisabled:active {
		
	}
	.FlyUiMenuItem {
		padding: 4px;
		width: 100%;
		color: inherit;
		cursor: pointer;
		box-sizing: border-box;

		font-family: '.$theme->desktop->font.', sans-serif;
		
		'.str_replace($vars,$values,$theme->controls->menuitem->normal).'
	} 
	.FlyUiMenuItem:hover {
		'.str_replace($vars,$values,$theme->controls->menuitem->hover).'
	}
	.FlyUiMenuItem:active {
		'.str_replace($vars,$values,$theme->controls->menuitem->active).'
	}
	.FlyUiMenuItemActive {
		padding: 4px;
		width: 100%;
		color: inherit;
		cursor: pointer;
		box-sizing: border-box;

		font-family: '.$theme->desktop->font.', sans-serif;
		
		'.str_replace($vars,$values,$theme->controls->menuitem->normal).'
		'.str_replace($vars,$values,$theme->controls->menuitem->active).'
	}
	
	.FlyUiProgressWaitContainer {
		position: relative;
		box-sizing: border-box;
		overflow: hidden;
		display: inline-block;
		height: 28px;
		min-width: 256px;
		cursor: wait;

		'.str_replace($vars,$values,$theme->controls->progress->container).'
	}
	
	.FlyUiProgressBar {
		display: inline-block;
		transition: width .2s ease-in-out;
		position: absolute;
		top: 0px;
		left: 0px;
		height: 100%;
		width: 0%;
		cursor: wait;
	
		'.str_replace($vars,$values,$theme->controls->progress->progress).'
	}
	.FlyUiWaitBar {
		display: inline-block;
		position: absolute;
		top: 0px;
		left: -30%;
		height: 100%;
		width: 30%;
		animation: FlyUiWaitBarAnimation 3s linear 0s infinite;
		cursor: wait;

		'.str_replace($vars,$values,$theme->controls->progress->wait).'
	}
	@keyframes FlyUiWaitBarAnimation {
		from {left: -30%;}
		to {left: 130%;}
	}
';
}
if ($enclosure) {
echo '
</style>
<script>
if (typeof shell !== "undefined") {
	shell.showThemeInfo = function() {
		shell.dialog("Theme information","Current theme is \"'.$theme->name.'\" by '.$theme->publisher.'.","Theme","'.$_FLY['RESOURCE']['URL']['APPS'].'SprocketComputers.Options/icons/personalization.svg");
	}
	shell.getThemeInfo = function() {
		return ["'.$theme->name.'","'.$theme->publisher.'","'.$theme->id.'"];
	}
}
</script>
	';
}

}
}
?>