<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'script.php';
?>
<link rel="stylesheet" type="text/css" href="index.css">
</head>
<body class="FlyUiText FlyUiTextHighlight">
<form name="game">
<div class="game">
<div class="middleContainer">
<div class="middleInside">
<center>
<table class="main">
<tr>
	<td class="box" onclick="yourChoice('A')"><img src="blank.svg" border=0 height=100 width=100 name=A alt="Top-Left"></a></td>
	<td class="box" onclick="yourChoice('B')"><img src="blank.svg" border=0 height=100 width=100 name=B alt="Top-Center"></a></td>
	<td class="box" onclick="yourChoice('C')"><img src="blank.svg" border=0 height=100 width=100 name=C alt="Top-Right"></a></td>
</tr>
<tr>
	<td class="box" onclick="yourChoice('D')"><img src="blank.svg" border=0 height=100 width=100 name=D alt="Middle-Left"></a></td>
	<td class="box" onclick="yourChoice('E')"><img src="blank.svg" border=0 height=100 width=100 name=E alt="Middle-Center"></a></td>
	<td class="box" onclick="yourChoice('F')"><img src="blank.svg" border=0 height=100 width=100 name=F alt="Middle-Right"></a></td>
</tr>
<tr>
	<td class="box" onclick="yourChoice('G')"><img src="blank.svg" border=0 height=100 width=100 name=G alt="Bottom-Left"></a></td>
	<td class="box" onclick="yourChoice('H')"><img src="blank.svg" border=0 height=100 width=100 name=H alt="Bottom-Center"></a></td>
	<td class="box" onclick="yourChoice('I')"><img src="blank.svg" border=0 height=100 width=100  name=I alt="Bottom-Right"></a></td>
</tr>
</table>
</center>
</div></div>
</div>
<div class="score">
<div class="middleContainer">
<div class="middleInside">
<input type="text" style="width:24px;" value="0" name="you">&nbsp;Wins<br>
<input type="text" style="width:24px;" value="0" name="computer">&nbsp;Losses<br>
<input type="text" style="width:24px;" value="0" name="ties">&nbsp;Ties<br>
</div></div>
</div>
</form>
<div class="buttons">
<div class="middleContainer">
<div class="middleInside">
<button onclick="playAgain();"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>refresh.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" class="FlyUiNoSelect">New Game</button>
&nbsp;&nbsp;
<button onclick="help();"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>question.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" class="FlyUiNoSelect">Help</button>
&nbsp;&nbsp;
<button onclick="Fly.window.close();"><img src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" class="FlyUiNoSelect">Quit</button>
</div></div>
</div>
</body>
</html>