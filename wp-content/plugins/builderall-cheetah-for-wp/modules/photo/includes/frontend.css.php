<?php

// render the popup css
if ($settings->link_type == 'popup' && isset($settings->popup_id) && (int) $settings->popup_id) {
	BACheetah::enqueue_layout_styles_scripts_by_id($settings->popup_id);
}

BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id .ba-cheetah-photo-img",
	'enabled' => in_array($settings->link_type, ['popup', 'video']),
	'props' => array(
		'cursor' => 'pointer',
	),
));

// Align
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'align',
	'selector'     => ".ba-cheetah-node-$id .ba-cheetah-photo",
	'prop'         => 'text-align',
) );

// Width
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'width',
	'selector'     => ".ba-cheetah-node-$id .ba-cheetah-photo-img, .ba-cheetah-node-$id .ba-cheetah-photo-content",
	'prop'         => 'width',
) );

// Border
if ( 'circle' === $settings->crop ) {
	$settings->border['radius'] = array(
		'top_left'     => '',
		'top_right'    => '',
		'bottom_left'  => '',
		'bottom_right' => '',
	);
}

BACheetahCSS::border_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border',
	'selector'     => ".ba-cheetah-node-$id .ba-cheetah-photo-img",
) );

BACheetahCSS::typography_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'caption_typography',
	'selector'     => ".ba-cheetah-node-$id.ba-cheetah-module-photo .ba-cheetah-photo-caption",
) );

?>

.ba-cheetah-node-<?php echo $id?>.ba-cheetah-module-photo .ba-cheetah-photo-caption {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->caption_color, 'initial'); ?>
}