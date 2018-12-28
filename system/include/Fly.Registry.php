<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.REGISTRY');

if (!function_exists('FlyRegistrySet')) {
function FlyRegistrySet( $key, $value ) {
	global $_FLY;
	if ($_FLY['IS_USER']) {
		return FlyUserRegistrySet($key,$value);
	} else {
		return FlyGlobalRegistrySet($key,$value);
	}
}

function FlyUserRegistrySet( $key, $value ) {
	global $_FLY;
	
	if ($_FLY['IS_APP']) {
		$application = $_FLY['APP']['ID'];
	} else {
		$application = 'root.public';
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$user = $_FLY['USER']['PATH'].'/data/registry/';
	
	if (!is_dir($user.$pub)) {
		mkdir($user.$pub);
		mkdir($user.$pub.'/'.$app);
	}
	if (!is_dir($user.$pub.'/'.$app)) {
		mkdir($user.$pub.'/'.$app);
	}
	
	if ($key == '') {
		return false;
	}

	if (file_put_contents($user.$pub.'/'.$app.'/'.$key,$value)) {
		return true;
	} else {
		return false;
	}

}

function FlyGlobalRegistrySet( $key, $value ) {
	global $_FLY;
	
	if ($_FLY['IS_APP']) {
		$application = $_FLY['APP']['ID'];
	} else {
		$application = 'root.public';
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$user = $_FLY['REGISTRY'];
	
	if (!is_dir($user.$pub)) {
		mkdir($user.$pub);
		mkdir($user.$pub.'/'.$app);
	}
	if (!is_dir($user.$pub.'/'.$app)) {
		mkdir($user.$pub.'/'.$app);
	}
	
	if ($key == '') {
		return false;
	}
	
	if (file_put_contents($user.$pub.'/'.$app.'/'.$key,$value)) {
		return true;
	} else {
		return false;
	}

}

function FlyRegistryGet( $key, $application='' ) {
	global $_FLY;
	if ($_FLY['IS_USER']) {
		return FlyUserRegistryGet($key,$application);
	} else {
		return FlyGlobalRegistryGet($key,$application);
	}
}

function FlyUserRegistryGet( $key, $application='' ) {
	global $_FLY;
	
	if (empty($application)) {
		if ($_FLY['IS_APP']) {
			$application = $_FLY['APP']['ID'];
		} else {
			$application = 'root.public';
		}
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$registry = $_FLY['USER']['PATH'].'/data/registry/';
	
	if (!is_dir($registry.$pub) || !is_dir($registry.$pub.'/'.$app) || !file_exists($registry.$pub.'/'.$app.'/'.$key)) {
		return false;
	} else {
		return file_get_contents($registry.$pub.'/'.$app.'/'.$key);
	}
}

function FlyGlobalRegistryGet( $key, $application='' ) {
	global $_FLY;
	
	if (empty($application)) {
		if ($_FLY['IS_APP']) {
			$application = $_FLY['APP']['ID'];
		} else {
			$application = 'root.public';
		}
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$registry = $_FLY['REGISTRY'];
	
	if (!is_dir($registry.$pub) || !is_dir($registry.$pub.'/'.$app) || !file_exists($registry.$pub.'/'.$app.'/'.$key)) {
		return false;
	} else {
		return file_get_contents($registry.$pub.'/'.$app.'/'.$key);
	}
}

function FlyRegistryRemove( $key, $application='' ) {
	global $_FLY;
	if ($_FLY['IS_USER']) {
		return FlyUserRegistryRemove($key,$application);
	} else {
		return FlyGlobalRegistryRemove($key,$application);
	}
}

function FlyUserRegistryRemove( $key, $application='' ) {
	global $_FLY;
	
	if (empty($application)) {
		if ($_FLY['IS_APP']) {
			$application = $_FLY['APP']['ID'];
		} else {
			$application = 'root.public';
		}
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$registry = $_FLY['USER']['PATH'].'/data/registry/';
	
	if (!is_dir($registry.$pub) || !is_dir($registry.$pub.'/'.$app) || !file_exists($registry.$pub.'/'.$app.'/'.$key)) {
		return false;
	} else {
		return unlink($registry.$pub.'/'.$app.'/'.$key);
	}
}

function FlyGlobalRegistryRemove( $key, $application='' ) {
	global $_FLY;
	
	if (empty($application)) {
		if ($_FLY['IS_APP']) {
			$application = $_FLY['APP']['ID'];
		} else {
			$application = 'root.public';
		}
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$registry = $_FLY['REGISTRY'];
	
	if (!is_dir($registry.$pub) || !is_dir($registry.$pub.'/'.$app) || !file_exists($registry.$pub.'/'.$app.'/'.$key)) {
		return false;
	} else {
		return unlink($registry.$pub.'/'.$app.'/'.$key);
	}
}

function FlyRegistryGetKeys( $application='' ) {
	global $_FLY;
	if ($_FLY['IS_USER']) {
		return FlyUserRegistryGetKeys($application);
	} else {
		return FlyGlobalRegistryGetKeys($application);
	}
}

function FlyUserRegistryGetKeys( $application='' ) {
	global $_FLY;
	
	if (empty($application)) {
		if ($_FLY['IS_APP']) {
			$application = $_FLY['APP']['ID'];
		} else {
			$application = 'root.public';
		}
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$registry = $_FLY['USER']['PATH'].'/data/registry/';
	
	if (!is_dir($registry.$pub) || !is_dir($registry.$pub.'/'.$app)) {
		return false;
	} else {
		
		$path = $registry.$pub.'/'.$app.'/';
		$return = array();

		$ignore = array( '.', '..' ); 

		$dh = @opendir( $path ); 

		while( false !== ( $file = readdir( $dh ) ) ){ 

		    if( !in_array( $file, $ignore ) ){ 

		        if( !is_dir( "$path/$file" ) ){ 
					$return[$file] = file_get_contents("$path/$file");
				}

		    } 

		} 
		closedir( $dh ); 
		asort($return);
		return $return;
		
	}
}

function FlyGlobalRegistryGetKeys( $application='' ) {
	global $_FLY;
	
	if (empty($application)) {
		if ($_FLY['IS_APP']) {
			$application = $_FLY['APP']['ID'];
		} else {
			$application = 'root.public';
		}
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$registry = $_FLY['REGISTRY'];
	
	if (!is_dir($registry.$pub) || !is_dir($registry.$pub.'/'.$app)) {
		return false;
	} else {
		
		$path = $registry.$pub.'/'.$app.'/';
		$return = array();

		$ignore = array( '.', '..' ); 

		$dh = @opendir( $path ); 

		while( false !== ( $file = readdir( $dh ) ) ){ 

		    if( !in_array( $file, $ignore ) ){ 

		        if( !is_dir( "$path/$file" ) ){ 
					$return[$file] = file_get_contents("$path/$file");
				}

		    } 

		} 
		closedir( $dh ); 
		asort($return);
		return $return;
		
	}
}


function FlyRegistryListKeys( $application='' ) {
	global $_FLY;
	if ($_FLY['IS_USER']) {
		return FlyUserRegistryListKeys($application);
	} else {
		return FlyGlobalRegistryListKeys($application);
	}
}

function FlyUserRegistryListKeys( $application='' ) {
	global $_FLY;
	
	if (empty($application)) {
		if ($_FLY['IS_APP']) {
			$application = $_FLY['APP']['ID'];
		} else {
			$application = 'root.public';
		}
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$registry = $_FLY['USER']['PATH'].'/data/registry/';
	
	if (!is_dir($registry.$pub) || !is_dir($registry.$pub.'/'.$app)) {
		return [];
	} else {
		
		$path = $registry.$pub.'/'.$app.'/';
		$return = array();

		$ignore = array( '.', '..' ); 

		$dh = @opendir( $path ); 

		while( false !== ( $file = readdir( $dh ) ) ){ 

		    if( !in_array( $file, $ignore ) ){ 

		        if( !is_dir( "$path/$file" ) ){ 
					array_push($return,$file);
				}

		    } 

		} 
		closedir( $dh ); 
		asort($return);
		return $return;
		
	}
}
	
function FlyGlobalRegistryListKeys( $application='' ) {
	global $_FLY;
	
	if (empty($application)) {
		if ($_FLY['IS_APP']) {
			$application = $_FLY['APP']['ID'];
		} else {
			$application = 'root.public';
		}
	}
	
	$pub = explode('.',$application)[0];
	$app = explode('.',$application)[1];
	
	$registry = $_FLY['REGISTRY'];
	
	if (!is_dir($registry.$pub) || !is_dir($registry.$pub.'/'.$app)) {
		return [];
	} else {
		
		$path = $registry.$pub.'/'.$app.'/';
		$return = array();

		$ignore = array( '.', '..' ); 

		$dh = @opendir( $path ); 

		while( false !== ( $file = readdir( $dh ) ) ){ 

		    if( !in_array( $file, $ignore ) ){ 

		        if( !is_dir( "$path/$file" ) ){ 
					array_push($return,$file);
				}

		    } 

		} 
		closedir( $dh ); 
		asort($return);
		return $return;
		
	}
}
}

//FlyRegistryGlobalGet
?>