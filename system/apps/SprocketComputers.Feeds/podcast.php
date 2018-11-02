<?php
if (!empty($_GET['add'])) {
	goto add;
}
if (!empty($_GET['remove'])) {
	goto remove;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

$json = json_decode(base64_decode($_GET['json']));

echo '<!--';
$rss = simpleXML_load_file(str_replace('https://','http://',$json->feedUrl));
echo '-->';

$genres = '';
foreach ($json->genres as $genre) {
	$genres .= $genre.', ';
}
$genres = str_lreplace(', ','',$genres);

if (is_dir($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['itunes_id'])) {
	$addButton = '<button id="addButton" onclick="window.remove()"><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'mark-minus.svg">Remove from My Podcasts</button>';
} else {
	$addButton = '<button id="addButton" onclick="add()"><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'mark-plus.svg">Add to My Podcasts</button>';
}
?>
<script>
function onload() {
	Fly.window.title.set('<?php echo $_FLY['APP']['NAME']; ?> - '+decodeURIComponent('<?php echo rawurlencode($json->collectionName); ?>')+' (Podcast)');
}
function add() {
	document.getElementById('addButton').disabled = true;
	document.getElementById('frame').src = 'podcast.php?add=<?php echo $_GET['itunes_id']; ?>&json=<?php echo $_GET['json']; ?>';
}
function added() {
	document.getElementById('addButton').innerHTML = '<img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-minus.svg">Remove from My Podcasts';
	document.getElementById('addButton').onclick = remove;
	document.getElementById('addButton').disabled = false;
}
function remove() {
	document.getElementById('addButton').disabled = true;
	document.getElementById('frame').src = 'podcast.php?remove=<?php echo $_GET['itunes_id']; ?>';
}
function removed() {
	document.getElementById('addButton').innerHTML = '<img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg">Add to My Podcasts';
	document.getElementById('addButton').onclick = add;
	document.getElementById('addButton').disabled = false;
}
</script>
<style>
#main {
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	overflow: auto;
	background-color: #fff;
}
#addButton {
	position: absolute;
	bottom: 8px;
	right: 10px;
	min-width: 100px;
}
#podcastImg {
	position: absolute;
	top: 4px;
	left: 4px;
	width: 100px;
	height: 100px;
	border-radius: 10px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	box-shadow: 0px 0px 5px #808080;
}
#podcastInfo {
	padding-left: 112px;
	padding-top: 12px;
	padding-right: 8px;
	min-height: 108px;
	cursor: default;
}
#podcastTitle {
	font-weight: bold;
	font-size: 1.5em;
}
#podcastArtist {
	margin-top: 4px;
	opacity: 0.5;
}
p {
	margin-left: 4px;
}
</style>
</head>
<body onload="onload()" class="FlyUiText FlyUiNoSelect">

<div id="main">

<img id="podcastImg" src="<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg" style="background-image:url('<?php echo $json->artworkUrl100; ?>');">
<div id="podcastInfo" class="FlyUiMenuItem">
<span id="podcastTitle"><?php echo $json->collectionName; ?></span>
<div id="podcastArtist"><?php echo $json->artistName; ?></div>
</div>

<p><?php echo htmlspecialchars($rss->channel->description); ?></p>
<p><?php echo $genres; ?></p>
<p><?php echo $json->trackCount; ?> episodes available</p>
<p>Last released <?php echo date("F j, Y",strtotime($json->releaseDate)); ?></p>
<p style="opacity:0.5;font-size:0.5em;"><?php echo $_GET['itunes_id']; ?></p>

</div>
<?php echo $addButton; ?>

<iframe id="frame" style="display:none;" src=""></iframe>

</body>
</html>
<?php
exit;

add:
include 'Fly.Core.php';
include 'Fly.Functions.php';

if (!is_dir($_FLY['APP']['DATA_PATH'].'/podcasts')) {
	mkdir($_FLY['APP']['DATA_PATH'].'/podcasts');
}
if ($_GET['add'] == '') {
	echo '<script>window.parent.removed()</script>';
	exit;
}
if (!is_dir($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['add'])) {
	mkdir($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['add']);
}

$json = json_decode(base64_decode($_GET['json']));
echo '<!--';
$rss = simpleXML_load_file(str_replace('https://','http://',$json->feedUrl));
echo '-->';

$xml = '
<podcast>
	<name><![CDATA['.$json->collectionName.']]></name>
	<artist><![CDATA['.$json->artistName.']]></artist>
	<img>
		<size30><![CDATA['.$json->artworkUrl30.']]></size30>
		<size60><![CDATA['.$json->artworkUrl60.']]></size60>
		<size100><![CDATA['.$json->artworkUrl100.']]></size100>
	</img>
	<added><![CDATA['.time().']]></added>
	<description><![CDATA['.$rss->channel->description.']]></description>
	<feed><![CDATA['.str_replace('https://','http://',$json->feedUrl).']]></feed>
	<json><![CDATA['.$_GET['json'].']]></json>
</podcast>
';

file_put_contents($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['add'].'/podcast.xml',$xml);
file_put_contents($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['add'].'/img30.jpg',file_get_contents($json->artworkUrl30));
file_put_contents($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['add'].'/img60.jpg',file_get_contents($json->artworkUrl60));
file_put_contents($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['add'].'/img100.jpg',file_get_contents($json->artworkUrl100));

echo '<script>window.parent.added()</script>';

exit;

remove:
include 'Fly.Core.php';
include 'Fly.Functions.php';

if (!is_dir($_FLY['APP']['DATA_PATH'].'/podcasts')) {
	echo '<script>window.parent.removed()</script>';
	exit;
}
if ($_GET['remove'] == '') {
	echo '<script>window.parent.added()</script>';
	exit;
}
if (is_dir($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['remove'])) {
	unlink_dir($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['remove']);
} else {
	echo '<script>window.parent.removed()</script>';
	exit;
}

echo '<script>window.parent.removed()</script>';

exit;
?>