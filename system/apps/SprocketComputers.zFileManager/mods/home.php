<?php
include 'Fly.CommonStyle.php';
include 'Fly.FileProcess.php';

if (file_exists($_FLY['APP']['DATA'].'bookmarks.json')) {
	$bookmarks = json_decode(file_get_contents($_FLY['APP']['DATA'].'bookmarks.json'),true);
} else {
	$bookmarks = [];
}
$Files = ["%FLY.USER.PATH%Desktop","%FLY.USER.PATH%Documents","%FLY.USER.PATH%Media","%FLY.PATH%","?applications"];
$Files = array_merge($Files,$bookmarks);
$List = [];
foreach ($Files as $i => $File) {
	if (substr($File,0,1) == '?') {
		if ($File == '?applications') {
			$File = [
				'file' => '?applications',
				'ffile' => '?applications',
				'path' => '?applications',
				'fpath' => '?applications',
				'name' => 'Applications',
				'fname' => 'Applications',
				'icon' => $_FLY['RESOURCE']['URL']['ICONS'].'application.svg',
				'type' => 'Folder',
				'extension' => '',
				'description' => '',
				'isdir' => true,
				'mime' => 'directory'
			];
		}
	} else {
		$File = FlyFileStringProcessor(FlyVarsReplace($File));
	}
	$Files[$i] = $File['fname'];
	array_push($List,$File);
}
?>
<script>
var Folder = {"file":"/mnt/c/Users/kitka/OneDrive/Apache/htdocs/users/1","name":"1","bname":"1","fname":"1","type":"folder","mime":"directory","icon":"http://localhost/system/resources/icons/folder.svg","description":"Folder","URL":"http://localhost/users/1","action":"system.command('run:SprocketComputers.zFileManager,p=/mnt/c/Users/kitka/OneDrive/Apache/htdocs/users/1')","path":"/mnt/c/Users/kitka/OneDrive/Apache/htdocs/users","fpath":"./users","ffile":"./users/1","isdir":true};
var List = <?php echo json_encode($List); ?>;
var Files = <?php echo json_encode($Files); ?>;

window.addEventListener('DOMContentLoaded',function() {

	Display.Title('Home');
	Display.Icon('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg');
	Display.Path('Home');
	Display.Status('Ready');

	document.body.innerHTML = '<style>body {margin-top:60px;} @media (max-height:300px) { body {margin-top:40px} }</style><div style="position:absolute;top:0;left:0;right:0;margin-top:0;" class="FlyCSTitle FlyCSSectionTitle">Home<img class="FlyCSSectionIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg"></div>';
	
	window.View(Folder,List);
});
</script>

<!-- if $View == filmstrip, change to something else (tiles? medium icons?) -->