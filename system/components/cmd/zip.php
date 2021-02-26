<?php
// Fly.SingleFileCommand
// for this command to work, make sure the PECL zip extension is installed
if (is_dir($cmd[0])) {
	$source = str_replace('\\', '/', realpath($cmd[0]));
	$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
	if ($cmd[1] == '') {
		$name = $cmd[0].'/'.basename($cmd[0]).'.zip';
	} else {
		$name = $cmd[1];
	}
	$zip = new ZipArchive();
	if ($zip->open($name, ZipArchive::CREATE) === TRUE) {

		foreach ($files as $file) {
			$file = str_replace('\\', '/', $file);

            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
			}
		}

		$zip->close();

		FlyCommandDisplay('Created ZIP file "'.$name.'".');
	} else {
		FlyCommandDisplay('Unable to create ZIP.');
	}
} else {
	FlyCommandDisplay('Source directory does not exist or is not a directory.');
}
?>