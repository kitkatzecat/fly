<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.VARS')) {
FlyIncludeRegister('FLY.VARS');

$FlyVarsIncluded = true;

echo '
<script>
var FlyVars = function(string=\'\',callback=function(){}) {
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
	
	FlyVars.callback = callback;
	
    FlyVars.request.open("GET", \''.$_FLY['RESOURCE']['URL']['COMPONENTS'].'cmd.php?time=\'+dateTime+\'&json=true&cmd=echo:\'+encodeURIComponent(string), false);
    FlyVars.request.onreadystatechange = FlyVars.response;
    FlyVars.request.setRequestHeader("Cache-Control", "no-cache");
    FlyVars.request.send(null);
}
FlyVars.replace = FlyVars;
FlyVars.request = new XMLHttpRequest();
FlyVars.callback = function(){};

FlyVars.response = function() {
    if(FlyVars.request.readyState == 4) {
        if(FlyVars.request.status == 200) {
            result = FlyVars.request.responseText;
			try {
				result = JSON.parse(result);
				FlyVars.callback(result.return);
			} catch(err) {
				FlyVars.error = err;
				FlyVars.callback(false);
			}
        } else {
			FlyVars.error = FlyVars.request.statusText;
            FlyVars.callback(false);
      }
   }
}

if (typeof Fly != \'undefined\') {
	Fly.vars = FlyVars;
} else {
	var Fly = {};
	Fly.vars = FlyVars;
}
</script>
';

}
?>