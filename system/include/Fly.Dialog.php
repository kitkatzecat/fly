<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.DIALOG');

if (!FlyIncludeCheck('FLY.WINDOW')) {
	include 'Fly.Window.php';
}
?>
<script>
if (typeof Fly == 'undefined') {
	var Fly = {};
}

Fly.dialog = {
	base: {
		modal: true,
		title: "Information",
		message: "Information",
		content: "Something happened",
		sound: "error",
		input: false,
		icon: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg",
		buttons: [
			{
				align: "right",
				image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-o.svg",
				default: true,
				onclick: function() {},
				text: ""
			}
		]
	},
	open: function(attributes={}) {
		attributes = Object.assign(Object.assign({},Fly.dialog.base),attributes);

		var size = Fly.window.size.get();
		var pos = Fly.window.position.get();

		var att64 = btoa(JSON.stringify(attributes));

		var win = {
			title: `${Fly.window.name.get()} - ${attributes.title}`,
			name: Fly.window.name.get(),
			icon: Fly.window.icon.get(),
			x: (pos[0]+(size[0]/2))-250,
			y: (pos[1]+(size[1]/2))-100,
			width: 500,
			height: 100,
			location: '<?php echo $_FLY['RESOURCE']['URL']['COMPONENTS']; ?>dialog3.php?a='+att64,
			close: (!attributes.modal),
			minimize: false,
			expand: false,
			resize: false,
			background: false,
			minheight: 100,
			minwidth: 500
		};

		Fly.window.child.open({modal:attributes.modal,attributes:win},function(frame) {
			frame.window.Dialog.attributes = attributes;
			frame.window.Dialog.opener = window;
		});
	},
	message: function(properties={}) {
		properties = Object.assign({title:'Information',message:'Information',content:'Something happened',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg',callback:function(){}},properties);

		Fly.dialog.open({
			modal: true,
			title: properties.title,
			message: properties.message,
			content: properties.content,
			sound: "error",
			input: false,
			icon: properties.icon,
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-o.svg",
					default: true,
					onclick: properties.callback,
					text: ""
				}
			]
		});
	}
};
</script>