<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';

if ($_GET['d'] == 'color') {
	goto d_color;
}

d_custom:
?>
<script>
var Dialog = {
	opener: {},
	attributes: {},
	default: function() {},
	ready: function() {
		document.querySelector('img#icon').src = Dialog.attributes.icon;
		document.querySelector('span#title').innerHTML = Dialog.attributes.message;
		document.querySelector('p#content').innerHTML = Dialog.attributes.content;

		if (!Dialog.attributes.hasOwnProperty('content') || Dialog.attributes.content == '') {
			document.querySelector('p#content').style.display = 'none';
		}

		if (!Dialog.attributes.hasOwnProperty('icon') || Dialog.attributes.icon == '') {
			document.querySelector('img#icon').style.display = 'none';
		}

		Dialog.attributes.buttons.forEach(function(element,index) {
			let b = document.createElement('button');
			b.className = 'Button';
			b.style.right = ((9*(index+1))+(100*index))+'px';

			b.innerHTML = '';
			if (element.hasOwnProperty('image') && element.image != '') {
				b.innerHTML += '<img class="button-image" src="'+element.image+'">';
			}
			if (element.hasOwnProperty('text') && element.text != '') {
				if (typeof element.image != 'undefined' && element.image != '') {
					b.innerHTML += ' '+element.text;
				} else {
					b.innerHTML += element.text;
				}
			}

			b.onclick = function() {
				if (Dialog.attributes.hasOwnProperty('input') && Dialog.attributes.input.hasOwnProperty('validate') && !!Dialog.attributes.input.validate && element.hasOwnProperty('validate') && element.validate == true) {
					if (!Dialog.validateInput()) {
						return false;
					}
				} else if (Dialog.attributes.hasOwnProperty('input') && Dialog.attributes['input']['type'] == 'list' && element.hasOwnProperty('validate') && element.validate == true) {
					if (!Dialog.validateList()) {
						return false;
					}
				}
				if (Dialog.attributes.hasOwnProperty('input') && Dialog.attributes.input.type == 'select') {
					document.getElementById('input').value = document.getElementById('select').value;
				}
				var v = document.getElementById('input').value;
				var c = document.getElementById('checkbox').checked;
				try {
					element.onclick(v,c);
				} catch(e) {console.log(e)}
				Fly.window.onclose();
			};
			if (element.default) {
				Dialog.default = b.onclick;
			}
			document.body.appendChild(b);
		});

		if (!!Dialog.attributes.checkbox) {
			var checkboxContainer = document.getElementById('checkboxContainer');
			var checkbox = document.getElementById('checkbox');
			var checkboxLabel = document.getElementById('checkboxLabel');

			checkboxContainer.style.display = 'block';

			if (Dialog.attributes.checkbox.hasOwnProperty('text')) {
				checkboxLabel.innerText = Dialog.attributes.checkbox.text;
			}
			if (Dialog.attributes.checkbox.hasOwnProperty('checked')) {
				checkbox.checked = !!Dialog.attributes.checkbox.checked;
			}

			if (checkboxContainer.offsetWidth+10 > window.innerWidth-(Dialog.attributes.buttons.length)*109) {
				Dialog.positionModifier += 28;
				checkboxContainer.classList.add('checkbox');
				document.getElementById('Content').classList.add('checkbox');
			}

			//checkboxContainer.style.right = ((Dialog.attributes.buttons.length)*109)+'px';
		}

		if (!!Dialog.attributes.input) {
			var input = document.querySelector('input#input');
			document.querySelector('div.input').style.display = 'block';
			if (Dialog.attributes.input.type == 'text') {
				input.type = 'text';
			} else if (Dialog.attributes.input.type == 'date') {
				input.type = 'date';
			} else if (Dialog.attributes.input.type == 'time') {
				input.type = 'time';
			} else if (Dialog.attributes.input.type == 'number') {
				input.type = 'number';
			} else if (Dialog.attributes.input.type == 'password') {
				input.type = 'password';
			} else if (Dialog.attributes.input.type == 'select') {
				document.querySelector('div.input').style.display = 'none';
				document.querySelector('div.select').style.display = 'block';
				var select = document.getElementById('select');

				Dialog.attributes.input.options.forEach(function(element) {
					let option = document.createElement('option');
					option.text = element['text'];
					option.value = element['value'];
					option.selected = (element['selected'] ? true : false);
					option.disabled = (element['disabled'] ? true : false);
					select.add(option);
				});
			} else if (Dialog.attributes.input.type == 'list') {
				document.querySelector('div.input').style.display = 'none';
				document.querySelector('div.richSelect').style.display = 'block';

				var list = document.querySelector('.richSelect');
				var input = document.getElementById('input');

				Dialog.attributes.input.options.forEach(function(element,i) {
					if (element.hasOwnProperty('header') && !!element['header']) {
						let header = document.createElement('div');
						header.className = 'richSelectHeader';
						header.innerHTML = element['text'];
						list.appendChild(header);
					} else {
						let option = document.createElement('div');
						option.className = 'richSelectItem FlyUiMenuItem FlyUiNoSelect '+(element['selected'] ? 'FlyUiMenuItemActive' : '');
						option.innerHTML = (element.hasOwnProperty('icon') ? '<img class="richSelectIcon" src="'+element['icon']+'">' : '')+'<div class="richSelectTitle">'+element['text']+'</div>';
						option.value = element['value'];
						option.onclick = function() {
							document.querySelectorAll('.richSelectItem').forEach(function(e) {
								e.classList.remove('FlyUiMenuItemActive');
							});
							option.classList.add('FlyUiMenuItemActive');
							input.value = option.value;
						};
						list.appendChild(option);
						if (!!element['selected']) {
							option.onclick();
						}
						if (!!element['disabled']) {
							option.style.filter = 'grayscale(100%)';
							option.style.pointerEvents = 'none';
						}
					}
				});

				list.style.height = Math.min(list.scrollHeight+8,300)+'px';
			} else {
				input.type = 'text';
			}

			if (Dialog.attributes.input.hasOwnProperty('min')) {
				input.min = Dialog.attributes.input['min'];
			}
			if (Dialog.attributes.input.hasOwnProperty('max')) {
				input.max = Dialog.attributes.input['max'];
			}

			if (Dialog.attributes.input.hasOwnProperty('validate') && !!Dialog.attributes.input.validate && Dialog.attributes.input.type != 'select') {
				var valmsg = document.querySelector('p#validateMessage');
				if (Dialog.attributes.input.hasOwnProperty('validateMessage')) {
					valmsg.innerHTML = Dialog.attributes.input['validateMessage'];
				} else {
					valmsg.innerHTML = 'Invalid input';
				}
			}

			if (Dialog.attributes.input.hasOwnProperty('placeholder')) {
				input.placeholder = Dialog.attributes.input.placeholder;
			}
			if (Dialog.attributes.input.hasOwnProperty('value')) {
				input.value = Dialog.attributes.input.value;
			}

			input.focus();
		}

		Dialog.position();

		document.addEventListener("keypress", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				try {
					Dialog.default();
				} catch(e) {
					console.log('Error in executing default function: '+e,e);
				}
			}
		}, false);
		document.getElementById('input').addEventListener("keypress", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				e.stopPropagation();
				try {
					Dialog.default();
				} catch(e) {
					console.log('Error in executing default function from input: '+e,e);
				}
			}
		}, false);

		try {
			window.top.shell.sound.system(Dialog.attributes.sound);
		} catch(e) {}
	},
	positionModifier: 48,
	position: function() {
		var height = (Dialog.positionModifier+Math.max(document.getElementById('Content').scrollHeight,0));
		Fly.window.size.set(400,height);
		var width = Fly.window.size.get()[0];
		var opener = window.top.document.getElementById(Dialog.opener.Fly.window.id);

		if (opener.window.isBackground || opener.window.isExpand || opener.window.isBorderless) {
			height = window.top.document.getElementById(Fly.window.id).offsetHeight;
			width = window.top.document.getElementById(Fly.window.id).offsetWidth;
		}
		Fly.window.position.set((Dialog.opener.Fly.window.position.get()[0]+((Dialog.opener.Fly.window.size.get()[0]/2)-(width/2))),(Dialog.opener.Fly.window.position.get()[1]+((Dialog.opener.Fly.window.size.get()[1]/2)-(height/2))));
	},
	positionRelative: function(oldheight) {
		var position = Fly.window.position.get();
		var height = window.top.document.getElementById(Fly.window.id).offsetHeight;
		Fly.window.position.set(position[0],Math.max((position[1]-((height-oldheight)/2)),0))
	},
	validateInput: function() {
		var input = document.querySelector('input#input');
		var rx = new RegExp(Dialog.attributes.input.validate);
		var valmsg = document.querySelector('p#validateMessage');
		
		var test = rx.test(input.value);

		if (test) {
			return true;
		} else {
			if (valmsg.style.display != 'block') {
				valmsg.style.display = 'block';
				var oldheight = window.top.document.getElementById(Fly.window.id).offsetHeight;
				console.log(Dialog.positionModifier);
				Dialog.position();
				Dialog.positionRelative(oldheight);
			} else {
				Fly.window.flash();
			}
			try {
				window.top.shell.sound.system('alert');
			} catch(e) {}
			return false;
		}
	},
	validateList: function() {
		let r = false;
		document.querySelectorAll('.richSelectItem').forEach(function(i) {
			if (i.classList.contains('FlyUiMenuItemActive')) {
				r = true;
			}
		});

		if (r) {
			return true;
		} else {
			Fly.window.message('Select an option',4);
			Fly.window.flash();
			try {
				window.top.shell.sound.system('alert');
			} catch(e) {}
			return false;
		}
	}
};
Fly.window.ready = function() {
	Fly.window.disableContext();
	Dialog.ready();
}
Fly.window.onclose = function() {
	try {
		Dialog.opener.Fly.window.focus.self();
		Dialog.opener.Fly.window.bringToFront();
	} catch(e) {console.log(e)}
	Fly.window.close();
}
</script>
<?php
include 'Fly.CommonStyle.php';
?>
<style>
body {
	margin: 0px;
	padding: 0px;
	background: transparent;
}
h1,h2,h3,h4,h5,h6,p {
	padding-left: 9%;
	padding-right: 9%;
}
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 16px;
	padding-bottom: 16px;
	padding-left: 9%;
	padding-right: 9%;
}
p {
	padding-right: 0;
	padding-left: 0;
}
div.description {
	padding-left: 9%;
	padding-right: 9%;
}
p.hint {
	font-size: 0.8em;
	opacity: 0.8;
	margin-top: -12px;
	padding-left: 44px;
}
p.shead {
	font-size: 0.8em;
	opacity: 0.8;
	margin-bottom: -16px;
}
.inline-icon {
	width: 18px;
	height: 18px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
.title-icon {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
img.button-image {
	width: 16px;
	height: 16px;
	vertical-align: middle;
}
#Content {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 4px;
	background-color: #fff;
	/*overflow-y: auto;*/
	overflow: hidden;
}
#Content.checkbox {
	bottom: 76px;
}
p#content {
	margin-top: -12px;
}
input#input,select#select {
	width: 90%;
}
.input,.select,.richSelect {
	display: none;
	padding-left: 9%;
	padding-right: 9%;
	margin-top: -6px;
	padding-bottom: 12px;
}
.richSelect {
	margin: -4px;
	/*transform: translateY(16px);*/
	box-sizing: border-box;
	overflow-x: hidden;
	overflow-y: auto;
	display: block;
	position: relative;
}
.richSelectItem {

}
.richSelectIcon {
	width: 24px;
	height: 24px;
	vertical-align: middle;
	margin-right: 6px;
}
.richSelectTitle {
	vertical-align: middle;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
	width: calc(100% - 30px);
	display: inline-block;
}
.richSelectHeader {
	position: sticky;
	top: -4px;
	background-color: #fff;
	z-index: 2;
	border-bottom: 1px solid;
	border-image-slice: 1;
	border-image-source: linear-gradient(to right, rgba(136,136,136,1), rgba(136,136,136,0));
	color: #888;
	padding-left: 4px;
	padding-right: 6px;
	padding-bottom: 3px;
	padding-top: 4px;
	margin-bottom: 2px;
	font-size: 14px;
}
.Button {
	width: 100px;
	position: absolute;
	bottom: 9px;
}
#checkboxContainer {
	position: absolute;
	bottom: 14px;
	left: 6px;
	padding-top: 6px;
	padding-bottom: 6px;
	padding-right: 6px;
	margin-top: -6px;
	margin-bottom: -6px;
	overflow: hidden;
	max-width: calc(100vw - 18px);
	white-space: nowrap;
	text-overflow: ellipsis;
	display: none;
}
#checkboxContainer.checkbox {
	bottom: 48px;
}
#checkbox {
	margin-top: -3px;
}
#validateMessage {
	color: #f00;
	display: none;
	margin-top: 6px;
	margin-bottom: -14px;
}
</style>
</head>
<body>

<div id="Content">

<div class="title FlyUiNoSelect"><img id="icon" class="title-icon" src=""><span id="title"></span></div>
<div class="description FlyUiNoSelect"><p id="content"></p></div>
<div class="input"><input autocomplete="off" type="text" id="input">
<p id="validateMessage" class="FlyCSDescriptionHint"></p></div>
<div class="richSelect"></div>
<div class="select"><select id="select"></select></div>

</div>
<div id="checkboxContainer" class="FlyUiNoSelect FlyUiTextHover">
	<input type="checkbox" id="checkbox" class="FlyCSInlineIcon"><label for="checkbox" id="checkboxLabel"></label>
</div>

</body>
</html>

<?php
exit;


d_color:
?>

<script>
// Dialog functions
var Dialog = {
	opener: {},
	attributes: {},
	default: function() {},
	ready: function() {
		Dialog.attributes.buttons = [
			{
				align: "right",
				image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg",
				default: true,
				onclick: function() {
					var ir = document.getElementById('r');
					var ig = document.getElementById('g');
					var ib = document.getElementById('b');
					var ih = document.getElementById('h');
					var is = document.getElementById('s');
					var il = document.getElementById('l');
					Dialog.attributes.callback([parseInt(ir.value),parseInt(ig.value),parseInt(ib.value)],[parseInt(ih.value),parseInt(is.value),parseInt(il.value)]);
				}
			},
			{
				align: "right",
				image: "<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg",
				onclick: function() {
					Dialog.attributes.callback(false,false);
				}
			}
		];

		if (Dialog.attributes.hasOwnProperty('value') && Dialog.attributes.value !== '') {
			try {
				setColor(Dialog.attributes.value[0],Dialog.attributes.value[1],Dialog.attributes.value[2]);
			} catch(e) {
				console.log('Unable to set color from attributes.value: '+e,e);
			}
		}
		Dialog.attributes.buttons.forEach(function(element,index) {
			let b = document.createElement('button');
			b.className = 'Button';
			b.style.right = ((9*(index+1))+(100*index))+'px';

			b.innerHTML = '';
			if (element.hasOwnProperty('image') && element.image != '') {
				b.innerHTML += '<img class="button-image" src="'+element.image+'">';
			}
			if (element.hasOwnProperty('text') && element.text != '') {
				if (typeof element.image != 'undefined' && element.image != '') {
					b.innerHTML += ' '+element.text;
				} else {
					b.innerHTML += element.text;
				}
			}

			b.onclick = function() {
				Fly.window.onclose();
				element.onclick();
			};
			if (element.default) {
				Dialog.default = b.onclick;
			}
			document.body.appendChild(b);
		});

		document.addEventListener("keydown", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				try {
					Dialog.default();
				} catch(e) {
					console.log('Error in executing default function: '+e,e);
				}
			}
		}, false);

		checkColorRGB();
		if (Dialog.attributes.custom) {
			toggleAdvanced();
		}
	}
};
Fly.window.ready = function() {
	Fly.window.disableContext();
	Dialog.ready();
}
Fly.window.onclose = function() {
	Dialog.opener.Fly.window.focus.self();
	Dialog.opener.Fly.window.bringToFront();
	Fly.window.close();
}

// Picker functions
function toggleAdvanced() {
	var main = document.getElementById('main');
	var advanced = document.getElementById('advanced');
	var advancedButton = document.getElementById('advancedButton');
	if (Fly.window.size.get()[0] == 240) {
		advanced.style.display = 'block';
		advancedButton.innerHTML = 'Hide custom colors <img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-left.svg" style="width:12px;height:12px;vertical-align:middle;margin-top:-2px;pointer-events:none;">';
		Fly.window.size.set(480,324);
	} else {
		advanced.style.display = 'none';
		advancedButton.innerHTML = 'Show custom colors <img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg" style="width:12px;height:12px;vertical-align:middle;margin-top:-2px;pointer-events:none;">';
		Fly.window.size.set(240,324);
	}
}
function setColor(r,g,b) {
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	var lightness = document.getElementById('lightness');
	var arrow = document.getElementById('arrow');
	var crosshair = document.getElementById('crosshair');
	var current = document.getElementById('currentColor');
	
	var hsl = rgbToHsl(r,g,b);
	hsl[0] = parseFloat(hsl[0]);
	hsl[1] = parseFloat(hsl[1]);
	hsl[2] = parseFloat(hsl[2]);
	if (isNaN(hsl[0])) {hsl[0] = 0};
	if (isNaN(hsl[1])) {hsl[1] = 0};
	if (isNaN(hsl[2])) {hsl[2] = 0};
	
	ir.value = Math.round(r);
	ig.value = Math.round(g);
	ib.value = Math.round(b);
	ih.value = Math.round(hsl[0]*100);
	is.value = Math.round(hsl[1]*100);
	il.value = Math.round(hsl[2]*100);
	
	arrow.style.left = (hsl[2]*100)+'%';
	crosshair.style.left = (hsl[0]*222)+'px';
	crosshair.style.top = ((1-hsl[1])*124)+'px';
	
	var hslrgb = hslToRgb(hsl[0],hsl[1],0.5);
	hslrgb[0] = parseFloat(hslrgb[0]);
	hslrgb[1] = parseFloat(hslrgb[1]);
	hslrgb[2] = parseFloat(hslrgb[2]);
	if (isNaN(hslrgb[0])) {hslrgb[0] = 0};
	if (isNaN(hslrgb[1])) {hslrgb[1] = 0};
	if (isNaN(hslrgb[2])) {hslrgb[2] = 0};
	
	lightness.style.backgroundImage = 'linear-gradient(to right, rgb(0,0,0) 0%,rgb('+hslrgb[0]+','+hslrgb[1]+','+hslrgb[2]+') 50%,rgb(255,255,255) 100%)';
	current.style.backgroundColor = 'rgb('+r+','+g+','+b+')';
}
function previewColorHueSat(r,g,b) {
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	if (+ih.value > 100) {
		ih.value = 100;
	}
	if (+is.value > 100) {
		is.value = 100;
	}
	if (+il.value > 100) {
		il.value = 100;
	}
	var color = hslToRgb((ih.value/100),(is.value/100),(il.value/100));
	
	var r = color[0];
	var g = color[1];
	var b = color[2];
	
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	var lightness = document.getElementById('lightness');
	var arrow = document.getElementById('arrow');
	var current = document.getElementById('currentColor');
	var hsl = rgbToHsl(r,g,b);
	ir.value = Math.round(r);
	ig.value = Math.round(g);
	ib.value = Math.round(b);
	ih.value = Math.round(hsl[0]*100);
	is.value = Math.round(hsl[1]*100);
	il.value = Math.round(hsl[2]*100);
	arrow.style.left = (hsl[2]*100)+'%';
	var hslrgb = hslToRgb(hsl[0],hsl[1],0.5);
	lightness.style.backgroundImage = 'linear-gradient(to right, rgb(0,0,0) 0%,rgb('+hslrgb[0]+','+hslrgb[1]+','+hslrgb[2]+') 50%,rgb(255,255,255) 100%)';
	current.style.backgroundColor = 'rgb('+r+','+g+','+b+')';
}
function previewColorLightness() {
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	if (+ih.value > 100) {
		ih.value = 100;
	}
	if (+is.value > 100) {
		is.value = 100;
	}
	if (+il.value > 100) {
		il.value = 100;
	}
	var color = hslToRgb((ih.value/100),(is.value/100),(il.value/100));
	
	var r = color[0];
	var g = color[1];
	var b = color[2];
	
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	var lightness = document.getElementById('lightness');
	var crosshair = document.getElementById('crosshair');
	var current = document.getElementById('currentColor');
	var hsl = rgbToHsl(r,g,b);
	ir.value = Math.round(r);
	ig.value = Math.round(g);
	ib.value = Math.round(b);
	ih.value = Math.round(hsl[0]*100);
	is.value = Math.round(hsl[1]*100);
	il.value = Math.round(hsl[2]*100);
	crosshair.style.left = (hsl[0]*222)+'px';
	crosshair.style.top = ((1-hsl[1])*124)+'px';
	var hslrgb = hslToRgb(hsl[0],hsl[1],0.5);
	lightness.style.backgroundImage = 'linear-gradient(to right, rgb(0,0,0) 0%,rgb('+hslrgb[0]+','+hslrgb[1]+','+hslrgb[2]+') 50%,rgb(255,255,255) 100%)';
	current.style.backgroundColor = 'rgb('+r+','+g+','+b+')';
}
function checkColorRGB() {
	var ir = document.getElementById('r');
	var ig = document.getElementById('g');
	var ib = document.getElementById('b');
	if (+ir.value > 255) {
		ir.value = 255;
	}
	if (+ig.value > 255) {
		ig.value = 255;
	}
	if (+ib.value > 255) {
		ib.value = 255;
	}
	setColor(ir.value,ig.value,ib.value);
}
function checkColorHSL() {
	var ih = document.getElementById('h');
	var is = document.getElementById('s');
	var il = document.getElementById('l');
	if (+ih.value > 100) {
		ih.value = 100;
	}
	if (+is.value > 100) {
		is.value = 100;
	}
	if (+il.value > 100) {
		il.value = 100;
	}
	var color = hslToRgb((ih.value/100),(is.value/100),(il.value/100));
	setColor(color[0],color[1],color[2]);
}
function hslToRgb(h, s, l){
	var r, g, b;

	if(s == 0){
		r = g = b = l; // achromatic
	} else {
		var hue2rgb = function hue2rgb(p, q, t){
			if(t < 0) t += 1;
			if(t > 1) t -= 1;
			if(t < 1/6) return p + (q - p) * 6 * t;
			if(t < 1/2) return q;
			if(t < 2/3) return p + (q - p) * (2/3 - t) * 6;
			return p;
		}

		var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
		var p = 2 * l - q;
		r = hue2rgb(p, q, h + 1/3);
		g = hue2rgb(p, q, h);
		b = hue2rgb(p, q, h - 1/3);
	}

	return [Math.round(r * 255), Math.round(g * 255), Math.round(b * 255)];
}
function rgbToHsl(r, g, b){
	r /= 255, g /= 255, b /= 255;
	var max = Math.max(r, g, b), min = Math.min(r, g, b);
	var h, s, l = (max + min) / 2;

	if(max == min){
		h = s = 0; // achromatic
	} else {
		var d = max - min;
		s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
		switch(max){
			case r: h = (g - b) / d + (g < b ? 6 : 0); break;
			case g: h = (b - r) / d + 2; break;
			case b: h = (r - g) / d + 4; break;
		}
		h /= 6;
	}

	return [h, s, l];
}
</script>
<style>
.Button {
	width: 100px;
	position: absolute;
	bottom: 9px;
}
img.button-image {
	width: 16px;
	height: 16px;
	vertical-align: middle;
}
#main {
	position: absolute;
	top: 0px;
	left: 8px;
	width: 224px;
	bottom: 48px;
}
#advanced {
	display: none;
	position: absolute;
	top: 0px;
	left: 248px;
	width: 224px;
	bottom: 48px;
}
#advancedButton {
	width: 100%;
	position: absolute;
	bottom: 4px;
	left: 0px;
}
.choose {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 8px;
}
.cancel {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 115px;
}
.head {
	display: block;
	font-size: 12px;
	margin-bottom: 4px;
	margin-top: 10px;
}
.preset {
	width: 24px;
	height: 24px;
	border: 1px solid #000;
	margin: 1px;
	display: inline-block;
	cursor: pointer;
}
.preset:active {
	border: 2px solid #000;
	margin: 0px;
}
#background {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
}
#r, #g, #b, #h, #s, #l {
	width: 36px;
}
#huesat {
	position: relative;
	width: 222px;
	height: 124px;
	border: 1px solid #000;
	background-image: linear-gradient(to bottom, rgba(136,136,136,0) 0%, rgba(136,136,136,1) 100%), linear-gradient(to right, rgba(255,0,0,1) 0%, rgba(255,255,0,1) 17%, rgba(0,255,0,1) 33%, rgba(0,255,255,1) 50%, rgba(0,0,255,1) 66%, rgba(255,0,255,1) 83%, rgba(255,0,0,1) 100%);
	background-size: 100% 100%;
	cursor: crosshair;
}
#huesat:active {
	cursor: none;
}
#arrowcontainer {
	position: relative;
	width: 224px;
	height: 42px;
	margin-bottom: -22px;
	cursor: pointer;
	z-index: 2;
}
#lightness {
	position: relative;
	width: 222px;
	height: 20px;
	border: 1px solid #000;
	background-image: linear-gradient(to right, rgb(0,0,0) 0%,rgb(<?php echo $color[0]; ?>,<?php echo $color[1]; ?>,<?php echo $color[2]; ?>) 50%,rgb(255,255,255) 100%);
	background-size: 100% 100%;
	cursor: pointer;
}
#crosshair {
	position: absolute;
	top: 0px;
	left: 0px;
	width: 1px;
	height: 1px;
	overflow: visible;
	pointer-events: none;
}
#crosshair-img {
	position: absolute;
	top: -8px;
	left: -8px;
	width: 16px;
	height: 16px;
}
#arrow {
	position: absolute;
	bottom: 22px;
	left: 0%;
	width: 1px;
	height: 1px;
	cursor: pointer;
	pointer-events: none;
}
#arrow-img {
	position: absolute;
	top: -12px;
	left: -6px;
	width: 12px;
	height: 12px;
}
</style>
</head>
<body>

<div id="background" class="FlyUiContent"></div>
<div id="main">
<span class="head FlyUiText FlyUiNoSelect">Current color:</span>
<div id="currentColor" style="width:100%;height:28px;box-sizing:border-box;border:1px solid #000;background-color:rgb(<?php echo $color[0]; ?>,<?php echo $color[1]; ?>,<?php echo $color[2]; ?>);"></div>

<span class="head FlyUiText FlyUiNoSelect">Preset colors:</span>
<div style="text-align:center;" class="FlyUiNoSelect">
<div class="preset" onclick="setColor(255,128,128)" style="background-color:rgb(255,128,128);"></div>		<div class="preset" onclick="setColor(255,255,128)" style="background-color:rgb(255,255,128);"></div>	<div class="preset" onclick="setColor(128,255,128)" style="background-color:rgb(128,255,128);"></div>	<div class="preset" onclick="setColor(128,255,255)" style="background-color:rgb(128,255,255);"></div>	<div class="preset" onclick="setColor(0,128,255)" style="background-color:rgb(0,128,255);"></div>		<div class="preset" onclick="setColor(255,128,192)" style="background-color:rgb(255,128,192);"></div>	<div class="preset" onclick="setColor(255,128,255)" style="background-color:rgb(255,128,255);"></div>
<div class="preset" onclick="setColor(255,000,000)" style="background-color:rgb(255,0,0);"></div>			<div class="preset" onclick="setColor(255,255,0)" style="background-color:rgb(255,255,0);"></div>		<div class="preset" onclick="setColor(128,255,0)" style="background-color:rgb(128,255,0);"></div>		<div class="preset" onclick="setColor(0,255,255)" style="background-color:rgb(0,255,255);"></div>		<div class="preset" onclick="setColor(0,128,192)" style="background-color:rgb(0,128,192);"></div>		<div class="preset" onclick="setColor(128,128,192)" style="background-color:rgb(128,128,192);"></div>	<div class="preset" onclick="setColor(255,0,255)" style="background-color:rgb(255,0,255);"></div>
<div class="preset" onclick="setColor(128,0,0)" style="background-color:rgb(128,0,0);"></div>				<div class="preset" onclick="setColor(255,128,0)" style="background-color:rgb(255,128,0);"></div>		<div class="preset" onclick="setColor(0,255,0)" style="background-color:rgb(0,255,0);"></div>			<div class="preset" onclick="setColor(0,128,128)" style="background-color:rgb(0,128,128);"></div>		<div class="preset" onclick="setColor(128,128,255)" style="background-color:rgb(128,128,255);"></div>	<div class="preset" onclick="setColor(128,0,64)" style="background-color:rgb(128,0,64);"></div>			<div class="preset" onclick="setColor(128,0,255)" style="background-color:rgb(128,0,255);"></div>
<div class="preset" onclick="setColor(64,0,0)" style="background-color:rgb(64,0,0);"></div>					<div class="preset" onclick="setColor(128,128,0)" style="background-color:rgb(128,128,0);"></div>		<div class="preset" onclick="setColor(0,128,0)" style="background-color:rgb(0,128,0);"></div>			<div class="preset" onclick="setColor(0,0,255)" style="background-color:rgb(0,0,255);"></div>			<div class="preset" onclick="setColor(0,0,160)" style="background-color:rgb(0,0,160);"></div>			<div class="preset" onclick="setColor(128,0,128)" style="background-color:rgb(128,0,128);"></div>		<div class="preset" onclick="setColor(64,0,128)" style="background-color:rgb(64,0,128);"></div>
<div class="preset" onclick="setColor(0,0,0)" style="background-color:rgb(0,0,0);"></div>				<div class="preset" onclick="setColor(128,64,0)" style="background-color:rgb(128,64,0);"></div>			<div class="preset" onclick="setColor(0,64,0)" style="background-color:rgb(0,64,0);"></div>				<div class="preset" onclick="setColor(0,0,128)" style="background-color:rgb(0,0,128);"></div>			<div class="preset" onclick="setColor(128,128,128)" style="background-color:rgb(128,128,128);"></div>	<div class="preset" onclick="setColor(192,192,192)" style="background-color:rgb(192,192,192);"></div>	<div class="preset" onclick="setColor(255,255,255)" style="background-color:rgb(255,255,255);"></div> 
<button id="advancedButton" class="FlyUiNoSelect" onclick="toggleAdvanced()">Show custom colors <img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg" style="width:12px;height:12px;vertical-align:middle;margin-top:-2px;pointer-events:none;"></button>
</div>
</div>

<div id="advanced">
<span class="head FlyUiText FlyUiNoSelect">Custom color:</span>
<div class="FlyUiNoSelect" id="huesat"><div class="FlyUiNoSelect" id="crosshair"><img class="FlyUiNoSelect" src="data:image/svg+xml;base64, PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOmNjPSJodHRwOi8vY3JlYXRpdmVjb21tb25zLm9yZy9ucyMiIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBpZD0ic3ZnOCIgdmVyc2lvbj0iMS4xIiB2aWV3Qm94PSIwIDAgNC4yMzMzMzMyIDQuMjMzMzMzNSIgaGVpZ2h0PSIxNiIgd2lkdGg9IjE2Ij48ZGVmcyBpZD0iZGVmczIiIC8+PG1ldGFkYXRhIGlkPSJtZXRhZGF0YTUiPjxyZGY6UkRGPjxjYzpXb3JrIHJkZjphYm91dD0iIj48ZGM6Zm9ybWF0PmltYWdlL3N2Zyt4bWw8L2RjOmZvcm1hdD48ZGM6dHlwZSByZGY6cmVzb3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIiAvPjxkYzp0aXRsZT48L2RjOnRpdGxlPjwvY2M6V29yaz48L3JkZjpSREY+PC9tZXRhZGF0YT48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLC0yOTIuNzY2NjUpIiBpZD0ibGF5ZXIxIj48cGF0aCBpZD0icGF0aDQ1MjQiIHRyYW5zZm9ybT0ibWF0cml4KDAuMjY0NTgzMzMsMCwwLDAuMjY0NTgzMzMsMCwyOTIuNzY2NjUpIiBkPSJNIDYuOTk4MDQ2OSAwLjAwMzkwNjI1IEwgNi45OTgwNDY5IDcuMDAxOTUzMSBMIDAgNy4wMDE5NTMxIEwgMCA5LjAwMTk1MzEgTCA2Ljk5ODA0NjkgOS4wMDE5NTMxIEwgNi45OTgwNDY5IDE2IEwgOC45OTgwNDY5IDE2IEwgOC45OTgwNDY5IDkuMDAxOTUzMSBMIDE1Ljk5NjA5NCA5LjAwMTk1MzEgTCAxNS45OTYwOTQgNy4wMDE5NTMxIEwgOC45OTgwNDY5IDcuMDAxOTUzMSBMIDguOTk4MDQ2OSAwLjAwMzkwNjI1IEwgNi45OTgwNDY5IDAuMDAzOTA2MjUgeiAiIHN0eWxlPSJjb2xvcjojMDAwMDAwO2ZvbnQtc3R5bGU6bm9ybWFsO2ZvbnQtdmFyaWFudDpub3JtYWw7Zm9udC13ZWlnaHQ6bm9ybWFsO2ZvbnQtc3RyZXRjaDpub3JtYWw7Zm9udC1zaXplOm1lZGl1bTtsaW5lLWhlaWdodDpub3JtYWw7Zm9udC1mYW1pbHk6c2Fucy1zZXJpZjtmb250LXZhcmlhbnQtbGlnYXR1cmVzOm5vcm1hbDtmb250LXZhcmlhbnQtcG9zaXRpb246bm9ybWFsO2ZvbnQtdmFyaWFudC1jYXBzOm5vcm1hbDtmb250LXZhcmlhbnQtbnVtZXJpYzpub3JtYWw7Zm9udC12YXJpYW50LWFsdGVybmF0ZXM6bm9ybWFsO2ZvbnQtZmVhdHVyZS1zZXR0aW5nczpub3JtYWw7dGV4dC1pbmRlbnQ6MDt0ZXh0LWFsaWduOnN0YXJ0O3RleHQtZGVjb3JhdGlvbjpub25lO3RleHQtZGVjb3JhdGlvbi1saW5lOm5vbmU7dGV4dC1kZWNvcmF0aW9uLXN0eWxlOnNvbGlkO3RleHQtZGVjb3JhdGlvbi1jb2xvcjojMDAwMDAwO2xldHRlci1zcGFjaW5nOm5vcm1hbDt3b3JkLXNwYWNpbmc6bm9ybWFsO3RleHQtdHJhbnNmb3JtOm5vbmU7d3JpdGluZy1tb2RlOmxyLXRiO2RpcmVjdGlvbjpsdHI7dGV4dC1vcmllbnRhdGlvbjptaXhlZDtkb21pbmFudC1iYXNlbGluZTphdXRvO2Jhc2VsaW5lLXNoaWZ0OmJhc2VsaW5lO3RleHQtYW5jaG9yOnN0YXJ0O3doaXRlLXNwYWNlOm5vcm1hbDtzaGFwZS1wYWRkaW5nOjA7Y2xpcC1ydWxlOm5vbnplcm87ZGlzcGxheTppbmxpbmU7b3ZlcmZsb3c6dmlzaWJsZTt2aXNpYmlsaXR5OnZpc2libGU7b3BhY2l0eToxO2lzb2xhdGlvbjphdXRvO21peC1ibGVuZC1tb2RlOm5vcm1hbDtjb2xvci1pbnRlcnBvbGF0aW9uOnNSR0I7Y29sb3ItaW50ZXJwb2xhdGlvbi1maWx0ZXJzOmxpbmVhclJHQjtzb2xpZC1jb2xvcjojMDAwMDAwO3NvbGlkLW9wYWNpdHk6MTt2ZWN0b3ItZWZmZWN0Om5vbmU7ZmlsbDojMDAwMDAwO2ZpbGwtb3BhY2l0eToxO2ZpbGwtcnVsZTpub256ZXJvO3N0cm9rZTojZmZmOGY4O3N0cm9rZS13aWR0aDoyLjAwMDAwMDAzO3N0cm9rZS1saW5lY2FwOmJ1dHQ7c3Ryb2tlLWxpbmVqb2luOm1pdGVyO3N0cm9rZS1taXRlcmxpbWl0OjQ7c3Ryb2tlLWRhc2hhcnJheTpub25lO3N0cm9rZS1kYXNob2Zmc2V0OjA7c3Ryb2tlLW9wYWNpdHk6MTtjb2xvci1yZW5kZXJpbmc6YXV0bztpbWFnZS1yZW5kZXJpbmc6YXV0bztzaGFwZS1yZW5kZXJpbmc6YXV0bzt0ZXh0LXJlbmRlcmluZzphdXRvO2VuYWJsZS1iYWNrZ3JvdW5kOmFjY3VtdWxhdGU7cGFpbnQtb3JkZXI6bWFya2VycyBzdHJva2UgZmlsbCIgLz48L2c+PC9zdmc+" id="crosshair-img"></div></div>
<div class="FlyUiNoSelect" id="arrowcontainer"><div class="FlyUiNoSelect" id="arrow"><img class="FlyUiNoSelect" src="data:image/svg+xml;base64, PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtbG5zOmNjPSJodHRwOi8vY3JlYXRpdmVjb21tb25zLm9yZy9ucyMiIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBpZD0ic3ZnOCIgdmVyc2lvbj0iMS4xIiB2aWV3Qm94PSIwIDAgMy4xNzQ5OTk5IDMuMTc1MDAwMSIgaGVpZ2h0PSIxMiIgd2lkdGg9IjEyIj48ZGVmcyBpZD0iZGVmczIiIC8+PG1ldGFkYXRhIGlkPSJtZXRhZGF0YTUiPjxyZGY6UkRGPjxjYzpXb3JrIHJkZjphYm91dD0iIj48ZGM6Zm9ybWF0PmltYWdlL3N2Zyt4bWw8L2RjOmZvcm1hdD48ZGM6dHlwZSByZGY6cmVzb3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIiAvPjxkYzp0aXRsZT48L2RjOnRpdGxlPjwvY2M6V29yaz48L3JkZjpSREY+PC9tZXRhZGF0YT48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSgwLC0yOTMuODI0OTgpIiBpZD0ibGF5ZXIxIj48cGF0aCB0cmFuc2Zvcm09Im1hdHJpeCg0LjM4MTYwNTcsMCwwLDUuMDU5NDQyNCwwLC0xMjA1LjY1NDQpIiBkPSJNIDAuMzYyMzEwMTIsMjk2Ljk5OTk5IDAuMTgxMTU1MDYsMjk2LjY4NjIyIDAsMjk2LjM3MjQ1IGwgMC4zNjIzMTAxMiwwIDAuMzYyMzEwMDksMCAtMC4xODExNTUwNSwwLjMxMzc3IHoiIGlkPSJwYXRoNDUyMiIgc3R5bGU9Im9wYWNpdHk6MTtmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7c3Ryb2tlLXdpZHRoOjAuMjY0NTgzMzI7c3Ryb2tlLWxpbmVjYXA6c3F1YXJlO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDo0O3N0cm9rZS1kYXNoYXJyYXk6bm9uZTtzdHJva2Utb3BhY2l0eToxO3BhaW50LW9yZGVyOmZpbGwgbWFya2VycyBzdHJva2UiIC8+PC9nPjwvc3ZnPg==" id="arrow-img"></div></div>
<div class="FlyUiNoSelect" id="lightness"></div>

<span class="head FlyUiText FlyUiNoSelect"></span>
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">R: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorRGB()" id="r" value="<?php echo $color[0]; ?>">
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">G: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorRGB()" id="g" value="<?php echo $color[1]; ?>">
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">B: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorRGB()" id="b" value="<?php echo $color[2]; ?>">
<div style="margin-top:8px;"></div>
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">H: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorHSL()" id="h" value="<?php echo $color[0]; ?>">
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">S: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorHSL()" id="s" value="<?php echo $color[1]; ?>">
<span style="margin-left:6px;font-size:12px;" class="FlyUiText FlyUiNoSelect">L: </span><input maxlength="3" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onkeyup="checkColorHSL()" id="l" value="<?php echo $color[2]; ?>">
</div>

<script>
var colorPicker = function() {};
colorPicker.container = document.getElementById('advanced');
colorPicker.picker = document.getElementById('huesat');
colorPicker.crosshair = document.getElementById('crosshair');
colorPicker.h = document.getElementById('h');
colorPicker.s = document.getElementById('s');

colorPicker.picker.mouse = {x: 0, y: 0};

colorPicker.picker.addEventListener('mousemove', function(e) {
	colorPicker.picker.mouse.x = e.pageX - (colorPicker.container.offsetLeft + this.offsetLeft);
	colorPicker.picker.mouse.y = e.pageY - (colorPicker.container.offsetTop + this.offsetTop);
}, false);

colorPicker.picker.addEventListener('mousedown', function(e) {
	colorPicker.crosshair.style.top = colorPicker.picker.mouse.y+'px';
	colorPicker.crosshair.style.left = colorPicker.picker.mouse.x+'px';
 	
	if (lightnessSlider.l.value == '0') {
		lightnessSlider.l.value = 1;
	}
	if (lightnessSlider.l.value == '100') {
		lightnessSlider.l.value = 99;
	}
	
	lightnessSlider.slider.style.pointerEvents = 'none';
	
	colorPicker.picker.addEventListener('mousemove', colorPicker.picker.move, false);
	document.body.addEventListener('mouseup', colorPicker.picker.up, false);
	colorPicker.picker.addEventListener('mouseup', function(e) {
		colorPicker.picker.mouse.x = e.pageX - (colorPicker.container.offsetLeft + colorPicker.picker.offsetLeft);
		colorPicker.picker.mouse.y = e.pageY - (colorPicker.container.offsetTop + colorPicker.picker.offsetTop);
		colorPicker.h.value = Math.round((colorPicker.picker.mouse.x / 222)*100)
		colorPicker.s.value = Math.round((1-(colorPicker.picker.mouse.y / 124))*100);
	}, false);
}, false);
colorPicker.picker.move = function() {
	colorPicker.crosshair.style.top = colorPicker.picker.mouse.y+'px';
	colorPicker.crosshair.style.left = colorPicker.picker.mouse.x+'px';
	
	colorPicker.h.value = Math.round((colorPicker.picker.mouse.x / 222)*100)
	colorPicker.s.value = Math.round((1-(colorPicker.picker.mouse.y / 124))*100);
	
	previewColorHueSat();
}
colorPicker.picker.up = function(e) {	
	colorPicker.picker.removeEventListener('mousemove', colorPicker.picker.move, false);
	document.body.removeEventListener('mouseup', colorPicker.picker.up, false);
	previewColorHueSat();
	
	lightnessSlider.slider.style.pointerEvents = 'auto';
}

var lightnessSlider = function() {};
lightnessSlider.container = document.getElementById('advanced');
lightnessSlider.slider = document.getElementById('arrowcontainer');
lightnessSlider.arrow = document.getElementById('arrow');
lightnessSlider.l = document.getElementById('l');

lightnessSlider.slider.mouse = {x: 0};

lightnessSlider.slider.addEventListener('mousemove', function(e) {
	lightnessSlider.slider.mouse.x = e.pageX - (lightnessSlider.container.offsetLeft + this.offsetLeft);
}, false);

lightnessSlider.slider.addEventListener('mousedown', function(e) {
	lightnessSlider.arrow.style.left = lightnessSlider.slider.mouse.x+'px';
	
	colorPicker.picker.style.pointerEvents = 'none';

	lightnessSlider.slider.addEventListener('mousemove', lightnessSlider.slider.move, false);
	document.body.addEventListener('mouseup', lightnessSlider.slider.up, false);
	lightnessSlider.slider.addEventListener('mouseup', function(e) {
		lightnessSlider.slider.mouse.x = e.pageX - (lightnessSlider.container.offsetLeft + lightnessSlider.slider.offsetLeft);
		lightnessSlider.l.value = Math.round((lightnessSlider.slider.mouse.x / 222)*100)
	}, false);
}, false);
lightnessSlider.slider.move = function() {
	lightnessSlider.arrow.style.left = lightnessSlider.slider.mouse.x+'px';
	
	lightnessSlider.l.value = Math.round((lightnessSlider.slider.mouse.x / 222)*100)
	
	previewColorLightness();
}
lightnessSlider.slider.up = function(e) {
	lightnessSlider.slider.removeEventListener('mousemove', lightnessSlider.slider.move, false);
	document.body.removeEventListener('mouseup', lightnessSlider.slider.up, false);
	previewColorLightness();
	
	colorPicker.picker.style.pointerEvents = 'auto';
}
</script>
</body>
</html>