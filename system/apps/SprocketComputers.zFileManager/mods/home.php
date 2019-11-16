<script>
window.addEventListener('load',function() {
	Display.Title('Home');
	Display.Icon('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg');
	Display.Path('Home');
	Display.Status('Ready');

	document.body.innerHTML = '<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg">Home</div>';
});
</script>

<!-- if $View == filmstrip, change to something else (tiles? medium icons?) -->