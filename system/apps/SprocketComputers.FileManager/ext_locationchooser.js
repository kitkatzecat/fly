if (Control.style.display !== 'none') {
	Control.style.display = 'inline-block';
}
Control.style.backgroundImage = "linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(225,225,225,1) 100%)";
Control.style.backgroundColor = "#C0C0C0";
Control.style.border = ".1px solid #C0C0C0";
Control.style.borderRadius = "3px";
Control.style.padding = "8px";
Control.style.position = "relative";
Control.style.minWidth = "236px";
Control.style.height = "28px";
Control.vars = {basename:false,path:false,fpath:false,icon:false};
Control.value = '';
Control.window = false;
if (Control.name == '' || Control.name == null) {
	var name = ControlId;
} else {
	var name = Control.name;
	Control.name = null;
}


Control.innerHTML = '<input type="hidden" name="'+name+'" id="'+id+'-locationChooser-path"><div id="'+ControlId+'-locationChooser-text" class="FlyUiText FlyUiNoSelect" style="position:absolute;min-width:120px;top:14px;left:8px;right:100px;font-size:14px;color:#808080;border:none;box-sizing:border-box;background:transparent;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">No location selected</div><button type="button" style="position:absolute;top:8px;bottom:8px;right:8px;" id="'+ControlId+'-locationChooser-button" onclick="document.getElementById(\''+ControlId+'\').browse();">Browse...</button>';

Control.loaded = function() {
	try {
		Control.window.window.content.contentWindow.ret = Control.return;
		Control.window.window.content.contentWindow.init(WindowId);
	} catch(err) {
		window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
	}
}
Control.browse = function() {
	Control.window = window.top.task.create('SprocketComputers.FileManager',{title:Fly.window.name.get()+' - Browse for Location', name:'Location Chooser', x:parseInt(Fly.window.position.get()[0])+32, y:parseInt(Fly.window.position.get()[1])+32, width:320, height:400, location:'%FLY.RESOURCE.URL.APPS%/SprocketComputers.FileManager/control.php?windowid='+WindowId+'&controlid='+ControlId+'&type=saveBrowser', icon:Fly.window.icon.get(), load:Control.loaded});
}
Control.onchange = function() {};
Control.return = function(ret) {
	document.getElementById(Control.id+'-locationChooser-text').style.color = "#000000";
	document.getElementById(Control.id+'-locationChooser-text').innerHTML = '<img style="width:16px;height:16px;vertical-align:middle;margin-right:4px;" src="'+ret.icon+'">'+ret.basename;
	document.getElementById(Control.id+'-locationChooser-path').value = ret.path;
	Control.vars.basename = ret.basename;
	Control.vars.path = ret.path;
	Control.vars.fpath = ret.fpath;
	Control.vars.icon = ret.icon;
	Control.value = ret.path;
	try {
		Control.onchange();
	} catch(err) {}
}