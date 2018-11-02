<?php
/*class MP3File {
	var $title;
	var $artist;
	var $album;
	var $year;
	var $comment;
	var $genre;
	function getid3($file) {
		if (file_exists($file)) {
			$id_start = filesize($file)-128;
			$fp = fopen($file,"r");
			fseek($fp,$id_start);
			$tag = fread($fp,3);
			if ($tag == "TAG") {
				$this->title = fread($fp,30);
				$this->artist = fread($fp,30);
				$this->album = fread($fp,30);
				$this->year = fread($fp,4);
				$this->comment = fread($fp,30);
				$this->genre = fread($fp,1);
				fclose($fp);
				return true;
			} else {
				fclose($fp);
				return false;
			}
		} else {
			return false;
		}
	}
}
function getMP3($file) {
	if (file_exists($file)) {
		$id_start = filesize($file)-128;
		$fp = fopen($file,"r");
		fseek($fp,$id_start);
		$tag = fread($fp,3);
		$return = array();
		if ($tag == "TAG") {
			$return['title'] = fread($fp,30);
			$return['artist'] = fread($fp,30);
			$return['album'] = fread($fp,30);
			$return['year'] = fread($fp,4);
			$return['comment'] = fread($fp,30);
			$return['genre'] = fread($fp,1);
			fclose($fp);
			return $return;
		} else {
			fclose($fp);
			return false;
		}
	} else {
		return false;
	}
}*/
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'Fly.Actionmenu.php';
include 'Fly.Registry.php';
include 'Fly.Command.php';
include 'Fly.Application.php';
include 'Fly.Standard.php';
echo FlyLoadExtension('SprocketComputers.Utilities','ColorPicker');
?>
<script>
function load() {
	Fly.extension.replace('test','SprocketComputers.Utilities','ColorPicker');
}
function context(event) {
	Fly.actionmenu(event,[
		['Home',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>home.svg'}],
		[''],
		['Icon Size',[
			['Huge',function(){},{icon:'icon.hg.svg'}],
			['Extra Large',function(){},{icon:'icon.xl.svg'}],
			['Large',function(){},{icon:'icon.lg.svg'}],
			['Medium',function(){},{icon:'icon.md.svg'}],
			['Small',function(){},{icon:'icon.sm.svg'}],
			['Tiny',function(){},{icon:'icon.tn.svg'}],
			['Tiles',function(){},{icon:'icon.ls.svg'}],
			['List',function(){},{icon:'icon.ls.svg'}],
			[''],
			['Custom...',function(){}]
		],{icon:'icon.xl.svg'}],
		['Image Previews',function(){},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>type/image.svg'}],
		['File Extensions',[['smaller'],['menu']]],
		[''],
		['Uhhhhhhhhhh']
	]);
}

function commm() {
	Fly.control.input('Input a command','Please','command me pls','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>pokeball.svg',function(c) {
		FlyCommand(c,function(a) {
			window.top.shell.dialog('Command Result','<pre>'+a.display+'<br><br>'+a.return+'</pre>','Command','<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg');
		});
	});
}

var gelp = function() {};

function returrr(dialog) {
	gelp = dialog;
}
function gellppp() {
	Fly.window.title.hide();
	Fly.window.movement.set(64,0,544,32);
	document.getElementById('titlebar').style.display = 'block';
}
function nellppp() {
	Fly.window.title.show();
	Fly.window.movement.reset();
	document.getElementById('titlebar').style.display = 'none';
}

function telp() {
	Fly.window.border.hide();
	document.body.backgroundColor = 'transparent';
}
function lelp() {
	Fly.window.border.show();
	document.body.backgroundColor = '#fff';
}

function gadget() {
	var frame;
	frame = window.top.task.create('public',{name:'Gadget',title:'Gadget',x:'auto',y:'auto',width:150,height:150,location:'<?php echo $_FLY['URL']; ?>system/resources/os/calendar.svg',expand:false,minimize:false,resize:false,close:false,background:true,load:function() {
		frame.window.hideBorder();
		frame.window.composition.secondaryMovement.set(0,0,150,150);
	}});
}
function wmp() {
	var frame;
	frame = window.top.task.create('public',{name:'Windows Media Player',title:'Windows Media Player',x:'auto',y:'auto',width:670,height:482,location:'<?php echo $_FLY['URL']; ?>system/users/1/Media/wmplayer.png',expand:false,minimize:true,resize:false,close:true,background:false,load:function() {
		frame.window.content.contentWindow.document.body.style.margin = '0px';
		frame.window.hideBorder();
		frame.window.composition.secondaryMovement.set(0,0,670,32);
	}});
}
</script>
<style>
#titlebar {
	position: absolute;
	display: none;
	top: 0px;
	left: 0px;
	right: 0px;
	height: 32px;
	text-align: center;
	box-sizing: border-box;
	padding: 4px;
	background: linear-gradient(to bottom, rgba(216,224,222,1) 0%,rgba(174,191,188,1) 22%,rgba(153,175,171,1) 33%,rgba(142,166,162,1) 50%,rgba(130,157,152,1) 67%,rgba(78,92,90,1) 82%,rgba(14,14,14,1) 100%);
	color: #fff;
	font-weight: bold;
}
.button {
	width: 32px;
	height: 32px;
	box-sizing: border-box;
	border: 1px solid #fff;
	background: linear-gradient(to bottom, rgba(239,197,202,1) 0%,rgba(210,75,90,1) 50%,rgba(186,39,55,1) 51%,rgba(241,142,153,1) 100%);
	color: #fff;
	cursor: pointer;
	padding: 6px;
	position: absolute;
}
</style>
</head>
<body onload="load()" style="background-color:#FFFFFF;width:200%" class="FlyUiText">
<!--<h1 style="color:#c0c0c0;text-shadow:2px 2px #808080;">Welcome to Options</h1>
<p>To open a directory, click an icon.</p>
<br>-->
<div id="titlebar"><div class="button" style="top:0px;left:0px;" onclick="Fly.window.minimize();">m</div><div class="button" style="top:0px;left:32px;" onclick="Fly.window.expand();">e</div>fly help<div class="button" style="top:0px;right:0px;" onclick="Fly.window.close();">x</div></div>
<button onclick="Fly.control.password('gimme your password, bish',function() {window.top.shell.dialog('Thanl you','Something went right');},function() {window.top.shell.dialog('I am angery.')})"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>lock.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;pointer-events:none;">I Yam Socure</button>
<form action="index.php" method="GET">
<div id="test"></div><br>
<input type="submit" value="Submit">
</form>
<br><br>
<div class="FlyUiProgressWaitContainer"><div class="FlyUiWaitBar"></div></div>
<br><br>
<button onclick="Fly.control.wait('Here is a very very','Looood',returrr);setInterval(function(){gelp.position(Math.floor((Math.random() * 100) + 1));},1000);">Make a progress bar</button>
<button onclick="gellppp()">gelp</button>
<button onclick="nellppp()">nelp</button>
<br>
<br>
<button onclick="telp()">telp</button>
<button onclick="lelp()">lelp</button>
<br>
<button onclick="gadget()">gadget</button>
<button onclick="wmp()">wmp</button>
<br><br>
<button onclick="commm()">commm</button>
<br><br>
<button onclick="Fly.control.modal();">yo</button>
<br><br>
<input type="text" id="aaabbbccc"><button onclick="Fly.window.message.show(document.getElementById('aaabbbccc').value)">show</button>
<br><br>
<div style="background-color: #c0c0c0;" oncontextmenu="context(event);return false;">right click here</div>
<br>
<div style="font-size:32px;" id="file"></div>
<xmp>
<?php
echo $_SERVER['DOCUMENT_ROOT'];
print_r($_SESSION);
/*$mp3 = getMP3(FLY_ROOT.'Little Saint Nick.mp3');
if ($mp3 == false) {
	echo 'noooooooo';
} else {
print_r($mp3);
}*/
print_r(FlyCommand('run:SprocketComputers.Utilities'));
print_r($_FLY);
?>
</xmp>
</body>
</html>