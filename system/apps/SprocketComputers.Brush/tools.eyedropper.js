Tools.Eyedropper = function() {
	CurrentTool.reset();
	CurrentTool = Tools.Eyedropper;
	
	Actionbars.Toolsbar.toggleAllOff();
	Object.keys(Tools).forEach(function(key,index) {
		try {
	 	   Tools[key].bar.parentNode.removeChild(Tools[key].bar);
		} catch(err) {}
	});
	
	Actionbars.Toolsbar.buttonsList.eyedropper.toggleOn();
	document.body.appendChild(Tools.Eyedropper.bar);

	ColorChange = function() {};
	
	canvas.addEventListener('mousedown',Tools.Eyedropper.onMouseDown,false);
	canvas.addEventListener('mouseup',Tools.Eyedropper.onMouseUp,false);
	canvas.addEventListener('mouseout',Tools.Eyedropper.onMouseOut,false);
}
Tools.Eyedropper.reset = function() {
	canvas.removeEventListener('mousedown',Tools.Eyedropper.onMouseDown,false);
	canvas.removeEventListener('mouseup',Tools.Eyedropper.onMouseUp,false);
	canvas.removeEventListener('mouseout',Tools.Eyedropper.onMouseOut,false);
	canvas.removeEventListener('mousemove',Tools.Eyedropper.onDrag,false);
}
Tools.Eyedropper.onMouseDown = function() {
	canvas.addEventListener('mousemove',Tools.Eyedropper.onDrag,false);
	
	Tools.Eyedropper.colorPreview.style.left = (bodymouse.x+10)+'px';
	Tools.Eyedropper.colorPreview.style.top = (bodymouse.y+10)+'px';
	
	var data = ctx.getImageData(mouse.x,mouse.y,1,1);
	var color = rgbToHex(data.data[0],data.data[1],data.data[2]);
	Tools.Eyedropper.currentColor = color;
	
	Tools.Eyedropper.colorPreview.style.backgroundColor = Tools.Eyedropper.currentColor;
	document.body.appendChild(Tools.Eyedropper.colorPreview);
}
Tools.Eyedropper.onMouseUp = function() {
	canvas.removeEventListener('mousemove',Tools.Eyedropper.onDrag,false);
	Tools.Eyedropper.colorPreview.parentNode.removeChild(Tools.Eyedropper.colorPreview);
	
	SetColor(Tools.Eyedropper.currentColor);
}
Tools.Eyedropper.onMouseOut = function() {
	canvas.removeEventListener('mousemove',Tools.Eyedropper.onDrag,false);
	Tools.Eyedropper.colorPreview.parentNode.removeChild(Tools.Eyedropper.colorPreview);
}
Tools.Eyedropper.onDrag = function() {
	Tools.Eyedropper.colorPreview.style.left = (bodymouse.x+10)+'px';
	Tools.Eyedropper.colorPreview.style.top = (bodymouse.y+10)+'px';
	
	var data = ctx.getImageData(mouse.x,mouse.y,1,1);
	var color = rgbToHex(data.data[0],data.data[1],data.data[2]);
	Tools.Eyedropper.currentColor = color;
	
	Tools.Eyedropper.colorPreview.style.backgroundColor = Tools.Eyedropper.currentColor;
}
Tools.Eyedropper.currentColor = '#ff0000';
Tools.Eyedropper.colorPreview = document.createElement('div');
Tools.Eyedropper.colorPreview.style.position = 'absolute';
Tools.Eyedropper.colorPreview.style.pointerEvents = 'none';
Tools.Eyedropper.colorPreview.style.zIndex = '99999999999';
Tools.Eyedropper.colorPreview.style.width = '36px';
Tools.Eyedropper.colorPreview.style.height = '36px';
Tools.Eyedropper.colorPreview.style.backgroundColor = Tools.Eyedropper.currentColor;
Tools.Eyedropper.colorPreview.style.boxShadow = '0px 0px 6px #000000';
Tools.Eyedropper.colorPreview.style.border = '4px solid #ffffff';
Tools.Eyedropper.colorPreview.style.outline = '1px solid #c0c0c0';
Tools.Eyedropper.bar = new Fly.actionbar();
Tools.Eyedropper.bar.style.position = 'absolute';
Tools.Eyedropper.bar.style.bottom = '0px';
Tools.Eyedropper.bar.style.width = 'auto';
Tools.Eyedropper.bar.style.right = '0px';
Tools.Eyedropper.buttons = {};

Tools.Eyedropper.buttons.title = Tools.Eyedropper.bar.add({text:'Eyedropper'});
Tools.Eyedropper.buttons.title.style.pointerEvents = 'none';
