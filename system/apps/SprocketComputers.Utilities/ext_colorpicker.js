if (Control.style.display !== 'none') {
	Control.style.display = 'inline-block';
}
Control.style.minWidth = '48px';
Control.style.height = '28px';
Control.color = {hex:'#FF0000',rgb:'255,0,0',rgba:'255,0,0,0',r:255,g:0,b:0,h:0,s:100,l:50};
Control.value = '#FF0000';
Control.window = false;
if (Control.name == '' || Control.name == null) {
	var name = ControlId;
} else {
	var name = Control.name;
	Control.name = null;
}

Control.innerHTML = '<input type="hidden" value="#FF0000" name="'+name+'" id="'+ControlId+'-colorPicker-color"><button onclick="document.getElementById(\''+ControlId+'\').choose()" type="button" style="width:100%;height:100%;padding:5px;"><div id="'+ControlId+'-colorPicker-preview" style="pointer-events:none;background-color:#ff0000;width:100%;height:100%;"></div></button>';

Control.loaded = function() {
	try {
		Control.window.window.content.contentWindow.ret = Control.return;
		Control.window.window.content.contentWindow.init(Fly.window.id);
	} catch(err) {
		window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
	}
}
Control.choose = function() {
	Control.window = window.top.task.create('SprocketComputers.Utilities',{title:Fly.window.name.get()+' - Pick Color', name:'Color Picker', x:parseInt(Fly.window.position.get()[0])+32, y:parseInt(Fly.window.position.get()[1])+32, width:240, height:324, location:'%FLY.RESOURCE.URL.APPS%/SprocketComputers.Utilities/colorpicker.php?Fly.window.id='+Fly.window.id+'&color='+Control.color.rgb, icon:Fly.window.icon.get(), load:Control.loaded});
}
Control.onchange = function() {};
Control.return = function(ret) {
	document.getElementById(Control.id+'-colorPicker-preview').style.backgroundColor = ret.hex;
	document.getElementById(Control.id+'-colorPicker-color').value = ret.hex;
	Control.value = ret.hex;
	Control.color.hex = ret.hex;
	Control.color.rgb = ret.r+','+ret.g+','+ret.b;
	Control.color.r = ret.r;
	Control.color.g = ret.g;
	Control.color.b = ret.b;
	Control.color.h = ret.h;
	Control.color.s = ret.s;
	Control.color.l = ret.l;
	try {
		Control.onchange();
	} catch(err) {}
}