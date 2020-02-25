<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
?>
<script>
	//JSON.parse(atob('<?php echo $_GET['a']; ?>'))
var Dialog = {
	opener: {},
	attributes: {},
	default: function() {},
	ready: function() {
		document.querySelector('img#icon').src = Dialog.attributes.icon;
		document.querySelector('span#title').innerHTML = Dialog.attributes.message;
		document.querySelector('p#content').innerHTML = Dialog.attributes.content;

		Dialog.attributes.buttons.forEach(function(element,index) {
			let b = document.createElement('button');
			b.className = 'Button';
			b.style.right = ((9*(index+1))+(100*index))+'px';

			b.innerHTML = '';
			if (typeof element.image != 'undefined' && element.image != '') {
				b.innerHTML += '<img class="button-image" src="'+element.image+'">';
			}
			if (typeof element.text != 'undefined' && element.text != '') {
				if (typeof element.image != 'undefined' && element.image != '') {
					b.innerHTML += ' '+element.text;
				} else {
					b.innerHTML += element.text;
				}
			}

			b.onclick = function() {
				var v = document.getElementById('input').value;
				Fly.window.onclose();
				element.onclick(v);
			};
			if (element.default) {
				Dialog.default = b.onclick;
			}
			document.body.appendChild(b);
		});

		if (!!Dialog.attributes.input) {
			document.querySelector('div.input').style.display = 'block';
		}

		var height = (56+Math.max(document.getElementById('Content').scrollHeight,0));
		Fly.window.size.set(500,height);

		Fly.window.position.set(Fly.window.position.get()[0],(Dialog.opener.Fly.window.position.get()[1]+((Dialog.opener.Fly.window.size.get()[1]/2)-(Fly.window.size.get()[1]/2))))

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
		document.getElementById('input').addEventListener("keydown", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
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
</script>
<style>
body {
	margin: 0px;
	padding: 0px;
}
h1,h2,h3,h4,h5,h6,p {
	padding-left: 9%;
	padding-right: 9%;
}
.title {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 18px;
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
	overflow-y: auto;
}
p#content {
	margin-top: -12px;
}
input#input {
	width: 90%;
}
.input {
	display: none;
	padding-left: 9%;
	padding-right: 9%;
	margin-top: -6px;
	padding-bottom: 12px;
}
.Button {
	width: 100px;
	position: absolute;
	bottom: 9px;
}
</style>
</head>
<body>

<div id="Content">

<div class="title"><img id="icon" class="title-icon" src=""><span id="title"></span></div>
<div class="description"><p id="content"></p></div>
<div class="input"><input type="text" id="input"></div>

</div>


</body>
</html>