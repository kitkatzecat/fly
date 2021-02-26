<?php
// Fly.SingleFileCommand
error_reporting(0);

include 'Fly.FileProcessor.php';
function getFolders( $path = '.', $dir, $rec,$match,$ext){

$return = [];

$ignore = array( '.', '..' ); 

$dh = @opendir( $path ); 

$contents = array();
if ($dh != false) {
	while( false !== ( $file = readdir( $dh ) ) ){ 
		if (!in_array($file,$ignore)) {
			array_push($contents,$file);
		}
	}
}
natcasesort($contents);

foreach ($contents as $file) {
    if( !in_array( $file, $ignore ) ){ 

        if( is_dir( "$path/$file" ) ){ 
				if ($dir) {
					if ($match !== false) {
						if (fnmatch($match,$file)) {
							array_push($return,$file);
						}
					} else {
						array_push($return,$file);
					}
				}
				if ($rec) {
					$a = getFolders($path.'/'.$file,$dir,$rec,$match,$ext);
					$b = getFiles($path.'/'.$file,$ext,$match);
					array_merge($a,$b);
					array_push($return,$a);
				}
			}
        } 
} 
if ($dh != false) {
	closedir( $dh ); 
}
return $return;
}
function getFiles( $path = '.' ,$ext,$match){

$return = [];

$ignore = array( '.', '..' ); 

$dh = @opendir( $path ); 

$contents = array();
if ($dh != false) {
	while( false !== ( $file = readdir( $dh ) ) ){ 
		if (!in_array($file,$ignore)) {
			array_push($contents,$file);
		}
	}
}
natcasesort($contents);

foreach ($contents as $file) {
    if( !in_array( $file, $ignore ) ){ 

        if( !is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor("$path/$file");
			if ($process != false) {
				if ($ext == false) {
					if ($match != false) {
						if (fnmatch($match,$process["name"])) {
							array_push($return,$process["name"]);
						}
					} else {
						array_push($return,$process["name"]);
					}
				} else if (strtolower($process['extension']) == strtolower($ext)) {
					if ($match != false) {
						if (fnmatch($match,$process["name"])) {
							array_push($return,$process["name"]);
						}
					} else {
						array_push($return,$process["name"]);
					}
				}
			}
		}
	} 
} 
if ($dh != false) {
	closedir( $dh ); 
}
return $return;
}
function draw($array,$level=0) {
	$return = '';
	$spaces = str_repeat(' ',$level*4);
	foreach ($array as $item) {
		if (is_array($item)) {
			$return .= draw($item,$level+1);
		} else {
			$return .= "\r\n".$spaces.$item;
		}
	}
	return $return;
}
	
	$ext = false;
	$rec = false;
	$dir = true;
	$match = false;
	$files = true;
	
	$count = count($cmd);
	for ($i = 0; $i < $count; $i++) {
		if (substr($cmd[$i],0,4) == 'ext=') {
			$ext = substr_replace($cmd[$i],'',0,4);
		} else if (substr($cmd[$i],0,6) == 'match=') {
			$match = substr_replace($cmd[$i],'',0,6);
		} else if (substr($cmd[$i],0,6) == 'files=') {
			if (in_array(strtolower(substr_replace($cmd[$i],'',0,6)),['false','off','no'])) {
				$files = false;
			}
		} else if (substr($cmd[$i],0,4) == 'rec=') {
			if (in_array(strtolower(substr_replace($cmd[$i],'',0,4)),['true','on','yes'])) {
				$rec = true;
			}
		} else if (substr($cmd[$i],0,4) == 'dir=') {
			if (in_array(strtolower(substr_replace($cmd[$i],'',0,4)),['false','off','no'])) {
				$dir = false;
			}
		}
	}
	
	if (file_exists($cmd[0]) && is_dir($cmd[0])) {
		$list1 = getFolders($cmd[0],$dir,$rec,$match,$ext);
		if ($files) {
			$list2 = getFiles($cmd[0],$ext,$match);
			$list = array_merge($list1,$list2);
		} else {
			$list = $list1;
		}
		FlyCommandDisplay(draw($list));
		FlyCommandError('system.command(\'run:'.$cmd[0].'\')');
		FlyCommandReturn(json_encode($list),true);
	} else if (file_exists($_FLY['PATH'].$cmd[0]) && is_dir($_FLY['PATH'].$cmd[0])) {
		$list1 = getFolders($_FLY['PATH'].$cmd[0],$dir,$rec,$match,$ext);
		if ($files) {
			$list2 = getFiles($_FLY['PATH'].$cmd[0],$ext,$match);
			$list = array_merge($list1,$list2);
		} else {
			$list = $list1;
		}
		FlyCommandDisplay(draw($list));
		FlyCommandError('system.command(\'run:'.$_FLY['PATH'].$cmd[0].'\')');
		FlyCommandReturn(json_encode($list),true);
	} else {
		FlyCommandDisplay('The specified folder could not be found.');
		FlyCommandError('shell.dialog(\'Folder not found\',\'The specified folder could not be found.\',\'Not Found\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg\')');
		FlyCommandReturn('false');
	}
?>