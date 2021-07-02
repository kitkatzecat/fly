<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Command.php';
include 'Fly.Dialog.php';
include 'Fly.File.php';
?>
<script src="main.js"></script>
<style>
#imageView {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%,-50%);
	max-width: 100%;
	max-height: 100%;
}
#image {
	visibility: hidden;
}
</style>
</head>
<body style="margin:8px;margin-top:0;" onload="ImgVr.init()">

<img id="image" src="">
<img style="visibility:hidden;" id="imageView" src="">

</body>
</html>