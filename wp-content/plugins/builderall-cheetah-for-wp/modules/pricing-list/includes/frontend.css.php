.ba-node-<?=$id?> .pricing-list__item:hover {
	background-color: <?= BACheetahColor::hex_or_rgb( $settings->item_hover_color, 'transparent' ) ?>;
}

.ba-node-<?=$id?> .pricing-list__heading {
	color: <?= BACheetahColor::hex_or_rgb( $settings->title_color ) ?>;
	gap: <?= $settings->divider_margin.'px' ?>; 
	align-items: <?= $settings->divider_align_v ?>; 
}

.ba-node-<?=$id?> .pricing-list__heading .pricing-list__price {
	color: <?= BACheetahColor::hex_or_rgb( $settings->price_color ) ?>;
}

.ba-node-<?=$id?> .pricing-list__description {
	color: <?= BACheetahColor::hex_or_rgb( $settings->description_color ) ?>;
}
.ba-node-<?=$id?> .pricing-list__heading hr {
	border-top-color: <?= BACheetahColor::hex_or_rgb( $settings->divider_color ) ?>;
}

<?php 

/** items */

BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'item_padding',
	'selector'     => ".ba-node-$id .pricing-list__item",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'item_padding_top',
		'padding-right'  => 'item_padding_right',
		'padding-bottom' => 'item_padding_bottom',
		'padding-left'   => 'item_padding_left',
	),
));

BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'space_between',
	'selector'     => ".ba-node-$id",
	'prop'         => 'gap',
	'unit'		   => 'px'
));

/** heading */

BACheetahCSS::typography_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'title_typography',
	'selector'     => ".ba-node-$id .pricing-list__heading",
) );

/** description */
BACheetahCSS::typography_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'description_typography',
	'selector'     => ".ba-node-$id .pricing-list__description",
) );

/**divider */
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .pricing-list__heading hr",
	'props' => array(
		'border-top-width' => $settings->divider_height.'px',
		'border-top-style' => $settings->divider_style
	),
));

/** photo */

BACheetah::render_module_css( 'photo', $id, $module->get_image_styles_settings());

BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'photo_margin',
	'selector'     => ".ba-node-$id .pricing-list__item",
	'prop'         => 'gap',
	'unit'		   => 'px'
));

BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'photo_vertical_align',
	'selector'     => ".ba-node-$id .pricing-list__item",
	'prop'         => 'align-items',
));

BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'space_between',
	'selector'     => ".ba-node-$id",
	'prop'         => 'gap',
	'unit'		   => 'px'
));
