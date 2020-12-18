<?php
include 'Fly.Core.php';

FlyIncludeRegister('FLY.STANDARD');

if (!FlyIncludeCheck('FLY.WINDOW')) {
	include 'Fly.Window.Background.php';
}

if (!FlyIncludeCheck('FLY.COREJS')) {
	include 'Fly.CoreJS.php';
}

echo '
<style>
html {
	overscroll-behavior: contain;
}
body {
	margin: 0px;
	overscroll-behavior: contain;
}
</style>
';

?>