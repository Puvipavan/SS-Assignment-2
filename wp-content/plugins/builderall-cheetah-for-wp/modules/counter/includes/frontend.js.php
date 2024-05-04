jQuery(document).ready(function() {
	try{
		new jsIncrements(<?php echo BACheetahCounterBase::get_settings_for_increments_js($settings, array( array(
			'selector' 		=> '.ba-module__counter.ba-node-'.$id.' .counter__increment',
			'type' 			=> 'text',
			"percentage" 	=> false
		)))?>).start();
	} catch (error) {
		console.log(error.message);
	}
});