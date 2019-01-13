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
		item.style.width = '96px';

		item.style.verticalAlign = 'top';
		item.style.wordWrap = 'break-word';
		item.style.textAlign = 'center';
		item.style.fontSize = '14px';
		item.style.whiteSpace = 'nowrap';
		item.style.textOverflow = 'ellipsis';

		document.body.style.overflow = 'hidden';

		// Strip
		var strip = document.createElement('div');
		strip.style.position = 'fixed';
		strip.style.bottom = '0px';
		strip.style.right = '0px';
		strip.style.left = '0px';
		strip.style.height = '136px';
		strip.style.whiteSpace = 'nowrap';
		strip.style.overflowY = 'hidden';
		strip.style.overflowX = 'auto';
		strip.style.borderTop = '1px solid #c0c0c0';
		document.body.appendChild(strip);
		
		var background = document.createElement('div');
		background.style.position = 'fixed';
		background.style.top = '0px';
		background.style.left = '0px';
		background.style.right = '0px';
		background.style.bottom = '136px';
		background.style.backgroundColor = '#fafafa';
		document.body.appendChild(background);

		var preview = document.createElement('div');
		preview.style.position = 'fixed';
		preview.style.top = '32px';
		preview.style.left = '32px';
		preview.style.right = '32px';
		preview.style.bottom = '200px';
		preview.style.backgroundPosition = 'center';
		preview.style.backgroundRepeat = 'no-repeat';
		preview.style.backgroundSize = 'contain';
		preview.style.backgroundImage = 'none';
		document.body.appendChild(preview);

		var title = document.createElement('div');
		title.style.position = 'fixed';
		title.style.left = '32px';
		title.style.right = '32px';
		title.style.bottom = '168px';
		title.className = 'FlyUiText';
		title.style.textAlign = 'center';
		title.style.fontSize = '16px';
		title.style.whiteSpace = 'nowrap';
		title.style.textOverflow = 'ellipsis';
		title.style.overflow = 'hidden';
		title.innerHTML = 'Select an image to see a preview';
		document.body.appendChild(title);

		View.ondeselect = function() {
			preview.style.backgroundImage = 'none';
			title.innerHTML = 'Select an image to see a preview';
		}

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
				if (List[this.id]['mime'].indexOf('image/') != -1) {
					preview.style.backgroundImage = 'url(\''+List[this.id]['URL']+'\')';
				} else {
					preview.style.backgroundImage = 'url(\''+List[this.id]['icon']+'\')';
				}
				title.innerHTML = List[this.id]['fname'];

				Select(this,e,List[this.id]);
			}
			itm.ondblclick = function() {
				Click(List[this.id]);
			}
			
			strip.appendChild(itm);
		}
	} else {
		document.body.innerHTML += Output;
	}
}