function View() {
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
		item.style.textOverflow = 'ellipsis';
		item.style.textAlign = 'left';
		item.style.fontSize = '16px';

		// Icon object
		var icon = document.createElement('img');
		icon.className = 'FlyUiNoSelect';

		icon.style.width = '48px';
		icon.style.height = '48px';

		icon.style.verticalAlign = 'middle';
		icon.style.marginRight = '6px';
		icon.style.marginBottom = '4px';

		for (var i = 0; i < List.length; i++) {
			var itm = item.cloneNode(true);
			var icn = icon.cloneNode(true);
			
			itm.id = i;
			
			icn.src = List[i]['icon'];
			icn.draggable = false;
			itm.appendChild(icn);
			itm.innerHTML += List[i]['fname'];
			itm.title = List[i]['name'];
			itm.draggable = true;
			
			itm.onmousedown = function(e) {
				Select(this,e);
			}
			itm.ondblclick = function() {
				window.parent.Nav(List[this.id]['file']);
			}
			
			document.body.appendChild(itm);
		}
	} else {
		document.body.innerHTML += Output;
	}
}