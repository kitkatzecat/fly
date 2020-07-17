<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';

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

if (substr_count($_GET['app'],'.') > 1) {
	$app = explode('.',$_GET['app']);
	$app = $app[0].'.'.$app[1];
} else {
	$app = $_GET['app'];
}
$process = FlyFileStringProcessor($app);
if ($process !== false && $process['type'] == 'application') {
$manifest = simpleXML_load_file($_FLY['RESOURCE']['PATH']['APPS'].$app.'/ApplicationManifest.xml');

$masks = '';
if (isset($manifest->masks)) {
	$m = '';
	foreach ($manifest->masks->children() as $mask) {
		$mprocess = FlyFileStringProcessor($app.'.'.$mask['id']);
		if ($mprocess != false) {
			if (in_array((string)$mask['hidden'],['true','on','yes'])) {
				$hidden_style = 'opacity:0.8;';
				$hidden_text = '<span style="margin-left:6px;color:#808080;font-size:0.75em;">(hidden)</span>';
			} else {
				$hidden_style = '';
				$hidden_text = '';
			}
			$m .= '<p style="'.$hidden_style.'"><a onclick="window.top.system.command(\'run:'.$mprocess['file'].'\');"><img class="inline-icon" src="'.$mprocess['icon'].'">'.htmlspecialchars($mprocess['name']).'</a>'.$hidden_text.'</p>';
		}
	}
	if ($m !== '') {
		$masks .= '
<p class="shead">Masks</p>
'.$m.'
		';
	} else {
		$masks .= '';
	}
} else {
	$masks .= '';
}

} else {
	echo '<script>console.log(JSON.parse(atob(\''.base64_encode(json_encode($_GET)).'\')));window.location.href = \'apps.php\';</script>';
}
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Files & Applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',index:'filesapps/index.php'},
	{name:'Installed applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',index:'filesapps/appdetails.php'},
	{name:'Details'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title"><img class="title-icon" src="<?php echo $process['icon']; ?>"><?php echo htmlspecialchars($process['name']); ?></div>
<p class="description"><?php echo htmlspecialchars($process['description']); ?></p>

<p class="shead">Publisher</p>
<p><?php echo htmlspecialchars($process['publisher']); ?></p>

<p class="shead">Version</p>
<p><?php echo htmlspecialchars($process['version']); ?></p>

<p class="shead">Date</p>
<p><?php echo date("F j, Y",$process['date']); ?></p>

<p class="shead">Size</p>
<p><?php echo formatFileSize(getFolderSize($_FLY['RESOURCE']['PATH']['APPS'].$process['file'])); ?></p>

<p class="shead">ID</p>
<p><?php echo htmlspecialchars($process['file']); ?></p>

<?php echo $masks; ?>

<div class="buttons">
<button onclick="window.top.system.command('run:<?php echo $process['file']; ?>');"><img class="button-image" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>run.svg">Open</button>
<button onclick="window.top.system.command('run:<?php echo $_FLY['RESOURCE']['PATH']['APPS'].$process['file']; ?>');"><img class="button-image" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg">Open Folder</button>
<button><img class="button-image" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>trash.svg">Uninstall</button>
</div>

</body>
</html>