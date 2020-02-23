<?php
$Applications = json_decode(FlyCommand('apps')['return'],true);
$List = [];


foreach ($Applications as $app) {
	$process = FlyFileStringProcessor($app);
	array_push($List,$process);
}

echo '
<script>
var Folder = {};
var Files = JSON.parse(atob(\''.base64_encode(json_encode($Applications)).'\'));
var List = JSON.parse(atob(\''.base64_encode(json_encode($List)).'\'));
</script>
';
?>
<script>
window.addEventListener('load',function() {
	try {
		window.View(Folder,List);
		if (parseInt(List.length)) {
			Display.Status(List.length+' items');
		} else {
			Display.Status('Ready');
		}
		Display.Title('Applications');
		Display.Icon('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg');
		Display.Path('Applications');

		document.body.oncontextmenu = function(e) {e.cancelBubble();};
	} catch(e) {
		console.log(e); // Change the = below to a += for debugging directories that always break views
		document.body.innerHTML = '<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg">An error occurred while loading this view.</div><p class="description">Try refreshing. If the problem persists, change the view.</p><p style="font-family:monospace;">'+e+'</p>';
		Display.Title('View Error');
		Display.Path('Applications');
		Display.Icon('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg');
	}
});
</script>