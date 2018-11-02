Tools.Text = function() {
	CurrentTool.reset();
	CurrentTool = Tools.Text;
	
	Actionbars.Toolsbar.toggleAllOff();
	Object.keys(Tools).forEach(function(key,index) {
		try {
	 	   Tools[key].bar.parentNode.removeChild(Tools[key].bar);
		} catch(err) {}
	});
	
	Actionbars.Toolsbar.buttons.text.toggleOn();
	document.body.appendChild(Tools.Text.bar);

	ColorChange = Tools.Text.setColor;
	
	canvas.style.cursor = 'text';
	ctx.fillStyle = Color;
	ctx.font = Tools.Text.fontStyle+' '+Tools.Text.fontSize+' '+Tools.Text.fontFace;

	canvas.addEventListener('click', Tools.Text.onClick, false);
}
Tools.Text.reset = function() {
	ColorChange = function() {};
	canvas.style.cursor = 'crosshair';
	
	canvas.removeEventListener('click', Tools.Text.onClick, false);
}
Tools.Text.onClick = function() {
	Tools.Text.pos = {x: mouse.x, y: mouse.y};
	Fly.control.input('Enter text','Enter the text to be added to the image.','Enter Text',Vars.iconsUrl+'pencil.svg',Tools.Text.returnText);
}
Tools.Text.setSize = function(size) {
	Tools.Text.fontSize = size+'px';
	ctx.font = Tools.Text.fontStyle+' '+Tools.Text.fontSize+' '+Tools.Text.fontFace;
}
Tools.Text.setFace = function(face) {
	Tools.Text.fontFace = face;
	ctx.font = Tools.Text.fontStyle+' '+Tools.Text.fontSize+' '+Tools.Text.fontFace;
}
Tools.Text.setStyle = function(style) {
	Tools.Text.fontStyle = style;
	ctx.font = Tools.Text.fontStyle+' '+Tools.Text.fontSize+' '+Tools.Text.fontFace;
}
Tools.Text.setColor = function() {
	ctx.fillStyle = Color;
}
Tools.Text.returnText = function(text) {
	ctx.fillText(text,Tools.Text.pos.x,Tools.Text.pos.y);
	UpdateState();
}
Tools.Text.fontStyle = 'normal';
Tools.Text.fontSize = '20px';
Tools.Text.fontFace = 'sans-serif';
Tools.Text.pos = {x: 0, y: 0};
Tools.Text.bar = new Fly.actionbar();
Tools.Text.bar.style.position = 'absolute';
Tools.Text.bar.style.bottom = '0px';
Tools.Text.bar.style.width = 'auto';
Tools.Text.bar.style.right = '0px';
Tools.Text.buttons = {};

Tools.Text.buttons.font = Tools.Text.bar.add({text:'Font',type:'dropdown',menu:[
	['<span style="font-family:sans-serif;">Sans Serif</span>',function(){Tools.Text.setFace('sans-serif')}],
	['<span style="font-family:serif;">Serif</span>',function(){Tools.Text.setFace('serif')}],
	['<span style="font-family:monospace;">Monospace</span>',function(){Tools.Text.setFace('monospace')}],
	['<span style="font-family:cursive;">Cursive</span>',function(){Tools.Text.setFace('cursive')}],
	['<span style="font-family:fantasy;">Fantasy</span>',function(){Tools.Text.setFace('fantasy')}],
]});
Tools.Text.buttons.style = Tools.Text.bar.add({text:'Style',type:'dropdown',menu:[
	['Normal',function(){Tools.Text.setStyle('normal')}],
	['<b>Bold</b>',function(){Tools.Text.setStyle('bold')}],
	['<i>Italic</i>',function(){Tools.Text.setStyle('italic')}],
	['<b><i>Oblique</i></b>',function(){Tools.Text.setStyle('bold italic')}]
]});
Tools.Text.buttons.size = Tools.Text.bar.add({text:'Size',type:'dropdown',menu:[
	['10',function(){Tools.Text.setSize(10)}],
	['12',function(){Tools.Text.setSize(12)}],
	['20',function(){Tools.Text.setSize(20)}],
	['36',function(){Tools.Text.setSize(36)}],
	['48',function(){Tools.Text.setSize(48)}],
	['72',function(){Tools.Text.setSize(72)}],
	[''],
	['More sizes...']
]});
Tools.Text.bar.add({type:'divider'});
Tools.Text.buttons.title = Tools.Text.bar.add({text:'Text'});
Tools.Text.buttons.title.style.pointerEvents = 'none';
