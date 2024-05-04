
/* Global body Styles
------------------------------------------------------ */

<?php 
$global_selector = "html body.ba-cheetah";

BACheetahCSS::rule( array(
	'selector' => $global_selector,
	'enabled'  => in_array( $settings->bg_type, array( 'color', 'photo' )),
	'props'    => array(
		'background-color' => $settings->bg_color,
	),
));

// Background Gradient
BACheetahCSS::rule( array(
	'selector' => $global_selector,
	'enabled'  => 'gradient' === $settings->bg_type,
	'props'    => array(
		'background-image' => BACheetahColor::gradient( $settings->bg_gradient ),
	),
));

// Background Photo - Desktop
if ( 'photo' == $settings->bg_type ) :
	BACheetahCSS::rule( array(
		'selector' => $global_selector,
		'enabled'  => 'photo' === $settings->bg_type && !empty($settings->bg_image_src),
		'props'    => array(
			'background-image'      => $settings->bg_image_src,
			'background-repeat'     => $settings->bg_repeat,
			'background-position'   => $settings->bg_position,
			'background-attachment' => $settings->bg_attachment,
			'background-size'       => $settings->bg_size,
		),
	) );
endif;

// Body padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'body_padding',
	'selector'     => $global_selector,
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'body_padding_top',
		'padding-right'  => 'body_padding_right',
		'padding-bottom' => 'body_padding_bottom',
		'padding-left'   => 'body_padding_left',
	),
));