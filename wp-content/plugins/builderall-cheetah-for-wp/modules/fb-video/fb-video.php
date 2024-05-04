<?php

/**
 * @class BACheetahFbVideoModule
 */
class BACheetahFbVideoModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Facebook Videos', 'ba-cheetah' ),
			'description'     => __( 'Facebook Videos Integration', 'ba-cheetah' ),
			'category'        => __( 'Social', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'fb-video.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahFbVideoModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
                    'video_url'       => array(
                        'type'          => 'text',
                        'label'         => 'URL',
                        'default'       => 'https://www.facebook.com/builderall.english/videos/594739244922145'
                    ),
                    'video_width' => array(
                        'type'     => 'unit',
                        'label'    => __( 'Width', 'ba-cheetah' ),
                        'default'  => '500',
                        'sanitize' => 'absint',
                        'slider'   => array(
                            'step' => 220,
                            'max'  => 1000,
                        ),
                    ),
                    'video_allowfullscreen' => array(
                        'label'       => __('Allow Fullscreen', 'ba-cheetah'),
                        'type'        => 'button-group',
                        'default'     => 'false',
                        'options'     => array(
                            'true'    => __('Yes', 'ba-cheetah'),
                            'false'   => __('No', 'ba-cheetah'),
                        ),
                    ),
                    'video_show_text' => array(
                        'label'       => __('Include Full Publication', 'ba-cheetah'),
                        'type'        => 'button-group',
                        'default'     => 'false',
                        'options'     => array(
                            'true'    => __('Yes', 'ba-cheetah'),
                            'false'   => __('No', 'ba-cheetah'),
                        ),
                    ),
                    'video_show_captions' => array(
                        'label'       => __('Include Captions (if possible)', 'ba-cheetah'),
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
