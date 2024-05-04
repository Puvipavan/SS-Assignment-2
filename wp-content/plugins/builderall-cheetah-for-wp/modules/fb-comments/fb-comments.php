<?php

/**
 * @class BACheetahFbCommentModule
 */
class BACheetahFbCommentModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Facebook Comments', 'ba-cheetah' ),
			'description'     => __( 'Facebook Comments Integration', 'ba-cheetah' ),
			'category'        => __( 'Social', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'fb-comments.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahFbCommentModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
                    'comment_url'       => array(
                        'type'          => 'text',
                        'label'         => 'URL to comment',
                        'default'       => 'https://www.facebook.com/builderall.english'
                    ),
                    'comment_count' => array(
                        'type'     => 'unit',
                        'label'    => __( 'Amount of publications', 'ba-cheetah' ),
                        'default'  => '10',
                        'sanitize' => 'absint',
                        'slider'   => array(
                            'step' => 1,
                            'max'  => 20,
                        ),
                    ),
                    'comment_width' => array(
                        'type'     => 'unit',
                        'label'    => __( 'Width', 'ba-cheetah' ),
                        'default'  => '500',
                        'sanitize' => 'absint',
                        'slider'   => array(
                            'step' => 320,
                            'max'  => 1000,
                        ),
                    ),
				),
			),
		),
	),
));
