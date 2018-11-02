<script>
var o = '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-x.svg';
// Location of where you uploaded your site's x image

var x = '<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-o.svg';
// Location of where you uploaded your site's o image

var blank = "blank.svg";
// Location of where you uploaded your site's blank image

var pause = 0;
var all = 0;
var a = 0;
var b = 0;
var c = 0;
var d = 0;
var e = 0;
var f = 0;
var g = 0;
var h = 0;
var i = 0;
var temp="";
var ok = 0;
var cf = 0;
var choice=9;
var aRandomNumber = 0;
var comp = 0;
var t = 0;
var wn = 0;
var ls = 0;
var ts = 0;
function help() {
	Fly.control.modal("Welcome to Tic-Tac-Toe!", "You play as the O's and the computer is the X's.  Select the square you want to put your O into by clicking them.  You cannot occupy a square that is already occupied. The first player to get three squares in a row wins.  Good Luck!","Tic-Tac-Toe","<?php echo $_FLY['APP']['URL']; ?>tictactoe.svg");
}
function logicOne() {
if ((a==1)&&(b==1)&&(c==1)) all=1;
if ((a==1)&&(d==1)&&(g==1)) all=1;
if ((a==1)&&(e==1)&&(i==1)) all=1;
if ((b==1)&&(e==1)&&(h==1)) all=1;
if ((d==1)&&(e==1)&&(f==1)) all=1;
if ((g==1)&&(h==1)&&(i==1)) all=1;
if ((c==1)&&(f==1)&&(i==1)) all=1;
if ((g==1)&&(e==1)&&(c==1)) all=1;
if ((a==2)&&(b==2)&&(c==2)) all=2;
if ((a==2)&&(d==2)&&(g==2)) all=2;
if ((a==2)&&(e==2)&&(i==2)) all=2;
if ((b==2)&&(e==2)&&(h==2)) all=2;
if ((d==2)&&(e==2)&&(f==2)) all=2;
if ((g==2)&&(h==2)&&(i==2)) all=2;
if ((c==2)&&(f==2)&&(i==2)) all=2;
if ((g==2)&&(e==2)&&(c==2)) all=2;
if ((a != 0)&&(b != 0)&&(c != 0)&&(d != 0)&&(e != 0)&&(f != 0)&&(g != 0)&&(h != 0)&&(i != 0)&&(all == 0)) all = 3;
}
function logicTwo() {
if ((a==2)&&(b==2)&&(c== 0)&&(temp=="")) temp="C";
if ((a==2)&&(b== 0)&&(c==2)&&(temp=="")) temp="B";
if ((a== 0)&&(b==2)&&(c==2)&&(temp=="")) temp="A";
if ((a==2)&&(d==2)&&(g== 0)&&(temp=="")) temp="G";
if ((a==2)&&(d== 0)&&(g==2)&&(temp=="")) temp="D";
if ((a== 0)&&(d==2)&&(g==2)&&(temp=="")) temp="A";
if ((a==2)&&(e==2)&&(i== 0)&&(temp=="")) temp="I";
if ((a==2)&&(e== 0)&&(i==2)&&(temp=="")) temp="E";
if ((a== 0)&&(e==2)&&(i==2)&&(temp=="")) temp="A";
if ((b==2)&&(e==2)&&(h== 0)&&(temp=="")) temp="H";
if ((b==2)&&(e== 0)&&(h==2)&&(temp=="")) temp="E";
if ((b== 0)&&(e==2)&&(h==2)&&(temp=="")) temp="B";
if ((d==2)&&(e==2)&&(f== 0)&&(temp=="")) temp="F";
if ((d==2)&&(e== 0)&&(f==2)&&(temp=="")) temp="E";
if ((d== 0)&&(e==2)&&(f==2)&&(temp=="")) temp="D";
if ((g==2)&&(h==2)&&(i== 0)&&(temp=="")) temp="I";
if ((g==2)&&(h== 0)&&(i==2)&&(temp=="")) temp="H";
if ((g== 0)&&(h==2)&&(i==2)&&(temp=="")) temp="G";
if ((c==2)&&(f==2)&&(i== 0)&&(temp=="")) temp="I";
if ((c==2)&&(f== 0)&&(i==2)&&(temp=="")) temp="F";
if ((c== 0)&&(f==2)&&(i==2)&&(temp=="")) temp="C";
if ((g==2)&&(e==2)&&(c== 0)&&(temp=="")) temp="C";
if ((g==2)&&(e== 0)&&(c==2)&&(temp=="")) temp="E";
if ((g== 0)&&(e==2)&&(c==2)&&(temp=="")) temp="G";
}
function logicThree() {
if ((a==1)&&(b==1)&&(c==0)&&(temp=="")) temp="C";
if ((a==1)&&(b==0)&&(c==1)&&(temp=="")) temp="B";
if ((a==0)&&(b==1)&&(c==1)&&(temp=="")) temp="A";
if ((a==1)&&(d==1)&&(g==0)&&(temp=="")) temp="G";
if ((a==1)&&(d==0)&&(g==1)&&(temp=="")) temp="D";
if ((a==0)&&(d==1)&&(g==1)&&(temp=="")) temp="A";
if ((a==1)&&(e==1)&&(i==0)&&(temp=="")) temp="I";
if ((a==1)&&(e==0)&&(i==1)&&(temp=="")) temp="E";
if ((a==0)&&(e==1)&&(i==1)&&(temp=="")) temp="A";
if ((b==1)&&(e==1)&&(h==0)&&(temp=="")) temp="H";
if ((b==1)&&(e==0)&&(h==1)&&(temp=="")) temp="E";
if ((b==0)&&(e==1)&&(h==1)&&(temp=="")) temp="B";
if ((d==1)&&(e==1)&&(f==0)&&(temp=="")) temp="F";
if ((d==1)&&(e==0)&&(f==1)&&(temp=="")) temp="E";
if ((d==0)&&(e==1)&&(f==1)&&(temp=="")) temp="D";
if ((g==1)&&(h==1)&&(i==0)&&(temp=="")) temp="I";
if ((g==1)&&(h==0)&&(i==1)&&(temp=="")) temp="H";
if ((g==0)&&(h==1)&&(i==1)&&(temp=="")) temp="G";
if ((c==1)&&(f==1)&&(i==0)&&(temp=="")) temp="I";
if ((c==1)&&(f==0)&&(i==1)&&(temp=="")) temp="F";
if ((c==0)&&(f==1)&&(i==1)&&(temp=="")) temp="C";
if ((g==1)&&(e==1)&&(c==0)&&(temp=="")) temp="C";
if ((g==1)&&(e==0)&&(c==1)&&(temp=="")) temp="E";
if ((g==0)&&(e==1)&&(c==1)&&(temp=="")) temp="G";
}
function clearOut() {
document.game.you.value="0";
document.game.computer.value="0";
document.game.ties.value="0";
}
function checkSpace() {
if ((temp=="A")&&(a==0)) {
ok=1;
if (cf==0) a=1;
if (cf==1) a=2;
}
if ((temp=="B")&&(b==0)) {
ok=1;
if (cf==0) b=1;
if (cf==1) b=2;
}
if ((temp=="C")&&(c==0)) {
ok=1;
if (cf==0) c=1;
if (cf==1) c=2;
}
if ((temp=="D")&&(d==0)) {
ok=1;
if (cf==0) d=1;
if (cf==1) d=2;
}
if ((temp=="E")&&(e==0)) {
ok=1;
if (cf==0) e=1;
if (cf==1) e=2;
}
if ((temp=="F")&&(f==0)) {
ok=1
if (cf==0) f=1;
if (cf==1) f=2;
}
if ((temp=="G")&&(g==0)) {
ok=1
if (cf==0) g=1;
if (cf==1) g=2;
}
if ((temp=="H")&&(h==0)) {
ok=1;
if (cf==0) h=1;
if (cf==1) h=2;
}
if ((temp=="I")&&(i==0)) {
ok=1;
if (cf==0) i=1;
if (cf==1) i=2;
}
}
function yourChoice(chName) {
pause = 0;
if (all!=0) ended();
if (all==0) {
	cf = 0;
	ok = 0;
	temp=chName;
	checkSpace();
	if (ok==1) {
	document.images[chName].src = x;
	}
	if (ok==0) {taken()};
	process();
	if ((all==0)&&(pause==0)) {setTimeout(myChoice,200);}
}
}
function taken() {
	//window.top.alert("That square is already occupied.  Please select another square.")
	try {
	 window.top.shell.sound.system('alert');
	} catch(err) {}
	pause=1;
}
function myChoice() {
temp="";
ok = 0;
cf=1;
logicTwo();
logicThree();
checkSpace();
while(ok==0) {
aRandomNumber=Math.random()
comp=Math.round((choice-1)*aRandomNumber)+1;
if (comp==1) temp="A";
if (comp==2) temp="B";
if (comp==3) temp="C";
if (comp==4) temp="D";
if (comp==5) temp="E";
if (comp==6) temp="F";
if (comp==7) temp="G";
if (comp==8) temp="H";
if (comp==9) temp="I";
checkSpace();
}
document.images[temp].src= o;
process();
}

function ended() {
	//window.top.alert("The game has already ended. To play a new game click the Play Again button.")
	try {
	 window.top.shell.sound.system('alert');
	} catch(err) {}
}

function process() {
	logicOne();
	if (all==1){ gameWon(); wn++; }
	if (all==2){ gameLost(); ls++; }
	if (all==3){ gameTied(); ts++; }
	if (all!=0) {
		document.game.you.value = wn;
		document.game.computer.value = ls;
		document.game.ties.value = ts;
	}
}
function playAgain() {
 reset();
}
function gameWon() {
 message('You won! Congratulations!',playAgain);
}
function gameLost() {
 message('Darn, you lost! RIP',playAgain);
}
function gameTied() {
 message('You tied...',playAgain);
}
function message(msg,funct) {
var block = document.createElement('div');
block.style.backgroundColor = 'rgba(0,0,0,0.8)';
block.style.position = 'absolute';
block.style.top = '50%';
block.style.left = '0px';
block.style.right = '0px';
block.style.transform = 'translateY(-50%)';
block.style.width = 'auto';
block.style.padding = '16px';
block.style.textAlign = 'center';
block.style.animation = 'middleAnimation 0.75s ease-in-out 0s 1';
block.style.color = '#fff';
block.innerText = msg;
block.innerHTML += '<br>';
document.body.appendChild(block);
var button = document.createElement('button');
button.style.marginTop = '32px';
button.innerHTML = '<img style="width:16px;height:16px;margin-right:6px;vertical-align:middle;height:32px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>mark-check.svg"> Close';
button.onclick = function() {block.parentNode.removeChild(block);funct();}
block.appendChild(button);
}
function reset() {
all = 0;
a = 0;
b = 0;
c = 0;
d = 0;
e = 0;
f = 0;
g = 0;
h = 0;
i = 0;
temp="";
ok = 0;
cf = 0;
choice=9;
aRandomNumber = 0;
comp = 0;
document.images.A.src= blank;
document.images.B.src= blank;
document.images.C.src= blank;
document.images.D.src= blank;
document.images.E.src= blank;
document.images.F.src= blank;
document.images.G.src= blank;
document.images.H.src= blank;
document.images.I.src= blank;
if (t==0) { t=2; myChoice(); }
t--;
}
</script>