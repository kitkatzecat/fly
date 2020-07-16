<script>
var OptionsSave = function(params,callback=function(r) {
		if (r) {
			Fly.window.message('Saved');
		} else {
			Fly.window.message('Error saving');
		}
	}) {
	var body = 'content='+encodeURIComponent(JSON.stringify(params));

	var request = new XMLHttpRequest();
	request.open('POST','<?php echo $_FLY['APP']['URL']; ?>save.php');
	request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	request.setRequestHeader("Cache-Control", "no-cache");
	request.addEventListener('readystatechange',function() {
		if (request.readyState == 4) {
			if (request.status == 200) {
				callback(true);
			} else {
				callback(false);
			}
		}
	});
	request.send(body);
}
</script>