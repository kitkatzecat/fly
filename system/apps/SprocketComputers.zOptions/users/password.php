<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Constants.php';
?>
<link rel="stylesheet" href="../style.css">
<?php
if (!file_exists($_FLY['RESOURCE']['PATH']['USERS'].$_GET['user'].'/data/user.xml')) {
	$user = $_FLY['USER']['ID'];
} else {
	$user = $_GET['user'];
}

$userXML = simpleXML_load_file($_FLY['RESOURCE']['PATH']['USERS'].$user.'/data/user.xml');

if ($user == $_FLY['USER']['ID']) {
	echo '
<script>
var OptionsTree = [
	{name:\'Users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/index.php\'},
	{name:\'Change password\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'lock.svg\'}
];
</script>
	';
	$p = 'your';
} else {
	echo '
<script>
var OptionsTree = [
	{name:\'Users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/index.php\'},
	{name:\'Other users\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'person.svg\',index:\'users/others.php\'},
	{name:\'Manage user\',index:\'users/index.php?user='.$user.'\'},
	{name:\'Change password\',icon:\''.$_FLY['RESOURCE']['URL']['ICONS'].'lock.svg\'}
];
</script>
	';
	$p = htmlspecialchars($userXML->user->name).'\'s';
}
?>
<script>
function checkMatch(error=true) {
	var pass = document.getElementById('pass');
	var passconfirm = document.getElementById('passconfirm');
	var match = document.getElementById('match');
	
	if (pass.value != '' && passconfirm.value != '') {
		if (pass.value == passconfirm.value) {
			match.style.display = 'none';
		} else {
			if (error == true) {
				match.style.display = 'block';
			}
		}
	} else {
		match.style.display = 'none';
	}
}
function change() {
	document.getElementById('match').style.display = 'none';
	checkMatch(false);
}
function authSubmit() {
	Fly.control.password('Enter your current password to allow these changes:',checkSubmit,function() {Fly.control.modal('Password required','You must enter your current password to allow these changes.','Options - Password Required','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg');});
}
function checkSubmit() {
	var pass = document.getElementById('pass');
	var passconfirm = document.getElementById('passconfirm');
	if (pass.value == passconfirm.value) {
		if (pass.value == '' && passconfirm.value == '') {
			Fly.control.confirm('Use no password?','Are you sure you want to use no password for <?php echo str_replace('\'','\\\'',$p); ?> account? This is less secure and means anyone can access <?php echo str_replace('\'','\\\'',$p); ?> files.','Options - No Password','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg',submit);
		} else {
			submit();
		}
	} else {
		checkMatch();
	}
}
function submit() {
	alert('succ ess');
}
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Change <?php echo $p; ?> password</div>
<p class="description">Enter a new password in the boxes below. To use no password, leave the boxes blank.</p>

<p id="match" style="display:none;color:#f00;">It doesn't look like those match.</p>

<p class="shead">New Password</p>
<p><input onblur="checkMatch()" onkeypress="change()" id="pass" type="password"></p>

<p class="shead">Confirm New Password</p>
<p><input onblur="checkMatch()" onkeypress="change()" id="passconfirm" type="password"></p>

<p class="shead">Password Hint (optional)</p>
<p><input id="hint" type="text"></p>

<div class="buttons"><button onclick="authSubmit()" id="save"><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>lock.svg">Save</button></div>

</body>
</html>