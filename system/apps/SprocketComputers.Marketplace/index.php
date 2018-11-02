<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
if (in_array($_GET['control'],['on','yes','true'])) {
	echo '<script>window.location.href = \'control.php?'.$_SERVER['QUERY_STRING'].'\';</script>';
}
?>
<style>
body {
	background: #fff;
	margin: 4px;
	margin-top: 60px;
}
.FlyUiMenuItem {
	box-sizing: border-box;
	padding: 6px;
	width: 100%;
}
a {
	color: rgb(36,99,203);
	text-decoration: none;
}
a:hover {
	cursor: pointer;
	text-decoration: underline;
}
a:active {
	color: rgb(36,39,143);
}
.header {
	font-size: 18px;
	font-weight: bold;
	box-shadow: 0px 0px 16px #000000;
	background-color: #FFFFFF;
	position: fixed;
	top: 0px;
	left: 0px;
	right: 0px;
	padding: 8px;
	z-index: 2;
	background-image: linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(231,231,231,1) 50%,rgba(215,215,215,1) 51%,rgba(236,236,236,1) 100%);
	cursor: pointer;
}
.header:hover {
	background-image: linear-gradient(0deg, rgba(180, 180, 255, .5), rgba(220, 220, 255, .5));
}
.header:active {
	background-image: linear-gradient(0deg, rgba(150, 150, 225, .5), rgba(190, 190, 255, .5));
}
.heading {
	font-size: 18px;
	font-weight: bold;
	display: block;
	margin-bottom: 4px;
}
</style>
</head>
<body class="FlyUiText">
<div class="header FlyUiText FlyUiNoSelect"><img style="height:32px;margin-right:6px;vertical-align:middle;" src="logo.png"></div>
<p style="margin-top:4px;">Thank you for your interest in the Fly Marketplace, but this feature is not yet available. Please check back soon for updates!</p>
</body>
</html>