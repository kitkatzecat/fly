function View(Folder=false,List=false) {
	if (Folder && List) {
		// Item object
		var item = document.createElement('div');
		item.className = 'FlyUiNoSelect FlyUiMenuItem FlyUiText';

		item.style.display = 'block';
		item.style.boxSizing = 'border-box';
		item.style.padding = '6px';
		item.style.margin = '0px';
		item.style.cursor = 'default';
		item.style.overflow = 'hidden';

		item.style.height = 'auto';
		item.style.width = '100%';

		item.style.verticalAlign = 'top';
		item.style.textAlign = 'left';
		item.style.fontSize = '12px';
		item.style.whiteSpace = 'nowrap';
		item.style.textOverflow = 'ellipsis';

		document.body.style.overflow = 'hidden';

		// Strip
		var strip = document.createElement('div');
		strip.style.position = 'fixed';
		strip.style.top = '0px';
		strip.style.bottom = '0px';
		strip.style.left = '0px';
		strip.style.minWidth = '160px';
		strip.style.width = '25%';
		strip.style.whiteSpace = 'nowrap';
		strip.style.overflowX = 'hidden';
		strip.style.overflowY = 'auto';
		strip.style.borderRight = '1px solid #c0c0c0';
		document.body.appendChild(strip);
		
		var background = document.createElement('div');
		background.style.position = 'fixed';
		background.style.top = '0px';
		background.style.bottom = '0px';
		background.style.right = '0px';
		background.style.left = 'max(160px,25%)';
		background.style.backgroundColor = '#fafafa';
		document.body.appendChild(background);

		var preview = document.createElement('div');
		preview.style.position = 'fixed';
		preview.style.top = '16px';
		preview.style.left = 'max(172px,calc(25% + 16px))';
		preview.style.right = '16px';
		preview.style.bottom = '16px';
		preview.style.backgroundPosition = 'center';
		preview.style.backgroundRepeat = 'no-repeat';
		preview.style.backgroundSize = 'contain';
		preview.style.backgroundImage = 'none';
		document.body.appendChild(preview);

		View.ondeselect = function() {
			preview.style.backgroundImage = 'none';
		}

		if (List.length == 0) {
			strip.style.display = 'none';
			background.style.display = 'none';
			preview.style.display = 'none';
		}

		for (var i = 0; i < List.length; i++) {
			var itm = item.cloneNode(true);
			var icn;
			
			itm.id = i;
			
			icn = Icon(List[i]);
			icn.style.display = 'inline-block';
			icn.style.width = '18px';
			icn.style.height = '18px';
			icn.style.verticalAlign = 'middle';
			icn.style.marginRight = '6px';
			itm.appendChild(icn);

			itm.innerHTML += List[i]['fname'];
			itm.title = List[i]['name'];
			itm.draggable = true;
			
			itm.onmousedown = function(e) {
				if (List[this.id]['mime'].indexOf('image/') != -1) {
					preview.style.backgroundImage = 'url(\''+List[this.id]['URL']+'\')';
				} else {
					preview.style.backgroundImage = 'url(\''+List[this.id]['icon']+'\')';
				}

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
			strip.appendChild(itm);
		}
		document.oncontextmenu = function(e) {
			e.preventDefault();
			return false;
		}
	} else {
		throw "Folder or List is not valid";
	}
}