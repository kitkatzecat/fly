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
		
		let vis = [];
		Player.visualizations.forEach(function(v) {
			vis.push([v,function() {Player.initVisualize(v)}]);
		});
		
		let fil = [];
		Player.filters.forEach(function(f) {
			fil.push([f[0],function() {Player.setFilter(f[1])}]);
		});

		Toolbars.toolbarButtons.view = Toolbar.add({text:'View',type:'dropdown',menu:[
			['Visualization',vis,{icon:`${Fly.core['RESOURCE']['URL']['ICONS']}type/image.svg`}],
			['Filter',fil,{icon:`${Fly.core['RESOURCE']['URL']['ICONS']}colors.svg`}],
			[''],
			['Toggle Infobar',function() {Toolbars.infobar.toggle.toggle()}]
		]});
		document.body.appendChild(Toolbar);

		Toolbars.infobar = document.createElement('div');
		Toolbars.infobar.id = 'Infobar';
		Toolbars.infobar.className = 'FlyUiText';
		Toolbars.infobar.style.display = 'none';
		Toolbars.infobar.innerText = 'Status';
		document.getElementById('Main').appendChild(Toolbars.infobar);
		Toolbars.infobar.toggle = new Fly.CS.toggle(
			function() {
				Toolbars.infobar.style.display = 'block';
				document.getElementById('Video').classList.add('Infobar');
			},
			function() {
				Toolbars.infobar.style.display = 'none';
				document.getElementById('Video').classList.remove('Infobar');
			},
			false
		);
	}
};
