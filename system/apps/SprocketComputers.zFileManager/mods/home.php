<?php
include 'Fly.CommonStyle.php';
?>
<script>
window.addEventListener('load',function() {
	Display.Title('Home');
	Display.Icon('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg');
	Display.Path('Home');
	Display.Status('Ready');

	document.body.innerHTML = '<div class="FlyCSTitle FlyCSSectionTitle">Home<img class="FlyCSSectionIcon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg"></div>';
});
</script>

<!-- if $View == filmstrip, change to something else (tiles? medium icons?) -->