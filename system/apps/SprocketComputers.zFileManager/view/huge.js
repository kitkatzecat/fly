function View() {
	if (Folder && List) {
		// Item object
		var item = document.createElement('div');
		item.className = 'FlyUiNoSelect FlyUiMenuItem FlyUiText';

		item.style.display = 'inline-block';
		item.style.boxSizing = 'border-box';
		item.style.padding = '6px';
		item.style.marginTop = '6px';
		item.style.marginLeft = '6px';
		item.style.cursor = 'default';
		item.style.overflow = 'hidden';

		item.style.height = 'auto';
		item.style.width = '288px';

		item.style.verticalAlign = 'top';
		item.style.wordWrap = 'break-word';
		item.style.textAlign = 'center';
		item.style.fontSize = '16px';

		for (var i = 0; i < List.length; i++) {
			var itm = item.cloneNode(true);
			var icn;
			
			itm.id = i;
			
			icn = Icon(List[i]);
			icn.style.display = 'block';
			icn.style.width = '256px';
			icn.style.height = '256px';
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
				Click(List[this.id]);
			}
			itm.oncontextmenu = function(e) {
				Select(this,e,List[this.id]);
				ContextMenu(List[this.id],e);
				e.preventDefault();
				return false;
			}			
			document.body.appendChild(itm);
		}
		document.oncontextmenu = function(e) {
			ContextMenu(Folder,e);
			e.preventDefault();
			e.stopPropagation();
			return false;
		}
	} else {
		document.body.innerHTML += Output;
	}
}