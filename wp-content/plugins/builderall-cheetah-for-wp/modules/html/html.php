<?php

/**
 * @class BACheetahHtmlModule
 */
class BACheetahHtmlModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'HTML', 'ba-cheetah' ),
			'description'     => __( 'Display raw HTML code.', 'ba-cheetah' ),
			'category'        => __('Basic', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'editor-code.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahHtmlModule', array(
	'general' => array(
		'title'    => __( 'General', 'ba-cheetah' ),
		'sections' => array(
			'general' => array(
				'title'  => __('Embedded Code', 'ba-cheetah'),
				'fields' => array(
					'html' => array(
						'type'        => 'code',
						'editor'      => 'html',
						'label'       => '',
						'rows'        => '18',
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.ba-cheetah-html',
						),
					),
				),
			),
		),
	),
));
