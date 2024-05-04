<?php

BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'border',
	'selector'  => ".ba-node-$id iframe",
));

// size
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'height',
	'selector'     => ".ba-node-$id iframe",
	'prop'         => 'height',
	'unit'		   => isset($settings->height_unit) ? $settings->height_unit : 'px'
));