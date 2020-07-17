<script>
function system() {};
var request = new XMLHttpRequest();
system.command = function(cmd='') {
	var now     = new Date(); 
	var year    = now.getFullYear();
	var month   = now.getMonth()+1; 
	var day     = now.getDate();
	var hour    = now.getHours();
	var minute  = now.getMinutes();
	var second  = now.getSeconds(); 
	var millisecond  = now.getMilliseconds(); 
	if(month.toString().length == 1) {
		var month = '0'+month;
	}
	if(day.toString().length == 1) {
		var day = '0'+day;
	}   
	if(hour.toString().length == 1) {
		var hour = '0'+hour;
	}
	if(minute.toString().length == 1) {
		var minute = '0'+minute;
	}
	if(second.toString().length == 1) {
		var second = '0'+second;
	}   
	var dateTime = year+''+month+''+day+''+hour+''+minute+''+second+''+millisecond;
	
	try {
		ui.loading.show();
	} catch(err) {}
	
    request.open("GET", '/system/components/cmd.php?time='+dateTime+'&cmd='+encodeURIComponent(cmd), true);
    request.onreadystatechange = system.command.response;
    request.setRequestHeader("Cache-Control", "no-cache");
    request.send(null);
}
system.command.response = function() {
    if(request.readyState == 4) {
	try {
		ui.loading.hide();
	} catch (err) {}
        if(request.status == 200) {
            result = request.responseText;
			eval(result);
        } else {
            shell.dialog( "An error occurred", request.statusText);
      }
   }
}
system.eval64 = function(code) {
	var cmd = atob(code);
	eval(cmd);
}
system.on = new Date();

function cmd(cmd) {
	system.command(cmd);
}

system.logout = function() {
	var audio = document.createElement('audio');
	audio.style.display = 'none';
	audio.innerHTML = '<source src="system/resources/sounds/logout.mp3"></source>';
	audio.onended = function() {
		window.location.href = 'index.php?logout=true';
	};
	document.body.appendChild(audio);
	
	var cover = document.createElement('div');
	cover.style.position = 'fixed';
	cover.style.top = '0px';
	cover.style.left = '0px';
	cover.style.bottom = '0px';
	cover.style.right = '0px';
	cover.style.zIndex = '50000000000';
	cover.style.backgroundColor = '#000';
	cover.style.transition = 'opacity 1s linear';
	cover.style.cursor = 'none';
	cover.style.opacity = '0';
	var windows = document.querySelectorAll('.FlyWindow');
	for (i = 0; i < windows.length; i++) { 
		windows[i].window.forceClose();
	}
	document.body.appendChild(cover);
	audio.play();
	setTimeout(function() {cover.style.opacity = '1';}, 1500);
}

// Shell commands
function shell() {};
shell.dialog = function(msg="An error occurred",content="Something went wrong",title="Error",icon="/system/resources/icons/error.svg") {
	//return task.create('public', {title:title, name:title, x:((window.innerWidth/2)-258), y:((window.innerHeight/2)-154), width:500, height:100, location:'/system/components/dialog2.php?type=message&content='+encodeURIComponent(window.btoa(content))+'&message='+encodeURIComponent(window.btoa(msg))+'&icon='+encodeURIComponent(window.btoa(icon)), icon:icon});
	if (typeof msg == 'object') {
		system.command('run:SprocketComputers.Utilities.ShellDialog,content='+encodeURIComponent(btoa(JSON.stringify(msg))));
	} else {
		var content = {
			message: msg,
			content: content,
			title: title,
			icon: icon
		}
		system.command('run:SprocketComputers.Utilities.ShellDialog,content='+encodeURIComponent(btoa(JSON.stringify(content))));
	}
}

shell.error = function(content="Something went wrong") {
	return task.create('public', {title:'Error', name:'Error', x:((window.innerWidth/2)-258), y:((window.innerHeight/2)-154), width:500, height:220, location:'/system/components/dialog.php?type=message&content='+encodeURIComponent(window.btoa(content))+'&message='+encodeURIComponent(window.btoa('An error occurred'))+'&icon='+encodeURIComponent(window.btoa('/system/resources/icons/error.svg')), icon:'/system/resources/icons/error.svg'});
}

shell.fatal = function(content="A fatal error has occurred. Please restart Fly.") {
	var backcover = document.createElement("div");
	backcover.style.position = 'absolute';
	backcover.style.top = '0px';
	backcover.style.left = '0px';
	backcover.style.right = '0px';
	backcover.style.bottom = '0px';
	backcover.style.zIndex = '5000005';
	backcover.style.backgroundColor = '#000';
	
	var mcover = document.createElement("div");
	mcover.style.position = 'absolute';
	mcover.style.top = '50%';
	mcover.style.left = '50%';
	mcover.style.width = '400px';
	mcover.style.paddingTop = '25px';
	mcover.style.paddingBottom = '25px';
	mcover.style.paddingLeft = '25px';
	mcover.style.paddingRight = '25px';
	mcover.style.backgroundColor = '#C0C0C0';
	mcover.style.borderLeft = '1px solid #FFFFFF';
	mcover.style.borderTop = '1px solid #FFFFFF';
	mcover.style.borderBottom = '1px solid #808080';
	mcover.style.borderRight = '1px solid #808080';
	mcover.style.transform = 'translate(-50%,-50%)';
	mcover.style.textAlign = 'center';
	mcover.style.zIndex = '5000006';
	mcover.innerHTML = '<span class="FlyUiNoSelect" style="color:#000000;font-family:sans-serif;"><img src="data:image/svg+xml;base64, PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5v Ij8+CjxzdmcKICAgeG1sbnM6b3NiPSJodHRwOi8vd3d3Lm9wZW5zd2F0Y2hib29rLm9y Zy91cmkvMjAwOS9vc2IiCiAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxl bWVudHMvMS4xLyIKICAgeG1sbnM6Y2M9Imh0dHA6Ly9jcmVhdGl2ZWNvbW1vbnMub3Jn L25zIyIKICAgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJk Zi1zeW50YXgtbnMjIgogICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAv c3ZnIgogICB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiAgIHhtbG5z OnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIgogICB4bWxuczpzb2Rp cG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGkt MC5kdGQiCiAgIHhtbG5zOmlua3NjYXBlPSJodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy9u YW1lc3BhY2VzL2lua3NjYXBlIgogICBoZWlnaHQ9IjEwMCUiCiAgIHdpZHRoPSIxMDAl IgogICB2ZXJzaW9uPSIxLjEiCiAgIGlkPSJzdmcyIgogICB2aWV3Qm94PSIwLDAsNDgs NDgiCiAgIGlua3NjYXBlOnZlcnNpb249IjAuOTEgcjEzNzI1IgogICBzb2RpcG9kaTpk b2NuYW1lPSJlcnJvci5zdmciPgogIDxzb2RpcG9kaTpuYW1lZHZpZXcKICAgICBwYWdl Y29sb3I9IiNmZmZmZmYiCiAgICAgYm9yZGVyY29sb3I9IiM2NjY2NjYiCiAgICAgYm9y ZGVyb3BhY2l0eT0iMSIKICAgICBvYmplY3R0b2xlcmFuY2U9IjEwIgogICAgIGdyaWR0 b2xlcmFuY2U9IjEwIgogICAgIGd1aWRldG9sZXJhbmNlPSIxMCIKICAgICBpbmtzY2Fw ZTpwYWdlb3BhY2l0eT0iMCIKICAgICBpbmtzY2FwZTpwYWdlc2hhZG93PSIyIgogICAg IGlua3NjYXBlOndpbmRvdy13aWR0aD0iMTkyMCIKICAgICBpbmtzY2FwZTp3aW5kb3ct aGVpZ2h0PSIxMDE3IgogICAgIGlkPSJuYW1lZHZpZXczNSIKICAgICBzaG93Z3JpZD0i ZmFsc2UiCiAgICAgaW5rc2NhcGU6em9vbT0iNi45NTMyMTY3IgogICAgIGlua3NjYXBl OmN4PSI2Ljg2NTIwNzQiCiAgICAgaW5rc2NhcGU6Y3k9IjQwLjEzMTg1NCIKICAgICBp bmtzY2FwZTp3aW5kb3cteD0iLTgiCiAgICAgaW5rc2NhcGU6d2luZG93LXk9Ii04Igog ICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjEiCiAgICAgaW5rc2NhcGU6Y3Vy cmVudC1sYXllcj0ic3ZnMiIgLz4KICA8ZGVmcwogICAgIGlkPSJkZWZzNCI+CiAgICA8 bGluZWFyR3JhZGllbnQKICAgICAgIG9zYjpwYWludD0ic29saWQiCiAgICAgICBpZD0i bGluZWFyR3JhZGllbnQ1MTAzIj4KICAgICAgPHN0b3AKICAgICAgICAgaWQ9InN0b3A1 MTA1IgogICAgICAgICBvZmZzZXQ9IjAiCiAgICAgICAgIHN0eWxlPSJzdG9wLWNvbG9y OiMwMDAwMDA7c3RvcC1vcGFjaXR5OjE7IiAvPgogICAgPC9saW5lYXJHcmFkaWVudD4K ICAgIDxsaW5lYXJHcmFkaWVudAogICAgICAgaWQ9ImxpbmVhckdyYWRpZW50NTA0MSI+ CiAgICAgIDxzdG9wCiAgICAgICAgIGlkPSJzdG9wNTA0MyIKICAgICAgICAgb2Zmc2V0 PSIwIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xvcjojZmZmZmZmO3N0b3Atb3BhY2l0 eToxOyIgLz4KICAgICAgPHN0b3AKICAgICAgICAgaWQ9InN0b3A1MDQ1IgogICAgICAg ICBvZmZzZXQ9IjEiCiAgICAgICAgIHN0eWxlPSJzdG9wLWNvbG9yOiNmZmZmZmY7c3Rv cC1vcGFjaXR5OjA7IiAvPgogICAgPC9saW5lYXJHcmFkaWVudD4KICAgIDxsaW5lYXJH cmFkaWVudAogICAgICAgaWQ9ImxpbmVhckdyYWRpZW50NDk4MSI+CiAgICAgIDxzdG9w CiAgICAgICAgIGlkPSJzdG9wNDk4MyIKICAgICAgICAgb2Zmc2V0PSIwIgogICAgICAg ICBzdHlsZT0ic3RvcC1jb2xvcjojOGUzMDMwO3N0b3Atb3BhY2l0eToxIiAvPgogICAg ICA8c3RvcAogICAgICAgICBpZD0ic3RvcDQ5ODUiCiAgICAgICAgIG9mZnNldD0iMSIK ICAgICAgICAgc3R5bGU9InN0b3AtY29sb3I6I2ZmMDAwMDtzdG9wLW9wYWNpdHk6MSIg Lz4KICAgIDwvbGluZWFyR3JhZGllbnQ+CiAgICA8bGluZWFyR3JhZGllbnQKICAgICAg IGlkPSJsaW5lYXJHcmFkaWVudDU3MzYiPgogICAgICA8c3RvcAogICAgICAgICBpZD0i c3RvcDU3MzgiCiAgICAgICAgIG9mZnNldD0iMCIKICAgICAgICAgc3R5bGU9InN0b3At Y29sb3I6I2MzYzNjMztzdG9wLW9wYWNpdHk6MSIgLz4KICAgICAgPHN0b3AKICAgICAg ICAgaWQ9InN0b3A1NzQwIgogICAgICAgICBvZmZzZXQ9IjEiCiAgICAgICAgIHN0eWxl PSJzdG9wLWNvbG9yOiNmZmZmZmY7c3RvcC1vcGFjaXR5OjE7IiAvPgogICAgPC9saW5l YXJHcmFkaWVudD4KICAgIDxsaW5lYXJHcmFkaWVudAogICAgICAgZ3JhZGllbnRVbml0 cz0idXNlclNwYWNlT25Vc2UiCiAgICAgICB5Mj0iOC4wNzgxMjUiCiAgICAgICB4Mj0i OS45NTcwMzEyIgogICAgICAgeTE9IjQxLjc2OTUzMSIKICAgICAgIHgxPSIzNy45NzY1 NjIiCiAgICAgICBpZD0ibGluZWFyR3JhZGllbnQ1NzQyIgogICAgICAgeGxpbms6aHJl Zj0iI2xpbmVhckdyYWRpZW50NTczNiIgLz4KICAgIDxsaW5lYXJHcmFkaWVudAogICAg ICAgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiCiAgICAgICB5Mj0iMTAxOS4w NDY2IgogICAgICAgeDI9IjI5LjQ4ODI4OSIKICAgICAgIHkxPSIxMDEyLjY5ODkiCiAg ICAgICB4MT0iMzYuNDAyMzUxIgogICAgICAgaWQ9ImxpbmVhckdyYWRpZW50NTc5Ni02 IgogICAgICAgeGxpbms6aHJlZj0iI2xpbmVhckdyYWRpZW50NTczNi04IiAvPgogICAg PGxpbmVhckdyYWRpZW50CiAgICAgICBpZD0ibGluZWFyR3JhZGllbnQ1NzM2LTgiPgog ICAgICA8c3RvcAogICAgICAgICBpZD0ic3RvcDU3MzgtOSIKICAgICAgICAgb2Zmc2V0 PSIwIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xvcjojYzNjM2MzO3N0b3Atb3BhY2l0 eToxIiAvPgogICAgICA8c3RvcAogICAgICAgICBpZD0ic3RvcDU3NDAtOCIKICAgICAg ICAgb2Zmc2V0PSIxIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xvcjojZmZmZmZmO3N0 b3Atb3BhY2l0eToxOyIgLz4KICAgIDwvbGluZWFyR3JhZGllbnQ+CiAgICA8bGluZWFy R3JhZGllbnQKICAgICAgIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIgogICAg ICAgeTI9IjguMDc4MTI1IgogICAgICAgeDI9IjkuOTU3MDMxMiIKICAgICAgIHkxPSI0 MS43Njk1MzEiCiAgICAgICB4MT0iMzcuOTc2NTYyIgogICAgICAgaWQ9ImxpbmVhckdy YWRpZW50NTc0Mi01IgogICAgICAgeGxpbms6aHJlZj0iI2xpbmVhckdyYWRpZW50NTcz Ni01IiAvPgogICAgPGxpbmVhckdyYWRpZW50CiAgICAgICBpZD0ibGluZWFyR3JhZGll bnQ1NzM2LTUiPgogICAgICA8c3RvcAogICAgICAgICBpZD0ic3RvcDU3MzgtOCIKICAg ICAgICAgb2Zmc2V0PSIwIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xvcjojYzNjM2Mz O3N0b3Atb3BhY2l0eToxIiAvPgogICAgICA8c3RvcAogICAgICAgICBpZD0ic3RvcDU3 NDAtNCIKICAgICAgICAgb2Zmc2V0PSIxIgogICAgICAgICBzdHlsZT0ic3RvcC1jb2xv cjojZmZmZmZmO3N0b3Atb3BhY2l0eToxOyIgLz4KICAgIDwvbGluZWFyR3JhZGllbnQ+ CiAgICA8cmFkaWFsR3JhZGllbnQKICAgICAgIGdyYWRpZW50VHJhbnNmb3JtPSJtYXRy aXgoMS40MzkyOTYyLC0wLjAxMzAyNzUyLDAuMDEwMzQ2NjYsMS4xNDMxMDU1LC0xOC4x NjI3NzMsLTExNDUuNjU1NCkiCiAgICAgICBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VP blVzZSIKICAgICAgIHI9IjE4Ljc1IgogICAgICAgZnk9IjEwMzcuMzMzNSIKICAgICAg IGZ4PSIyMi4yMzI3MzEiCiAgICAgICBjeT0iMTAzNy4zMzM1IgogICAgICAgY3g9IjIy LjIzMjczMSIKICAgICAgIGlkPSJyYWRpYWxHcmFkaWVudDQ5ODciCiAgICAgICB4bGlu azpocmVmPSIjbGluZWFyR3JhZGllbnQ0OTgxIiAvPgogICAgPGxpbmVhckdyYWRpZW50 CiAgICAgICBncmFkaWVudFRyYW5zZm9ybT0ibWF0cml4KDAsMS4xODU2ODg0LC0xLjE4 NTY4ODQsMCwxMjM4LjQyNjEsLTEuOTM2OSkiCiAgICAgICBncmFkaWVudFVuaXRzPSJ1 c2VyU3BhY2VPblVzZSIKICAgICAgIHkyPSIxMDI0LjIzNzIiCiAgICAgICB4Mj0iMjUu NDg4MjgxIgogICAgICAgeTE9IjEwMjQuMjM3MiIKICAgICAgIHgxPSIxLjA3NDIxODgi CiAgICAgICBpZD0ibGluZWFyR3JhZGllbnQ1MDQ3IgogICAgICAgeGxpbms6aHJlZj0i I2xpbmVhckdyYWRpZW50NTA0MSIgLz4KICAgIDxmaWx0ZXIKICAgICAgIGhlaWdodD0i MS4xMiIKICAgICAgIHk9Ii0wLjA2IgogICAgICAgd2lkdGg9IjEuMTIiCiAgICAgICB4 PSItMC4wNiIKICAgICAgIGlkPSJmaWx0ZXI1MTYyIgogICAgICAgc3R5bGU9ImNvbG9y LWludGVycG9sYXRpb24tZmlsdGVyczpzUkdCIj4KICAgICAgPGZlR2F1c3NpYW5CbHVy CiAgICAgICAgIGlkPSJmZUdhdXNzaWFuQmx1cjUxNjQiCiAgICAgICAgIHN0ZERldmlh dGlvbj0iMC45NzE2Nzk2OSIgLz4KICAgIDwvZmlsdGVyPgogICAgPGxpbmVhckdyYWRp ZW50CiAgICAgICB4bGluazpocmVmPSIjbGluZWFyR3JhZGllbnQ1MDQxIgogICAgICAg aWQ9ImxpbmVhckdyYWRpZW50NTA0Ny0wIgogICAgICAgeDE9IjEuMDc0MjE4OCIKICAg ICAgIHkxPSIxMDI0LjIzNzIiCiAgICAgICB4Mj0iMjUuNDg4MjgxIgogICAgICAgeTI9 IjEwMjQuMjM3MiIKICAgICAgIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIgog ICAgICAgZ3JhZGllbnRUcmFuc2Zvcm09Im1hdHJpeCgxLjE4NTY4ODQsMCwwLDEuMzMy ODM2NSwxOC4wNjMxLC0xMzg5LjE0MDcpIiAvPgogIDwvZGVmcz4KICA8bWV0YWRhdGEK ICAgICBpZD0ibWV0YWRhdGE3Ij4KICAgIDxyZGY6UkRGPgogICAgICA8Y2M6V29yawog ICAgICAgICByZGY6YWJvdXQ9IiI+CiAgICAgICAgPGRjOmZvcm1hdD5pbWFnZS9zdmcr eG1sPC9kYzpmb3JtYXQ+CiAgICAgICAgPGRjOnR5cGUKICAgICAgICAgICByZGY6cmVz b3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIiAvPgog ICAgICAgIDxkYzp0aXRsZT48L2RjOnRpdGxlPgogICAgICA8L2NjOldvcms+CiAgICA8 L3JkZjpSREY+CiAgPC9tZXRhZGF0YT4KICA8Y2lyY2xlCiAgICAgc3R5bGU9Im9wYWNp dHk6MTtmaWxsOiMwMDAwMDA7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmU7ZmlsdGVy OnVybCgjZmlsdGVyNTE2MikiCiAgICAgaWQ9InBhdGg0OTc5LTMiCiAgICAgY3g9IjI0 IgogICAgIGN5PSIxMDI4LjM2MjIiCiAgICAgcj0iMTkuNDMzNTk0IgogICAgIHRyYW5z Zm9ybT0ibWF0cml4KDEuMTI1NjI4MSwwLDAsMS4xMjU2MjgxLC0zLjAxNTA3NTQsLTEx MzMuNTUzNCkiIC8+CiAgPGNpcmNsZQogICAgIHN0eWxlPSJvcGFjaXR5OjE7ZmlsbDp1 cmwoI3JhZGlhbEdyYWRpZW50NDk4Nyk7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmUi CiAgICAgaWQ9InBhdGg0OTc5IgogICAgIGN4PSIyNCIKICAgICBjeT0iMjMuOTk5OTgz IgogICAgIHI9IjIxLjg3NSIgLz4KICA8ZwogICAgIHRyYW5zZm9ybT0ibWF0cml4KDEu MTE4MTIyNSwwLDAsMC45NDAxNzE5MywtMC42MDcwNTEyOSwtMTAzMC4zNzM0KSIKICAg ICBzdHlsZT0iZm9udC1zdHlsZTpub3JtYWw7Zm9udC13ZWlnaHQ6bm9ybWFsO2ZvbnQt c2l6ZTozMi4xOTYxNTU1NXB4O2xpbmUtaGVpZ2h0OjEyNSU7Zm9udC1mYW1pbHk6c2Fu cy1zZXJpZjtsZXR0ZXItc3BhY2luZzowcHg7d29yZC1zcGFjaW5nOjBweDtmaWxsOiNm ZmZmZmY7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOiNmZmZmZmY7c3Ryb2tlLXdpZHRoOjEu MjAwMDAwMDU7c3Ryb2tlLWxpbmVjYXA6cm91bmQ7c3Ryb2tlLWxpbmVqb2luOnJvdW5k O3N0cm9rZS1taXRlcmxpbWl0OjQ7c3Ryb2tlLWRhc2hhcnJheTpub25lO3N0cm9rZS1v cGFjaXR5OjEiCiAgICAgaWQ9InRleHQ1MDQ5Ij4KICAgIDxwYXRoCiAgICAgICBpbmtz Y2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgICAgZD0ibSAzMS45NzQ0NDgs MTEwOS43NjQ1IC04LjA4MDQ4MSwxMS41NzA1IDguMDY0NzYsMTEuODM3NyAtMy42MDAw NTgsMCAtNi4zODI2MzcsLTkuNjM2OCAtNi41Mzk4NDQsOS42MzY4IC0zLjM5NTY4OCww IDguMTU5MDg0LC0xMS42OTYyIC03Ljk3MDQzNSwtMTEuNzEyIDMuNTg0MzM4LDAgNi4z MDQwMzIsOS41MTExIDYuNDQ1NTIsLTkuNTExMSAzLjQxMTQwOSwwIHoiCiAgICAgICBz dHlsZT0iZmlsbDojZmZmZmZmO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTojZmZmZmZmO3N0 cm9rZS13aWR0aDoxLjIwMDAwMDA1O3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1s aW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDo0O3N0cm9rZS1kYXNoYXJyYXk6 bm9uZTtzdHJva2Utb3BhY2l0eToxIgogICAgICAgaWQ9InBhdGg1MTIwIiAvPgogIDwv Zz4KICA8cGF0aAogICAgIHN0eWxlPSJvcGFjaXR5OjE7ZmlsbDp1cmwoI2xpbmVhckdy YWRpZW50NTA0Nyk7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOm5vbmUiCiAgICAgZD0iTSAy NCAyLjY5NTMxMjUgQSAyMS4zMDUzNCAyMS4zMDUzNCAwIDAgMCAyLjY5NTMxMjUgMjQg QSAyMS4zMDUzNCAyMS4zMDUzNCAwIDAgMCA0LjI0ODA0NjkgMzEuOTU1MDc4IEEgMjMu OTQ5NDA4IDIxLjMwNTM0IDAgMCAxIDI0IDIyLjY5NTMxMiBBIDIzLjk0OTQwOCAyMS4z MDUzNCAwIDAgMSA0My43NDAyMzQgMzEuOTc2NTYyIEEgMjEuMzA1MzQgMjEuMzA1MzQg MCAwIDAgNDUuMzA0Njg4IDI0IEEgMjEuMzA1MzQgMjEuMzA1MzQgMCAwIDAgMjQgMi42 OTUzMTI1IHogIgogICAgIGlkPSJwYXRoNDk3OS0wIiAvPgo8L3N2Zz4K" width="16" height="16" align="absmiddle">&nbsp;<b>Fatal Error</b><br>'+content+'</span></div>';
	
	document.body.appendChild(backcover);
	document.body.appendChild(mcover);
	
	if (typeof shell.sound !== "undefined") {
		shell.sound.system('data:audio/mpeg;base64,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');
	}
}

shell.window = function(title, id='window', x=32, y=32, width=100, height=100, content, icon, background='transparent') {
	return shell.window.create.html(title, id, x, y, width, height, content, icon, background);
}

shell.window.create = function(title, id='window', x=32, y=32, width=100, height=100, content, icon, background='transparent') {
	return shell.window.create.html(title, id, x, y, width, height, content, icon, background);
}

shell.window.create.page = function(title, id='window', x=32, y=32, width=100, height=100, page, icon, background='transparent') {
	/*var newWindow = new JSWindow(title, id, x, y, width, height, '[page]'+page, icon, background);
	return shell.window.getbyId(newWindow.id);*/
	return task.create(id, {title:title, x:x, y:y, width:width, height:height, location:page, icon:icon});
}

shell.window.create.html = function(title, id='window', x=32, y=32, width=100, height=100, html, icon, background='transparent') {
	var newWindow = new JSWindow(title, id, x, y, width, height, '[html]'+html, icon, background);
	return shell.window.getbyId(newWindow.id);
}

shell.window.get = function(id) {
	return shell.window.getById(id);
}

shell.window.getById = function(id) {
	var getWindow = document.getElementById(id);
	var window = new Object;
	window.id = getWindow.id;
	window.title = getWindow.getAttribute('data-title');
	window.sendToBack = function() {
		document.getElementById(window.id).jsWindow.sendToBack();
	};
	window.bringToFront = function() {
		document.getElementById(window.id).jsWindow.bringToFront();
	};
	window.close = function() {
		document.getElementById(window.id).jsWindow.close();
	};
	window.minimize = function() {
		document.getElementById(window.id).jsWindow.minimize();
	};
	window.maximize = function() {
		document.getElementById(window.id).jsWindow.maximize();
	};
	return window;
}

shell.checkSystemConnectivity = function() {
	var xhr = new XMLHttpRequest();
	var file = '<?php echo DOCUMENT_ROOT.'system/components/syscheck.php'; ?>';
	var randomNum = Math.round(Math.random() * 10000);
	 
	xhr.open('HEAD', file + "?rand=" + randomNum, true);

	xhr.onreadystatechange = function() {
		if (!(xhr.status >= 200 && xhr.status < 304)) {
			shell.fatal('The system cannot connect to the back end. (0d100)');
			clearInterval(shell.checkSystemConnectivityInterval);
		}
	}
	 
	try {
		xhr.send(null);
	} catch (e) {
		console.log(e);
		return false;
	}
	return true;
}

shell.checkSystemConnectivityInterval = setInterval(function() {if (!shell.checkSystemConnectivity()) { shell.fatal('The system cannot connect to the back end. (0d100)');clearInterval(shell.checkSystemConnectivityInterval);}}, 10000);

shell.notification = function(title='Notification',content='This is a notification.',icon='<?php echo FLY_ICONS_URL; ?>message.svg',sound='notification') {
	return shell.notification.create(title,content,icon,sound);
}

shell.notification.init = function() {
	shell.notification.block = document.createElement('div');
	var block = shell.notification.block;
	block.style.position = 'fixed';
	if (typeof ui !== "undefined") {
		if (typeof ui.toolbar !== "undefined") {
			block.style.top = ui.toolbar.offsetHeight+'px';
		}
	} else {
		block.style.top = '0px';
	}
	block.style.right = '0px';
	block.style.bottom = '0px';
	block.style.width = '300px';
	block.style.padding = '4px';
	block.style.marginTop = '0px';
	block.style.overflowY = 'auto';
	block.style.overflowX = 'visible';
	block.style.pointerEvents = 'none';
	document.body.appendChild(shell.notification.block);
	
}

shell.notification.create = function(title='Notification',content='This is a notification.',icon='<?php echo FLY_ICONS_URL; ?>message.svg',sound='notification') {
	var now     = new Date(); 
	var year    = now.getFullYear();
	var month   = now.getMonth()+1; 
	var day     = now.getDate();
	var hour    = now.getHours();
	var minute  = now.getMinutes();
	var second  = now.getSeconds(); 
	var millisecond  = now.getMilliseconds(); 
	if(month.toString().length == 1) {
		var month = '0'+month;
	}
	if(day.toString().length == 1) {
		var day = '0'+day;
	}   
	if(hour.toString().length == 1) {
		var hour = '0'+hour;
	}
	if(minute.toString().length == 1) {
		var minute = '0'+minute;
	}
	if(second.toString().length == 1) {
		var second = '0'+second;
	}   
	if (hour > 12) {
		var date = (month/1)+'/'+(day/1)+'/'+year+' '+(hour-12)+':'+minute+' PM';
	} else if (hour == 0) {
		var date = (month/1)+'/'+(day/1)+'/'+year+' '+12+':'+minute+' AM';
	} else if (hour == 12) {
		var date = (month/1)+'/'+(day/1)+'/'+year+' '+(hour/1)+':'+minute+' PM';
	} else {
		var date = (month/1)+'/'+(day/1)+'/'+year+' '+(hour/1)+':'+minute+' AM';
	}
	var id = 'notification-'+year+''+month+''+day+''+hour+''+minute+''+second+''+millisecond;

	var block = shell.notification.block;
	block.innerHTML = '<div onmouseenter="this.style.opacity = \'1\';" id="'+id+'" style="pointer-events:auto;box-sizing:border-box;margin-bottom:6px;transition:opacity .2s linear;opacity:1;width:90%;float:right;" class="FlyUiControlNonScaled"><div class="FlyUiNoSelect FlyUiText" style="background:#ffffff;width:100%;padding:3px;box-sizing:border-box;float:left;"><img style="width:24px;height:24px;vertical-align:middle;float:right;" src="'+icon+'"><b>'+title+'</b><br><span style="font-size:14px;">'+content+'</span></div><div class="FlyUiTextHighlightControl" style="font-size:12px;padding-top:5px;float:left;width:calc(100% - 18px);">'+date+'</div><div class="FlyUiTextHoverControl" style="float:right;display:inline-block;padding-right:2px;" onclick="shell.notification.remove(\''+id+'\');">𐌗</div></div>' + block.innerHTML;
	shell.sound(sound);
	setTimeout(function(){document.getElementById(id).style.opacity = '.7';document.getElementById(id).onmouseleave = function() {this.style.opacity = '.7';};},6000);

	return id;
}

shell.notification.remove = function(id) {
	shell.notification.block.removeChild(document.getElementById(id));
}


shell.security = function() {};

shell.security.command = function(cmd) {
	new JSWindow('Fly Security - Run Command', 'security', ((window.innerWidth/2)-258), ((window.innerHeight/2)-154), 500, 220, '[page]/system/components/security.php?cmd='+encodeURIComponent(window.btoa(cmd)), 'system/resources/icons/lock.svg', 'transparent');
}
/*
[window].close()
[window].minimize()
[window].sendToBack()
[window].bringToFront()
[window].id
*/
</script>