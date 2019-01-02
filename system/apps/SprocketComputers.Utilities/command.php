<!DOCTYPE html>
<html>
<head>

<?php
include 'fly.php';
include 'Fly.Command.php';

echo FlyLoadExtension('SprocketComputers.Utilities','ColorPicker');
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
#main {
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	bottom: 48px;
	background-color: #000;
	color: #fff;
	overflow-x: hidden;
	word-wrap: break-word;
	white-space: pre-wrap;
	word-break: break-all;
	overflow-y: auto;
	font-family: Consolas, Courier New, monospace;
	padding: 4px;
	font-size: 12px;
}
#button {
	position: absolute;
	width: 100px;
	bottom: 8px;
	right: 10px;
}
#carat {
	position: absolute;
	left: 6px;
	bottom: 8px;
	height: 24px;
	font-family: Consolas, Courier New, monospace;
	font-size: 16px;
}
#input {
	position: absolute;
	width:  calc(100% - 140px);
	bottom: 8px;
	left: 20px;
	font-family: Consolas, Courier New, monospace;
}
</style>
<script>
Fly.window.ready = function() {
	var text = document.getElementById('main');
	text.scrollTop = text.scrollHeight;
	
	if (typeof window.top.task == "undefined") {
		document.body.style.backgroundColor = '#000';
		document.getElementById('carat').className = '';
		document.getElementById('carat').style.color = '#fff';
	}
	
	init();
	cmd('clear');
}
function cmd(cmd) {
	var input = document.getElementById('input');
	var button = document.getElementById('button');
	var text = document.getElementById('main');
	
	if (!input.disabled) {
		text.innerText += '> '+cmd;
	}
	
	input.disabled = true;
	button.disabled = true;
		
	if (cmd == 'clearlog' || cmd == 'clear') {
		text.innerText = '';
	}
	
	FlyCommand(cmd,function(a) {
		if (a == false) {
			text.innerText += "\r\nCommand error: "+FlyCommand.error+"\r\n\r\n";
			text.scrollTop = text.scrollHeight;
			
			input.disabled = false;
			button.disabled = false;
			
			input.focus();
			input.select();
		} else {
			text.innerText += "\r\n"+a.display+"\r\n\r\n";
			text.scrollTop = text.scrollHeight;
			try {
				if (a.execute !=='') {
					window.top.window.eval(a.execute);
				}
			} catch(err) {}
			
			input.disabled = false;
			button.disabled = false;
			
			input.focus();
			input.select();
		}
	});
}
function enter() {
	var input = document.getElementById('input');
	if (input.value !== '') {
		cmd(input.value);
	}
}
function eva(event) {
    if (event.which == 13 || event.keyCode == 13) {
        enter();
        return false;
    }
    return true;
}
var actionbar;
function init() {
	Fly.window.size.set(640,320);
	Fly.window.title.set('Command Prompt');

	actionbar = new Fly.actionbar();
	actionbar.style.position = 'absolute';
	actionbar.style.top = '0px';
	actionbar.style.left = '0px';
	actionbar.style.right = '0px';
	
	actionbar.add({text:'View',type:'dropdown',menu:[
		['Colors',[
			['Background...',function() {
				var picker = document.getElementById('ColorPicker');
				picker.onchange = color_background;
				picker.choose();
			}],
			['Text...',function() {
				var picker = document.getElementById('ColorPicker');
				picker.onchange = color_text;
				picker.choose();
			}],
		],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>colors.svg'}],
		['Clear',function() {cmd('clear')},{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg'}],
		[''],
		['Command Builder',builder,{icon:'<?php echo $_FLY['WORKING_URL']; ?>command.svg'}]
	]});
	actionbar.add({text:'Mode',type:'dropdown',menu:[
		['Output',function(){},{toggled:true}],
		['Details',function() {mode();}]
	]});	
	actionbar.add({text:'About',align:'right',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>info.svg',action:function() {
		window.top.system.command('ver');
	}});
	actionbar.add({text:'Help',align:'right',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg',action:function() {
		window.top.system.command('help');
	}});	
	
	document.body.appendChild(actionbar);
}
function color_background() {
	var picker = document.getElementById('ColorPicker');
	var main = document.getElementById('main');
	
	main.style.backgroundColor = picker.color.hex;
}
function color_text() {
	var picker = document.getElementById('ColorPicker');
	var main = document.getElementById('main');
	
	main.style.color = picker.color.hex;
}
function mode() {
	if (!!child) {
		child.Fly.window.close();
	}
	window.location.href = 'commandadv.php';
}
Fly.window.onclose = function() {
	if (!!child) {
		child.Fly.window.close();
	}
	Fly.window.close();
}
var child = false;
function builder() {
	if (!child) {
		var pos = Fly.window.position.get();
		pos[0] = parseInt(pos[0])+32;
		pos[1] = parseInt(pos[1])+32;
		window.top.task.create('SprocketComputers.Utilities.CommandPrompt',{name:'Command Builder',title:'Command Builder',load:function(w) {
			w.window.content.contentWindow.cmd.parent = window;
			child = w.window.content.contentWindow;
		},icon:'<?php echo $_FLY['WORKING_URL']; ?>command.svg',resize:true,expand:false,minwidth:300,minheight:100,width:500,height:240,x:pos[0],y:pos[1],location:'<?php echo $_FLY['WORKING_URL']; ?>commandbld.php'});
	} else {
		child.Fly.window.bringToFront();
	}
}
</script>
</head>
<body>

<div id="main"></div>
<div id="carat" onclick="builder()" class="FlyUiTextHover FlyUiNoSelect">&gt;</div><input disabled onkeypress="eva(event)" type="text" id="input"><button class="FlyUiNoSelect" onclick="enter()" disabled id="button">Execute</button>

<div id="ColorPicker" style="display:none;"></div>
<script>
Fly.extension.replace('ColorPicker','SprocketComputers.Utilities','ColorPicker');
</script>

</body>
</html>