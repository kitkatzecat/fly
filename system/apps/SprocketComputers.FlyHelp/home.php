<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.CommonStyle.php';
?>

<script>
var OptionsTree = [
	{name:'Home',icon:`${Fly.core['RESOURCE']['URL']['ICONS']}home.svg`,index:'home.php'}
];
</script>

</head>
<body class="FlyUiText FlyUiNoSelect">
<div class="FlyCSTitle FlyCSSectionTitle">Welcome to Fly Help<img class="FlyCSSectionIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg"></div>

<p>Fly Help is your one-stop place for any questions you might have about Fly.</p>
<p>Choose a category on the sidebar, or find an article below to get started.</p>

<div class="FlyCSTitle">Common questions</div>

<div class="FlyCSTitle">Recently viewed</div>
<p class="FlyCSDescription">You haven't viewed any articles recently.</p>

</body>
</html>