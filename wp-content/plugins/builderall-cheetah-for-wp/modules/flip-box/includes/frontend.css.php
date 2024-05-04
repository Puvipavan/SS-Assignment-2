<?php
$node_selector = ".ba-module__flip-box.ba-node-$id";

// render the popup css
if (isset($settings->popup_id)) {
	BACheetah::enqueue_layout_styles_scripts_by_id($settings->popup_id);
}
?>

<?php echo $node_selector ?> {
    min-height: <?php echo $settings->min_height?>px;
	background: <?php echo BACheetahColor::hex_or_rgb_or_gradient($settings->box_background_type, $settings->box_background, $settings->box_background_gradient) ?>;
}

<?php echo $node_selector ?> .flip-box__content {
	gap: <?php echo $settings->content_spacing_between_items ?>px;
}
<?php echo $node_selector ?> .flip-box__content .flip-box__title {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->title_color, 'initial') ?>;
}
<?php echo $node_selector ?> .flip-box__content .flip-box__subtitle {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->subtitle_color, 'initial') ?>;
}
<?php echo $node_selector ?> .flip-box__content.internal .flip-box__title_internal {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->title_color_internal, 'initial') ?>;
}
<?php echo $node_selector ?> .flip-box__content.internal .flip-box__subtitle_internal {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->subtitle_color_internal, 'initial') ?>;
}


<?php

BACheetah::render_module_css( 'button', $id, $module->get_button_settings());

// box border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'box_border',
	'selector'  => $node_selector,
));

// box border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'logo_box_border',
	'selector'  => "$node_selector .img-logo",
));

// image border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'image_border',
	'selector'  => "$node_selector .flip-box__image",
));

// box Padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'box_padding',
	'selector'     => $node_selector,
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'box_padding_top',
		'padding-right'  => 'box_padding_right',
		'padding-bottom' => 'box_padding_bottom',
		'padding-left'   => 'box_padding_left',
	),
));

// image size responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'image_size',
	'selector'     => "$node_selector .flip-box__image",
	'prop'         => 'flex-basis',
	'unit'		  => 'px'
));

// image position responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'image_position',
	'selector'     => $node_selector,
	'prop'         => 'flex-direction',
));


// content Padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'content_padding',
	'selector'     => "$node_selector .flip-box__content",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'content_padding_top',
		'padding-right'  => 'content_padding_right',
		'padding-bottom' => 'content_padding_bottom',
		'padding-left'   => 'content_padding_left',
	),
));

// title typography
BACheetahCSS::typography_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'title_typography',
	'selector'     => "$node_selector .flip-box__content .flip-box__title",
));

// subtitle typography
BACheetahCSS::typography_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'subtitle_typography',
	'selector'     => "$node_selector .flip-box__content .flip-box__subtitle",
));


// title_internal typography
BACheetahCSS::typography_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'title_typography_internal',
	'selector'     => "$node_selector .flip-box__content.internal .flip-box__title_internal",
));

// subtitle_internal typography
BACheetahCSS::typography_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'subtitle_typography_internal',
	'selector'     => "$node_selector .flip-box__content.internal .flip-box__subtitle_internal",
));

?>
