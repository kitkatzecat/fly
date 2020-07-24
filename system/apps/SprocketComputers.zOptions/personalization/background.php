<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.File.php';
include 'Fly.Dialog.php';

$THEME = FlyLoadThemeFile(FlyUserThemeGenerate(),false)[1];
?>
<link rel="stylesheet" href="../style.css">
<style>
#image {
	margin: 0 auto;
	height: 48vh;
	max-width: 90%;
	background-image: url('<?php echo $THEME['BACKGROUND']; ?>');
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
}
</style>
<script>
var OptionsTree = [
	{name:'Personalization',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg',index:'personalization/index.php'},
	{name:'Change background',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg',}
];
function GetImage(file) {
	if (file['mime'].indexOf('image/') == -1) {
		Fly.dialog.message({message:'Unsupported file type',content:'The file chosen is not a supported file type to be used as a background image.',title:'Unsupported File Type',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg'})
	} else {
		var img = document.getElementById('image');
		var name = document.getElementById('name');
		img.style.backgroundImage = 'url(\''+file['URL']+'\')';
		name.innerHTML = file['name'];
	}
}
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Change your background</div>
<div class="box"><div id="image"></div><p style="margin-bottom:0;" id="name"><?php echo basename($THEME['BACKGROUND']); ?></p></div>
<p><a onclick="Fly.file.get(GetImage,{path:'<?php echo $_FLY['USER']['PATH']; ?>Media',types:['image/']})"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>browse.svg">Choose a new image</a></p>
<div class="buttons"><button><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg">Save</button></div>

</body>
</html>