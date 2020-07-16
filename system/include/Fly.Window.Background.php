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

	Fly.window.ready = function() {}

	Fly.window.title = function() {
		return Fly.window.title.get();
	}
	Fly.window.title.get = function() {
		return window.top.document.getElementById(Fly.window.id).window.title;
	}
	Fly.window.title.set = function(title) {
		window.top.document.getElementById(Fly.window.id).window.setTitle(title);
		return window.top.document.getElementById(Fly.window.id).window.title;
	}
	Fly.window.title.hide = function() {}
	Fly.window.title.show = function() {}
	
	Fly.window.message = function() {}
	Fly.window.message.show = function() {}
	
	Fly.window.buttons = function() {}
	Fly.window.buttons.hide = function() {}
	Fly.window.buttons.show = function() {}
	
	Fly.window.border = function() {}
	Fly.window.border.hide = function() {}
	Fly.window.border.show = function() {}
	
	Fly.window.name = function() {
		return Fly.window.name.get();
	}
	Fly.window.name.get = function() {
		return window.top.document.getElementById(Fly.window.id).window.name;
	}
	Fly.window.name.set = function(name) {
		window.top.document.getElementById(Fly.window.id).window.setName(name);
		return window.top.document.getElementById(Fly.window.id).window.name;
	}

	Fly.window.icon = function() {
		return Fly.window.icon.get();
	}
	Fly.window.icon.get = function() {
		return window.top.document.getElementById(Fly.window.id).window.icon;
	}
	Fly.window.icon.set = function(icon) {
		window.top.document.getElementById(Fly.window.id).window.setIcon(icon);
		return window.top.document.getElementById(Fly.window.id).window.icon;
	}

	Fly.window.position = function() {
		return Fly.window.position.get();
	}
	Fly.window.position.get = function() {
		return [0,0];
	}
	Fly.window.position.set = function() {
		return [0,0];
	}

	Fly.window.size = function() {
		return Fly.window.size.get();
	}
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
	Fly.window.resize = function() {}
	Fly.window.resize.enable = function() {}
	Fly.window.resize.disable = function() {}

	Fly.window.close = function() {
		window.top.document.getElementById(Fly.window.id).window.forceClose();
	}
	Fly.window.close.enable = function() {}
	Fly.window.close.disable = function() {}
	Fly.window.onclose = Fly.window.close;
	Fly.window.sendToBack = function() {}
	Fly.window.bringToFront = function() {}
	Fly.window.minimize = function() {}
	Fly.window.minimize.enable = function() {}
	Fly.window.minimize.disable = function() {}
	Fly.window.maximize = function() {}
	Fly.window.expand = function() {}
	Fly.window.expand.enable = function() {}
	Fly.window.expand.disable = function() {}
	Fly.window.restore = function() {}

	Fly.window.focus = function() {}
	Fly.window.focus.self = function() {}
	Fly.window.focus.get = function() {}
	Fly.window.focus.set = function(id) {}
	Fly.window.focus.take = function(id) {}
	Fly.window.focus.give = function(id) {
		window.top.document.getElementById(id).window.resetFocus();
	}

	Fly.window.flash = function() {
		window.top.document.getElementById(Fly.window.id).window.flash();
	}
	
	Fly.window.movement = function() {}
	Fly.window.movement.set = function() {}
	Fly.window.movement.reset = function() {}

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
		window.top.task.create(window.top.document.getElementById(Fly.window.id).window.id,attributes);
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
