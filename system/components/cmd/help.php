<?php
function getCommands() {
global $_FLY;

$path = $_FLY['RESOURCE']['PATH']['CMD'];
$return = [];

$ignore = array( 'cgi-bin', '.', '..' ); 

$dh = @opendir( $path ); 

while( false !== ( $file = readdir( $dh ) ) ){ 

    if( !in_array( $file, $ignore ) ){ 

        if( !is_dir( "$path/$file" ) ){ 
			$process = FlyFileStringProcessor("$path/$file");
			if ($process != false) {
				array_push($return,str_lreplace('.php','',$file));
			}
		}

    } 

}
closedir( $dh ); 
return $return;
}
$commands = getCommands();

$display = '';
foreach ($commands as $c) {
	$display .= $c.':, ';
}
$display = str_lreplace(', ','',$display);

FlyCommandReturn(json_encode($commands));
FlyCommandError('shell.dialog(\'Available commands\',\''.$display.'<small><br><br>Fly Command Interpreter '.$FlyCommandVersion.'</small>\',\'Available Commands\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
FlyCommandDisplay('Available commands: '.$display);
?>