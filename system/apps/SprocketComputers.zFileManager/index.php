<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Actionbar.php';
include 'Fly.Command.php';
include 'Fly.Dialog.php';

if (isset($_GET['p'])) {
	$p = $_GET['p'];
} else {
	$p = '?home';
}
$p = base64_encode($p);

$views = json_decode(file_get_contents($_FLY['WORKING_PATH'].'view/views.json'),true);
$cv = FlyRegistryGet('View');
$vm = '';
$vl = '{';
$vc = 0;
foreach ($views as $k => $v) {
	if ($v['src'] == $cv) {
		$vm .= '[\''.$k.'\',function(){View.set(\''.$v['src'].'\');},{icon:\''.FlyVarsReplace($v['icon']).'\',toggled:true}],';
	} else {
		$vm .= '[\''.$k.'\',function(){View.set(\''.$v['src'].'\');},{icon:\''.FlyVarsReplace($v['icon']).'\'}],';
	}
	$vl .= '\''.$v['src'].'\':'.$vc.',';
	$vc++;
}
$vl .= '}';
$vl = str_lreplace(',','',$vl);

$vm = str_lreplace(',','',$vm);

?>
<script>
var Menubar;
var Navbar;
var Panebar;
var Addressbar;
var Panes = {};
Fly.window.ready = function() {
	ToolbarInit();
	Fly.window.disableContext();
	<?php if (FlyRegistryGet('ShowStatusBar') == 'true') { echo 'StatusBar.show();'; } ?>
	Nav(atob('<?php echo $p; ?>'));
}

Fly.window.onclose = function() {
	Dialog.closeAll();
	Fly.window.close();
}

function ToolbarInit() {
	Menubar = new Fly.actionbar();
	Menubar.style.position = 'absolute';
	Menubar.style.top = '0px';
	Menubar.style.left = '0px';
	Menubar.style.width = 'auto';
	
	Menubar.add({text:'File',type:'dropdown',menu:[
		['New...',function() {Dialog.open('dialogs.php?','New',300,300,false);},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg'}],
		[''],
		['Properties',Properties,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'}],
		[''],
		['Close',Close,{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}]
	]});
	Menubar.add({text:'Edit',type:'dropdown',menu:[
		['Copy to',function(){},{disabled:true}],
		['Move to',function(){},{disabled:true}],
		[''],
		['Keywords',function(){Nav('?keywords');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>go.svg'}],
		['Options',function(){Nav('?options');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg'}]
	]});
	Menubar.add({text:'View',type:'dropdown',menu:[
		['Home',function(){Nav('?home');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg'}],
		[''],
		['Icon View',[
			<?php echo $vm; ?>,
			[''],
			['Subtitle...',function(){Subtitle.dialog()}]
		],{icon:'icon.xl.svg'}],
		['Image Previews',function(){ImagePreviews.toggle();},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg'}],
		['File Extensions',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg'}],
		['System Files',function(){SystemFiles.toggle();},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg'}],
		[''],
		['Status Bar',function(){StatusBar.toggle();},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>index.svg'}],
		[''],
		['Panes',[
			['Search',function(){Pane.toggle('search');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg'}],
			['Properties',function(){Pane.toggle('properties');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg'}],
			['Bookmarks',function(){Pane.toggle('bookmarks');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-star.svg'}],
			['Folders',function(){Pane.toggle('folders');},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg'}],
			[''],
			['Hide',Pane.hide]
		]]
	]});
	
	document.body.appendChild(Menubar);

	Panebar = new Fly.actionbar();
	Panebar.style.position = 'absolute';
	Panebar.style.top = '0px';
	Panebar.style.right = '0px';
	Panebar.style.width = 'auto';
	
	Panes.search = {};
	Panes.search.url = 'panes.php?pane=search';
	Panes.search.button = Panebar.add({text:'',title:'Search',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>search.svg',action:function(){Pane.toggle('search');}});
	//Panes.search.button.style.borderRadius = '50px';
	//Panes.search.button.style.lineHeight = '12px';
	
	Panes.properties = {};
	Panes.properties.url = 'panes.php?pane=properties';
	Panes.properties.button = Panebar.add({text:'',title:'Properties',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>properties.svg',action:function(){Pane.toggle('properties');}});
	//Panes.properties.button.style.borderRadius = '50px';
	//Panes.properties.button.style.lineHeight = '12px';
	
	Panes.bookmarks = {};
	Panes.bookmarks.url = 'panes.php?pane=bookmarks';
	Panes.bookmarks.button = Panebar.add({text:'',title:'Bookmarks',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-star.svg',action:function(){Pane.toggle('bookmarks');}});
	//Panes.bookmarks.button.style.borderRadius = '50px';
	//Panes.bookmarks.button.style.lineHeight = '12px';
	
	Panes.folders = {};
	Panes.folders.url = 'panes.php?pane=folders';
	Panes.folders.button = Panebar.add({text:'',title:'Folders',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>folder.svg',action:function(){Pane.toggle('folders');}});
	//Panes.folder.button.style.borderRadius = '50px';
	//Panes.folder.button.style.lineHeight = '12px';
	
	document.body.appendChild(Panebar);

	Navbar = new Fly.actionbar();
	Navbar.style.position = 'absolute';
	Navbar.style.top = '34px';
	Navbar.style.right = '0px';
	Navbar.style.left = '0px';
	Navbar.style.width = 'auto';
	Navbar.style.transition = 'right 0.2s ease-in-out';
	
	Addressbar = document.createElement("input");
	Addressbar.type = 'text';
	Addressbar.className = 'addressbar transparent-white FlyUiTextHighlight';
	Addressbar.onkeydown = function(event) {
		if (event.keyCode == 13) {
			Go();
		}
	}
	Addressbar.onfocus = function() {
		Addressbar.className = 'addressbar-focus';
		setTimeout(function(){Addressbar.select()},100);
	}
	Addressbar.onblur = function() {
		Addressbar.className = 'addressbar transparent-white FlyUiTextHighlight';
		if (window.getSelection) {window.getSelection().removeAllRanges();}
		else if (document.selection) {document.selection.empty();}
	}
	
	Navbar.add({text:'',title:'Back',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-left.svg',action:Back});
	Navbar.add({text:'',title:'Forward',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-right.svg',action:Forward});
	Navbar.add({text:'',title:'Up',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>arrow-up.svg',action:Up});
	var ab = Navbar.add({type:'custom',content:Addressbar});
	ab.style.width = 'calc(100% - 200px)';
	Navbar.add({text:'',title:'Refresh',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg',action:Refresh,align:'right'});
	Navbar.add({text:'Go',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>go.svg',action:Go,align:'right'});
	
	document.body.appendChild(Navbar);
}

var New = {
	text: function() {
		
	},
	image: function() {
		
	}
};
function Properties() {
	window.top.system.command('run:SprocketComputers.zFileManager.Properties,file='+SelectedFile['file']);
}
function Close() {
	Fly.window.close();
}
function Go() {
	Nav(Addressbar.value);
	Addressbar.blur();
}

function Nav(path,clearForward=true) {
	window.top.shell.sound.system('click');
	if (clearForward) {
		Nav.forward = [];
	}
	Addressbar.value = '';
	document.getElementById('statusbar').innerHTML = 'Loading...';
	document.getElementById('frame-main').style.display = 'none';
	if (path.indexOf('?') == 0) {
		var file = path.replace('?','');
		Addressbar.value = 'Loading...';
		Fly.window.icon.set('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>file.svg');
		Fly.window.title.set('Loading...');
		document.getElementById('frame-main').src = 'list.php?p='+encodeURIComponent(path);

		Nav.current = path;

		Nav.back.push(path);
	} else {
		Fly.command('fileprocess:'+path,function(pth){
			if (pth['return'].hasOwnProperty('ffile')) {
				Addressbar.value = pth['return']['ffile'];
				Fly.window.icon.set(pth['return']['icon']);
				Fly.window.title.set(pth['return']['fname']);
			} else {
				Addressbar.value = path;
				Fly.window.icon.set('<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>error.svg');
				Fly.window.title.set('Not Found');
			}
			Nav.current = pth['return'];

			if (pth['return']['extension'] !== 'als') {
				Nav.back.push(pth['return']['ffile']);
			}
		});
		document.getElementById('frame-main').src = 'list.php?p='+encodeURIComponent(path);
	}
}
Nav.current = false;
Nav.back = [];
Nav.forward = [];

function Back() {
	if (Nav.back.length > 1) {
		Nav.forward.push(Nav.back.pop());
		Nav(Nav.back.pop(),false);
	}
}
function Forward() {
	if (Nav.forward.length > 0) {
		Nav(Nav.forward.pop(),false);
	}
}
function Up() {
	Nav(Nav.current['fpath']);
}
function Refresh(pos=false) {
	if (!!pos) {
		var frame = document.getElementById('frame-main');
		var a = function() {
			frame.contentWindow.scrollTo(0,pos);
			frame.removeEventListener('load',a);
		}
		frame.addEventListener('load',a);
	}
	if (typeof Nav.current['ffile'] !== 'undefined') {
		Nav(Nav.current['ffile']);
	} else {
		Nav(Nav.current);
	}
}

function OnSelectionChange() {
	if (Pane.visiblePane == 'properties') {
		var frame = document.getElementById('frame-pane');

		frame.contentWindow.ShowProperties(SelectedFile);
	}
}

var Pane = {
	toggle: function(pane) {
		if (Pane.visible && Pane.visiblePane !== pane) {
			Pane.reshow(pane);
		} else if (Pane.visible && Pane.visiblePane == pane) {
			Pane.hide();
		} else {
			Pane.show(pane);
		}
	},
	show: function(pane) {
		document.getElementById('frame-pane').src = Panes[pane].url;
			Panes[pane].button.toggleOn();
		document.getElementById('frame-pane').onload = function() {
			Object.keys(Panes).map(function(key) {
				Panes[key].button.toggleOff();
			});
			Panes[pane].button.toggleOn();

			document.getElementById('main').style.right = '160px';
			Navbar.style.right = '160px';
			Pane.visiblePane = pane;
			Pane.visible = true;
			Pane.timeout = setTimeout(function() {document.getElementById('pane').style.display = 'block';},200);
			document.getElementById('frame-pane').onload = function() {};
		}
	},
	reshow: function(pane) {
		Pane.hide();
		Panes[pane].button.toggleOn();
		setTimeout(function(){Pane.show(pane);},200);
	},
	hide: function() {
		Pane.visiblePane = '';
		Pane.visible = false;
		Object.keys(Panes).map(function(key) {
			Panes[key].button.toggleOff();
		});
		document.getElementById('main').style.right = '0px';
		Navbar.style.right = '0px';
		clearTimeout(Pane.timeout);
		document.getElementById('frame-pane').src = '';
		document.getElementById('pane').style.display = 'none';
	},
	visible: false,
	visiblePane: '',
	timeout: false
};

var View = {
	set: function(view) {
		View.setting = view;
		Fly.command('registry:set,View,'+view,View.callback);
	},
	callback: function(a) {
		if (!a.return) {
			Fly.window.message.show('An error occurred while saving your options to the registry');
			View.setting = false;
		} else {
			Menubar.buttons[2].menu.options[2].menu.options[View.list[View.current]].toggleOff();
			Menubar.buttons[2].menu.options[2].menu.options[View.list[View.setting]].toggleOn();
			View.current = View.setting;
			View.setting = false;
		}
		Refresh(document.getElementById('frame-main').contentWindow.pageYOffset);
	},
	list: <?php echo $vl; ?>,
	current: '<?php echo $cv; ?>',
	setting: false
};
var Subtitle = {
	options: {
		'none': 'None',
		'type': 'Type',
		'mime': 'MIME',
		'size': 'Size',
		//'accessed': 'Date Accessed',
		'modified': 'Modified',
		'modified-date': 'Date Modified'
	},
	dialog: function() {
		var options = [];
		for (let type in Subtitle.options) {
			let o = {
				text: Subtitle.options[type],
				value: type,
				selected: (type == Subtitle.show)
			};
			options.push(o);
		}
		Fly.dialog.select({
			title: 'Item Subtitle',
			message: 'Item subtitle',
			content: 'Some views can show a property of the item as a subtitle along with the item\'s name and icon.</p><p>Select which property to show as the subtitle:',
			icon: `${Fly.core['WORKING_URL']}icon.ls.svg`,
			options: options,
			callback: Subtitle.set
		});
	},
	show: '<?php echo FlyRegistryGet('ItemSubtitle'); ?>',
	setting: false,
	set: function(subtitle) {
		Subtitle.setting = subtitle;
		Fly.command('registry:set,ItemSubtitle,'+subtitle,Subtitle.callback);
	},
	callback: function(a) {
		if (!a.return) {
			Fly.window.message.show('An error occurred while saving your options to the registry');
			Subtitle.setting = false;
		} else {
			Subtitle.show = Subtitle.setting;
			Subtitle.setting = false;
		}
		Refresh(document.getElementById('frame-main').contentWindow.pageYOffset);
	},
}

var StatusBar = {
	visible: false,
	show: function() {
		var frame = document.getElementById('main');
		var pane = document.getElementById('pane');
		var bar = document.getElementById('statusbar');

		frame.style.bottom = '24px';
		pane.style.bottom = '24px';
		bar.style.display = 'block';
		StatusBar.visible = true;
		Menubar.buttons[2].menu.options[7].toggleOn();

		Fly.command('registry:set,ShowStatusBar,true',function(a) {
			if (!a.return) {
				Fly.window.message.show('An error occurred while saving your options to the registry');
			}
		});
	},
	hide: function() {
		var frame = document.getElementById('main');
		var pane = document.getElementById('pane');
		var bar = document.getElementById('statusbar');

		frame.style.bottom = '0px';
		pane.style.bottom = '0px';
		bar.style.display = 'block';
		StatusBar.visible = false;
		Menubar.buttons[2].menu.options[7].toggleOff();

		Fly.command('registry:set,ShowStatusBar,false',function(a) {
			if (!a.return) {
				Fly.window.message.show('An error occurred while saving your options to the registry');
			}
		});
	},
	toggle: function() {
		if (StatusBar.visible) {
			StatusBar.hide();
		} else {
			StatusBar.show();
		}
	}
}

var ImagePreviews = {
	visible: false,
	show: function() {
		ImagePreviews.visible = true;
		ImagePreviews.toggleOn();

		Fly.command('registry:set,ShowImagePreviews,true',function(a) {
			Refresh(document.getElementById('frame-main').contentWindow.pageYOffset);
			if (!a.return) {
				Fly.window.message.show('An error occurred while saving your options to the registry');
			}
		});
	},
	hide: function() {
		ImagePreviews.visible = false;
		ImagePreviews.toggleOff();

		Fly.command('registry:set,ShowImagePreviews,false',function(a) {
			Refresh(document.getElementById('frame-main').contentWindow.pageYOffset);
			if (!a.return) {
				Fly.window.message.show('An error occurred while saving your options to the registry');
			}
		});
	},
	toggleOn: function() {
		Menubar.buttons[2].menu.options[3].toggleOn();
	},
	toggleOff: function() {
		Menubar.buttons[2].menu.options[3].toggleOff();
	},
	toggle: function() {
		if (ImagePreviews.visible) {
			ImagePreviews.hide();
		} else {
			ImagePreviews.show();
		}
	}
}

var SystemFiles = {
	visible: false,
	show: function() {
		SystemFiles.visible = true;
		SystemFiles.toggleOn();

		Fly.command('registry:set,ShowSystemFiles,true',function(a) {
			Refresh(document.getElementById('frame-main').contentWindow.pageYOffset);
			if (!a.return) {
				Fly.window.message.show('An error occurred while saving your options to the registry');
			}
		});
	},
	hide: function() {
		SystemFiles.visible = false;
		SystemFiles.toggleOff();

		Fly.command('registry:set,ShowSystemFiles,false',function(a) {
			Refresh(document.getElementById('frame-main').contentWindow.pageYOffset);
			if (!a.return) {
				Fly.window.message.show('An error occurred while saving your options to the registry');
			}
		});
	},
	toggleOn: function() {
		Menubar.buttons[2].menu.options[5].toggleOn();
	},
	toggleOff: function() {
		Menubar.buttons[2].menu.options[5].toggleOff();
	},
	toggle: function() {
		if (SystemFiles.visible) {
			SystemFiles.hide();
		} else {
			Fly.dialog.confirm({title:'System Files',message:'Show system files?',content:'Are you sure you want to show system files and folders? Changing the contents of these files can harm your computer.',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg',callback:function(r){
				if (r) {
					SystemFiles.show();
				}
			}});
		}
	}
}
var Dialog = {
	open: function(url='dialogs.php?',title='<?php echo $_FLY['APP']['NAME']; ?>',width=300,height=300,linked=true) {
		var pos = Fly.window.position.get();
		var size = Fly.window.size.get();
		var x = parseInt(pos[0])+parseInt((size[0]/2)-(width/2));
		var y = parseInt(pos[1])+parseInt((size[1]/2)-(height/2));
		url = '<?php echo $_FLY['WORKING_URL'];?>'+url;

		var opened = window.top.task.create('<?php echo $_FLY['APP']['ID']; ?>',{name:title,title:title,width:width,height:height,icon:'<?php echo $_FLY['APP']['ICON_URL']; ?>',x:x,y:y,location:url,load:function(w){
			if (linked) {
				var win = w.window.content.contentWindow;
				win.Fly.window.focus.take(Fly.window.id);
				win.Parent = window;
				win.Fly.window.onclose = function() {
					win.Fly.window.focus.give(Fly.window.id);
					Fly.window.bringToFront();
					win.Fly.window.close();
				}
			}
		}});
		if (linked) {
			Dialog.opened.push(opened);
		}
		return opened;
	},
	opened: [],
	closeAll: function() {
		Dialog.opened.forEach(function(a){
			try {
				a.window.forceClose();
			} catch(err) {console.log(err);}
		});
	}
};

var SelectedFile = CurrentLocation;

function FrameLoad() {
	var frame = document.getElementById('frame-main');

	frame.style.display = 'block';
}

</script>
<style>
#main {
	position: absolute;
	top: 68px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	transition: right 0.2s ease-in-out;
	background: #fff;
	z-index: 2;
	cursor: wait;
}
.addressbar-focus {
	margin-left: 4px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	cursor: text;
	font-size: 14px;
	text-align: center;
	width: 100%;
	background-color: #fff !important;
	text-shadow: none !important;
	color: #000 !important;
	border-color: #000 !important;
}
.addressbar {
	margin-left: 4px;
	margin-right: 4px;
	margin-top: 3px;
	height: 24px;
	box-sizing: border-box;
	background-color: transparent;
	cursor: text;
	font-size: 14px;
	text-align: center;
	width: 100%;
}
.addressbar:disabled {
	color: #808080 !important;
}
.transparent-white {
	border: 1px solid rgba(255,255,255,0.3) !important;
}
.transparent-white:hover {
	background-color: rgba(255,255,255,0.2);
}
.white {
	border: 1px solid rgb(255,255,255) !important;
}
.white:hover {
	background-color: rgba(255,255,255,0.2);
}
.black {
	border: 1px solid rgb(0,0,0) !important;
}
.black:hover {
	background-color: rgba(255,255,255,0.2);
}
#frame-main {
	width: 100%;
	height: 100%;
}
#pane {
	right: 0px;
	bottom: 0px;
	top: 34px;
	position: absolute;
	top: 34px;
	left: calc(100% - 160px);
	display: none;
}
#frame-pane {
	width: 100%;
	height: 100%;
}
#statusbar {
	position: absolute;
	bottom: 0px;
	left: 0px;
	right: 0px;
	height: 24px;
	box-sizing: border-box;
	font-size: 12px;
	padding: 6px;
	display: none;
}
</style>
<script>
window.addEventListener('DOMContentLoaded',Load);
</script>
</head>
<body>

<div id="pane">
	<iframe id="frame-pane" frameborder="0" allowtransparency="true" scrolling="auto" src=""></iframe>
</div>
<div id="main">
<iframe id="frame-main" onload="FrameLoad();" frameborder="0" allowtransparency="true" scrolling="auto" src=""></iframe>
</div>
<div class="FlyUiTextHighlight" id="statusbar">Loading...</div>


</body>
</html>