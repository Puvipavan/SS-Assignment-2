<?php

/**
 * @class BACheetahFbShareModule
 */
class BACheetahFbShareModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Facebook Share Button', 'ba-cheetah' ),
			'description'     => __( 'Facebook Share Button Integration', 'ba-cheetah' ),
			'category'        => __( 'Social', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'fb-share.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahFbShareModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
                    'btn_url'         => array(
                        'type'          => 'text',
                        'label'         => 'URL',
                        'default'       => 'https://www.facebook.com/builderall.english'
                    ),
                    'btn_layout' => array(
                        'label'       => __('Layout', 'ba-cheetah'),
                        'type' => 'button-group',
                        'default' => 'button',
                        'options' => array(
                            'button'       => __('Button', 'ba-cheetah'),
                            'box_count'    => __('Box Count', 'ba-cheetah'),
                            'button_count' => __('Button Count', 'ba-cheetah'),
                        ),
                    ),
                    'btn_size' => array(
                        'label'       => __('Size', 'ba-cheetah'),
                        'type' => 'button-group',
                        'default' => 'small',
                        'options' => array(
                            'small' => __('Small', 'ba-cheetah'),
                            'large' => __('Large', 'ba-cheetah'),
                        ),
                    ),
				),
			),
		),
	),
));
