<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.Background.php';
include 'Fly.Dialog.php';
?>
<script>
var Stability = {
	checkDesktop: true,
	checkToolbar: true,
	timeout: 5000,
	checkD: function() {
		var good = true;
		if (!window.top.task.isOpen('SprocketComputers.zFileManager.Desktop') && Stability.checkDesktop) {
			good = false;
			Fly.dialog.custom({
				modal: true,
				title: 'Desktop',
				message: 'Desktop closed',
				content: 'The desktop file view application has closed. Would you like to reopen it?',
				sound: 'question',
				checkbox: {
					text: 'Don\'t ask again'
				},
				icon: '<?php echo $_FLY['WORKING_URL']; ?>stability.svg',
				buttons: [
					{
						align: "right",
						image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
						default: true,
						onclick: function(i,c) {
							window.top.system.command('run:SprocketComputers.zFileManager.Desktop');
							Stability.checkDesktop = !c;

							setTimeout(Stability.checkD,Stability.timeout);
						},
						text: ""
					},
					{
						align: "right",
						image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
						onclick: function(i,c) {
							Stability.checkDesktop = !c;

							setTimeout(Stability.checkD,Stability.timeout);
						},
						text: ""
					}
				]
			});
		}
		if (good) {
			setTimeout(Stability.checkD,Stability.timeout);
		}
	},
	checkT: function() {
		var good = true;
		if (!window.top.task.isOpen('SprocketComputers.Utilities.Toolbar') && Stability.checkToolbar) {
			good = false;
			Fly.dialog.custom({
				modal: true,
				title: 'Toolbar',
				message: 'Toolbar closed',
				content: 'The toolbar UI application has closed. Would you like to reopen it?',
				sound: 'question',
				checkbox: {
					text: 'Don\'t ask again'
				},
				icon: '<?php echo $_FLY['WORKING_URL']; ?>stability.svg',
				buttons: [
					{
						align: "right",
						image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
						default: true,
						onclick: function(i,c) {
							window.top.system.command('run:SprocketComputers.Utilities.Toolbar');
							Stability.checkToolbar = !c;

							setTimeout(Stability.checkT,Stability.timeout);
						},
						text: ""
					},
					{
						align: "right",
						image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
						onclick: function(i,c) {
							Stability.checkToolbar = !c;

							setTimeout(Stability.checkT,Stability.timeout);
						},
						text: ""
					}
				]
			});
		}
		if (good) {
			setTimeout(Stability.checkT,Stability.timeout);
		}
	},
	close: function() {
		Fly.dialog.confirm({
			title: '',
			message: 'Close Stability Checker?',
			content: 'Are you sure you want to close Fly Stability Checker?</p><p><a onclick="window.top.system.command(\'run:SprocketComputers.FlyHelp.StabilityCheck\');">What is Stability Checker?</a>',
			callback: function(r) {
				if (r) {
					Fly.window.close();
				}
			}
		});
	}
}
Fly.window.ready = function() {
	if (window.top.task.count('SprocketComputers.FlyHelp.StabilityCheck') > 1) {
		Fly.dialog.message({
			title: '',
			message: 'Fly Stability Checker',
			content: 'This application runs in the background and monitors the stability of critical system processes.',
			callback: Fly.window.close
		});
	} else {
		setTimeout(Stability.checkD,Stability.timeout);
		setTimeout(Stability.checkT,Stability.timeout);
	}
}
Fly.window.onclose = Stability.close;
</script>
</head>
<body>

</body>
</html>