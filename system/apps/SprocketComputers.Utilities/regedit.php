<!DOCTYPE html>
<html>
<head>
<?php

include 'fly.php';

function getFolders( $path = '.' , $level = 3){

	global $_FLY;

	$ignore = array( '.', '..' ); 
	
	$dh = @opendir( $path ); 
	
	$margin = ($level*4);
	
	$return = '';
	
	while( false !== ( $file = readdir( $dh ) ) ){ 
	
		if( !in_array( $file, $ignore ) && trimslashes("$path/$file") !== trimslashes($_FLY['REGISTRY'].'/SprocketComputers.Fly') ){ 
	
			if( is_dir( "$path/$file" ) ){ 
				$rand = rand();
				$return .= '<li onclick="toggle(\''.$rand.'\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick=""><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'type/settingfolder.svg">'.$file.'</li>';
				$return .= '<ul style="margin-left:'.$margin.'px;display:none;" id=\''.$rand.'\'>';
				$return .= getFolders("$path/$file", ($level+1));
				$return .= '</ul>';
			} 
	
		} 
	
	} 
	closedir( $dh ); 
	return $return;
}
?>
<script>
function toggle(id) {
	var obj = document.getElementById(id);
	if (obj.style.display == 'none') {
		obj.style.display = 'block';
	} else {
		obj.style.display = 'none';
	}
}
</script>
<style>
ul {
	padding-left: 0px;
}
.item {
	display: block;
	list-style-type: none;
	font-size: 14px;
	line-height: 20px;
	cursor: pointer;
	white-space: nowrap;
	padding-top: 6px;
	margin-top: -8px;
	padding-bottom: 6px;
	margin-bottom: -8px;
}
.icon {
	width: 16px;
	height: 16px;
	margin-right: 2px;
	vertical-align: middle;
}
</style>
</head>
<body>

<?php
echo '<div id="index">
<ul style="padding:6px;" id="folders">
<li onclick="toggle(\'root-user\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick=""><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg">'.$_FLY['USER']['NAME'].'</li>
<ul style="margin-left:12px;display:block;" id=\'root-user\'>';
echo getFolders($_FLY['USER']['PATH'].'/data/registry',4);
echo '</ul>
<hr style="margin-top:16px;margin-bottom:16px;">
<li onclick="toggle(\'root-computer\')" class="FlyUiTextHover FlyUiNoSelect item" ondblclick=""><img class="icon FlyUiNoSelect" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'computer.svg">Computer</li>
<ul style="margin-left:12px;display:block;" id=\'root-computer\'>';
echo getFolders($_FLY['REGISTRY'],4);
echo '</ul>
</ul></div>';
?>

</body>
</html>