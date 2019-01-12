<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.EXTENSIONS');

echo '
<script>
if (typeof Fly == \'undefined\') {
	var Fly = {};
}

Fly.extension = function() {};
Fly.extension.replace = function(id,app,type) {
	ext = app.replace(/\./g,"_")+\'_\'+type;
	if (typeof window["Fly"]["extension"][ext] !== "undefined") {
		window["Fly"]["extension"][ext](id);
	} else {
		return false;
	}
}
</script>
';
function FlyLoadExtension($app,$id) {
	if (file_exists(FLY_APPS_PATH.$app)) {
		$manifestXML = simpleXML_load_file(FLY_APPS_PATH.$app.'/ApplicationManifest.xml');
		if (isset($manifestXML->extensions)) {
			if (isset($manifestXML->extensions->xpath("//extension[@id='".$id."']")[0])) {
				$xml = $manifestXML->extensions->xpath("//extension[@id='".$id."']")[0];
				$return = '';
				if ($xml['src'] !== '') {
					if (file_exists(FLY_APPS_PATH.$app.'/'.$xml['src'])) {
						$code = file_get_contents(FLY_APPS_PATH.$app.'/'.$xml['src']);
					} else {
						$code = (string)$xml;
					}
				} else if (!empty($xml)) {
					$code = (string)$xml;	
				} else {
					$code = (string)$xml;
				}
				if ($xml['type'] == 'control') {
					$return .= '
					<script>
					Fly.extension.'.str_replace('.','_',$app).'_'.$id.' = function(id) {
						var WindowId = Fly.window.id;
						var ControlId = id;
						var Control = document.getElementById(id);
						'.FlyVarsReplace($code).'
					}
					</script>
					';
				} else if ($xml['type'] == 'run') {
					$return .= '
					<script>
					Fly.extension.'.str_replace('.','_',$app).'_'.$id.' = function() {
						var WindowId = Fly.window.id;
						'.FlyVarsReplace($code).'
					}
					setTimeout(Fly.extension.'.str_replace('.','_',$app).'_'.$id.',500);
					</script>
					';
				} else {
					$return .= '
					<script>
					Fly.extension.'.str_replace('.','_',$app).'_'.$id.' = function(id) {
						var WindowId = Fly.window.id;
						var ControlId = id;
						var Control = document.getElementById(id);
						'.$code.'
					}
					</script>
					';
				}
				return $return;
			} else {
				return '<script>window.top.shell.dialog(\'Error loading extension\',\'The extension "'.str_replace('.','_',$app).'_'.$id.'" could not be loaded because the extension does not exist.\',\'Extension Error\')</script>';
			}
		} else {
			return '<script>window.top.shell.dialog(\'Error loading extension\',\'The extension "'.str_replace('.','_',$app).'_'.$id.'" could not be loaded because the application does not have any extensions.\',\'Extension Error\')</script>';
		}
	} else {
		return '<script>window.top.shell.dialog(\'Error loading extension\',\'The extension "'.str_replace('.','_',$app).'_'.$id.'" could not be loaded because the application does not exist.\',\'Extension Error\')</script>';
	}
}
?>