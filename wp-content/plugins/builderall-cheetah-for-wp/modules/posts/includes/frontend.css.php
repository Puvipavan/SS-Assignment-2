<?php 
BACheetah::render_module_css( 'card', $id, $module->get_all_settings());

// gap responsive
BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'gap',
	'selector'     => ".ba-node-$id.ba-module__posts",
	'prop'         => 'gap',
	'unit'		   => 'px'
));

// Columns responsive
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id.ba-module__posts",
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns . ', 1fr)',
	),
));
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id.ba-module__posts",
	'media' => 'medium',
	'enabled' => !empty($settings->columns_medium),
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns_medium . ', 1fr)',
	),
));
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id.ba-module__posts",
	'media' => 'responsive',
	'enabled' => !empty($settings->columns_responsive),
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns_responsive . ', 1fr)',
	),
));
?>