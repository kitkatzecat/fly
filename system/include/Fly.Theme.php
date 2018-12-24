<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.THEME');

include $_FLY['RESOURCE']['PATH']['COMPONENTS'].'theme2.php';
?>