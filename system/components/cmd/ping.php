<?php
function isUp($host)
{
    // use 80 for http or 443 for https protocol
	if(substr($host,0,4)=='http') {
		$host = str_replace('https://','',$host);
		$host = str_replace('http://','',$host);
	}
    $connected = @fsockopen($host, 80);
    if ($connected){
        fclose($connected);
        return true; 
    }
    return false;
}
if (isUp($cmd[0])) {
	FlyCommandDisplay('"'.$cmd[0].'" is up.');
	FlyCommandError('shell.dialog(\'Up\',\'"'.$cmd[0].'" is up.\',\'Up\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'mark-check.svg\')');
} else {
	FlyCommandDisplay('"'.$cmd[0].'" could not be connected to.');
	FlyCommandError('shell.dialog(\'Not connected\',\'"'.$cmd[0].'" could not be connected to.\',\'Not Connected\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'mark-x.svg\')');
}
?>