<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
function onload() {
	Fly.window.size.set(640,480);
	Fly.window.title.set('Screenshot');
}
</script>
<script src="html2canvas.js"></script>
<body>
<button onclick="html2canvas(window.top.document.getElementById('SprocketComputers.MediaPlayer-2017022514104751').jsWindow.windowContent, {onrendered: function(canvas) {document.body.appendChild(canvas);}});">Take a screenshot</button>
</body>
</html>