<?php
if ($_GET['results'] == 'true') {
	goto query;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

echo '
<script>
Fly.window.focus.take(\''.$_GET['parent_id'].'\');
function CloseDialog() {
	Fly.window.focus.give(\''.$_GET['parent_id'].'\');
	window.top.document.getElementById(\''.$_GET['parent_id'].'\').window.bringToFront();
	Fly.window.close();
}
Fly.window.onclose = CloseDialog;
</script>

';

?>
<script>
var Toolbar;
var SearchBar;
var SearchBarObject;
var SearchBarClassName;
var SearchButton;
function onload() {
	var theme = window.top.shell.getThemeInfo()[2];
	if (theme == "E794H6r0rTF8bY45PfVYjFfi7j9w89sP") {
		SearchBarClassName = "transparent-white";
	} else if (theme == "m8k52VTgGFubX7Hz11LFDb3gYQ63P21p") {
		SearchBarClassName = "black";
	} else {
		SearchBarClassName = "white";
	}

	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.top = '0px';
	Toolbar.style.left = '0px';
	Toolbar.style.right = '0px';
	
	SearchBar = document.createElement('input');
	SearchBar.type = 'text';
	SearchBar.value = 'Type here to search podcasts';
	SearchBar.style.width = '100%';
	SearchBar.className = 'pathbar FlyUiTextHighlight '+SearchBarClassName;
	SearchBar.onfocus = function() {
		if (SearchBar.value == 'Type here to search podcasts') {
			SearchBar.value = '';
		}
		SearchBar.className = 'pathbar-focus';
	}
	SearchBar.onblur = function() {
		if (SearchBar.value == '') {
			SearchBar.value = 'Type here to search podcasts';
		}
		SearchBar.className = 'pathbar FlyUiTextHighlight '+SearchBarClassName;
		window.getSelection().removeAllRanges();
	}
	SearchBar.onkeydown = function(e) {
		if (e.keyCode == 13) {
			Search();
		}
	}
 	
	SearchBarObject = Toolbar.add({type:'custom',content:SearchBar});
	
	SearchButton = Toolbar.add({text:'Search',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg',align:'right',action:Search});
	
	document.body.appendChild(Toolbar);
	
	SearchBarObject.style.width = 'calc(100% - '+(SearchButton.offsetWidth+4)+'px)';
	
	Fly.window.title.set('<?php echo $_FLY['APP']['NAME']; ?> - Add Podcast');
}
function Search() {
	var frame = document.getElementById('frame');
	if (SearchBar.value !== 'Type here to search podcasts' && SearchBar.value !== '') {
		frame.src = 'search.php?results=true&query='+encodeURIComponent(SearchBar.value);
	}
	SearchBar.blur();
}

function DetailsWindow(id,json) {
		var title = '<?php echo $_FLY['APP']['NAME']; ?> - Podcast Details';
		var pos = Fly.window.position.get();
		var size = Fly.window.size.get();
		var x = parseInt(pos[0])+parseInt((size[0]/2)-(400/2));
		var y = parseInt(pos[1])+parseInt((size[1]/2)-(300/2));
		var url = '<?php echo $_FLY['WORKING_URL'];?>podcast.php?itunes_id='+id+'&json='+json;
		return window.top.task.create('<?php echo $_FLY['APP']['ID']; ?>',{name:title,title:title,width:400,height:300,icon:'<?php echo $_FLY['APP']['ICON_URL']; ?>',x:x,y:y,location:url});
}
</script>
<style>
#main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	overflow: hidden;
	background-color: #fff;
	padding: 0px;
}
#frame {
	width: 100%;
	height: 100%;
	margin: 0px;
	padding: 0px;
	box-sizing: border-box;
}
.pathbar-focus {
	margin-left: 0px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	cursor: text;
	font-size: 14px;
}
.pathbar {
	margin-left: 0px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	background-color: transparent;
	cursor: text;
	font-size: 14px;
}
.pathbar:disabled {
	color: #808080 !important;
}
.transparent-white {
	border: .1px solid rgba(255,255,255,0.3) !important;
}
.transparent-white:hover {
	background-color: rgba(255,255,255,0.2);
}
.white {
	border: .1px solid rgb(255,255,255) !important;
}
.white:hover {
	background-color: rgba(255,255,255,0.2);
}
.black {
	border: .1px solid rgb(0,0,0) !important;
}
.black:hover {
	background-color: rgba(255,255,255,0.2);
}
</style>
</head>
<body onload="onload()">

<div id="main">
<iframe frameborder="0" id="frame" src="search.php?results=true"></iframe>
</div>

</body>
</html>
<?php
exit;

query:
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

if ($_GET['query'] == '') {
	$content = '<div class="FlyUiText" style="opacity:0.5;font-size:0.8em;">Search results will be displayed here</div>';
} else {
	$json = json_decode(file_get_contents('http://itunes.apple.com/search?entity=podcast&limit=30&term='.rawurlencode($_GET['query'])));
	$content = '';
	foreach ($json->results as $result) {
		$content .= '<div class="podcast FlyUiMenuItem" onclick="window.parent.DetailsWindow(this.id,\''.base64_encode(json_encode($result)).'\')" id="'.$result->collectionId.'"><img class="podcastImg" style="background-image:url(\''.$result->artworkUrl60.'\');" src="'.$_FLY['RESOURCE']['URL']['OS'].'userimagelight.svg"><div class="podcastInfo"><b>'.htmlspecialchars($result->collectionName).'</b><br><span class="podcastArtist">'.htmlspecialchars($result->artistName).'</span></div></div>';
	}
	$content .= '<p style="opacity:0.5;" class="FlyUiText">'.$json->resultCount.' results<span style="font-size:0.5em;float:right;margin-top:8px;">Powered by the iTunes API</span></p>';
}
?>
<style>
body {
	margin: 4px;
}
.podcastImg {
	width: 40px;
	height: 40px;
	float: left;
	margin-right: 4px;
	margin-bottom: 4px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	border-radius: 5px;
	box-shadow: 0px 0px 3px #808080;
}
.podcastArtist {
	opacity: 0.7;
	font-size: 0.8em;
}
.podcastInfo {
	display: inline-block;
	max-width: calc(100% - 50px);
}
.podcast {
	margin-bottom: 4px;
	min-height: 56px !important;
}
</style>
</head>
<body class="FlyUiNoSelect">

<?php echo $content; ?>

</body>
</html>
