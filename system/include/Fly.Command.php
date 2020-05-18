<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.COMMAND')) {
FlyIncludeRegister('FLY.COMMAND');

$FlyCommandIncluded = true;
include $_FLY['RESOURCE']['PATH']['COMPONENTS'].'cmd.php';

echo '
<script>
var FlyCommand = function(cmd=\'\',callback=function(){},options={}) {
	var now     = new Date(); 
	var year    = now.getFullYear();
	var month   = now.getMonth()+1; 
	var day     = now.getDate();
	var hour    = now.getHours();
	var minute  = now.getMinutes();
	var second  = now.getSeconds(); 
	var millisecond  = now.getMilliseconds(); 
	if(month.toString().length == 1) {
		var month = \'0\'+month;
	}
	if(day.toString().length == 1) {
		var day = \'0\'+day;
	}   
	if(hour.toString().length == 1) {
		var hour = \'0\'+hour;
	}
	if(minute.toString().length == 1) {
		var minute = \'0\'+minute;
	}
	if(second.toString().length == 1) {
		var second = \'0\'+second;
	}   
	var dateTime = year+\'\'+month+\'\'+day+\'\'+hour+\'\'+minute+\'\'+second+\'\'+millisecond;
	
	FlyCommand.callback = callback;
	
	if (typeof options.silent != \'undefined\' && options.silent) {
		var opt = \'&silent=true\';
	} else {
		var opt = \'\';
	}

	var request = new XMLHttpRequest();
	var error;
    request.open("GET", \''.$_FLY['RESOURCE']['URL']['COMPONENTS'].'cmd.php?time=\'+dateTime+\'&json=true&req=true\'+opt+\'&cp='.$_FLY['CURRENT_PATH'].'&cmd=\'+encodeURIComponent(cmd), true);
    request.onreadystatechange = function() {
		if(request.readyState == 4) {
			if(request.status == 200) {
				result = request.responseText;
				try {
					result = JSON.parse(result);
					try {
						result.return = JSON.parse(result.return);
					} catch(e) {}
					callback(result);
				} catch(err) {
					error = err;
					callback(false);
				}
			} else {
				error = FlyCommand.request.statusText;
				callback(false);
		  }
	   }
	};
    request.setRequestHeader("Cache-Control", "no-cache");
    request.send(null);
}

if (typeof Fly != "undefined") {
	Fly.command = FlyCommand;
} else {
	var Fly = {};
}
</script>
';

}
?>