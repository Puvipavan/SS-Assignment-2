<?php
/** BOX */
?>

.ba-node-<?php echo $id; ?> .pricing-table__box{
	background: <?php echo BACheetahColor::hex_or_rgb_or_gradient($settings->box_background_type, $settings->box_background, $settings->box_background_gradient) ?>
}

<?php
/** HEADER */
?>

.ba-node-<?php echo $id; ?> .pricing-table__header{
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->header_background); ?>;
	order: <?php echo $module->get_section_order('header') ?>;
}
.ba-node-<?php echo $id; ?> .pricing-table__header .pricing-table__title{
	color: <?php echo BACheetahColor::hex_or_rgb($settings->header_title_color); ?>;
}
.ba-node-<?php echo $id; ?> .pricing-table__header .pricing-table__subtitle{
	color: <?php echo BACheetahColor::hex_or_rgb($settings->header_subtitle_color); ?>;
}

<?php
/** PRICE */
?>

.ba-node-<?php echo $id; ?> .pricing-table__price {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->price_background); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->price_color); ?>;
	gap: <?php echo $settings->pricing_elements_gap; ?>px;
	order: <?php echo $module->get_section_order('price') ?>;
}
.ba-node-<?php echo $id; ?> .pricing-table__price .pricing-table__currency{
	font-size: <?php echo $settings->currency_size; ?>%;
	align-self: <?php echo $settings->currency_position; ?>;
}
.ba-node-<?php echo $id; ?> .pricing-table__price .pricing-table__period{
	font-size: <?php echo $settings->period_size; ?>%;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->period_color); ?>;
	<?php
	if ($settings->period_position == 'bellow') {
		echo "flex-basis: 100%;";
		echo "-ms-flex-preferred-size 100%;";
	}
	else {
		echo "align-self: $settings->period_position;";
		echo "-ms-flex-item-align: $settings->period_position;";
	}
	?>
}

<?php
/** PHOTO */
BACheetah::render_module_css( 'photo', $id, $module->get_image_settings());
?>
.ba-node-<?php echo $id; ?> .pricing-table__image {
	order: <?php echo $module->get_section_order('image') ?>;
}

<?php
/** FEATURES */
?>

.ba-node-<?php echo $id; ?> .pricing-table__features {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->features_background); ?>;
	order: <?php echo $module->get_section_order('features') ?>;
}
.ba-node-<?php echo $id; ?> .pricing-table__features .pricing-table__feature-item span {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->features_color); ?>;
}
.ba-node-<?php echo $id; ?> .pricing-table__features .pricing-table__feature-item--divider{
	margin: <?php echo ($settings->space_between_items / 2) ?>px 0;
}
.ba-node-<?php echo $id; ?> .pricing-table__features .pricing-table__feature-item--divider hr{
	border-top-width: <?php echo $settings->show_feature_divider == 'yes' ? $settings->divider_height : 0 ?>px;
	border-top-color: <?php echo BACheetahColor::hex_or_rgb($settings->divider_color); ?>;
	border-top-style: <?php echo $settings->divider_style ?>;
	flex-basis: <?php echo $settings->divider_width . $settings->divider_width_unit ?>;
	border-bottom: none;
}

.ba-node-<?php echo $id; ?> .pricing-table__features .pricing-table__feature-item i{
	font-size: <?php echo isset($settings->features_icon_size) && $settings->features_icon_size ? $settings->features_icon_size.$settings->features_icon_size_unit : 'inherit' ?>
}

<?php
/** RIBBON */
?>

.ba-node-<?php echo $id; ?> .pricing-table__ribbon::before{
	color: <?php echo BACheetahColor::hex_or_rgb($settings->ribbon_color); ?>;
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->ribbon_background_color); ?>;
}

<?php
/** CALL TO ACTION */
BACheetah::render_module_css( 'button', $id, $module->get_button_settings());
?>
.ba-node-<?php echo $id; ?> .pricing-table__cta {
	order: <?php echo $module->get_section_order('cta') ?>;
}
<?php


$justify_to_text_align = function() use ($settings) {
	return array_search($settings->features_alignment, array(
		'left' => 'flex-start',
		'center' => 'center',
		'right' => 'flex-end'
	));
};

// Features alignment
BACheetahCSS::rule( array(
	'selector'     => ".ba-node-$id .pricing-table__features .pricing-table__feature-item",
	'props'        => array(
		'justify-content' => $settings->features_alignment,
		'text-align' => $justify_to_text_align()
	)
));

// icon position - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'features_icon_position',
	'selector'     => ".ba-node-$id .pricing-table__features .pricing-table__feature-item",
	'prop'         => 'flex-direction',
));

BACheetahCSS::rule( array(
	'selector'     => ".ba-node-$id .pricing-table__features .pricing-table__feature-item span",
	'enabled'	   => $settings->features_alignment != 'center',
	'props'        => array(
		'flex' => 1,
	)
));

// Title typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'header_title_typography',
	'selector'    => ".ba-node-$id .pricing-table__header .pricing-table__title",
));

// Subtitle typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'header_subtitle_typography',
	'selector'    => ".ba-node-$id .pricing-table__header .pricing-table__subtitle",
));

// price typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'price_typography',
	'selector'    => ".ba-node-$id .pricing-table__price",
));

// box border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'box_border',
	'selector'  => ".ba-node-$id .pricing-table__box",
));

// Header border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'header_border',
	'selector'  => ".ba-node-$id .pricing-table__box .pricing-table__header",
));

// Price border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'pricing_border',
	'selector'  => ".ba-node-$id .pricing-table__box .pricing-table__price",
));

// Features border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'features_border',
	'selector'  => ".ba-node-$id .pricing-table__box .pricing-table__features",
));

// box Padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'box_padding',
	'selector'     => ".ba-node-$id .pricing-table__box",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'box_padding_top',
		'padding-right'  => 'box_padding_right',
		'padding-bottom' => 'box_padding_bottom',
		'padding-left'   => 'box_padding_left',
	),
));

// header padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'header_padding',
	'selector'     => ".ba-node-$id .pricing-table__box .pricing-table__header",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'header_padding_top',
		'padding-right'  => 'header_padding_right',
		'padding-bottom' => 'header_padding_bottom',
		'padding-left'   => 'header_padding_left',
	),
));

// price padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'pricing_padding',
	'selector'     => ".ba-node-$id .pricing-table__box .pricing-table__price",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'pricing_padding_top',
		'padding-right'  => 'pricing_padding_right',
		'padding-bottom' => 'pricing_padding_bottom',
		'padding-left'   => 'pricing_padding_left',
	),
));

// features typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'features_typography',
	'selector'    => ".ba-node-$id .pricing-table__box .pricing-table__features .pricing-table__feature-item",
));

// features padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'features_padding',
	'selector'     => ".ba-node-$id .pricing-table__box .pricing-table__features",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'features_padding_top',
		'padding-right'  => 'features_padding_right',
		'padding-bottom' => 'features_padding_bottom',
		'padding-left'   => 'features_padding_left',
	),
));

// features margin
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'features_margin',
	'selector'     => ".ba-node-$id .pricing-table__box .pricing-table__features",
	'unit'         => 'px',
	'props'        => array(
		'margin-top'    => 'features_margin_top',
		'margin-right'  => 'features_margin_right',
		'margin-bottom' => 'features_margin_bottom',
		'margin-left'   => 'features_margin_left',
	),
));

// ribbon font size
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'ribbon_fontsize',
	'enabled'	   => isset($settings->ribbon_display) && $settings->ribbon_display == 'block',
	'selector'     => ".ba-node-$id .pricing-table__box .pricing-table__ribbon::before",
	'prop'         => 'font-size',
));

// ribbon distance
BACheetahCSS::rule( array(
	'selector'     => ".ba-node-$id .pricing-table__box .pricing-table__ribbon::before",
	'enabled'	   => isset($settings->ribbon_display) && $settings->ribbon_display == 'block',
	'props'        => array(
		'padding-left' => $settings->ribbon_distance.$settings->ribbon_distance_unit,
		'padding-right' => $settings->ribbon_distance.$settings->ribbon_distance_unit,
	)
));

// ribbon typography
BACheetahCSS::typography_field_rule(array(
	'settings'    	=> $settings,
	'enabled'	   	=> isset($settings->ribbon_display) && $settings->ribbon_display == 'block',
	'setting_name'	=> 'ribbon_typography',
	'selector'    	=> ".ba-node-$id .pricing-table__box .pricing-table__ribbon::before",
));

// cta padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'cta_padding',
	'selector'     => ".ba-node-$id .pricing-table__box .pricing-table__cta",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'cta_padding_top',
		'padding-right'  => 'cta_padding_right',
		'padding-bottom' => 'cta_padding_bottom',
		'padding-left'   => 'cta_padding_left',
	),
));
