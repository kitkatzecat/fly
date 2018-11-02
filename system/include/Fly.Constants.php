<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.CONSTANTS');

if (!function_exists(FlyStringReplaceConstants)) {

if (!function_exists(str_lreplace)) {
	include 'functions.php';
}

date_default_timezone_set("America/Chicago");

define("FLY_ROOT",$_SERVER['DOCUMENT_ROOT'].'/'); // interchangeable with FLY_PATH
define("FLY_PATH",$_SERVER['DOCUMENT_ROOT'].'/'); // interchangeable with FLY_ROOT
define("FLY_VERSION",(string)simpleXML_load_file(FLY_ROOT.'system/config.xml')->version->major);
if(isset($_SERVER['HTTPS'])) {
    if (!empty($_SERVER['HTTPS'])) {
        define("DOCUMENT_PROTOCOL","https");
    } else {
		define("DOCUMENT_PROTOCOL","http");
	}
} else {
	define("DOCUMENT_PROTOCOL","http");
}
define("FLY_HOSTNAME",$_SERVER['HTTP_HOST']);
define("FLY_URL",DOCUMENT_PROTOCOL.'://'.FLY_HOSTNAME.'/');

define("DOCUMENT_HOST",$_SERVER['HTTP_HOST']); // DEPRECATED - use FLY_HOSTNAME instead
define("DOCUMENT_ROOT",DOCUMENT_PROTOCOL.'://'.FLY_HOSTNAME.'/'); // DEPRECATED - use FLY_URL instead

define("CURRENT_URL",FLY_URL.substr_replace($_SERVER['PHP_SELF'],'',0,1));
define("CURRENT_PATH",FLY_ROOT.substr_replace($_SERVER['PHP_SELF'],'',0,1));

define("WORKING_URL",str_lreplace(basename(CURRENT_URL),'',CURRENT_URL).'/');
define("WORKING_PATH",str_lreplace(basename(CURRENT_PATH),'',CURRENT_PATH).'/');

define("FLY_ICONS_URL",FLY_URL.'system/resources/icons/');
define("FLY_ICONS_PATH",FLY_ROOT.'system/resources/icons/');
define("FLY_SOUNDS_URL",FLY_URL.'system/resources/sounds/');
define("FLY_SOUNDS_PATH",FLY_ROOT.'system/resources/sounds/');
define("FLY_APPS_URL",FLY_URL.'system/apps/');
define("FLY_APPS_PATH",FLY_ROOT.'system/apps/');

function FlyStringReplaceConstants($string) {
	$string = str_replace('%FLY_ROOT%',FLY_ROOT,$string);
	$string = str_replace('%FLY_VERSION%',FLY_VERSION,$string);
	$string = str_replace('%DOCUMENT_PROTOCOL%',DOCUMENT_PROTOCOL,$string);
	$string = str_replace('%DOCUMENT_HOST%',FLY_HOSTNAME,$string); // DEPRECATED - use %FLY_HOSTNAME% instead
	$string = str_replace('%DOCUMENT_ROOT%',FLY_URL,$string); // DEPRECATED - use %FLY_URL% instead
	$string = str_replace('%FLY_URL%',FLY_URL,$string);
	$string = str_replace('%FLY_HOSTNAME%',FLY_HOSTNAME,$string);
	$string = str_replace('%CURRENT_URL%',CURRENT_URL,$string);
	$string = str_replace('%CURRENT_PATH%',CURRENT_PATH,$string);
	$string = str_replace('%WORKING_URL%',WORKING_URL,$string);
	$string = str_replace('%WORKING_PATH%',WORKING_PATH,$string);
	$string = str_replace('%FLY_ICONS_URL%',FLY_ICONS_URL,$string);
	$string = str_replace('%FLY_ICONS_PATH%',FLY_ICONS_PATH,$string);
	$string = str_replace('%FLY_SOUNDS_URL%',FLY_SOUNDS_URL,$string);
	$string = str_replace('%FLY_SOUNDS_PATH%',FLY_SOUNDS_PATH,$string);
	$string = str_replace('%FLY_APPS_URL%',FLY_APPS_URL,$string);
	$string = str_replace('%FLY_APPS_PATH%',FLY_APPS_PATH,$string);
	
	return $string;
}
}
?>