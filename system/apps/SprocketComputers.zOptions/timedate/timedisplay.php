<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Time & Date',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg',index:'timedate/index.php'},
	{name:'Change time display',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg'}
];
function updatePreview() {
	var tfhr = document.getElementById('24hr');
	var seconds = document.getElementById('secs');
	var preview = document.getElementById('preview');
	
	if (tfhr.checked == true && seconds.checked == true) {
		preview.innerHTML = '15:45:15';
	} else if (tfhr.checked == true && seconds.checked == false) {
		preview.innerHTML = '15:45';
	} else if (tfhr.checked == false && seconds.checked == true) {
		preview.innerHTML = '3:45:15 PM';
	} else if (tfhr.checked == false && seconds.checked == false) {
		preview.innerHTML = '3:45 PM';
	}
}
</script>
</head>
<body class="FlyUiText FlyUiNoSelect" onload="updatePreview()">

<div class="title">Change how times are displayed</div>
<p class="description">This only affects how the time is shown on the toolbar, not in any applications. [?]</p>
<div class="box"><h1 id="preview">3:45 PM</h1></div><br>
<p><input class="FlyCSInlineIcon" type="checkbox" onchange="updatePreview()" id="24hr"><label for="24hr">Show 24-hour time</label></p>
<p><input class="FlyCSInlineIcon" type="checkbox" onchange="updatePreview()" id="secs"><label for="secs">Show seconds</label></p>
<div class="buttons"><button><img class="FlyCSButtonIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg"></button></div>

</body>
</html>