<!DOCTYPE html >
<html>
<head>
<?php
include 'Fly.Core.php';
include 'Fly.Window.Background.php';
include 'Fly.Dialog.php';
?>
<script>
Fly.window.ready = function() {
	Fly.dialog.custom({
		modal: true,
		title: '',
		message: 'Power',
		content: 'What do you want the computer to do?',
		sound: "question",
		input: {
			type: "list",
			options: [
				{text:'Shut down',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'},
				{text:'Restart',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg'},
				{text:'Sleep',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-o.svg'}
			]
		},
		icon: '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>power.svg',
		buttons: [
			{
				align: "right",
				image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
				default: true,
				validate: true,
				onclick: function(i) {
					Fly.window.close();
				}
			},
			{
				align: "right",
				validate: false,
				image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
				onclick: Fly.window.close
			}
		]
	})
}
</script>
</head>
<body>
</body>
</html>