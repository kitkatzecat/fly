<!DOCTYPE html>
<html>
<head>

<?php
include 'fly.php';
include 'Fly.Command.php';

echo FlyLoadExtension('SprocketComputers.Utilities', 'ColorPicker');
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
	white-space: pre;
	overflow: auto;
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
a {
	color: inherit;
	text-decoration: underline;
	cursor: pointer;
	font-family: inherit;
}
a:hover, a:active {
	color: inherit;
}
xmp {
	font-family: inherit;
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
	cmd.ready();
}
function cmd(comd) {
	var input = document.getElementById('input');
	var button = document.getElementById('button');
	var text = document.getElementById('main');
	
	text.innerHTML = '';

	if (!input.disabled) {
		text.innerHTML += '&gt; '+comd;
	}
	
	input.disabled = true;
	button.disabled = true;
	
	FlyCommand(comd,function(a) {
		if (a == false) {
			text.innerHTML += "\r\nCommand error: "+FlyCommand.error+"\r\n\r\n";
			cmd.ready();
		} else {
			text.innerHTML += '\r\n\r\nDisplay:\r\n'+a.display;

			cmd.execute = function() {
				try {
					window.top.eval(a.execute);
				} catch (err) {console.log(err);}
			}
			text.innerHTML += '\r\n\r\nExecute:\r\n';
			text.innerHTML += '<a onclick="cmd.execute();"><xmp>'+a.execute+'</xmp></a>';

			cmd.error = function() {
				try {
					window.top.eval(a.error);
				} catch (err) {console.log(err);}
			}
			text.innerHTML += '\r\n\r\nError:\r\n';
			text.innerHTML += '<a onclick="cmd.error();"><xmp>'+a.error+'</xmp></a>';

			text.innerHTML += '\r\n\r\nJSON:\r\n';
			text.innerHTML += '<xmp>'+JSON.stringify(a,null,'    ')+'</xmp>';
			
			cmd.ready();
		}
	});
}
cmd.ready = function() {		
	input.disabled = false;
	button.disabled = false;
			
	input.focus();
	input.select();
}
cmd.error = function(){};
cmd.execute = function(){};
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
	Fly.window.size.set(720,480);
	Fly.window.title.set('Command Prompt - Details');

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
		],{icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>colors.svg'}]
	]});
	actionbar.add({text:'Mode',type:'dropdown',menu:[
		['Output',function() {mode();}],
		['Details',function(){},{toggled:true}]
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
	window.location.href = 'command.php';
}
</script>
</head>
<body>

<div id="main"><br>Enter a command to view detailed output</div>
<div id="carat" class="FlyUiTextHighlight FlyUiNoSelect">&gt;</div><input disabled onkeypress="eva(event)" type="text" id="input"><button class="FlyUiNoSelect" onclick="enter()" disabled id="button">Execute</button>

<div id="ColorPicker" style="display:none;"></div>
<script>
Fly.extension.replace('ColorPicker','SprocketComputers.Utilities','ColorPicker');
</script>

</body>
</html>