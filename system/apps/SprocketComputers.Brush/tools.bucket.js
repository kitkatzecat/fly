Tools.Bucket = function() {
	CurrentTool.reset();
	CurrentTool = Tools.Bucket;
	
	Actionbars.Toolsbar.toggleAllOff();
	Object.keys(Tools).forEach(function(key,index) {
		try {
	 	   Tools[key].bar.parentNode.removeChild(Tools[key].bar);
		} catch(err) {}
	});
	
	Actionbars.Toolsbar.buttonsList.bucket.toggleOn();
	document.body.appendChild(Tools.Bucket.bar);

	ColorChange = function() {};
	
	ctx.lineWidth = 1;
	ctx.lineJoin = 'miter';
	ctx.lineCap = 'butt';
	ctx.strokeStyle = Color;
	
	canvas.addEventListener('mousedown', Tools.Bucket.fill, false);
	canvas.addEventListener('click', UpdateState, false);
}
Tools.Bucket.reset = function() {
	ColorChange = function() {};
	
	canvas.removeEventListener('mousedown', Tools.Bucket.fill, false);
	canvas.removeEventListener('click', UpdateState, false);
}
Tools.Bucket.fill = function() {
	var startX = mouse.x;
	var startY = mouse.y;
	var rgb = hexToRgb(Color);
	var startR = 255;
	var startG = 255;
	var startB = 255;
	var fillColorR = rgb.r;
	var fillColorG = rgb.g;
	var fillColorB = rgb.b;
	pixelStack = [[startX, startY]];
	
	var colorLayer = ctx.getImageData(0,0,canvas.width,canvas.height);
	var startColor = ctx.getImageData(mouse.x,mouse.y,1,1);
	var startR = startColor.data[0];
	var startG = startColor.data[1];
	var startB = startColor.data[2];
	var startA = startColor.data[3];
	
	while(pixelStack.length)
	{
	  var newPos, x, y, pixelPos, reachLeft, reachRight;
	  newPos = pixelStack.pop();
	  x = newPos[0];
	  y = newPos[1];
	  
	  pixelPos = (y*canvas.width + x) * 4;
	  while(y-- >= 0 && matchStartColor(pixelPos))
	  {
	    pixelPos -= canvas.width * 4;
	  }
	  pixelPos += canvas.width * 4;
	  ++y;
	  reachLeft = false;
	  reachRight = false;
	  while(y++ < canvas.height-1 && matchStartColor(pixelPos))
	  {
	    colorPixel(pixelPos);

	    if(x > 0)
	    {
	      if(matchStartColor(pixelPos - 4)) {
	        if(!reachLeft){
	          pixelStack.push([x - 1, y]);
	          reachLeft = true;
	        }
	      } else if(reachLeft) {
	        reachLeft = false;
	      }
	    }
		
	    if(x < canvas.width-1) {
	      if(matchStartColor(pixelPos + 4)) {
	        if(!reachRight) {
	          pixelStack.push([x + 1, y]);
	          reachRight = true;
	        }
	      } else if(reachRight) {
	        reachRight = false;
	      }
	    }
				
	    pixelPos += canvas.width * 4;
	  }
	}
	ctx.putImageData(colorLayer, 0, 0);
	  
	function matchStartColor(pixelPos) {
	  var r = colorLayer.data[pixelPos];	
	  var g = colorLayer.data[pixelPos+1];	
	  var b = colorLayer.data[pixelPos+2];
	  var a = colorLayer.data[pixelPos+3];

	  return (r == startR && g == startG && b == startB && a == startA);
	}

	function colorPixel(pixelPos) {
	  colorLayer.data[pixelPos] = fillColorR;
	  colorLayer.data[pixelPos+1] = fillColorG;
	  colorLayer.data[pixelPos+2] = fillColorB;
	  colorLayer.data[pixelPos+3] = 255;
	}
}
Tools.Bucket.bar = new Fly.actionbar();
Tools.Bucket.bar.style.position = 'absolute';
Tools.Bucket.bar.style.bottom = '0px';
Tools.Bucket.bar.style.width = 'auto';
Tools.Bucket.bar.style.right = '0px';
Tools.Bucket.buttons = {};

Tools.Bucket.buttons.title = Tools.Bucket.bar.add({text:'Bucket'});
Tools.Bucket.buttons.title.style.pointerEvents = 'none';
