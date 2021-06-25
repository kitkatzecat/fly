<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.REGISTRY')) {
	include 'Fly.Registry.php';
}

function audio_sound_init($config) {
	global $_FLY;
	$FLY = FlyCoreVars($_FLY['PATH']);
	/*
	if(isset($_SERVER['HTTPS'])) {
	    if (!empty($_SERVER['HTTPS'])) {
	        $protocol = 'https://';
	    } else {
			$protocol = 'http://';
		}
	} else {
		$protocol = 'http://';
	}
	*/

	if ($_FLY['IS_USER']) {
		$sounds_user = json_decode(FlyUserRegistryGet('SystemSounds','root.public'),true);
	} else {
		$sounds_user = [];
	}
	if ($sounds_user == false) {
		$sounds_user = [];
	}
	$sounds_global = json_decode(FlyGlobalRegistryGet('SystemSounds','root.public'),true);
	$sounds = array_merge($sounds_global,$sounds_user);

	echo '
	<script>
	shell.sound = function(type = \'error\') {
		shell.sound.system(type);
	}

	shell.sound.init = function() {
	}

	shell.sound.system = function(type=\'error\') {
		var source;
		if (type == "error") {
			source = \''.FlyVarsReplace($sounds['error'],true,$FLY).'\';
		} else if (type == "login") {
			source = \''.FlyVarsReplace($sounds['login'],true,$FLY).'\';
		} else if (type == "startup") {
			source = \''.FlyVarsReplace($sounds['startup'],true,$FLY).'\';
		} else if (type == "question") {
			source = \''.FlyVarsReplace($sounds['question'],true,$FLY).'\';
		} else if (type == "notification") {
			source = \''.FlyVarsReplace($sounds['notification'],true,$FLY).'\';
		} else if (type == "alert") {
			source = \''.FlyVarsReplace($sounds['alert'],true,$FLY).'\';
		} else if (type == "password") {
			source = \''.FlyVarsReplace($sounds['password'],true,$FLY).'\';
		} else if (type == "click") {
			source = \''.FlyVarsReplace($sounds['click'],true,$FLY).'\';
		} else {
			source = type;
		}
		var audio = document.createElement("audio");
		audio.style.display = "none";
		audio.innerHTML = \'<source src="\'+source+\'"></source>\';
		audio.onended = function() {
			this.parentNode.removeChild(this);
		};
		document.body.appendChild(audio);

		audio.play();
	}
	</script>
	';
}
?>