<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
if (!empty($_POST['user']) || !empty($_POST['password'])) {
	if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/users/' . $_POST['user'])) {
		$xml = simpleXML_load_file($_SERVER['DOCUMENT_ROOT'] . '/users/' . $_POST['user'] . '/data/user.xml');
		if ($_POST['password'] == base64_decode((string)$xml->user->password)) {
			$_SESSION['fly_user_id'] = $_POST['user'];
		} else {
			header('Location: index.php?skiplogo=true');
		}
	} else {
		header('Location: index.php?skiplogo=true');
	}
}
if (!empty($_GET['logout'])) {
	unset($_SESSION['fly_user_id']);
	session_destroy();
	header('Location: index.php?skiplogo=true');
	exit;
}
if ($_SESSION['fly_user_id'] == '' || !empty($_GET['login'])) {
	goto login;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
// DELETE TMP FILES
function unlink_dir($directory)
{
	foreach (glob("{$directory}/*") as $file) {
		if (is_dir($file)) {
			unlink_dir($file);
		} else {
			unlink($file);
		}
	}
	rmdir($directory);
}
unlink_dir($_SERVER['DOCUMENT_ROOT'] . '/system/tmp');
mkdir($_SERVER['DOCUMENT_ROOT'] . '/system/tmp');

// FLY EXTENDED PHP FUNCTIONS
date_default_timezone_set("America/Chicago");
function str_lreplace($search, $replace, $subject)
{
	$pos = strrpos($subject, $search);
	if ($pos !== false) {
		$subject = substr_replace($subject, $replace, $pos, strlen($search));
	}
	return $subject;
}

// DEFINE PHP CONSTANTS
include 'Fly.Core.php';
include 'Fly.Registry.php';
include 'Fly.Constants.php';

// LOAD CONFIG
$config_system = $_FLY_CONFIG; // System config
$config_user = simpleXML_load_file($_FLY['USER']['PATH'] . 'data/user.xml'); // User config
//$config_user = $_FLY_USER; // User config

// SYSTEM COMPONENTS
include 'Fly.Registry.php'; // Registry
include 'Fly.Command.php'; // Dynamic commands
include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'shell.php'; // Fly shell
include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'sound.php'; // Fly shell - sound
//include $_FLY['RESOURCE']['PATH']['COMPONENTS'].'theme.php'; // Theme loader 1.0 (OLD)
//include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'theme2.php'; // Theme loader 2.0 (OLD)
include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'theme3.php'; // Theme loader 3.0
//include $_FLY['RESOURCE']['PATH']['COMPONENTS'].'window.php'; // Window manager 1.0 (OLD)
//include $_FLY['RESOURCE']['PATH']['COMPONENTS'].'window2.php'; // Window manager 2.0 (OLD)
include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'window3.php'; // Window manager 3.0
include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'ui.php'; // Fly user interface

include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'types_compatibility.php'; // Compatibility for types.json

include 'Fly.Actionmenu.php'; // Fly Actionmenu for Window Switcher menu

// JS SCRIPTS

// EXECUTE SYSTEM FUNCTIONS
//FlyLoadTheme('all');
FlyTheme(['text','controls','window','toolbar','body']);
audio_sound_init($config_user);

// CHECK USER FOLDERS
if (!is_dir($_FLY['USER']['DATA_PATH'])) {
	mkdir($_FLY['USER']['DATA_PATH']);
}
if (!is_dir($_FLY['USER']['DATA_PATH'].'apps')) {
	mkdir($_FLY['USER']['DATA_PATH'].'apps');
}
if (!is_dir($_FLY['USER']['DATA_PATH'].'registry')) {
	mkdir($_FLY['USER']['DATA_PATH'].'registry');
}

?>
<script>
function onload() {
	system.command('clearlog');
	ui.init();
	task.init();
	shell.notification.init();
	//setTimeout(function() {shell.notification.create('Welcome to Fly!','For some tips and tricks to help get you started, open the "Fly Help" application from the Jump menu.','<?php echo FLY_ICONS_URL; ?>fly.svg');},5000);
	
	document.getElementById('SystemStartupCover').style.backgroundColor='rgba(0,0,0,0)';
	setTimeout(function() {document.getElementById('SystemStartupCover').parentNode.removeChild(document.getElementById('SystemStartupCover'))},1050);
	
	try {
		shell.sound.system('login');
	} catch(err) {}
	
	system.command('run:%FLY.USER.PATH%data/autostart.jsc');
	
	return true;
}
function toggleFullScreen() {
  if ((document.fullScreenElement && document.fullScreenElement !== null) ||    
   (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if (document.documentElement.requestFullScreen) {  
      document.documentElement.requestFullScreen();  
    } else if (document.documentElement.mozRequestFullScreen) {  
      document.documentElement.mozRequestFullScreen();  
    } else if (document.documentElement.webkitRequestFullScreen) {  
      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);  
    }  
  } else {  
    if (document.cancelFullScreen) {  
      document.cancelFullScreen();  
    } else if (document.mozCancelFullScreen) {  
      document.mozCancelFullScreen();  
    } else if (document.webkitCancelFullScreen) {  
      document.webkitCancelFullScreen();  
    }  
  }  
}
</script>
<?php
// BUILD
date_default_timezone_set("America/Chicago");
$VersionBuild = FlyGlobalRegistryGet('VersionBuild','root.public');
$VersionDate = FlyGlobalRegistryGet('VersionDate','root.public');
$VersionMajor = FlyGlobalRegistryGet('Version','root.public');
if (intval($VersionDate) >= intval(date("YmdHis", filemtime('index.php')))) {
	$build = $VersionBuild;
} else {
	$build_o = $VersionBuild;
	$build = ((float)$VersionBuild) + 1;
	$VersionBuild = $build;
	$VersionDate = date("YmdHis", filemtime('index.php'));
	file_put_contents($_FLY['REGISTRY'].'root/public/VersionBuild',$VersionBuild);
	file_put_contents($_FLY['REGISTRY'].'root/public/VersionDate',$VersionDate);

	echo '<script>setTimeout(function() {shell.notification.create("Build Updated","The Fly build has been updated from ' . $build_o . ' to ' . $build . '.","' . FLY_ICONS_URL . 'fly.svg");},5000);</script>';
}
?>
<title><?php echo 'Fly v' . $VersionMajor . ' b' . $build; ?></title>
</head>
<body onload="onload()">
<?php
if (in_array(FlyRegistryGet('DesktopShowVersion', 'SprocketComputers.Options'), ['on', 'true', 'yes'])) {
	echo '<div class="FlyUiNoSelect FlyUiText" onclick="system.command(\'run:SprocketComputers.Utilities.AboutFly\')" style="position:fixed;bottom:12px;right:12px;width:240px;text-align:right;color:#FFFFFF;text-shadow: 0px 0px 6px #000000;z-index:1;">Fly&nbsp;' . FlyGlobalRegistryGet('VersionName','root.public') . '<br>Version ' . $VersionMajor . '&nbsp;Build&nbsp;' . $build . '<br>&copy;&nbsp;' . substr($VersionDate, 0, 4) . '&nbsp;Sprocket Computers</div>';
}
?>
<div id="SystemStartupCover" style="position:absolute;top:0px;left:0px;right:0px;bottom:0px;background-color:rgba(0,0,0,1);z-index:5000010;transition:background-color 1s linear;">
<noscript>
<div style="border-width: 1px; border-style: solid; border-color: rgb(255, 255, 255) rgb(128, 128, 128) rgb(128, 128, 128) rgb(255, 255, 255); padding: 25px; left: 50%; top: 50%; width: 400px; text-align: center; position: absolute; z-index: 5000006; transform: translate(-50%, -50%); background-color: rgb(192, 192, 192);"><span class="FlyUiNoSelect" style="color:#000000;font-family:sans-serif;"><img width="16" height="16" align="absmiddle" src="data:image/svg+xml;base64, PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5v Ij8+CjxzdmcKICAgeG1sbnM6b3NiPSJodHRwOi8vd3d3Lm9wZW5zd2F0Y2hib29rLm9y Zy91cmkvMjAwOS9vc2IiCiAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxl bWVudHMvMS4xLyIKICAgeG1sbnM6Y2M9Imh0dHA6Ly9jcmVhdGl2ZWNvbW1vbnMub3Jn L25zIyIKICAgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJk Zi1zeW50YXgtbnMjIgogICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAv c3ZnIgogICB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiAgIHhtbG5z OnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIgogICB4bWxuczpzb2Rp cG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGkt MC5kdGQiCiAgIHhtbG5zOmlua3NjYXBlPSJodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy9u YW1lc3BhY2VzL2lua3NjYXBlIgogICBoZWlnaHQ9IjEwMCUiCiAgIHdpZHRoPSIxMDAl IgogICB2ZXJzaW9uPSIxLjEiCiAgIGlkPSJzdmcyIgogICB2aWV3Qm94PSIwLDAsNDgs NDgiCiAgIGlua3NjYXBlOnZlcnNpb249IjAuOTEgcjEzNzI1IgogICBzb2RpcG9kaTpk b2NuYW1lPSJlcnJvci5zdmciPgogIDxzb2RpcG9kaTpuYW1lZHZpZXcKICAgICBwYWdl Y29sb3I9IiNmZmZmZmYiCiAgICAgYm9yZGVyY29sb3I9IiM2NjY2NjYiCiAgICAgYm9y ZGVyb3BhY2l0eT0iMSIKICAgICBvYmplY3R0b2xlcmFuY2U9IjEwIgogICAgIGdyaWR0 b2xlcmFuY2U9IjEwIgogICAgIGd1aWRldG9sZXJhbmNlPSIxMCIKICAgICBpbmtzY2Fw ZTpwYWdlb3BhY2l0eT0iMCIKICAgICBpbmtzY2FwZTpwYWdlc2hhZG93PSIyIgogICAg IGlua3NjYXBlOndpbmRvdy13aWR0aD0iMTkyMCIKICAgICBpbmtzY2FwZTp3aW5kb3ct aGVpZ2h0PSIxMDE3IgogICAgIGlkPSJuYW1lZHZpZXczNSIKICAgICBzaG93Z3JpZD0i ZmFsc2UiCiAgICAgaW5rc2NhcGU6em9vbT0iNi45NTMyMTY3IgogICAgIGlua3NjYXBl OmN4PSI2Ljg2NTIwNzQiCiAgICAgaW5rc2NhcGU6Y3k9IjQwLjEzMTg1NCIKICAgICBp bmtzY2FwZTp3aW5kb3cteD0iLTgiCiAgICAgaW5rc2NhcGU6d2luZG93LXk9Ii04Igog ICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjEiCiAgICAgaW5rc2NhcGU6Y3Vy cmVudC1sYXllcj0ic3ZnMiIgLz4KICA8ZGVmcwogICAgIGlkPSJkZWZzNCI+CiAgICA8 bGluZWFyR3JhZGllbnQKICAgICAgIG9zYjpwYWludD0ic29saWQiCiAgICAgICBpZD0i bGluZWFyR3JhZGllbnQ1MTAzIj4KICAgICAgPHN0b3AKICAgICAgICAgaWQ9InN0b3A1 MTA1IgogICAgICAgICBvZmZzZXQ9IjAiCiAgICAgICAgIHN0eWxlPSJzdG9wLWNvbG9y OiMwMDAwMDA7c3RvcC1vcGFjaXR5OjE7IiAvPgogICAgPC9saW5lYXJHcmFkaWVudD4K ICAgIDxsaW5lYXJHcmFkaWVudAogICAgICAgaWQ9ImxpbmVhckdyYWRpZW50NTA0MSI+ CiAgICAgIDxzdG9wCiAgICAgICAgIGlkPSJzdG9wNTA0MyIKICAgICAgICAgb2Zmc2V0 PSIwIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xvcjojZmZmZmZmO3N0b3Atb3BhY2l0 eToxOyIgLz4KICAgICAgPHN0b3AKICAgICAgICAgaWQ9InN0b3A1MDQ1IgogICAgICAg ICBvZmZzZXQ9IjEiCiAgICAgICAgIHN0eWxlPSJzdG9wLWNvbG9yOiNmZmZmZmY7c3Rv cC1vcGFjaXR5OjA7IiAvPgogICAgPC9saW5lYXJHcmFkaWVudD4KICAgIDxsaW5lYXJH cmFkaWVudAogICAgICAgaWQ9ImxpbmVhckdyYWRpZW50NDk4MSI+CiAgICAgIDxzdG9w CiAgICAgICAgIGlkPSJzdG9wNDk4MyIKICAgICAgICAgb2Zmc2V0PSIwIgogICAgICAg ICBzdHlsZT0ic3RvcC1jb2xvcjojOGUzMDMwO3N0b3Atb3BhY2l0eToxIiAvPgogICAg ICA8c3RvcAogICAgICAgICBpZD0ic3RvcDQ5ODUiCiAgICAgICAgIG9mZnNldD0iMSIK ICAgICAgICAgc3R5bGU9InN0b3AtY29sb3I6I2ZmMDAwMDtzdG9wLW9wYWNpdHk6MSIg Lz4KICAgIDwvbGluZWFyR3JhZGllbnQ+CiAgICA8bGluZWFyR3JhZGllbnQKICAgICAg IGlkPSJsaW5lYXJHcmFkaWVudDU3MzYiPgogICAgICA8c3RvcAogICAgICAgICBpZD0i c3RvcDU3MzgiCiAgICAgICAgIG9mZnNldD0iMCIKICAgICAgICAgc3R5bGU9InN0b3At Y29sb3I6I2MzYzNjMztzdG9wLW9wYWNpdHk6MSIgLz4KICAgICAgPHN0b3AKICAgICAg ICAgaWQ9InN0b3A1NzQwIgogICAgICAgICBvZmZzZXQ9IjEiCiAgICAgICAgIHN0eWxl PSJzdG9wLWNvbG9yOiNmZmZmZmY7c3RvcC1vcGFjaXR5OjE7IiAvPgogICAgPC9saW5l YXJHcmFkaWVudD4KICAgIDxsaW5lYXJHcmFkaWVudAogICAgICAgZ3JhZGllbnRVbml0 cz0idXNlclNwYWNlT25Vc2UiCiAgICAgICB5Mj0iOC4wNzgxMjUiCiAgICAgICB4Mj0i OS45NTcwMzEyIgogICAgICAgeTE9IjQxLjc2OTUzMSIKICAgICAgIHgxPSIzNy45NzY1 NjIiCiAgICAgICBpZD0ibGluZWFyR3JhZGllbnQ1NzQyIgogICAgICAgeGxpbms6aHJl Zj0iI2xpbmVhckdyYWRpZW50NTczNiIgLz4KICAgIDxsaW5lYXJHcmFkaWVudAogICAg ICAgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiCiAgICAgICB5Mj0iMTAxOS4w NDY2IgogICAgICAgeDI9IjI5LjQ4ODI4OSIKICAgICAgIHkxPSIxMDEyLjY5ODkiCiAg ICAgICB4MT0iMzYuNDAyMzUxIgogICAgICAgaWQ9ImxpbmVhckdyYWRpZW50NTc5Ni02 IgogICAgICAgeGxpbms6aHJlZj0iI2xpbmVhckdyYWRpZW50NTczNi04IiAvPgogICAg PGxpbmVhckdyYWRpZW50CiAgICAgICBpZD0ibGluZWFyR3JhZGllbnQ1NzM2LTgiPgog ICAgICA8c3RvcAogICAgICAgICBpZD0ic3RvcDU3MzgtOSIKICAgICAgICAgb2Zmc2V0 PSIwIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xvcjojYzNjM2MzO3N0b3Atb3BhY2l0 eToxIiAvPgogICAgICA8c3RvcAogICAgICAgICBpZD0ic3RvcDU3NDAtOCIKICAgICAg ICAgb2Zmc2V0PSIxIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xvcjojZmZmZmZmO3N0 b3Atb3BhY2l0eToxOyIgLz4KICAgIDwvbGluZWFyR3JhZGllbnQ+CiAgICA8bGluZWFy R3JhZGllbnQKICAgICAgIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIgogICAg ICAgeTI9IjguMDc4MTI1IgogICAgICAgeDI9IjkuOTU3MDMxMiIKICAgICAgIHkxPSI0 MS43Njk1MzEiCiAgICAgICB4MT0iMzcuOTc2NTYyIgogICAgICAgaWQ9ImxpbmVhckdy YWRpZW50NTc0Mi01IgogICAgICAgeGxpbms6aHJlZj0iI2xpbmVhckdyYWRpZW50NTcz Ni01IiAvPgogICAgPGxpbmVhckdyYWRpZW50CiAgICAgICBpZD0ibGluZWFyR3JhZGll bnQ1NzM2LTUiPgogICAgICA8c3RvcAogICAgICAgICBpZD0ic3RvcDU3MzgtOCIKICAg ICAgICAgb2Zmc2V0PSIwIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xvcjojYzNjM2Mz O3N0b3Atb3BhY2l0eToxIiAvPgogICAgICA8c3RvcAogICAgICAgICBpZD0ic3RvcDU3 NDAtNCIKICAgICAgICAgb2Zmc2V0PSIxIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xv cjojZmZmZmZmO3N0b3Atb3BhY2l0eToxOyIgLz4KICAgIDwvbGluZWFyR3JhZGllbnQ+ CiAgICA8cmFkaWFsR3JhZGllbnQKICAgICAgIGdyYWRpZW50VHJhbnNmb3JtPSJtYXRy aXgoMS40MzkyOTYyLC0wLjAxMzAyNzUyLDAuMDEwMzQ2NjYsMS4xNDMxMDU1LC0xOC4x NjI3NzMsLTExNDUuNjU1NCkiCiAgICAgICBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VP blVzZSIKICAgICAgIHI9IjE4Ljc1IgogICAgICAgZnk9IjEwMzcuMzMzNSIKICAgICAg IGZ4PSIyMi4yMzI3MzEiCiAgICAgICBjeT0iMTAzNy4zMzM1IgogICAgICAgY3g9IjIy LjIzMjczMSIKICAgICAgIGlkPSJyYWRpYWxHcmFkaWVudDQ5ODciCiAgICAgICB4bGlu azpocmVmPSIjbGluZWFyR3JhZGllbnQ0OTgxIiAvPgogICAgPGxpbmVhckdyYWRpZW50 CiAgICAgICBncmFkaWVudFRyYW5zZm9ybT0ibWF0cml4KDAsMS4xODU2ODg0LC0xLjE4 NTY4ODQsMCwxMjM4LjQyNjEsLTEuOTM2OSkiCiAgICAgICBncmFkaWVudFVuaXRzPSJ1 c2VyU3BhY2VPblVzZSIKICAgICAgIHkyPSIxMDI0LjIzNzIiCiAgICAgICB4Mj0iMjUu NDg4MjgxIgogICAgICAgeTE9IjEwMjQuMjM3MiIKICAgICAgIHgxPSIxLjA3NDIxODgi CiAgICAgICBpZD0ibGluZWFyR3JhZGllbnQ1MDQ3IgogICAgICAgeGxpbms6aHJlZj0i I2xpbmVhckdyYWRpZW50NTA0MSIgLz4KICAgIDxmaWx0ZXIKICAgICAgIGhlaWdodD0i MS4xMiIKICAgICAgIHk9Ii0wLjA2IgogICAgICAgd2lkdGg9IjEuMTIiCiAgICAgICB4 PSItMC4wNiIKICAgICAgIGlkPSJmaWx0ZXI1MTYyIgogICAgICAgc3R5bGU9ImNvbG9y LWludGVycG9sYXRpb24tZmlsdGVyczpzUkdCIj4KICAgICAgPGZlR2F1c3NpYW5CbHVy CiAgICAgICAgIGlkPSJmZUdhdXNzaWFuQmx1cjUxNjQiCiAgICAgICAgIHN0ZERldmlh dGlvbj0iMC45NzE2Nzk2OSIgLz4KICAgIDwvZmlsdGVyPgogICAgPGxpbmVhckdyYWRp ZW50CiAgICAgICB4bGluazpocmVmPSIjbGluZWFyR3JhZGllbnQ1MDQxIgogICAgICAg aWQ9ImxpbmVhckdyYWRpZW50NTA0Ny0wIgogICAgICAgeDE9IjEuMDc0MjE4OCIKICAg ICAgIHkxPSIxMDI0LjIzNzIiCiAgICAgICB4Mj0iMjUuNDg4MjgxIgogICAgICAgeTI9 IjEwMjQuMjM3MiIKICAgICAgIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIgog ICAgICAgZ3JhZGllbnRUcmFuc2Zvcm09Im1hdHJpeCgxLjE4NTY4ODQsMCwwLDEuMzMy ODM2NSwxOC4wNjMxLC0xMzg5LjE0MDcpIiAvPgogIDwvZGVmcz4KICA8bWV0YWRhdGEK ICAgICBpZD0ibWV0YWRhdGE3Ij4KICAgIDxyZGY6UkRGPgogICAgICA8Y2M6V29yawog ICAgICAgICByZGY6YWJvdXQ9IiI+CiAgICAgICAgPGRjOmZvcm1hdD5pbWFnZS9zdmcr eG1sPC9kYzpmb3JtYXQ+CiAgICAgICAgPGRjOnR5cGUKICAgICAgICAgICByZGY6cmVz b3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIiAvPgog ICAgICAgIDxkYzp0aXRsZT48L2RjOnRpdGxlPgogICAgICA8L2NjOldvcms+CiAgICA8 L3JkZjpSREY+CiAgPC9tZXRhZGF0YT4KICA8Y2lyY2xlCiAgICAgc3R5bGU9Im9wYWNp dHk6MTtmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7ZmlsdGVy OnVybCgjZmlsdGVyNTE2MikiCiAgICAgaWQ9InBhdGg0OTc5LTMiCiAgICAgY3g9IjI0 IgogICAgIGN5PSIxMDI4LjM2MjIiCiAgICAgcj0iMTkuNDMzNTk0IgogICAgIHRyYW5z Zm9ybT0ibWF0cml4KDEuMTI1NjI4MSwwLDAsMS4xMjU2MjgxLC0zLjAxNTA3NTQsLTEx MzMuNTUzNCkiIC8+CiAgPGNpcmNsZQogICAgIHN0eWxlPSJvcGFjaXR5OjE7ZmlsbDp1 cmwoI3JhZGlhbEdyYWRpZW50NDk4Nyk7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmUi CiAgICAgaWQ9InBhdGg0OTc5IgogICAgIGN4PSIyNCIKICAgICBjeT0iMjMuOTk5OTgz IgogICAgIHI9IjIxLjg3NSIgLz4KICA8ZwogICAgIHRyYW5zZm9ybT0ibWF0cml4KDEu MTE4MTIyNSwwLDAsMC45NDAxNzE5MywtMC42MDcwNTEyOSwtMTAzMC4zNzM0KSIKICAg ICBzdHlsZT0iZm9udC1zdHlsZTpub3JtYWw7Zm9udC13ZWlnaHQ6bm9ybWFsO2ZvbnQt c2l6ZTozMi4xOTYxNTU1NXB4O2xpbmUtaGVpZ2h0OjEyNSU7Zm9udC1mYW1pbHk6c2Fu cy1zZXJpZjtsZXR0ZXItc3BhY2luZzowcHg7d29yZC1zcGFjaW5nOjBweDtmaWxsOiNm ZmZmZmY7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOiNmZmZmZmY7c3Ryb2tlLXdpZHRoOjEu MjAwMDAwMDU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5k O3N0cm9rZS1taXRlcmxpbWl0OjQ7c3Ryb2tlLWRhc2hhcnJheTpub25lO3N0cm9rZS1v cGFjaXR5OjEiCiAgICAgaWQ9InRleHQ1MDQ5Ij4KICAgIDxwYXRoCiAgICAgICBpbmtz Y2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgICAgZD0ibSAzMS45NzQ0NDgs MTEwOS43NjQ1IC04LjA4MDQ4MSwxMS41NzA1IDguMDY0NzYsMTEuODM3NyAtMy42MDAw NTgsMCAtNi4zODI2MzcsLTkuNjM2OCAtNi41Mzk4NDQsOS42MzY4IC0zLjM5NTY4OCww IDguMTU5MDg0LC0xMS42OTYyIC03Ljk3MDQzNSwtMTEuNzEyIDMuNTg0MzM4LDAgNi4z MDQwMzIsOS41MTExIDYuNDQ1NTIsLTkuNTExMSAzLjQxMTQwOSwwIHoiCiAgICAgICBz dHlsZT0iZmlsbDojZmZmZmZmO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTojZmZmZmZmO3N0 cm9rZS13aWR0aDoxLjIwMDAwMDA1O3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1s aW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDo0O3N0cm9rZS1kYXNoYXJyYXk6 bm9uZTtzdHJva2Utb3BhY2l0eToxIgogICAgICAgaWQ9InBhdGg1MTIwIiAvPgogIDwv Zz4KICA8cGF0aAogICAgIHN0eWxlPSJvcGFjaXR5OjE7ZmlsbDp1cmwoI2xpbmVhckdy YWRpZW50NTA0Nyk7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmUiCiAgICAgZD0iTSAy NCAyLjY5NTMxMjUgQSAyMS4zMDUzNCAyMS4zMDUzNCAwIDAgMCAyLjY5NTMxMjUgMjQg QSAyMS4zMDUzNCAyMS4zMDUzNCAwIDAgMCA0LjI0ODA0NjkgMzEuOTU1MDc4IEEgMjMu OTQ5NDA4IDIxLjMwNTM0IDAgMCAxIDI0IDIyLjY5NTMxMiBBIDIzLjk0OTQwOCAyMS4z MDUzNCAwIDAgMSA0My43NDAyMzQgMzEuOTc2NTYyIEEgMjEuMzA1MzQgMjEuMzA1MzQg MCAwIDAgNDUuMzA0Njg4IDI0IEEgMjEuMzA1MzQgMjEuMzA1MzQgMCAwIDAgMjQgMi42 OTUzMTI1IHogIgogICAgIGlkPSJwYXRoNDk3OS0wIiAvPgo8L3N2Zz4K">&nbsp;<b>Fatal Error</b><br>Please enable JavaScript to use Fly.</span></div>
</noscript></div>
</body>
</html>
<?php
exit;

login :
?>
<!DOCTYPE html>
<html>
<head>
<script>
var shell = function() {};
</script>
<?php
// EXTENDED PHP
date_default_timezone_set("America/Chicago");

// CONSTANTS
include 'Fly.Constants.php';
include 'Fly.Core.php';
include 'Fly.FileProcessor.php';
include 'Fly.Command.php';

// LOAD CONFIG
$config_system = $_FLY_CONFIG; // System config
$config_user = simpleXML_load_file($_FLY['RESOURCE']['PATH']['OS'] . 'loginstyles.xml'); // User config

// SYSTEM COMPONENTS
include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'theme3.php'; // Theme loader
include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'window3.php'; // Window manager 3.0
include $_FLY['RESOURCE']['PATH']['COMPONENTS'] . 'sound.php'; // Fly shell - sound

// EXECUTE SYSTEM FUNCTIONS
FlyCommand('clearlog');
FlyTheme(['text','controls','window','toolbar','body'],true,true,$_FLY['RESOURCE']['PATH']['OS'].'loginstyles.thm');
audio_sound_init($config_user);

if (!empty($_GET['skiplogo'])) {
	$skip = 'true';
} else {
	$skip = 'false';
}

if (in_array((string)$config_user->registry->SprocketComputers->Options->TimeShowSeconds, ['true', 'yes', 'on'])) {
	$script_show_seconds = '":" + seconds';
} else {
	$script_show_seconds = '""';
}
if (in_array((string)$config_user->registry->SprocketComputers->Options->TimeShowMilitary, ['true', 'yes', 'on'])) {
	$script_show_military = 'var hr = "";';
} else {
	$script_show_military = '
		var hr = " AM";
        if(hours > 11){
            hr = " PM";
        } else {
            hr = " AM";
        }
		if(hours == 0){
		    hours=12;
		}
		if(hours > 12){
			hours-=12;
		}';
}

?>
<script>
var skiplogo = <?php echo $skip; ?>;
var lwindow;
function onload() {
	document.body.style.cursor = 'none';
	
	document.oncontextmenu = function(e) {
		e.preventDefault();
		e.stopPropagation();
		return false;
	}

	task.init();
	
	history.replaceState( {} , 'Fly', '/' );
	
	if (skiplogo) {
		document.getElementById('fade').style.opacity = 0;
		document.body.style.cursor = 'auto';
		setTimeout(openWindow, 500);
		setTimeout(function() {document.getElementById('time').style.opacity = 1;}, 500);
	} else {
		setTimeout(openWindow, 4500);
		setTimeout(function() {document.getElementById('time').style.opacity = 1;}, 4500);
		setTimeout(function() {document.getElementById('LogonSound').play();}, 1500);
		setTimeout(function() {document.getElementById('logo').style.opacity = 1;}, 500);
		setTimeout(function() {document.getElementById('logo').style.opacity = 0;}, 3000);
		setTimeout(function() {document.getElementById('fade').style.opacity = 0;}, 4000);
		setTimeout(function() {document.body.style.cursor = 'auto';}, 5000);
	}
	
	updateTime();

}
function openWindow() {
	<?php
	if (FlyGlobalRegistryGet('FirstRun','root.public') == 'true') {
		?>
		lwindow = <?php echo FlyFileStringProcessor('SprocketComputers.Experiments.Oobe')['action']; ?>
		<?php
	} else {
		?>
		lwindow = task.create('public', {title:'Welcome to Fly', name:'Welcome to Fly', x:((window.innerWidth/2)-200), y:((window.innerHeight/2)-175), width:400, height:350, location:'<?php echo $_FLY['URL']; ?>/system/components/login.php?', icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>/fly.svg'});
		<?php
	}
	?>
}
shell.dialog = function(msg,content) {
	alert(msg+"\r\n"+content);
}
function login(username,password) {
	document.getElementById('fade').style.transition = 'opacity 1s linear';
	document.getElementById('fade').style.opacity = 1;
	document.getElementById('username').value = username;
	document.getElementById('password').value = password;
	setTimeout(function() {document.getElementById('form').submit();},1100);
}
function updateTime() {
	var currentTime = new Date();
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	var seconds = currentTime.getSeconds();
	var year    = currentTime.getFullYear();
	var month   = currentTime.getMonth()+1; 
	var day     = currentTime.getDate();
	<?php
echo $script_show_military;
?>
	if (minutes < 10){
	    minutes = "0" + minutes;
	}
	if (seconds < 10){
	    seconds = "0" + seconds;
	}
	var v = hours + ":" + minutes + <?php echo $script_show_seconds; ?> + hr;
	var td = "<b>" + hours + ":" + minutes + ":" + seconds + hr + "</b><br>" + (month/1) + "/" + (day/1) + "/" + year;
	setTimeout(updateTime,1000);
	document.getElementById('time').innerHTML=v;
}
function toggleFullScreen() {
  if ((document.fullScreenElement && document.fullScreenElement !== null) ||    
   (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if (document.documentElement.requestFullScreen) {  
      document.documentElement.requestFullScreen();  
    } else if (document.documentElement.mozRequestFullScreen) {  
      document.documentElement.mozRequestFullScreen();  
    } else if (document.documentElement.webkitRequestFullScreen) {  
      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);  
    }  
  } else {  
    if (document.cancelFullScreen) {  
      document.cancelFullScreen();  
    } else if (document.mozCancelFullScreen) {  
      document.mozCancelFullScreen();  
    } else if (document.webkitCancelFullScreen) {  
      document.webkitCancelFullScreen();  
    }  
  }  
}
function powerOptions() {
	<?php echo FlyFileStringProcessor('SprocketComputers.Utilities.PowerOptions')['action']; ?>
}
</script>
<style>
#logo {
	position: fixed;
	top: 0px;
	left: 0px;
	bottom: 0px;
	right: 0px;
	background-color: transparent;
	transition: 1s opacity;
	opacity: 0;
	pointer-events: none;
	background-image: url("<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg");
	background-position: center center;
	background-size: 200px 200px;
	background-repeat: no-repeat;
	z-index: 501;
}
#fade {
	position: fixed;
	top: 0px;
	left: 0px;
	bottom: 0px;
	right: 0px;
	background-color: #000000;
	transition: 1.5s opacity;
	opacity: 100;
	pointer-events: none;
	z-index: 500;
}
#time {
	position: fixed;
	top: 6px;
	left: 6px;
	padding: 8px;
	opacity: 0;
	transition: opacity .5s linear;
}
</style>
</head>
<body onload="onload()">
<audio id="LogonSound"><source src="<?php echo $_FLY['RESOURCE']['URL']['SOUNDS']; ?>startup.mp3"></source></audio>

<form id="form" method="post">
<input type="hidden" id="username" name="user" value="">
<input type="hidden" id="password" name="password" value="">
</form>

<div id="time" onclick="toggleFullScreen()" class="FlyUiTextHighlightControl FlyUiControlScaled">12:00 AM</div>
<div id="fade"></div>
<div id="logo"></div>

</body>
</html>