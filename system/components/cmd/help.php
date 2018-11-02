<?php
function getCommands() {
global $_FLY;

$path = $_FLY['RESOURCE']['PATH']['CMD'];
$return = '';

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( !is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor("$path/$file");
			if ($process != false) {
				$return .= str_lreplace('.php','',$file).':, ';
			}
		}

    } 

}
closedir( $dh ); 
$return = str_lreplace(', ','',$return);
return $return;
}
$commands = getCommands();

FlyCommandError('shell.dialog(\'Available commands\',\''.$commands.'<small><br><br>Fly Command Interpreter '.$FlyCommandVersion.'</small>\',\'Available Commands\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
FlyCommandDisplay('Available commands: '.$commands);
?>