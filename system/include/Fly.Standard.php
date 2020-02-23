<?php
include 'Fly.Core.php';

FlyIncludeRegister('FLY.STANDARD');

if (!FlyIncludeCheck('FLY.THEME')) {
	include 'Fly.Theme.php';
}

if (!FlyIncludeCheck('FLY.WINDOW')) {
	include 'Fly.Window.php';
}

if (!FlyIncludeCheck('FLY.ACTIONMENU')) {
	include 'Fly.Actionmenu.php';
}

if (!FlyIncludeCheck('FLY.ACTIONBAR')) {
	include 'Fly.Actionbar.php';
}

if (!FlyIncludeCheck('FLY.CONTROLS')) {
	include 'Fly.Controls.php';
}

if (!FlyIncludeCheck('FLY.EXTENSIONS')) {
	include 'Fly.Extensions.php';
}

if (!FlyIncludeCheck('FLY.COREJS')) {
	include 'Fly.CoreJS.php';
}

FlyTheme(['text','controls']);

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