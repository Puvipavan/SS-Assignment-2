<?php

/**
 * @class BACheetahFbButtonModule
 */
class BACheetahFbButtonModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Facebook Button', 'ba-cheetah' ),
			'description'     => __( 'Facebook Button Integration', 'ba-cheetah' ),
			'category'        => __( 'Social', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'fb-button.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahFbButtonModule', array(
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
                    'btn_type' => array(
                        'label'       => __('Type', 'ba-cheetah'),
                        'type' => 'button-group',
                        'default' => 'like',
                        'options' => array(
                            'like'      => __('Like', 'ba-cheetah'),
                            'recommend' => __('Recommend', 'ba-cheetah'),
                        ),
                    ),
                    'btn_layout' => array(
                        'label'       => __('Layout', 'ba-cheetah'),
                        'type' => 'button-group',
                        'default' => 'button',
                        'options' => array(
                            'button'       => __('Button', 'ba-cheetah'),
                            'standard'     => __('Standard', 'ba-cheetah'),
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
                    'btn_share' => array(
                        'label'       => __('Include Share Button', 'ba-cheetah'),
                        'type' => 'button-group',
                        'default' => 'false',
                        'options' => array(
                            'true'   => __('Yes', 'ba-cheetah'),
                            'false'  => __('No', 'ba-cheetah'),
                        ),
                    ),
				),
			),
		),
	),
));
