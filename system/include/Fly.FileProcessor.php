<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.FILEPROCESSOR');

if (null == $_FLY['PATH']) {
	include 'Fly.Constants.php';
}

if (!FlyIncludeCheck('FLY.REGISTRY')) {
	include 'Fly.Registry.php';
}

if (!FlyIncludeCheck('FLY.CONSTANTS')) {
	include 'Fly.Constants.php';
}

if (!function_exists('FlyConvertURLtoPath')) {

function FlyConvertURLtoPath($link) {
	$link = explode(',',$link,2);
	$url = $link[0];
	$options = $link[1];
	
	/*for ($x = 1; $x < count($link); $x++) {
		$options .= ','.$link[$x];
	}*/
	
	$path = str_replace($_SERVER['HTTP_HOST'],$_SERVER['DOCUMENT_ROOT'],str_replace('https://','',str_replace('http://','',$url)));
	if (substr($path,0,strlen($_SERVER['DOCUMENT_ROOT'])) !== $_SERVER['DOCUMENT_ROOT']) {
		$path = $_SERVER['DOCUMENT_ROOT'].'/'.$path;
	}
	return $path.$options;
}
function FlyConvertPathtoURL($path) {
	if(isset($_SERVER['HTTPS'])) {
	    if (!empty($_SERVER['HTTPS'])) {
	        $protocol = 'https://';
	    } else {
			$protocol = 'http://';
		}
	} else {
		$protocol = 'http://';
	}
	$url = str_replace($_SERVER['DOCUMENT_ROOT'],$protocol.$_SERVER['HTTP_HOST'].'/',$path);
	return $url;
}
function FlyFileStringProcessor($item) {
	global $_FLY;
	
	if(isset($_SERVER['HTTPS'])) {
	    if (!empty($_SERVER['HTTPS'])) {
	        $protocol = 'https://';
	    } else {
			$protocol = 'http://';
		}
	} else {
		$protocol = 'http://';
	}
	
	if (substr($item,0,4)=='http') {
		return FlyFileStringProcessor(FlyConvertURLtoPath($item));
		exit;
	}
	
	$appX = '';
	$appY = '';
	$appLoad = '';
	
	$file = explode(',',$item);
	$options = '';
	$filePath = $file[0];
	
	for ($x = 1; $x < count($file); $x++) {
		if ($x == 1) {
			$string = explode('=',$file[$x],2);
			if ($string[0] == 'Fly_x') {
				$appX = $string[1];
			} else if ($string[0] == 'Fly_y') {
				$appY = $string[1];
			} else {
				$var = $string[0].'='.rawurlencode(urldecode($string[1]));
				$options .= '?'.$var;
			}
		} else {
			$string = explode('=',$file[$x],2);
			if ($string[0] == 'Fly_x') {
				$appX = $string[1];
			} else if ($string[0] == 'Fly_y') {
				$appY = $string[1];
			} else {
				$var = $string[0].'='.rawurlencode(urldecode($string[1]));
				if ($options == '') {
					$options .= '?'.$var;
				} else {
					$options .= '&'.$var;
				}
			}
		}
	}
	
	if (file_exists($filePath) === false) {
		$appCheckEx = explode('.',$filePath);
		if (!empty($appCheckEx[0]) && !empty($appCheckEx[1])) {
			$appCheck = $appCheckEx[0].'.'.$appCheckEx[1];
		} else {
			$appCheck = false;
		}
		if (file_exists($_SERVER['DOCUMENT_ROOT'].'/system/apps/'.$appCheck) && $appCheck) { // APPLICATION ------------------------------------------------
			$type = 'application';
			$icon = $protocol.$_SERVER['HTTP_HOST'].'/system/resources/icon/application.svg';
			
			if (file_exists($_SERVER['DOCUMENT_ROOT'].'/system/apps/'.$appCheck.'/ApplicationManifest.xml')) {
				$manifestXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['APPS'].$appCheck.'/ApplicationManifest.xml');
				if (!empty(explode('.',$filePath)[2])) {
					if (isset($manifestXML->masks->xpath("//mask[@id='".explode('.',$filePath)[2]."']")[0])) {
						$manifestXML = $manifestXML->masks->xpath("//mask[@id='".explode('.',$filePath)[2]."']")[0];
					} else {
						return false;
					}
				}
				
				if (strpos((string)$manifestXML->index,'?') !== false) {
					$indexOptions = explode('?',(string)$manifestXML->index)[1];
					$index = explode('?',(string)$manifestXML->index)[0];
					if ($options == '') {
						$options = '?'.$indexOptions;
					} else {
						$options .= '&'.$indexOptions;
					}
				} else {
					$index = (string)$manifestXML->index;
				}
				$index = FlyVarsReplace($index);
				
				$id = $manifestXML->id;
				if ($id == '') {
					$id = $appCheck.'.'.$manifestXML['id'];
				}
				$name = (string)$manifestXML->name;
				$publisher = (string)$manifestXML->publisher;
				$version = (string)$manifestXML->version;
				$date = (string)$manifestXML->date;
				$icon = str_replace('%app_path%','%FLY_APPS_URL%'.$appCheck.'/',str_replace('%CURRENT_PATH%','%FLY_APPS_PATH%'.$appCheck.'/',str_replace('%WORKING_PATH%','%FLY_APPS_PATH%'.$appCheck.'/',str_replace('%CURRENT_URL%','%FLY_APPS_URL%'.$appCheck.'/',str_replace('%WORKING_URL%','%FLY_APPS_URL%'.$appCheck.'/',str_replace('%icon_path%','%FLY_ICONS_URL%',$manifestXML->icon))))));
				$icon = FlyVarsReplace(FlyStringReplaceConstants($icon),true,FlyCoreVars($_FLY['RESOURCE']['PATH']['APPS'].$appCheck.'/ApplicationManifest.xml'));
				$description = (string)$manifestXML->description;
				$window = $protocol.$_SERVER['HTTP_HOST'].'/system/apps/'.$appCheck.'/'.$index.$options;		
				if ($appX == '') {
					$appX = (string)$manifestXML->window->x;
				}
				if ($appY == '') {
					$appY = (string)$manifestXML->window->y;
				}
				if (in_array((string)$manifestXML->window->expand,['true','yes','on'])) {
					$expand = 'true';
				} else {
					$expand = 'false';
				}

				if (intval((string)$manifestXML->window->minwidth)) {
					$minWidth = intval((string)$manifestXML->window->minwidth);
				} else {
					$minWidth = 'false';
				}
				if (intval((string)$manifestXML->window->maxwidth)) {
					$maxWidth = intval((string)$manifestXML->window->maxwidth);
				} else {
					$maxWidth = 'false';
				}

				if (intval((string)$manifestXML->window->minheight)) {
					$minHeight = intval((string)$manifestXML->window->minheight);
				} else {
					$minHeight = 'false';
				}
				if (intval((string)$manifestXML->window->maxheight)) {
					$maxHeight = intval((string)$manifestXML->window->maxheight);
				} else {
					$maxHeight = 'false';
				}

				if (intval((string)$manifestXML->window->maxinitwidth)) {
					$maxInitWidth = intval((string)$manifestXML->window->maxinitwidth);
				} else {
					$maxInitWidth = 'false';
				}
				if (intval((string)$manifestXML->window->maxinitheight)) {
					$maxInitHeight = intval((string)$manifestXML->window->maxinitheight);
				} else {
					$maxInitHeight = 'false';
				}

				if (in_array((string)$manifestXML->window->minimize,['false','no','off'])) {
					$minimize = 'false';
				} else {
					$minimize = 'true';
				}
				if (in_array((string)$manifestXML->window->close,['false','no','off'])) {
					$close = 'false';
				} else {
					$close = 'true';
				}
				if (in_array((string)$manifestXML->window->resize,['true','yes','on'])) {
					$resize = 'true';
				} else {
					$resize = 'false';
				}
				$filePath = preg_replace('#/+#','/',$filePath);
				$location = $_SERVER['DOCUMENT_ROOT'].'/system/apps/'.$appCheck.'/';
				$manifest = $_SERVER['DOCUMENT_ROOT'].'/system/apps/'.$appCheck.'/ApplicationManifest.xml';
				$action = 'task.create(\''.$id.'\', {title:\''.$manifestXML->window->title.'\', name:\''.$name.'\', x:\''.$appX.'\', y:\''.$appY.'\', width:\''.$manifestXML->window->width.'\', height:\''.$manifestXML->window->height.'\', minwidth:\''.$minWidth.'\', minheight:\''.$minHeight.'\', maxwidth:\''.$maxWidth.'\', maxheight:\''.$maxHeight.'\', maxinitwidth:'.$maxInitWidth.', maxinitheight:'.$maxInitHeight.', location:\''.$window.'\', icon:\''.$icon.'\', expand:'.$expand.', minimize:'.$minimize.', close:'.$close.', resize:'.$resize.'})';
				return ["file"=>$filePath,"type"=>$type,"name"=>$name,"fname"=>$name,"publisher"=>$publisher,"version"=>$version,"date"=>$date,"icon"=>$icon,"description"=>$description,"action"=>$action,"location"=>$location,"manifest"=>$manifest];
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		$type = 'file';
		if (is_dir($filePath)) { // DIRECTORY -----------------------------------------------------------------------------------
			$filePath = trimslashes($filePath);
			$type = 'folder';
			
			$url = str_replace($_FLY['PATH'],$_FLY['URL'],$filePath);
			if (str_replace(basename($filePath),'',$filePath) == $_FLY['RESOURCE']['PATH']['APPS']) {
				$manifestXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['APPS'].basename($filePath).'/ApplicationManifest.xml');
				$icon = FlyVarsReplace(str_replace('%app_path%',$protocol.$_SERVER['HTTP_HOST'].'/system/apps/'.basename($filePath).'/',str_replace('%icon_path%',$protocol.$_SERVER['HTTP_HOST'].'/system/resources/icons/',$manifestXML->icon)),true,FlyCoreVars($_FLY['RESOURCE']['PATH']['APPS'].basename($filePath).'/ApplicationManifest.xml'));
			} else {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'folder.svg';
			}
			if (trimslashes(str_lreplace(trimslashes(basename(str_lreplace(basename($filePath),'',$filePath))),'',str_replace(basename($filePath),'',$filePath))) == trimslashes($_FLY['RESOURCE']['PATH']['USERS'])) {
				if (basename($filePath) == 'Documents') {
					$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/docsfolder.svg';
				}
				if (basename($filePath) == 'Media') {
					$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/mediafolder.svg';
				}
				if (basename($filePath) == 'Desktop') {
					$icon = $_FLY['RESOURCE']['URL']['ICONS'].'desktop.svg';
				}
			}
			if (fnmatch($_FLY['RESOURCE']['PATH']['USERS']."*/data/registry/*",$filePath)) {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/settingfolder.svg';
			} else if (fnmatch($_FLY['REGISTRY']."*",$filePath)) {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/settingfolder.svg';
			}
		/*	if (fnmatch($_FLY['RESOURCE']['PATH']['USERS']."[!/]/Documents",$filePath)) {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/docsfolder.svg';
			}
			if (fnmatch($_FLY['RESOURCE']['PATH']['USERS']."[!/]/Media",$filePath)) {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/mediafolder.svg';
			}
			if (fnmatch($_FLY['RESOURCE']['PATH']['USERS']."[!/]/Desktop",$filePath)) {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'desktop.svg';
			}*/
			if (trimslashes($filePath) == trimslashes($_FLY['PATH'])) {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'computer.svg';
			}
			$description = 'Folder';
			$action = 'system.command(\'run:SprocketComputers.zFileManager,p='.$filePath.'\')';
			$path = trimslashes(str_lreplace(basename($filePath),'',$filePath));
			$ffile = trimslashes(str_freplace($_SERVER['DOCUMENT_ROOT'],'.',$filePath));
			$fpath = trimslashes(str_freplace($_SERVER['DOCUMENT_ROOT'],'.',str_lreplace(basename('/'.$filePath),'',$filePath)));
			
			if (trimslashes($filePath,true) == trimslashes($_FLY['PATH'],true) || $filePath == '.') {
				$fname = 'Computer';
			} else if ($filePath == '/') {
				$fname = '/';
			} else {
				$fname = basename($filePath);
			}
			
			if ($ffile == '.') {
				$ffile = './';
			}
			
			if ($path == '.') {
				$path = './';
			}
			if ($path == '.') {
				$path = './';
			}
			if ($fpath == '.') {
				$fpath = './';
			}
			if ($fpath == '') {
				$fpath = '/';
			}
			
			if (trimslashes($filePath) == trimslashes($_FLY['PATH'])) {
				$fpath = './';
			}
			
			return ["file"=>$path.'/'.basename($filePath),"name"=>basename($filePath),"type"=>$type,"mime"=>'directory',"icon"=>$icon,"description"=>$description,"URL"=>$url,"action"=>$action,"path"=>$path,"fpath"=>$fpath,"ffile"=>$ffile,"fname"=>$fname,"isdir"=>true];
		} else { // FILE ------------------------------------------------------------------------------------------------------
			$filePath = trimslashes($filePath);
			$extension = strtolower(end(explode('.',basename($filePath))));
			if ($extension == strtolower(basename($filePath))) {
				$extension = '';
			}
			
			$lookup = FlyFileTypeLookup($extension);
			
			if ($extension == 'als') {
				if (simpleXML_load_file($filePath)->icon == '') {
					$icon = FlyFileStringProcessor(FlyVarsReplace(FlyStringReplaceConstants(simpleXML_load_file($filePath)->link),true,FlyCoreVars($filePath)))['icon'];
				} else {
					$icon = FlyVarsReplace(FlyVarsReplace(FlyStringReplaceConstants(simpleXML_load_file($filePath)->icon),true,FlyCoreVars($filePath)));
				}
			} else {
				$icon = FlyVarsReplace(FlyStringReplaceConstants($lookup['icon']),true,FlyCoreVars($filePath));
			}
			if ($icon ==  '') {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/unknown.svg';
			}
			$description = $lookup['description'];
			if (fnmatch($_FLY['RESOURCE']['PATH']['USERS']."*/data/registry/*",$filePath) && $extension == '') {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/setting.svg';
				$description = 'Registry';
			} else if (fnmatch($_FLY['REGISTRY']."*",$filePath) && $extension == '') {
				$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/setting.svg';
				$description = 'Registry';
			}
			if ($description ==  '') {
				$description = 'Unknown';
			}
			if ($lookup != false) {
				$registered = true;
			} else {
				$registered = false;
			}
			$action = '';
			if ($extension == 'als') {
				$action = 'system.command(\'run:'.FlyVarsReplace(FlyStringReplaceConstants((string)simpleXML_load_file($filePath)->link)).'\')';
			} else if ($extension == 'jsc') {
				$action = 'try { (function(){'.FlyVarsReplace(FlyStringReplaceConstants(file_get_contents($filePath)),false,FlyCoreVars($filePath)).'}()); } catch(e) {shell.dialog(\'Script error\',\'An error has occurred in the script "'.htmlspecialchars(basename($filePath)).'":<br><pre>\'+e+\'</pre>\',\'Script Error\');}';
			} else {
				$action = 'system.command(\'run:'.FlyVarsReplace(FlyStringReplaceConstants(str_replace('%1',$filePath,$lookup['action'])),false,FlyCoreVars($filePath)).'\')';
			}
			if ($action == 'system.command(\'run:\')') {
				$action = '';
			}
			$url = str_ireplace($_SERVER['DOCUMENT_ROOT'],$protocol.$_SERVER['HTTP_HOST'],$filePath);
			$path = preg_replace('#/+#','/',str_ireplace(basename($filePath),'',$filePath));
			$ffile = preg_replace('#/+#','/',str_ireplace($_SERVER['DOCUMENT_ROOT'],'.',$filePath));
			$fpath = preg_replace('#/+#','/',str_ireplace($_SERVER['DOCUMENT_ROOT'],'.',str_replace('/'.basename($filePath),'',$filePath)));
			$mime = mime_content_type($path.'/'.basename($filePath));
			
			if (FlyUserRegistryGet('HideFileExtensions','SprocketComputers.zFileManager') == 'true' || (FlyUserRegistryGet('ShowExtensionALS','SprocketComputers.zFileManager') == 'false' && $extension == 'als')) {
				$fname = str_lreplace('.'.end(explode('.',basename($filePath))),'',basename($filePath));
				if ($fname == '') {
					$fname = basename($filePath);
				}
			} else {
				$fname = basename($filePath);
			}
			if ($path == '') {
				$path = '/';
			}
			if ($fpath == '') {
				$fpath = '/';
			}
			
			
			return ["file"=>$path.'/'.basename($filePath),"name"=>basename($filePath),"registered"=>$registered,"type"=>$type,"mime"=>$mime,"extension"=>$extension,"icon"=>$icon,"description"=>$description,"URL"=>$url,"action"=>$action,"path"=>$path,"fpath"=>$fpath,"ffile"=>$ffile,"fname"=>$fname,"isdir"=>false];
		}
	}
}

function FlyFileTypeLookup($string) {
	global $_FLY;

	$extension = strtolower($string);
	
	$types = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['COMPONENTS'].'types.json'),true);
	
	if (isset($types[$extension])) {
		$icon = FlyVarsReplace(FlyStringReplaceConstants($types[$extension]['icon']));
		if ($icon ==  '') {
			$icon = $_FLY['RESOURCE']['URL']['ICONS'].'type/unknown.svg';
		}
		
		$description = (string)$types[$extension]['description'];
		if ($description ==  '') {
			$description = 'Unknown';
		}
		
		if (!(!isset($types[$extension]['action']) || (string)$types[$extension]['action'] == '')) {
			$action = (string)$types[$extension]['action'];
			$app = false;
		} else {
			if (isset($types[$extension]['app'])) {
				$app = (string)$types[$extension]['app'];
				if (file_exists($_FLY['RESOURCE']['PATH']['APPS'].$app.'/ApplicationManifest.xml')) {
					$appXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['APPS'].$app.'/ApplicationManifest.xml');
					if (isset($appXML->types->$extension)) {
						if (isset($appXML->types->$extension->action)) {
							$action = $app.','.$appXML->types->$extension->action;
						} else {
							$action = false;
						}
					} else {
						/*if isset($appXML->mime-actions) {
							$json = json_decode((string)$appXML->mime-actions);
							if ($json) {
								if 
							} else {
								$action = false;
							}
						} else {
							$action = false;
						}*/
						$action = false;
					}
				} else {
					$app = false;
				}
			} else {
				$app = false;
			}
		}
		$mime = (string)$types[$extension]['mime'];
		
		return ["extension"=>$extension,"type"=>$extension,"icon"=>$icon,"description"=>$description,"action"=>$action,"app"=>$app,"mime"=>$mime];
	} else {
		return false;
	}
	
}

function FlyFileTypeAppLookup($type) {
	if (strpos($type,'/') != false) {

	} else {
		
	}
}

function FlyGetApps() {
	global $_FLY;
	
	$apps = [];
	$dir = $_FLY['RESOURCE']['PATH']['APPS'];
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if (is_dir($dir.$file) && !in_array($file, ['.','..'])) {
					if (file_exists($dir.$file.'/ApplicationManifest.xml')) {
						array_push($apps,$file);
					}
				}
			}
			closedir($dh);
		}
	}
	return $apps;
}

function FlyFileStringReplace($string) {
	return preg_replace('/[^a-zA-Z0-9\s\_\-\.]*/im','',$string);
}

$FlyFileStringFunction = '
<script>
function FlyFileStringReplace(string) {
	return string.replace(/[^a-zA-Z0-9\s\_\-\.]*/img,\'\');
}
</script>
';
}
?>
