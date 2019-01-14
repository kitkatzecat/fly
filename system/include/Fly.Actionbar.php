<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.ACTIONBAR');
if (!FlyIncludeCheck('FLY.ACTIONMENU')) {
	include 'Fly.Actionmenu.php';
}

?>
<script>
if (typeof Fly == "undefined") {
	var Fly = {};
}

Fly.actionbar = function() {
	var actionbar = document.createElement('div');
	actionbar.style.height = '34px';
	actionbar.style.width = '100%';
	actionbar.oncontextmenu = function() {return false};
	actionbar.buttons = [];
	
	actionbar.add = function(options) {
		if (options.type === undefined || options.type == '') {
			options.type = 'button';
		}
		var button = document.createElement('div');
		
		if (options.type == 'button') {
			button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
			button.oncontextmenu = function() {return false};
			
			if (options.icon === undefined || options.icon == '') {
				button.icon = '';
			} else {
				if (options.icon.substr(0,4) == 'data') {
					var exists = true;
				} else {
				    var http = new XMLHttpRequest();
				    http.open('HEAD', options.icon+'?rand='+Math.floor( Math.random() * 10000 ), true);
				    http.send();
				    if (http.status!=404) {
						var exists = true;
					}
				}
				if (exists) {
		            if (options.text === undefined || options.text == '') {
						button.icon = '<img src="'+options.icon+'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;">';
					} else {
						button.icon = '<img src="'+options.icon+'" style="width:16px;height:16px;margin-right:4px;vertical-align:middle;pointer-events:none;">';
					}
				} else {
					button.icon = '';
				}
			}
			
			if (options.text === undefined || options.text == '') {
				options.text = '';
			}
			button.text = options.text;
			
			if (options.align === undefined || options.align == '') {
				options.align = 'left';
			}
			button.style.float = options.align;
			
			button.innerHTML = button.icon+button.text;
			
			if (options.action === undefined || options.action == '') {
				options.action = function() {};
			}
			button.onclick = options.action;
			
			button.setText = function(text) {
				button.innerHTML = button.icon+text;
			}
			
			button.setIcon = function(icon) {
				if (icon.substr(0,4) == 'data') {
					var exists = true;
				} else {
				    var http = new XMLHttpRequest();
				    http.open('HEAD', icon+'?rand='+Math.floor( Math.random() * 10000 ), false);
				    http.send();
				    if (http.status!=404) {
						var exists = true;
					}
				}
				if (exists) {
		            if (button.text == '') {
						button.icon = '<img src="'+icon+'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;">';
					} else {
						button.icon = '<img src="'+icon+'" style="width:16px;height:16px;margin-right:4px;vertical-align:middle;pointer-events:none;">';
					}
				} else {
					button.icon = '';
				}
				button.innerHTML = button.icon+text;
			}
			
			button.setAction = function(action) {
				button.onclick = action;
			}
			
			button.disabled = false;
			button.disable = function() {
				button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
				button.style.pointerEvents = 'none';
				button.disabled = true;
			}
			button.enable = function() {
				button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
				button.style.pointerEvents = 'auto';
				button.disabled = false;
			}
			if (options.disabled) {
				button.disable();
			}
			
			button.toggled = false;
			button.toggle = function() {
				if (button.className == 'FlyUiNoSelect FlyUiToolbarItem FlyUiText') {
					button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
					button.toggled = true;
				} else {
					button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
					button.toggled = false;
				}
			}
			button.toggleOn = function() {
				button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
				button.toggled = true;
			}
			button.toggleOff = function() {
				button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
				button.toggled = false;
			}
			if (options.toggled) {
				button.toggleOn();
			}
		}
		
		if (options.type == 'title') {
			button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
			button.style.pointerEvents = 'none';
			button.oncontextmenu = function() {return false};
			
			if (options.icon === undefined || options.icon == '') {
				button.icon = '';
			} else {
				if (options.icon.substr(0,4) == 'data') {
					var exists = true;
				} else {
				    var http = new XMLHttpRequest();
				    http.open('HEAD', options.icon+'?rand='+Math.floor( Math.random() * 10000 ), false);
				    http.send();
				    if (http.status!=404) {
						var exists = true;
					}
				}
				if (exists) {
		            if (options.text === undefined || options.text == '') {
						button.icon = '<img src="'+options.icon+'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;">';
					} else {
						button.icon = '<img src="'+options.icon+'" style="width:16px;height:16px;margin-right:4px;vertical-align:middle;pointer-events:none;">';
					}
				} else {
					button.icon = '';
				}
			}
			
			if (options.text === undefined || options.text == '') {
				options.text = '';
			}
			button.text = options.text;
			
			if (options.align === undefined || options.align == '') {
				options.align = 'left';
			}
			button.style.float = options.align;
			
			button.innerHTML = button.icon+button.text;
			
			if (options.action === undefined || options.action == '') {
				options.action = function() {};
			}
			
			button.setText = function(text) {
				button.innerHTML = button.icon+text;
			}
			
			button.setIcon = function(icon) {
				if (icon.substr(0,4) == 'data') {
					var exists = true;
				} else {
				    var http = new XMLHttpRequest();
				    http.open('HEAD', icon+'?rand='+Math.floor( Math.random() * 10000 ), false);
				    http.send();
				    if (http.status!=404) {
						var exists = true;
					}
				}
				if (exists) {
		            if (button.text == '') {
						button.icon = '<img src="'+icon+'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;">';
					} else {
						button.icon = '<img src="'+icon+'" style="width:16px;height:16px;margin-right:4px;vertical-align:middle;pointer-events:none;">';
					}
				} else {
					button.icon = '';
				}
				button.innerHTML = button.icon+text;
			}
		}
		
		if (options.type == 'dropdown') {
			button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
			button.oncontextmenu = function() {return false};
			
			if (options.icon === undefined || options.icon == '') {
				button.icon = '';
			} else {
				if (options.icon.substr(0,4) == 'data') {
					var exists = true;
				} else {
				    var http = new XMLHttpRequest();
				    http.open('HEAD', options.icon+'?rand='+Math.floor( Math.random() * 10000 ), false);
				    http.send();
				    if (http.status!=404) {
						var exists = true;
					}
				}
				if (exists) {
		            if (options.text === undefined || options.text == '') {
						button.icon = '<img src="'+options.icon+'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;">';
					} else {
						button.icon = '<img src="'+options.icon+'" style="width:16px;height:16px;margin-right:4px;vertical-align:middle;pointer-events:none;">';
					}
				} else {
					button.icon = '';
				}
			}
			
			if (options.text === undefined || options.text == '') {
				options.text = '';
			}
			button.text = options.text;
			
			if (options.align === undefined || options.align == '') {
				options.align = 'left';
			}
			button.style.float = options.align;
			
			button.innerHTML = button.icon+button.text+' ▾';
			
			
			if (options.menu === undefined || options.menu == '') {
				options.menu = [];
			}
			button.menu = Fly.actionmenu([0,0],options.menu,function() {
				if (button.toggled) {
					button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
				} else {
					button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
				}
			},false);
			
			// MENU HANDLED BY Fly.Actionmenu

			button.onclick = function() {
				button.menu.style.top = ((actionbar.offsetTop+button.offsetTop+button.offsetHeight) - 1)+'px';
				button.menu.style.left = (button.offsetLeft+actionbar.offsetLeft)+'px';
				button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
				document.body.appendChild(button.menu);
				document.body.appendChild(button.menu.cover);
				
				if ((button.menu.offsetTop+button.menu.offsetHeight) > (window.innerHeight+window.pageYOffset)) {
					button.menu.style.top = ((actionbar.offsetTop - button.menu.offsetHeight) + 1)+'px';
				}
				if ((button.menu.offsetLeft+button.menu.offsetWidth) > (window.innerWidth+window.pageXOffset)) {
					button.menu.style.left = ((button.offsetLeft+actionbar.offsetLeft) - (button.menu.offsetWidth - button.offsetWidth))+'px';
				}
				
				document.getSelection().removeAllRanges();
			}
			
			button.setText = function(text) {
				button.innerHTML = button.icon+text+' ▾';
			}
			
			button.setIcon = function(icon) {
				if (icon.substr(0,4) == 'data') {
					var exists = true;
				} else {
				    var http = new XMLHttpRequest();
				    http.open('HEAD', icon+'?rand='+Math.floor( Math.random() * 10000 ), false);
				    http.send();
				    if (http.status!=404) {
						var exists = true;
					}
				}
				if (exists) {
		            if (button.text == '') {
						button.icon = '<img src="'+icon+'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;">';
					} else {
						button.icon = '<img src="'+icon+'" style="width:16px;height:16px;margin-right:4px;vertical-align:middle;pointer-events:none;">';
					}
				} else {
					button.icon = '';
				}
				button.innerHTML = button.icon+text+' ▾';
			}
			
			button.disabled = false;
			button.disable = function() {
				button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
				button.style.pointerEvents = 'none';
				button.disabled = true;
			}
			button.enable = function() {
				button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
				button.style.pointerEvents = 'auto';
				button.disabled = false;
			}
			if (options.disabled) {
				button.disable();
			}
			
			button.toggled = false;
			button.toggle = function() {
				if (button.className == 'FlyUiNoSelect FlyUiToolbarItem FlyUiText') {
					button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
					button.toggled = true;
				} else {
					button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
					button.toggled = false;
				}
			}
			button.toggleOn = function() {
				button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
				button.toggled = true;
			}
			button.toggleOff = function() {
				button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
				button.toggled = false;
			}
			if (options.toggled) {
				button.toggleOn();
			}
		}
		
		if (options.type == 'custom') {
			button.appendChild(options.content);

			if (options.align === undefined || options.align == '') {
				options.align = 'left';
			}
			button.style.float = options.align;
		}

		if (options.type == 'divider') {
			if (options.align === undefined || options.align == '') {
				options.align = 'left';
			}
			button.style.float = options.align;
			button.oncontextmenu = function() {return false};

			button.style.display = 'inline-block';
			button.style.height = '24px';
			button.style.width = '0px';
			button.style.marginTop = '5px';
			button.style.marginLeft = '4px';
			button.style.marginRight = '4px';
			button.style.borderLeft = '.1px solid #c0c0c0';
			button.style.borderRight = '.1px solid #808080';
		}

		if (typeof options.title != 'undefined') {
			button.title = options.title;
		}
		
		actionbar.appendChild(button);
		actionbar.buttons.push(button);
		return button;
	}
	
	return actionbar;
}
</script>
