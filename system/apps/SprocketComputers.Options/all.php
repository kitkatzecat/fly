<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'registry.php';
include 'fileprocessor.php';

function getApplets() {

$path = 'applets';
$return = '';

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( !is_dir( "$path/$file" ) ){ 
			$query = simpleXML_load_string(file_get_contents(str_replace('https://','http://',WORKING_URL).'applets/'.$file.'?query=yes'));
			$return .= '<div onclick="window.location.href=\'applets/'.$file.'\';" class="FlyUiMenuItem FlyUiNoSelect" style="cursor:pointer;padding-bottom:4px;"><img src="icons/'.$query->icon.'" style="width:32px;height:32px;vertical-align:middle;margin-right:6px;">'.$query->name.'</div>';
		}

    } 

} 
closedir( $dh ); 
return $return;
}
?>
<style>
.FlyUiMenuItem {
	box-sizing: border-box;
	cursor: default;
}
.head {
	font-weight: bold;
	font-size: 24px;
	margin-bottom: 8px;
	margin-left: 4px;
	display: block;
}
li {
	margin-left: 16px;
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
body {
	background: #fff;
	margin: 4px;
	margin-top: 12px;
	padding-bottom: 64px;
}
</style>
</head>
<body>
<div class="head FlyUiText">All Items</div>
<?
echo getApplets();
?>
</body>
</html>