<?php

/**
 * @class BACheetahSpacingModule
 */
class BACheetahSpacingModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Spacing', 'ba-cheetah' ),
			'description'     => '',
			'category'        => __('Basic', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'spacing.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahSpacingModule', array(
	'general' => array(
		'title'    => __('Style', 'ba-cheetah'),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'spacing' => array(
						'type'		=> 'unit',
						'label'		=> __('Spacing', 'ba-cheetah'),
						'units'		=> array('vh', 'px', 'em', 'rem'),
						'default'	=> '50',
						'default_unit' => 'px',
						'responsive' => true,
						'slider'	=> array(
							'vh' => array(
								'min' => 0,
								'max' => 100,
								'step' => 1,
							),
							'px' => array(
								'min' => 0,
								'max' => 650,
								'step' => 1,
							),
							'em' => array(
								'min' => 0,
								'max' => 50,
								'step' => 0.5,
							),
							'rem' => array(
								'min' => 0,
								'max' => 100,
								'step' => 1,
							),
						),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '{node}',
							'property' => 'height'
						),
					),
				),
			),
		),
	),
));
