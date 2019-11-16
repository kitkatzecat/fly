<?php
//$Output = '<div class="title"><img class="title-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'home.svg">Home</div><p class="description">This will eventually be where this home will load.</p><p>'.trimslashes(str_freplace($_FLY['PATH'],'./',$Path)).'</p><p>Or, try going <a><img class="inline-icon" style="margin-right:4px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'home.svg">Home</a>';

$HomeList = [];
$HomeFiles = [];

array_push($HomeList,FlyFileStringProcessor($_FLY['USER']['PATH'].'Documents'));
array_push($HomeFiles,'Documents');

array_push($HomeList,FlyFileStringProcessor('/mnt/c'));
array_push($HomeFiles,'c');


echo '
<script>
var Folder = true;
var List = JSON.parse(atob(\''.base64_encode(json_encode($HomeList)).'\'));
var Files = JSON.parse(atob(\''.base64_encode(json_encode($HomeFiles)).'\'));
</script>
';
?>