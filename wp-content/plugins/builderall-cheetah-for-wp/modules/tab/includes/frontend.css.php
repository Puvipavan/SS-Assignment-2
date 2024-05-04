.ba-node-<?php echo $id; ?> .tab__nav {
	<?php // background-color: <?php echo BACheetahColor::hex_or_rgb($settings->nav_background); ?>
}

.ba-node-<?php echo $id; ?> .tab__nav li{
	background: <?php echo BACheetahColor::hex_or_rgb_or_gradient($settings->nav_item_background_type, $settings->nav_item_background, $settings->nav_item_background_gradient)?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->nav_item_text_color, 'initial'); ?>;
	flex-grow: <?php echo $settings->grow ?>;
}

.ba-node-<?php echo $id; ?> .tab__nav li a {
	flex-direction: <?php echo $settings->icon_position ?>;
	gap: <?php echo $settings->icon_distance_from_text.$settings->icon_distance_from_text_unit ?>;
	<?php echo $module->get_item_alignment(); ?>
}
.ba-node-<?php echo $id; ?> .tab__nav li a i{
	font-size: <?php echo $settings->icon_size.$settings->icon_size_unit ?>;
}

.ba-node-<?php echo $id; ?> .tab__nav li.ui-tabs-active{
	background: <?php echo BACheetahColor::hex_or_rgb_or_gradient($settings->nav_item_active_background_type, $settings->nav_item_active_background, $settings->nav_item_active_background_gradient)?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->nav_item_active_text_color, 'initial'); ?> !important;
}

.ba-node-<?php echo $id; ?> .tab__body{
	padding: <?php echo $settings->body_padding ?>px;
	background: <?php echo BACheetahColor::hex_or_rgb_or_gradient($settings->body_background_type, $settings->body_background, $settings->body_background_gradient)?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->body_text_color, 'initial'); ?>;
}

.ba-node-<?php echo $id; ?>.tab--vertical .tab__nav{
	min-width: <?php echo $settings->nav_vertical_min_width . $settings->nav_vertical_min_width_unit ?>;
}
<?php

// Alignment responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'justify',
	'selector'     => ".ba-node-$id .tab__nav",
	'prop'         => 'justify-content',
));

// Nav border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'nav_border',
	'selector'  => ".ba-node-$id .tab__nav",
));

// Nav item typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'nav_item_typography',
	'selector'    => ".ba-node-$id .tab__nav li",
));

// Nav item border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'nav_item_border',
	'selector'  => ".ba-node-$id .tab__nav li",
));

// Nav item padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'nav_item_padding',
	'selector'     => ".ba-node-$id .tab__nav li",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'nav_item_padding_top',
		'padding-right'  => 'nav_item_padding_right',
		'padding-bottom' => 'nav_item_padding_bottom',
		'padding-left'   => 'nav_item_padding_left',
	),
));

// Nav item active border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'nav_item_active_border',
	'selector'  => ".ba-node-$id .tab__nav li.ui-tabs-active",
));

// Body typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'body_typography',
	'selector'    => ".ba-node-$id .tab__body",
));

// Body border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'body_border',
	'selector'  => ".ba-node-$id .tab__body",
));
