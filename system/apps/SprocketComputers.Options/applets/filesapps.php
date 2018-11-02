<?php
if (in_array($_GET['query'],['true','yes','on'])) {
	echo '
	<applet>
	<name><![CDATA[Files & Applications]]></name>
	<icon><![CDATA[application.svg]]></icon>
	<publisher><![CDATA[Sprocket Computers]]></publisher>
	</applet>	
	';
	exit;
}
function formatFileSize($filesize) {
	$filesize = abs(intval($filesize));
	if ($filesize > 1000000000) {
		$filesize = round($filesize/1000000000,2).' GB';
	} else if ($filesize > 1000000 && $filesize < 10000000000) {
		$filesize = round($filesize/1000000,2).' MB';
	} else if ($filesize > 1000 && $filesize < 10000000) {
		$filesize = round($filesize/1000).' KB';
	} else {
		$filesize = $filesize.' bytes';
	}
	
	return $filesize;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'fileprocessor.php';

echo FlyLoadExtension('SprocketComputers.FileManager','FileChooser');
?>
<style>
body {
	background: #fff;
	margin: 4px;
	margin-top: 60px;
}
.FlyUiMenuItem {
	box-sizing: border-box;
	padding: 6px;
	width: 100%;
}
.header {
	font-size: 18px;
	font-weight: bold;
	box-shadow: 0px 0px 16px #000000;
	background-color: #FFFFFF;
	position: fixed;
	top: 0px;
	left: 0px;
	right: 0px;
	padding: 8px;
	z-index: 2;
	background-image: linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(231,231,231,1) 50%,rgba(215,215,215,1) 51%,rgba(236,236,236,1) 100%);
	cursor: pointer;
}
.header:hover {
	background-image: linear-gradient(0deg, rgba(180, 180, 255, .5), rgba(220, 220, 255, .5));
}
.header:active {
	background-image: linear-gradient(0deg, rgba(150, 150, 225, .5), rgba(190, 190, 255, .5));
}
.heading {
	font-size: 18px;
	font-weight: bold;
	display: block;
	margin-bottom: 4px;
}
</style>
<script>
function navigate(id) {
	window.location.href = '<?php echo CURRENT_URL; ?>?action='+id;
}
</script>
</head>
<body class="FlyUiText">
<div onclick="window.location.href='<?php echo CURRENT_URL; ?>'" class="header FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;margin-right:6px;vertical-align:middle;" src="../icons/application.svg">Files &amp; Applications</div>
<?php
if ($_GET['action']!=='') {
	if ($_GET['action']=='installed') {
		goto installed;
	}
	if ($_GET['action']=='filetypes') {
		goto filetypes;
	}
	if ($_GET['action']=='extensions') {
		goto extensions;
	}
	if ($_GET['action']=='appdetails') {
		goto appdetails;
	}
	if ($_GET['action']=='typedetails') {
		goto typedetails;
	}
	if ($_GET['action']=='components') {
		goto components;
	}
}
if ($_GET['save']!=='') {
	if ($_GET['save']=='installed') {
		goto installed_save;
	}
	if ($_GET['save']=='filetypes') {
		goto filetypes_save;
	}
}
?>

<div id="installed" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Manage installed applications
</div>

<div id="extensions" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	View installed application extensions
</div>

<div id="filetypes" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	Manage file types and associations
</div>

<div id="components" onclick="navigate(this.id);" class="FlyUiMenuItem FlyUiNoSelect">
	View installed system components
</div>
</body>
</html>
<?php
exit;

/*-----------------*/

installed:
function getApps() {

$path = FLY_APPS_PATH;
$return = '';

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor($file);
			if ($process != false) {
				$return .= '<table oncontextmenu="Fly.control.contextMenu(event,[\'<b>Details</b>\',\'Open\'],[\'details(\\\''.$file.'\\\')\',\'window.top.system.eval64(\\\''.base64_encode('system.command(\'run:'.$process["file"].'\')').'\\\')\']);return false;" onclick="details(\''.$file.'\');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="padding:0px;"><td style="width:24px;"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process["icon"].'"></td><td style="width:33%;">'.htmlentities($process["name"]).'</td><td style="width:50%;">'.htmlentities($process["publisher"]).'</td><td style="width:15%;">'.htmlentities($process["version"]).'</td></tr></table>';
			}
		}

    } 

} 
closedir( $dh ); 
return $return;
}
?>
<script>
function details(app) {
	window.location.href = '<?php echo CURRENT_URL ?>?action=appdetails&app='+app;
}
</script>
<span class="heading FlyUiText">Manage installed applications</span>
Manage, change, or uninstall applications that are installed on your computer. Right-click an item for more options.<hr>
<div style="position:absolute;top:136px;left:3px;right:3px;height:24px;border:.1px solid #c0c0c0;padding:3px;border-top-left-radius:3px;border-top-right-radius:3px;background:linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(231,231,231,1) 50%,rgba(215,215,215,1) 51%,rgba(236,236,236,1) 100%);">
<table class="FlyUiText FlyUiNoSelect" style="padding:0px;width:100%;"><td style="width:37%;">Name</td><td style="width:47%;">Publisher</td><td style="width:15%;">Version</td></tr></table>
</div>
<div style="position:absolute;top:166px;left:3px;right:3px;bottom:3px;border:.1px solid #c0c0c0;overflow:auto;padding:4px;">
<button style="position:fixed;z-index:2;right:3px;bottom:3px;" onclick="window.location.reload();"><img src="<?php echo FLY_ICONS_URL; ?>refresh.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Refresh</button>
<?php
echo getApps();
?>
</div>
<?php
goto ext;

installed_save:
exit;

/*-----------------*/

filetypes:
$typesXML = simpleXML_load_file($_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml');
$typesArray = [];
foreach($typesXML as $type)
{
	array_push($typesArray,$type->getName());
}
sort($typesArray);
foreach($typesArray as $typeArray)
{
	$type = $typesXML->$typeArray;
	if (empty($type->action)) {
		$process = '';
	} else {
		$process = FlyFileStringProcessor($type->action);
	}
	$types .= '<table onclick="details(\''.$type->getName().'\')" oncontextmenu="Fly.control.contextMenu(event,[\'<b>Details</b>\'],[\'details(\\\''.$type->getName().'\\\')\']);return false;" class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="padding:0px;"><td style="width:24px;"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.FlyVarsReplace(FlyStringReplaceConstants($type->icon)).'"></td><td style="width:41%;">'.htmlentities($type->description).'</td><td style="width:16%;">'.$type->getName().'</td><td style="width:40%;"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process['icon'].'">'.htmlentities($process['name']).'</td></tr></table>';
}
?>
<script>
function details(type) {
	window.location.href = '<?php echo CURRENT_URL ?>?action=typedetails&type='+type;
}
</script>
<span class="heading FlyUiText">Manage file types and associations</span>
Manage the types of files on your computer and the applications that they open with. Right-click an item for more options.<hr>
<div style="position:absolute;top:136px;left:3px;right:3px;height:24px;border:.1px solid #c0c0c0;padding:3px;border-top-left-radius:3px;border-top-right-radius:3px;background:linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(231,231,231,1) 50%,rgba(215,215,215,1) 51%,rgba(236,236,236,1) 100%);">
<table class="FlyUiText FlyUiNoSelect" style="padding:0px;width:100%;"><td style="width:44%;">Description</td><td style="width:15%;">Extension</td><td style="width:40%;">Opens With</td></tr></table>
</div>
<div style="position:absolute;top:166px;left:3px;right:3px;bottom:3px;border:.1px solid #c0c0c0;overflow:auto;padding:4px;">
<button style="position:fixed;z-index:2;right:3px;bottom:3px;" onclick="window.location.reload();"><img src="<?php echo FLY_ICONS_URL; ?>refresh.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Refresh</button>

<table onclick="" oncontextmenu="" class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="padding:0px;"><td style="width:24px;"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="<?php echo FLY_ICONS_URL ?>mark-plus.svg"></td><td style="width:41%;">Create new file type</td><td style="width:16%;"></td><td style="width:40%;"></td></tr></table>
<?php
echo $types;
?>
</div>
<?php
goto ext;

filetypes_save:
if ($_GET['a']=='iconchange') {
	$typesXML = simpleXML_load_file($_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml');
	$icon = '<![CDATA[%FLY.URL%'.str_replace($_FLY['PATH'],'',$_GET['path']).']]>';
	if (str_replace(basename($_GET['path']),'',$_GET['path'])==$_FLY['RESOURCE']['PATH']['ICONS']) {
		$icon = '<![CDATA[%FLY.RESOURCE.URL.ICONS%'.str_replace($_FLY['RESOURCE']['PATH']['ICONS'],'',$_GET['path']).']]>';
	}
	if (str_replace('type/'.basename($_GET['path']),'',$_GET['path'])==$_FLY['RESOURCE']['PATH']['ICONS']) {
		$icon = '<![CDATA[%FLY.RESOURCE.URL.ICONS%'.str_replace($_FLY['RESOURCE']['PATH']['ICONS'],'',$_GET['path']).']]>';
	}
	$typesXML->$_GET['type']->icon = $icon;
	file_put_contents($_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml',str_replace(']]&gt;',']]>',str_replace('&lt;![CDATA[','<![CDATA[',$typesXML->asXML())));
	echo '<script>window.location = CURRENT_URL+"?action=typedetails&type='.$_GET['type'].'"</script>';
}
if ($_GET['a']=='assocchange') {
	$typesXML = simpleXML_load_file($_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml');
	$action = '<![CDATA['.$_GET['app'].',file=%1]]>';
	$typesXML->$_GET['type']->action = $action;
	file_put_contents($_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml',str_replace(']]&gt;',']]>',str_replace('&lt;![CDATA[','<![CDATA[',$typesXML->asXML())));
	echo '<script>window.location = CURRENT_URL+"?action=typedetails&type='.$_GET['type'].'"</script>';
}
if ($_GET['a']=='assocclear') {
	$typesXML = simpleXML_load_file($_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml');
	$action = '<![CDATA[]]>';
	$typesXML->$_GET['type']->action = $action;
	file_put_contents($_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml',str_replace(']]&gt;',']]>',str_replace('&lt;![CDATA[','<![CDATA[',$typesXML->asXML())));
	echo '<script>window.location = CURRENT_URL+"?action=typedetails&type='.$_GET['type'].'"</script>';
}
exit;

/*-----------------*/

appdetails:
function getFolderSize( $path = '.' ){

$size = "0";
$ignore = array( 'cgi-bin', '.', '..' ); 
// Directories to ignore when listing output. Many hosts 
// will deny PHP access to the cgi-bin. 

$dh = @opendir( $path ); 
// Open the directory to the handle $dh 

while( false !== ( $file = readdir( $dh ) ) ){ 
// Loop through the directory 

    if( !in_array( $file, $ignore ) ){ 
    // Check that this file is not to be ignored 

        if( is_dir( "$path/$file" ) ){ 
			$size += getFolderSize("$path/$file");
		} else {        
			$size += filesize("$path/$file");
        } 

    } 

} 

closedir( $dh ); 
return $size;
// Close the directory handle 
}
if ($_GET['app']=='') {
	echo '<span class="heading FlyUiText">No application specified</span>';
	goto ext;
}
$process = FlyFileStringProcessor($_GET['app']);
$manifest = simpleXML_load_file('../../'.$_GET['app'].'/ApplicationManifest.xml');

if (isset($manifest->masks)) {
	$m = '';
	foreach ($manifest->masks->children() as $mask) {
		$mprocess = FlyFileStringProcessor($_GET['app'].'.'.$mask['id']);
		if ($mprocess != false) {
			if (in_array((string)$mask['hidden'],['true','on','yes'])) {
				$hidden_style = 'opacity:0.8;';
				$hidden_text = '<span style="margin-left:6px;color:#808080;font-size:0.75em;">(hidden)</span>';
			} else {
				$hidden_style = '';
				$hidden_text = '';
			}
			$m .= '<div onclick="window.top.system.command(\'run:'.$mprocess['file'].'\')" style="'.$hidden_style.'" class="FlyUiNoSelect FlyUiMenuItem"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$mprocess['icon'].'">'.htmlentities($mprocess['name']).$hidden_text.'</div>';
		}
	}
	if ($m !== '') {
		$masks .= '
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Masks:</td>
	<td style="width:auto;vertical-align:top;">'.$m.'</td>
</tr>

		';
	} else {
		$masks .= '';
	}
} else {
	$masks .= '';
}
?>
<span class="heading FlyUiText">Application details</span><hr>

<div onclick="window.top.system.command('run:<?php echo $_GET['app']; ?>');" class="FlyUiNoSelect FlyUiMenuItem" style="font-size:24px;"><img style="width:64px;height:64px;margin-right:8px;vertical-align:middle" src="<?php echo $process['icon']; ?>"><?php echo $process['name']; ?></div>

<p style="margin-left:8px;">
<?php echo htmlentities($process['description']); ?>
</p>
<table style="width:100%;">
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Name:</td>
	<td style="width:auto;vertical-align:top;"><?php echo htmlentities($process['name']); ?></td>
</tr>
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Publisher:</td>
	<td style="width:auto;vertical-align:top;"><?php echo htmlentities($process['publisher']); ?></td>
</tr>
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">ID:</td>
	<td style="width:auto;vertical-align:top;"><?php echo htmlentities($_GET['app']); ?></td>
</tr>
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Version:</td>
	<td style="width:auto;vertical-align:top;"><?php echo htmlentities($process['version']); ?></td>
</tr>
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Date:</td>
	<td style="width:auto;vertical-align:top;"><?php echo date("F j, Y",$process['date']); ?></td>
</tr>
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Size:</td>
	<td style="width:auto;vertical-align:top;"><?php echo formatFileSize(getFolderSize(FLY_APPS_PATH.$_GET['app'])); ?></td>
</tr>
<?php
echo $masks;
?>

</table>
<div style="display:inline-block;margin:8px;padding-top:8px;padding-right:8px;background-image:linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(225,225,225,1) 100%);border:.1px solid #C0C0C0;border-radius:3px;">
<button onclick="window.top.system.command('run:<?php echo $_GET['app']; ?>');" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>popout.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Open</button>
<button onclick="window.top.system.command('run:<?php echo FLY_APPS_PATH.$_GET['app']; ?>');" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>folder.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Open Folder</button>
<button disabled style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Uninstall</button>
</div>
<br>
<a onclick="window.location.href='<?php echo CURRENT_URL ?>?action=installed';">&lt; Back to Manage installed applications</a>
<?php
goto ext;

/*-----------------*/

typedetails:
if ($_GET['type']=='') {
	echo '<span class="heading FlyUiText">No type specified</span>';
	goto ext;
}
$typeXML = simpleXML_load_file($_FLY['PATH'].'system/reg/SprocketComputers.Fly/System.FileTypes.xml')->$_GET['type'];
if (in_array($_GET['type'],['als'])) {
	$ow_disabled = 'disabled';
} else {
	$ow_disabled = '';
}
$process = FlyFileStringProcessor($typeXML->action);
?>
<span class="heading FlyUiText">File type details</span><hr>
<div class="FlyUiNoSelect FlyUiMenuItem" style="cursor:default;font-size:24px;"><img style="width:64px;height:64px;margin-right:8px;vertical-align:middle" src="<?php echo FlyVarsReplace(FlyStringReplaceConstants($typeXML->icon)); ?>"><?php echo htmlentities($typeXML->description); ?></div>
<br>
<table style="width:100%;">
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Description:</td>
	<td style="width:auto;vertical-align:top;"><div style="width:460px;overflow-x:auto;"><?php echo htmlentities($typeXML->description); ?></div></td>
</tr>
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Extension:</td>
	<td style="width:auto;vertical-align:top;"><?php echo htmlentities($typeXML->getName()); ?></td>
</tr>
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Icon:</td>
	<td style="width:auto;vertical-align:top;"><div style="width:460px;overflow-x:auto;"><?php echo htmlentities(str_replace($_FLY['URL'],'./',FlyVarsReplace($typeXML->icon))); ?></div></td>
</tr>
<tr>
	<td style="width:100px;font-weight:bold;padding-left:8px;text-align:left;vertical-align:top;">Opens With:</td>
	<td style="width:auto;vertical-align:top;">
	<?php
	if ($process) {
		echo '<div onclick="window.top.system.command(\'run:'.$process['file'].'\')" style="width:40%;" class="FlyUiNoSelect FlyUiMenuItem"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process['icon'].'">'.htmlentities($process['name']).'</div></td>';
		$clear = 'ClearAssoc()';
	} else {
		echo 'none';
		$clear = 'disabled';
	}
	?>
</tr>
</table>
<div style="display:none;" id="Browser-ChangeIcon"></div><script>Fly.extension.replace('Browser-ChangeIcon','SprocketComputers.FileManager','FileChooser');</script>
<div style="display:none;" id="Chooser-SetAssociation"></div><script>Fly.control.replace('Chooser-SetAssociation','Fly.control.applicationChooser');</script>
<div style="display:inline-block;margin:8px;padding-top:8px;padding-right:8px;background-image:linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(225,225,225,1) 100%);border:.1px solid #C0C0C0;border-radius:3px;">
<button <?php echo $ow_disabled; ?> id="button-openwith" onclick="Fly.control.contextMenu([document.getElementById('button-openwith').offsetLeft,(parseInt(document.getElementById('button-openwith').offsetTop)+parseInt(document.getElementById('button-openwith').offsetHeight))],['Clear association','Set association'],['<?php echo $clear; ?>','document.getElementById(\'Chooser-SetAssociation\').browse();']);" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>popout.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Application Association ▾</button>
<button onclick="document.getElementById('Browser-ChangeIcon').browse()" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>file.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Change Icon</button>
<button onclick="Fly.control.input('Edit description','Enter a description for files of the type &quot;<?php echo htmlentities($typeXML->getName()); ?>&quot;.','Edit Description','<?php echo FlyVarsReplace(FlyStringReplaceConstants($typeXML->icon)); ?>',function(){});" style="margin-left:8px;margin-bottom:8px;"><img src="<?php echo FLY_ICONS_URL; ?>pencil.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Edit Description</button>
</div>
<br>
<a onclick="window.location.href='<?php echo CURRENT_URL ?>?action=filetypes';">&lt; Back to Manage file types and associations</a>

<script>
document.getElementById('Browser-ChangeIcon').onchange = function() {
	var browser = document.getElementById('Browser-ChangeIcon');
	if (browser.vars.extension !=='svg') {
		setTimeout(function(){window.top.shell.dialog('Selected file cannot be used','The selected file cannot be used as an icon because icons are required to be images of the type "svg".','Options','<?php echo FLY_ICONS_URL; ?>warning.svg')},200);
	} else {
		window.location = CURRENT_URL+'?save=filetypes&a=iconchange&path='+browser.vars.path+'&type=<?php echo $typeXML->getName(); ?>';
	}
}
document.getElementById('Chooser-SetAssociation').onchange = function() {
	var browser = document.getElementById('Chooser-SetAssociation');
	window.location = CURRENT_URL+'?save=filetypes&a=assocchange&app='+browser.getAttribute('app')+'&type=<?php echo $typeXML->getName(); ?>';
}
function ClearAssoc() {
	window.location = CURRENT_URL+'?save=filetypes&a=assocclear&type=<?php echo $typeXML->getName(); ?>';
}
</script>
<?php
goto ext;

extensions:
function getExtensions() {

$path = FLY_APPS_PATH;
$return = '';

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor($file);
			if ($process != false) {
				$return .= '<table oncontextmenu="Fly.control.contextMenu(event,[\'<b>Details</b>\',\'Open\'],[\'details(\\\''.$file.'\\\')\',\'window.top.system.eval64(\\\''.base64_encode('system.command(\'run:'.$process["file"].'\')').'\\\')\']);return false;" onclick="details(\''.$file.'\');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="padding:0px;"><td style="width:24px;"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process["icon"].'"></td><td style="width:33%;">'.htmlentities($process["name"]).'</td><td style="width:50%;">'.htmlentities($process["publisher"]).'</td><td style="width:15%;">'.htmlentities($process["version"]).'</td></tr></table>';
			}
		}

    } 

} 
closedir( $dh ); 
return $return;
}
?>
<script>
function details(app) {
	window.location.href = '<?php echo CURRENT_URL ?>?action=appdetails&app='+app;
}
</script>
<span class="heading FlyUiText">View installed application extensions</span>
View extensions that are installed with applications on your computer. Right-click an item for more options.<hr>
<div style="position:absolute;top:136px;left:3px;right:3px;height:24px;border:.1px solid #c0c0c0;padding:3px;border-top-left-radius:3px;border-top-right-radius:3px;background:linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(231,231,231,1) 50%,rgba(215,215,215,1) 51%,rgba(236,236,236,1) 100%);">
<table class="FlyUiText FlyUiNoSelect" style="padding:0px;width:100%;"><td style="width:37%;">Name</td><td style="width:47%;">Publisher</td><td style="width:15%;">Version</td></tr></table>
</div>
<div style="position:absolute;top:166px;left:3px;right:3px;bottom:3px;border:.1px solid #c0c0c0;overflow:auto;padding:4px;">
<button style="position:fixed;z-index:2;right:3px;bottom:3px;" onclick="window.location.reload();"><img src="<?php echo FLY_ICONS_URL; ?>refresh.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Refresh</button>
<?php
echo getExtensions();
?>
</div>
<?php
goto ext;


/*-----------------*/


components:
function getComponents() {

$path = FLY_PATH.'/system/components/';
$return = '';
$componentsXML = simpleXML_load_file(FLY_PATH.'/system/components/components.xml');

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( !is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor("$path/$file");
			if ($process != false) {
				$component = $componentsXML->xpath('/components/component[@file="'.strtolower($file).'"]')[0];
				$return .= '<table oncontextmenu="" onclick="window.top.shell.dialog(\''.htmlspecialchars($component->name).'\',\''.htmlspecialchars($component->description).'&lt;br&gt;&lt;br&gt;&lt;b&gt;Filename:&lt;/b&gt; '.$file.'&lt;br&gt;&lt;b&gt;Version:&lt;/b&gt; '.htmlspecialchars($component->version).'&lt;br&gt;&lt;b&gt;Size:&lt;/b&gt; '.formatFileSize(filesize("$path/$file")).'&lt;br&gt;&lt;b&gt;Updated:&lt;/b&gt; '.date("M j, Y",filemtime("$path/$file")).'&lt;br&gt;\',\'Options - Component Details\',\''.FLY_ICONS_URL.'options.svg\');" class="FlyUiMenuItem FlyUiText FlyUiNoSelect" style="padding:0px;"><tr><td style="width:24px;"><img style="width:24px;height:24px;vertical-align:middle;margin-right:6px;" src="'.$process["icon"].'"></td><td style="width:33%;">'.htmlentities($process["name"]).'</td><td style="width:50%;">'.htmlentities((string)$component->name).'</td><td style="width:15%;">'.htmlentities((string)$component->version).'</td></tr></table>';
			}
		}

    } 

} 
closedir( $dh ); 
return $return;
}
?>
<span class="heading FlyUiText">View installed system components</span>
These are the system components that are installed on your computer. These are essential for Fly core functions.<hr>
<div style="position:absolute;top:136px;left:3px;right:3px;height:24px;border:.1px solid #c0c0c0;padding:3px;border-top-left-radius:3px;border-top-right-radius:3px;background:linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(231,231,231,1) 50%,rgba(215,215,215,1) 51%,rgba(236,236,236,1) 100%);">
<table class="FlyUiText FlyUiNoSelect" style="padding:0px;width:100%;"><td style="width:37%;">Filename</td><td style="width:47%;">Name</td><td style="width:15%;">Version</td></tr></table>
</div>
<div style="position:absolute;top:166px;left:3px;right:3px;bottom:3px;border:.1px solid #c0c0c0;overflow:auto;padding:4px;">
<button style="position:fixed;z-index:2;right:3px;bottom:3px;" onclick="window.location.reload();"><img src="<?php echo FLY_ICONS_URL; ?>refresh.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px">Refresh</button>
<?php
echo getComponents();
?>
</div>
<?php
goto ext;

/*-----------------*/

ext:
echo '</body></html>';
?>