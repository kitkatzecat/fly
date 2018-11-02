<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Personalization',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg',index:'personalization/index.php'},
	{name:'Change background',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg',}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Change your background</div>
<div class="box"><div style="margin:0 auto;width:320px;height:180px;background-image:url('<?php echo $_FLY['URL'].$_FLY_USER->visual->theme->backgroundImage; ?>');background-size:cover;background-position:center center;"></div></div>
<p><a><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>browse.svg">Choose a new image</a></p>
<div class="buttons"><button><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg">Save</button></div>

</body>
</html>