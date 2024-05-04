<?php

$page_background_settings = array(

	// type

	'bg_type' => array(
		'type'    => 'select',
		'label'   => __('Type', 'ba-cheetah'),
		'default' => 'global',
		'options' => array(
			'theme'     => _x('Theme Background', 'Background type.', 'ba-cheetah'),
			'global'   => _x('Global Background', 'Background type.', 'ba-cheetah'),
			'color'    => _x('Color', 'Background type.', 'ba-cheetah'),
			'gradient' => _x('Gradient', 'Background type.', 'ba-cheetah'),
			'photo'    => _x('Photo', 'Background type.', 'ba-cheetah'),
		),
		'toggle'  => array(
			'color'    => array(
				'fields' => array('bg_color'),
			),
			'gradient' => array(
				'fields' => array('bg_gradient'),
			),
			'photo'    => array(
				'fields' => array('bg_size','bg_attachment','bg_position','bg_repeat','bg_image','bg_color'),
			),
		),
	),

	// color

	'bg_color' => array(
		'type'        => 'color',
		'connections' => array('color'),
		'label'       => __('Color', 'ba-cheetah'),
		'default'	  => 'ffffff',
		'show_reset'  => true,
		'show_alpha'  => true,
		'preview' => array(
			'type' => 'css',
			'selector' => 'body.ba-cheetah',
			'property' => 'background-color',
			'important' => true
		)
	),

	// gradient


	'bg_gradient' => array(
		'type'    => 'gradient',
		'label'   => __('Gradient', 'ba-cheetah'),
		'preview'     => array(
			'type'     => 'refresh',
			'selector' => 'body.ba-cheetah',
			'property' => 'background-image',
		),
	),

	// image

	'bg_image'      => array(
		'type'        => 'photo',
		'show_remove' => true,
		'label'       => __('Photo', 'ba-cheetah'),
		'connections' => array('photo'),
		'preview'     => array(
			'type'     => 'refresh',
			'selector' => 'body.ba-cheetah',
			'property' => 'background-image',
		),
	),
	'bg_repeat'     => array(
		'type'       => 'select',
		'label'      => __('Repeat', 'ba-cheetah'),
		'default'    => 'no-repeat',
		// 'responsive' => true,
		'options'    => array(
			'no-repeat' => _x('None', 'Background repeat.', 'ba-cheetah'),
			'repeat'    => _x('Tile', 'Background repeat.', 'ba-cheetah'),
			'repeat-x'  => _x('Horizontal', 'Background repeat.', 'ba-cheetah'),
			'repeat-y'  => _x('Vertical', 'Background repeat.', 'ba-cheetah'),
		),
		'preview'    => array(
			'type'     => 'refresh',
			'selector' => 'body.ba-cheetah',
			'property' => 'background-repeat',
		),
	),
	'bg_position'   => array(
		'type'       => 'select',
		'label'      => __('Position', 'ba-cheetah'),
		'default'    => 'left top',
		// 'responsive' => true,
		'options'    => array(
			'left top'      => __('Left Top', 'ba-cheetah'),
			'left center'   => __('Left Center', 'ba-cheetah'),
			'left bottom'   => __('Left Bottom', 'ba-cheetah'),
			'right top'     => __('Right Top', 'ba-cheetah'),
			'right center'  => __('Right Center', 'ba-cheetah'),
			'right bottom'  => __('Right Bottom', 'ba-cheetah'),
			'center top'    => __('Center Top', 'ba-cheetah'),
			'center center' => __('Center', 'ba-cheetah'),
			'center bottom' => __('Center Bottom', 'ba-cheetah'),
		),
		'preview'    => array(
			'type'     => 'refresh',
			'selector' => 'body.ba-cheetah',
			'property' => 'background-position',
		),
	),
	'bg_attachment' => array(
		'type'       => 'select',
		'label'      => __('Attachment', 'ba-cheetah'),
		'default'    => 'scroll',
		// 'responsive' => true,
		'options'    => array(
			'scroll' => __('Scroll', 'ba-cheetah'),
			'fixed'  => __('Fixed', 'ba-cheetah'),
		),
		'preview'    => array(
			'type'     => 'refresh',
			'selector' => 'body.ba-cheetah',
			'property' => 'background-attachment',
		),
	),
	'bg_size'       => array(
		'type'       => 'select',
		'label'      => __('Size', 'ba-cheetah'),
		'default'    => 'cover',
		// 'responsive' => true,
		'options'    => array(
			'auto'    => _x('None', 'Background scale.', 'ba-cheetah'),
			'contain' => __('Fit', 'ba-cheetah'),
			'cover'   => __('Fill', 'ba-cheetah'),
		),
		'preview'    => array(
			'type'     => 'refresh',
			'selector' => 'body.ba-cheetah',
			'property' => 'background-size',
		),
	),	
);
