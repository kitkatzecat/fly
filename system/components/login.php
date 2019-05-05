<?php
if ($_GET['authwindow'] == 'true') {
	goto authwindow;
}
if ($_GET['helpwindow'] == 'true') {
	goto helpwindow;
}
if ($_GET['checkpass'] == 'true') {
	goto checkpass;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Constants.php';
include 'Fly.Core.php';
include 'Fly.Window.php';
include 'Fly.Controls.php';
include 'Fly.Theme.php';

if ((string)$_FLY_CONFIG->firstRun == 'true') {
	echo '<script>window.location.href="oobe/?Fly_Id='.$_GET['Fly_Id'].'";</script>';
	exit;
}

FlyLoadTheme('text controls');

function getUsers() {
	global $_FLY;
	
	$path = $_FLY['RESOURCE']['PATH']['USERS'];
	$return = array();

	$ignore = array( 'cgi-bin', '.', '..' ); 

	$dh = @opendir( $path ); 

	while( false !== ( $file = readdir( $dh ) ) ){ 

	    if( !in_array( $file, $ignore ) ){ 

	        if( is_dir( "$path/$file" ) ){ 
				if (file_exists("$path/$file/data/user.xml")) {
					$return[$file] = simpleXML_load_file("$path/$file/data/user.xml");
				}
			}

	    } 

	} 
	closedir( $dh ); 
	asort($return);
	return $return;
}

function drawUsers($array) {
	global $_FLY;
	
	$return = '';
	foreach($array as $id => $userXML) {
		if ((string)$userXML->user->password == '') {
			$onclick = 'Fly.window.close();window.top.login(\''.$id.'\',\'\')';
		} else {
			$onclick = 'login(\''.$id.'\')';
		}
		$return .= '<div class="FlyUiMenuItem userCard" onclick="'.$onclick.'"><img src="'.$_FLY['RESOURCE']['URL']['OS'].'userimagelight.svg" style="background-image:url(\''.FlyVarsReplace(FlyStringReplaceConstants((string)$userXML->user->image)).'\'),linear-gradient(to bottom, rgb(140,152,165) 0%,rgb(175,192,206) 100%);" class="userImage"><br>'.htmlspecialchars($userXML->user->name).'</div>';
	}
	return $return;
}

?>
<script>
var interval;
function onload() {
	Fly.window.buttons.hide();
	interval = setInterval(position,1000);

	document.oncontextmenu = function(e) {
		e.preventDefault();
		e.stopPropagation();
		return false;
	}
}
function position() {
	Fly.window.position.set(((window.top.window.innerWidth/2)-200), ((window.top.window.innerHeight/2)-175));
}
function login(id) {
	var init = function() {
		try {
			frame.window.content.contentWindow.dialog.ret = function(user,pass) {
				window.top.login(user,pass);
				Fly.window.close();
			};
			frame.window.hideTitlebar();
			frame.window.content.contentWindow.dialog.enable();
		}
		catch(err) {
			window.top.shell.dialog('Control Error - Error','An error has occurred in the control:<pre>'+err+'</pre>','Control Error');
			frame.window.close();
		}
	}
	var frame = window.top.task.create('public',{title:'Welcome to Fly',x:((window.top.window.innerWidth/2)-258),y:((window.top.window.innerHeight/2)-100),width:500,height:220,location:'/system/components/login.php?authwindow=true&windowid='+encodeURIComponent(window.btoa(Fly.window.id))+'&user='+id,icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg',load:init});
}
function helpWindow() {
	window.top.task.create('public',{title:'Fly Help',x:((window.top.window.innerWidth/2)-150),y:((window.top.window.innerHeight/2)-200),width:300,height:400,location:'/system/components/login.php?helpwindow=true&windowid='+encodeURIComponent(window.btoa(Fly.window.id)),icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg'});
}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 6px;
	background-color: #fff;
	overflow: auto;
}
#powerButton {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
#helpButton {
	position: absolute;
	width: 28px;
	bottom: 8px;
	left: 10px;
	box-sizing: border-box;
	padding-left: 6px;
}
.userImage {
	vertical-align: middle;
	width: 56px;
	height: 56px;
	margin-bottom: 12px;
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
}
.userCard {
	padding: 8px;
	display: inline-block;
	text-align: center;
	width: 88px;
	word-wrap: break-word;
	vertical-align: top;
}
.msg {
	margin-top: 28px;
	margin-bottom: 12px;
	text-align: center;
	font-size: 1.2em;
}
</style>
</head>
<body onload="onload()" class="FlyUiNoSelect">

<div id="main" class="FlyUiText">

<center>
<div class="msg"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg" style="width:32px;height:32px;"><br>
Welcome</div><p style="margin-bottom:24px;">Choose your user account:</p>

<?php
echo drawUsers(getUsers());
?>
</center>
</div>
<button id="helpButton" onclick="helpWindow()"><img style="width:16px;height:16px;vertical-align:middle;pointer-events:none;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg"></button>
<button id="powerButton" onclick="window.top.powerOptions()"><img style="width:16px;height:16px;vertical-align:middle;pointer-events:none;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>power.svg"></button>

</body>
</html>
<?php
exit;

authwindow:
?>
<!DOCTYPE html>
<html>
<?php
include 'fly.php';

if (file_exists($_FLY['RESOURCE']['PATH']['USERS'].$_GET['user'].'/data/user.xml')) {
	$userXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['USERS'].$_GET['user'].'/data/user.xml');
} else {
	echo '<script>Fly.window.close();</script>';
	exit;
}

?>
<style>
body {
	margin: 0px;
	padding: 0px;
}
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
</head>
<body onload="onload()" class="FlyUiNoSelect">

<script>
	Fly.window.ready = function() {
		document.getElementById('TextInput').focus();
	}

	function onload() {
		Fly.window.size.set(500,(156+Math.max(document.getElementById('Content').scrollHeight,56)));
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
		document.body.addEventListener('click',function() {
			document.getElementById('TextInput').focus();
		});

		document.oncontextmenu = function(e) {
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
	}

	var dialog = function() {};
	dialog.returnValue = function() {
		var TargetWindow = window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>');
		var Input = document.getElementById('TextInput');
		try {
			dialog.ret('<?php echo $_GET['user']; ?>',Input.value);
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
	dialog.ret = function() {};
	dialog.enable = function() {
		Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
		document.getElementById('TextInput').disabled = false;
		document.getElementById('ButtonOk').disabled = false;
	}
	function checkPassword() {
		document.getElementById('frame').src = 'login.php?checkpass=true&user=<?php echo $_GET['user']; ?>&pass='+document.getElementById('TextInput').value;
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
<div style="position:absolute;top:0px;left:0px;right:0px;bottom:50px;background-color:#FFFFFF;">
	<div style="padding:24px;">
		<img src="<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg" class="userImage">
		<div class="FlyUiText FlyUiNoSelect" style="width:400px;height:32px;position:absolute;display:block;top:36px;left:96px;font-size:24px;font-weight:bold;word-wrap:break-word;overflow:hidden;">
			<?php echo htmlspecialchars((string)$userXML->user->name); ?>
		</div>
		<div class="FlyUiText FlyUiNoSelect" id="Content" style="height:64px;overflow:auto;width:400px;position:absolute;top:72px;left:96px;word-wrap:break-word;">
			<span id="content-enterpassword">Enter your password:</span><span style="display:none;" id="content-checking">Checking password...</span><span id="content-passwordincorrect" style="display:none;color:#f00;">Password incorrect, please try again:</span><br>
			<input id="TextInput" disabled onkeydown="if (event.keyCode == 13) {checkPassword();}" type="password" style="height:32px;width:360px;margin-top:8px;" value="">
		</div>
	</div>
</div>
<iframe style="display:none;" id="frame" src=""></iframe>
<button disabled onclick="checkPassword();" id="ButtonOk" style="width:100px;position:absolute;bottom:9px;right:9px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>
<button onclick="dialog.cancel();" id="ButtonCancel" style="width:100px;position:absolute;bottom:9px;right:117px;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

</body>
</html>
<?php
exit;

helpwindow:
?>
<!DOCTYPE html>
<html>
<?php
include 'fly.php';
?>
<style>
body {
	margin: 0px;
	padding: 0px;
}
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	padding: 5px;
	background-color: #fff;
	overflow: auto;
}
#okButton {
	position: absolute;
	min-width: 100px;
	bottom: 8px;
	right: 10px;
}
#aboutFly {
	position: absolute;
	bottom: 12px;
	left: 10px;
}
.msg {
	margin-top: 8px;
	margin-bottom: 4px;
	text-align: center;
	font-size: 1.2em;
}
p {
	font-size: 0.8em;
}
</style>
<script>
function onload() {
	Fly.window.focus.take('<?php echo base64_decode($_GET['windowid']); ?>');
	Fly.window.onclose = function() {
		Fly.window.focus.give('<?php echo base64_decode($_GET['windowid']); ?>');
		window.top.document.getElementById('<?php echo base64_decode($_GET['windowid']); ?>').window.bringToFront();
		Fly.window.close();
	}
	window.top.shell.sound.system('error');
}
function resetPassword() {
	alert('too bad');
}
function aboutFly() {
	window.top.task.create('SprocketComputers.Utilities.AboutFly', {title:'About Fly', name:'About Fly', x:'auto', y:'auto', width:400, height:350, location:'<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.Utilities/aboutfly.php?', icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg'});
}
</script>
</head>
<body onload="onload()">

<div id="main" class="FlyUiText">
<img style="width:100%;height:auto;" src="<?php echo $_FLY['VERSION_IMAGE']['URL']; ?>">
<div class="msg">Welcome to <?php echo $_FLY['VERSION_STRING']; ?></div>
<p>To sign in, click your user account image and enter your password in the prompt given.
</p><p>If you are not a user on this computer, you may ask an existing user to create a user account
for you.</p><p>If you would like to turn off or restart your computer, click the 
<img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>power.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;">
button in the Welcome to Fly window.</p><p>If you have forgotten your password, <a onclick="resetPassword()">click here</a> to reset it.</p>
</div>
<div id="aboutFly" class="FlyUiTextHover" onclick="aboutFly()">About Fly</div>
<button id="okButton" onclick="Fly.window.onclose()" ondblclick="return false;"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-o.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:0px;pointer-events:none;"></button>

</body>
</html>
<?php
exit;

checkpass:
include 'Fly.Core.php';
FlyCoreVars_System();

if (file_exists($_FLY['RESOURCE']['PATH']['USERS'].$_GET['user'])) {
	$xml = simpleXML_load_file($_FLY['RESOURCE']['PATH']['USERS'].$_GET['user'].'/data/user.xml');
	if ($_GET['pass'] == base64_decode((string)$xml->user->password)) {
		echo '<script>window.parent.passwordCorrect();</script>';
	} else {
		echo '<script>window.parent.passwordIncorrect();</script>';
	}
} else {
	echo '<script>window.parent.passwordIncorrect();</script>';
}
?>