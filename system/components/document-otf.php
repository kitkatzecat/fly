<!DOCTYPE html >
<html>
<head>
<?php
include 'fly.php';
?>
<style>
body {
	margin: 0px;
	padding: 0px;
}
</style>
</head>
<body>
<?php
echo base64_decode($_GET['content']);
?>
</body>
</html>