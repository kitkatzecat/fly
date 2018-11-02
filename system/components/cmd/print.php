<?php

include 'Fly.FileProcessor.php';

if (file_exists($cmd[0]) && !is_dir($cmd[0])) {
	FlyCommandDisplay(file_get_contents($cmd[0]));
	FlyCommandReturn('false');
} else {
	FlyCommandDisplay('The file "'.$cmd[0].'" could not be found.');
	FlyCommandReturn('false');
}

?>