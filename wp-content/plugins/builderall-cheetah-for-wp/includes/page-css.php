
/* Page body Styles
------------------------------------------------------ */

<?php 

$page_selector = 'html body.ba-cheetah.page-id-' . $post_id;

if (isset($settings->bg_type) && $settings->bg_type != 'global') {

	BACheetahCSS::rule( array(
		'selector' => $page_selector,
		'enabled'  => in_array( $settings->bg_type, array( 'color', 'photo' )) && !empty($settings->bg_color),
		'props'    => array(
			'background-color' => $settings->bg_color,
		),
	));

	// Background Gradient
	BACheetahCSS::rule( array(
		'selector' => $page_selector,
		'enabled'  => 'gradient' === $settings->bg_type && !empty($settings->bg_gradient),
		'props'    => array(
			'background-image' => BACheetahColor::gradient( $settings->bg_gradient ),
		),
	));

	// Background Photo - Desktop
	if ( 'photo' == $settings->bg_type ) :
		BACheetahCSS::rule( array(
			'selector' => $page_selector,
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

	if ($settings->bg_type == 'color' && !empty($settings->bg_color)) {
		echo $page_selector . '{ background-image: none }';
	}

	if ($settings->bg_type == 'photo' && empty($settings->bg_image_src)) {
		echo $page_selector . '{ background-image: none }';
	}

}

// Body padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'enabled'	   => isset($settings->body_padding) && !empty($settings->body_padding),
	'setting_name' => 'body_padding',
	'selector'     => $page_selector,
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'body_padding_top',
		'padding-right'  => 'body_padding_right',
		'padding-bottom' => 'body_padding_bottom',
		'padding-left'   => 'body_padding_left',
	),
));

// page popup 
if (isset($settings->popup) && (int) $settings->popup && !BACheetahModel::is_builder_active()) {
	BACheetah::enqueue_layout_styles_scripts_by_id($settings->popup);
}