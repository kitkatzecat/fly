<?php
if (!isset($_FLY)) {
include 'Fly.Session.php';

if (!function_exists('str_lreplace')) {
/*
	Replace only the last occurrence of a subject in a string
	str_lreplace( [string] needle , [string] replace with , [string] haystack )
	returns [string]
*/
function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);
    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }
    return $subject;
}
}

if (!function_exists('str_freplace')) {
/*
	Replace only the first occurrence of a subject in a string
	str_freplace( [string] needle , [string] replace with , [string] haystack )
	returns [string]
*/
function str_freplace($from, $to, $content)
{
    $from = '/'.preg_quote($from, '/').'/';

    return preg_replace($from, $to, $content, 1);
}
}

if (!function_exists('trimslashes')) {
/*
	Trims extra slashes from paths and URLs
	**WARNING** Make sure it's not trimming the slashes from URL protocols
	trimslashes( [string] string to be trimmed , [bool] strip trailing slash (if present) (true by default) )
	returns [string]
*/
function trimslashes($content,$trimtrailing=true) {
	if ($content !== '/') {
		$content = preg_replace('#/+#','/',$content);
		
		if ($trimtrailing) {
			if (substr($content,(strlen($content)-1),strlen($content)) == '/') {
				$content = str_lreplace('/','',$content);
			}
		}
	}
	return $content;
}
}

$_FLY_CONFIG = simpleXML_load_file($_SERVER['DOCUMENT_ROOT'].'/system/config.xml');
if (session_status() == PHP_SESSION_NONE) {
	include 'Fly.Session.php';
	if (!isset($_SESSION['fly_user_id'])) {
		$_FLY_USER = false;
	} else {
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/users/'.$_SESSION['fly_user_id'].'/data/user.xml')) {
			$_FLY_USER = simpleXML_load_file($_SERVER['DOCUMENT_ROOT'].'/users/'.$_SESSION['fly_user_id'].'/data/user.xml');
		} else {
			$_FLY_USER = false;
		}
	}
} else {
	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/users/'.$_SESSION['fly_user_id'].'/data/user.xml')) {
		$_FLY_USER = simpleXML_load_file($_SERVER['DOCUMENT_ROOT'].'/users/'.$_SESSION['fly_user_id'].'/data/user.xml');
	} else {
		$_FLY_USER = false;
	}
}

function FlyCoreVars_System($scope=false,$_FLY=false) {
	
	global $_FLY_USER;
	global $_FLY_CONFIG;
	
	if (!is_array($_FLY)) {
		$_FLY = array();
	}
	
	/*if (count($_FLY) == 0 && isset($_SESSION['FLY']) && count($_SESSION['FLY']) !== 0) { //EVENTUALLY FIX LOADING $_FLY FROM SESSION!
		$_FLY = $_SESSION['FLY'];
	} else {*/
		
		$_FLY['PATH'] = $_SERVER['DOCUMENT_ROOT'].'/';
		if(isset($_SERVER['HTTPS'])) {
		    if (!empty($_SERVER['HTTPS'])) {
		        $_FLY['PROTOCOL'] = 'https';
		    } else {
		        $_FLY['PROTOCOL'] = 'http';
			}
		} else {
		    $_FLY['PROTOCOL'] = 'http';
		}
		$_FLY['HOSTNAME'] = $_SERVER['HTTP_HOST'];
		$_FLY['URL'] = $_FLY['PROTOCOL'].'://'.$_FLY['HOSTNAME'].'/';
		$_FLY['PLATFORM'] = 'desktop';

		$_FLY['VERSION'] = (string)$_FLY_CONFIG->version->major;
		$_FLY['VERSION_MAJOR'] = (string)$_FLY_CONFIG->version->major;
		$_FLY['VERSION_BUILD'] = (string)$_FLY_CONFIG->version->build;
		$_FLY['VERSION_DATE'] = (string)$_FLY_CONFIG->version->bdate;
		$_FLY['VERSION_IMAGE'] = array(
			'URL' => str_replace('./',$_FLY['URL'],(string)$_FLY_CONFIG->version->image),
			'PATH' => str_replace('./',$_FLY['PATH'],(string)$_FLY_CONFIG->version->image)
		);
		$_FLY['VERSION_NAME'] = (string)$_FLY_CONFIG->version->name;
		$_FLY['VERSION_STRING'] = 'Fly '.(string)$_FLY_CONFIG->version->name;

		if ($scope != false) {
			$_FLY['CURRENT_URL'] = str_replace($_FLY['PATH'],$_FLY['URL'],preg_replace('#/+#','/',$scope));
			$_FLY['CURRENT_PATH'] = preg_replace('#/+#','/',$scope);
			$_FLY['WORKING_URL'] = str_replace($_FLY['PATH'],$_FLY['URL'],preg_replace('#/+#','/',str_lreplace(basename($scope),'',$scope)));
			$_FLY['WORKING_PATH'] = preg_replace('#/+#','/',str_lreplace(basename($scope),'',$scope));
		} else {
			$_FLY['CURRENT_URL'] = $_FLY['URL'].preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1));
			$_FLY['CURRENT_PATH'] = $_FLY['PATH'].preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1));
			$_FLY['WORKING_URL'] = $_FLY['URL'].str_lreplace(basename(preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1))),'',preg_replace('#/+#','/',preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1))));
			$_FLY['WORKING_PATH'] = $_FLY['PATH'].str_lreplace(basename(preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1))),'',preg_replace('#/+#','/',preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1))));
		}

		$_FLY['INCLUDES_PATH'] = $_FLY['PATH'].'system/include/';
		$_FLY['INCLUDES_URL'] = $_FLY['URL'].'system/include/';
		$_FLY['INCLUDES'] = array(
			'FLY.CORE'
		);

		$_FLY['REGISTRY'] = $_FLY['PATH'].'system/reg/';

		$_FLY['RESOURCE'] = array(
			'PATH' => array(
					'RESOURCES' => $_FLY['PATH'].'system/resources/',
					'ICONS' => $_FLY['PATH'].'system/resources/icons/',
					'SOUNDS' => $_FLY['PATH'].'system/resources/sounds/',
					'APPS' => $_FLY['PATH'].'system/apps/',
					'TEMP' => $_FLY['PATH'].'system/tmp/',
					'USERS' => $_FLY['PATH'].'users/',
					'OS' => $_FLY['PATH'].'system/resources/os/',
					'CMD' => $_FLY['PATH'].'system/components/cmd/',
					'SYSTEM' => $_FLY['PATH'].'system/',
					'COMPONENTS' => $_FLY['PATH'].'system/components/',
					'TYPES' => $_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml',
					'FILETYPES' => $_FLY['PATH'].'system/components/types.json',
					'FONTS' => $_FLY['PATH'].'system/resources/fonts/',
					'THEMES' => $_FLY['PATH'].'system/resources/themes/'
				),
			'URL' => array(
					'RESOURCES' => $_FLY['URL'].'system/resources/',
					'ICONS' => $_FLY['URL'].'system/resources/icons/',
					'SOUNDS' => $_FLY['URL'].'system/resources/sounds/',
					'APPS' => $_FLY['URL'].'system/apps/',
					'TEMP' => $_FLY['URL'].'system/tmp/',
					'USERS' => $_FLY['URL'].'users/',
					'OS' => $_FLY['URL'].'system/resources/os/',
					'CMD' => $_FLY['URL'].'system/components/cmd/',
					'SYSTEM' => $_FLY['URL'].'system/',
					'COMPONENTS' => $_FLY['URL'].'system/components/',
					'TYPES' => $_FLY['URL'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml',
					'FILETYPES' => $_FLY['URL'].'system/components/types.json',
					'FONTS' => $_FLY['URL'].'system/resources/fonts/',
					'THEMES' => $_FLY['URL'].'system/resources/themes/'
				)
			);
		
		$_SESSION['FLY'] = $_FLY;
		
	//}
	
	return $_FLY;

}
function FlyCoreVars($scope=false,$_FLY=false) {
	
	global $_FLY_USER;
	global $_FLY_CONFIG;
	
	if (!is_array($_FLY)) {
		$_FLY = FlyCoreVars_System($scope);
	}
	
	/*	
	if ($scope != false) {
		$_FLY['CURRENT_URL'] = str_replace($_FLY['PATH'],$_FLY['URL'],preg_replace('#/+#','/',$scope));
		$_FLY['CURRENT_PATH'] = preg_replace('#/+#','/',$scope);
		$_FLY['WORKING_URL'] = str_replace($_FLY['PATH'],$_FLY['URL'],preg_replace('#/+#','/',str_lreplace(basename($scope),'',$scope)));
		$_FLY['WORKING_PATH'] = preg_replace('#/+#','/',str_lreplace(basename($scope),'',$scope));
	} else {
		$_FLY['CURRENT_URL'] = $_FLY['URL'].preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1));
		$_FLY['CURRENT_PATH'] = $_FLY['PATH'].preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1));
		$_FLY['WORKING_URL'] = $_FLY['URL'].str_lreplace(basename(preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1))),'',preg_replace('#/+#','/',preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1))));
		$_FLY['WORKING_PATH'] = $_FLY['PATH'].str_lreplace(basename(preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1))),'',preg_replace('#/+#','/',preg_replace('#/+#','/',substr_replace($_SERVER['PHP_SELF'],'',0,1))));
	}
	*/

	if (strpos($_FLY['WORKING_PATH'],$_FLY['RESOURCE']['PATH']['APPS']) !== false) {
		$_FLY['IS_APP'] = true;
	} else {
		$_FLY['IS_APP'] = false;
	}

	if ($_FLY['IS_APP']) {
		$_FLY_APP_MANIFEST = simpleXML_load_file($_FLY['RESOURCE']['PATH']['APPS'].'/'.explode('/',trimslashes(str_replace($_FLY['RESOURCE']['PATH']['APPS'],'',$_FLY['WORKING_PATH'])))[0].'/ApplicationManifest.xml');
		$_FLY['APP'] = array(
				'PATH' => $_FLY['RESOURCE']['PATH']['APPS'].explode('/',preg_replace('#/+#','/',str_replace($_FLY['RESOURCE']['PATH']['APPS'],'',$_FLY['WORKING_PATH'])))[0].'/',
				'URL' => $_FLY['RESOURCE']['URL']['APPS'].explode('/',preg_replace('#/+#','/',str_replace($_FLY['RESOURCE']['PATH']['APPS'],'',$_FLY['WORKING_PATH'])))[0].'/',
				'ID' => (string)$_FLY_APP_MANIFEST->id,
				'NAME' => (string)$_FLY_APP_MANIFEST->name,
				'PUBLISHER' => (string)$_FLY_APP_MANIFEST->publisher,
				'VERSION' => (string)$_FLY_APP_MANIFEST->version,
				'INDEX' => $_FLY['RESOURCE']['URL']['APPS'].(string)$_FLY_APP_MANIFEST->id.'/'.(string)$_FLY_APP_MANIFEST->index,
				'INDEX_URL' => $_FLY['RESOURCE']['URL']['APPS'].(string)$_FLY_APP_MANIFEST->id.'/'.(string)$_FLY_APP_MANIFEST->index,
				'INDEX_PATH' =>$_FLY['RESOURCE']['PATH']['APPS'].(string)$_FLY_APP_MANIFEST->id.'/'.(string)$_FLY_APP_MANIFEST->index,
				'ICON' => FlyVarsReplace(str_replace('%app_path%','%FLY.WORKING_URL%',(string)$_FLY_APP_MANIFEST->icon),true,FlyCoreVars_System($_FLY['RESOURCE']['PATH']['APPS'].'/'.explode('/',preg_replace('#/+#','/',str_replace($_FLY['RESOURCE']['PATH']['APPS'],'',$_FLY['WORKING_PATH'])))[0].'/ApplicationManifest.xml')),
				'ICON_URL' => FlyVarsReplace(str_replace('%app_path%','%FLY.WORKING_URL%',(string)$_FLY_APP_MANIFEST->icon),true,FlyCoreVars_System($_FLY['RESOURCE']['PATH']['APPS'].'/'.explode('/',preg_replace('#/+#','/',str_replace($_FLY['RESOURCE']['PATH']['APPS'],'',$_FLY['WORKING_PATH'])))[0].'/ApplicationManifest.xml')),
				'ICON_PATH' => FlyVarsReplace(str_replace('%app_path%','%FLY.WORKING_PATH%',(string)$_FLY_APP_MANIFEST->icon),false,FlyCoreVars_System($_FLY['RESOURCE']['PATH']['APPS'].'/'.explode('/',preg_replace('#/+#','/',str_replace($_FLY['RESOURCE']['PATH']['APPS'],'',$_FLY['WORKING_PATH'])))[0].'/ApplicationManifest.xml'))
			);
	} else {
		$_FLY['APP'] = false;
	}


	if ($_FLY_USER) {

	$_FLY['IS_USER'] = true;
	$_FLY['USER'] = array(
			'ID' => $_SESSION['fly_user_id'],
			'NAME' => (string)$_FLY_USER->user->name,
			'PATH' => $_FLY['RESOURCE']['PATH']['USERS'].(string)$_FLY_USER->user->id.'/',
			'DATA' => $_FLY['RESOURCE']['PATH']['USERS'].(string)$_FLY_USER->user->id.'/data/',
			'DATA_PATH' => $_FLY['RESOURCE']['PATH']['USERS'].(string)$_FLY_USER->user->id.'/data/',
			'DATA_URL' => $_FLY['RESOURCE']['URL']['USERS'].(string)$_FLY_USER->user->id.'/data/',
			'IMAGE' => FlyVarsReplace((string)$_FLY_USER->user->image,true,FlyCoreVars_System($_FLY['RESOURCE']['URL']['USERS'].(string)$_FLY_USER->user->id.'/')),
			'URL' => $_FLY['RESOURCE']['URL']['USERS'].(string)$_FLY_USER->user->id.'/',
			'XML' => $_FLY['RESOURCE']['PATH']['USERS'].(string)$_FLY_USER->user->id.'/data/user.xml'
		);

	} else {

	$_FLY['IS_USER'] = false;
	$_FLY['USER'] = false;

	}

	if ($_FLY['IS_APP'] == true && $_FLY['IS_USER'] == true) {
		if (!is_dir($_FLY['USER']['DATA'].'apps/'.explode('.',$_FLY['APP']['ID'])[0])) {
			mkdir($_FLY['USER']['DATA'].'apps/'.explode('.',$_FLY['APP']['ID'])[0]);
		}
		if (!is_dir($_FLY['USER']['DATA'].'apps/'.explode('.',$_FLY['APP']['ID'])[0].'/'.explode('.',$_FLY['APP']['ID'])[1])) {
			mkdir($_FLY['USER']['DATA'].'apps/'.explode('.',$_FLY['APP']['ID'])[0].'/'.explode('.',$_FLY['APP']['ID'])[1]);
		}
		$_FLY['APP']['DATA'] = $_FLY['USER']['DATA'].'apps/'.explode('.',$_FLY['APP']['ID'])[0].'/'.explode('.',$_FLY['APP']['ID'])[1].'/';
		$_FLY['APP']['DATA_PATH'] = $_FLY['USER']['DATA_PATH'].'apps/'.explode('.',$_FLY['APP']['ID'])[0].'/'.explode('.',$_FLY['APP']['ID'])[1].'/';
		$_FLY['APP']['DATA_URL'] = $_FLY['USER']['DATA_URL'].'apps/'.explode('.',$_FLY['APP']['ID'])[0].'/'.explode('.',$_FLY['APP']['ID'])[1].'/';
	}
	
	return $_FLY;
}

if (!function_exists('FlyIncludeRegister')) {
function FlyIncludeRegister($id) {
	global $_FLY;
	array_push($_FLY['INCLUDES'], $id);
}
function FlyIncludeCheck($id) {
	global $_FLY;
	
	if (in_array($id,$_FLY['INCLUDES'])) {
		return true;
	} else {
		return false;
	}
}
}

if (!function_exists('FlyVarsReplace')) {
function FlyVarsReplace($string,$url=false,$fly=false) {
	global $_FLY;
	
	$pattern = '/(\%.[^ ]*?\%)/';
	$matches = [];
	$return = $string;
	
	if (substr($return,0,2) == './') {
		if ($url) {
			$return = substr_replace($return,'%FLY.URL%',0,2);
		} else {
			$return = substr_replace($return,'%FLY.PATH%',0,2);
		}
	}
	
	if ($fly == false) {
		$fly = $_FLY;
	}
	
	preg_match_all($pattern,$return,$matches);
	foreach ($matches[0] as $m) {
		$m = substr($m,1,-1);
		$ma = explode('.',$m);
		if ($ma[0] == 'FLY') {
			array_shift($ma);
			$var = '$fly';
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

}

$_FLY = FlyCoreVars();
if (!FlyIncludeCheck('FLY.SESSION')) {
	FlyIncludeRegister('FLY.SESSION');
}

/*
if (!function_exists(Fly_Include)) {
function Fly_Include($name) {
	if (file_exists($_FLY['INCLUDE_PATH'].$name.'.php') || file_exists($_FLY['INCLUDE_PATH'].$name.'.js')) {
		
	}
}
}

echo '<pre>';
print_r($_FLY);
echo '</pre>'; */
}
?>
