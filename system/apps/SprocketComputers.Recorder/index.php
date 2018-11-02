<?php
if (isset($_GET['checkfile'])) {
	if (file_exists($_GET['checkfile'])) {
		echo '<script>window.parent.Save_exists();</script>';
		exit;
	} else {
		echo '<script>window.parent.Save_save();</script>';
		exit;
	}
}
if ($_GET['save'] == 'true') {
	move_uploaded_file($_FILES["file"]["tmp_name"],$_GET['filename']);
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
include 'fileprocessor.php';

echo FlyLoadExtension('SprocketComputers.FileManager','SaveDialog');

echo $FlyFileStringFunction;
?>
<script>
var leftchannel = [];
var rightchannel = [];
var recorder = null;
var recording = false;
var recordingLength = 0;
var volume = null;
var audioInput = null;
var sampleRate = null;
var audioContext = null;
var context = null;
var outputString;

var BlobFile;
var AudioStream;
var Saved = true;

if (!navigator.getUserMedia)
	navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia ||
				  navigator.mozGetUserMedia || navigator.msGetUserMedia;

if (navigator.getUserMedia) {
    navigator.getUserMedia({audio:true}, success, function(e) {
    	ShowError('Audio could not be captured, possibly because permission was not given to use the microphone or your system does not have a microphone.');
    });
} else {
	ShowError('Audio recording is not supported in this browser.');
}

function interleave(leftChannel, rightChannel){
  var length = leftChannel.length + rightChannel.length;
  var result = new Float32Array(length);

  var inputIndex = 0;

  for (var index = 0; index < length; ){
	result[index++] = leftChannel[inputIndex];
	result[index++] = rightChannel[inputIndex];
	inputIndex++;
  }
  return result;
}

function mergeBuffers(channelBuffer, recordingLength){
  var result = new Float32Array(recordingLength);
  var offset = 0;
  var lng = channelBuffer.length;
  for (var i = 0; i < lng; i++){
	var buffer = channelBuffer[i];
	result.set(buffer, offset);
	offset += buffer.length;
  }
  return result;
}

function writeUTFBytes(view, offset, string){ 
  var lng = string.length;
  for (var i = 0; i < lng; i++){
	view.setUint8(offset + i, string.charCodeAt(i));
  }
}

function success(e){
	audioContext = window.AudioContext;
	context = new audioContext();

	sampleRate = context.sampleRate;

	volume = context.createGain();

	audioInput = context.createMediaStreamSource(e);

	audioInput.connect(volume);

	var bufferSize = 2048;
	recorder = context.createScriptProcessor(bufferSize, 2, 2);

	recorder.onaudioprocess = function(e){
		if (!recording) return;
		var left = e.inputBuffer.getChannelData (0);
		var right = e.inputBuffer.getChannelData (1);
		leftchannel.push (new Float32Array (left));
		rightchannel.push (new Float32Array (right));
		recordingLength += bufferSize;
	}

	volume.connect (recorder);
	recorder.connect (context.destination);
	
	AudioStream = window.URL.createObjectURL(e);
	
	MicrophoneAllowed();
}


function onload() {
	Fly.toolbar.init();
	Fly.toolbar.add.custom('waiting','<div style="height:100%;line-height:34px;margin-left:8px;" class="FlyUiTextHighlight">Waiting on microphone...</div>');
}
function MicrophoneAllowed() {
	Fly.toolbar.remove('waiting');
	
	Fly.toolbar.add('Record','record',Record,'<?php echo FLY_ICONS_URL; ?>record.svg','left');
	Fly.toolbar.add('Listen','listen',Listen,'<?php echo WORKING_URL; ?>recorder.svg','left');
	Fly.toolbar.add.custom('time','<div style="height:100%;line-height:34px;margin-right:8px;text-align:right;" id="time-text" class="FlyUiTextHighlight">00:00</div>','right');
}
function Record() {
	var recordButton = document.getElementById('FlyToolbarItem-record');
	if (recording) {
		recordButton.toggleOff();
		StopTime();
		recording = false;
		
		var leftBuffer = mergeBuffers ( leftchannel, recordingLength );
		var rightBuffer = mergeBuffers ( rightchannel, recordingLength );
		var interleaved = interleave ( leftBuffer, rightBuffer );
		
		var buffer = new ArrayBuffer(44 + interleaved.length * 2);
		var view = new DataView(buffer);
		
		writeUTFBytes(view, 0, 'RIFF');
		view.setUint32(4, 44 + interleaved.length * 2, true);
		writeUTFBytes(view, 8, 'WAVE');
		writeUTFBytes(view, 12, 'fmt ');
		view.setUint32(16, 16, true);
		view.setUint16(20, 1, true);
		view.setUint16(22, 2, true);
		view.setUint32(24, sampleRate, true);
		view.setUint32(28, sampleRate * 4, true);
		view.setUint16(32, 4, true);
		view.setUint16(34, 16, true);
		writeUTFBytes(view, 36, 'data');
		view.setUint32(40, interleaved.length * 2, true);
		
		var lng = interleaved.length;
		var index = 44;
		var volume = 1;
		for (var i = 0; i < lng; i++){
			view.setInt16(index, interleaved[i] * (0x7FFF * volume), true);
			index += 2;
		}
		
		var blob = new Blob ( [ view ], { type : 'audio/wav' } );
		
		var url = window.URL.createObjectURL(blob);
		
		BlobFile = blob;
		// ... upload & save
		
		document.getElementById('browser').browse();
		Fly.toolbar.add('Listen','listen',Listen,'<?php echo WORKING_URL; ?>recorder.svg','left');
	} else {
		if (!Saved) {
			Fly.control.confirm('Recording not saved','The previously recorded audio has not been saved, and starting a new recording will delete it. Would you like to save the previous recording?','Sound Recorder','<?php echo FLY_ICONS_URL; ?>warning.svg','document.getElementById("browser").browse()','StartRecording()');
		} else {
			StartRecording();
		}
	}
}
function StartRecording() {
	var recordButton = document.getElementById('FlyToolbarItem-record');
	
	if (Listening) {
		Listen();
	}
	Fly.toolbar.remove('listen');
	
	recordButton.toggleOn();
	recording = true;
	Saved = false;
	leftchannel.length = rightchannel.length = 0;
	recordingLength = 0;
	Length = 0;
	setTimeout(function() {UpdateTime();},1000);
}
function ShowError(err) {
	window.top.shell.dialog('An error occurred',err,'Sound Recorder - Error');
	Fly.window.close();
}
var Listening = false;
function Listen() {
	if (Listening) {
		Listening = false;
		document.getElementById('FlyToolbarItem-listen').toggleOff();
		document.getElementById('audio').src = '';
	} else {
		Listening = true;
		document.getElementById('FlyToolbarItem-listen').toggleOn();
		document.getElementById('audio').src = AudioStream;
	}
}
function FormatTime(sec) {
	var hr = Math.floor(sec / 3600);
	var min = Math.floor((sec - (hr * 3600))/60);
	sec -= ((hr * 3600) + (min * 60));
	sec += ''; min += '';
	while (min.length < 2) {min = '0' + min;}
	while (sec.length < 2) {sec = '0' + sec;}
	hr = (hr)?hr+':':'';
	return hr + min + ':' + sec;
}
var Length = 0;
var Timeout;
function UpdateTime() {
	var time = document.getElementById('time-text');
	Length += 1;
	time.innerHTML = FormatTime(Math.round(Length));
	Timeout = setTimeout(function() {UpdateTime();},1000);
}
function StopTime() {
	clearTimeout(Timeout);
}
function ResetTime() {
	var time = document.getElementById('time-text');
	Length = 0;
	time.innerHTML = FormatTime(0);
}
var SaveName;
function Save_check() {
	var frame = document.getElementById('frame');
	var browser = document.getElementById('browser');
	name = browser.vars.basename;
	name = FlyFileStringReplace(name)+'.wav';
	SaveName = name;
	frame.src = 'index.php?checkfile='+encodeURIComponent(browser.vars.bpath+'/'+SaveName);
}
function Save_exists() {
	var browser = document.getElementById('browser');
	Fly.control.confirm('File already exists','The file "'+SaveName+'" already exists in "'+browser.vars.pbasename+'". Do you want to overwrite it?','File Exists','<?php echo FLY_ICONS_URL; ?>warning.svg',Save_save,function() {setTimeout(function(){document.getElementById('browser').browse();},10)});
}
function Save_save() {
	var browser = document.getElementById('browser');
	var blob = BlobFile;
	form = new FormData(),
	request = new XMLHttpRequest();
	form.append("file",blob);
	request.open("POST",'index.php?save=true&filename='+encodeURIComponent(browser.vars.bpath+'/'+SaveName),true);
	request.send(form);
	document.getElementById('time-text').innerHTML = 'Saving...';
	request.onreadystatechange = function() {
		ResetTime();
	}
	Saved = true;
}
</script>
</head>
<body onload="onload()">

<div id="browser" style="display:none;"></div>
<script>
Fly.extension.replace('browser','SprocketComputers.FileManager','SaveDialog');
document.getElementById('browser').onchange = function() {
	Save_check();
}
</script>

<iframe id="frame" style="display:none;"></iframe>
<audio autoplay id="audio"></audio>

</body>
</html>