<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.ACTIONMENU')) {
	include 'Fly.Actionmenu.php';
}
FlyIncludeRegister('FLY.CONTROLS');
echo '
<script>
if (typeof Fly == \'undefined\') {
	var Fly = {};
}

Fly.control = {};

Fly.control.progressBar = function(element,options) {
	element.className = "";
}

Fly.control.contextMenu = function(pos,options,actions) {
	var moptions = [];
	options.forEach(function(value,index) {
		var copt = [];
		copt.push(value);
		if (actions[index] !== "" && actions[index] !== "none" && actions[index] !== "disabled") {
			copt.push(function() {eval(actions[index]);});
		}
		moptions.push(copt);
	});
	Fly.actionmenu(pos,moptions);
}
Fly.control.contextMenu.close = function(id) {
	/*
	document.getElementById(\'Fly-ContextMenu-Cover\').parentNode.removeChild(document.getElementById(\'Fly-ContextMenu-Cover\'));
	document.getElementById(id).parentNode.removeChild(document.getElementById(id));
	*/
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
	var frame = window.top.task.create(\''.$_FLY['APP']['ID'].'\',{title:title, name:Fly.window.name.get(),x:((window.top.window.innerWidth/2)-258),y:((window.top.window.innerHeight/2)-154),width:500,height:220,location:\'/system/components/dialog.php?type=input&windowid=\'+encodeURIComponent(window.btoa(Fly.window.id))+\'&message=\'+encodeURIComponent(window.btoa(msg))+\'&content=\'+encodeURIComponent(window.btoa(content))+\'&icon=\'+encodeURIComponent(window.btoa(icon)),icon:Fly.window.icon.get(),load:init});
	return frame;
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
	var frame = window.top.task.create(\''.$_FLY['APP']['ID'].'\',{title:title, name:Fly.window.name.get(),x:((window.top.window.innerWidth/2)-258),y:((window.top.window.innerHeight/2)-154),width:500,height:100,location:\'/system/components/dialog2.php?type=confirm&windowid=\'+encodeURIComponent(window.btoa(Fly.window.id))+\'&message=\'+encodeURIComponent(window.btoa(msg))+\'&content=\'+encodeURIComponent(window.btoa(content))+\'&icon=\'+encodeURIComponent(window.btoa(icon)),icon:Fly.window.icon.get(),load:init});
	return frame;
}

Fly.control.modal = function(msg="Information",content="Something happened.",title="Information",icon="'.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg",returntrue=function(){}) {
	if (icon == "") {
		icon = "'.$_FLY['RESOURCE']['URL']['ICONS'].'question.svg";
	}
	var init = function() {
		try {
			frame.window.content.contentWindow.dialog.retT = returntrue;
			frame.window.content.contentWindow.dialog.enable();
		} catch(err) {
			window.top.shell.dialog(\'Control Error - Error\',\'An error has occurred in the control:<pre>\'+err+\'</pre>\',\'Control Error\');
			frame.window.close();
		}
	}
	var frame = window.top.task.create(\''.$_FLY['APP']['ID'].'\',{title:title, name:Fly.window.name.get(), x:((window.top.window.innerWidth/2)-258),y:((window.top.window.innerHeight/2)-154),width:500,height:220,location:\'/system/components/dialog.php?type=modal&windowid=\'+encodeURIComponent(window.btoa(Fly.window.id))+\'&message=\'+encodeURIComponent(window.btoa(msg))+\'&content=\'+encodeURIComponent(window.btoa(content))+\'&icon=\'+encodeURIComponent(window.btoa(icon)),icon:Fly.window.icon.get(),load:init});
	return frame;
}

Fly.control.password = function(msg="Enter your password to continue:",returntrue=function(){},returnfalse=function(){}) {
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
	var frame = window.top.task.create(\''.$_FLY['APP']['ID'].'\',{title:Fly.window.name.get()+\' - Enter Password\', name:Fly.window.name.get(), x:((window.top.window.innerWidth/2)-258),y:((window.top.window.innerHeight/2)-154),width:500,height:220,location:\'/system/components/dialog.php?type=password&windowid=\'+encodeURIComponent(window.btoa(Fly.window.id))+\'&msg=\'+encodeURIComponent(window.btoa(msg)),icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'lock.svg\',load:init});
	return frame;
}
Fly.control.progress = function(msg="Loading...",title="Loading",callback=function(){}) {
	var init = function() {
		try {
			frame.window.setPosition(parseInt(Fly.window.position.get()[0])+parseInt((Fly.window.size.get()[0]/2)-(frame.offsetWidth/2)),parseInt(Fly.window.position.get()[1])+parseInt((Fly.window.size.get()[1]/2)-(frame.offsetHeight/2)));
			callback(frame.window.content.contentWindow.dialog);
		}
		catch(err) {
			window.top.shell.dialog(\'Control Error - Error\',\'An error has occurred in the control:<pre>\'+err+\'</pre>\',\'Control Error\');
			frame.window.close();
		}
	}
	var frame = window.top.task.create(\'public\', {title:Fly.window.name.get()+\' - \'+title, name:Fly.window.name.get(), x:Fly.window.position.get()[0], y:Fly.window.position.get()[1], width:300, height:64, location:\'/system/components/dialog.php?type=progress&content=\'+encodeURIComponent(window.btoa(msg))+\'&windowid=\'+encodeURIComponent(window.btoa(Fly.window.id)), icon:Fly.window.icon.get(),load:init});
	return frame;
}
Fly.control.wait = function(msg="Loading...",title="Loading",callback=function(){}) {
	var init = function() {
		try {
			frame.window.setPosition(parseInt(Fly.window.position.get()[0])+parseInt((Fly.window.size.get()[0]/2)-(frame.offsetWidth/2)),parseInt(Fly.window.position.get()[1])+parseInt((Fly.window.size.get()[1]/2)-(frame.offsetHeight/2)));
			callback(frame.window.content.contentWindow.dialog);
		}
		catch(err) {
			window.top.shell.dialog(\'Control Error - Error\',\'An error has occurred in the control:<pre>\'+err+\'</pre>\',\'Control Error\');
			frame.window.close();
		}
	}
	var frame = window.top.task.create(\'public\', {title:Fly.window.name.get()+\' - \'+title, name:Fly.window.name.get(), x:Fly.window.position.get()[0], y:Fly.window.position.get()[1], width:300, height:64, location:\'/system/components/dialog.php?type=wait&content=\'+encodeURIComponent(window.btoa(msg))+\'&windowid=\'+encodeURIComponent(window.btoa(Fly.window.id)), icon:Fly.window.icon.get(),load:init});
	return frame;
}

Fly.control.openFile = function(options={},callback=function(){}) {

}

</script>
';
?>