Tools.Pencil = function() {
	CurrentTool.reset();
	CurrentTool = Tools.Pencil;
	
	Actionbars.Toolsbar.toggleAllOff();
	Object.keys(Tools).forEach(function(key,index) {
		try {
	 	   Tools[key].bar.parentNode.removeChild(Tools[key].bar);
		} catch(err) {}
	});
	
	Actionbars.Toolsbar.buttonsList.pencil.toggleOn();
	document.body.appendChild(Tools.Pencil.bar);

	ColorChange = Tools.Pencil.setColor;
	
	ctx.lineWidth = 1;
	ctx.lineJoin = 'miter';
	ctx.lineCap = 'butt';
	ctx.strokeStyle = Color;
	
	canvas.addEventListener('mousedown',Tools.Pencil.onMouseDown,false);
	canvas.addEventListener('mouseup',Tools.Pencil.onMouseUp,false);
	canvas.addEventListener('mouseout',Tools.Pencil.onMouseOut,false);
	canvas.addEventListener('click', UpdateState, false);
}
Tools.Pencil.reset = function() {
	ColorChange = function() {};
	
	canvas.removeEventListener('mousedown',Tools.Pencil.onMouseDown,false);
	canvas.removeEventListener('mouseup',Tools.Pencil.onMouseUp,false);
	canvas.removeEventListener('mouseout',Tools.Pencil.onMouseOut,false);
	canvas.removeEventListener('mousemove', Tools.Pencil.onPaint, false);
	canvas.removeEventListener('click', UpdateState, false);
}
Tools.Pencil.setColor = function() {
	ctx.strokeStyle = Color;
}
Tools.Pencil.onPaint = function() {
	ctx.lineTo(mouse.x, mouse.y);
	ctx.stroke();
}
Tools.Pencil.onMouseDown = function() {
	ctx.beginPath();
	ctx.moveTo(mouse.x, mouse.y);
	canvas.addEventListener('mousemove', Tools.Pencil.onPaint, false);
}
Tools.Pencil.onMouseOut = function() {
	canvas.removeEventListener('mousemove', Tools.Pencil.onPaint, false);
}
Tools.Pencil.onMouseUp = function() {
	canvas.removeEventListener('mousemove', Tools.Pencil.onPaint, false);
}
Tools.Pencil.bar = new Fly.actionbar();
Tools.Pencil.bar.style.position = 'absolute';
Tools.Pencil.bar.style.bottom = '0px';
Tools.Pencil.bar.style.width = 'auto';
Tools.Pencil.bar.style.right = '0px';
Tools.Pencil.buttons = {};

Tools.Pencil.buttons.title = Tools.Pencil.bar.add({text:'Pencil'});
Tools.Pencil.buttons.title.style.pointerEvents = 'none';
