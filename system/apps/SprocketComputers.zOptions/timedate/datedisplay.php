<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Time & Date',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg',index:'timedate/index.php'},
	{name:'Change date display',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>calendar.svg'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Change how dates are displayed</div>
<div class="box"><h1 id="preview">January 24, 2018</h1></div><br>

<div class="buttons"><button><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg">Save</button></div>

</body>
</html>