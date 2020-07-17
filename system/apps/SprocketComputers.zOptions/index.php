<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Actionbar.php';
?>
<style>
#main {
	position: absolute;
	top: 34px;
	left: min(220px,25%);
	right: 0px;
	bottom: 0px;
	background-color: #fff;
	transition: left .2s ease-in-out;
	overflow: hidden;
	padding: 0px;
}
#index {
	position: absolute;
	top: 34px;
	left: 0px;
	width: 25%;
	max-width: 220px;
	bottom: 0px;
	padding-top: 4px;
	padding-left: 8px;
	padding-right: 8px;
	padding-bottom: 4px;
	box-sizing: border-box;
	overflow: hidden;
}
#frame {
	width: 100%;
	height: 100%;
	box-sizing: border-box;
	border: none;
}
.index-option {
	cursor: pointer;
	font-size: 13px;
	padding-top: 6px;
	padding-bottom: 6px;
	margin-top: -4px;
	margin-bottom: -4px;
	word-wrap: nowrap;
	white-space: pre;
	max-width: 100%;
	overflow: hidden;
	text-overflow: ellipsis;
}
.index-icon {
	width: 16px;
	height: 16px;
	vertical-align: middle;
	margin-right: 6px;
}
</style>
<script>
var Toolbar;
var IndexToolbar;
function onload() {
	Toolbar = new Fly.actionbar();
	Toolbar.style.position = 'absolute';
	Toolbar.style.top = '0px';
	Toolbar.style.left = 'min(220px,25%)';
	Toolbar.style.right = '0px';
	Toolbar.style.wordWrap = 'nowrap';
	Toolbar.style.whiteSpace = 'pre';
	Toolbar.style.overflowX = 'auto !important';
	
	Toolbar.add({text:'',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg',action:function(){nav('home.php')}});
	Toolbar.add({type:'title',text:'▸'});
	
	document.body.appendChild(Toolbar);
	
	IndexToolbar = new Fly.actionbar();
	IndexToolbar.style.position = 'absolute';
	IndexToolbar.style.top = '0px';
	IndexToolbar.style.left = '0px';
	IndexToolbar.style.width = '23%';
	IndexToolbar.style.width = '160px';
	IndexToolbar.style.wordWrap = 'nowrap';
	IndexToolbar.style.whiteSpace = 'pre';
	IndexToolbar.style.overflowX = 'auto !important';
	
	IndexToolbar.add({text:'Categories',icon:false,type:'title'})
	
	document.body.appendChild(IndexToolbar);
	
	<?php
	if (file_exists(preg_replace('/\?.*/', '',$_GET['page']))) {
		echo 'nav(\''.$_GET['page'].'\')';
	} else {
		echo 'nav(\'home.php\')';
	}
	?>
}
function nav(page) {
	var frame = document.getElementById('frame');
	frame.src = page;
}
var Tree = [];
var frameloadinit = false;
function frameload() {
	var frame = document.getElementById('frame');

	if (frameloadinit) {
		try {
			window.top.shell.sound.system('click');
		} catch(e) {}
	} else {
		frameloadinit = true;
	}

	if (Tree.length > 0) {
		for (i=0; i < Tree.length; i++) {
			Tree[i].remove();
		}
		Tree = [];
	}
	if (typeof frame.contentWindow.OptionsTree !== 'undefined') {
		var tree = frame.contentWindow.OptionsTree;
		for (let i=0; i < tree.length; i++) {
			if (i == tree.length-1) {
				Tree.push(Toolbar.add({text:tree[i].name,icon:tree[i].icon,toggled:true,action:function(){}}));
				Fly.window.title.set('Options - '+tree[i].name);
			} else {
				var b = Toolbar.add({text:tree[i].name,icon:tree[i].icon,action:function(){b.toggleOn();nav(tree[i].index);}});
				Tree.push(b);
				Tree.push(Toolbar.add({type:'title',text:'▸'}));
			}
		}
	} else {
		Tree.push(Toolbar.add({text:frame.contentWindow.document.title,toggled:true,action:function(){}}));
		Fly.window.title.set('Options - '+frame.contentWindow.document.title);
	}
}
</script>
</head>
<body onload="onload()">

<div id="index">
<?php
$index = json_decode(file_get_contents('index.json'),true);

foreach ($index as $item) {
	echo '<div class="FlyUiTextHover index-option" onclick="nav(\''.$item['index'].'\')"><img class="index-icon" src="'.FlyVarsReplace($item['icon']).'">'.htmlspecialchars($item['name']).'</div>';
}
?>
<!-- <div class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg">Home</div>
<div class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>personalization.svg">Personalization</div>
<div class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg">Files & Applications</div>
<div class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg">Registry</div>
<div class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.Options/icons/clock.svg">Time & Date</div>
<div class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>text.svg">Fonts</div>
<div class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>drive.svg">Hardware</div> -->
<hr style="margin-top:10px;margin-bottom:10px;">
<div onclick="window.top.system.command('run:SprocketComputers.zFileManager,p=%3Foptions');" class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.FileManager/fileman.svg">File Manager</div>
<div onclick="window.top.system.command('run:SprocketComputers.Utilities');" class="FlyUiTextHover index-option"><img class="index-icon" src="<?php echo $_FLY['RESOURCE']['URL']['APPS']; ?>SprocketComputers.Utilities/utilities.svg">Utilities</div>
</div>

<div id="main" class="FlyUiText FlyUiNoSelect"><iframe onload="frameload()" src="" frameborder="0" id="frame"></iframe></div>

</body>
</html>