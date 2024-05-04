.ba-node-<?php echo $id?> .counter__content {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->number_color);?>;
}

<?php 
// number typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'number_typography',
	'selector'    => ".ba-node-$id .counter__content",
));
?>