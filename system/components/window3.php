<?php
//error_reporting(0);

if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
if (!FlyIncludeCheck('FLY.FONTS')) {
	include 'Fly.Fonts.php';
}
if (!FlyIncludeCheck('FLY.THEME')) {
	include 'Fly.Theme.php';
}

FlyFontLoad('Symbols');

$window_timeout = '1000';

$themebase = FlyLoadThemeFile($_FLY['RESOURCE']['PATH']['OS'].'base.thm');
$themebase[0] = json_decode($themebase[0],true);
$theme3 = FlyLoadThemeFile();
$theme3[0] = json_decode($theme3[0],true);
$theme3 = array_replace_recursive($themebase,$theme3);

$window_move = boolval($theme3[0]['style']['window']['background_movement']);

error_reporting(E_ALL);
?>
<style>
@keyframes FlyWindowMsgShow {
	from {opacity: 0;}
	to {opacity: 1;}
}
@keyframes FlyWindowMsgHide {
	from {opacity: 1;}
	to {opacity: 0;}
}
</style>
<script type="text/javascript">

var task = function() {};

task.init = function() {
	task.index = 3;
	task.autoPos = 0;
}

task.create = function(id='public', attributes={title:'Untitled', name:'Untitled', icon:'', x:'auto', y:'auto', width:320, height:240, location:'/system/components/document-otf.php?content=PGRpdiBjbGFzcz0iRmx5VWlUZXh0IiBzdHlsZT0icG9zaXRpb246YWJzb2x1dGU7dG9wOjBweDtsZWZ0OjBweDtyaWdodDowcHg7Ym90dG9tOjBweDtiYWNrZ3JvdW5kOiNmZmZmZmY7cGFkZGluZzo4cHg7Ij5ObyBjb250ZW50IHByb3ZpZGVkPC9zcGFuPg==', expand:false, minimize:true, close:true, resize:false, background:false, minheight:60, minwidth:100, maxheight:false, maxwidth:false, maxinitheight:false, maxinitwidth:false}) {
	
	// Init new task
	task.index += 1;
	
	// Create window object
	var frame = document.createElement('div');
	frame.window = {};
	frame.window.isLoaded = false;
	frame.window.composition = {};
	
	if (typeof attributes.resize == 'undefined') {
		attributes.resize = false;
	}
	if (typeof attributes.background == 'undefined') {
		attributes.background = false;
	}
	
	if (typeof attributes.minheight == 'undefined' || attributes.minheight == false) {
		attributes.minheight = 60;
	}
	if (typeof attributes.minwidth == 'undefined' || attributes.minwidth == false) {
		attributes.minwidth = 200;
	}

	if (typeof attributes.maxheight == 'undefined') {
		attributes.maxheight = false;
	}
	if (typeof attributes.maxwidth == 'undefined') {
		attributes.maxwidth = false;
	}

	if (typeof attributes.maxinitheight == 'undefined') {
		attributes.maxinitheight = false;
	}
	if (typeof attributes.maxinitwidth == 'undefined') {
		attributes.maxinitwidth = false;
	}
	
	frame.window.isBackground = attributes.background;
	frame.window.isMinimized = false;
	frame.window.isMaximized = true;
	frame.window.isResizable = attributes.resize;
	frame.window.isExpand = false;
	frame.window.isActive = true;
	frame.window.isInactive = false;
	frame.window.activeClass = 'FlyWindowActive';
	frame.window.inactiveClass = 'FlyWindowInactive';
	
	// Style window object
	frame.className = 'FlyWindow '+frame.window.activeClass;
	frame.style.position = 'absolute';
	frame.style.width = 'auto';
	frame.style.minWidth = '100px';
	frame.style.height = 'auto';
	frame.style.minHeight = '64px';
	frame.style.visibility = 'hidden';
	frame.style.paddingTop = '0px';
	
	frame.window.initial = attributes;
	
	frame.window.composition.minimizeObj = false;
	
	// Make sure buttons are visible
	if (typeof attributes.close == 'undefined') {
		attributes.close = true;
	}
	if (typeof attributes.minimize == 'undefined') {
		attributes.minimize = true;
	}
	if (typeof attributes.expand == 'undefined') {
		attributes.expand = false;
	}
	
	// Set window title
	frame.setAttribute('window-title', attributes.title);
	frame.window.title = attributes.title;
	
	// Set application name
	if (typeof attributes.name == 'undefined') {
		attributes.name = attributes.title;
	}
	frame.setAttribute('window-name', attributes.name);
	frame.window.name = attributes.name;
	
	// Set window ID
	var m_now     = new Date(); 
	var m_year    = m_now.getFullYear();
	var m_month   = m_now.getMonth()+1; 
	var m_day     = m_now.getDate();
	var m_hour    = m_now.getHours();
	var m_minute  = m_now.getMinutes();
	var m_second  = m_now.getSeconds(); 
	var m_millisecond  = m_now.getMilliseconds(); 
	if(m_month.toString().length == 1) {
		var m_month = '0'+m_month;
	}
	if(m_day.toString().length == 1) {
		var m_day = '0'+m_day;
	}   
	if(m_hour.toString().length == 1) {
		var m_hour = '0'+m_hour;
	}
	if(m_minute.toString().length == 1) {
		var m_minute = '0'+m_minute;
	}
	if(m_second.toString().length == 1) {
		var m_second = '0'+m_second;
	}   
	var dateTime = m_year+''+m_month+''+m_day+''+m_hour+''+m_minute+''+m_second+''+m_millisecond;
	
	if (id == '') {
		id == 'public';
	}
	
	frame.id = id+'-'+dateTime;
	frame.window.id = id;
	frame.window.uniqueid = id+'-'+dateTime;
	frame.setAttribute('window-id',id);
	frame.setAttribute('window-uniqueid',id+'-'+dateTime);
	
	// Set window focus
	frame.window.focus = frame.id;
	
	// Calculate window size
	if ((attributes.width+"").indexOf('%') != -1 && (attributes.width+"").indexOf('%') == attributes.width.length-1 ) {
		attributes.width = (parseInt(attributes.width)/100)*window.innerWidth;
		if (attributes.maxinitwidth) {
			if (attributes.width > attributes.maxinitwidth) {
				attributes.width = attributes.maxinitwidth;
			}
			if (attributes.width < attributes.minwidth) {
				attributes.width = attributes.minwidth;
			}
		}
	} else {
		attributes.width = parseInt(attributes.width);
	}
	if ((attributes.height+"").indexOf('%') != -1 && (attributes.height+"").indexOf('%') == attributes.height.length-1 ) {
		attributes.height = (parseInt(attributes.height)/100)*window.innerHeight;
		if (attributes.maxinitheight) {
			if (attributes.height > attributes.maxinitheight) {
				attributes.height = attributes.maxinitheight;
			}
			if (attributes.height < attributes.minheight) {
				attributes.height = attributes.minheight;
			}
		}
	} else {
		attributes.height = parseInt(attributes.height);
	}

	// Set window position
	if (attributes.x == 'auto' || attributes.y == 'auto' || attributes.x == '' || attributes.y == '') {
		task.autoPos = task.autoPos+32;
		if ((task.autoPos+(attributes.height+140)) > window.innerHeight) {
			task.autoPos = 32;
			attributes.x = task.autoPos;
			attributes.y = task.autoPos;
		} else {
			attributes.x = task.autoPos;
			attributes.y = task.autoPos;
		}
	}
	if (attributes.x == 'center') {
		attributes.x = (window.innerWidth/2)-(attributes.width/2);
	}
	if (attributes.y == 'center') {
		attributes.y = (window.innerHeight/2)-(attributes.height/2);
	}
	frame.style.position = 'absolute';
	frame.style.left = attributes.x + 'px';
	frame.style.top = attributes.y + 'px';
	
	// Disable system context menu on frame
	frame.oncontextmenu = function(e) {
		e.preventDefault();
		return false;
	}
	
	// Set icon
	if (!attributes.icon == '') {
		frame.window.composition.icon = '<img src="'+attributes.icon+'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;"> ';
		frame.window.icon = attributes.icon;
	} else {
		frame.window.composition.icon = '';
		frame.window.icon = '';
	}
	
	// Define content
	if (attributes.location.indexOf('?') > -1) {
		frame.window.location = attributes.location+'&Fly_Id='+frame.id;
	} else {
		frame.window.location = attributes.location+'?Fly_Id='+frame.id;
	}
	
	// Window functions
	
	// Show message
	frame.window.showMessage = function(msg,duration=8) {
		var msgbox = document.createElement('div');
		msgbox.style.position = 'absolute';
		msgbox.style.zIndex = '2';
		msgbox.className = 'FlyUiControlNonScaled';
		msgbox.style.bottom = '25%';
		msgbox.style.left = '50%';
		msgbox.style.transform = 'translateX(-50%)';
		msgbox.style.maxWidth = '100%';
		msgbox.style.paddingTop = '8px';
		msgbox.style.paddingBottom = '8px';
		msgbox.style.paddingLeft = '12px';
		msgbox.style.paddingRight = '12px';
		msgbox.style.textAlign = 'center';
		msgbox.style.pointerEvents = 'none';
		msgbox.style.animation = 'FlyWindowMsgShow 0.25s linear 0s 1';
		
		msgbox.innerHTML = '<span class="FlyUiTextHighlightControl">'+msg+'</span>';
		
		setTimeout(function() {
			msgbox.style.animation = 'FlyWindowMsgHide 0.5s linear 0s 1';
			
			setTimeout(function() {
				msgbox.remove();
			},500);
		},(duration*1000));
		
		frame.window.composition.content.appendChild(msgbox);
	}
	
	// Close window
	frame.window.close = function() {
		try {
			frame.window.content.contentWindow.Fly.window.onclose();
		} catch(err) {
			frame.window.forceClose();
		}
	}
	frame.window.forceClose = function() {
		if (frame.window.isMinimized) {
			frame.window.composition.minimizeObj.remove();
			frame.window.clear();
			frame.parentNode.removeChild(frame);
		} else {
			if (frame.window.isActive) {
				try {
					ui.toolbar.setActiveApplication('Desktop');
				} catch(err) {}
			}
			if (frame.window.isExpand) {
				ui.toolbar.windowRestored();
			}
			frame.window.checkIfBehind = function() {};
			frame.style.zIndex = '99999999';
			frame.style.pointerEvents = 'none';
			<?php
				echo 'frame.style.animation = "FlyWindowCloseAnimation '.$theme3[0]['style']['window']['animations']['close']['length'].'s '.$theme3[0]['style']['window']['animations']['close']['timing'].' '.$theme3[0]['style']['window']['animations']['close']['delay'].'s '.$theme3[0]['style']['window']['animations']['close']['repeat'].'";';

				echo 'setTimeout(function() {frame.window.clear();frame.parentNode.removeChild(frame);}, '.((((float)$theme3[0]['style']['window']['animations']['close']['length']*(int)$theme3[0]['style']['window']['animations']['close']['repeat'])+(float)$theme3[0]['style']['window']['animations']['close']['delay'])*1000).');';
			?>
		}
	}
	frame.window.clear = function() {
		try {
			frame.window.content.contentWindow.document.write('');
		} catch(e) {}
		frame.window.content.remove();
	}
	
	// Minimize window
	frame.window.minimize = function() {
		if (frame.window.isMinimized == false) {
			<?php
				echo 'frame.style.animation = "FlyWindowMinimizeAnimation '.$theme3[0]['style']['window']['animations']['minimize']['length'].'s '.$theme3[0]['style']['window']['animations']['minimize']['timing'].' '.$theme3[0]['style']['window']['animations']['minimize']['delay'].'s '.$theme3[0]['style']['window']['animations']['minimize']['repeat'].'";';

				echo 'setTimeout(frame.window.minimized, '.((((float)$theme3[0]['style']['window']['animations']['minimize']['length']*(int)$theme3[0]['style']['window']['animations']['minimize']['repeat'])+(float)$theme3[0]['style']['window']['animations']['minimize']['delay'])*1000).');';
			?>
		}
	}
	
	// Window minimized
	frame.window.minimized = function() {
		frame.window.sendToBack();
		frame.style.animation = '';
		frame.style.display = 'none';
		frame.style.position = 'static';
		try {
			frame.window.composition.minimizeObj = ui.tray.add(frame);
			frame.window.isMinimized = true;
			frame.window.isActive = false;
			try {
				ui.toolbar.setActiveApplication('Desktop');
			} catch(err) {}
		} catch(err) {
			frame.style.display = 'block';
			frame.style.position = 'absolute';
			frame.window.bringToFront();
			shell.dialog('Unable to minimize','"'+frame.window.title+'" could not be minimized because the UI has not been initialized.');
			frame.window.isMinimized = false;
		}
	}
	
	// Maximize window
	frame.window.maximize = function() {
		if (frame.window.isMinimized == true) {
			frame.style.position = 'absolute';
			frame.style.display = 'block';
			frame.window.isMinimized = false;
			
			frame.window.composition.minimizeObj.remove();
			frame.window.composition.minimizeObj = false;
			
			<?php
				echo 'frame.style.animation = "FlyWindowMinimizeAnimation '.$theme3[0]['style']['window']['animations']['minimize']['length'].'s '.$theme3[0]['style']['window']['animations']['minimize']['timing'].' '.$theme3[0]['style']['window']['animations']['minimize']['delay'].'s '.$theme3[0]['style']['window']['animations']['minimize']['repeat'].' reverse";';
				echo 'setTimeout(function() {frame.window.bringToFront();frame.style.animation="";}, '.((((float)$theme3[0]['style']['window']['animations']['minimize']['length']*(int)$theme3[0]['style']['window']['animations']['minimize']['repeat'])+(float)$theme3[0]['style']['window']['animations']['minimize']['delay'])*1000).');';
			?>

			frame.window.bringToFront();
		}
	}
	
	// Expand window
	frame.window.expand = function() {
		if (frame.window.isExpand == false) {
			frame.window.composition.saveX = frame.style.left;
			frame.window.composition.saveY = frame.style.top;
			frame.window.composition.saveW = frame.window.content.style.width;
			frame.window.composition.saveH = frame.window.content.style.height;
			frame.window.composition.saveTb = frame.window.composition.titleRow.status.visible;
				
			try {
				var expandWindows = document.getElementsByClassName('FlyWindowExpand');
				for (i = 0; i < expandWindows.length; i++) { 
					expandWindows[i].window.restore();
				}
			} catch(err) {}

			frame.className = 'FlyWindow FlyWindowExpand';
			
			frame.window.composition.content.style.width = '100%';
			frame.window.composition.content.style.height = '100%';
			
			frame.window.content.style.width = '100%';
			frame.window.content.style.height = '100%';
			
			frame.window.isExpand = true;
			
			try {
				ui.toolbar.windowExpanded(frame);
			} catch(err) {}
			
			frame.window.composition.expand.onclick = frame.window.restore;
			
			frame.window.hideTitlebar();
		}
	}
	
	// Restore window
	frame.window.restore = function() {
		if (frame.window.isExpand == true) {
			frame.className = 'FlyWindow '+frame.window.activeClass;

			frame.style.left = frame.window.composition.saveX;
			frame.style.top = frame.window.composition.saveY;
			frame.window.content.style.width = frame.window.composition.saveW;
			frame.window.content.style.height = frame.window.composition.saveH;
			
			frame.window.isExpand = false;
			
			try{
				ui.toolbar.windowRestored();
			} catch(err) {}
			
			frame.window.composition.expand.onclick = frame.window.expand;

			if (frame.window.composition.saveTb) {
				frame.window.showTitlebar();
			}
			
			frame.window.composition.title.adjustWidth();
		}
	}
	
	// Flash window
	frame.window.flash = function() {
		if (frame.window.isActive) {
			if (!frame.window.isMinimized) {
				frame.className = 'FlyWindow '+frame.window.inactiveClass;
				frame.window.composition.titleRow.className = 'FlyWindowTitleRowInactive';
				frame.window.composition.title.className = 'FlyWindowTitleInactive';
				frame.window.composition.close.className = 'FlyWindowButtonInactive';
				frame.window.composition.expand.className = 'FlyWindowButtonInactive';
				frame.window.composition.minimize.className = 'FlyWindowButtonInactive';
				
				setTimeout(function() {
					frame.className = 'FlyWindow '+frame.window.activeClass;
					frame.window.composition.titleRow.className = 'FlyWindowTitleRowActive';
					frame.window.composition.title.className = 'FlyWindowTitleActive';
					frame.window.composition.close.className = 'FlyWindowButtonActive';
					frame.window.composition.expand.className = 'FlyWindowButtonActive';
					frame.window.composition.minimize.className = 'FlyWindowButtonActive';
				},250);
			}
		}
	}
	
	// Send window to back
	frame.window.sendToBack = function() {
		// if not minimized
		if (!frame.window.isMinimized) {
			frame.style.zIndex = 1;
			frame.window.composition.content.style.pointerEvents = 'none';
			if (!frame.window.isExpand) {
				frame.className = 'FlyWindow '+frame.window.inactiveClass;
			}
			frame.window.composition.titleRow.className = 'FlyWindowTitleRowInactive';
			frame.window.composition.title.className = 'FlyWindowTitleInactive';
			frame.window.composition.minimize.className = 'FlyWindowButtonInactive';
			frame.window.composition.expand.className = 'FlyWindowButtonInactive';
			frame.window.composition.close.className = 'FlyWindowButtonInactive';
			
			if (frame.window.isActive) {
				try {
					ui.toolbar.setActiveApplication('Desktop');
				} catch(err) {}
			}
			
			frame.window.isActive = false;
			frame.window.isInactive = true;
		}
	}
	
	// Bring window to front
	frame.window.bringToFront = function() {
		if (!frame.window.isMinimized) {
		
			// if window has its focus
			if (frame.window.focus == frame.id) {
			
				try {
					if (!frame.window.isBackground) {
						ui.toolbar.setActiveApplication(frame);
					} else {
						ui.toolbar.setActiveApplication('Desktop');
					}
				} catch(err) {}
				
				if (frame.style.zIndex < task.index ) {
					task.index += 1;
					frame.style.zIndex = task.index;
				
					var allWindows = document.getElementsByClassName('FlyWindow');
					for (i = 0; i < allWindows.length; i++) { 
						allWindows[i].window.checkIfBehind();
					}
					
					frame.window.composition.content.style.pointerEvents = "auto";
					if (!frame.window.isExpand) {
						frame.className = 'FlyWindow '+frame.window.activeClass;
					}
					frame.window.composition.titleRow.className = "FlyWindowTitleRowActive";
					frame.window.composition.title.className = "FlyWindowTitleActive";
					frame.window.composition.minimize.className = "FlyWindowButtonActive";
					frame.window.composition.expand.className = "FlyWindowButtonActive";
					frame.window.composition.close.className = "FlyWindowButtonActive";
					
					frame.window.isInactive = false;
					frame.window.isActive = true;
					
					setTimeout(function(){frame.window.content.contentWindow.focus();},10);
				}
			} else {
				var focusWindow = document.getElementById(frame.window.focus);
				if (focusWindow == null) {
					frame.window.resetFocus();
					frame.window.bringToFront();
				} else {
					if (focusWindow.window.isMinimized) {
						try {
							focusWindow.window.maximize();
							focusWindow.window.flash();
							try {
								shell.sound.system('alert');
							} catch(err) {}
						} catch(err) {
							frame.window.resetFocus();
							frame.window.bringToFront();
						}
					} else {
						try {
							focusWindow.window.bringToFront();
							focusWindow.window.flash();
							try {
								shell.sound.system('alert');
							} catch(err) {}
						}
						catch(err) {
							frame.window.resetFocus();
							frame.window.bringToFront();
						}
					}
				}
			}
		} else {
			frame.window.maximize();
			frame.window.content.contentWindow.focus();
			frame.window.isMinimized = false;
			frame.window.isMaximized = true;
			frame.window.isActive = true;
			frame.window.isInactive = false;
		}
	}
	
	// Check if window is behind
	frame.window.checkIfBehind = function() {
		// if not minimized
		if (!frame.window.isMinimized) {
		
			if (frame.style.zIndex < task.index || frame.window.focus !== frame.id) {
				frame.window.composition.content.style.pointerEvents = "none";
				if (!frame.window.isExpand) {
					frame.className = 'FlyWindow '+frame.window.inactiveClass;
				}
				frame.window.composition.titleRow.className = "FlyWindowTitleRowInactive";
				frame.window.composition.title.className = "FlyWindowTitleInactive";
				frame.window.composition.minimize.className = "FlyWindowButtonInactive";
				frame.window.composition.expand.className = "FlyWindowButtonInactive";
				frame.window.composition.close.className = "FlyWindowButtonInactive";
				
				frame.window.isActive = false;
				frame.window.isInactive = true;
			}
		
		}
	}
	
	// Set window title
	frame.window.setTitle = function(title) {
		frame.window.title = title;
		frame.setAttribute('window-title', title);
		
		var div = document.createElement("div");
		div.innerText = div.textContent = frame.window.title;
		var escapedTitle = div.innerHTML;
		
		frame.window.composition.title.innerHTML = frame.window.composition.icon+escapedTitle;
		
		if (frame.style.zIndex == task.index) {
			try {
				ui.toolbar.setActiveApplication(frame);
			} catch(err) {}
		}
	}

	// Set application name
	frame.window.setName = function(name) {
		frame.window.name = name;
		frame.setAttribute('window-name', name);
	}
	
	// Set window icon
	frame.window.setIcon = function(icon) {
		if (!icon=="") {
			frame.window.composition.icon = '<img src="'+icon+'" style="width:16px;height:16px;vertical-align:middle;pointer-events:none;"> ';
			frame.window.icon = icon;
		} else {
			frame.window.composition.icon = '';
			frame.window.icon = '';
		}
		
		frame.window.composition.title.innerHTML = frame.window.composition.icon+frame.window.title;
	}
	
	// Hide window title bar
	frame.window.hideTitlebar = function() {
		frame.window.composition.titleRow.status.hide();
	}
	
	// Show window title bar
	frame.window.showTitlebar = function() {
		frame.window.composition.titleRow.status.show();
	}
	
	// Hide window buttons
	frame.window.hideButtons = function() {
		frame.window.composition.expand.status.hide();
		frame.window.composition.minimize.status.hide();
		frame.window.composition.close.status.hide();
	}
	
	// Show window buttons
	frame.window.showButtons = function() {
		frame.window.composition.expand.status.show();
		frame.window.composition.minimize.status.show();
		frame.window.composition.close.status.show();
	}
	
	// Hide the window's border
	frame.window.hideBorder = function() {
		frame.window.composition.saveTb = frame.window.composition.titleRow.status.visible;
		frame.window.hideTitlebar();
		
		frame.className = 'FlyWindow FlyWindowTransparent';
		
		frame.window.inactiveClass = 'FlyWindowTransparent';
		frame.window.activeClass = 'FlyWindowTransparent';
	}
	
	// Show the window's border
	frame.window.showBorder = function() {
		if (frame.window.composition.saveTb) {
			frame.window.showTitlebar();
		}

		frame.window.inactiveClass = 'FlyWindowInactive';
		frame.window.activeClass = 'FlyWindowActive';
		
		if (frame.window.zIndex >= task.index) {
			frame.className = 'FlyWindow '+frame.window.activeClass;
		} else {
			frame.className = 'FlyWindow '+frame.window.inactiveClass;
		}
	}
	
	// Set window position
	frame.window.setPosition = function(x,y) {
		if (!frame.window.isExpand) {
				frame.style.left = x + 'px';
				frame.style.top = y + 'px';
			<?php
			if ($window_move) {
				echo 'frame.style.backgroundPosition = "-"+(frame.style.left)+" -"+(frame.style.top);';
			}
			?>
		} else {
			frame.window.composition.saveX = x+'px';
			frame.window.composition.saveY = y+'px';
		}
	}
	
	// Set window size
	frame.window.setSize = function(width,height) {
		if (frame.window.isExpand) {
			frame.window.restore();
		}
		
		if (attributes.minwidth) {
			if (width < attributes.minwidth) {
				width = attributes.minwidth;
			}
		}
		if (attributes.maxwidth) {
			if (width > attributes.maxwidth) {
				width = attributes.maxwidth;
			}
		}
		if (attributes.minheight) {
			if (height < attributes.minheight) {
				height = attributes.minheight;
			}
		}
		if (attributes.maxheight) {
			if (height > attributes.maxheight) {
				height = attributes.maxheight;
			}
		}

		frame.window.content.style.width = width+'px';
		frame.window.content.style.height = height+'px';
	
		frame.window.composition.title.adjustWidth();
	}
	
	// Set focus
	frame.window.setFocus = function(id) {
		var focusWindow = document.getElementById(id);
		if (focusWindow !== null) {
			frame.window.focus = id;
			frame.window.composition.close.onclick = function() {};
			frame.window.composition.minimize.onclick = function() {};
			frame.window.composition.expand.onclick = function() {};
			if (frame.style.zIndex == task.index) {
				try {
					focusWindow.bringToFront();
				}
				catch(err) {
					frame.window.focus.reset();
				}
			}
		}
	}
	
	// Reset focus
	frame.window.resetFocus = function() {
		frame.window.composition.close.onclick = frame.window.close;
		frame.window.composition.minimize.onclick = frame.window.minimize;
		frame.window.composition.expand.onclick = frame.window.expand;
		frame.window.focus = frame.id;
	}
	
	// Window movement
	frame.window.onMouseDown = function() {
		frame.window.composition.bDown = true;
		
		frame.window.composition.cover = document.createElement("div");
		frame.window.composition.cover.style.position = "fixed";
		frame.window.composition.cover.style.top = '0px';
		frame.window.composition.cover.style.left = '0px';
		frame.window.composition.cover.style.bottom = '0px';
		frame.window.composition.cover.style.right = '0px';
		frame.window.composition.cover.style.zIndex = '99999999';
		frame.window.composition.cover.style.backgroundColor = "transparent";
		frame.window.composition.cover.style.cursor = "move";
		document.body.appendChild(frame.window.composition.cover);
		
		frame.window.composition.saveMouseMove = document.body.onmousemove;
		frame.window.composition.saveMouseUp = document.body.onmouseup;
		
		document.body.onmousemove = frame.window.bodyOnMouseMove;
		document.body.onmouseup = function(e) {
			frame.window.onMouseUp(e);
			if (typeof ui !== 'undefined') {
				try {
					if (e.clientX > (window.innerWidth-10) && frame.window.isResizable) {
						ui.toolbar.snap.window(frame,'right');
					}
					if (e.clientX < 10 && frame.window.isResizable) {
						ui.toolbar.snap.window(frame,'left');
					}
					if (e.clientX > 10  && e.clientX < (window.innerWidth-10) && e.clientY < ui.toolbar.offsetHeight && frame.window.isResizable) {
						frame.window.expand();
					}
				} catch(e) {console.log(e);}
			}
		};
	}
	frame.window.bodyOnMouseMove = function(evt) {
		var e = frame.event ? frame.event : evt;
		frame.window.onMouseMove(evt);
	}
	frame.window.onMouseMove = function(evt) {
		if ( frame.window.composition.bDown )
		{		
			if ( !frame.window.isMinimized ) {
				frame.window.composition.dx = parseInt(frame.style.left, 10) - evt.clientX;
				frame.window.composition.dy = parseInt(frame.style.top, 10) - evt.clientY;
				frame.window.composition.bDown = false;
			}
		} else {
			frame.style.left = Math.max((frame.window.composition.dx + evt.clientX),32-(frame.offsetWidth)) + 'px';
			frame.style.top = Math.max((frame.window.composition.dy + evt.clientY),0) + 'px';
	<?php
	if ($window_move) {
		echo 'frame.style.backgroundPosition = (parseInt(frame.style.left)*-1)+"px "+(parseInt(frame.style.top)*-1)+"px";';
	}
	?>
		}
	}
	frame.window.onMouseUp = function() {
	<?php
	if ($window_move) {
		echo 'frame.style.backgroundPosition = (parseInt(frame.style.left)*-1)+"px "+(parseInt(frame.style.top)*-1)+"px";';
	}
	?>
		frame.window.composition.cover.parentNode.removeChild(frame.window.composition.cover);

		document.body.onmouseup = frame.window.composition.saveMouseUp;
		document.body.onmousemove = frame.window.composition.saveMouseMove;
	}
	
	// Window onload function - run once, on initial load
	if (typeof attributes.load == 'function') {
		frame.window.onLoad = attributes.load;
	} else {
		frame.window.onLoad = function() {};
	}
	// Window onreload function - run every time a page is loaded
	if (typeof attributes.reload == 'function') {
		frame.window.onReload = attributes.reload;
	} else {
		frame.window.onReload = function() {};
	}
	
	// Set window events
	frame.onmousedown = frame.window.bringToFront;
	frame.window.composition.bDown = false;
	
	// Draw window
	
	// TITLE ROW
	frame.window.composition.titleRow = document.createElement('div');

	frame.window.composition.titleRow.className = 'FlyWindowTitleRowActive';
	frame.window.composition.titleRow.style.position = 'absolute';
	frame.window.composition.titleRow.style.top = '0px';
	frame.window.composition.titleRow.style.left = '0px';
	frame.window.composition.titleRow.style.right = '0px';
	frame.window.composition.titleRow.style.height = '36px';
	frame.window.composition.titleRow.style.boxSizing = 'border-box';
	frame.window.composition.titleRow.style.zIndex = '1';
	frame.window.composition.titleRow.status = {
		visible: true,
		hide: function() {
			frame.window.composition.titleRow.style.display = 'none';
			frame.window.composition.titleRow.status.visible = false;
			
			frame.window.composition.content.style.marginTop = '0px';
			frame.style.paddingTop = '';
		},
		show: function() {
			frame.window.composition.titleRow.style.display = 'block';
			frame.window.composition.titleRow.status.visible = true;
			
			frame.window.composition.content.style.marginTop = '36px';
			frame.style.paddingTop = '0px';
			
			frame.window.composition.title.adjustWidth();
		}
	}

	/* a little stuff left from testing
	frame.window.composition.titleRow.style.backgroundImage = 'linear-gradient(to bottom, rgba(56,136,233,1) 0%,rgba(73,147,230,1) 8%,rgba(35,89,214,1) 16%,rgba(37,97,222,1) 84%,rgba(30,80,196,1) 100%)';
	frame.window.composition.titleRow.style.backgroundSize = '100% 100%';
	frame.window.composition.titleRow.style.backgroundRepeat = 'repeat-x';
	frame.window.composition.titleRow.style.backgroundPosition = '0px 0px';
	*/


	frame.appendChild(frame.window.composition.titleRow);


	// BUTTONS
	frame.window.composition.buttons = {};


	// CLOSE
	frame.window.composition.buttons.close = document.createElement('div');

	frame.window.composition.buttons.close.className = 'FlyWindowButtonActive';
	frame.window.composition.buttons.close.style.float = 'right';
	frame.window.composition.buttons.close.innerHTML = 'B';
	frame.window.composition.buttons.close.onclick = frame.window.close;
	frame.window.composition.close = frame.window.composition.buttons.close;
	frame.window.composition.close.status = {
		visible: true,
		disabled: false,
		hide: function() {
			frame.window.composition.close.style.display = 'none';
			
			frame.window.composition.close.status.visible = false;
			
			frame.window.composition.setTitlebarWidth();
		},
		show: function() {
			frame.window.composition.close.style.display = 'block';
			
			frame.window.composition.close.status.visible = true;

			frame.window.composition.setTitlebarWidth();
		}
	}

	frame.window.composition.titleRow.appendChild(frame.window.composition.buttons.close);


	// MINIMIZE
	frame.window.composition.buttons.minimize = document.createElement('div');

	frame.window.composition.buttons.minimize.className = 'FlyWindowButtonActive';
	frame.window.composition.buttons.minimize.style.float = 'left';
	frame.window.composition.buttons.minimize.innerHTML = 'E';
	frame.window.composition.buttons.minimize.onclick = frame.window.minimize;
	frame.window.composition.minimize = frame.window.composition.buttons.minimize;
	frame.window.composition.minimize.status = {
		visible: true,
		disabled: false,
		hide: function() {
			frame.window.composition.minimize.style.display = 'none';
			
			frame.window.composition.minimize.status.visible = false;
			
			frame.window.composition.setTitlebarWidth();
		},
		show: function() {
			frame.window.composition.minimize.style.display = 'block';
			
			frame.window.composition.minimize.status.visible = true;

			frame.window.composition.setTitlebarWidth();
		}
	}

	frame.window.composition.titleRow.appendChild(frame.window.composition.buttons.minimize);


	// EXPAND
	frame.window.composition.buttons.expand = document.createElement('div');

	frame.window.composition.buttons.expand.className = 'FlyWindowButtonActive';
	frame.window.composition.buttons.expand.style.float = 'left';
	frame.window.composition.buttons.expand.innerHTML = 'C';
	frame.window.composition.buttons.expand.onclick = frame.window.expand;
	frame.window.composition.expand = frame.window.composition.buttons.expand;
	frame.window.composition.expand.status = {
		visible: false,
		disabled: false,
		hide: function() {
			frame.window.composition.expand.style.display = 'none';
			
			frame.window.composition.expand.status.visible = false;
			
			frame.window.composition.setTitlebarWidth();
		},
		show: function() {
			frame.window.composition.expand.style.display = 'block';
			
			frame.window.composition.expand.status.visible = true;

			frame.window.composition.setTitlebarWidth();
		}
	}

	frame.window.composition.titleRow.appendChild(frame.window.composition.buttons.expand);


	// TITLE BAR
	var div = document.createElement('div');
	div.innerText = div.textContent = frame.window.title;
	var escapedTitle = div.innerHTML;
	
	frame.window.composition.title = document.createElement('div');
	frame.window.composition.title.className = 'FlyWindowTitleActive';
	frame.window.composition.title.style.position = 'absolute';
	frame.window.composition.title.style.zIndex = '2';
	frame.window.composition.title.style.top = parseInt(frame.window.composition.titleRow.style.padding)+'px';
	frame.window.composition.title.style.left = parseInt(frame.window.composition.titleRow.style.padding)+'px';
	frame.window.composition.title.style.right = parseInt(frame.window.composition.titleRow.style.padding)+'px';
	frame.window.composition.title.style.bottom = parseInt(frame.window.composition.titleRow.style.padding)+'px';
	frame.window.composition.title.onmousedown = function(e) {
		e.preventDefault();
		if (frame.window.focus != frame.id && !frame.window.isActive) {
			return false;
		} else {
			if (e.button == 0) {	
				frame.window.onMouseDown();
			} else if (e.button == 2) {
				if (typeof ui !== 'undefined') {
					try {
						var wn = ui.toolbar.activeApplication.applicationMenu(frame);
						Fly.actionmenu(e,wn);
					} catch(er) {console.log(er);}
				} else {	
					return false;
				}
			}
		}
	}
	frame.window.composition.title.oncontextmenu = function(e) {
		e.preventDefault();
		return false;
	}
	//frame.window.composition.title.ondblclick = frame.window.expand;
	frame.window.composition.title.status = {
		visible: true,
		disabled: false
	}
	

	frame.window.composition.title.adjustWidth = function() {
		frame.window.composition.title.style.left = (Math.max(frame.window.composition.buttons.minimize.offsetWidth+frame.window.composition.buttons.expand.offsetWidth,frame.window.composition.buttons.close.offsetWidth)+12)+'px';
		frame.window.composition.title.style.right = frame.window.composition.title.style.left;
	}
	frame.window.composition.setTitlebarWidth = frame.window.composition.title.adjustWidth;

	//frame.window.composition.title.style.background = 'rgba(0,0,0,0.3)';
	frame.window.composition.title.style.lineHeight = '28px';
	frame.window.composition.title.style.paddingTop = '0px';
	frame.window.composition.title.style.paddingBottom = '0px';
	frame.window.composition.title.innerHTML = frame.window.composition.icon+escapedTitle;

	frame.window.composition.titleRow.appendChild(frame.window.composition.title);


	// CONTENT
	frame.window.composition.content = document.createElement('div');

	frame.window.composition.content.style.position = 'relative';
	frame.window.composition.content.style.margin = '0px';
	frame.window.composition.content.style.marginTop = '36px';
	frame.window.composition.content.style.width = 'auto';
	frame.window.composition.content.style.height = 'auto';

	frame.appendChild(frame.window.composition.content);
	
		
	// SET SECONDARY MOVEMENT
	frame.window.composition.secondaryMovement = document.createElement('div');
	frame.window.composition.secondaryMovement.style.position = 'absolute';
	frame.window.composition.secondaryMovement.style.zIndex = '2';
	frame.window.composition.secondaryMovement.style.top = '0px';
	frame.window.composition.secondaryMovement.style.left = '0px';
	frame.window.composition.secondaryMovement.style.width = '0px';
	frame.window.composition.secondaryMovement.style.height = '0px';
	frame.window.composition.secondaryMovement.style.cursor = 'move';
	//frame.window.composition.secondaryMovement.style.backgroundColor = 'rgba(0,0,0,0.4)';
	frame.window.composition.secondaryMovement.onmousedown = frame.window.onMouseDown;
	frame.window.composition.secondaryMovement.set = function(x,y,w,h) {
		frame.window.composition.secondaryMovement.style.top = y+'px';
		frame.window.composition.secondaryMovement.style.left = x+'px';
		frame.window.composition.secondaryMovement.style.width = w+'px';
		frame.window.composition.secondaryMovement.style.height = h+'px';
		frame.window.composition.content.appendChild(frame.window.composition.secondaryMovement); 
	}
	frame.window.composition.secondaryMovement.reset = function() {
		frame.window.composition.secondaryMovement.style.top = '0px';
		frame.window.composition.secondaryMovement.style.left = '0px';
		frame.window.composition.secondaryMovement.style.width = '0px';
		frame.window.composition.secondaryMovement.style.height = '0px';
		frame.window.composition.secondaryMovement.parentNode.removeChild(frame.window.composition.secondaryMovement); 
	}
	
	
	// INACTIVE COVER
	frame.window.composition.cover = document.createElement('div');
	frame.window.composition.cover.style.position = 'absolute';
	frame.window.composition.cover.style.zIndex = '3';
	frame.window.composition.cover.style.top = '0px';
	frame.window.composition.cover.style.left = '0px';
	frame.window.composition.cover.style.width = '100%';
	frame.window.composition.cover.style.height = '100%';
	frame.window.composition.cover.style.cursor = 'wait';
	frame.window.composition.cover.style.backgroundColor = 'rgba(255,255,255,0.4)';
	//frame.window.composition.content.appendChild(frame.window.composition.cover); 
	
	
	// CONTENT FRAME
	frame.window.content = document.createElement('iframe');
	frame.window.content.style.width = attributes.width+'px';
	frame.window.content.style.height = attributes.height+'px';
	frame.window.content.frameBorder = "0";
	frame.window.content.allowTransparency = 'true';
	frame.window.content.scrolling = 'no';
	frame.window.content.style.marginBottom = '-4px';
	frame.window.content.style.overflow = 'hidden';
	//frame.window.content.style.transition = 'width .05s ease-in-out, height .05s ease-in-out';
	//frame.window.content.style.visibility = 'hidden';
	frame.window.content.src = frame.window.location;
	
	frame.window.content.onload = function() {
		if (frame.window.isLoaded != true) {
			frame.window.isLoaded = true;
			
			frame.window.onContentLoaded();
			
			setTimeout(function(){frame.window.content.contentWindow.focus();},10);
			try {
				frame.window.onLoad(frame);
			}
			catch(err) {
				shell.dialog('Window error','Window load function error:<pre>'+err+'</pre>','Window Error');
			}
			frame.window.setSize(attributes.width,attributes.height);
		}

		try {
			frame.window.onReload(frame);
		}
		catch(err) {
			shell.dialog('Window error','Window reload function error:<pre>'+err+'</pre>','Window Error');
		}

		if (typeof frame.window.content.contentWindow.Fly.window != 'undefined') {
			frame.window.content.contentWindow.Fly.window.id = frame.id;
			try {
				frame.window.content.contentWindow.Fly.window.ready();
			} catch(err) {console.log(err);}
		}

		frame.window.content.contentWindow.document.body.addEventListener('mousedown', function(e) {
			if (frame.style.zIndex < task.index) {
				frame.window.bringToFront();
			}
		}, true); 
	}
	
	frame.window.composition.content.appendChild(frame.window.content);
	
	// RESIZE GRABBERS
	frame.window.composition.resize = {};
	
	// EAST RESIZE
	frame.window.composition.resize.east = document.createElement('div');
	frame.window.composition.resize.east.style.position = 'absolute';
	frame.window.composition.resize.east.style.top = '0px';
	frame.window.composition.resize.east.style.bottom = '0px';
	frame.window.composition.resize.east.style.left = '100%';
	frame.window.composition.resize.east.style.width = '10px';
	frame.window.composition.resize.east.style.cursor = 'ew-resize';
	frame.window.composition.resize.east.style.backgroundColor = 'rgba(0,0,0,0)';
	
	frame.window.composition.resize.east.movement = {
		mousedown: function() {
			frame.window.composition.resize.east.movement.bdown = true;
			
			frame.window.composition.resize.east.movement.cover = document.createElement('div');
			frame.window.composition.resize.east.movement.cover.style.position = 'fixed';
			frame.window.composition.resize.east.movement.cover.style.top = '0px';
			frame.window.composition.resize.east.movement.cover.style.left = '0px';
			frame.window.composition.resize.east.movement.cover.style.bottom = '0px';
			frame.window.composition.resize.east.movement.cover.style.right = '0px';
			frame.window.composition.resize.east.movement.cover.style.zIndex = task.index+1;
			frame.window.composition.resize.east.movement.cover.style.backgroundColor = 'rgba(0,0,0,0)';
			frame.window.composition.resize.east.movement.cover.style.cursor = 'ew-resize';
			
			frame.window.composition.resize.east.movement.cover.onmouseup = frame.window.composition.resize.east.movement.mouseup;
			frame.window.composition.resize.east.movement.cover.onmousemove = frame.window.composition.resize.east.movement.mousemove;
			
			document.body.appendChild(frame.window.composition.resize.east.movement.cover);
		},
		mouseup: function() {
			frame.window.composition.resize.east.movement.bdown = false;
			frame.window.composition.resize.east.movement.cover.remove();
		},
		mousemove: function(e) {
			if (frame.window.composition.resize.east.movement.bdown) {
				frame.window.setSize((e.pageX-(frame.offsetLeft+(frame.offsetWidth-parseInt(frame.window.content.style.width)))),parseInt(frame.window.content.style.height));
			} else {
				frame.window.composition.resize.east.movement.mouseup();
			}
		},
		bdown: false,
	}
	
	frame.window.composition.resize.east.onmousedown = frame.window.composition.resize.east.movement.mousedown;
	frame.window.composition.resize.east.onmouseup = frame.window.composition.resize.east.movement.mouseup;
	
	frame.window.composition.content.appendChild(frame.window.composition.resize.east);
	
	// SOUTH RESIZE
	frame.window.composition.resize.south = document.createElement('div');
	frame.window.composition.resize.south.style.position = 'absolute';
	frame.window.composition.resize.south.style.top = '100%';
	frame.window.composition.resize.south.style.right = '0px';
	frame.window.composition.resize.south.style.left = '0px';
	frame.window.composition.resize.south.style.height = '10px';
	frame.window.composition.resize.south.style.cursor = 'ns-resize';
	frame.window.composition.resize.south.style.backgroundColor = 'rgba(0,0,0,0)';
	
	frame.window.composition.resize.south.movement = {
		mousedown: function() {
			frame.window.composition.resize.south.movement.bdown = true;
			
			frame.window.composition.resize.south.movement.cover = document.createElement('div');
			frame.window.composition.resize.south.movement.cover.style.position = 'fixed';
			frame.window.composition.resize.south.movement.cover.style.top = '0px';
			frame.window.composition.resize.south.movement.cover.style.left = '0px';
			frame.window.composition.resize.south.movement.cover.style.bottom = '0px';
			frame.window.composition.resize.south.movement.cover.style.right = '0px';
			frame.window.composition.resize.south.movement.cover.style.zIndex = task.index+1;
			frame.window.composition.resize.south.movement.cover.style.backgroundColor = 'rgba(0,0,0,0)';
			frame.window.composition.resize.south.movement.cover.style.cursor = 'ns-resize';
			
			frame.window.composition.resize.south.movement.cover.onmouseup = frame.window.composition.resize.south.movement.mouseup;
			frame.window.composition.resize.south.movement.cover.onmousemove = frame.window.composition.resize.south.movement.mousemove;
			
			document.body.appendChild(frame.window.composition.resize.south.movement.cover);
		},
		mouseup: function() {
			frame.window.composition.resize.south.movement.bdown = false;
			frame.window.composition.resize.south.movement.cover.remove();
		},
		mousemove: function(e) {
			if (frame.window.composition.resize.south.movement.bdown) {
				frame.window.setSize(parseInt(frame.window.content.style.width),(e.pageY-(frame.offsetTop+(frame.offsetHeight-parseInt(frame.window.content.style.height)))));
			} else {
				frame.window.composition.resize.south.movement.mouseup();
			}
		},
		bdown: false,
	}
	
	frame.window.composition.resize.south.onmousedown = frame.window.composition.resize.south.movement.mousedown;
	frame.window.composition.resize.south.onmouseup = frame.window.composition.resize.south.movement.mouseup;
	
	frame.window.composition.content.appendChild(frame.window.composition.resize.south);
	
	// SOUTHEAST RESIZE
	frame.window.composition.resize.se = document.createElement('div');
	frame.window.composition.resize.se.style.position = 'absolute';
	frame.window.composition.resize.se.style.top = '100%';
	frame.window.composition.resize.se.style.left = '100%';
	frame.window.composition.resize.se.style.width = '10px';
	frame.window.composition.resize.se.style.height = '10px';
	frame.window.composition.resize.se.style.cursor = 'nwse-resize';
	frame.window.composition.resize.se.style.backgroundColor = 'rgba(0,0,0,0)';
	
	frame.window.composition.resize.se.movement = {
		mousedown: function() {
			frame.window.composition.resize.se.movement.bdown = true;
			
			frame.window.composition.resize.se.movement.cover = document.createElement('div');
			frame.window.composition.resize.se.movement.cover.style.position = 'fixed';
			frame.window.composition.resize.se.movement.cover.style.top = '0px';
			frame.window.composition.resize.se.movement.cover.style.left = '0px';
			frame.window.composition.resize.se.movement.cover.style.bottom = '0px';
			frame.window.composition.resize.se.movement.cover.style.right = '0px';
			frame.window.composition.resize.se.movement.cover.style.zIndex = task.index+1;
			frame.window.composition.resize.se.movement.cover.style.backgroundColor = 'rgba(0,0,0,0)';
			frame.window.composition.resize.se.movement.cover.style.cursor = 'nwse-resize';
			
			frame.window.composition.resize.se.movement.cover.onmouseup = frame.window.composition.resize.se.movement.mouseup;
			frame.window.composition.resize.se.movement.cover.onmousemove = frame.window.composition.resize.se.movement.mousemove;
			
			document.body.appendChild(frame.window.composition.resize.se.movement.cover);
		},
		mouseup: function() {
			frame.window.composition.resize.se.movement.bdown = false;
			frame.window.composition.resize.se.movement.cover.remove();
		},
		mousemove: function(e) {
			if (frame.window.composition.resize.se.movement.bdown) {
				frame.window.setSize((e.pageX-(frame.offsetLeft+(frame.offsetWidth-parseInt(frame.window.content.style.width)))),(e.pageY-(frame.offsetTop+(frame.offsetHeight-parseInt(frame.window.content.style.height)))));
			} else {
				frame.window.composition.resize.se.movement.mouseup();
			}
		},
		bdown: false,
	}
	
	frame.window.composition.resize.se.onmousedown = frame.window.composition.resize.se.movement.mousedown;
	frame.window.composition.resize.se.onmouseup = frame.window.composition.resize.se.movement.mouseup;
	
	frame.window.composition.content.appendChild(frame.window.composition.resize.se);
	
	// CHECK IF RESIZE ENABLED
	if (attributes.resize == false) {
		frame.window.composition.resize.east.remove();
		frame.window.composition.resize.south.remove();
		frame.window.composition.resize.se.remove();
	}
	
	// Background movement
<?php
if ($window_move) {
	echo 'frame.style.backgroundPosition = "-"+(frame.style.left)+" -"+(frame.style.top);';
}
?>

	frame.window.onContentLoaded = function() {
		frame.style.visibility = 'visible';
		frame.window.bringToFront();
		<?php
			echo 'frame.style.animation = "FlyWindowOpenAnimation '.$theme3[0]['style']['window']['animations']['open']['length'].'s '.$theme3[0]['style']['window']['animations']['open']['timing'].' '.$theme3[0]['style']['window']['animations']['open']['delay'].'s '.$theme3[0]['style']['window']['animations']['open']['repeat'].'";';
		?>
		clearTimeout(frame.window.composition.showContentTimeout);
	}
	frame.window.composition.showContentTimeout = setTimeout(frame.window.onContentLoaded,<?php echo $window_timeout; ?>);
	
	if (attributes.expand) {
		frame.window.composition.expand.status.show();
	}
	if (!attributes.minimize) {
		frame.window.composition.minimize.status.hide();
	}
	if (!attributes.close) {
		frame.window.composition.close.status.hide();
	}

	var allWindows = document.getElementsByClassName('FlyWindow');
	for (i = 0; i < allWindows.length; i++) { 
		allWindows[i].window.checkIfBehind();
	}
	try {
		ui.toolbar.setActiveApplication(frame);
	} catch(err) {}
	
	if (typeof ui == "undefined") {
		frame.window.composition.minimize.status.hide();
		frame.window.composition.expand.status.hide();
	}
	
	document.body.appendChild(frame);
	
	if (!attributes.expand) {frame.window.composition.buttons.expand.status.hide();}
	if (!attributes.minimize) {frame.window.composition.buttons.minimize.status.hide();}
	if (!attributes.close) {frame.window.composition.buttons.close.status.hide();}
	frame.window.composition.title.adjustWidth();
	
	console.log(`Window opened - ${id} "${attributes.name}" (${frame.id})`);
	return frame;
}
/*
setTimeout(function() {
	shell.notification('Beta Window Composition','The window3.php beta is in use. Some issues may be present with windows.','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg');
},5000);
*/
</script>