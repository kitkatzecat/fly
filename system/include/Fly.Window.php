<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.WINDOW');

echo '
<script>
if (typeof Fly == "undefined") {
	function Fly() {};
}

if (typeof Fly.window == "undefined") {
	Fly.window = {};
	Fly.window.id = \''.$_GET['Fly_Id'].'\';
	if (Fly.window.id == \'\') {
		try {
			Fly.window.id = window.parent.Fly.window.id;
		} catch(err) {}
	}

	Fly.window.title = function() {
		return Fly.window.title.get();
	}
	Fly.window.title.get = function() {
		return window.top.document.getElementById(Fly.window.id).window.title;
	}
	Fly.window.title.set = function(title) {
		window.top.document.getElementById(Fly.window.id).window.setTitle(title);
		return window.top.document.getElementById(Fly.window.id).window.title;
	}
	Fly.window.title.hide = function() {
		window.top.document.getElementById(Fly.window.id).window.hideTitlebar();
	}
	Fly.window.title.show = function() {
		window.top.document.getElementById(Fly.window.id).window.showTitlebar();
	}
	
	Fly.window.message = function(msg,duration=8) {
		return Fly.window.message.show(msg,duration);
	}
	Fly.window.message.show = function(msg,duration=8) {
		window.top.document.getElementById(Fly.window.id).window.showMessage(msg,duration);
	}
	
	Fly.window.buttons = function() {
		return Fly.window.buttons.hide();
	}
	Fly.window.buttons.hide = function() {
		window.top.document.getElementById(Fly.window.id).window.hideButtons();
	}
	Fly.window.buttons.show = function() {
		window.top.document.getElementById(Fly.window.id).window.showButtons();
	}
	
	Fly.window.border = function() {
		return Fly.window.border.hide();
	}
	Fly.window.border.hide = function() {
		window.top.document.getElementById(Fly.window.id).window.hideBorder();
	}
	Fly.window.border.show = function() {
		window.top.document.getElementById(Fly.window.id).window.showBorder();
	}
	
	Fly.window.name = function() {
		return Fly.window.name.get();
	}
	Fly.window.name.get = function() {
		return window.top.document.getElementById(Fly.window.id).window.name;
	}
	Fly.window.name.set = function(name) {
		window.top.document.getElementById(Fly.window.id).window.setname(name);
		return window.top.document.getElementById(Fly.window.id).window.name;
	}

	Fly.window.icon = function() {
		return Fly.window.icon.get();
	}
	Fly.window.icon.get = function() {
		return window.top.document.getElementById(Fly.window.id).window.icon;
	}
	Fly.window.icon.set = function(icon) {
		window.top.document.getElementById(Fly.window.id).window.setIcon(icon);
		return window.top.document.getElementById(Fly.window.id).window.icon;
	}

	Fly.window.position = function() {
		return Fly.window.position.get();
	}
	Fly.window.position.get = function() {
		if (window.top.document.getElementById(Fly.window.id).window.isExpand) {
			return [0,0];
		} else {
			return [window.top.document.getElementById(Fly.window.id).style.left.replace(/\\D/g,\'\'),window.top.document.getElementById(Fly.window.id).style.top.replace(/\\D/g,\'\')];
		}
	}
	Fly.window.position.set = function(x=32,y=32) {
		window.top.document.getElementById(Fly.window.id).window.setPosition(x,y);
		return [window.top.document.getElementById(Fly.window.id).style.left.replace(/\\D/g,\'\'),window.top.document.getElementById(Fly.window.id).style.top.replace(/\\D/g,\'\')];
	}

	Fly.window.size = function() {
		return Fly.window.size.get();
	}
	Fly.window.size.get = function() {
		return [window.top.document.getElementById(Fly.window.id).window.content.offsetWidth,window.top.document.getElementById(Fly.window.id).window.content.offsetHeight];
	}
	Fly.window.size.set = function(width=320,height=240) {
		window.top.document.getElementById(Fly.window.id).window.setSize(width,height);
		return [window.top.document.getElementById(Fly.window.id).window.content.style.width.replace(/\\D/g,\'\'),window.top.document.getElementById(Fly.window.id).window.content.style.height.replace(/\\D/g,\'\')];
	}
	Fly.window.resize = function() {
		return Fly.window.resize.enable();
	}
	Fly.window.resize.enable = function() {
		window.top.document.getElementById(Fly.window.id).window.isResizable = true;
	}
	Fly.window.resize.disable = function() {
		window.top.document.getElementById(Fly.window.id).window.isResizable = false;
	}

	Fly.window.close = function() {
		window.top.document.getElementById(Fly.window.id).window.forceClose();
	}
	Fly.window.close.enable = function() {
		window.top.document.getElementById(Fly.window.id).window.composition.close.status.show();
	}
	Fly.window.close.disable = function() {
		window.top.document.getElementById(Fly.window.id).window.composition.close.status.hide();
	}
	Fly.window.onclose = Fly.window.close;
	Fly.window.sendToBack = function() {
		window.top.document.getElementById(Fly.window.id).window.sendToBack();
	}
	Fly.window.bringToFront = function() {
		window.top.document.getElementById(Fly.window.id).window.bringToFront();
	}
	Fly.window.minimize = function() {
		window.top.document.getElementById(Fly.window.id).window.minimize();
	}
	Fly.window.minimize.enable = function() {
		window.top.document.getElementById(Fly.window.id).window.composition.minimize.status.show();
	}
	Fly.window.minimize.disable = function() {
		window.top.document.getElementById(Fly.window.id).window.composition.minimize.status.hide();
	}
	Fly.window.maximize = function() {
		window.top.document.getElementById(Fly.window.id).window.maximize();
	}
	Fly.window.expand = function() {
		window.top.document.getElementById(Fly.window.id).window.expand();
	}
	Fly.window.expand.enable = function() {
		window.top.document.getElementById(Fly.window.id).window.composition.expand.status.show();
	}
	Fly.window.expand.disable = function() {
		window.top.document.getElementById(Fly.window.id).window.composition.expand.status.hide();
	}
	Fly.window.restore = function() {
		window.top.document.getElementById(Fly.window.id).window.restore();
	}

	Fly.window.focus = function() {
		Fly.window.focus.self();
	}
	Fly.window.focus.self = function() {
		window.top.document.getElementById(Fly.window.id).window.resetFocus();
	}
	Fly.window.focus.get = function() {
		return window.top.document.getElementById(Fly.window.id).window.focus;
	}
	Fly.window.focus.set = function(id) {
		return window.top.document.getElementById(Fly.window.id).window.setFocus(id);
	}
	Fly.window.focus.take = function(id) {
		window.top.document.getElementById(id).window.setFocus(Fly.window.id);
	}
	Fly.window.focus.give = function(id) {
		window.top.document.getElementById(id).window.resetFocus();
	}

	Fly.window.flash = function() {
		window.top.document.getElementById(Fly.window.id).window.flash();
	}
	
	Fly.window.movement = function(x,y,w,h) {
		return Fly.window.movement.set(x,y,w,h);
	}
	Fly.window.movement.set = function(x,y,w,h) {
		window.top.document.getElementById(Fly.window.id).window.composition.secondaryMovement.set(x,y,w,h);
	}
	Fly.window.movement.reset = function() {
		window.top.document.getElementById(Fly.window.id).window.composition.secondaryMovement.reset();
	}

}
</script>
';
?>