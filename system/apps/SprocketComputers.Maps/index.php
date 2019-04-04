<!DOCTYPE html>
<html>
<head>
	<?php
	include 'Fly.Standard.php';
	?>
	<script src="OpenLayers.js"></script>
	<script>
	Fly.window.ready = function() {
		map = new OpenLayers.Map('main');
		map.addLayer(new OpenLayers.Layer.OSM());
		map.zoomToMaxExtent();
		map.setCenter([38.957586,-95.253044]);

		Toolbar = new Fly.actionbar();
		Toolbar.style.position = 'absolute';
		Toolbar.style.top = '0px';
		Toolbar.style.left = '0px';
		Toolbar.style.right = '0px';
		
		Toolbar.add({text:'About',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg',action:function() {Fly.control.modal('About Maps','Maps uses OpenLayers 2 to display maps. Mapping data copyright &copy; OpenStreetMap contributors.');}});

		Toolbar.add({text:'',title:'Zoom Out',align:'right',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-minus.svg',action:function() {map.zoomOut();}});
		Toolbar.add({text:'',title:'Zoom In',align:'right',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg',action:function() {map.zoomIn();}});
		Toolbar.add({text:'Zoom...',align:'right',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg',action:function() {}});
		
		document.body.appendChild(Toolbar);
	}
	function MapContext(pos) {
		var options = [];
		options.push(['Zoom In',function() {map.zoomIn();},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg'}]);
		options.push(['Zoom Out',function() {map.zoomOut();},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-minus.svg'}]);
		
		Fly.actionmenu(pos,options);
	}
	</script>
	<style>
	#main {
		position: absolute;
		top: 34px;
		left: 0px;
		right: 0px;
		bottom: 0px;
		background-color: #888;
	}
	</style>
</head>
<body>
	<div id="main"oncontextmenu="MapContext(event);event.stopPropagation();return false;"></div>
</body>
</html>