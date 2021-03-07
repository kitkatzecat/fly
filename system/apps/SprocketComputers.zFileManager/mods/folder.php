<?php
function FileManagerFolderModProperties(&$process) {
	$process['FileManager_Modified'] = nicetime(filemtime($process['file']));
	$process['FileManager_DateModified'] = date("M j, Y g:i A",filemtime($process['file']));
	if (!$process['isdir']) {
		$process['FileManager_Size'] = formatFileSize(filesize($process['file']));
	}
}
function formatFileSize($filesize) {
	$filesize = abs(intval($filesize));
	if ($filesize > 1000000000) {
		$filesize = round($filesize/1000000000,2).' GB';
	} else if ($filesize > 1000000 && $filesize < 10000000000) {
		$filesize = round($filesize/1000000,2).' MB';
	} else if ($filesize > 1000 && $filesize < 10000000) {
		$filesize = round($filesize/1000).' KB';
	} else {
		$filesize = $filesize.' bytes';
	}
	
	return $filesize;
}
function nicetime($date)
{
	if (empty($date)) {
		return "Not available";
	}
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");
	$now = time();
	$unix_date = $date;
	
	if (empty($unix_date)) {    
		return "Not available";
	}
	
	if ($now > $unix_date) {    
		$difference = $now - $unix_date;
		$tense = "ago";
	} else {
		$difference = $unix_date - $now;
		$tense = "from now";
	}
	for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}
	$difference = round($difference);

	if ($difference != 1) {
		$periods[$j].= "s";
	}
	$return = "$difference $periods[$j] {$tense}";
	if ($return == "0 seconds from now") {
		$return = "Just now";
	}
	
	return $return;
}

$Path = FlyVarsReplace($Path,false,FlyCoreVars($_FLY['PATH']));
$FolderProcess = FlyFileStringProcessor($Path);	

$protected_enforce = FlyRegistryGet('ShowSystemFiles');
$protected = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['COMPONENTS'].'protected.json'),true);

if (in_array($FolderProcess['ffile'],$protected) && $protected_enforce !== 'true') {
	// File/folder is a protected system file
	$Output = '<script>window.parent.Fly.window.title.set(\'System File\');Fly.window.disableContext();</script><div class="title"><img class="title-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'warning.svg">The specified path is a system folder or file.</div><p class="description">These files are hidden to protect your computer.</p><p>'.trimslashes(str_freplace($_FLY['PATH'],'./',$Path)).'</p><p>If you still want to continue, <a onclick="window.parent.SystemFiles.toggle();"><img class="inline-icon" style="margin-right:4px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'options.svg">Show System Files</a>';
	echo '
	<script>
	var Folder = false;
	var List = false;
	var Files = false;
	</script>
	';
} else if (is_dir($Path)) {
	$FolderList = FlyCommand($_FLY['APP']['PATH'].'cmd/list.php:'.$Path);
	$FolderListArray = json_decode($FolderList['return']);
	// add sorting code somewhere around here
	if (count($FolderListArray) == 0) {
		// Directory is empty
		$Output = '<div class="title"><img class="title-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'folder.svg">This directory is empty.</div>';
		echo '
		<script>
		var Folder = JSON.parse(atob(\''.base64_encode(json_encode($FolderProcess)).'\'));
		var List = false;
		var Files = [];
		
		document.oncontextmenu = function(e) {
			ContextMenu(Folder,e);
			e.preventDefault();
			return false;
		}
		</script>
		';
	} else {
		// Directory is not empty
		foreach ($FolderListArray as &$Item) {
			$process = FlyFileStringProcessor($Path.'/'.$Item);
			$Item = $process;
			FileManagerFolderModProperties($Item);
		}
		echo '
		<script>
		var Folder = JSON.parse(atob(\''.base64_encode(json_encode($FolderProcess)).'\'));
		var List = JSON.parse(atob(\''.base64_encode(json_encode($FolderListArray)).'\'));
		var Files = JSON.parse(atob(\''.base64_encode($FolderList['return']).'\'));
		</script>
		';
	}
} else {
	// Path given is not to a directory
	if ($FolderProcess['type'] == 'file') {
		// Path given is to a file
		if ($FolderProcess['extension'] == 'als') {
			$als = simpleXML_load_file($FolderProcess['file']);
			$ALSProcess = FlyFileStringProcessor(FlyVarsReplace($als->link));
			if (!!$ALSProcess && $ALSProcess['type'] == 'folder') {
				echo '<script>window.parent.Nav(\''.$ALSProcess['file'].'\');</script>';
			} else {
				echo '<script>window.top.eval(atob(\''.base64_encode('system.command(\'run:'.$FolderProcess['file'].'\');').'\'));window.parent.Fly.window.message(\'"\'+atob(\''.base64_encode($FolderProcess['fname']).'\')+\'" has been opened\');window.parent.Nav(\''.$FolderProcess['path'].'\');</script>';
			}
		}
	} else {
		// Directory does not exist
		$Output = '<script>window.parent.Fly.window.title.set(\'Not Found\');Fly.window.disableContext();</script><div class="title"><img class="title-icon" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'error.svg">The specified directory or keyword could not be found.</div><p class="description">Try checking the spelling of the path.</p><p>'.trimslashes(str_freplace($_FLY['PATH'],'./',$Path)).'</p><p>Or, try going <a onclick="window.parent.Nav(\'?home\');"><img class="inline-icon" style="margin-right:4px;" src="'.$_FLY['RESOURCE']['URL']['ICONS'].'home.svg">Home</a>';
		echo '
		<script>
		var Folder = false;
		var List = false;
		var Files = false;
		</script>
		';
	}
}
?>
<script>
if (typeof onLoad == 'undefined') {
	onLoad = [];
}
onLoad.push(function() {
	if (Folder && List) {
		try {
			window.View(Folder,List);
			if (parseInt(List.length)) {
				Display.Status(List.length+' items');
			} else {
				Display.Status('Ready');
			}
			window.Check = function() {
			Fly.command('<?php echo $_FLY['APP']['PATH']; ?>cmd/list.php:'+Folder['file'],function(a){
				if (JSON.stringify(a.return) != JSON.stringify(Files) && !!a.return) {
					Refresh(window.pageYOffset);
				}
			},{silent:true});
		}
		window.CheckInterval = setInterval(window.Check,<?php echo FlyRegistryGet('RefreshInterval'); ?>);

		} catch(e) {
			console.log(e); // Change the = below to a += for debugging directories that always break views
			Fly.window.disableContext();
			document.body.innerHTML = '<div class="title"><img class="title-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg">An error occurred while loading this view.</div><p class="description">Try refreshing. If the problem persists, change the view.</p><p style="font-family:monospace;">'+e+'</p>';
			Display.Title('View Error');
			Display.Path(atob('<?php echo base64_encode($Path); ?>'));
			Display.Icon('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg');
		}
	} else {
		Display.Status('Ready');
		document.body.innerHTML = Output;
	}
});
</script>