<?php

/**
 * @class BACheetahAnimatedHeadlineModule
 */
class BACheetahAnimatedHeadlineModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Animated Headline', 'ba-cheetah' ),
			'description'     => '',
			'category'        => __( 'Animated', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'animated-headline.svg',
		));
	}
}


/**
 * Subform animated headline
 */
BACheetah::register_settings_form('animated-headline__item__texts', array(
    'title' => __('Texts', 'ba-cheetah'),
    'tabs'  => array(
        'general'      => array(
            'title'         => '',
            'sections'      => array(
                'general'       => array(
                    'title'         => '',
                    'fields'        => array(
                        'text' => array(
                            'type'  => 'text',
                            'label' => __('Text', 'ba-cheetah'),
                        ),
                    )
                ),
            )
        )
    )
));

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahAnimatedHeadlineModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
                    "text_items" => array(
                        'type'          => 'form',
                        'label'         => __('Texts', 'ba-cheetah'),
                        'form'          => 'animated-headline__item__texts',
                        'preview_text'  => 'text',
                        'multiple'      => true,
                        'limit'         => 15,
                        'default' => array(
                            array('text' => 'Lorem ipsum dolor sit amet'),
                            array('text' => 'Lorem ipsum dolor sit amet'),
                            array('text' => 'Lorem ipsum dolor sit amet'),
                        )
                    ),
                    'effect_in' => array(
                        'label' => __('Effect In', 'ba-cheetah'),
                        'type' => 'select',
                        'default' => 'none',
                        'options' => array(
                            'rotateYIn'		            => __('Vertical Rotation','ba-cheetah'),
                            'rotateXIn'		            => __('Horizontal Rotation','ba-cheetah'),
                            'slide-in'		            => __('Vertical Slide','ba-cheetah'),
                            'slide-zoom-in'	            => __('Horizontal Slide','ba-cheetah'),
                            'fadeInRotate'			    => __('Fade In Spinning','ba-cheetah'),
                            'slide-in-right-to-left'    => __('Slide Right to Left','ba-cheetah'),
                            'rubberBand-in'             => __('Rubber Band','ba-cheetah'),
                            'flip-in'		            => __('Flip','ba-cheetah'),
                            'roll-in'		            => __('Twist','ba-cheetah'),
                            'rolling-in'	            => __('Roll to Left','ba-cheetah'),
                        ),
                    ),
                    'effect_out' => array(
                        'label' => __('Effect Out', 'ba-cheetah'),
                        'type' => 'select',
                        'default' => 'none',
                        'options' => array(
                            'rotateYOut'	    => __('Vertical Rotation','ba-cheetah'),
                            'rotateXOut'	    => __('Horizontal Rotation','ba-cheetah'),
                            'slide-out'		    => __('Vertical Slide','ba-cheetah'),
                            'slide-zoom-out'    => __('Horizontal Slide','ba-cheetah'),
                            'fadeOutRotate'	    => __('Fade Out Spinning','ba-cheetah'),
                            'slideLeftFast-out' => __('Slide Fast to Left','ba-cheetah'),
                            'hinge'			    => __('Hinge','ba-cheetah'),
                            'zoomOutUp'		    => __('Zoom Out Up','ba-cheetah'),
                        ),
                    ),
                    'loop'         => array(
                        'type'    => 'select',
                        'label'   => __( 'Loop', 'ba-cheetah' ),
                        'default' => '1',
                        'options' => array(
                            '0' => __( 'No', 'ba-cheetah' ),
                            '1' => __( 'Yes', 'ba-cheetah' ),
                        ),
                    ),
                    'duration'  => array(
                        'type'     => 'unit',
                        'label'    => __( 'Duration', 'ba-cheetah' ),
                        'default'  => '2',
                        'sanitize' => 'absint',
                        'slider'   => array(
                            'step' => 1,
                            'max'  => 20,
                        ),
                    ),
                    'delay'  => array(
                        'type'     => 'unit',
                        'label'    => __( 'Delay', 'ba-cheetah' ),
                        'default'  => '1',
                        'sanitize' => 'absint',
                        'slider'   => array(
                            'step' => 1,
                            'max'  => 10,
                        ),
                    ),
				),
			),
		),
	),
	'style'   => array( // Tab
		'title'    => __( 'Style', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'style' => array( // Section
				'title'  => __( 'Typography', 'ba-cheetah' ),
				'fields' => array( // Section Fields
					'typography' => array(
						'type'       => 'typography',
						'label'      => '',
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__animated-headline *',
						),
					),
                    'color'      => array(
                        'type'        => 'color',
                        'connections' => array( 'color' ),
                        'label'       => __( 'Color', 'ba-cheetah' ),
                        'show_reset'  => true,
                        'default'     => '000',
                        'show_alpha'  => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__animated-headline *',
							'property' => 'color',
							'important'=> true
						),
                    ),
				),
			),
		),
	),
));
