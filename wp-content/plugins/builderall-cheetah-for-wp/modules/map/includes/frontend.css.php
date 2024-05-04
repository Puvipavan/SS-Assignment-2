<?php

BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'border',
	'selector'  => ".ba-module__map.ba-node-$id",
));

// size
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'height',
	'selector'     => ".ba-node-$id",
	'prop'         => 'height',
));