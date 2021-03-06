<!DOCTYPE html >
<html>
<head>
<?php
include 'Fly.Standard.php';
?>
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
#Button1 {
	width: 100px;
	position: absolute;
	bottom: 9px;
	right: 9px;
}
#Button2 {
	width: 100px;
	position: absolute;
	bottom: 9px;
	right: 117px;
}
</style>
</head>
<body>
<script>
	Fly.window.ready = function() {
		Fly.window.expand.disable();
		Fly.window.minimize.disable();

		var height = (56+Math.max(document.getElementById('Content').scrollHeight,0));

		Fly.window.size.set(500,height);
		Fly.window.position.set(((window.top.window.innerWidth/2)-258),((window.top.window.innerHeight/2)-((height+100)/2)));

		document.addEventListener("keydown", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				document.getElementById('Button1').onclick();
			}
		}, false);

		if (typeof document.getElementById('Button2') != "undefined") {
			Fly.window.onclose = function() {
				try {
					document.getElementById('Button2').onclick;
				} catch(e) {
					Fly.window.close();
				}
				Fly.window.onclose = Fly.window.close();
			}
		}

	}
	var dialog = {
		retT: function() {},
		retF: function() {},
		enable: function() {
			try {
				Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
			} catch(e) {
				console.log(Fly.window.id+': no window to take focus of');
			}
			try {
				document.getElementById('Button1').disabled = false;
			} catch(e) {}
			try {
				document.getElementById('Button2').disabled = false;
			} catch(e) {}
		}
	}
</script>

<div id="Content">

<div class="title"><img class="title-icon" src="<?php echo base64_decode($_GET['icon']); ?>"><?php echo base64_decode($_GET['message']); ?></div>
<div class="description"><p style="margin-top:-12px;"><?php echo base64_decode($_GET['content']); ?></p></div>
</div>

<?php
if ($_GET['type'] == 'message') {
	echo '<button onclick="Fly.window.close();" disabled id="Button1"><img src="'.$_FLY['RESOURCE']['URL']['ICONS'].'mark-o.svg" class="button-image"></button>
	<script>
	try {
		window.top.shell.sound.system("error");
	} catch(e) {}
	dialog.enable();
	</script>';
}
if ($_GET['type'] == 'confirm') {
	echo '
	<script>
	try {
		window.top.shell.sound.system("question");
	} catch(e) {}
	dialog.returnTrue = function() {
		var TargetWindow = window.top.document.getElementById("'.base64_decode($_GET['windowid']).'");
		try {
			dialog.retT();
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog("Control Error - Error","An error has occurred in the control:<pre>"+err+"</pre>","Control Error");
		}
		Fly.window.close();
	}
	dialog.returnFalse = function() {
		var TargetWindow = window.top.document.getElementById("'.base64_decode($_GET['windowid']).'");
		try {
			dialog.retF();
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog("Control Error - Error","An error has occurred in the control:<pre>"+err+"</pre>","Control Error");
		}
		Fly.window.close();
	}
	</script>
	<button onclick="dialog.returnTrue();" disabled id="Button1"><img src="'.$_FLY['RESOURCE']['URL']['ICONS'].'mark-check.svg" class="button-image"></button>
	<button onclick="dialog.returnFalse();" disabled id="Button2"><img src="'.$_FLY['RESOURCE']['URL']['ICONS'].'mark-x.svg" class="button-image"></button>
	';
}
?>
</body>

</html>