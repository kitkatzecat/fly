<!DOCTYPE html>
<head>
<?php
include 'fly.php';

echo '
<script>
Fly.window.focus.take(\''.$_GET['parent_id'].'\');
function CloseDialog() {
	Fly.window.focus.give(\''.$_GET['parent_id'].'\');
	window.top.document.getElementById(\''.$_GET['parent_id'].'\').window.bringToFront();
	Fly.window.close();
}
Fly.window.onclose = CloseDialog;
</script>

';
if ($_GET['dialog'] == 'bookmark_add') {
	goto bookmark_add;
}
exit;

bookmark_add:
?>
<script>
function load() {

}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 4px;
	background-color: #fff;
}
</style>
</head>
<body onload="load()">

<div id="main">
Add bookmark
</div>

</body>
</html>
<?php
exit;

favorites:
?>
</head>
<body>



</body>
</html>
<?php
exit;
?>
