<?php

$targets = array(
	array(
		'selector' 		=> '.ba-module__progress-circle.ba-node-'.$id.' .progress-circle__progress',
		'type' 			=> 'style',
		'property' 		=> '--progress-percent',
		'unit'			=> null,
	),
);
if ($settings->number_enabled == 'yes') {
	array_push($targets, array(
		'selector' 		=> '.ba-module__progress-circle.ba-node-'.$id.' text',
		'type' 			=> 'text',
		'percentage' 	=> true
	));
}
?>

jQuery(document).ready(function() {
	try{
		new jsIncrements(<?php echo BACheetahCounterBase::get_settings_for_increments_js($settings, $targets) ?>).start();
	} catch (error) {
		console.log(error.message)
	}
});