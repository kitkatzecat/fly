<?php
function isConnected()
{
    // use 80 for http or 443 for https protocol
    $connected = @fsockopen("www.example.com", 80);
    if ($connected){
        fclose($connected);
        return true; 
    }
    return false;
}
if (isConnected()) {
	FlyCommandDisplay('This device is connected to the Internet.');
	FlyCommandError('shell.dialog(\'Connected\',\'This device is connected to the Internet.\',\'Connected\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'mark-check.svg\')');
	FlyCommandReturn('true');
} else {
	FlyCommandDisplay('This device is not connected to the Internet.');
	FlyCommandError('shell.dialog(\'Not connected\',\'This device is not connected to the Internet.\',\'Not Connected\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'mark-x.svg\')');
	FlyCommandReturn('false');
}
?>