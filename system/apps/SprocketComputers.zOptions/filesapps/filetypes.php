<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';

$types = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['COMPONENTS'].'types.json'),true);
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Files & Applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',index:'filesapps/index.php'},
	{name:'File types',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle">Manage file types and associations</div>

<?php
foreach ($types as $extension => $type) {
	echo '<p><a onclick="window.location.href=\'\';"><img class="FlyCSInlineIcon" src="'.FlyVarsReplace($type['icon']).'">'.htmlspecialchars($type['description']).'</a></p><p class="FlyCSHint">'.$extension.'</p>';
}
?>

</body>
</html>