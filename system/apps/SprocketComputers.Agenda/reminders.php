<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<style>
body {
	background-color: #ffffff;
	margin: 4px;
	margin-top: 60px;
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
<body>
<div class="header FlyUiText FlyUiNoSelect"><img style="width:32px;height:32px;margin-right:6px;vertical-align:middle;" src="<?php echo FLY_ICONS_URL; ?>index.svg">Reminders
<button style="float:right;margin-top:2px;"><img style="width:16px;height:16px;margin-right:6px;vertical-align:middle;" src="<?php echo FLY_ICONS_URL; ?>mark-plus.svg">New reminder</button>
</div>

</body>
</html>