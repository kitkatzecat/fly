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
		checkbox: false,
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
	custom: function(attributes={}) {
		attributes = Object.assign(Object.assign({},Fly.dialog.base),attributes);

		var size = Fly.window.size.get();
		var pos = Fly.window.position.get();

		var att64 = btoa(JSON.stringify(attributes));

		var title = (!attributes.hasOwnProperty('title') || attributes.title == '' ? Fly.window.name.get() : `${Fly.window.name.get()} - ${attributes.title}`);

		var win = {
			title: title,
			name: Fly.window.name.get(),
			icon: Fly.window.icon.get(),
			x: (pos[0]+(size[0]/2))-200,
			y: (pos[1]+(size[1]/2))-100,
			width: 400,
			height: 100,
			location: '<?php echo $_FLY['RESOURCE']['URL']['COMPONENTS']; ?>dialog3.php?a='+att64,
			close: (!attributes.modal),
			minimize: false,
			expand: false,
			resize: false,
			background: false,
			minheight: 100,
			minwidth: 400
		};

		Fly.window.child.open({modal:attributes.modal,attributes:win},function(frame) {
			frame.window.Dialog.attributes = attributes;
			frame.window.Dialog.opener = window;
		});
	},
	message: function(properties={}) {
		properties = Object.assign({title:'Information',message:'Information',content:'Something happened.',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg',callback:function(){}},properties);

		Fly.dialog.custom({
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
	},
	confirm: function(properties={}) {
		properties = Object.assign({title:'Confirm',message:'Confirm',content:'Are you sure?',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg',callback:function(){}},properties);

		Fly.dialog.custom({
			modal: true,
			title: properties.title,
			message: properties.message,
			content: properties.content,
			sound: "question",
			input: false,
			icon: properties.icon,
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
					default: true,
					onclick: function() {
						properties.callback(true);
					},
					text: ""
				},
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
					onclick: function() {
						properties.callback(false);
					},
					text: ""
				}
			]
		});
	},
	select: function(properties={}) {
		properties = Object.assign({title:'Select',message:'Select',content:'Select an option.',options:[],icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg',callback:function(){}},properties);

		Fly.dialog.custom({
			modal: true,
			title: properties.title,
			message: properties.message,
			content: properties.content,
			sound: "question",
			input: {
				type: "select",
				options: properties.options
			},
			icon: properties.icon,
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
					default: true,
					onclick: function(i) {
						properties.callback(i);
					}
				},
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"
				}
			]
		});
	},
	list: function(properties={}) {
		properties = Object.assign({title:'List',message:'Select',content:'Select an option.',options:[],icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg',callback:function(){}},properties);

		Fly.dialog.custom({
			modal: true,
			title: properties.title,
			message: properties.message,
			content: properties.content,
			sound: "question",
			input: {
				type: "list",
				options: properties.options
			},
			icon: properties.icon,
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
					default: true,
					validate: true,
					onclick: function(i) {
						properties.callback(i);
					}
				},
				{
					align: "right",
					validate: false,
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"
				}
			]
		});
	},
	input: function(properties={}) {
		properties = Object.assign({title:'Input',message:'Input',content:'Input some text.',validate:false,validateMessage:'',placeholder:'',value:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg',callback:function(){}},properties);

		Fly.dialog.custom({
			modal: true,
			title: properties.title,
			message: properties.message,
			content: properties.content,
			sound: "question",
			input: {
				type: "text",
				placeholder: properties.placeholder,
				value: properties.value,
				validate: properties.validate,
				validateMessage: properties.validateMessage
			},
			icon: properties.icon,
			buttons: [
				{
					align: "right",
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
					default: true,
					validate: properties.validate,
					onclick: function(i) {
						properties.callback(i);
					}
				},
				{
					align: "right",
					validate: false,
					image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"
				}
			]
		});
	},
	color: function(properties={}) {
		properties = Object.assign({title:'Pick Color',value:[255,0,0],custom:false,callback:function(){}},properties);

		var pos = Fly.window.position.get();

		var att64 = btoa(JSON.stringify(properties));

		var win = {
			title: `${Fly.window.name.get()} - ${properties.title}`,
			name: Fly.window.name.get(),
			icon: Fly.window.icon.get(),
			x: pos[0]+32,
			y: pos[1]+32,
			width: 240,
			height: 324,
			location: '<?php echo $_FLY['RESOURCE']['URL']['COMPONENTS']; ?>dialog3.php?d=color&a='+att64,
			close: false,
			minimize: false,
			expand: false,
			resize: false,
			background: false,
			minheight: 324,
			minwidth: 240
		};

		Fly.window.child.open({modal:true,attributes:win},function(frame) {
			frame.window.Dialog.attributes = properties;
			frame.window.Dialog.opener = window;
		});
	}
};
</script>