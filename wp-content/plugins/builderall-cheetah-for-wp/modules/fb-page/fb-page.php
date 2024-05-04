<?php

/**
 * @class BACheetahFbPageModule
 */
class BACheetahFbPageModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Facebook Page', 'ba-cheetah' ),
			'description'     => __( 'Facebook Pages Integration', 'ba-cheetah' ),
			'category'        => __( 'Social', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'fb-page.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahFbPageModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
                    'page_url'       => array(
                        'type'          => 'text',
                        'label'         => 'URL',
                        'default'       => 'https://www.facebook.com/builderall.english'
                    ),
                    'page_width' => array(
                        'type'     => 'unit',
                        'label'    => __( 'Width', 'ba-cheetah' ),
                        'default'  => '500',
                        'sanitize' => 'absint',
                        'slider'   => array(
                            'step' => 220,
                            'max'  => 1000,
                        ),
                    ),
                    'page_height' => array(
                        'type'     => 'unit',
                        'label'    => __( 'Height', 'ba-cheetah' ),
                        'default'  => '500',
                        'sanitize' => 'absint',
                        'slider'   => array(
                            'step' => 220,
                            'max'  => 1000,
                        ),
                    ),
                    'page_show_events' => array(
                        'label'       => __('Show Tab Events', 'ba-cheetah'),
                        'type'        => 'button-group',
                        'default'     => 'false',
                        'options'     => array(
                            'true'    => __('Yes', 'ba-cheetah'),
                            'false'   => __('No', 'ba-cheetah'),
                        ),
                    ),
                    'page_show_message' => array(
                        'label'       => __('Show Tab Messages', 'ba-cheetah'),
                        'type'        => 'button-group',
                        'default'     => 'false',
                        'options'     => array(
                            'true'    => __('Yes', 'ba-cheetah'),
                            'false'   => __('No', 'ba-cheetah'),
                        ),
                    ),
                    'page_small_header' => array(
                        'label'       => __('Use small header', 'ba-cheetah'),
                        'type'        => 'button-group',
                        'default'     => 'false',
                        'options'     => array(
                            'true'    => __('Yes', 'ba-cheetah'),
                            'false'   => __('No', 'ba-cheetah'),
                        ),
                    ),
                    'page_hide_cover' => array(
                        'label'       => __('Hide cover photo', 'ba-cheetah'),
                        'type'        => 'button-group',
                        'default'     => 'false',
                        'options'     => array(
                            'true'    => __('Yes', 'ba-cheetah'),
                            'false'   => __('No', 'ba-cheetah'),
                        ),
                    ),
                    'page_adapt' => array(
                        'label'       => __('Adapt to plugin container width', 'ba-cheetah'),
                        'type'        => 'button-group',
                        'default'     => 'false',
                        'options'     => array(
                            'true'    => __('Yes', 'ba-cheetah'),
                            'false'   => __('No', 'ba-cheetah'),
                        ),
                    ),
                    'page_show_facepile' => array(
                        'label'       => __('Show friends faces', 'ba-cheetah'),
                        'type'        => 'button-group',
                        'default'     => 'false',
                        'options'     => array(
                            'true'    => __('Yes', 'ba-cheetah'),
                            'false'   => __('No', 'ba-cheetah'),
                        ),
                    ),

				),
			),
		),
	),
));
