<!DOCTYPE html>
<html>
<head>
<?php
include 'fly.php';

function generate_timezone_list()
{
	static $regions = array(
		//DateTimeZone::AFRICA,
		DateTimeZone::AMERICA//,
		//DateTimeZone::ANTARCTICA,
		//DateTimeZone::ASIA,
		//DateTimeZone::ATLANTIC,
		//DateTimeZone::AUSTRALIA,
		//DateTimeZone::EUROPE,
		//DateTimeZone::INDIAN,
		//DateTimeZone::PACIFIC,
	);

	$timezones = array();
	foreach ($regions as $region) {
		$timezones = array_merge($timezones, DateTimeZone::listIdentifiers($region));
	}

	$timezone_offsets = array();
	foreach($timezones as $timezone) {
		$tz = new DateTimeZone($timezone);
		$timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
	}

	// sort timezone by offset
	asort($timezone_offsets);

	$timezone_list = array();
	foreach($timezone_offsets as $timezone => $offset) {
		$offset_prefix = $offset < 0 ? '-' : '+';
		$offset_formatted = gmdate( 'H:i', abs($offset) );

		$pretty_offset = "UTC${offset_prefix}${offset_formatted}";

		$timezone_list[$timezone] = "(${pretty_offset}) $timezone";
	}

	return $timezone_list;
}
?>
<link rel="stylesheet" href="../style.css">
<script>
var OptionsTree = [
	{name:'Time & Date',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>clock.svg',index:'timedate/index.php'},
	{name:'Change time zone',icon:'<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>earth.svg'}
];
</script>
</head>
<body class="FlyUiText FlyUiNoSelect">

<div class="title">Change your time zone</div>
<div class="title">Region:</div>
<p><input class="inline-icon" type="radio" name="region" id="Africa"><label for="Africa">Africa</label></p>
<p><input class="inline-icon" checked type="radio" name="region" id="America"><label for="America">America</label></p>
<p><input class="inline-icon" type="radio" name="region" id="Antarctica"><label for="Antarctica">Antarctica</label></p>
<p><input class="inline-icon" type="radio" name="region" id="Asia"><label for="Asia">Asia</label></p>
<p><input class="inline-icon" type="radio" name="region" id="Atlantic"><label for="Atlantic">Atlantic</label></p>
<p><input class="inline-icon" type="radio" name="region" id="Europe"><label for="Europe">Europe</label></p>
<p><input class="inline-icon" type="radio" name="region" id="Indian"><label for="Indian">Indian</label></p>
<p><input class="inline-icon" type="radio" name="region" id="Pacific"><label for="Pacific">Pacific</label></p>

<div class="title">Time zone:</div>
<?php
foreach (generate_timezone_list() as $name => $timezone) {
	if ($name == date_default_timezone_get()) {
		$checked = ' checked';
	} else {
		$checked = '';
	}
	echo '<p><input class="inline-icon"'.$checked.' type="radio" name="timezone" id="'.$name.'"><label for="'.$name.'">'.str_replace('_',' ',$timezone).'</label></p>';
}
?>

<div class="buttons"><button><img style="width:16px;height:16px;vertical-align:middle;margin-right:6px;" src="<?php echo $_FLY['RESOURCE']['URL']['ICONS']; ?>save.svg">Save</button></div>

</body>
</html>