<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
var Toolbar;
function onload() {
	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.top = '0px';
	Toolbar.style.left = '0px';
	Toolbar.style.right = '0px';
	
	Toolbar.add({text:'Add Podcast',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg',action:Podcast.add});
	Toolbar.add({text:'Refresh',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg',align:'right',action:Podcast.refresh});
	
	document.body.appendChild(Toolbar);
	
	Fly.window.title.set('<?php echo $_FLY['APP']['NAME']; ?> - My Podcasts');
	document.getElementById('frame').src = 'mypodcasts.php';
}

var Podcast = {
	add: function() {
		Dialog.open('search.php?','<?php echo $_FLY['APP']['NAME']; ?> - Add Podcast',300,400);
	},
	refresh: function() {
		document.getElementById('frame').contentWindow.location.reload();
	}
}
var Dialog = {
	open: function(url='',title='<?php echo $_FLY['APP']['NAME']; ?>',width=300,height=300) {
		var pos = Fly.window.position.get();
		var size = Fly.window.size.get();
		var x = parseInt(pos[0])+parseInt((size[0]/2)-(width/2));
		var y = parseInt(pos[1])+parseInt((size[1]/2)-(height/2));
		url = '<?php echo $_FLY['WORKING_URL'];?>'+url+'&parent_id='+Fly.window.id;
		return window.top.task.create('<?php echo $_FLY['APP']['ID']; ?>',{name:title,title:title,width:width,height:height,icon:'<?php echo $_FLY['APP']['ICON_URL']; ?>',x:x,y:y,location:url});
	}
};
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
}
#frame {
	width: 100%;
	height: 100%;
	box-sizing: border-box;
}
</style>
</head>
<body onload="onload()">

<div id="main">
<iframe frameborder="0" id="frame" src=""></iframe>
</div>

</body>
</html>