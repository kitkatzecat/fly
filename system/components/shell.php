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
	
    request.open("GET", '/system/components/cmd.php?time='+dateTime+'&cmd='+encodeURIComponent(cmd), false);
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
	return task.create('public', {title:title, name:title, x:((window.innerWidth/2)-258), y:((window.innerHeight/2)-154), width:500, height:220, location:'/system/components/dialog.php?type=message&content='+encodeURIComponent(window.btoa(content))+'&message='+encodeURIComponent(window.btoa(msg))+'&icon='+encodeURIComponent(window.btoa(icon)), icon:icon});
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
		shell.sound.system('error');
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
	 
	xhr.open('HEAD', file + "?rand=" + randomNum, false);
	 
	try {
		xhr.send();
		 
		if (xhr.status >= 200 && xhr.status < 304) {
			return true;
		} else {
			return false;
		}
	} catch (e) {
		return false;
	}
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