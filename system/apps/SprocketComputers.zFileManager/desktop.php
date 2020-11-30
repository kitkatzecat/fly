<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Core.php';
include 'Fly.Registry.php';
include 'Fly.Window.Background.php';
include 'Fly.Dialog.php';
?>
</head>
<script>
var Desktop = {
	init: function() {
		if (window.top.task.count('<?php echo $_FLY['APP']['ID']; ?>.Desktop') > 1) {
			Fly.dialog.message({title:'',message:'Desktop already open',content:'Another instance of the desktop file view is already open.',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',callback:Fly.window.close});
		} else {

		Desktop.desktop = document.createElement("div");
		Desktop.desktop.style.position = 'fixed';
		Desktop.desktop.style.top = window.top.ui.toolbar.offsetHeight+'px';
		Desktop.desktop.style.left = '0px';
		Desktop.desktop.style.right = '0px';
		Desktop.desktop.style.bottom = '0px';
		Desktop.desktop.style.zIndex = '0';
		Desktop.desktop.style.transition = 'opacity .2s linear';
		Desktop.desktop.style.opacity = '0';

		Desktop.frame = document.createElement('iframe');
		Desktop.frame.style.width = '100%';
		Desktop.frame.style.height = '100%';
		Desktop.frame.style.border = 'none';
		Desktop.frame.src = '<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.zFileManager/list.php?v=<?php echo FlyVarsReplace(FlyUserRegistryGet('ViewDesktop','SprocketComputers.zFileManager')); ?>&p=<?php echo FlyVarsReplace(FlyUserRegistryGet('DesktopPath','SprocketComputers.Options')); ?>';
		Desktop.frame.addEventListener('load',function() {
				setTimeout(function() {
					if (Desktop.desktop.style.opacity == '0') {
						//Desktop.desktop.style.opacity = '1';
						Fly.dialog.message({title:'Load Error',message:'Desktop load error',content:'The desktop file view has failed to load correctly. This could be due to an unsupported view.</p><p>The desktop application will now close.',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg',callback:Fly.window.onclose});
					}
				},10000);
			});

		Desktop.desktop.appendChild(Desktop.frame);
		Desktop.desktop.frame = Desktop.frame;

		window.top.document.body.appendChild(Desktop.desktop);

		window.top.ui.desktop = Desktop.desktop;

		}
	},
	opacity: function(opacity) {
		Desktop.desktop.style.opacity = opacity;
	},
	close: function() {
		try {
			Desktop.desktop.remove();
		} catch(e) {}
		Fly.window.close();
	}
};
Fly.window.ready = Desktop.init;
Fly.window.onclose = Desktop.close;
</script>
<body>

</body>
</html>