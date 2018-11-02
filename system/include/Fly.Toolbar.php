<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.TOOLBAR');

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
</script>
';
?>