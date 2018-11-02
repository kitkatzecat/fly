document.onkeypress = checkKey;
document.onkeydown = checkOtherKey;
function checkKey(e) {
if (!e) e = window.event;
	if (String.fromCharCode(e.keyCode)=="+") {
		Operate(String.fromCharCode(e.keyCode));
		keyFlash('add');
	}
	if (String.fromCharCode(e.keyCode)=="*") {
		Operate(String.fromCharCode(e.keyCode));
		keyFlash('mul');
	}
	if (String.fromCharCode(e.keyCode)=="/") {
		Operate(String.fromCharCode(e.keyCode));
		keyFlash('div');
	}
	if (String.fromCharCode(e.keyCode)=="-") {
		Operate(String.fromCharCode(e.keyCode));
		keyFlash('sub');
	}
	if (String.fromCharCode(e.keyCode)=="^") {
		Operate(String.fromCharCode(e.keyCode));
		keyFlash('exp');
	}
	if (String.fromCharCode(e.keyCode)=="=") {
		Calculate();
		keyFlash('result');
	}
	if (String.fromCharCode(e.keyCode)==".") {
		Dot();
		keyFlash('dot');
	}
	if (String.fromCharCode(e.keyCode)=="n") {
		PlusMinus();
		keyFlash('plusmin');
	}
	if (String.fromCharCode(e.keyCode)=="1" || String.fromCharCode(e.keyCode)=="2" || String.fromCharCode(e.keyCode)=="3" || String.fromCharCode(e.keyCode)=="4" || String.fromCharCode(e.keyCode)=="5" || String.fromCharCode(e.keyCode)=="6" || String.fromCharCode(e.keyCode)=="7" || String.fromCharCode(e.keyCode)=="8" || String.fromCharCode(e.keyCode)=="9" || String.fromCharCode(e.keyCode)=="0") {
		AddDigit(String.fromCharCode(e.keyCode));
		keyFlash(String.fromCharCode(e.keyCode));
	}
	if (String.fromCharCode(e.keyCode)=="c") {
		AllClear();
		keyFlash('AC');
	}
	e.preventDefault();
}
function checkOtherKey(e) {
if (!e) e = window.event;
	if (e.keyCode==8) {
		Clear();
		keyFlash('clear');
		e.preventDefault();
	}
	if (e.keyCode==46) {
		AllClear();
		keyFlash('AC');
		e.preventDefault();
	}
	if (e.keyCode==13) {
		Calculate();
		keyFlash('result');
		e.preventDefault();
	}
}
function keyFlash(name) {
	if (document.getElementsByName(name)[0].className=="light") {
		document.getElementsByName(name)[0].className="lightactive";
		setTimeout(function() {document.getElementsByName(name)[0].className="light"; }, 100);
	}
	if (document.getElementsByName(name)[0].className=="dark") {
		document.getElementsByName(name)[0].className="darkactive";
		setTimeout(function() {document.getElementsByName(name)[0].className="dark"; }, 100);
	}
}

	Memory  = "0";      // initialise memory variable
	CalcMem  = "0";      // initialise calc mem variable
	Current = "0";      //   and value of Display ("current" value)
	Operation = 0;      // Records code for eg * / etc.
	MAXLENGTH = 30;     // maximum number of digits before decimal!

function AddDigit(dig)          //ADD A DIGIT TO DISPLAY (keep as 'Current')
 { if (Current.indexOf("r") == -1)  //if not already an error
    { if (    (eval(Current) == 0)
              && (Current.indexOf(".") == -1)
         ) { Current = dig;
           } else
           { Current = Current + dig;
           };
      Current = Current.toLowerCase(); //FORCE LOWER CASE
    } else
    { Current = "Error";  //Help out, if error present.
    };
   if (Current.indexOf("e0") != -1)
     { var epos = Current.indexOf("e");
       Current = Current.substring(0,epos+1) + Current.substring(epos+2);
     };
  if (Current.length > MAXLENGTH)
     { Current = "Overflow"; //don't allow over MAXLENGTH digits before "." ???
     };
   document.Calculator.Display.value = Current;
 }

function Dot()                  //PUT IN "." if appropriate.
 {
  if ( Current.length == 0)     //no leading ".", use "0."
    { Current = "0.";
    } else
    {  if (   ( Current.indexOf(".") == -1)
            &&( Current.indexOf("e") == -1)
          )
         { Current = Current + ".";
    };   };
  document.Calculator.Display.value = Current;
 }

function DoExponent()
 {
  if ( Current.indexOf("e") == -1 )
       { Current = Current + "e0";
         document.Calculator.Display.value = Current;
       };
 }

function PlusMinus()
 {
 if (Current.indexOf("r") == -1 && document.Calculator.Display.value !== "") {
  if  (Current.indexOf("e") != -1)
    { var epos = Current.indexOf("e-");
      if (epos != -1)
         { Current = Current.substring(0,1+epos) + Current.substring(2+epos); //clip out -ve exponent 
         } else
         { epos = Current.indexOf("e");
           Current = Current.substring(0,1+epos) + "-" + Current.substring(1+epos); //insert -ve exponent
         };
    } else
    {  if ( Current.indexOf("-") == 0 )
         { Current = Current.substring(1);
         } else
         { Current = "-" + Current;
         };
       if (    (eval(Current) == 0)
            && (Current.indexOf(".") == -1 )
          ) { Current = "0"; };
    };
  document.Calculator.Display.value = Current;
 }
 }

function Clear()                //CLEAR ENTRY
 { Current = "0";
   document.Calculator.Display.value = Current;
 }

function AllClear()             //Clear ALL entries!
 { Current = "0";
   Operation = 0;                //clear operation
   Memory = "0";                  //clear memory
   document.Calculator.Memory.value = '';
   document.Calculator.Display.value = Current;
 }

function Operate(op)            //STORE OPERATION e.g. + * / etc.
 {
 if (document.Calculator.Display.value !== "Overflow" && document.Calculator.Display.value !== "Error" && document.Calculator.Display.value !== "Invalid input") {
 if (Operation != 0 && Current != "") { Calculate(); }; //'Press "=" if pending operation!
 
 if (Current == "") {
 	Current = Memory;
 }
 // note that design is not good for showing *intermediate* results.

  if (op.indexOf("*") > -1) { Operation = 1; Sign = '×'; };       //codes for *
  if (op.indexOf("/") > -1) { Operation = 2; Sign = '÷'; };       // slash (divide)
  if (op.indexOf("+") > -1) { Operation = 3; Sign = '+'; };       // sum
  if (op.indexOf("-") > -1) { Operation = 4; Sign = '−'; };       // difference
  if (op.indexOf("^") > -1) { Operation = 5; Sign = '^'; };       // powers

  Memory = Current;                 //store value
  // note how e.g. Current.value gives neither error nor value! ***
  Current = "";
  document.Calculator.Memory.value = Memory + ' ' + Sign;
  document.Calculator.Display.value = Current;
 }
 }

function MemoryClear() {
	CalcMem = "0";
}
function MemoryRecall() {
	if (document.Calculator.Display.value !== "Overflow" && document.Calculator.Display.value !== "Error" && document.Calculator.Display.value !== "Invalid input") {
	Current = CalcMem.toLowerCase();
	document.Calculator.Display.value = Current;
	}
}
function MemorySave() {
	if (document.Calculator.Display.value !== "Overflow" && document.Calculator.Display.value !== "Error" && document.Calculator.Display.value !== "Invalid input") {
	if (Operation != 0) { Calculate(); }; //'Press "=" if pending operation!
	CalcMem = Current.toLowerCase();
	}
}
function MemoryAdd() {
	if (document.Calculator.Display.value !== "Overflow" && document.Calculator.Display.value !== "Error" && document.Calculator.Display.value !== "Invalid input") {
	if (Operation != 0) { Calculate(); }; //'Press "=" if pending operation!
	CalcMem = eval(Current) + eval(CalcMem);
	}
}
function MemorySubtract() {
	if (document.Calculator.Display.value !== "Overflow" && document.Calculator.Display.value !== "Error" && document.Calculator.Display.value !== "Invalid input") {
	if (Operation != 0) { Calculate(); }; //'Press "=" if pending operation!
	CalcMem = eval(CalcMem) - eval(Current);
	}
}


function Calculate()            //PERFORM CALCULATION (= button)
 { 
 if (Current.indexOf("r") == -1 && document.Calculator.Display.value !== "") {
  if (Current == "") { Current = 0; };
  
  if (Operation == 1) { Current = eval(Memory) * eval(Current); };
  if (Operation == 2)
    { if (eval(Current) != 0)
      { Current = eval(Memory) / eval(Current)
      } else
      { Current = "Divide by zero"; //don't allow over MAXLENGTH digits before "." ???
      }
    };
  if (Operation == 3) { Current = eval(Memory) + eval(Current); };
  if (Operation == 4) { Current = eval(Memory) - eval(Current); };
  if (Operation == 5) { Current = Math.pow( eval(Memory) , eval(Current) ) };
  Operation = 0;                //clear operation
  Memory = "0";                  //clear memory
  Current = Current + "";       //FORCE A STRING!
  if (Current.indexOf("Infinity") != -1)        //eg "1e320" * 1
    { Current = "Overflow";
    };
  if (Current.indexOf("NaN") != -1)        //eg "1e320" / "1e320"
    { Current = "Invalid input";
    };
  document.Calculator.Memory.value = '';
  document.Calculator.Display.value = Current;
  // NOTE: if no operation, nothing changes, Current is left the same!
 }
 }
 
function FixCurrent()
 {
  Current = document.Calculator.Display.value;
  Current = "" + parseFloat(Current);
  if (Current.indexOf("NaN") != -1)
    { Current = "Invalid input";
    };
  document.Calculator.Display.value = Current;
 }