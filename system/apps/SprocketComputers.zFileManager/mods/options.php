<?php
if ($_GET['save'] == 'true') {
	include 'Fly.Core.php';
	if (!$_FLY['IS_USER']) {
		$ok = false;
		goto end;
	}
	
	$params = json_decode($_POST['content'],true);
	
	$ok = true;
	
	foreach ($params as $param) {
		if ($param['type'] == 'registry') {
			$app = $_FLY['APP']['ID'];
			if (isset($param['application'])) {
				$app = $param['application'];
			}
	
			$key = $param['key'];
			$value = $param['value'];
			$app = explode('.',$app,2);
	
			$path = $_FLY['USER']['DATA']."registry/$app[0]/$app[1]";
	
			if (!is_dir($path)) {
				mkdir($path,0777,true);
			}
	
			if (!file_put_contents("$path/$key",$value)) {
				$ok = false;
			}
		}
	}
	
	end:
	if (!$ok) {
		http_response_code(500);
	}
	exit;
}

include 'Fly.CommonStyle.php';
?>
<script>
window.addEventListener('load',function() {
	Display.Title('File Manager Options');
	Display.Icon('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg');
	Display.Path('Options');
	Display.Status('Ready');
});
var OptionsSave = function(params,callback=function(r) {
		if (r) {
			Fly.window.message('Saved');
		} else {
			Fly.window.message('Error saving');
		}
	}) {
	var body = 'content='+encodeURIComponent(JSON.stringify(params));

	var request = new XMLHttpRequest();
	request.open('POST','<?php echo $_FLY['APP']['URL']; ?>mods/options.php?save=true');
	request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	request.setRequestHeader("Cache-Control", "no-cache");
	request.addEventListener('readystatechange',function() {
		if (request.readyState == 4) {
			if (request.status == 200) {
				callback(true);
			} else {
				callback(false);
			}
		}
	});
	request.send(body);
}
function Save() {
	var params = [];
	params.push({
		type: 'registry',
		application: 'SprocketComputers.zFileManager',
		key: 'HideFileExtensions',
		value: (document.getElementById('hideextensions').checked ? 'true' : 'false')
	});
	params.push({
		type: 'registry',
		application: 'SprocketComputers.zFileManager',
		key: 'ShowExtensionALS',
		value: (document.getElementById('showals').checked ? 'true' : 'false')
	});
	params.push({
		type: 'registry',
		application: 'SprocketComputers.zFileManager',
		key: 'ShowImagePreviews',
		value: (document.getElementById('imagepreviews').checked ? 'true' : 'false')
	});
	params.push({
		type: 'registry',
		application: 'SprocketComputers.zFileManager',
		key: 'ShowStatusBar',
		value: (document.getElementById('statusbar').checked ? 'true' : 'false')
	});
	params.push({
		type: 'registry',
		application: 'SprocketComputers.zFileManager',
		key: 'ShowSystemFiles',
		value: (document.getElementById('systemfiles').checked ? 'true' : 'false')
	});
	OptionsSave(params,function(r) {
		if (r) {
			Fly.window.message('Saved');
			window.parent.location.href = 'index.php?p=%3Foptions&Fly_Id='+window.parent.Fly.window.id;
		} else {
			Fly.window.message('Error saving');
			window.location.reload();
		}
	});
}
</script>
</head>
<body>
<div class="FlyCSTitle FlyCSSectionTitle">Options<img class="FlyCSSectionIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg"></div>
<p>These settings will apply to File Manager windows; save, open, rename, and delete dialogs; and your desktop.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('HideFileExtensions','SprocketComputers.zFileManager') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="hideextensions"><label for="hideextensions">Hide file extensions</label></p>
<p class="FlyCSHint">Hides filename extensions.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('ShowExtensionALS','SprocketComputers.zFileManager') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="showals"><label for="showals">Show ALS file extension</label></p>
<p class="FlyCSHint">Shows the extension of alias (.als) files when Hide File Extensions is off.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('ShowImagePreviews','SprocketComputers.zFileManager') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="imagepreviews"><label for="imagepreviews">Show image previews</label></p>
<p class="FlyCSHint">When available, the icon of an image is replaced with a preview of the image.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('ShowStatusBar','SprocketComputers.zFileManager') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="statusbar"><label for="statusbar">Show status bar</label></p>
<p class="FlyCSHint">Shows the status bar at the bottom of File Manager windows.</p>

<p><input class="FlyCSInlineIcon" <?php echo ((FlyRegistryGet('ShowSystemFiles','SprocketComputers.zFileManager') == 'true') ? 'checked ' : ''); ?>type="checkbox" id="systemfiles"><label for="systemfiles">Show system files and folders</label></p>
<p class="FlyCSHint">Makes system files and folders visible in File Explorer window and allows modification of these files (such as rename and delete).<br><span style="color:#f00;">Warning: Modifying these items can harm your computer.</span></p>

<div class="FlyCSSticky FlyCSStickyBottom" style="text-align:right;"><button onclick="Save()"><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button></div>

</body>