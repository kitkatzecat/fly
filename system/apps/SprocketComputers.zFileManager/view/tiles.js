function View(Folder=false,List=false) {
	if (Folder && List) {
		// Item object
		var item = document.createElement('div');
		item.className = 'FlyUiNoSelect FlyUiMenuItem FlyUiText';

		item.style.display = 'inline-block';
		item.style.boxSizing = 'border-box';
		item.style.padding = '4px';
		item.style.marginTop = '6px';
		item.style.marginLeft = '6px';
		item.style.cursor = 'default';
		item.style.overflow = 'hidden';

		item.style.height = '58px';
		item.style.width = '50%';
		item.style.minWidth = '250px';
		item.style.maxWidth = '320px';

		item.style.whiteSpace = 'nowrap';
		item.style.textAlign = 'left';
		item.style.fontSize = '16px';

		/* Icon object
		var icon = document.createElement('img');
		icon.className = 'FlyUiNoSelect';

		icon.style.width = '48px';
		icon.style.height = '48px';

		icon.style.verticalAlign = 'middle';
		icon.style.marginRight = '6px';
		icon.style.marginBottom = '4px';
		*/

		for (var i = 0; i < List.length; i++) {
			var itm = item.cloneNode(true);
			//var icn = icon.cloneNode(true);
			var icn;
			
			itm.id = i;
			
			icn = Icon(List[i]);
			icn.style.width = '48px';
			icn.style.height = '48px';
			icn.style.verticalAlign = 'middle';
			//icn.style.float = 'left';
			icn.style.marginRight = '6px';
			icn.style.marginBottom = '4px';
			itm.appendChild(icn);

			var title = document.createElement('div');
			title.style.display = 'inline-block';
			title.style.width = 'calc(100% - 56px)'; //100% - (width of icon + margin)
			
			var name = document.createElement('div');
			name.innerText = List[i]['fname'];
			name.style.width = '100%';
			name.style.overflow = 'hidden';
			name.style.whiteSpace = 'no-wrap';
			name.style.textOverflow = 'ellipsis';

			title.appendChild(name);

			var sub = Subtitle(List[i]);
			if (sub) {
				icn.style.verticalAlign = 'top';
				title.style.marginTop = '4px';
				var subtitle = document.createElement('div');
				subtitle.style.opacity = '0.8';
				subtitle.innerHTML = sub;
				subtitle.style.width = '100%';
				subtitle.style.overflow = 'hidden';
				subtitle.style.whiteSpace = 'no-wrap';
				subtitle.style.textOverflow = 'ellipsis';
					title.appendChild(subtitle);
			}

			itm.appendChild(title);
			itm.title = List[i]['name'];
			itm.draggable = true;
			
			itm.onmousedown = function(e) {
				Select(this,e,List[this.id]);
			}
			itm.ondblclick = function() {
				Click(List[this.id]);
				//window.parent.Nav(List[this.id]['file']);
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
			ContextMenu(Folder,e);
			e.preventDefault();
			return false;
		}
	} else {
		throw "Folder or List is not valid";
	}
}