.ba-node-<?php echo $id; ?> .icon__wrapper {
	background: <?php echo $settings->icon_shape == 'none'  ? 'transparent' : BACheetahColor::hex_or_rgb($settings->background); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->color); ?>;
	border-radius: <?php echo $module->shape_to_unit()?>;
}

.ba-node-<?php echo $id; ?> .icon__wrapper:hover{
	background: <?php echo $settings->icon_shape == 'none'  ? 'transparent' : BACheetahColor::hex_or_rgb($settings->background_hover); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->color_hover); ?>;
}

<?php
// border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'border',
	'selector'  => ".ba-node-$id .icon__wrapper",
));


// size
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'size',
	'selector'     => ".ba-node-$id .icon__wrapper",
	'prop'         => 'font-size',
	'unit'		   => 'px'
));

// padding
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'padding',
	'selector'     => ".ba-node-$id .icon__wrapper",
	'prop'         => 'padding',
));

// Alignment
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'alignment',
	'selector'     => ".ba-node-$id",
	'prop'         => 'justify-content',
));
?>