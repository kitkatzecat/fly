function View(Folder=false,List=false) {
	if (Folder && List) {
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

		item.style.height = 'auto';
		item.style.width = '76px';

		item.style.verticalAlign = 'top';
		item.style.wordWrap = 'break-word';
		item.style.textAlign = 'center';
		item.style.fontSize = '14px';

		for (var i = 0; i < List.length; i++) {
			var itm = item.cloneNode(true);
			var icn;
			
			itm.id = i;
			
			icn = Icon(List[i]);
			icn.style.display = 'block';
			icn.style.width = '32px';
			icn.style.height = '32px';
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
				if (List[this.id]['type'] == 'folder' || List[this.id]['extension'] == 'als') {
					Click(List[this.id]);
				} else {
					window.parent.Dialog.select(List[this.id]);
				}
			}
			itm.oncontextmenu = function(e) {
				Select(this,e,List[this.id]);
				e.preventDefault();
				e.stopPropagation();
				return false;
			}
			
			document.body.appendChild(itm);
		}

		document.oncontextmenu = function(e) {
			e.preventDefault();
			return false;
		}
	} else {
		throw "Folder or List is not valid";
	}
}