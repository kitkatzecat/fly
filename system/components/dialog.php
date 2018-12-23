<!DOCTYPE html >
<html>
<head>
<?php
include 'fly.php';
?>
<style>
body {
	margin: 0px;
	padding: 0px;
}
</style>
</head>
<body onload="onload()">
<?php
if ($_GET['type'] == 'message') {
	goto dg_message;
} else if ($_GET['type'] == 'input') {
	goto dg_input;
} else if ($_GET['type'] == 'confirm') {
	goto dg_confirm;
} else if ($_GET['type'] == 'modal') {
	goto dg_modal;
} else if ($_GET['type'] == 'password') {
	goto dg_password;
} else if ($_GET['type'] == 'progress') {
	goto dg_progress;
} else if ($_GET['type'] == 'wait') {
	goto dg_wait;
} else {
	echo '<style>body{background:#fff;margin:4px;word-wrap:break-word;}</style><span class="FlyUiText"><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg"><b>Error:</b> Unknown dialog type<br><xmp>'.$_SERVER['QUERY_STRING'].'</xmp></span>';
	exit;
}
dg_message:
?>
<script>
	try {
		window.top.shell.sound.system('error');
	} catch(err) {}
	function onload() {
		Fly.window.size.set(500,(148+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(Math.max(((window.top.window.innerWidth/2)-(window.top.document.getElementById(Fly.window.id).offsetWidth/2)),0),Math.max((window.top.window.innerHeight/2)-(window.top.document.getElementById(Fly.window.id).offsetHeight/2)-24,0));
		Fly.window.minimize.disable();
		document.getElementById('Content').style.overflow = 'visible';
		document.addEventListener("keydown", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				Fly.window.close();
			}
		}, false);
	}
</script>
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background:#fff;" class="">
	<div style="padding:24px;">
		<img src="<?php echo base64_decode($_GET['icon']); ?>" style="line-height:24px;width:48px;height:48px;">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:80px;font-size:24px;font-weight:bold;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
			<?php echo base64_decode($_GET['message']); ?>
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:80px;word-wrap:break-word;">
			<?php echo base64_decode($_GET['content']); ?>
		</div>
	</div>
</div>
<button onclick="Fly.window.close();" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-o.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<?php
goto ext;
dg_input:
?>
<script>
	function onload() {
		Fly.window.size.set(500,(156+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(Math.max(((window.top.window.innerWidth/2)-(window.top.document.getElementById(Fly.window.id).offsetWidth/2)),0),Math.max((window.top.window.innerHeight/2)-(window.top.document.getElementById(Fly.window.id).offsetHeight/2)-24,0));
		Fly.window.minimize.disable();
		document.getElementById('Content').style.overflow = 'visible';
		document.addEventListener("keydown", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				dialog.returnValue();
			}
		}, false);
		Fly.window.onclose = dialog.cancel;
	}

	var dialog = function() {};
	dialog.returnValue = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		var Input = document.getElementById('TextInput');
		try {
			dialog.ret(Input.value);
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		Fly.window.close();
	}
	dialog.cancel = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		try {
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		Fly.window.close();
	}
	try {
		window.top.shell.sound.system('question');
	} catch(err) {}
	dialog.ret = function() {};
	dialog.enable = function() {
		Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
		document.getElementById('TextInput').disabled = false;
		document.getElementById('ButtonOk').disabled = false;
	}
	Fly.window.ready = function() {
		document.getElementById('TextInput').focus();
	}
</script>
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background:#fff;" class="">
	<div style="padding:24px;">
		<img src="<?php echo base64_decode($_GET['icon']); ?>" style="line-height:24px;width:48px;height:48px;">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:80px;font-size:24px;font-weight:bold;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
			<?php echo base64_decode($_GET['message']); ?>
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:80px;word-wrap:break-word;">
			<?php echo base64_decode($_GET['content']); ?><br>
			<input id="TextInput" disabled onkeypress="if (event.keyCode == 13) {dialog.returnValue();}" type="text" style="height:32px;width:360px;margin-top:8px;" value="<?php echo base64_decode($_GET['value']); ?>">
		</div>
	</div>
</div>
<button disabled onclick="dialog.returnValue();" id="ButtonOk" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button onclick="dialog.cancel();" id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<?php
goto ext;
dg_confirm:
?>
<script>
	function onload() {
		Fly.window.size.set(500,(148+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(Math.max(((window.top.window.innerWidth/2)-(window.top.document.getElementById(Fly.window.id).offsetWidth/2)),0),Math.max((window.top.window.innerHeight/2)-(window.top.document.getElementById(Fly.window.id).offsetHeight/2)-24,0));
		Fly.window.minimize.disable();
		document.getElementById('Content').style.overflow = 'visible';
		document.addEventListener("keydown", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				dialog.returnTrue();
			}
		}, false);
		Fly.window.onclose = dialog.returnFalse;
	}
	try {
		window.top.shell.sound.system('question');
	} catch(err) {}
	var dialog = function() {};
	dialog.returnTrue = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		try {
			dialog.retT();
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		Fly.window.close();
	}
	dialog.returnFalse = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		try {
			dialog.retF();
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		Fly.window.close();
	}
	dialog.retT = function() {};
	dialog.retF = function() {};
	dialog.enable = function() {
		Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
		document.getElementById('ButtonTrue').disabled = false;
		document.getElementById('ButtonFalse').disabled = false;
	}
</script>
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background:#fff;" class="">
	<div style="padding:24px;">
		<img src="<?php echo base64_decode($_GET['icon']); ?>" style="line-height:24px;width:48px;height:48px;">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:80px;font-size:24px;font-weight:bold;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
			<?php echo base64_decode($_GET['message']); ?>
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:80px;word-wrap:break-word;">
			<?php echo base64_decode($_GET['content']); ?>
		</div>
	</div>
</div>
<button id="ButtonTrue" disabled onclick="dialog.returnTrue();" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button id="ButtonFalse" disabled onclick="dialog.returnFalse();" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<?php
goto ext;

dg_modal:
?>
<script>
	function onload() {
		Fly.window.size.set(500,(148+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(Math.max(((window.top.window.innerWidth/2)-(window.top.document.getElementById(Fly.window.id).offsetWidth/2)),0),Math.max((window.top.window.innerHeight/2)-(window.top.document.getElementById(Fly.window.id).offsetHeight/2)-24,0));
		Fly.window.minimize.disable();
		document.getElementById('Content').style.overflow = 'visible';
		document.addEventListener("keydown", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				dialog.returnTrue();
			}
		}, false);
		Fly.window.onclose = dialog.returnFalse;
	}
	try {
		window.top.shell.sound.system('question');
	} catch(err) {}
	var dialog = function() {};
	dialog.return = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		try {
			dialog.retT();
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		Fly.window.close();
	}
	dialog.retT = function() {};
	dialog.enable = function() {
		Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
		document.getElementById('ButtonTrue').disabled = false;
	}
</script>
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background:#fff;" class="">
	<div style="padding:24px;">
		<img src="<?php echo base64_decode($_GET['icon']); ?>" style="line-height:24px;width:48px;height:48px;">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:80px;font-size:24px;font-weight:bold;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
			<?php echo base64_decode($_GET['message']); ?>
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:80px;word-wrap:break-word;">
			<?php echo base64_decode($_GET['content']); ?>
		</div>
	</div>
</div>
<button id="ButtonTrue" disabled onclick="dialog.return();" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<?php
goto ext;

dg_password:

if ($_FLY['IS_USER'] !== false) {
	$userXML = $_FLY_USER;
} else {
	echo '<script>window.top.shell.dialog(\'Not logged in\',\'Please log in to confirm this action with your password.\',\'Not Logged In\');Fly.window.close();</script>';
	exit;
}

?>
<script>
	var cover = document.createElement('div');
	cover.style.position = 'fixed';
	cover.style.top = '0px';
	cover.style.left = '0px';
	cover.style.bottom = '0px';
	cover.style.right = '0px';
	cover.style.backgroundColor = 'rgba(0,0,0,0.2)';
	cover.style.zIndex = '999999998';
	cover.onclick = function() {
		Fly.window.flash();
		try {
			window.top.shell.sound.system('alert');
		} catch(err) {}
	};
	cover.oncontextmenu = function() {return false;};
	
	function onload() {
		Fly.window.minimize.disable();
		window.top.document.body.appendChild(cover);
		window.top.document.getElementById(Fly.window.id).style.zIndex = '999999999';
		setInterval(function() {
			window.top.document.getElementById(Fly.window.id).style.zIndex = '999999999';
		}, 1000);
		
		Fly.window.size.set(500,(156+Math.max(document.getElementById('Content').scrollHeight,56)));
		Fly.window.position.set(Math.max(((window.top.window.innerWidth/2)-(window.top.document.getElementById(Fly.window.id).offsetWidth/2)),0),Math.max((window.top.window.innerHeight/2)-(window.top.document.getElementById(Fly.window.id).offsetHeight/2)-24,0));
		Fly.window.title.hide();
		document.getElementById('Content').style.overflow = 'visible';
		document.addEventListener("keydown", function(e) {
			if (e.keyCode == 13) {
				e.preventDefault();
				checkPassword();
			}
		}, false);
		try {
			window.top.shell.sound.system('password');
		} catch(err) {}
		
		Fly.window.onclose = dialog.cancel;
	}
		
	var dialog = function() {};
	dialog.returnValue = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		var Input = document.getElementById('TextInput');
		try {
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
			dialog.retT();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		cover.parentNode.removeChild(cover);
		Fly.window.close();
	}
	dialog.cancel = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		try {
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
			dialog.retF();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		cover.parentNode.removeChild(cover);
		Fly.window.close();
	}
	dialog.retT = function() {};
	dialog.retF = function() {};
	dialog.enable = function() {
		Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
		document.getElementById('TextInput').disabled = false;
		document.getElementById('ButtonOk').disabled = false;
	}
	function checkPassword() {
		document.getElementById('frame').src = 'login.php?checkpass=true&user=<?php echo $_FLY['USER']['ID']; ?>&pass='+document.getElementById('TextInput').value;
		document.getElementById('TextInput').disabled = true;
		document.getElementById('ButtonOk').disabled = true;
		document.getElementById('content-enterpassword').style.display = 'none';
		document.getElementById('content-passwordincorrect').style.display = 'none';
		document.getElementById('content-checking').style.display = 'inline-block';
	}
	function passwordIncorrect() {
		document.getElementById('TextInput').disabled = false;
		document.getElementById('TextInput').focus();
		document.getElementById('TextInput').select();
		document.getElementById('ButtonOk').disabled = false;
		document.getElementById('content-checking').style.display = 'none';
		document.getElementById('content-passwordincorrect').style.display = 'inline-block';
		window.top.shell.sound.system('alert');
		Fly.window.flash();
	}
	function passwordCorrect() {
		dialog.returnValue();
	}
</script>
<style>
.userImage {
	width: 56px;
	height: 56px;
	line-height: 24px;
	border-top: 1px solid #fff;
	border-left: 1px solid #fff;
	border-bottom: 1px solid #f2f2f2;
	border-right: 1px solid #f2f2f2;
	border-radius: 6px;
	box-shadow: 0px 0px 4px #000;
	background-size: contain;
	background-color: #808080;
	background-repeat: no-repeat;
	background-position: center center;
	background-image: url('<?php echo FlyVarsReplace(FlyStringReplaceConstants((string)$userXML->user->image)); ?>');
}
</style>
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background:#fff;" class="">
	<div style="padding:24px;">
		<img src="<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg" class="userImage">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:96px;font-size:24px;font-weight:bold;word-wrap:break-word;overflow:hidden;">
			<?php echo htmlspecialchars((string)$userXML->user->name); ?>
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:96px;word-wrap:break-word;">
			<span id="content-enterpassword"><?php echo base64_decode($_GET['msg']); ?></span><span style="display:none;" id="content-checking">Checking password...</span><span id="content-passwordincorrect" style="display:none;color:#f00;">Password incorrect, please try again:</span><br>
			<input id="TextInput" disabled onkeydown="if (event.keyCode == 13) {checkPassword();}" type="password" style="height:32px;width:360px;margin-top:8px;" value="">
		</div>
	</div>
</div>
<iframe style="display:none;" id="frame" src=""></iframe>
<button disabled onclick="checkPassword();" id="ButtonOk" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button onclick="dialog.cancel();" id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<?php
goto ext;

dg_progress:
?>
<script>
	function onload() {
		Fly.window.size.set(300,56);
		Fly.window.minimize.disable();
		Fly.window.title.hide();
		size();
		Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
	}
	function size() {
		Fly.window.size.set(300,(Math.max((document.getElementById('Content').offsetHeight+4),56)));
	}
	
	var dialog = {};
	dialog.content = function(content) {
		document.getElementById('Content').innerText = content;
		size();
	}
	dialog.title = function(title) {
		Fly.window.title.set(window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>').window.title+' - '+title);
	}
	dialog.position = function(percent) {
		if (!isNaN(parseInt(percent))) {
			document.getElementById('ProgressBar').style.width = parseInt(percent)+'%';
		}
	}
	dialog.complete = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		try {
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		Fly.window.close();
	}
</script>
<style>
body {
	background: transparent;
	padding: 4px;
}
</style>

<div class="FlyUiTextHighlight FlyUiNoSelect" id="Content" style="">
	<?php echo base64_decode($_GET['content']); ?><br>
	<div style="width:100%;margin-top:6px;" id="Container" class="FlyUiProgressWaitContainer"><div id="ProgressBar" class="FlyUiProgressBar"></div></div>
</div>
<?php
goto ext;

dg_wait:
?>
<script>
	function onload() {
		Fly.window.size.set(300,56);
		Fly.window.minimize.disable();
		Fly.window.title.hide();
		size();
		Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
	}
	function size() {
		Fly.window.size.set(300,(Math.max((document.getElementById('Content').offsetHeight+4),56)));
	}
	
	var dialog = {};
	dialog.content = function(content) {
		document.getElementById('Content').innerText = content;
		size();
	}
	dialog.title = function(title) {
		Fly.window.title.set(window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>').window.title+' - '+title);
	}
	dialog.complete = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		try {
			TargetWindow.window.resetFocus();
			TargetWindow.window.bringToFront();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
		}
		Fly.window.close();
	}
</script>
<style>
body {
	background: transparent;
	padding: 4px;
}
</style>

<div class="FlyUiTextHighlight FlyUiNoSelect" style="">
	<span id="content"><?php echo htmlspecialchars(base64_decode($_GET['content'])); ?></span><br>
	<div style="width:100%;margin-top:6px;" class="FlyUiProgressWaitContainer"><div class="FlyUiWaitBar"></div></div>
</div>
<?php
goto ext;

ext:
?>
</body>
</html>