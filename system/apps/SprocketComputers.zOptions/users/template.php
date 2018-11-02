<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<link rel="stylesheet" href="style.css">
<script>
var OptionsTree = [
	{name:'Users',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>person.svg',index:'users/index.php'},
	{name:'Other users',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>person.svg'}
];
</script>
<title>Change background</title>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Change your background</div>
<div class="title">Time display on the toolbar</div>
<p class="description">This only affects how the time is shown on the toolbar, not in any applications.</p>
<div class="box"><h1>3:25 PM</h1></div><br>
<div class="box"><div style="margin:0 auto;width:320px;height:180px;background-image:url('<?php echo $_FLY['URL']; ?>Reddit Images/Silent Valley Northern Ireland.jpg');background-size:cover;background-position:center center;"></div></div>
<p><input class="inline-icon" type="checkbox" id="24hr"><label for="24hr">Show 24-hour time</label></p>
<p class="hint">Shows the clock with 24-hour time</p>
<p><input class="inline-icon" type="checkbox" id="secs"><label for="secs">Show seconds</label></p>
<p><a><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>browse.svg">Choose a new image</a></p>
<div class="buttons"><button><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg">Save</button></div>

</body>
</html>