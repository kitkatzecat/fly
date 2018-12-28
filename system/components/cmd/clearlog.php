<?php
unlink($_FLY['PATH'].'system/cmd.log');
$display = $_FLY['VERSION_STRING'].' (version '.$_FLY['VERSION_MAJOR'].' build '.$_FLY['VERSION_BUILD'].')';
if ($_FLY['IS_USER']) {
	$display .= ' - Logged in as '.$_FLY['USER']['NAME'].' ('.$_FLY['USER']['ID'].')';
} else {
	$display .= ' - Not logged in';
}
FlyCommandDisplay($display);
?>