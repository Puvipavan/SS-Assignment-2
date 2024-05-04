.ba-node-<?php echo $id; ?> li {
	margin-top: <?php echo $settings->gap ?>px;
}

.ba-node-<?php echo $id; ?> ul .float-button__item {
	transition-duration: <?php echo $settings->colors_on_hover === 'disabled' ? '0s' : $settings->transition_duration . 'ms' ?>;
	-webkit-transition-duration: <?php echo $settings->colors_on_hover === 'disabled' ? '0s' : $settings->transition_duration . 'ms' ?>;
}

.ba-cheetah-module.ba-cheetah-module-float-button.ba-cheetah-node-<?php echo $id; ?> {
    position: fixed;
    z-index: 999;
    <?php if ($settings->v_alignment === 'flex-end'):?>
        bottom: 0;
    <?php else:?>
        top: 0;
    <?php endif;?>
    <?php if ($settings->alignment === 'flex-start'):?>
        left: 0;
    <?php else:?>
        right: 0;
    <?php endif;?>
}

<?php
// border rules
if ($settings->icon_shape === 'custom'):
	BACheetahCSS::border_field_rule(array(
		'settings'  => $settings,
		'setting_name'  => 'border',
		'selector'  => ".ba-node-$id ul .float-button__item",
	));
else:
?>
.ba-node-<?php echo $id; ?> .container-float-button-with-menu .float-button__menu,
.ba-node-<?php echo $id; ?> ul .float-button__item {
	border-radius: <?php echo $module->shape_to_unit()?> !important;
}
<?php endif; ?>


<?php
// color rules
if ($settings->use_default_colors === 'yes' || $settings->colors_on_hover === 'original'):
	$child_number = 0;
	foreach ($settings->items as $key => $item):

		$child_number++;
		$network_color = $module->get_float_button_network_color($item->icon);

		// original color
		if ($settings->use_default_colors === 'yes') {
			echo ".ba-node-$id ul .float-button__item:nth-child($child_number) {";
			echo "background: $network_color";
			echo "}";
		}

		// original color on hover
		if ($settings->colors_on_hover === 'original') {
			echo ".ba-node-$id ul .float-button__item:nth-child($child_number):hover {";
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
	'selector'     => ".ba-node-$id ul .float-button__item a",
	'prop'         => 'font-size',
	'unit'		   => 'px'
));

// padding
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'padding',
	'selector'     => ".ba-node-$id ul .float-button__item a",
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
.ba-node-<?php echo $id; ?> ul .float-button__item {
	background: <?php echo BACheetahColor::hex_or_rgb($settings->background); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->color); ?>;
}
<?php endif; ?>


<?php
// custom color on hover
if ($settings->colors_on_hover === 'custom'): ?>
.ba-node-<?php echo $id; ?> ul .float-button__item:hover {
	background: <?php echo BACheetahColor::hex_or_rgb($settings->background_hover); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->color_hover); ?>;
}
<?php endif; ?>


.ba-node-<?php echo $id; ?> .float-button__menu-anim.open .float-button__menu-toggle,
.ba-node-<?php echo $id; ?> .float-button__menu-toggle:hover {
    background-color: <?php echo BACheetahColor::hex_or_rgb($settings->bg_icon_color_active); ?> !important;
}

.ba-node-<?php echo $id; ?> .float-button__menu-anim.open .float-button__menu-toggle i,
.ba-node-<?php echo $id; ?> .float-button__menu-toggle:hover i{
    color: <?php echo BACheetahColor::hex_or_rgb($settings->icon_color_active); ?> !important;
}
