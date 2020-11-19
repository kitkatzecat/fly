var Toolbars = {
	toolbarButtons: {},
	init: function() {
		Toolbar = new Fly.actionbar();
		Toolbar.style.position = 'absolute';
		Toolbar.style.top = '0px';
		Toolbar.style.left = '0px';
		Toolbar.style.width = 'auto';
		Toolbar.id = 'Toolbar';

		Toolbars.toolbarButtons.file = Toolbar.add({text:'File',type:'dropdown',menu:[
			['Open',Player.loadFile,{icon:`${Fly.core['RESOURCE']['URL']['ICONS']}folder.svg`}],
			[''],
			['Attributions',function() {
				Fly.dialog.message({
					title: 'Attributions',
					message: 'Third-party attributions',
					content: 'Visualizations - Wave.js by foobar404 on GitHub</p><p>ID3 tag reading - Mutag.js by chunqiuyiyu on GitHub',
					icon:`${Fly.core['RESOURCE']['URL']['ICONS']}person.svg`
				});
			},{icon:`${Fly.core['RESOURCE']['URL']['ICONS']}person.svg`}]
		]});
		
		Toolbars.toolbarButtons.view = Toolbar.add({text:'View',type:'dropdown',menu:[
			['Visualization',Player.visualization,{icon:`${Fly.core['RESOURCE']['URL']['ICONS']}type/image.svg`}],
			['Filter',Player.filter,{icon:`${Fly.core['RESOURCE']['URL']['ICONS']}colors.svg`}]
		]});
		document.body.appendChild(Toolbar);
	}
};
