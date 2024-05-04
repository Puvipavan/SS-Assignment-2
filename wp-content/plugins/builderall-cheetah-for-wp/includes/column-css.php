<?php if ( ! empty( $col->settings->text_color ) ) : // Text Color ?>
.ba-cheetah-node-<?php echo $col->node; ?> {
	color: <?php echo BACheetahColor::hex_or_rgb( $col->settings->text_color ); ?>;
}
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> *:not(input):not(textarea):not(select):not(a):not(h1):not(h2):not(h3):not(h4):not(h5):not(h6):not(.ba-cheetah-menu-mobile-toggle) {
	color: <?php echo BACheetahColor::hex_or_rgb( $col->settings->text_color ); ?>;
}
<?php endif; ?>

<?php if ( ! empty( $col->settings->link_color ) ) : // Link Color ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> a {
	color: <?php echo BACheetahColor::hex_or_rgb( $col->settings->link_color ); ?>;
}
<?php elseif ( ! empty( $col->settings->text_color ) ) : ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> a {
	color: <?php echo BACheetahColor::hex_or_rgb( $col->settings->text_color ); ?>;
}
<?php endif; ?>

<?php if ( ! empty( $col->settings->hover_color ) ) : // Link Hover Color ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> a:hover {
	color: <?php echo BACheetahColor::hex_or_rgb( $col->settings->hover_color ); ?>;
}
<?php elseif ( ! empty( $col->settings->text_color ) ) : ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> a:hover {
	color: <?php echo BACheetahColor::hex_or_rgb( $col->settings->text_color ); ?>;
}
<?php endif; ?>

<?php if ( ! empty( $col->settings->heading_color ) ) : // Heading Color ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h1,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h2,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h3,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h4,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h5,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h6,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h1 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h2 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h3 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h4 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h5 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h6 a {
	color: <?php echo BACheetahColor::hex_or_rgb( $col->settings->heading_color ); ?>;
}
<?php elseif ( ! empty( $col->settings->text_color ) ) : ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h1,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h2,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h3,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h4,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h5,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h6,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h1 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h2 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h3 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h4 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h5 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $col->node; ?> h6 a {
	color: <?php echo BACheetahColor::hex_or_rgb( $col->settings->text_color ); ?>;
}
<?php endif; ?>

<?php

$responsive_enabled = $global_settings->responsive_enabled;

// Width - Desktop
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id",
	'props'    => array(
		'width' => "{$settings->size}%",
	),
) );

// Width - Medium
BACheetahCSS::rule( array(
	'media'    => 'medium',
	'selector' => ".ba-cheetah-content .ba-cheetah-node-$id",
	'enabled'  => '' !== $settings->size_medium && $responsive_enabled,
	'props'    => array(
		'width'            => "{$settings->size_medium}% !important",
		'max-width'        => 'none',
		'-webkit-box-flex' => '0 1 auto',
		'-moz-box-flex'    => '0 1 auto',
		'-webkit-flex'     => '0 1 auto',
		'-ms-flex'         => '0 1 auto',
		'flex'             => '0 1 auto',
	),
) );

// Width - Responsive
BACheetahCSS::rule( array(
	'media'    => 'responsive',
	'selector' => ".ba-cheetah-content .ba-cheetah-node-$id",
	'enabled'  => '' !== $settings->size_responsive && $responsive_enabled,
	'props'    => array(
		'width'     => "{$settings->size_responsive}% !important",
		'max-width' => 'none',
		'clear'     => 'none',
		'float'     => 'left',
	),
) );

// Stacking Order - Responsive
BACheetahCSS::rule( array(
	'media'    => 'responsive',
	'selector' => ".ba-cheetah-col-group-custom-width.ba-cheetah-col-group-responsive-reversed .ba-cheetah-node-$id",
	'enabled'  => 'reversed' == $settings->responsive_order && '' !== $settings->size_responsive && $responsive_enabled,
	'props'    => array(
		'flex-basis' => "{$settings->size_responsive}%",
		'margin'     => '0',
	),
) );

// Set Order - Responsive
BACheetahCSS::rule( array(
	'media'    => 'responsive',
	'selector' => ".ba-cheetah-col-group.ba-cheetah-col-group-responsive-set .ba-cheetah-node-$id",
	'enabled'  => 'set' == $settings->responsive_order && $responsive_enabled && $settings->responsive_order_set > 0,
	'props'    => array(
		'order' => $settings->responsive_order_set,
	),
) );

// Background Color
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-col-content",
	'enabled'  => ( ( 'color' == $settings->bg_type ) || ( 'photo' == $settings->bg_type ) ),
	'props'    => array(
		'background-color' => $settings->bg_color,
	),
) );

// Background Gradient
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-col-content",
	'enabled'  => 'gradient' === $settings->bg_type,
	'props'    => array(
		'background-image' => BACheetahColor::gradient( $settings->bg_gradient ),
	),
) );

// Background Color Overlay
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-col-content:after",
	'enabled'  => 'none' !== $settings->bg_overlay_type && in_array( $settings->bg_type, array( 'photo' ) ),
	'props'    => array(
		'background-color' => 'color' === $settings->bg_overlay_type ? $settings->bg_overlay_color : '',
		'background-image' => 'gradient' === $settings->bg_overlay_type ? BACheetahColor::gradient( $settings->bg_overlay_gradient ) : '',
	),
) );

// Background Photo - Desktop
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-col-content",
	'enabled'  => 'photo' === $settings->bg_type,
	'props'    => array(
		'background-image'      => $settings->bg_image_src,
		'background-repeat'     => $settings->bg_repeat,
		'background-position'   => $settings->bg_position,
		'background-attachment' => $settings->bg_attachment,
		'background-size'       => $settings->bg_size,
	),
) );

// Background Photo - Medium
BACheetahCSS::rule( array(
	'media'    => 'medium',
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-col-content",
	'enabled'  => 'photo' === $settings->bg_type,
	'props'    => array(
		'background-image'      => $settings->bg_image_medium_src,
		'background-repeat'     => $settings->bg_repeat_medium,
		'background-position'   => $settings->bg_position_medium,
		'background-attachment' => $settings->bg_attachment_medium,
		'background-size'       => $settings->bg_size_medium,
	),
) );

// Background Photo - Responsive
BACheetahCSS::rule( array(
	'media'    => 'responsive',
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-col-content",
	'enabled'  => 'photo' === $settings->bg_type,
	'props'    => array(
		'background-image'      => $settings->bg_image_responsive_src,
		'background-repeat'     => $settings->bg_repeat_responsive,
		'background-position'   => $settings->bg_position_responsive,
		'background-attachment' => $settings->bg_attachment_responsive,
		'background-size'       => $settings->bg_size_responsive,
	),
) );

// Border
BACheetahCSS::border_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border',
	'selector'     => ".ba-cheetah-node-$id > .ba-cheetah-col-content",
) );

// Minimum Height
BACheetahCSS::responsive_rule( array(
	'settings'     => $col->settings,
	'setting_name' => 'min_height',
	'selector'     => ".ba-cheetah-content .ba-cheetah-node-$id > .ba-cheetah-col-content",
	'prop'         => 'min-height',
) );
