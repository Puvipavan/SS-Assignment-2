.ba-node-<?php echo $id; ?> .accordion__title{
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->header_background); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->header_text_color); ?>;
	flex-direction: <?php echo $settings->icon_position ?>;
}

.ba-node-<?php echo $id; ?> .accordion__content.ui-accordion:not(:first-of-type),
.ba-node-<?php echo $id; ?> .accordion__title:not(:first-of-type) {
	margin-top: <?php echo $settings->space_between_items ?>px;
}

.ba-node-<?php echo $id; ?> .accordion__body{
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->body_background); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->body_text_color); ?>;
}

<?php if(BACheetahModel::is_builder_active() && false) { ?>
	.accordion__body {
		display: block !important;
	}
<?php } ?>

<?php
// Header typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'header_typography',
	'selector'    => ".ba-node-$id .accordion__title",
));

// Header border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'header_border',
	'selector'  => ".ba-node-$id .accordion__title",
));

// Header padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'header_padding',
	'selector'     => ".ba-node-$id .accordion__title",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'header_padding_top',
		'padding-right'  => 'header_padding_right',
		'padding-bottom' => 'header_padding_bottom',
		'padding-left'   => 'header_padding_left',
	),
));

// Body typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'body_typography',
	'selector'    => ".ba-node-$id .accordion__body",
));

// Body border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'body_border',
	'selector'  => ".ba-node-$id .accordion__body",
));

// Body margin
BACheetahCSS::dimension_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'body_margin',
	'selector'     => ".ba-node-$id .accordion__body",
	'unit'         => 'px',
	'props'        => array(
		'margin-top'    => 'body_margin_top',
		'margin-right'  => 'body_margin_right',
		'margin-bottom' => 'body_margin_bottom',
		'margin-left'   => 'body_margin_left',
	),
));
?>