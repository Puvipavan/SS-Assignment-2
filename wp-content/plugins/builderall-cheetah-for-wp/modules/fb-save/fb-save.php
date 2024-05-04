<?php

/**
 * @class BACheetahFbSaveModule
 */
class BACheetahFbSaveModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Facebook Save', 'ba-cheetah' ),
			'description'     => __( 'Facebook Save Integration', 'ba-cheetah' ),
			'category'        => __( 'Social', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'fb-save.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahFbSaveModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
                    'save_url'       => array(
                        'type'          => 'text',
                        'label'         => 'URL to Save',
                        'default'       => 'https://www.facebook.com/builderall.english'
                    ),
                    'save_size' => array(
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
