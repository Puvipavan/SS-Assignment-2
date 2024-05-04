.ba-node-<?php echo $id; ?> ul {
	gap: <?php echo $settings->gap ?>px;
}

.ba-node-<?php echo $id; ?> ul .social__item {
	transition-duration: <?php echo $settings->colors_on_hover === 'disabled' ? '0s' : $settings->transition_duration . 'ms' ?>;
	-webkit-transition-duration: <?php echo $settings->colors_on_hover === 'disabled' ? '0s' : $settings->transition_duration . 'ms' ?>;

}

<?php
// border rules
if ($settings->icon_shape === 'custom'):
	BACheetahCSS::border_field_rule(array(
		'settings'  => $settings,
		'setting_name'  => 'border',
		'selector'  => ".ba-node-$id ul .social__item",
	));
else:
?>
.ba-node-<?php echo $id; ?> ul .social__item {
	border-radius: <?php echo $module->shape_to_unit()?>;
}
<?php endif; ?>


<?php
// color rules
if ($settings->use_default_colors === 'yes' || $settings->colors_on_hover === 'original'):
	$child_number = 0;
	foreach ($settings->items as $key => $item): 

		$child_number++;
		$network_color = $module->get_social_network_color($item->icon);

		// original color
		if ($settings->use_default_colors === 'yes') {
			echo ".ba-node-$id ul .social__item:nth-child($child_number) {";
			echo "background: $network_color";
			echo "}";
		}

		// original color on hover
		if ($settings->colors_on_hover === 'original') {
			echo ".ba-node-$id ul .social__item:nth-child($child_number):hover {";
			echo "color: #fff;";
			echo "background: $network_color";
			echo "}";
		}

	endforeach; 
endif;
?>

<?php 
// size
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'size',
	'selector'     => ".ba-node-$id ul .social__item a",
	'prop'         => 'font-size',
	'unit'		   => 'px'
));

// padding
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'padding',
	'selector'     => ".ba-node-$id ul .social__item a",
	'prop'         => 'padding',
));

// Alignment
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'alignment',
	'selector'     => ".ba-node-$id ul",
	'prop'         => 'justify-content',
));
?>


<?php 
// custom color
if ($settings->use_default_colors === 'no'): ?>
.ba-node-<?php echo $id; ?> ul .social__item {
	background: <?php echo BACheetahColor::hex_or_rgb($settings->background); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->color); ?>;
}
<?php endif; ?>


<?php 
// custom color on hover
if ($settings->colors_on_hover === 'custom'): ?>
.ba-node-<?php echo $id; ?> ul .social__item:hover {
	background: <?php echo BACheetahColor::hex_or_rgb($settings->background_hover); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->color_hover); ?>;
}
<?php endif; ?>