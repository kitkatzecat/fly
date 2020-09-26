<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.ACTIONMENU');

?>
<script>
if (typeof Fly == "undefined") {
	var Fly = {};
}

Fly.actionmenu = function(pos,moptions,onclose=function(){},show=true) {
	var xpos;
	var ypos;
	if(pos.clientX || pos.clientY) {
		xpos = pos.clientX;
		ypos = pos.clientY;
	} else {
		xpos = pos[0];
		ypos = pos[1];
	}

	var menu = document.createElement('div');
	menu.style.position = 'fixed';
	menu.style.top = ypos+'px';
	menu.style.left = xpos+'px';
	menu.className = 'FlyUiMenu FlyUiNoSelect';
	menu.style.padding = '2px';
	menu.style.zIndex = '1000000000';
	menu.oncontextmenu = function(e) {e.preventDefault(); e.stopPropagation(); return false};
	menu.extrapadding = false;
	menu.onclose = onclose;

	menu.cover = document.createElement('div');
	menu.cover.style.position = 'fixed';
	menu.cover.style.top = '0px';
	menu.cover.style.bottom = '0px';
	menu.cover.style.left = '0px';
	menu.cover.style.right = '0px';
	menu.cover.style.backgroundColor = 'transparent';
	menu.cover.style.zIndex = '999999999';
	menu.cover.onmousedown = function() {
		menu.parentNode.removeChild(menu);
		menu.cover.parentNode.removeChild(menu.cover);
		try {
			menu.onclose();
		} catch(e) {console.log(e);}
	}
	menu.cover.oncontextmenu = function() {return false;}

	menu.options = {};

	if (moptions === undefined || moptions == '') {
		moptions = [];
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
			menuobject.options[index].style.borderTop = '1px solid rgba(0,0,0,0.5)';
			menuobject.options[index].type = 'divider';
		} else if (typeof key[1] == 'function') {
			menuobject.options[index].className = 'FlyUiMenuItem FlyUiText FlyUiNoSelect';
			menuobject.options[index].innerHTML = key[0];
			menuobject.options[index].style.paddingLeft = '4px';
			menuobject.options[index].style.paddingRight = '21px';
			menuobject.options[index].onclick = key[1];
			menuobject.options[index].onmouseup = function(){setTimeout(menuobject.cover.onmousedown,10)};
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
			menuobject.options[index].menu.style.position = 'fixed';
			menuobject.options[index].menu.className = 'FlyUiMenu FlyUiNoSelect';
			menuobject.options[index].menu.style.padding = '2px';
			menuobject.options[index].menu.style.zIndex = (parseInt(menuobject.style.zIndex) + 3);
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
			menuobject.options[index].menu.cover.onmousedown = function() {
				menuobject.options[index].menu.cover.remove();
				menuobject.options[index].menu.parentCover.remove();
				menuobject.options[index].menu.style.top = '';
				menuobject.options[index].menu.style.left = '';
				menuobject.options[index].menu.style.bottom = '';
				menuobject.options[index].menu.style.right = '';
				menuobject.options[index].menu.parentNode.removeChild(menuobject.options[index].menu);
				menuobject.options[index].parentNode.cover.onmousedown();
			}
			menuobject.options[index].menu.cover.oncontextmenu = function() {return false};

			menuobject.options[index].menu.parentCover = document.createElement('div');
			menuobject.options[index].menu.parentCover.style.position = 'fixed';
			menuobject.options[index].menu.parentCover.style.backgroundColor = 'transparent';
			menuobject.options[index].menu.parentCover.style.zIndex = (parseInt(menuobject.style.zIndex) + 2);
			menuobject.options[index].menu.parentCover.onmouseenter = function() {
				menuobject.options[index].menu.parentCover.timeout = setTimeout(menuobject.options[index].menu.parentCover.onclick,600);
			} // HOVER DOESN'T WORK
			menuobject.options[index].menu.parentCover.onmouseout = function() {
				clearTimeout(menuobject.options[index].menu.parentCover.timeout);
			}
			menuobject.options[index].menu.parentCover.onclick = function(e) {
				e.stopPropagation();
				menuobject.options[index].menu.cover.remove();
				menuobject.options[index].menu.parentCover.remove();
				menuobject.options[index].menu.style.top = '';
				menuobject.options[index].menu.style.left = '';
				menuobject.options[index].menu.style.bottom = '';
				menuobject.options[index].menu.style.right = '';
				menuobject.options[index].menu.parentNode.removeChild(menuobject.options[index].menu);
			}
			menuobject.options[index].menu.cover.oncontextmenu = function() {menuobject.options[index].menu.parentCover.onclick();return false};
			
			menuobject.options[index].menu.options = {};
			
			key[1].forEach(function(k,i){menuoptions(k,i,menuobject.options[index].menu);});
			
			menuobject.options[index].onclick = function() {
				menuobject.options[index].menu.style.top = (parseInt(menuobject.style.top)+menuobject.options[index].offsetTop)+'px';
				menuobject.options[index].menu.style.left = ((menuobject.offsetWidth+parseInt(menuobject.style.left)) - 4)+'px';

				menuobject.options[index].menu.parentCover.style.top = menuobject.offsetTop+'px';
				menuobject.options[index].menu.parentCover.style.height = menuobject.offsetHeight+'px';
				menuobject.options[index].menu.parentCover.style.left = menuobject.offsetLeft+'px';
				menuobject.options[index].menu.parentCover.style.width = menuobject.offsetWidth+'px';
				
				document.body.appendChild(menuobject.options[index].menu);
				document.body.appendChild(menuobject.options[index].menu.cover);
				document.body.appendChild(menuobject.options[index].menu.parentCover);
				
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
					var left = (parseInt(menuobject.style.left) - menuobject.options[index].menu.offsetWidth);
					if (left-5 < 0) {
						menuobject.options[index].menu.style.left = '0px';
						var right = (menuobject.options[index].menu.offsetWidth);
						if (right > window.innerWidth) {
							menuobject.options[index].menu.style.left = '0px';
							menuobject.options[index].menu.style.right = '0px';
							menuobject.options[index].menu.style.overflowX = 'auto';
						}
					} else {
						menuobject.options[index].menu.style.left = (left+4)+'px';
					}
				}
				
				document.getSelection().removeAllRanges();
				
			};
			
			menuobject.options[index].mouseHoverTimeout = false;
			menuobject.options[index].onmouseenter = function() {
				menuobject.options[index].mouseHoverTimeout = setTimeout(menuobject.options[index].onclick,400);
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
					menuobject.options[i].innerHTML = '<img style="width:12px;height:12px;padding-right:6px;margin-top:-2px;pointer-events:none;vertical-align:middle;opacity:0;" class="FlyUiNoSelect" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg">'+menuobject.options[i].innerHTML;
				}
			});
			menuobject.extrapadding = true;
		}
		if (menuobject.extrapadding && menuobject.options[index].type !== 'divider' && !menuobject.options[index].extrapadding) {
			menuobject.options[index].innerHTML = '<img style="width:12px;height:12px;padding-right:6px;margin-top:-2px;pointer-events:none;vertical-align:middle;opacity:0;" class="FlyUiNoSelect" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg">'+menuobject.options[index].innerHTML;
		}
		menuobject.appendChild(menuobject.options[index]);
	}

	moptions.forEach(function(key,index){menuoptions(key,index,menu);});
	
	if (show) {
		document.body.appendChild(menu);
		document.body.appendChild(menu.cover);
	}
	
	if ((menu.offsetTop+menu.offsetHeight) > window.innerHeight) {
		var top = (ypos - menu.offsetHeight);
		if (top < 0) {
			menu.style.top = '0px';
			var bottom = (menu.offsetHeight);
			if (bottom > window.innerHeight) {
				menu.style.top = '0px';
				menu.style.bottom = '0px';
				menu.style.overflowY = 'auto';
			}
		} else {
			menu.style.top = top+'px';
		}
	}
	if ((menu.offsetLeft+menu.offsetWidth) > window.innerWidth) {
		var left = (xpos - menu.offsetWidth);
		if (left < 0) {
			menu.style.left = '0px';
			var right = (menu.offsetWidth);
			if (right > window.innerWidth) {
				menu.style.right = '0px';
				menu.style.left = '0px';
				menu.style.overflowX = 'auto';
			}
		} else {
			menu.style.left = left+'px';
		}
	}
	
	document.getSelection().removeAllRanges();
	return menu;
}
</script>