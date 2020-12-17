<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
if (!$_FLY['IS_USER']) {
	echo '<style>body {background:#fff;}</style>No user is logged in.';
	exit;
}
?>
<style>
div#lefttop {
	position: absolute;
	top: 0;
	left: 0;
	width: 210px;
}
div#leftbottom {
	position: absolute;
	bottom: 0;
	left: 0;
	width: 210px;
}
div#right {
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	width: 282px;
	background-color: #fff;
	overflow: hidden;
}
div#loading {
	position: absolute;
	top: 50%;
	right: 0;
	width: 282px;
	height: auto;
	transform: translateY(-50%);
	text-align: center;
	animation: loading-show 1s linear;
}
@keyframes loading-show {
	0% {
		opacity: 0;
	}
	75% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}
div.item {
	width: 100%;
	height: 36px !important;
	box-sizing: border-box;
	position: relative;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
}
div.item>div.arrow {
	position: absolute;
	right: 8px;
	font-size: 18px;
	top: calc(50% - 12px);
}
img.itemicon {
	width: 24px;
	height: 24px;
	vertical-align: middle;
	margin-right: 6px;
	margin-top: -2px;
}
.userImage {
    width: 24px;
    height: 24px;
    border-top: 1px solid #fff;
    border-left: 1px solid #fff;
    border-bottom: 1px solid #f2f2f2;
    border-right: 1px solid #f2f2f2;
    border-radius: 3px;
    box-shadow: 0px 0px 4px #000;
    background-size: contain;
    background-color: #808080;
    background-repeat: no-repeat;
    background-position: center center;
	background-image: url('<?php echo $_FLY['USER']['IMAGE']; ?>');
	box-sizing: border-box;
    vertical-align: middle;
	margin-right: 6px;
	margin-top: -2px;
}
div.divider {
	height: 0;
	margin-left: 3%;
	margin-right: 3%;
	border-top: 1px solid #c0c0c0;
	margin-top: 5px;
	margin-bottom: 8px;
	width: 94%;
}
#frame {
	animation: enter .1s ease-out;
}
@keyframes enter {
	from {
		transform: translateX(-20px);
		opacity: 0;
	}
	to {
		transform: translateX(0);
		opacity: 1;
	}
}
</style>
<script>
var list = [
	{type:'expand',id:'pins',nav:'pins.php',title:'Pins',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pin.svg'},
	{type:'expand',id:'recent',nav:'recent.php',title:'Recent',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg'},
	{type:'divider'},
	{type:'expand',id:'files',nav:'files.php',title:'Files',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>browse.svg'},
	{type:'expand',id:'apps',nav:'apps.php',title:'Applications',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>application.svg'},
	{type:'divider'},
	{type:'button',title:'Options',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>options.svg',action:function(){run('SprocketComputers.zOptions')}},
];
var items = {};
//Fly.window.ready = 
document.addEventListener('DOMContentLoaded',function() {
	//Fly.window.title.hide();

	var lefttop = document.getElementById('lefttop');
	list.forEach(function(item) {
		if (item['type'] == 'divider') {
			var div = document.createElement('div');
			div.className = 'divider';
			item['object'] = div;

			lefttop.appendChild(item['object']);
		} else if (item['type'] == 'button') {
			var div = document.createElement('div');
			div.className = 'FlyUiToolbarItem item';
			div.innerHTML = '<img src="'+item['icon']+'" class="itemicon">'+item['title'];
			div.onclick = item['action'];
			item['object'] = div;

			lefttop.appendChild(item['object']);
		} else if (item['type'] == 'expand') {
			var div = document.createElement('div');
			div.className = 'FlyUiToolbarItem item';
			div.innerHTML = '<img src="'+item['icon']+'" class="itemicon">'+item['title']+'<div class="arrow">▸</div>';
			div.toggled = false;
			div.toggleOn = function() {
				div.className = 'FlyUiToolbarItemToggle item';
				div.toggled = true;
			}
			div.toggleOff = function() {
				div.className = 'FlyUiToolbarItem item';
				div.toggled = false;
			}
			div.hoverTimeout = false;
			div.active = function() {
				if (!div.toggled) {
					if (!!div.hoverTimeout) {
						clearTimeout(div.hoverTimeout);
						div.hoverTimeout = false;
					}
					list.forEach(function(item) {
						if (item['type'] == 'expand') {
							item['object'].toggleOff();
						}
					});
					div.toggleOn();
					nav(item['nav']);
				}
			}
			div.addEventListener('click',function() {
				div.active();
			})
			div.addEventListener('mouseenter',function() {
				div.hoverTimeout = setTimeout(function() {
					div.hoverTimeout = false;
					div.active();
				},300);
			});
			div.addEventListener('mouseleave',function() {
				if (!!div.hoverTimeout) {
					clearTimeout(div.hoverTimeout);
					div.hoverTimeout = false;
				}
			});
			item['object'] = div;

			lefttop.appendChild(item['object']);
		}
		if (item.hasOwnProperty('id')) {
			items[item['id']] = item;
		}
	});

	items['pins']['object'].active();
});

function run(app) {
	window.top.system.command('run:'+app);
	close();
}
function close() {
	Fly.window.close();
}
function nav(page) {
	var frame = document.getElementById('frame');
	var loading = document.getElementById('loading');

	loading.style.display = 'block';
	frame.style.display = 'none';

	frame.src = page;
	frame.onload = function() {
		loading.style.display = 'none';
		frame.style.display = 'block';
	}
}
</script>
</head>
<body>

<div id="lefttop"></div>
<div id="leftbottom">
	<div onclick="run('SprocketComputers.Utilities.AboutFly');" title="Show information about this computer and Fly" class="FlyUiToolbarItem item"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>fly.svg" class="itemicon">About Fly</div>
	<div onclick="run('SprocketComputers.zOptions,page=users');" title="Change user options" class="FlyUiToolbarItem item"><img class="userImage" src="<?php echo $_FLY['RESOURCE']['URL']['OS']; ?>userimagelight.svg"><?php echo htmlspecialchars($_FLY['USER']['NAME']); ?></div>
	<div class="divider"></div>
	<div onclick="run('SprocketComputers.Utilities.LogOff');" title="Close all applications and end your session" class="FlyUiToolbarItem item"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>lock.svg" class="itemicon">Log Off</div>
	<div class="FlyUiToolbarItem item" onclick="run('SprocketComputers.Utilities.PowerOptions');" title="Close all applications and power off the computer"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>power.svg" class="itemicon">Power</div>
</div>
<div id="right">
	<iframe id="frame" src="" style="width:100%;height:100%;" frameborder="0"></iframe>
</div>
<div id="loading">Loading...</div>
</body>
</html>