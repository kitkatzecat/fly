<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.WINDOW');

?>
<script>
if (typeof Fly == "undefined") {
	var Fly = {};
}

if (typeof Fly.window == "undefined") {
	Fly.window = {};
	Fly.window.id = '<?php echo $_GET['Fly_Id']?>';
	if (Fly.window.id == '') {
		try {
			Fly.window.id = window.parent.Fly.window.id;
		} catch(err) {}
	}
	
	Fly.window.frame = window.top.document.getElementById(Fly.window.id);

	Fly.window.ready = function() {}

	Fly.window.title = function() {
		return Fly.window.title.get();
	}
	Fly.window.title.get = function() {
		return Fly.window.frame.window.title;
	}
	Fly.window.title.set = function(title) {
		Fly.window.frame.window.setTitle(title);
		return Fly.window.frame.window.title;
	}
	Fly.window.title.hide = function() {
		Fly.window.frame.window.hideTitlebar();
	}
	Fly.window.title.show = function() {
		Fly.window.frame.window.showTitlebar();
	}
	
	Fly.window.message = function(msg,duration=8) {
		return Fly.window.message.show(msg,duration);
	}
	Fly.window.message.show = function(msg,duration=8) {
		Fly.window.frame.window.showMessage(msg,duration);
	}
	
	Fly.window.buttons = function() {
		return Fly.window.buttons.hide();
	}
	Fly.window.buttons.hide = function() {
		Fly.window.frame.window.hideButtons();
	}
	Fly.window.buttons.show = function() {
		Fly.window.frame.window.showButtons();
	}

	Fly.window.hide = function() {
		Fly.window.frame.style.display = 'none';
		Fly.window.frame.window.isBackground = true;

		Fly.window.hide.handles.sizeSet = Fly.window.size.set;
		Fly.window.hide.handles.sizeGet = Fly.window.size.get;
		Fly.window.hide.handles.positionSet = Fly.window.position.set;
		Fly.window.hide.handles.positionGet = Fly.window.position.get;

		Fly.window.size.get = function() {
			var height = window.top.innerHeight;
			if (typeof window.top.ui.toolbar !== 'undefined') {
				height -= window.top.ui.toolbar.offsetHeight;
			}
			return [window.top.innerWidth,height];
		}
		Fly.window.size.set = function() {
			return Fly.window.size.get();
		}
		Fly.window.position.get = function() {
			return [0,0];
		}
		Fly.window.position.set = function() {
			return [0,0];
		}
	}
	Fly.window.hide.handles = {};
	Fly.window.unhide = function() {
		Fly.window.frame.style.display = 'block';
		Fly.window.frame.window.isBackground = false;

		Fly.window.size.set = Fly.window.hide.handles.sizeSet;
		Fly.window.size.get = Fly.window.hide.handles.sizeGet;
		Fly.window.position.set = Fly.window.hide.handles.positionSet;
		Fly.window.position.get = Fly.window.hide.handles.positionGet;
	}
	
	Fly.window.border = function() {
		return Fly.window.border.hide();
	}
	Fly.window.border.hide = function() {
		Fly.window.frame.window.hideBorder();
	}
	Fly.window.border.show = function() {
		Fly.window.frame.window.showBorder();
	}
	
	Fly.window.name = function() {
		return Fly.window.name.get();
	}
	Fly.window.name.get = function() {
		return Fly.window.frame.window.name;
	}
	Fly.window.name.set = function(name) {
		Fly.window.frame.window.setName(name);
		return Fly.window.frame.window.name;
	}

	Fly.window.icon = function() {
		return Fly.window.icon.get();
	}
	Fly.window.icon.get = function() {
		return Fly.window.frame.window.icon;
	}
	Fly.window.icon.set = function(icon) {
		Fly.window.frame.window.setIcon(icon);
		return Fly.window.frame.window.icon;
	}

	Fly.window.position = function() {
		return Fly.window.position.get();
	}
	Fly.window.position.get = function() {
		if (Fly.window.frame.window.isExpand) {
			return [0,0];
		} else {
			return [parseInt(Fly.window.frame.style.left.replace(/\\D/g,'')),parseInt(Fly.window.frame.style.top.replace(/\\D/g,''))];
		}
	}
	Fly.window.position.set = function(x=32,y=32) {
		Fly.window.frame.window.setPosition(x,y);
		return [parseInt(Fly.window.frame.style.left.replace(/\\D/g,'')),parseInt(Fly.window.frame.style.top.replace(/\\D/g,''))];
	}
	Fly.window.position.center = function() {
		var windowHeight = Fly.window.frame.offsetHeight;
		var windowWidth = Fly.window.frame.offsetWidth;

		var screenHeight = window.top.innerHeight;
		try {
			screenHeight -= window.top.ui.toolbar.offsetHeight;
		} catch(e) {
			console.log('Fly.window.position.center - Couldn\'t get toolbar size: UI may not be active');
		}
		var screenWidth = window.top.innerWidth;

		var x = (screenWidth/2)-(windowWidth/2);
		var y = (screenHeight/2)-(windowHeight/2);

		return Fly.window.position.set(x,y);
	}

	Fly.window.size = function() {
		return Fly.window.size.get();
	}
	Fly.window.size.get = function() {
		return [parseInt(Fly.window.frame.window.content.offsetWidth),parseInt(Fly.window.frame.window.content.offsetHeight)];
	}
	Fly.window.size.set = function(width=320,height=240) {
		Fly.window.frame.window.setSize(width,height);
		return [parseInt(Fly.window.frame.window.content.style.width.replace(/\\D/g,'')),parseInt(Fly.window.frame.window.content.style.height.replace(/\\D/g,''))];
	}
	Fly.window.resize = function() {
		return Fly.window.resize.enable();
	}
	Fly.window.resize.enable = function() {
		Fly.window.frame.window.isResizable = true;
	}
	Fly.window.resize.disable = function() {
		Fly.window.frame.window.isResizable = false;
	}

	Fly.window.close = function() {
		Fly.window.frame.window.forceClose();
	}
	Fly.window.close.enable = function() {
		Fly.window.frame.window.composition.close.status.show();
	}
	Fly.window.close.disable = function() {
		Fly.window.frame.window.composition.close.status.hide();
	}
	Fly.window.onclose = Fly.window.close;
	Fly.window.sendToBack = function() {
		Fly.window.frame.window.sendToBack();
	}
	Fly.window.bringToFront = function() {
		Fly.window.frame.window.bringToFront();
	}
	Fly.window.minimize = function() {
		Fly.window.frame.window.minimize();
	}
	Fly.window.minimize.enable = function() {
		Fly.window.frame.window.composition.minimize.status.show();
	}
	Fly.window.minimize.disable = function() {
		Fly.window.frame.window.composition.minimize.status.hide();
	}
	Fly.window.maximize = function() {
		Fly.window.frame.window.maximize();
	}
	Fly.window.expand = function() {
		Fly.window.frame.window.expand();
	}
	Fly.window.expand.enable = function() {
		Fly.window.frame.window.composition.expand.status.show();
	}
	Fly.window.expand.disable = function() {
		Fly.window.frame.window.composition.expand.status.hide();
	}
	Fly.window.restore = function() {
		Fly.window.frame.window.restore();
	}

	Fly.window.focus = function() {
		Fly.window.focus.self();
	}
	Fly.window.focus.self = function() {
		Fly.window.frame.window.resetFocus();
	}
	Fly.window.focus.get = function() {
		return Fly.window.frame.window.focus;
	}
	Fly.window.focus.set = function(id) {
		return Fly.window.frame.window.setFocus(id);
	}
	Fly.window.focus.take = function(id) {
		window.top.document.getElementById(id).window.setFocus(Fly.window.id);
	}
	Fly.window.focus.give = function(id) {
		window.top.document.getElementById(id).window.resetFocus();
	}

	Fly.window.flash = function() {
		Fly.window.frame.window.flash();
	}
	
	Fly.window.movement = function(x,y,w,h) {
		return Fly.window.movement.set(x,y,w,h);
	}
	Fly.window.movement.set = function(x,y,w,h) {
		Fly.window.frame.window.composition.secondaryMovement.set(x,y,w,h);
	}
	Fly.window.movement.reset = function() {
		Fly.window.frame.window.composition.secondaryMovement.reset();
	}

	Fly.window.child = function(a,b) {
		return Fly.window.child.open(a,b);
	}
	Fly.window.child.open = function(options={modal:false,attributes:{}},callback=function(){}) {
		
		attributes = Object.assign({title:'Untitled', name:'Untitled', icon:'', x:'center', y:'center', width:320, height:240, location:'/system/components/document-otf.php?content=PGRpdiBjbGFzcz0iRmx5VWlUZXh0IiBzdHlsZT0icG9zaXRpb246YWJzb2x1dGU7dG9wOjBweDtsZWZ0OjBweDtyaWdodDowcHg7Ym90dG9tOjBweDtiYWNrZ3JvdW5kOiNmZmZmZmY7cGFkZGluZzo4cHg7Ij5ObyBjb250ZW50IHByb3ZpZGVkPC9zcGFuPg==', expand:false, minimize:true, close:true, resize:false, background:false, minheight:60, minwidth:100, maxheight:false, maxwidth:false, maxinitheight:false, maxinitwidth:false},options.attributes);

		attributes.reload = function(frame) {
			Fly.window.child.children[frame.id]['window'] = frame.window.content.contentWindow;
		}

		if ((typeof attributes.x != 'undefined' && attributes.x == 'auto') || (typeof attributes.y != 'undefined' && attributes.y == 'auto')) {
			pos = Fly.window.position.get();
			attributes.x = parseInt(pos[0])+32;
			attributes.y = parseInt(pos[1])+32;
		}
		if ((typeof attributes.x != 'undefined' && attributes.x == 'center') || (typeof attributes.y != 'undefined' && attributes.y == 'center')) {
			pos = Fly.window.position.get();
			size = Fly.window.size.get();
			attributes.x = parseInt(pos[0])+((parseInt(size[0])/2)-(attributes.width/2));
			attributes.y = parseInt(pos[1])+((parseInt(size[1])/2)-(attributes.height/2));
		}

		if (typeof options.modal != 'undefined' && options.modal) {
			attributes.load = function(frame) {
				Fly.window.child.children[frame.id] = {};
				Fly.window.child.children[frame.id]['frame'] = frame;
				Fly.window.child.children[frame.id]['modal'] = true;
				Fly.window.focus.set(frame.id);
				Fly.window.child.children[frame.id]['window'] = frame.window.content.contentWindow;

				frame.window.closeWindow = frame.window.forceClose;
				frame.window.forceClose = function() {
					try {
						Fly.window.focus.self();
						Fly.window.bringToFront();
					} catch(e) {
						console.log(e);
					}
					frame.window.closeWindow();
				}
				frame.window.composition.buttons.close.onclick = frame.window.close;

				try {
					callback(Fly.window.child.children[frame.id]);
				} catch(e) {console.log(e);}
			}
		} else {
			attributes.load = function(frame) {
				Fly.window.child.children[frame.id] = {};
				Fly.window.child.children[frame.id]['frame'] = frame;
				Fly.window.child.children[frame.id]['modal'] = false;
				Fly.window.child.children[frame.id]['window'] = frame.window.content.contentWindow;

				frame.window.closeWindow = frame.window.forceClose;
				frame.window.forceClose = function() {
					try {
						Fly.window.focus.self();
						Fly.window.bringToFront();
					} catch(e) {
						console.log(e);
					}
					frame.window.closeWindow();
				}
				frame.window.composition.buttons.close.onclick = frame.window.close;
				
				try {
					callback(Fly.window.child.children[frame.id]);
				} catch(e) {console.log(e);}
			}
		}
		window.top.task.create(Fly.window.frame.window.id,attributes);
	}
	Fly.window.child.children = {};
	Fly.window.child.close = function() {

	}
	Fly.window.open = Fly.window.child.open;
	Fly.window.open.application = function(app='',options={},callback=function(){}) {
		options = Object.assign({modal:false,attibutes:{}},options);

		if (!Fly.hasOwnProperty('command')) {
			throw 'Error: Fly.Command is required';
		}

		Fly.command('fileprocess:'+app,function(r) {
			process = r['return'];
			if (process && process['type'] == 'application') {
				Fly.window.child.open({
					modal: options.modal,
					attributes: Object.assign(process['window'],options.attributes)
				},callback);
			} else {
				throw `Error: "${app}" is not a valid application`;
			}
		});
	}
	Fly.window.child.application = Fly.window.open.application;

	Fly.window.disableContext = function() {
		document.addEventListener('contextmenu',function(e) {
			e.preventDefault();
			e.stopPropagation();
			return false;
		});
	}

}
</script>
