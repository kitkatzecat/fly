<?php
function audio_sound_init($config) {
	if(isset($_SERVER['HTTPS'])) {
	    if (!empty($_SERVER['HTTPS'])) {
	        $protocol = 'https://';
	    } else {
			$protocol = 'http://';
		}
	} else {
		$protocol = 'http://';
	}

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
			source = \''.$protocol.$_SERVER['HTTP_HOST'].'/system/resources/sounds/error.mp3\';
		} else if (type == "login") {
			source = \''.$protocol.$_SERVER['HTTP_HOST'].'/system/resources/sounds/login.mp3\';
		} else if (type == "startup") {
			source = \''.$protocol.$_SERVER['HTTP_HOST'].'/system/resources/sounds/startup.mp3\';
		} else if (type == "question") {
			source = \''.$protocol.$_SERVER['HTTP_HOST'].'/system/resources/sounds/question.mp3\';
		} else if (type == "notification") {
			source = \''.$protocol.$_SERVER['HTTP_HOST'].'/system/resources/sounds/notification.mp3\';
		} else if (type == "alert") {
			source = \''.$protocol.$_SERVER['HTTP_HOST'].'/system/resources/sounds/alert.mp3\';
		} else if (type == "password") {
			source = \''.$protocol.$_SERVER['HTTP_HOST'].'/system/resources/sounds/password.mp3\';
		} else if (type == "click") {
			source = \''.$protocol.$_SERVER['HTTP_HOST'].'/system/resources/sounds/click.mp3\';
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