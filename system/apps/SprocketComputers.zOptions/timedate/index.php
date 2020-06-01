<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Time & Date',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg',index:'timedate/index.php'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title category">Time & Date<img class="category-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg"></div>
<p><a onclick="window.location.href='timezone.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>earth.svg">Change your time zone</a></p>
<p><a onclick="window.location.href='timedisplay.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg">Change how times are displayed</a></p>
<p><a onclick="window.location.href='datedisplay.php';"><img class="inline-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>calendar.svg">Change how dates are displayed</a></p>

</body>
</html>