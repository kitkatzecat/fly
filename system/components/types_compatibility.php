<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}

function FlyLoadTypesCompatibility() {
	global $_FLY;
	$array = json_decode(file_get_contents($_FLY['RESOURCE']['PATH']['COMPONENTS'].'types.json'),true);

	$xml_data = new SimpleXMLElement('<?xml version="1.0"?><filetypes></filetypes>');

	// function call to convert array to xml
	FlyLoadTypesCompatibility_ArrayToXML($array,$xml_data);

	//saving generated xml file; 
	file_put_contents($_FLY['RESOURCE']['PATH']['TYPES'],$xml_data->asXML());
}
function FlyLoadTypesCompatibility_ArrayToXML( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_numeric($key) ){
            $key = 'item'.$key; //dealing with <0/>..<n/> issues
        }
        if( is_array($value) ) {
            $subnode = $xml_data->addChild($key);
            FlyLoadTypesCompatibility_ArrayToXML($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}
FlyLoadTypesCompatibility();
?>