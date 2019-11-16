function View(Folder=false,List=false) {
	if (Folder && List) {

		var documentStyle = document.createElement('style');
		documentStyle.innerHTML = 'body,html { height: 100%; max-height: 100%; min-height:100%; overflow: hidden; }';
		document.head.appendChild(documentStyle);

		document.body.style.display = 'flex';
		document.body.style.flexDirection = 'column';
		document.body.style.flexWrap = 'wrap';
		document.body.style.justifyContent = 'flex-start';
		document.body.style.alignItems = 'flex-start';
		document.body.style.alignContent = 'flex-start';

		// Item object
		var item = document.createElement('div');
		item.className = 'FlyUiNoSelect FlyUiMenuItem FlyUiText';

		item.style.display = 'inline-block';
		item.style.boxSizing = 'border-box';
		item.style.padding = '6px';
		item.style.marginTop = '4px';
		item.style.marginLeft = '4px';
		item.style.cursor = 'default';
		item.style.overflow = 'hidden';

		item.style.height = '120px';
		item.style.width = '108px';

		item.style.verticalAlign = 'top';
		item.style.wordWrap = 'break-word';
		item.style.textAlign = 'center';
		item.style.fontSize = '14px';
		item.style.color = '#fff';
		item.style.textShadow = '0px 1px 4px #000';

		for (var i = 0; i < List.length; i++) {
			var itm = item.cloneNode(true);
			var icn;
			
			itm.id = i;
			
			icn = Icon(List[i]);
			icn.style.display = 'block';
			icn.style.width = '64px';
			icn.style.height = '64px';
			icn.style.margin = '0 auto';
			icn.style.marginBottom = '4px';
			itm.appendChild(icn);

			itm.innerHTML += List[i]['fname'];
			itm.title = List[i]['name'];
			itm.draggable = true;
			
			itm.onmousedown = function(e) {
				Select(this,e,List[this.id]);
			}
			itm.ondblclick = function() {
				window.top.system.command('run:'+List[this.id]['file']);
				Deselect();
			}
			itm.oncontextmenu = function(e) {
				Select(this,e,List[this.id]);
				ContextMenu(List[this.id],e);
				e.preventDefault();
				e.stopPropagation();
				return false;
			}			
			document.body.appendChild(itm);
		}
		document.oncontextmenu = function(e) {
			var folder = ContextMenu(Folder,e,true);
			folder.push([''],[
				'Icon View',
				[
					['Large Icons',function(){},{toggled:true,icon:'icon.xl.svg'}],
					['Medium Icons',function(){View.set('desktop.js');},{icon:'icon.lg.svg'}],
					['Small Icons',function(){View.set('desktop.sm.js');},{icon:'icon.md.svg'}]
				],
				{icon:'icon.xl.svg'}
			]);
			Fly.actionmenu(e,folder);
			e.preventDefault();
			return false;
		}

		var View = {
			set: function(view) {
				Fly.command('registry:set,ViewDesktop,'+view,View.callback);
			},
			callback: function(a) {
				Refresh();
			},
		};

		document.addEventListener('mousedown',function() {
			window.top.ui.toolbar.setActiveApplication('Desktop');
	
			window.top.task.index += 1;
			
			var activeWindows = window.top.document.getElementsByClassName('FlyWindowActive');
			for (i = 0; i < activeWindows.length; i++) { 
				activeWindows[i].window.checkIfBehind();
			}
		});

		window.top.ui.desktop.style.opacity = '1';

		window.Refresh = function() {
			try {
				window.top.shell.sound.system('click');
			} catch(e) {console.log(e);}
			
			window.top.ui.desktop.style.opacity = '0';
			setTimeout(function() {
				Fly.command('registry:get,ViewDesktop,SprocketComputers.zFileManager',function(a) {
					window.location.href = '?p='+Folder['file']+'&v='+a.return;
				});
			},500);
		}
	} else {
		throw "Folder or List is not valid";
	}
}
window.addEventListener('load',function() {
	document.body.style.color = '#fff';
	document.body.style.textShadow = '0px 0px 4px rgba(0,0,0,0.7)';
});