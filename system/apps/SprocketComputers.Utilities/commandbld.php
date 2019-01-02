<!DOCTYPE html>
<html>
<head>
<?php
include 'Fly.Standard.php';
include 'Fly.Command.php';
?>
<style>
	#main {
		position: absolute;
		top: 0px;
		left: 0px;
		right: 0px;
		bottom: 50px;
		overflow-x: hidden;
		overflow-y: auto;
	}
	#ok {
		position: absolute;
		bottom: 9px;
		right: 9px;
		width: 100px;
	}
	#cancel {
		position: absolute;
		bottom: 9px;
		right: 117px;
		width: 100px;
	}
	#add {
		position: absolute;
		bottom: 9px;
		left: 9px;
		width: auto;
	}
	.icon {
		width: 16px;
		height: 16px;
		vertical-align: middle;
	}
	#hr {
		position: absolute;
		bottom: 50px;
		margin: 0;
		padding: 0;
		left: 0px;
		right: 0px;
	}
	.head {
		font-size: 12px;
		width: 100%;
		box-sizing: border-box;
	}
	.arg {
		padding: 8px;
	}
	.argtext {
		width: 100%;
	}
	.remove {
		font-size: inherit;
		float: right;
		display: inline-block;
	}
	.text {
		font-size: inherit;
		float: left;
		display: inline-block;
	}
</style>
<script>
	Fly.window.ready = function() {
		document.getElementById('cancel').disabled = false;
		document.getElementById('ok').disabled = false;
		document.getElementById('add').disabled = false;
		try {
			window.top.shell.sound.system('question');
		} catch (err) {console.log(err);}
		cmd.getOptions();
		setInterval(cmd.updateHeads,100);
		Fly.window.onclose = cmd.cancel;
	}
	var cmd = {
		add: function() {
			var n = document.createElement('div');
			n.className = 'arg';

			n.head = document.createElement('div');
			n.head.className = 'FlyUiTextHighlight head';

			n.head.text = document.createElement('div');
			n.head.text.className = 'FlyUiTextHighlight text';
			n.head.text.innerText = 'Argument '+cmd.arguments.length;

			n.head.rem = document.createElement('div');
			n.head.rem.className = 'FlyUiTextHover remove';
			n.head.rem.innerText = '𐌗';
			n.head.rem.onclick = function() {
				cmd.arguments.splice(cmd.arguments.indexOf(n),1);
				n.remove();
			};

			n.text = document.createElement('input');
			n.text.type = 'text';
			n.text.className = 'argtext';
			n.text.onkeydown = cmd.checkInput;

			n.head.appendChild(n.head.text);
			n.head.appendChild(n.head.rem);
			n.appendChild(n.head);

			n.appendChild(n.text);
			document.getElementById('main').appendChild(n);
			cmd.arguments.push(n);
		},
		getOptions: function() {
			FlyCommand('help',function(a) {
				var select = document.getElementById('commandtext');
				a.return.forEach(function(i) {
					select.innerHTML += '<option value="'+i+'">'+i+':</option>';
				});
				select.options[0].remove();
				select.disabled = false;
				cmd.add();
			});
		},
		updateHeads: function() {
			cmd.arguments.forEach(function(n,i) {
				n.head.text.innerText = 'Argument '+i;
			});
		},
		checkInput: function(event) {
			if (event.which == 13 || event.keyCode == 13) {
				cmd.ok();
				return false;
			} else if (event.which == 9 || event.keyCode == 9) {
				cmd.add();
			}
			return true;
		},
		arguments: [],
		parent: false,
		ok: function() {
			var command = document.getElementById('commandtext').value+':';
			cmd.arguments.forEach(function(v,i) {
				if (i != 0) {
					command += ',';
				}
				command += v.text.value;
			});
			cmd.parent.document.getElementById('input').value = command;
			Fly.window.message.show('Command inserted in main window');
			cmd.parent.Fly.window.bringToFront();
		},
		cancel: function() {
			cmd.parent.Fly.window.bringToFront();
			cmd.parent.child = false;
			Fly.window.close();
		}
	}
</script>
</head>
<body>
	<div id="main" class="FlyUiText FlyUiTextHighlight">
		<div class="arg" id="command">
			<div class="FlyUiTextHighlight head">Command</div>
			<select disabled id="commandtext" class="argtext">
				<option>Populating...</option>
			</select>
		</div>
	</div>
	<hr id="hr">
	<button disabled id="add" onclick="cmd.add()"><img class="icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-plus.svg"></button>
	<button disabled id="cancel" onclick="cmd.cancel()"><img class="icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg"></button>
	<button disabled id="ok" onclick="cmd.ok();"><img class="icon" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg"></button>
</body>
</html>