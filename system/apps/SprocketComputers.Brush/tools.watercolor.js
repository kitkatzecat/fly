Tools.Watercolor = function() {
	CurrentTool.reset();
	CurrentTool = Tools.Watercolor;

	Actionbars.Toolsbar.toggleAllOff();
	Object.keys(Tools).forEach(function(key,index) {
		try {
	 	   Tools[key].bar.parentNode.removeChild(Tools[key].bar);
		} catch(err) {}
	});
	
	Actionbars.Toolsbar.buttons.paint.toggleOn();
	Actionbars.Toolsbar.buttons.watercolor.toggleOn();
	document.body.appendChild(Tools.Watercolor.bar);
	
	ColorChange = Tools.Watercolor.setColor;
	
	ctx.lineWidth = Tools.Watercolor.lineWidth;
	ctx.lineJoin = 'round';
	ctx.lineCap = 'round';
	var RgbColor = hexToRgb(Color);
	ctx.strokeStyle = 'rgba('+RgbColor.r+','+RgbColor.g+','+RgbColor.b+',0.01)';
	
	canvas.addEventListener('mousedown',Tools.Watercolor.onMouseDown,false);
	canvas.addEventListener('mouseup',Tools.Watercolor.onMouseUp,false);
	canvas.addEventListener('mouseout',Tools.Watercolor.onMouseOut,false);
	canvas.addEventListener('click', UpdateState, false);
}
Tools.Watercolor.reset = function() {
	ColorChange = function() {};
	ctx.lineWidth = 1;
	ctx.lineJoin = 'miter';
	ctx.lineCap = 'butt';
	ctx.strokeStyle = Color;
	
	canvas.removeEventListener('mousedown',Tools.Watercolor.onMouseDown,false);
	canvas.removeEventListener('mouseup',Tools.Watercolor.onMouseUp,false);
	canvas.removeEventListener('mouseout',Tools.Watercolor.onMouseOut,false);
	canvas.removeEventListener('mousemove', Tools.Watercolor.onPaint, false);
	canvas.removeEventListener('click', UpdateState, false);
}
Tools.Watercolor.setWidth = function(width) {
	Tools.Watercolor.lineWidth = width;
	ctx.lineWidth = Tools.Watercolor.lineWidth;
}
Tools.Watercolor.setColor = function() {
	var RgbColor = hexToRgb(Color);
	ctx.strokeStyle = 'rgba('+RgbColor.r+','+RgbColor.g+','+RgbColor.b+',0.01)';
}
Tools.Watercolor.onPaint = function() {
	ctx.lineTo(mouse.x, mouse.y);
	ctx.stroke();
}
Tools.Watercolor.onMouseDown = function() {
	ctx.beginPath();
	ctx.moveTo(mouse.x, mouse.y);
	canvas.addEventListener('mousemove', Tools.Watercolor.onPaint, false);
}
Tools.Watercolor.onMouseOut = function() {
	canvas.removeEventListener('mousemove', Tools.Watercolor.onPaint, false);
}
Tools.Watercolor.onMouseUp = function() {
	canvas.removeEventListener('mousemove', Tools.Watercolor.onPaint, false);
}
Tools.Watercolor.bar = new Fly.actionbar();
Tools.Watercolor.bar.style.position = 'absolute';
Tools.Watercolor.bar.style.bottom = '0px';
Tools.Watercolor.bar.style.width = 'auto';
Tools.Watercolor.bar.style.right = '0px';
Tools.Watercolor.buttons = {};

Tools.Watercolor.lineWidth = 20;

Tools.Watercolor.buttons.thickness = Tools.Watercolor.bar.add({text:'Thickness',type:'dropdown',menu:[
	['5',function(){Tools.Watercolor.setWidth(5)}],
	['10',function(){Tools.Watercolor.setWidth(10)}],
	['20',function(){Tools.Watercolor.setWidth(20)}],
	['50',function(){Tools.Watercolor.setWidth(50)}],
	['100',function(){Tools.Watercolor.setWidth(100)}],
	[''],
	['More sizes...']
]});
Tools.Watercolor.bar.add({type:'divider'});
Tools.Watercolor.buttons.title = Tools.Watercolor.bar.add({text:'Watercolor'});
Tools.Watercolor.buttons.title.style.pointerEvents = 'none';
