Tools.Brush = function() {
	CurrentTool.reset();
	CurrentTool = Tools.Brush;

	Actionbars.Toolsbar.toggleAllOff();
	Object.keys(Tools).forEach(function(key,index) {
		try {
	 	   Tools[key].bar.parentNode.removeChild(Tools[key].bar);
		} catch(err) {}
	});
	
	Actionbars.Toolsbar.buttonsList.paint.toggleOn();
	Actionbars.Toolsbar.buttonsList.brush.toggleOn();
	document.body.appendChild(Tools.Brush.bar);
	
	ColorChange = Tools.Brush.setColor;
	
	ctx.lineWidth = Tools.Brush.lineWidth;
	ctx.lineJoin = Tools.Brush.lineJoin;
	ctx.lineCap = Tools.Brush.lineCap;
	ctx.strokeStyle = Color;
	
	canvas.addEventListener('mousedown',Tools.Brush.onMouseDown,false);
	canvas.addEventListener('mouseup',Tools.Brush.onMouseUp,false);
	canvas.addEventListener('mouseout',Tools.Brush.onMouseOut,false);
	canvas.addEventListener('click', UpdateState, false);
}
Tools.Brush.reset = function() {
	ColorChange = function() {};
	ctx.lineWidth = 1;
	ctx.lineJoin = 'miter';
	ctx.lineCap = 'butt';
	
	canvas.removeEventListener('mousedown',Tools.Brush.onMouseDown,false);
	canvas.removeEventListener('mouseup',Tools.Brush.onMouseUp,false);
	canvas.removeEventListener('mouseout',Tools.Brush.onMouseOut,false);
	canvas.removeEventListener('mousemove', Tools.Brush.onPaint, false);
	canvas.removeEventListener('click', UpdateState, false);
}
Tools.Brush.setJoin = function(join) {
	Tools.Brush.lineJoin = join;
	ctx.lineJoin = Tools.Brush.lineJoin;
}
Tools.Brush.setCap = function(cap) {
	Tools.Brush.lineCap = cap;
	ctx.lineCap = Tools.Brush.lineCap;
}
Tools.Brush.setWidth = function(width) {
	Tools.Brush.lineWidth = width;
	ctx.lineWidth = Tools.Brush.lineWidth;
}
Tools.Brush.setColor = function() {
	ctx.strokeStyle = Color;
}
Tools.Brush.onPaint = function() {
	ctx.lineTo(mouse.x, mouse.y);
	ctx.stroke();
}
Tools.Brush.onMouseDown = function() {
	ctx.beginPath();
	ctx.moveTo(mouse.x, mouse.y);
	canvas.addEventListener('mousemove', Tools.Brush.onPaint, false);
}
Tools.Brush.onMouseOut = function() {
	canvas.removeEventListener('mousemove', Tools.Brush.onPaint, false);
}
Tools.Brush.onMouseUp = function() {
	canvas.removeEventListener('mousemove', Tools.Brush.onPaint, false);
}
Tools.Brush.bar = new Fly.actionbar();
Tools.Brush.bar.style.position = 'absolute';
Tools.Brush.bar.style.bottom = '0px';
Tools.Brush.bar.style.width = 'auto';
Tools.Brush.bar.style.right = '0px';
Tools.Brush.buttons = {};

Tools.Brush.lineWidth = 5;
Tools.Brush.lineJoin = 'round';
Tools.Brush.lineCap = 'round';

Tools.Brush.buttons.thickness = Tools.Brush.bar.add({text:'Thickness',type:'dropdown',menu:[
	['5',function(){Tools.Brush.setWidth(5)}],
	['10',function(){Tools.Brush.setWidth(10)}],
	['20',function(){Tools.Brush.setWidth(20)}],
	['50',function(){Tools.Brush.setWidth(50)}],
	['100',function(){Tools.Brush.setWidth(100)}],
	[''],
	['More sizes...']
]});
Tools.Brush.buttons.type = Tools.Brush.bar.add({text:'Brush Type',type:'dropdown',menu:[
	['Wide',function(){Tools.Brush.setCap('butt')},{icon:Vars.workingUrl+'paint.type.wide.svg'}],
	['Round',function(){Tools.Brush.setCap('round')},{icon:Vars.workingUrl+'paint.type.round.svg'}],
	['Square',function(){Tools.Brush.setCap('square')},{icon:Vars.workingUrl+'paint.type.square.svg'}]
]});
Tools.Brush.buttons.corners = Tools.Brush.bar.add({text:'Corners',type:'dropdown',menu:[
	['Round',function(){Tools.Brush.setJoin('round')},{icon:Vars.workingUrl+'paint.corners.round.svg'}],
	['Beveled',function(){Tools.Brush.setJoin('bevel')},{icon:Vars.workingUrl+'paint.corners.beveled.svg'}],
	['Sharp',function(){Tools.Brush.setJoin('miter')},{icon:Vars.workingUrl+'paint.corners.sharp.svg'}]
]});
Tools.Brush.bar.add({type:'divider'});
Tools.Brush.buttons.title = Tools.Brush.bar.add({text:'Paint'});
Tools.Brush.buttons.title.style.pointerEvents = 'none';