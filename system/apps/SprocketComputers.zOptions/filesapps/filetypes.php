<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
include 'Fly.FileProcessor.php';

$types = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['COMPONENTS'].'types.json'),true);
?>
<link rel="stylesheet" href="../style.css">
<style>
table {
	padding-left: 6%;
	padding-right: 6%;
	width: 100%;
	table-layout: fixed;
	width: 90%;
	min-width: 600px;
	margin-top: -16px;
}
td,tr {
	padding: 0;
}
td {
	min-width: 30%;
	padding: inherit;
}
th {
	padding-top: 16px;
	padding-bottom: 6px;
	position: sticky;
	top: 0;
	font-size: 0.8em;
	color: rgba(0,0,0,0.8);
	font-weight: normal;
	text-align: left;
	background-color: #fff;
}
</style>
<script>
var OptionsTree = [
	{name:'Files & Applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg',index:'filesapps/index.php'},
	{name:'File types',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="FlyCSTitle">Manage file types and associations</div>
<p class="FlyCSDescription">File type associations are used by Fly to determine which application to use when opening files.</p>

<table cellspacing="0">
	<tr><th style="width:43%">Description</th><th style="width:14%">Extension</th><th style="width:43%">Opens With</th></tr>
<?php
foreach ($types as $extension => $type) {
	$app = '';
	if (isset($type['app'])) {
		$app = $type['app'];
	}
	if (isset($type['action'])) {
		$app = explode(',',$type['action'])[0];
	}
	if ($app == '') {
		$app = '';
	} else {
		$app = FlyFileStringProcessor($app);
		$app = '<img class="FlyCSInlineIcon" src="'.$app['icon'].'">'.$app['name'];
	}
	echo '<tr class="FlyUiMenuItem FlyUiNoSelect"  onclick="window.location.href=\'\';"><td><img class="FlyCSInlineIcon" src="'.FlyVarsReplace($type['icon']).'">'.htmlspecialchars($type['description']).'</td><td>'.$extension.'</td><td>'.$app.'</td></tr>';
}
?>
</table>

<div style="" class="FlyCSSticky FlyCSStickyBottom">
	<p style="padding-left:0;"><a><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg" class="FlyCSInlineIcon">Create a new file type</a></p>
</div>

</body>
</html>