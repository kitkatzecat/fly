<?php
// Fly.SingleFileCommand
	function getSymbolByQuantity($bytes) {
		$symbols = array('bytes','KB','MB','GB','TB','PB','EB','ZB','YB');
		$exp = floor(log($bytes)/log(1024));
		
		return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
	}
	$total_space = disk_total_space($_FLY['PATH']);
	$free_space = disk_free_space($_FLY['PATH']);
	$percent_free = round(($free_space/$total_space)*100);
	
	FlyCommandDisplay('This device has '.getSymbolByQuantity($free_space).' free of '.getSymbolByQuantity($total_space).' ('.$percent_free.'% free).');
	FlyCommandError('shell.dialog(\'Storage\',\'This device has '.getSymbolByQuantity($free_space).' free of '.getSymbolByQuantity($total_space).' ('.$percent_free.'% free).\',\'Storage\',\''.$_FLY['RESOURCE']['URL']['ICONS'].'info.svg\')');
?>