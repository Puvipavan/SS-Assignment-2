
.ba-node-<?php echo $id; ?> .progress__bar__outer {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->bar_outer_background, 'initial'); ?>;
}

.ba-node-<?php echo $id?> .progress__bar__counter {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->number_color);?>;
}

.ba-node-<?php echo $id?> .progress__bar__inner .progress__bar__text {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->internal_text_color);?>;
}

<?php 

// bar border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'bar_border',
	'selector'  => ".ba-node-$id .progress__bar__outer",
));

BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'bar_height',
	'selector'     => ".ba-node-$id .progress__bar__outer",
	'prop'         => 'height',
	'unit'		   => 'px'
));

BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'number_typography',
	'selector'    => ".ba-node-$id .progress__bar__counter",
));

BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'internal_text_typography',
	'selector'    => ".ba-node-$id .progress__bar__inner .progress__bar__text",
));

BACheetahCSS::responsive_rule(array(
	'settings' => $settings,
	'setting_name' => 'counter_position',
	'enabled' => $settings->counter_position != 'bar',
	'selector' => ".ba-node-$id",
	'prop' => 'flex-direction'
));

// round the inner bar if the outer one is rounded too
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .progress__bar__inner",
	'enabled' => isset($settings->bar_border['radius']['top_right']) && isset($settings->bar_border['radius']['bottom_right']),
	'props' => array(
		'border-top-right-radius' => $settings->bar_border['radius']['top_right'].'px',
		'border-bottom-right-radius' => $settings->bar_border['radius']['bottom_right'].'px',
	),
));

BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id",
	'enabled' => in_array($settings->counter_position, ['row', 'row-reverse']),
	'props' => array(
		'align-items' => 'center'
	),
));

// background color or gradient
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .progress__bar__inner",
	'enabled'  => in_array($settings->bar_inner_bg_type, array('solid', 'gradient')),
	'props'    => array(
		'background' => BACheetahColor::hex_or_rgb_or_gradient($settings->bar_inner_bg_type, $settings->bar_inner_bg, $settings->bar_inner_bg_gradient),
	),
));

// background image
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .progress__bar__inner",
	'enabled'  => 'photo' === $settings->bar_inner_bg_type && isset($settings->inner_bg_image_src),
	'props'    => array(
		'background-image'      => $settings->inner_bg_image_src,
		'background-repeat'     => $settings->inner_bg_repeat,
		'background-position'   => $settings->inner_bg_position,
		'background-size'       => $settings->inner_bg_size,
		'background-color'		=> $settings->bar_inner_bg,
	),
));

// smooth animation
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .progress__bar__inner",
	'props' => array(
		'transition' => sprintf('width %sms linear', (($settings->duration * 1000) / ($settings->to - $settings->from)) * $settings->step)
	),
));