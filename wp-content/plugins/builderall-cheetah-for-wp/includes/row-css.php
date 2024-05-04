<?php if ( ! empty( $settings->text_color ) ) : // Text Color ?>
.ba-cheetah-node-<?php echo $id; ?> {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->text_color ); ?>;
}
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> *:not(input):not(textarea):not(select):not(a):not(h1):not(h2):not(h3):not(h4):not(h5):not(h6):not(.ba-cheetah-menu-mobile-toggle) {
	color: inherit;
}
<?php endif; ?>

<?php if ( ! empty( $settings->link_color ) ) : // Link Color ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> a {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->link_color ); ?>;
}
<?php elseif ( ! empty( $settings->text_color ) ) : ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> a {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->text_color ); ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->hover_color ) ) : // Link Hover Color ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> a:hover {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->hover_color ); ?>;
}
<?php elseif ( ! empty( $settings->text_color ) ) : ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> a:hover {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->text_color ); ?>;
}
<?php endif; ?>

<?php if ( ! empty( $settings->heading_color ) ) : // Heading Color ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h1,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h2,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h3,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h4,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h5,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h6,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h1 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h2 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h3 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h4 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h5 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h6 a {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->heading_color ); ?>;
}
<?php elseif ( ! empty( $settings->text_color ) ) : ?>
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h1,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h2,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h3,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h4,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h5,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h6,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h1 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h2 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h3 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h4 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h5 a,
.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> h6 a {
	color: <?php echo BACheetahColor::hex_or_rgb( $settings->text_color ); ?>;
}
<?php endif; ?>

<?php if ( 'yes' === $row->settings->bg_video_audio ) : ?>
.ba-cheetah-node-<?php echo $row->node; ?> .ba-cheetah-bg-video-audio {
	display: none;
	cursor: pointer;
	position: absolute;
	bottom: 20px;
	right: 20px;
	z-index: 5;
	width: 20px;
}
.ba-cheetah-node-<?php echo $row->node; ?> .ba-cheetah-bg-video-audio .ba-cheetah-audio-control {
	font-size: 20px;
}
.ba-cheetah-node-<?php echo $row->node; ?> .ba-cheetah-bg-video-audio .fa-times {
	font-size: 10px;
	vertical-align: middle;
	position: absolute;
	top: 5px;
	left: 11px;
	bottom: 0;
}
<?php endif; ?>

<?php

// Background Color
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
	'enabled'  => in_array( $settings->bg_type, array( 'color', 'photo', 'parallax', 'slideshow', 'video' ) ),
	'props'    => array(
		'background-color' => $settings->bg_color,
	),
) );

// Background Gradient
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
	'enabled'  => 'gradient' === $settings->bg_type,
	'props'    => array(
		'background-image' => BACheetahColor::gradient( $settings->bg_gradient ),
	),
) );

// Background Overlay
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap:after",
	'enabled'  => 'none' !== $settings->bg_overlay_type && in_array( $settings->bg_type, array( 'photo', 'parallax', 'slideshow', 'video' ) ),
	'props'    => array(
		'background-color' => 'color' === $settings->bg_overlay_type ? $settings->bg_overlay_color : '',
		'background-image' => 'gradient' === $settings->bg_overlay_type ? BACheetahColor::gradient( $settings->bg_overlay_gradient ) : '',
	),
) );

// Background Photo - Desktop
if ( 'photo' == $row->settings->bg_type ) :
	$row_bg_image_lg = '';

	if ( 'library' == $row->settings->bg_image_source ) {
		$row_bg_image_lg = $row->settings->bg_image_src;
	} elseif ( 'url' == $row->settings->bg_image_source && ! empty( $row->settings->bg_image_url ) ) {
		if ( 'array' == gettype( $row->settings->bg_image_url ) ) {
			$row_bg_image_lg = do_shortcode( $row->settings->bg_image_url['url'] );
		} else {
			$row_bg_image_lg = (string) do_shortcode( $row->settings->bg_image_url );
		}
	}
	if ( 'custom_pos' == $row->settings->bg_position ) {
		$row_bg_position_lg  = empty( $row->settings->bg_x_position ) ? '0' : $row->settings->bg_x_position;
		$row_bg_position_lg .= $row->settings->bg_x_position_unit;
		$row_bg_position_lg .= ' ';
		$row_bg_position_lg .= empty( $row->settings->bg_y_position ) ? '0' : $row->settings->bg_y_position;
		$row_bg_position_lg .= $row->settings->bg_y_position_unit;

	} else {
		$row_bg_position_lg = $row->settings->bg_position;
	}

	BACheetahCSS::rule( array(
		'selector' => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
		'enabled'  => 'photo' === $settings->bg_type,
		'props'    => array(
			'background-image'      => $row_bg_image_lg,
			'background-repeat'     => $settings->bg_repeat,
			'background-position'   => $row_bg_position_lg,
			'background-attachment' => $settings->bg_attachment,
			'background-size'       => $settings->bg_size,
		),
	) );
endif;

// Background Photo - Medium
if ( 'photo' == $row->settings->bg_type ) :
	$row_bg_image_md = '';

	if ( 'library' == $row->settings->bg_image_source ) {
		$row_bg_image_md = $row->settings->bg_image_medium_src;
	} elseif ( 'url' == $row->settings->bg_image_source && ! empty( $row->settings->bg_image_url ) ) {
		$row_bg_image_md = $row_bg_image_lg;
	}
	if ( 'custom_pos' == $row->settings->bg_position_medium ) {
		$row_bg_position_md  = empty( $row->settings->bg_x_position_medium ) ? '0' : $row->settings->bg_x_position_medium;
		$row_bg_position_md .= $row->settings->bg_x_position_medium_unit;
		$row_bg_position_md .= ' ';
		$row_bg_position_md .= empty( $row->settings->bg_y_position_medium ) ? '0' : $row->settings->bg_y_position_medium;
		$row_bg_position_md .= $row->settings->bg_y_position_medium_unit;

	} else {
		$row_bg_position_md = $row->settings->bg_position_medium;
	}

	BACheetahCSS::rule( array(
		'media'    => 'medium',
		'selector' => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
		'enabled'  => 'photo' === $settings->bg_type,
		'props'    => array(
			'background-image'      => $row_bg_image_md,
			'background-repeat'     => $settings->bg_repeat_medium,
			'background-position'   => $row_bg_position_md,
			'background-attachment' => $settings->bg_attachment_medium,
			'background-size'       => $settings->bg_size_medium,
		),
	) );
endif;

// Background Photo - Responsive
if ( 'photo' == $row->settings->bg_type ) :
	$row_bg_image_sm = '';

	if ( 'library' == $row->settings->bg_image_source ) {
		$row_bg_image_sm = $row->settings->bg_image_responsive_src;
	} elseif ( 'url' == $row->settings->bg_image_source && ! empty( $row->settings->bg_image_url ) ) {
		$row_bg_image_sm = $row_bg_image_lg;
	}

	if ( 'custom_pos' == $row->settings->bg_position_responsive ) {
		$row_bg_position_sm  = empty( $row->settings->bg_x_position_responsive ) ? '0' : $row->settings->bg_x_position_responsive;
		$row_bg_position_sm .= $row->settings->bg_x_position_responsive_unit;
		$row_bg_position_sm .= ' ';
		$row_bg_position_sm .= empty( $row->settings->bg_y_position_responsive ) ? '0' : $row->settings->bg_y_position_responsive;
		$row_bg_position_sm .= $row->settings->bg_y_position_responsive_unit;

	} else {
		$row_bg_position_sm = $row->settings->bg_position_responsive;
	}

	BACheetahCSS::rule( array(
		'media'    => 'responsive',
		'selector' => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
		'enabled'  => 'photo' === $settings->bg_type,
		'props'    => array(
			'background-image'      => $row_bg_image_sm,
			'background-repeat'     => $settings->bg_repeat_responsive,
			'background-position'   => $row_bg_position_sm,
			'background-attachment' => $settings->bg_attachment_responsive,
			'background-size'       => $settings->bg_size_responsive,
		),
	) );
endif;

// Background Parallax
BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
	'enabled'  => 'parallax' === $settings->bg_type,
	'props'    => array(
		'background-repeat'     => 'no-repeat',
		'background-position'   => 'center center',
		'background-attachment' => 'fixed',
		'background-size'       => 'cover',
	),
) );

BACheetahCSS::rule( array(
	'selector' => ".ba-cheetah-mobile .ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
	'enabled'  => 'parallax' === $settings->bg_type,
	'props'    => array(
		'background-image'      => $settings->bg_parallax_image_src,
		'background-position'   => 'center center',
		'background-attachment' => 'scroll',
	),
) );

// Border
BACheetahCSS::border_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border',
	'selector'     => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
) );

// Min Height
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'min_height',
	'selector'     => ".ba-cheetah-node-$id > .ba-cheetah-row-content-wrap",
	'prop'         => 'min-height',
	'enabled'      => 'custom' === $settings->full_height,
) );

// Row Resize - Max Width
if ( isset( $settings->max_content_width ) ) {
	$has_max_width        = ! BACheetahCSS::is_empty( $settings->max_content_width );
	$is_row_fixed         = ( 'fixed' === $settings->width );
	$is_row_content_fixed = ( 'fixed' === $settings->content_width );
	$are_both_full_width  = ( ! $is_row_fixed && ! $is_row_content_fixed );
	$max_width_selector   = '';

	if ( $is_row_fixed ) {
		$max_width_selector = ".ba-cheetah-node-$id.ba-cheetah-row-fixed-width, .ba-cheetah-node-$id .ba-cheetah-row-fixed-width";
	} else {
		$max_width_selector = ".ba-cheetah-node-$id .ba-cheetah-row-content";
	}

	BACheetahCSS::rule( array(
		'selector' => $max_width_selector,
		'enabled'  => $has_max_width && ! $are_both_full_width,
		'props'    => array(
			'max-width' => array(
				'value' => $settings->max_content_width,
				'unit'  => BACheetahCSS::get_unit( 'max_content_width', $settings ),
			),
		),
	) );
}

BACheetahArt::render_shape_layers_css( $row );
