<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Command.php';
include 'Fly.Registry.php';
include 'Fly.FileProcessor.php';

$files = [
	'Your Files',
	[
		'fname' => 'Documents',
		'icon' => $_FLY['RESOURCE']['URL']['ICONS'].'type/docsfolder.svg',
		'file' => $_FLY['USER']['PATH'].'Documents/'
	],
	[
		'fname' => 'Media',
		'icon' => $_FLY['RESOURCE']['URL']['ICONS'].'type/mediafolder.svg',
		'file' => $_FLY['USER']['PATH'].'Media/'
	],
	'Other Places',
	[
		'fname' => 'Home',
		'icon' => $_FLY['RESOURCE']['URL']['ICONS'].'home.svg',
		'file' => 'SprocketComputers.zFileManager,p=?home'
	],
	[
		'fname' => 'Computer',
		'icon' => $_FLY['RESOURCE']['URL']['ICONS'].'computer.svg',
		'file' => './'
	],
	[
		'fname' => 'Applications',
		'icon' => $_FLY['RESOURCE']['URL']['ICONS'].'application.svg',
		'file' => 'SprocketComputers.zFileManager,p=?applications'
	],
	'Bookmarks'
];

$bookmarks = json_decode(file_get_contents($_FLY['USER']['DATA'].'apps/SprocketComputers/zFileManager/bookmarks.json'),true);
foreach ($bookmarks as $bookmark) {
	$process = FlyFileStringProcessor(FlyVarsReplace($bookmark));
	if ($process) {
		array_push($files,$process);
	}
}

//$showID = FlyRegistryGet('JumpShowApplicationID','SprocketComputers.Utilities');
?>
<link rel="stylesheet" href="items.css">
</head>
<body>

<?php
foreach ($files as $file) {
	if (is_string($file)) {
		echo '<div class="letter FlyUiNoSelect">'.$file.'</div>';
	} else {
		echo '<div onclick="window.parent.run(\''.$file['file'].'\')" class="FlyUiMenuItem FlyUiNoSelect"><img class="icon" src="'.$file['icon'].'">'.$file['fname'].'</div>';
	}
}
?>

</body>
</html>