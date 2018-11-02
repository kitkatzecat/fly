<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="index.css">
<script type="text/javascript" src="script.js"></script>
</head>
<body>
<form name="Calculator" id="Calculator-form">
<div class="output-box">
<input type="text" class="memory" maxlength="40" disabled value="" name="Memory">
<input type="text" class="output" maxlength="40" disabled value="0" name="Display" onChange="FixCurrent()">
</div>
<br><br><br><input type="button" class="m" name="memory-clear" value="MC" OnClick="MemoryClear()"><input type="button" class="m" name="memory-save" value="MS" OnClick="MemorySave()"><input type="button" class="m" name="memory-recall" value="MR" OnClick="MemoryRecall()"><!--<input type="button" class="m" name="memory-add" value="M+" OnClick="MemoryAdd()"><input type="button" class="m" name="memory-subtract" value="M−" OnClick="MemorySubtract()">--><br><input type="button" class="dark" name="clear" value="CE" OnClick="Clear()"><input type="button" class="dark" name="AC" value="C" OnClick="AllClear()"><input type="button" class="dark" name="exp" value="^" OnClick="Operate('^')"><input type="button" class="dark" name="div" value="÷" OnClick="Operate('/')"><br><input type="button" class="light" name="7" value="7" OnClick="AddDigit('7')"><input type="button" class="light" name="8" value="8" OnClick="AddDigit('8')"><input type="button" class="light" name="9" value="9" OnClick="AddDigit('9')"><input type="button" class="dark" name="mul" value="×" OnClick="Operate('*')"><br><input type="button" class="light" name="4" value="4" OnClick="AddDigit('4')"><input type="button" class="light" name="5" value="5" OnClick="AddDigit('5')"><input type="button" class="light" name="6" value="6" OnClick="AddDigit('6')"><input type="button" class="dark" name="sub" value="−" OnClick="Operate('-')"><br><input type="button" class="light" name="1" value="1" OnClick="AddDigit('1')"><input type="button" class="light" name="2" value="2" OnClick="AddDigit('2')"><input type="button" class="light" name="3" value="3" OnClick="AddDigit('3')"><input type="button" name="add" class="dark" value="+" OnClick="Operate('+')"><br><input type="button" class="light" name="plusmin" value="±" OnClick="PlusMinus()"><input type="button" class="light" name="0" value="0" OnClick="AddDigit('0')"><input type="button" class="light" name="dot" value="." OnClick="Dot()"><input type="button" class="dark" name="result" value="=" OnClick="Calculate()"></FORM>
</body>
</html>