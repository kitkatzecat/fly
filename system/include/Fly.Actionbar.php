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
	function Fly() {};
}

Fly.actionbar = function() {
	var actionbar = document.createElement('div');
	actionbar.style.height = '34px';
	actionbar.style.width = '100%';
	actionbar.oncontextmenu = function() {return false};
	
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
			
			
			/* HANDLED BY Fly.Actionmenu
			button.menu = document.createElement('div');
			button.menu.style.position = 'absolute';
			button.menu.className = 'FlyUiMenu FlyUiNoSelect';
			button.menu.style.padding = '2px';
			button.menu.style.zIndex = '1000000000';
			button.menu.oncontextmenu = function() {return false};
			button.menu.extrapadding = false;
			
			button.menu.cover = document.createElement('div');
			button.menu.cover.style.position = 'fixed';
			button.menu.cover.style.top = '0px';
			button.menu.cover.style.bottom = '0px';
			button.menu.cover.style.left = '0px';
			button.menu.cover.style.right = '0px';
			button.menu.cover.style.backgroundColor = 'transparent';
			button.menu.cover.style.zIndex = '999999999';
			button.menu.cover.oncontextmenu = function() {return false};
			button.menu.cover.onclick = function() {
				if (button.toggled) {
					button.className = 'FlyUiNoSelect FlyUiToolbarItemToggle FlyUiText';
				} else {
					button.className = 'FlyUiNoSelect FlyUiToolbarItem FlyUiText';
				}
				button.menu.parentNode.removeChild(button.menu);
				button.menu.cover.parentNode.removeChild(button.menu.cover);
			}
			
			button.menu.options = {};
			
			if (options.menu === undefined || options.menu == '') {
				options.menu = [];
			}
			
			var menuoptions = function(key,index,menuobject) {
				var extrapadding = false;
				menuobject.options[index] = document.createElement('div');
				menuobject.options[index].extrapadding = false;
				menuobject.options[index].type = '';
				if (typeof key[1] !== 'function' && key[0] == '') {
					menuobject.options[index].style.display = 'inline-block';
					menuobject.options[index].style.width = 'calc(100% - 10px)';
					menuobject.options[index].style.height = '0px';
					menuobject.options[index].style.marginLeft = '5px';
					menuobject.options[index].style.marginTop = '4px';
					menuobject.options[index].style.marginBottom = '4px';
					menuobject.options[index].style.borderTop = '.1px solid #c0c0c0';
					menuobject.options[index].style.borderBottom = '.1px solid #808080';
					menuobject.options[index].type = 'divider';
				} else if (typeof key[1] == 'function') {
					menuobject.options[index].className = 'FlyUiMenuItem FlyUiText FlyUiNoSelect';
					menuobject.options[index].innerHTML = key[0];
					menuobject.options[index].style.paddingLeft = '4px';
					menuobject.options[index].style.paddingRight = '21px';
					menuobject.options[index].onclick = key[1];
					menuobject.options[index].onmouseup = function(){setTimeout(menuobject.cover.onclick,10)};
					menuobject.options[index].style.whiteSpace = 'nowrap';
					menuobject.options[index].disabled = false;
					menuobject.options[index].disable = function() {
						menuobject.options[index].style.opacity = '0.7';
						menuobject.options[index].style.pointerEvents = 'none';
						menuobject.options[index].style.filter = 'grayscale(100%)';
						menuobject.options[index].disabled = true;
					}
					menuobject.options[index].enable = function() {
						menuobject.options[index].style.opacity = '1';
						menuobject.options[index].style.pointerEvents = 'auto';
						menuobject.options[index].style.filter = 'none';
						menuobject.options[index].disabled = false;
					}
					menuobject.options[index].toggled = false;
					menuobject.options[index].toggleOn = function() {
						menuobject.options[index].className = 'FlyUiMenuItemActive';
						menuobject.options[index].toggled = true;
					}
					menuobject.options[index].toggleOff = function() {
						menuobject.options[index].className = 'FlyUiMenuItem';
						menuobject.options[index].toggled = false;
					}
					menuobject.options[index].toggle = function() {
						if (menuobject.options[index].toggled) {
							menuobject.options[index].toggleOff();
						} else {
							menuobject.options[index].toggleOn();
						}
					}
					if (typeof key[2] !== 'undefined') {
					if (typeof key[2].disabled !== 'undefined') {
						if (key[2].disabled == true) {
							menuobject.options[index].disable();
						}
						if (key[2].disabled == false) {
							menuobject.options[index].enable();
						}
					}
					if (typeof key[2].toggled !== 'undefined') {
						if (key[2].toggled == true) {
							menuobject.options[index].toggleOn();
						}
						if (key[2].toggled == false) {
							menuobject.options[index].toggleOff();
						}
					}
					if (typeof key[2].icon !== 'undefined') {
						extrapadding = true;
						menuobject.options[index].extrapadding = true;
						menuobject.options[index].innerHTML = '<img style="width:12px;height:12px;padding-right:6px;margin-top:-2px;pointer-events:none;vertical-align:middle;" class="FlyUiNoSelect" src="'+key[2].icon+'">'+menuobject.options[index].innerHTML;
					}}
					menuobject.options[index].type = 'option';
				} else if (Object.prototype.toString.call(key[1]) === '[object Array]') {
					var arrow = document.createElement('div');
					arrow.style.display = 'inline-block';
					arrow.style.position = 'absolute';
					arrow.style.pointerEvents = 'none';
					arrow.style.top = 'calc(50% - 9px)';
					arrow.style.right = '3px';
					arrow.innerHTML = '▸';
					
					menuobject.options[index].className = 'FlyUiMenuItem FlyUiText FlyUiNoSelect';
					menuobject.options[index].style.position = 'relative';
					menuobject.options[index].style.paddingLeft = '4px';
					menuobject.options[index].style.paddingRight = '21px';
					menuobject.options[index].innerHTML = key[0];
					menuobject.options[index].style.whiteSpace = 'nowrap';
					menuobject.options[index].appendChild(arrow);
					
					menuobject.options[index].menu = document.createElement('div');
					menuobject.options[index].menu.style.position = 'absolute';
					menuobject.options[index].menu.className = 'FlyUiMenu FlyUiNoSelect';
					menuobject.options[index].menu.style.padding = '2px';
					menuobject.options[index].menu.style.zIndex = (parseInt(menuobject.style.zIndex) + 2);
					menuobject.options[index].menu.oncontextmenu = function() {return false};
					menuobject.options[index].menu.extrapadding = false;
					menuobject.options[index].disabled = false;
					menuobject.options[index].disable = function() {
						menuobject.options[index].style.opacity = '0.7';
						menuobject.options[index].style.pointerEvents = 'none';
						menuobject.options[index].style.filter = 'grayscale(100%)';
						menuobject.options[index].disabled = true;
					}
					menuobject.options[index].enable = function() {
						menuobject.options[index].style.opacity = '1';
						menuobject.options[index].style.pointerEvents = 'auto';
						menuobject.options[index].style.filter = 'none';
						menuobject.options[index].disabled = false;
					}
					menuobject.options[index].toggled = false;
					menuobject.options[index].toggleOn = function() {
						menuobject.options[index].className = 'FlyUiMenuItemActive';
						menuobject.options[index].toggled = true;
					}
					menuobject.options[index].toggleOff = function() {
						menuobject.options[index].className = 'FlyUiMenuItem';
						menuobject.options[index].toggled = false;
					}
					menuobject.options[index].toggle = function() {
						if (menuobject.options[index].toggled) {
							menuobject.options[index].toggleOff();
						} else {
							menuobject.options[index].toggleOn();
						}
					}
					
					if (typeof key[2] !== 'undefined') {
					if (typeof key[2].disabled !== 'undefined') {
						if (key[2].disabled == true) {
							menuobject.options[index].disable();
						}
					}
					if (typeof key[2].disabled !== 'undefined') {
						if (key[2].disabled == true) {
							menuobject.options[index].disable();
						}
						if (key[2].disabled == false) {
							menuobject.options[index].enable();
						}
					}
					if (typeof key[2].toggled !== 'undefined') {
						if (key[2].toggled == true) {
							menuobject.options[index].toggleOn();
						}
						if (key[2].toggled == false) {
							menuobject.options[index].toggleOff();
						}
					}
					}
					
					menuobject.options[index].menu.cover = document.createElement('div');
					menuobject.options[index].menu.cover.style.position = 'fixed';
					menuobject.options[index].menu.cover.style.top = '0px';
					menuobject.options[index].menu.cover.style.bottom = '0px';
					menuobject.options[index].menu.cover.style.left = '0px';
					menuobject.options[index].menu.cover.style.right = '0px';
					menuobject.options[index].menu.cover.style.backgroundColor = 'transparent';
					menuobject.options[index].menu.cover.style.zIndex = (parseInt(menuobject.style.zIndex) + 1);
					menuobject.options[index].menu.cover.oncontextmenu = function() {return false};
					menuobject.options[index].menu.cover.onclick = function() {
						menuobject.options[index].menu.cover.parentNode.removeChild(menuobject.options[index].menu.cover);
						menuobject.options[index].menu.style.top = '';
						menuobject.options[index].menu.style.left = '';
						menuobject.options[index].menu.style.bottom = '';
						menuobject.options[index].menu.style.right = '';
						menuobject.options[index].menu.parentNode.removeChild(menuobject.options[index].menu);
						menuobject.options[index].parentNode.cover.onclick();
					}
					
					menuobject.options[index].menu.options = {};
					
					key[1].forEach(function(k,i){menuoptions(k,i,menuobject.options[index].menu);});
					
					menuobject.options[index].onclick = function() {
						menuobject.options[index].menu.style.top = (parseInt(menuobject.style.top)+menuobject.options[index].offsetTop)+'px';
						menuobject.options[index].menu.style.left = ((menuobject.offsetWidth+parseInt(menuobject.style.left)) - 4)+'px';
						document.body.appendChild(menuobject.options[index].menu);
						document.body.appendChild(menuobject.options[index].menu.cover);
						
						
						if ((menuobject.options[index].menu.offsetTop+menuobject.options[index].menu.offsetHeight) > window.innerHeight) {
							var top = ((parseInt(menuobject.style.top)+menuobject.options[index].offsetTop) - menuobject.options[index].menu.offsetHeight + menuobject.options[index].offsetHeight);
							if (top < 0) {
								menuobject.options[index].menu.style.top = '0px';
								var bottom = (menuobject.options[index].menu.offsetHeight);
								if (bottom > window.innerHeight) {
									menuobject.options[index].menu.style.top = '0px';
									menuobject.options[index].menu.style.bottom = '0px';
									menuobject.options[index].menu.style.overflowY = 'auto';
								}
							} else {
								menuobject.options[index].menu.style.top = top+'px';
							}
						}
						if ((menuobject.options[index].menu.offsetLeft+menuobject.options[index].menu.offsetWidth) > window.innerWidth) {
							menuobject.options[index].menu.style.left = (((menuobject.offsetWidth+parseInt(menuobject.style.left)) + 4) - menuobject.options[index].menu.offsetWidth - menuobject.offsetWidth)+'px';
						}
						
						document.getSelection().removeAllRanges();
					};
					
					menuobject.options[index].mouseHoverTimeout = false;
					menuobject.options[index].onmouseenter = function() {
						menuobject.options[index].mouseHoverTimeout = setTimeout(menuobject.options[index].onclick,600);
					}
					menuobject.options[index].onmouseout = function() {
						clearTimeout(menuobject.options[index].mouseHoverTimeout);
					}
					
					if (typeof key[2] !== 'undefined') {
					if (typeof key[2].icon !== 'undefined') {
						extrapadding = true;
						menuobject.options[index].extrapadding = true;
						menuobject.options[index].innerHTML = '<img style="width:12px;height:12px;padding-right:6px;margin-top:-2px;pointer-events:none;vertical-align:middle;" class="FlyUiNoSelect" src="'+key[2].icon+'">'+menuobject.options[index].innerHTML;
					}}
					menuobject.options[index].type = 'menu';
				} else {
					menuobject.options[index].className = 'FlyUiMenuItem FlyUiText FlyUiNoSelect';
					menuobject.options[index].style.pointerEvents = 'none';
					menuobject.options[index].style.paddingLeft = '4px';
					menuobject.options[index].style.paddingRight = '21px';
					menuobject.options[index].style.opacity = '0.7';
					menuobject.options[index].style.whiteSpace = 'nowrap';
					menuobject.options[index].innerHTML = key[0];
					menuobject.options[index].style.filter = 'grayscale(100%)';
					menuobject.options[index].type = 'option';
				}
				if (extrapadding && !menuobject.extrapadding) {
					Object.keys(menuobject.options).forEach(function(i) { 
						if (!menuobject.options[i].extrapadding && menuobject.options[i].type !== 'divider') {
							menuobject.options[i].innerHTML = '<img style="width:12px;height:12px;padding-right:6px;margin-top:-2px;pointer-events:none;vertical-align:middle;opacity:0;" class="FlyUiNoSelect" src="<?php echo FLY_ICONS_URL; ?>arrow-right.svg">'+menuobject.options[i].innerHTML;
						}
					});
					menuobject.extrapadding = true;
				}
				if (menuobject.extrapadding && menuobject.options[index].type !== 'divider' && !menuobject.options[index].extrapadding) {
					menuobject.options[index].innerHTML = '<img style="width:12px;height:12px;padding-right:6px;margin-top:-2px;pointer-events:none;vertical-align:middle;opacity:0;" class="FlyUiNoSelect" src="<?php echo FLY_ICONS_URL; ?>arrow-right.svg">'+menuobject.options[index].innerHTML;
				}
				menuobject.appendChild(menuobject.options[index]);
			}
			
			options.menu.forEach(function(key,index){menuoptions(key,index,button.menu);});
			
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
			*/
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
		
		actionbar.appendChild(button);
		return button;
	}
	
	return actionbar;
}
</script>
