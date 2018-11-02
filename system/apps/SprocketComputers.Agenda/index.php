<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';
?>
<script>
function onload() {
	Fly.toolbar.init();
	Fly.toolbar.add('Contacts','tab-contacts',TabContacts,'<?php echo FLY_ICONS_URL; ?>person.svg');
	Fly.toolbar.add('Reminders','tab-reminders',TabReminders,'<?php echo FLY_ICONS_URL; ?>index.svg');
	Fly.toolbar.add('Calendar','tab-calendar',TabCalendar,'<?php echo FLY_ICONS_URL; ?>calendar.svg');
}
function TabContacts() {
	document.getElementById('FlyToolbarItem-tab-contacts').toggleOff();
	document.getElementById('FlyToolbarItem-tab-reminders').toggleOff();
	document.getElementById('FlyToolbarItem-tab-calendar').toggleOff();
	
	document.getElementById('FlyToolbarItem-tab-contacts').toggleOn();
	document.getElementById('Content-Frame').src = 'contacts.php';
}
function TabReminders() {
	document.getElementById('FlyToolbarItem-tab-contacts').toggleOff();
	document.getElementById('FlyToolbarItem-tab-reminders').toggleOff();
	document.getElementById('FlyToolbarItem-tab-calendar').toggleOff();
	
	document.getElementById('FlyToolbarItem-tab-reminders').toggleOn();
	document.getElementById('Content-Frame').src = 'reminders.php';
}
function TabCalendar() {
	document.getElementById('FlyToolbarItem-tab-contacts').toggleOff();
	document.getElementById('FlyToolbarItem-tab-reminders').toggleOff();
	document.getElementById('FlyToolbarItem-tab-calendar').toggleOff();
	
	document.getElementById('FlyToolbarItem-tab-calendar').toggleOn();
	document.getElementById('Content-Frame').src = 'calendar.php';
}
</script>
</head>
<body onload="onload()">
<div style="position:absolute;top:34px;left:0px;right:0px;bottom:0px;background:transparent;">
<iframe id="Content-Frame" style="width:100%;height:100%;" frameborder="0" src="" allowtransparency="yes"></iframe>
</div>

</body>
</html>