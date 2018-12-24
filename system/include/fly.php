<?php
// SESSION - integrates with constants, cannot be used by itself
include 'Fly.Session.php';

include 'Fly.Core.php';

// FLY EXTENDED PHP FUNCTIONS
include 'functions.php';

// DEFINE PHP CONSTANTS
define("FLY_WINDOW_ID",$_GET['Fly_Id']);
include 'Fly.Constants.php';
include 'Fly.Theme.php';

// DEFINE JS CONSTANTS
echo '
<script>
const DOCUMENT_PROTOCOL = \''.DOCUMENT_PROTOCOL.'\';
const FLY_URL = \''.FLY_URL.'\';
const FLY_ROOT = \''.FLY_ROOT.'\';
const CURRENT_URL = \''.CURRENT_URL.'\';
const WORKING_URL = \''.WORKING_URL.'\';
</script>
';

FlyLoadTheme('text controls');
echo '
<style>
html {
	overscroll-behavior: contain;
}
body {
	margin: 0px;
	overscroll-behavior: contain;
}
</style>
';

//error_reporting(0);
// SET JS VARS
include 'Fly.Window.php';
include 'Fly.Actionbar.php';
include 'Fly.Controls.php';
include 'Fly.CoreJS.php';

echo '
<script>
Fly.toolbar = document.createElement("div");
Fly.toolbar.init = function() {
if (Fly.toolbar.toggle === undefined) {
	Fly.toolbar.onshow = function() {};
	Fly.toolbar.onhide = function() {};
	
	Fly.toolbar.style.position = "absolute";
	Fly.toolbar.style.background = "transparent";
	Fly.toolbar.style.top = "0px";
	Fly.toolbar.style.left = "0px";
	Fly.toolbar.style.right = "0px";
	Fly.toolbar.style.height = "34px";
	Fly.toolbar.style.display = "block";
	Fly.toolbar.style.zIndex = "50000";
	Fly.toolbar.style.overflowX = "hidden";
	Fly.toolbar.style.overflowY = "auto";
	document.body.appendChild(Fly.toolbar);
	
	Fly.toolbar.toggle = function() {
		if (Fly.toolbar.style.display == "block") {
			Fly.toolbar.style.display = "none";
			Fly.toolbar.onhide();
		} else {
			Fly.toolbar.style.display = "block";
			Fly.toolbar.onshow();
		}
	}
	Fly.toolbar.hide = function() {
		Fly.toolbar.style.display = "none";
		Fly.toolbar.onhide();
	}
	Fly.toolbar.show = function() {
		Fly.toolbar.style.display = "block";
		Fly.toolbar.onshow();
	}
	
	Fly.toolbar.add = function(text,id,action,icon,float="left") {
		var button = document.createElement("div");
		button.className = "FlyUiNoSelect FlyUiToolbarItem FlyUiText";
		button.style.float = float;
		if (icon === undefined || icon == "") {
			button.image = "";
		} else {
			if (icon.substr(0,4) == "data") {
				var exists = true;
			} else {
			    var http = new XMLHttpRequest();
			    http.open("HEAD", icon+"?rand="+Math.floor( Math.random() * 10000 ), false);
			    http.send();
			    if (http.status!=404) {
					var exists = true;
				}
			}
			if (exists) {
	            if (text === undefined || text == "") {
					button.image = \'<img src="\'+icon+\'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;">\';
				} else {
					button.image = \'<img src="\'+icon+\'" style="width:16px;height:16px;margin-right:4px;vertical-align:middle;pointer-events:none;">\';
				}
			} else {
				button.image = "";
			}
		}
		button.innerHTML = button.image+text;
		button.onclick = action;
		button.id = "FlyToolbarItem-"+id;
		button.setContent = function(text) {
			this.innerHTML = button.image+text;
		}
		button.toggle = function() {
			if (this.className == "FlyUiNoSelect FlyUiToolbarItem FlyUiText") {
				this.className = "FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText";
			} else {
				this.className = "FlyUiNoSelect FlyUiToolbarItem FlyUiText";
			}
		}
		button.toggleOn = function() {
			this.className = "FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText";
		}
		button.toggleOff = function() {
			this.className = "FlyUiNoSelect FlyUiToolbarItem FlyUiText";
		}
		Fly.toolbar.appendChild(button);
		return button;
	}
	
	Fly.toolbar.add.custom = function(id,content,float="left") {
		var div = document.createElement("div");
		div.id = "FlyToolbarItem-"+id;
		div.innerHTML = content;
		div.style.height = "24px";
		div.style.display = "inline-block";
		div.style.float = float;
		div.setContent = function(text) {
			this.innerHTML = text;
		}
		Fly.toolbar.appendChild(div);
		return div;
	}
	
	Fly.toolbar.add.divider = function(id="divider",float="left") {
		var div = document.createElement("div");
		div.style.display = "inline-block";
		div.style.height = "24px";
		div.style.width = "0px";
		div.style.float = float;
		div.style.marginTop = "5px";
		div.style.marginLeft = "4px";
		div.style.marginRight = "4px";
		div.style.borderLeft = ".1px solid #c0c0c0";
		div.style.borderRight = ".1px solid #808080";
		Fly.toolbar.appendChild(div);
	}
	
	Fly.toolbar.remove = function(id) {
		document.getElementById("FlyToolbarItem-"+id).parentNode.removeChild(document.getElementById("FlyToolbarItem-"+id));
	}
	
	return true;
}
}


// FLY CONTROL FUNCTIONS - MADE OBSOLETE BY EXTENSIONS FUNCTIONS, DO NOT USE IN NEW DEVELOPMENT (excluding input, confirm, or context menu)
/*
Fly.control = function() {};
Fly.control.replace = function(id,control) {
	if (typeof(document.getElementById(id)) == "undefined") {
		throw("Fly control replace - ERROR: Object "+id+" does not exist");
	} else {
		var replace = document.getElementById(id);
		if (control == "Fly.control.progressBar") {
			replace.style.display = "inline-block";
			replace.style.minWidth = "236px";
			replace.style.height = "20px";
			replace.style.border = ".1px solid rgb(181,198,208)";
			replace.style.borderRadius = "3px";
			replace.style.transition = "background-size .1s ease-in-out";
			replace.style.backgroundImage = "linear-gradient(to bottom, rgb(184,225,252) 0%,rgb(169,210,243) 10%,rgb(144,186,228) 25%,rgb(144,188,234) 37%,rgb(144,191,240) 50%,rgb(107,168,229) 51%,rgb(162,218,245) 83%,rgb(189,243,253) 100%), linear-gradient(to bottom, rgba(242,246,248,1) 0%,rgba(216,225,231,1) 50%,rgba(181,198,208,1) 51%,rgba(224,239,249,1) 100%);";
			replace.style.backgroundRepeat = "no-repeat";
			replace.style.backgroundSize = "0% 100%, 100% 100%";
			
			replace.setAttribute("position","0"); 
			
			replace.setPosition = function(percent) {
				this.style.backgroundSize = percent+"% 100%, 100% 100%";
				this.setAttribute("position",percent);
				return percent+"%";
			}
			replace.getPosition = function() {
				return this.getAttribute("position");
			}
		}
		if (control == "Fly.control.fileBrowser") {
			if (replace.style.display !== "none") {
				replace.style.display = "inline-block";
			}
			replace.style.backgroundImage = "linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(225,225,225,1) 100%)";
			replace.style.backgroundColor = "#C0C0C0";
			replace.style.border = ".1px solid #C0C0C0";
			replace.style.borderRadius = "3px";
			replace.style.padding = "8px";
			replace.style.position = "relative";
			replace.style.minWidth = "236px";
			replace.style.height = "28px";
			replace.innerHTML = \'<input type="hidden" name="\'+id+\'" id="\'+id+\'-fileBrowser-path"><div id="\'+id+\'-fileBrowser-text" class="FlyUiText FlyUiNoSelect" style="position:absolute;min-width:120px;top:14px;left:8px;right:100px;font-size:14px;color:#808080;border:none;box-sizing:border-box;background:transparent;">No file selected</div><button type="button" style="position:absolute;top:8px;bottom:8px;right:8px;" id="\'+id+\'-fileBrowser-button" onclick="Fly.control.fileBrowser.browse(\\\'\'+id+\'\\\')">Browse...</button>\';
			replace.browse = function() {
				Fly.control.fileBrowser.browse(this.id);
			}
			replace.onchange = function() {};
		}
		if (control == "Fly.control.saveBrowser") {
			if (replace.style.display !== "none") {
				replace.style.display = "inline-block";
			}
			replace.style.backgroundImage = "linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(225,225,225,1) 100%)";
			replace.style.backgroundColor = "#C0C0C0";
			replace.style.border = ".1px solid #C0C0C0";
			replace.style.borderRadius = "3px";
			replace.style.padding = "8px";
			replace.style.position = "relative";
			replace.style.minWidth = "236px";
			replace.style.height = "28px";
			replace.innerHTML = \'<input type="hidden" name="\'+id+\'" id="\'+id+\'-saveBrowser-path"><div id="\'+id+\'-saveBrowser-text" class="FlyUiText FlyUiNoSelect" style="position:absolute;min-width:120px;top:14px;left:8px;right:100px;font-size:14px;color:#808080;border:none;box-sizing:border-box;background:transparent;">No location selected</div><button type="button" style="position:absolute;top:8px;bottom:8px;right:8px;" id="\'+id+\'-fileBrowser-button" onclick="Fly.control.saveBrowser.browse(\\\'\'+id+\'\\\')">Browse...</button>\';
			replace.browse = function() {
				Fly.control.saveBrowser.browse(this.id);
			}
			replace.onchange = function() {};
		}
		if (control == "Fly.control.applicationChooser") {
			if (replace.style.display !== "none") {
				replace.style.display = "inline-block";
			}
			replace.style.backgroundImage = "linear-gradient(to bottom, rgba(245,245,245,1) 0%,rgba(225,225,225,1) 100%)";
			replace.style.backgroundColor = "#C0C0C0";
			replace.style.border = ".1px solid #C0C0C0";
			replace.style.borderRadius = "3px";
			replace.style.padding = "8px";
			replace.style.position = "relative";
			replace.style.minWidth = "236px";
			replace.style.height = "28px";
			replace.innerHTML = \'<input type="hidden" name="\'+id+\'" id="\'+id+\'-applicationChooser-app"><div id="\'+id+\'-applicationChooser-text" class="FlyUiText FlyUiNoSelect" style="position:absolute;min-width:120px;top:14px;left:8px;right:96px;font-size:14px;color:#808080;border:none;box-sizing:border-box;background:transparent;">No application selected</div><button type="button" style="position:absolute;top:8px;bottom:8px;right:8px;" id="\'+id+\'-applicationChooser-button" onclick="Fly.control.applicationChooser.browse(\\\'\'+id+\'\\\')">Choose...</button>\';
			replace.browse = function() {
				Fly.control.applicationChooser.browse(this.id);
			}
			replace.onchange = function() {};
		}
	}
}

Fly.control.progressBar = function() {};

Fly.control.fileBrowser = function() {};
Fly.control.fileBrowser.browse = function(id) {
	window.top.system.command(\'run:SprocketComputers.FileManager.FileChooser,controlid=\'+id+\',windowid=\'+Fly.window.id);
} 
Fly.control.fileBrowser.setFile = function(id,path,basename,url,extension,icon) {
	document.getElementById(id+\'-fileBrowser-text\').style.color = "#000000";
	document.getElementById(id+\'-fileBrowser-text\').innerHTML = \'<img style="width:16px;height:16px;vertical-align:middle;margin-right:4px;" src="\'+icon+\'">\'+basename;
	document.getElementById(id+\'-fileBrowser-path\').value = path;
	document.getElementById(id).setAttribute("basename",basename);
	document.getElementById(id).setAttribute("path",path);
	document.getElementById(id).setAttribute("URL",url);
	document.getElementById(id).setAttribute("extension",extension);
	document.getElementById(id).setAttribute("icon",icon);
	Fly.window.bringToFront();
	document.getElementById(id).onchange();
}

Fly.control.saveBrowser = function() {};
Fly.control.saveBrowser.browse = function(id) {
	window.top.system.command(\'run:SprocketComputers.FileManager.LocationChooser,controlid=\'+id+\',windowid=\'+Fly.window.id);
} 
Fly.control.saveBrowser.setLocation = function(id,path,basename,fpath) {
	document.getElementById(id+\'-saveBrowser-text\').style.color = "#000000";
	document.getElementById(id+\'-saveBrowser-text\').innerHTML = \'<img style="width:16px;height:16px;vertical-align:middle;margin-right:4px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'folder.svg">\'+basename;
	document.getElementById(id+\'-saveBrowser-path\').value = path;
	document.getElementById(id).setAttribute("basename",basename);
	document.getElementById(id).setAttribute("path",path);
	document.getElementById(id).setAttribute("fpath",fpath);
	Fly.window.bringToFront();
	document.getElementById(id).onchange();
}

Fly.control.applicationChooser = function() {};
Fly.control.applicationChooser.browse = function(id) {
	window.top.system.command(\'run:SprocketComputers.Marketplace.AppChooser,controlid=\'+id+\',windowid=\'+Fly.window.id);
}
Fly.control.applicationChooser.setApplication = function(id,app,name,publisher,icon) {
	document.getElementById(id+\'-applicationChooser-text\').style.color = "#000000";
	document.getElementById(id+\'-applicationChooser-text\').innerHTML = \'<img style="width:16px;height:16px;vertical-align:middle;margin-right:4px;" src="\'+icon+\'">\'+name;
	document.getElementById(id+\'-applicationChooser-app\').value = app;
	document.getElementById(id).setAttribute("app",app);
	document.getElementById(id).setAttribute("name",name);
	document.getElementById(id).setAttribute("icon",icon);
	document.getElementById(id).setAttribute("publisher",publisher);
	Fly.window.bringToFront();
	document.getElementById(id).onchange();
}

Fly.control.contextMenu = function(pos,options,actions) {
	if(pos.pageX || pos.pageY) {
		this.xpos = pos.pageX;
		this.ypos = pos.pageY;
	} else {
		this.xpos = pos[0];
		this.ypos = pos[1];
	}
	var div = document.createElement("table");
	var rand = Math.floor((Math.random() * 100000) + 1);
	div.id = \'Fly-ContextMenu-\'+rand;
	div.style.left = this.xpos+"px";
	div.style.top = this.ypos+"px";
	div.className = "FlyUiMenu";
	div.oncontextmenu = function() {return false;};
	for (x = 0; x < options.length; x++) { 
		if (actions[x] == "" || actions[x] == "none" || actions[x] == "disabled") {
			this.optionclass = "FlyUiMenuItemDisabled";
			this.optionaction = "";
		} else {
			this.optionclass = "FlyUiMenuItem";
			this.optionaction = \'onclick="\' + \'Fly.control.contextMenu.close(\\\'Fly-ContextMenu-\'+rand+\'\\\');\' + actions[x] + \'"\';
		}
 	   div.innerHTML += \'<tr><td \'+this.optionaction+\' class="\'+this.optionclass+\' FlyUiNoSelect">\' + options[x] + \'</td></tr>\';
 	}
	div.innerHTML += \'</table>\';
	
	var cover = document.createElement("div");
	cover.style.position = "fixed";
	cover.style.top = "0px";
	cover.style.bottom = "0px";
	cover.style.left = "0px";
	cover.style.right = "0px";
	cover.style.opacity = "0";
	cover.style.zIndex = "5000001";
	cover.id = "Fly-ContextMenu-Cover";
	cover.oncontextmenu = function() {Fly.control.contextMenu.close(\'Fly-ContextMenu-\'+rand);};
	cover.onclick = function() {Fly.control.contextMenu.close(\'Fly-ContextMenu-\'+rand);};
	
	document.body.appendChild(cover);
	document.body.appendChild(div);
}
Fly.control.contextMenu.close = function(id) {
	document.getElementById(\'Fly-ContextMenu-Cover\').parentNode.removeChild(document.getElementById(\'Fly-ContextMenu-Cover\'));
	document.getElementById(id).parentNode.removeChild(document.getElementById(id));
}

Fly.control.input = function(msg="Enter text",content="",title="Enter Text",icon="'.$_FLY['RESOURCE']['URL']['ICONS'].'pencil.svg",funct=function(){}) {
	if (icon == "") {
		icon = "'.$_FLY['RESOURCE']['URL']['ICONS'].'pencil.svg";
	}
	var init = function() {
		try {
			frame.window.content.contentWindow.dialog.ret = funct;
			frame.window.content.contentWindow.dialog.enable();
		}
		catch(err) {
			window.top.shell.dialog(\'Control Error - Error\',\'An error has occurred in the control:<pre>\'+err+\'</pre>\',\'Control Error\');
			frame.window.close();
		}
	}
	var frame = window.top.task.create(\''.$_FLY['APP']['ID'].'\',{title:title,x:((window.top.window.innerWidth/2)-258),y:((window.top.window.innerHeight/2)-154),width:500,height:220,location:\'/system/components/dialog.php?type=input&windowid=\'+encodeURIComponent(window.btoa(Fly.window.id))+\'&message=\'+encodeURIComponent(window.btoa(msg))+\'&content=\'+encodeURIComponent(window.btoa(content))+\'&icon=\'+encodeURIComponent(window.btoa(icon)),icon:Fly.window.icon.get(),load:init});
 }

Fly.control.confirm = function(msg="Confirm",content="Are you sure you want to do this?",title="Confirm",icon="'.$_FLY['RESOURCE']['URL']['ICONS'].'question.svg",returntrue=function(){},returnfalse=function(){}) {
	if (icon == "") {
		icon = "'.$_FLY['RESOURCE']['URL']['ICONS'].'question.svg";
	}
	var init = function() {
		try {
			frame.window.content.contentWindow.dialog.retT = returntrue;
			frame.window.content.contentWindow.dialog.retF = returnfalse;
			frame.window.content.contentWindow.dialog.enable();
		}
		catch(err) {
			window.top.shell.dialog(\'Control Error - Error\',\'An error has occurred in the control:<pre>\'+err+\'</pre>\',\'Control Error\');
			frame.window.close();
		}
	}
	var frame = window.top.task.create(\''.$_FLY['APP']['ID'].'\',{title:title,x:((window.top.window.innerWidth/2)-258),y:((window.top.window.innerHeight/2)-154),width:500,height:220,location:\'/system/components/dialog.php?type=confirm&windowid=\'+encodeURIComponent(window.btoa(Fly.window.id))+\'&message=\'+encodeURIComponent(window.btoa(msg))+\'&content=\'+encodeURIComponent(window.btoa(content))+\'&icon=\'+encodeURIComponent(window.btoa(icon)),icon:Fly.window.icon.get(),load:init});
}

setTimeout(function(){document.body.oncontextmenu = "return false;";},500);

//document.getElementsByClassName("fly_filebrowser")*/
</script>
';

// FLY APPLICATION EXTENSIONS
include 'extensions.php';

?>