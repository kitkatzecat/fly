<?php
if (!empty($_GET['details'])) {
	goto details;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.FileProcessor.php';

if (!is_dir($_FLY['APP']['DATA_PATH'].'podcasts/')) {
	mkdir($_FLY['APP']['DATA_PATH'].'podcasts/');
}

function getPodcasts() {
	global $_FLY;
	
	$path = $_FLY['APP']['DATA_PATH'].'podcasts/';
	$return = [];

	$ignore = ['.', '..']; 

	$dh = @opendir( $path ); 

	while( false !== ( $file = readdir( $dh ) ) ){ 

	    if( !in_array( $file, $ignore ) ){ 

	        if( is_dir( "$path/$file" ) ){
				$return[$file] = "$path/$file";
			}

	    } 

	} 
	closedir( $dh ); 
	asort($return);
	return $return;
}
?>
<script>
function DetailsWindow(id) {
		var title = '<?php echo $_FLY['APP']['NAME']; ?> - Podcast Details';
		var pos = Fly.window.position.get();
		var size = Fly.window.size.get();
		var x = parseInt(pos[0])+parseInt((size[0]/2)-(400/2));
		var y = parseInt(pos[1])+parseInt((size[1]/2)-(300/2));
		var url = '<?php echo $_FLY['WORKING_URL'];?>mypodcasts.php?details='+id;
		return window.top.task.create('<?php echo $_FLY['APP']['ID']; ?>',{name:title,title:title,width:400,height:300,icon:'<?php echo $_FLY['APP']['ICON_URL']; ?>',x:x,y:y,location:url});
}
</script>
<style>
body {
	margin: 4px;
}
.podcastImg {
	width: 60px;
	height: 60px;
	float: left;
	margin-right: 6px;
	margin-bottom: 4px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	border-radius: 7px;
	box-shadow: 0px 0px 4px #808080;
}
.podcastArtist {
	opacity: 0.7;
	font-size: 0.8em;
}
.podcastInfo {
	display: inline-block;
	margin-top: 4px;
	max-width: calc(100% - 70px);
}
.podcast {
	margin-bottom: 4px;
	min-height: 70px !important;
}
</style>
</head>
<body class="FlyUiNoSelect">

<?php
foreach (getPodcasts() as $podcast) {
	$xml = simpleXML_load_file($podcast.'/podcast.xml');
	echo '<div class="podcast FlyUiMenuItem" onclick="DetailsWindow(this.id)" id="'.basename($podcast).'"><img class="podcastImg" style="background-image:url(\''.FlyConvertPathToURL($podcast).'/img60.jpg\');" src="'.$_FLY['RESOURCE']['URL']['OS'].'userimagelight.svg"><div class="podcastInfo"><b>'.htmlspecialchars($xml->name).'</b><br><span class="podcastArtist">'.htmlspecialchars($xml->artist).'</span></div></div>';
}
?>

</body>
</html>
<?php
exit;

details:
include 'Fly.Core.php';
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

$podcast = $_FLY['APP']['DATA_PATH'].'podcasts/'.$_GET['details'].'/';
$xml = simpleXML_load_file($podcast.'/podcast.xml');

$json = json_decode(base64_decode($xml->json));

echo '<!--';
//$rss = simpleXML_load_file($xml->feed);
echo '-->';

$genres = '';
foreach ($json->genres as $genre) {
	$genres .= $genre.', ';
}
$genres = str_lreplace(', ','',$genres);

if (is_dir($_FLY['APP']['DATA_PATH'].'/podcasts/'.$_GET['details'])) {
	$addButton = '<button id="addButton" onclick="window.remove()"><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'mark-minus.svg">Remove from My Podcasts</button>';
} else {
	$addButton = '<button id="addButton" onclick="add()"><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'mark-plus.svg">Add to My Podcasts</button>';
}
?>
<script>
function onload() {
	Fly.window.title.set('<?php echo $_FLY['APP']['NAME']; ?> - '+decodeURIComponent('<?php echo rawurlencode($xml->name); ?>')+' (Podcast)');
}
function add() {
	document.getElementById('addButton').disabled = true;
	document.getElementById('frame').src = 'podcast.php?add=<?php echo $_GET['details']; ?>&json=<?php echo $xml->json; ?>';
}
function added() {
	document.getElementById('addButton').innerHTML = '<img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-minus.svg">Remove from My Podcasts';
	document.getElementById('addButton').onclick = remove;
	document.getElementById('addButton').disabled = false;
}
function remove() {
	document.getElementById('addButton').disabled = true;
	document.getElementById('frame').src = 'podcast.php?remove=<?php echo $_GET['details']; ?>';
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

<p><?php echo htmlspecialchars($xml->description); ?></p>
<p><?php echo $genres; ?></p>
<p><?php echo $json->trackCount; ?> episodes available (not synced)</p>
<p>Last released <?php echo date("F j, Y",strtotime($json->releaseDate)); ?> (not synced)</p>
<p>Added to My Podcasts <?php echo date("F j, Y",(string)$xml->added); ?></p>
<p style="opacity:0.5;font-size:0.5em;"><?php echo $_GET['details']; ?></p>

</div>
<?php echo $addButton; ?>

<iframe id="frame" style="display:none;" src=""></iframe>

</body>
</html>
<?php
exit;
?>