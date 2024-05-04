.ba-node-<?php echo $id; ?> .line__wrapper {
	flex-direction: <?php echo $settings->content_justify ?>;
}

.ba-node-<?php echo $id; ?> .line__wrapper .line__border{
	border-top-color: <?php echo BACheetahColor::hex_or_rgb($settings->border_color); ?>;
	border-top-style: <?php echo $settings->border_style ?>;
	box-shadow: <?php echo BACheetahColor::shadow( $settings->border_shadow ); ?>;
	border-radius: <?php echo $settings->border_radius ?>px;
}

.ba-node-<?php echo $id; ?> .line__wrapper .line__content {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->content_background); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->content_color); ?>;
}

<?php 

// content border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'content_border',
	'selector'  => ".ba-node-$id .line__wrapper .line__content",
));

// Content typography
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'content_typography',
	'selector'    => ".ba-node-$id .line__wrapper .line__content",
));

// content padding
BACheetahCSS::dimension_field_rule(array(
	'settings'     => $settings,
	'setting_name' => 'content_padding',
	'selector'     => ".ba-node-$id .line__wrapper .line__content",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'content_padding_top',
		'padding-right'  => 'content_padding_right',
		'padding-bottom' => 'content_padding_bottom',
		'padding-left'   => 'content_padding_left',
	),
));

// align border - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border_justify',
	'selector'     => ".ba-node-$id",
	'prop'         => 'justify-content',
));

// border width - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border_width',
	'selector'     => ".ba-node-$id .line__wrapper",
	'prop'         => 'width',
));

// border height - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border_height',
	'selector'     => ".ba-node-$id .line__wrapper .line__border",
	'prop'         => 'border-top-width',
));

// icon size - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'icon_size',
	'selector'     => ".ba-node-$id .line__wrapper .line__content i",
	'prop'         => 'font-size',
	'unit'		   => 'px'
));
