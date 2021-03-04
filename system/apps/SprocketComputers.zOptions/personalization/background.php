<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.File.php';
include 'Fly.Dialog.php';
include 'Fly.CommonStyle.php';

$THEME = FlyLoadThemeFile(FlyUserThemeGenerate(),false)[1];
?>
<link rel="stylesheet" href="../style.css">
<style>
#preview {
	height: 48vh;
	max-width: 90%;
	white-space: nowrap;
	margin: 0 auto;
	cursor: pointer;
}
#border {
	height: 100%;
	width: auto;
	transform: translate(-50%);
}
#background {
	height: 100%;
	width: auto;
	transform: translate(50%);
	box-sizing: border-box;
	padding: 6vh;
	background-size: cover;
	background-position: center;
	background-repeat: no-repeat;
	background-color: <?php echo $THEME['BACKGROUND_COLOR']; ?>;
	background-image: url('<?php echo $THEME['BACKGROUND']; ?>');
}
.color {
	display: inline-block;
    width: 50px;
    height: 50px;
    border-top: 1px solid #fff;
    border-left: 1px solid #fff;
    border-bottom: 1px solid #f2f2f2;
    border-right: 1px solid #f2f2f2;
    border-radius: 6px;
    box-shadow: 0px 0px 4px #000;
	background-color: #808080;
	background-size: 50%;
	background-repeat: no-repeat;
	background-position: center;
	vertical-align: bottom;
	margin: 2px;
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
		var img = document.getElementById('background');
	//	var name = document.getElementById('name');
		img.style.backgroundImage = 'url(\''+file['URL']+'\')';
	//	name.innerHTML = file['name'];
	}
}
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle">Change your background</div>
<p class="FlyCSDescription">Select an image or color to be displayed as the background of your desktop.</p>
<!--<div class="box"><div id="image"></div><p style="margin-bottom:0;" id="name">Preview<?php /*echo basename($THEME['BACKGROUND']);*/ ?></p></div> -->
<div class="FlyCSBox" style="text-align:center;"><div id="preview"><img id="background" src="background-preview.svg"><img id="border" src="background-preview-border.svg"><img id="" src="<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg" class="color" style="margin-right:12px;background-color:<?php echo $THEME['BACKGROUND_COLOR'];?>"></div></div>

<p><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>cursor.svg">Click on the background image to select a new image, or click on the color swatch to pick a new background color.

<p class="FlyCSParagraphTitle">Background Image Display</p>
<p>
	<select name="" id="">
		<option value="">Fill</option>
		<option value="">Contain</option>
		<option value="">Stretch</option>
		<option value="">Center</option>
		<option value="">Tile</option>
		<option value="">None (color only)</option>
	</select>
</p>
<p><a onclick=""><img class="FlyCSInlineIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg">Clear background image selection</a></p>
<p class="FlyCSHint">Clearing your background image selection will revert the background image to the default one provided by the theme (or none, if the theme does not provide one).</p>
<!--
<p class="FlyCSParagraphTitle">Background Image</p>
<p><a onclick="Fly.file.get(GetImage,{path:'<?php echo $_FLY['USER']['PATH']; ?>Media',types:['image/']})"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>browse.svg">Choose a new image</a></p>

<p class="FlyCSParagraphTitle">Background Color</p>
<p style="margin-top:20px;">
	<img id="" src="<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg" class="color" style="margin-right:12px;background-color:<?php echo $THEME['COLOR_OPAQUE'];?>"></img>
	Current color
</p>
<p class="FlyCSDescription"><a onclick=""><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>colors.svg">Pick a new color</a></p>
-->
<div class="buttons"><button><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg">Save</button></div>

</body>
</html>