.ba-node-<?php echo $id; ?> ul .list__item {
	gap: <?php echo $settings->icon_text_gap . $settings->icon_text_gap_unit?>;
}

.ba-node-<?php echo $id; ?> ul .list__item .list__icon i{
	color: <?php echo BACheetahColor::hex_or_rgb($settings->icon_color); ?>;
}

.ba-node-<?php echo $id; ?> ul .list__item:hover .list__icon i{
	color: <?php echo BACheetahColor::hex_or_rgb($settings->icon_color_hover); ?>;
}

.ba-node-<?php echo $id; ?> ul .list__item .list__text {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->text_color); ?>;
}

.ba-node-<?php echo $id; ?> ul .list__divider{
	margin: <?php echo ($settings->space_between_items / 2) ?>px 0;
	justify-content: <?php echo $settings->alignment ?>;
}

.ba-node-<?php echo $id; ?> ul .list__divider hr{
	border-top-width: <?php echo $settings->divider_height ?>px;
	border-top-color: <?php echo BACheetahColor::hex_or_rgb($settings->divider_color); ?>;
	border-top-style: <?php echo $settings->divider_style ?>;
}

.list__text {
	min-width: 97% !important;
}

<?php

// typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'text_typography',
	'selector'    => ".ba-node-$id ul .list__item .list__text",
));

// alignment - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'alignment',
	'selector'     => ".ba-node-$id ul .list__item",
	'prop'         => 'justify-content',
));

// icon position - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'icon_position',
	'selector'     => ".ba-node-$id ul .list__item",
	'prop'         => 'flex-direction',
));

// icon size - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'icon_size',
	'selector'     => ".ba-node-$id ul .list__item .list__icon i",
	'unit'		   => 'px',
	'prop'         => 'font-size',
));

// divider width - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'divider_width',
	'selector'     => ".ba-node-$id ul .list__divider hr",
	'prop'         => 'flex-basis',
));