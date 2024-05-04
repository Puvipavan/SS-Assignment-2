<?php
$targets = array(array(
	'selector' 		=> '.ba-module__progress.ba-node-'.$id.' .progress__bar__inner',
	'type' 			=> 'style',
	"property" 		=> 'width',
	'mode'			=> 'performance'
));

if ($settings->show_counter == 'yes') {
	array_push($targets, array(
		'selector' => '.ba-module__progress.ba-node-'.$id.' .progress__bar__counter .counter__increment',
		'type' => 'text',
		'percentage' => $settings->counter_type == 'percentage'
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