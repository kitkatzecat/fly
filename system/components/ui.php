<?php
if (in_array(FlyRegistryGet('TimeShowSeconds','SprocketComputers.Options'),['true','yes','on'])) {
	$script_show_seconds = '":" + seconds';
} else {
	$script_show_seconds = '""';
}
if (in_array(FlyRegistryGet('TimeShowMilitary','SprocketComputers.Options'),['true','yes','on'])) {
	$script_show_military = 'var hr = "";';
} else {
	$script_show_military = '
		var hr = " AM";
        if(hours > 11){
            hr = " PM";
        } else {
            hr = " AM";
        }
		if(hours == 0){
		    hours=12;
		}
		if(hours > 12){
			hours-=12;
		}';
}
?>
<script>
function ui() {};

ui.init = function() {
	// if UI is not already initialized
	if (typeof ui.toolbar == "undefined") {
		ui.loading = document.createElement("div");
		ui.loading.style.position = 'fixed';
		ui.loading.style.bottom = '32px';
		ui.loading.style.left = '32px';
		ui.loading.style.textAlign = 'center';
		ui.loading.style.padding = '10px';
		ui.loading.className = 'FlyUiControlNonScaled FlyUiTextHighlightControl';
		ui.loading.style.animation = 'FlyTrayIconAnimation 0.2s linear';
		ui.loading.innerHTML = 'Loading...';
		ui.loading.style.display = 'none';
		document.body.appendChild(ui.loading);
		
		ui.loading.show = function() {
			ui.loading.style.display = 'block';
		}
		ui.loading.hide = function() {
			ui.loading.style.display = 'none';
		}
		ui.loading.onclick = ui.loading.hide;
		
		ui.toolbar = document.createElement("div");
		ui.toolbar.className = 'FlyToolbar';
		ui.toolbar.oncontextmenu = function() {
			ui.toolbar.activeApplication.onclick();
			return false;
		}
		document.body.appendChild(ui.toolbar);
		
		ui.toolbar.setActiveApplication = function(content) {
			if (content == 'Desktop' || content == false) {
				ui.toolbar.activeApplication.innerHTML = 'Desktop ▾';
				ui.toolbar.activeApplication.object = false;
			} else {
				if (content.window.isExpand) {
					ui.toolbar.activeApplication.innerHTML = content.window.title+' ▾';
				} else {
					ui.toolbar.activeApplication.innerHTML = content.window.name+' ▾';
				}
				ui.toolbar.activeApplication.object = content;
			}
		}
		
		ui.toolbar.flyButton = document.createElement('img');
		ui.toolbar.flyButton.src = '/system/resources/icons/fly.svg';
		ui.toolbar.flyButton.className = 'FlyJumpButton';
		ui.toolbar.flyButton.onclick = function() {ui.jump.toggle()};
		ui.toolbar.flyButton.oncontextmenu = function(e) {
			Fly.actionmenu(e,[
				['<b>Open Jump menu',function() {ui.jump.toggle();},{icon:'/system/resources/icons/fly.svg'}],
				['Customize...',function() {system.command('run:SprocketComputers.Options,page=personalization,action=jump');}]
			]);
			return false;
		}
		document.body.appendChild(ui.toolbar.flyButton);
		
		ui.toolbar.time = document.createElement("div");
		ui.toolbar.time.style.position = 'absolute';
		ui.toolbar.time.style.top = '9px';
		ui.toolbar.time.style.left = '58px';
		ui.toolbar.time.style.width = '110px';
		ui.toolbar.time.className = 'FlyUiTextHoverControl';
		ui.toolbar.time.innerHTML = '12:00 AM';
		ui.toolbar.time.onclick = function() {ui.time.toggle();};
		ui.toolbar.appendChild(ui.toolbar.time);

		ui.toolbar.activeApplication = document.createElement("div");
		ui.toolbar.activeApplication.style.position = 'absolute';
		ui.toolbar.activeApplication.style.top = '9px';
		ui.toolbar.activeApplication.style.width = 'auto';
		ui.toolbar.activeApplication.style.maxWidth = 'calc(100% - 368px)';
		ui.toolbar.activeApplication.style.left = 'calc(50% + 8px)';
		ui.toolbar.activeApplication.style.transform = 'translateX(-50%)';
		ui.toolbar.activeApplication.style.overflow = 'hidden';
		ui.toolbar.activeApplication.style.whiteSpace = 'nowrap';
		ui.toolbar.activeApplication.style.textOverflow = 'ellipsis';
		ui.toolbar.activeApplication.style.textAlign = 'center';
		ui.toolbar.activeApplication.style.padding = '12px';
		ui.toolbar.activeApplication.style.marginTop = '-12px';
		ui.toolbar.activeApplication.style.marginBottom = '-12px';
		ui.toolbar.activeApplication.style.lineHeight = '18px';
		ui.toolbar.activeApplication.className = 'FlyUiTextHoverControl';
		ui.toolbar.activeApplication.innerHTML = 'Desktop ▾';
		ui.toolbar.activeApplication.object = false;
		
		ui.toolbar.activeApplication.onclick = function() {
			var aa = ui.toolbar.activeApplication;
			var ob = ui.toolbar.activeApplication.object;
			var ar = [];
		
			if (ob == false) {
				ar.push(['Desktop',[
					['Refresh',function() {try {document.getElementById('FlyUiDesktopFrame').contentWindow.Refresh();} catch(e) {document.getElementById('FlyUiDesktopFrame').contentWindow.window.location.reload();}},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg'}]
				],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>desktop.svg'}]);
			} else {
				var wn = ui.toolbar.activeApplication.applicationMenu(ob);
				ar.push([ob.window.title,wn,{icon:ob.window.icon}]);
			}
			ar.push(['']);
			var allWindows = document.getElementsByClassName('FlyWindow');
			var windows = [];
			if (allWindows.length == 0) {
				ar.push(['No windows are open']);
				ar.push(['']);
				ar.push(['Close All',function(){},{disabled:true,icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]);
			} else {
				for (i = 0; i < allWindows.length; i++) { 
					if (allWindows[i].id !== ob.id && allWindows[i].window.isBackground !== true) {
						windows.push([allWindows[i].window.title,allWindows[i].window.bringToFront,{icon:allWindows[i].window.icon}])
					}
				}
				if (windows.length == 0 && ob !== false) {
					ar.push(['No other windows open']);
					ar.push(['']);
					ar.push(['Close All',function(){
						var allWindows = document.getElementsByClassName('FlyWindow');
						for (i = 0; i < allWindows.length; i++) { 
							setTimeout(allWindows[i].window.close,10);
						}					
					},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]);
				} else if (windows.length !== 0) {
					for (i = 0; i < windows.length; i++) { 
						ar.push(windows[i]);
					}
					ar.push(['']);
					ar.push(['Close All',function(){
						var allWindows = document.getElementsByClassName('FlyWindow');
						for (i = 0; i < allWindows.length; i++) { 
							setTimeout(allWindows[i].window.close,10);
						}					
					},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]);
				} else {
					ar.push(['No windows are open']);
					ar.push(['']);
					ar.push(['Close All',function(){},{disabled:true,icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]);
				}
			}
			
			var m = Fly.actionmenu([aa.offsetLeft,(ui.toolbar.offsetTop+ui.toolbar.offsetHeight)],ar);
			m.style.left = (aa.offsetLeft-(m.offsetWidth/2)-8)+'px';
		}
		ui.toolbar.activeApplication.oncontextmenu = function() {
			ui.toolbar.activeApplication.onclick();
			return false;
		}
		ui.toolbar.activeApplication.applicationMenu = function(ob) {
			var wn = [];
			if (ob.window.isExpand) {
				wn.push(['Restore',ob.window.restore,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-in.svg'}]);
			} else {
				if (ob.window.composition.expand.status.visible == true) {
					wn.push(['Expand',ob.window.expand,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-out.svg'}]);
				} else {
					wn.push(['Expand',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-out.svg',disabled:true}]);
				}
				if (ob.window.composition.minimize.status.visible == true) {
					wn.push(['Minimize',ob.window.minimize,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right-up.svg'}]);
				} else {
					wn.push(['Minimize',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right-up.svg',disabled:true}]);
				}
				if (ob.window.isResizable == true) {
					wn.push(['Snap',[
						['Snap to left',function(){ui.toolbar.snap.window(ob,'left');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-left.svg'}],
						['Snap to right',function(){ui.toolbar.snap.window(ob,'right');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg'}],
						['Snap to fill',function(){ui.toolbar.snap.window(ob,'fill');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-up-down.svg'}],
						[''],
						['Un-Snap',function(){ui.toolbar.snap.window.unsnap(ob);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>undo.svg'}],
					],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-left-right.svg'}]);
				} else {
					wn.push(['Snap',[],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrows-left-right.svg',disabled:true}]);
				}
			}
			wn.push(['']);
			wn.push(['Reset',function(){ui.toolbar.snap.window.reset(ob);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>undo.svg'}]);
			wn.push(['']);
			wn.push(['Close',ob.window.close,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]);
			
			return wn;
		}
		ui.toolbar.snap = {};
		ui.toolbar.snap.window = function(frame,side) {
			try {
				var pos = {
					x: 0,
					y: 0
				};
				var size = {
					w: ((window.innerWidth/2) - (frame.offsetWidth - frame.window.content.offsetWidth)),
					h: (window.innerHeight - ui.toolbar.offsetHeight - (frame.offsetHeight - frame.window.content.offsetHeight))
				};
				if (side == 'left') {
					pos.x = 0;
				} else if (side == 'right') {
					pos.x = (window.innerWidth/2)
				} else if (side == 'fill') {
					pos.x = 0;
					size.w = ((window.innerWidth) - (frame.offsetWidth - frame.window.content.offsetWidth));
				} else {
					return;
				}
				
				frame.window.setPosition(pos.x,pos.y);
				frame.window.setSize(size.w,size.h);
			} catch (err) {
				shell.dialog('Unable to snap','The window could not be snapped: '+err,'Unable to Snap');
			}
		}
		ui.toolbar.snap.window.reset = function(frame) {
			frame.window.setSize(frame.window.initial.width,frame.window.initial.height);
			frame.window.setPosition(frame.window.initial.x,frame.window.initial.y);
		}
		ui.toolbar.snap.window.unsnap = function(frame) {
			frame.window.setSize(frame.window.initial.width,frame.window.initial.height);
			frame.window.setPosition(frame.window.initial.x,frame.window.initial.y);
		}
				
		ui.toolbar.appendChild(ui.toolbar.activeApplication);
		
		ui.toolbar.expandedControls = document.createElement("div");
		ui.toolbar.expandedControls.style.position = 'absolute';
		ui.toolbar.expandedControls.style.top = '4px';
		ui.toolbar.expandedControls.style.width = '150px';
		ui.toolbar.expandedControls.style.height = '30px';
		ui.toolbar.expandedControls.style.right = '-192px';
		ui.toolbar.expandedControls.style.transition = 'right .5s ease-in-out';
		ui.toolbar.expandedControls.style.textAlign = 'right';
		ui.toolbar.expandedControls.style.overflow = 'visible';
		
		ui.toolbar.expandedControls.restore = document.createElement('div');
		ui.toolbar.expandedControls.restore.style.display = 'inline-block';
		ui.toolbar.expandedControls.restore.style.marginRight = '8px';
		ui.toolbar.expandedControls.restore.innerHTML = 'D';
		ui.toolbar.expandedControls.restore.title = 'Restore';
		ui.toolbar.expandedControls.restore.className = 'FlyWindowButtonActive';
		
		ui.toolbar.expandedControls.close = document.createElement('div');
		ui.toolbar.expandedControls.close.style.display = 'inline-block';
		ui.toolbar.expandedControls.close.style.marginRight = '8px';
		ui.toolbar.expandedControls.close.innerHTML = 'B';
		ui.toolbar.expandedControls.close.title = 'Close';
		ui.toolbar.expandedControls.close.className = 'FlyWindowButtonActive';
		
		ui.toolbar.expandedControls.appendChild(ui.toolbar.expandedControls.restore);
		ui.toolbar.expandedControls.appendChild(ui.toolbar.expandedControls.close);
		
		ui.toolbar.appendChild(ui.toolbar.expandedControls);
		
		ui.toolbar.isExpandedWindow = false;
		
		ui.toolbar.windowExpanded = function(frame) {
			if (frame.window.isExpand == true) {
				ui.toolbar.isExpandedWindow = true;
				if (ui.toolbar.trayMsg.style.right == '-256px') {
					ui.toolbar.expandedControls.style.right = '32px';
					ui.toolbar.tray.style.right = '-256px';
				}
				ui.toolbar.setActiveApplication(ui.toolbar.activeApplication.object);
				ui.toolbar.expandedControls.close.onclick = function() {
					frame.window.close();
				}
				ui.toolbar.expandedControls.restore.onclick = function() {
					frame.window.restore();
				}
			}
		}
		
		ui.toolbar.windowRestored = function() {
			ui.toolbar.isExpandedWindow = false;
			if (ui.toolbar.trayMsg.style.right == '-256px') {
				ui.toolbar.expandedControls.style.right = '-256px';
				ui.toolbar.tray.style.right = '32px';
			}
			ui.toolbar.setActiveApplication(ui.toolbar.activeApplication.object);
		}
		
		ui.toolbar.tray = document.createElement("div");
		ui.toolbar.tray.style.position = 'absolute';
		//ui.toolbar.tray.style.backgroundColor = 'rgba(255,255,255,.1)';
		ui.toolbar.tray.style.top = '6px';
		ui.toolbar.tray.style.width = '150px';
		ui.toolbar.tray.style.height = '28px';
		ui.toolbar.tray.style.right = '32px';
		ui.toolbar.tray.style.transition = 'right .5s ease-in-out';
		ui.toolbar.tray.style.textAlign = 'right';
		ui.toolbar.tray.style.overflow = 'auto';
		ui.toolbar.tray.className = 'FlyUiTextHoverControl';
		ui.toolbar.tray.innerHTML = '';
		ui.toolbar.appendChild(ui.toolbar.tray);
		
		ui.toolbar.trayMsg = document.createElement("div");
		ui.toolbar.trayMsg.style.position = 'absolute';
		//ui.toolbar.trayMsg.style.backgroundColor = 'rgba(255,255,255,.1)';
		ui.toolbar.trayMsg.style.top = '9px';
		ui.toolbar.trayMsg.style.width = '250px';
		ui.toolbar.trayMsg.style.right = '-256px';
		ui.toolbar.trayMsg.style.transition = 'right .5s ease-in-out';
		ui.toolbar.trayMsg.style.textAlign = 'right';
		ui.toolbar.trayMsg.className = 'FlyUiTextHoverControl';
		ui.toolbar.trayMsg.onclick = function() {ui.tray.toggle();};
		ui.toolbar.trayMsg.innerHTML = 'Minimized Windows';
		ui.toolbar.appendChild(ui.toolbar.trayMsg);


		ui.toolbar.trayExpand = document.createElement("div");
		ui.toolbar.trayExpand.style.position = 'absolute';
		ui.toolbar.trayExpand.style.top = '11px';
		ui.toolbar.trayExpand.style.width = '16px';
		ui.toolbar.trayExpand.style.height = '24px';
		ui.toolbar.trayExpand.style.right = '12px';
		ui.toolbar.trayExpand.style.textAlign = 'right';
		ui.toolbar.trayExpand.style.fontSize = '12px';
		ui.toolbar.trayExpand.style.fontFamily = 'Arial,sans-serif';
		ui.toolbar.trayExpand.className = 'FlyUiTextHoverControl';
		ui.toolbar.trayExpand.innerHTML = '▼';
		ui.toolbar.trayExpand.onclick = function() {ui.tray.toggle();};
		ui.toolbar.appendChild(ui.toolbar.trayExpand);
		
		ui.desktop = document.createElement("div");
		ui.desktop.style.position = 'fixed';
		ui.desktop.style.top = ui.toolbar.offsetHeight+'px';
		ui.desktop.style.left = '0px';
		ui.desktop.style.right = '0px';
		ui.desktop.style.bottom = '0px';
		ui.desktop.style.zIndex = '-1';
		ui.desktop.style.transition = 'opacity .3s linear';
		ui.desktop.style.opacity = '0';
		setTimeout(function(){ui.desktop.innerHTML = '<iframe id="FlyUiDesktopFrame" style="width:100%;height:100%;" frameborder="0" allowtransparency="true" scrolling="no" src="<?php echo FLY_APPS_URL; ?>SprocketComputers.FileManager/filelist.php?seamless=true&FlyDesktopFiles=true&path=<?php echo FlyVarsReplace(FlyUserRegistryGet('DesktopPath','SprocketComputers.Options')); ?>"></iframe>';},2000);		
		document.body.appendChild(ui.desktop);
		
		ui.time = document.createElement("div");
		ui.time.style.position = 'fixed';
		ui.time.style.top = ui.toolbar.offsetHeight+'px';
		ui.time.style.left = '50px';
		ui.time.style.display = 'none';
		ui.time.style.zIndex = '4999997';
		ui.time.style.transformOrigin = '0% 0%';
		ui.time.className = 'FlyUiControlScaled';
		document.body.appendChild(ui.time);
		
		ui.time.toggle = function() {
			if (ui.time.style.display == 'none') {
				var coverDiv = document.createElement("div");
				coverDiv.style.position = 'fixed';
				coverDiv.style.top = '0px';
				coverDiv.style.bottom = '0px';
				coverDiv.style.left = '0px';
				coverDiv.style.right = '0px';
				coverDiv.style.backgroundColor = 'transparent';
				coverDiv.style.zIndex = '4999996';
				coverDiv.id = 'UiTempCoverDivTime';
				coverDiv.onclick = function() {ui.time.toggle();};
				document.body.appendChild(coverDiv);

				ui.time.style.display = 'block';
			} else {
				document.getElementById('UiTempCoverDivTime').parentNode.removeChild(document.getElementById('UiTempCoverDivTime'));

				ui.time.style.display = 'none';
			}
		}
		
		ui.time.content = document.createElement('div');
		ui.time.content.style.width = '256px';
		ui.time.content.style.height = '105px';
		ui.time.content.style.overflow = 'hidden';
		ui.time.content.style.position = 'relative';
		ui.time.content.style.padding = '3px';
		ui.time.content.style.backgroundColor = '#ffffff';
		ui.time.content.className = 'FlyUiText FlyUiNoSelect';
		ui.time.content.innerHTML = '<embed style="width:40%;" src="<?php echo FLY_URL; ?>system/resources/os/calendar.svg"></embed>';
		ui.time.appendChild(ui.time.content);
		
		ui.time.content.timedate = document.createElement("div");
		ui.time.content.timedate.style.position = 'absolute';
		ui.time.content.timedate.style.top = '50%';
		ui.time.content.timedate.style.right = '0px';
		ui.time.content.timedate.style.transform = 'translateY(-50%)';
		ui.time.content.timedate.style.width = '60%';
		ui.time.content.timedate.style.textAlign = 'center';
		ui.time.content.timedate.style.fontSize = '18px';
		ui.time.content.timedate.style.className = 'FlyUiText FlyUiNoSelect';
		ui.time.content.appendChild(ui.time.content.timedate);
		
		ui.time.openTimeOptions = document.createElement("div");
		ui.time.openTimeOptions.style.height = '20px';
		ui.time.openTimeOptions.style.padding = '4px';
		ui.time.openTimeOptions.style.marginTop = '4px';
		ui.time.openTimeOptions.className = 'FlyUiTextHoverControl FlyUiNoSelect';
		ui.time.openTimeOptions.innerHTML = 'Open Time &amp; Date Options';
		ui.time.openTimeOptions.onclick = function() {ui.time.toggle();system.command('run:SprocketComputers.Options,page=timedate');};
		ui.time.appendChild(ui.time.openTimeOptions);
		
		ui.tray = document.createElement("div");
		ui.tray.style.position = 'fixed';
		ui.tray.style.top = ui.toolbar.offsetHeight+'px';
		ui.tray.style.right = '0px';
		ui.tray.style.display = 'none';
		ui.tray.className = 'FlyUiControlScaled';
		ui.tray.style.transformOrigin = '100% 0%';
		ui.tray.style.zIndex = '4999997';
		document.body.appendChild(ui.tray);
		
		ui.tray.content = document.createElement("div");
		ui.tray.content.style.width = '256px';
		ui.tray.content.style.padding = '3px';
		ui.tray.content.style.backgroundColor = '#FFFFFF';
		ui.tray.content.className = 'FlyUiText FlyUiNoSelect';
		ui.tray.content.innerHTML = '';
		ui.tray.appendChild(ui.tray.content);
		
		ui.tray.openWinMgr = document.createElement("div");
		ui.tray.openWinMgr.style.height = '20px';
		ui.tray.openWinMgr.style.padding = '4px';
		ui.tray.openWinMgr.style.marginTop = '4px';
		ui.tray.openWinMgr.className = 'FlyUiTextHoverControl FlyUiNoSelect';
		ui.tray.openWinMgr.innerHTML = 'Open Window Manager';
		ui.tray.openWinMgr.onclick = function() {ui.tray.toggle();system.command('run:SprocketComputers.Utilities.WinMgr');};
		ui.tray.appendChild(ui.tray.openWinMgr);
		
		/*
		ui.tray.add = function(id) {
			var win = document.getElementById(id);
			if (typeof(win) !== "undefined") {
				var title = win.window.title;
				var icon = win.window.icon;
				ui.toolbar.tray.innerHTML = '<img src="'+icon+'" title="'+title+'" onclick="ui.tray.open(\''+id+'\')" id="TaskIcon-'+id+'" class="FlyTrayIcon">'+ui.toolbar.tray.innerHTML;
				ui.tray.content.innerHTML = '<div style="line-height:24px;" onclick="ui.tray.open(\''+id+'\')" id="TaskButton-'+id+'" class="FlyUiMenuItem"><img src="'+icon+'" style="width:24px;height:24px;vertical-align:top;margin-right:6px;"><div style="width:20px;height:24px;float:right;line-height:16px;font-size:12px;" onclick="ui.tray.close(\''+id+'\');event.stopPropagation();" class="FlyUiMenuItem"><img style="width:12px;height:12px;vertical-align:top;" src="/system/resources/icons/mark-x.svg"></div><div style="display:inline-block;width:196px;word-wrap:break-word;">'+title+'</div></div>'+ui.tray.content.innerHTML;
			}
		}
		
		ui.tray.open = function(id) {
			if (ui.tray.style.display == 'block') {
				ui.tray.toggle();
			}
			document.getElementById(id).window.maximize();
		}
		
		ui.tray.close = function(id) {
			document.getElementById(id).window.close();
			if(ui.tray.content.innerHTML == '') {
				ui.tray.toggle();
			}
		}
		
		ui.tray.remove = function(id) {
			document.getElementById('TaskIcon-'+id).parentNode.removeChild(document.getElementById('TaskIcon-'+id));
			document.getElementById('TaskButton-'+id).parentNode.removeChild(document.getElementById('TaskButton-'+id));
		}*/
		
		ui.tray.minimized = [];
		
		ui.tray.add = function(frame) {
			var trayObj = {};
			trayObj.tray = document.createElement('img');
			trayObj.list = document.createElement('div');
			
			trayObj.close = function() {
				try {
					frame.window.close();
				} catch(err) {alert(err)}
				if (ui.tray.minimized.length < 1) {
					ui.tray.toggle();
				}
			}
			
			trayObj.open = function() {
				if (ui.tray.style.display == 'block') {
					ui.tray.toggle();
				}
				frame.window.maximize();
			}
			
			trayObj.remove = function() {
				trayObj.tray.remove();
				trayObj.list.remove();
				
				var index = ui.tray.minimized.indexOf(trayObj);
				if (index > -1) {
					ui.tray.minimized.splice(index,1);
				}
			}
			
			trayObj.tray.title = frame.window.title;
			trayObj.tray.onclick = trayObj.open;
			trayObj.tray.className = 'FlyTrayIcon';
			trayObj.tray.src = frame.window.icon;
			trayObj.tray.oncontextmenu = function(e) {
				Fly.actionmenu(e,[
					[frame.window.title,frame.window.maximize,{icon:frame.window.icon}],
					[''],
					['Maximize',frame.window.maximize,{icon:'/system/resources/icons/arrow-left-down.svg'}],
					['Close',frame.window.close,{icon:'/system/resources/icons/mark-x.svg'}],
				]);
				e.stopPropagation();
				return false;
			}
			
			trayObj.list.style.lineHeight = '24px';
			trayObj.list.className = 'FlyUiMenuItem';
			trayObj.list.onclick = trayObj.open;
			trayObj.list.innerHTML = '<img src="'+frame.window.icon+'" style="width:24px;height:24px;vertical-align:top;margin-right:6px;"><div style="display:inline-block;width:196px;word-wrap:break-word;">'+frame.window.title+'</div>';
			
			trayObj.list.close = document.createElement('div');
			trayObj.list.close.style.width = '20px';
			trayObj.list.close.style.height = '24px';
			trayObj.list.close.style.float = 'right';
			trayObj.list.close.style.lineHeight = '16px';
			trayObj.list.close.style.fontSize = '12px';
			trayObj.list.close.onclick = function(event) {
				trayObj.close();
				event.stopPropagation();
			}
			trayObj.list.close.className = 'FlyUiMenuItem';
			trayObj.list.close.innerHTML = '<img style="width:12px;height:12px;vertical-align:top;" src="/system/resources/icons/mark-x.svg">';
			trayObj.list.appendChild(trayObj.list.close);
			
			ui.toolbar.tray.insertBefore(trayObj.tray,ui.toolbar.tray.firstChild);
			ui.tray.content.insertBefore(trayObj.list,ui.tray.content.firstChild);
			
			ui.tray.minimized.push(trayObj);
			return trayObj;
		}
		
		ui.tray.toggle = function() {
			if (ui.tray.style.display == 'none') {
				var coverDiv = document.createElement("div");
				coverDiv.style.position = 'fixed';
				coverDiv.style.top = '0px';
				coverDiv.style.bottom = '0px';
				coverDiv.style.left = '0px';
				coverDiv.style.right = '0px';
				coverDiv.style.backgroundColor = 'transparent';
				coverDiv.style.zIndex = '4999996';
				coverDiv.id = 'UiTempCoverDivApp';
				coverDiv.onclick = function() {ui.tray.toggle();};
				document.body.appendChild(coverDiv);
				
				if (ui.tray.content.innerHTML == '') {
					ui.tray.content.innerHTML = '<div class="FlyUiText FlyUiNoSelect"><center><small><br>No windows are minimized.<br><br></small></center></div>';
				}
				if (ui.toolbar.isExpandedWindow) {
					ui.toolbar.expandedControls.style.right = '-192px';
				} else {
					ui.toolbar.tray.style.right = '-192px';
				}
				ui.toolbar.trayMsg.style.right = '32px';
				setTimeout(function(){ui.tray.style.display = 'block';ui.toolbar.trayExpand.innerHTML = '▲';},500);
			} else {
				document.getElementById('UiTempCoverDivApp').parentNode.removeChild(document.getElementById('UiTempCoverDivApp'));
				
				if (ui.tray.content.innerHTML == '<div class="FlyUiText FlyUiNoSelect"><center><small><br>No windows are minimized.<br><br></small></center></div>') {
					ui.tray.content.innerHTML = '';
				}
				ui.toolbar.trayExpand.innerHTML = '▼';
				if (ui.toolbar.isExpandedWindow) {
					ui.toolbar.expandedControls.style.right = '32px';
				} else {
					ui.toolbar.tray.style.right = '32px';
				}
				ui.toolbar.trayMsg.style.right = '-256px';
				ui.tray.style.display = 'none';
			}
		}
				
		var spacerDiv = document.createElement("div");
		spacerDiv.style.width = '100%';
		spacerDiv.style.height = ui.toolbar.offsetHeight+'px';
		document.body.appendChild(spacerDiv);
		
		var flyStylesheet = document.getElementById('FlyStylesheet');
		flyStylesheet.innerHTML += '.FlyWindowActive { margin-top: '+ui.toolbar.offsetHeight+'px; }';
		flyStylesheet.innerHTML += '.FlyWindowInactive { margin-top: '+ui.toolbar.offsetHeight+'px; }';
		flyStylesheet.innerHTML += '.FlyWindowMinimize { margin-top: '+ui.toolbar.offsetHeight+'px; }';
		flyStylesheet.innerHTML += '.FlyWindowTransparent { margin-top: '+ui.toolbar.offsetHeight+'px; }';
		flyStylesheet.innerHTML += '.FlyWindowExpand { margin-top: '+ui.toolbar.offsetHeight+'px; height: calc(100% - '+ui.toolbar.offsetHeight+'px) !important; }';

		ui.jump = document.createElement("div");
		ui.jump.className = 'FlyUiControlScaled';
		ui.jump.style.transformOrigin = '0px 0px';
		ui.jump.style.borderTopLeftRadius = '0px';
		ui.jump.style.borderTopRightRadius = '0px';
		ui.jump.style.width = '300px';
		ui.jump.style.height = '0px';
		ui.jump.setAttribute("data-height",'500px');
		ui.jump.style.display = 'none';
		ui.jump.style.position = 'fixed';
		ui.jump.style.transition = 'height .3s ease-in-out';
		ui.jump.style.visibility = 'visible';
		ui.jump.style.top = ui.toolbar.offsetHeight+'px';
		ui.jump.style.left = '0px';
		ui.jump.style.zIndex = '4999999';
		ui.jump.innerHTML = '<iframe allowtransparency="true" id="fly-ui-jump-list" frameborder="0" scrolling="no" style="height:456px;overflow:auto;width:295px;padding:2px;background-color:#FFFFFF;" src=""></iframe><div onclick="system.command(\'run:SprocketComputers.Options,page=personalization,action=jump\');ui.jump.toggle();" class="FlyUiTextHoverControl" style="margin-top:4px;padding:4px;height:36px;">Customize...</div>';
		document.body.appendChild(ui.jump);

		ui.jump.toggle = function() {
			if (ui.jump.style.display == 'none') {
				document.body.appendChild(ui.jump.coverDiv);
				document.getElementById('fly-ui-jump-list').src = '/system/components/jump.php';
			} else {
				ui.jump.style.height = '0px';
				ui.jump.coverDiv.parentNode.removeChild(ui.jump.coverDiv);
				setTimeout(function() {ui.jump.style.display = 'none';ui.jump.coverDiv.style.backgroundColor='rgba(0,0,0,0)';}, 300);
			}
		}
			
		ui.jump.coverDiv = document.createElement("div");
		ui.jump.coverDiv.style.position = 'fixed';
		ui.jump.coverDiv.style.top = '0px';
		ui.jump.coverDiv.style.bottom = '0px';
		ui.jump.coverDiv.style.left = '0px';
		ui.jump.coverDiv.style.right = '0px';
		ui.jump.coverDiv.style.backgroundColor = 'rgba(0,0,0,0)';
		ui.jump.coverDiv.style.transition = 'background-color .2s linear';
		ui.jump.coverDiv.style.zIndex = '4999998';
		ui.jump.coverDiv.onclick = ui.jump.toggle;
		ui.updateTime();
		
		return true;
	} else {
		return false;
	}
}
ui.updateTime = function() {
	var currentTime = new Date();
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	var seconds = currentTime.getSeconds();
	var year    = currentTime.getFullYear();
	var month   = currentTime.getMonth()+1; 
	var day     = currentTime.getDate();
	<?php
	echo $script_show_military;
	?>
	if (minutes < 10){
	    minutes = "0" + minutes;
	}
	if (seconds < 10){
	    seconds = "0" + seconds;
	}
	var v = hours + ":" + minutes + <?php echo $script_show_seconds; ?> + hr;
	var td = "<b>" + hours + ":" + minutes + ":" + seconds + hr + "</b><br>" + (month/1) + "/" + (day/1) + "/" + year;
	setTimeout("ui.updateTime()",1000);
	ui.toolbar.time.innerHTML=v;
	ui.time.content.timedate.innerHTML=td;
}
</script>