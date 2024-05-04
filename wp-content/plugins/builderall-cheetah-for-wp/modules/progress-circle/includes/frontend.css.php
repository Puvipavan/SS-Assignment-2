<?php
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'size',
	'selector'     => ".ba-node-$id svg",
	'props'        => array(
		'width' => array(
			'value' => $settings->size,
			'unit' =>  'px'
		),
		'height' =>array(
			'value' => $settings->size,
			'unit' =>  'px'
		),
	)
));

BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .progress-circle__circle",
	'enabled'  => isset($settings->circle_shadow['color']),
	'props'    => array(
		'filter' => 'drop-shadow('.BACheetahColor::shadow( $settings->circle_shadow, 'filter').')',
	),
));
