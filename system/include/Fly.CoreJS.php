<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.COREJS');

echo '
<script>
var FlyCore = JSON.parse(atob(\''.base64_encode(json_encode($_FLY)).'\'));
if (typeof Fly != \'undefined\') {
	Fly.core = FlyCore;
} else {
	var Fly = {};
}
</script>
';
?>