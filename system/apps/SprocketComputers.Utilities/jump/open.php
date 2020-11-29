<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';
?>
<script>
Fly.window.ready = function() {
	Jump.init();
}
var Jump = {
	init: function() {
		Jump.control = document.createElement('div');
		Jump.control.className = 'FlyUiControlScaled';
		Jump.control.style.borderTopRightRadius = '0';
		Jump.control.style.borderTopLeftRadius = '0';
		Jump.control.style.width = '500px';
		Jump.control.style.height = '0px';
		Jump.control.style.position = 'fixed';
		Jump.control.style.transition = 'height .2s ease-out';
		Jump.control.style.top = (typeof window.top.ui.toolbar !== 'undefined' ? window.top.ui.toolbar.offsetHeight : 0)+'px';
		Jump.control.style.left = '0px';
		Jump.control.style.zIndex = '4999999';

		Jump.cover = document.createElement('div');
		Jump.cover.style.position = 'fixed';
		Jump.cover.style.top = '0';
		Jump.cover.style.left = '0';
		Jump.cover.style.bottom = '0';
		Jump.cover.style.right = '0';
		Jump.cover.style.backgroundColor = 'rgb(0,0,0)';
		Jump.cover.style.opacity = '0';
		Jump.cover.style.transition = 'opacity .2s linear';
		Jump.cover.style.zIndex = '4999998';
		Jump.cover.onclick = Jump.close

		Jump.frame = document.createElement('iframe');
		Jump.frame.style.width = '500px';
		Jump.frame.style.height = '500px';
		Jump.frame.frameBorder = '0';
		Jump.frame.onload = Jump.hook;
		Jump.frame.src = '<?php echo $_FLY['WORKING_URL']; ?>';

		window.top.document.body.appendChild(Jump.control);
		Jump.control.appendChild(Jump.frame);
		window.top.document.body.appendChild(Jump.cover);
		setTimeout(function() {
			Jump.control.style.height = '500px';
			Jump.cover.style.opacity = '0.2';
		},10);
	},
	close: function() {
		Jump.control.style.transition = 'height .2s ease-in';
		Jump.control.style.height = '0';
		Jump.cover.style.opacity = '0';
		Jump.cover.style.pointerEvents = 'none';
		setTimeout(function() {
			Jump.cover.remove();
			Jump.control.remove();
			Fly.window.close();
		},210);
	},
	hook: function() {
		Jump.frame.contentWindow.window.close = Jump.close;
	}
}
Fly.window.onclose = Jump.close;
</script>
</head>
<body>

</body>
</html>