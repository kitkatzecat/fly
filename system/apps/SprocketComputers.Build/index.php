<?php
if ($_GET['build']=='true'){
	goto build;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

$bdate = str_split($_FLY['VERSION_DATE'],2);
?>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	padding: 4px;
}
</style>
<script>
function onload() {
	Fly.window.size.set(320,(document.getElementById('main').scrollHeight));
}

var progressGeneration;
function startGeneration() {
	var bdate = document.getElementById('bdate');
	var bnumber = document.getElementById('bnumber');
	
	Fly.control.wait('Generating build...','Generating',function(dialog){progressGeneration = dialog;});
	setTimeout(function() {document.getElementById('frame').src = 'index.php?build=true&build_date='+bdate.value+'&build_number='+bnumber.value;},500);
}
function completeGeneration() {
	progressGeneration.complete();
	Fly.control.modal('Generation complete','Build generation has completed. You must log off and back on to see changes.','Generation Complete','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg');
}
</script>
</head>
<body onload="onload()">

<div class="FlyUiText FlyUiContent" id="main">
<img class="FlyUiNoSelect" style="width:100%;height:auto;" src="<?php echo $_FLY['VERSION_IMAGE']['URL']; ?>">
<div class="FlyUiNoSelect" style="text-align:center;margin-top:16px;margin-bottom:32px;">
<b><?php echo $_FLY['VERSION_STRING']; ?></b><br>
Version <?php echo $_FLY['VERSION_MAJOR']; ?> Build  <?php echo $_FLY['VERSION_BUILD']; ?><br>
&copy; <?php echo substr($_FLY['VERSION_DATE'],0,4); ?> Sprocket Computers
</div>
<hr>
<div style="margin-top:2px;margin-bottom:2px;">
<b>Build date: </b><?php echo date("F j, Y g:i:s A",strtotime($bdate[0].$bdate[1].'/'.$bdate[2].'/'.$bdate[3].' '.$bdate[4].':'.$bdate[5].':'.$bdate[6])); ?><br>
<b>Date string: </b><?php echo $_FLY['VERSION_DATE']; ?><br>
<b>Unix time: </b><?php echo strtotime($bdate[0].$bdate[1].'/'.$bdate[2].'/'.$bdate[3].' '.$bdate[4].':'.$bdate[5].':'.$bdate[6]); ?>
</div>
<hr>
<div style="margin-top:2px;margin-bottom:2px;">
<b>Generate Build</b><br>
Date (YYYYMMDDHHMMSS):<br><input id="bdate" type="text" style="width:100%;" value="<?php echo date("YmdHis"); ?>"><br>
Number:<br><input id="bnumber" type="text" style="width:100%;" value="<?php echo ((float)$_FLY['VERSION_BUILD']+1); ?>"><br>
<button onclick="startGeneration()"><img style="width:16px;height:16px;margin-right:6px;vertical-align:middle;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg">Generate</button>
</div>
<iframe id="frame" style="display:none;" src=""></iframe>
</body>
</html>
<?php
exit;

build:
include 'Fly.Core.php';
$build_date = $_GET['build_date'];
$build_number = $_GET['build_number'];
$_FLY_CONFIG->version->build = $build_number;
$_FLY_CONFIG->version->bdate = $build_date;
$_FLY_CONFIG->asXML($_SERVER['DOCUMENT_ROOT'].'/system/config.xml');
echo '<script>window.parent.completeGeneration();</script>';

?>