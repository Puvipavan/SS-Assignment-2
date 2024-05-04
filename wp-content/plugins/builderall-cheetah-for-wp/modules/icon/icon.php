<?php

/**
 * @class BACheetahIconModule
 */
class BACheetahIconModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	const SHAPES = array(
		'rounded'	=> '0.4em',
		'square'	=> '0',
		'circle'	=> '50%',
		'custom'	=> 'initial',
		'none' 		=> 'initial'
	);

	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Icon', 'ba-cheetah' ),
			'description'     => __( 'Display an icon and optional title.', 'ba-cheetah' ),
			'category'        => __('Media', 'ba-cheetah'),
			'editor_export'   => false,
			'partial_refresh' => true,
			'icon'            => 'star-filled.svg',
		));
	}

	public function shape_to_unit() {
		return self::SHAPES[$this->settings->icon_shape];
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahIconModule', array(
	'general' => array(
		'title'    => __( 'General', 'ba-cheetah' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'icon'    => array(
						'type'    => 'icon',
						'label'   => __( 'Icon', 'ba-cheetah' ),
						'default' => 'fas fa-star'
					),
					'link'    => array(
						'type'          => 'link',
						'label'         => __( 'Link', 'ba-cheetah' ),
						'show_target'   => true,
					),
				),
			),
		),
	),
	'style'   => array(
		'title'    => __( 'Style', 'ba-cheetah' ),
		'sections' => array(
			'content' => array(
				'title' => __('Layout', 'ba-cheetah'),
				'fields' => array(
					'alignment' => array(
						'type' => 'align',
						'label' => __('Alignment', 'ba-cheetah'),
						'responsive' => true,
						'values' => array(
							'left' => 'flex-start',
							'center' => 'center',
							'right' => 'flex-end'
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__icon',
							'property'	=> 'justify-content'
						),
					),
					
					'icon_shape' => array(
						'label' => __('Shape', 'ba-cheetah'),
						'type' => 'select',
						'default' => 'none',
						'options' => array(
							'none' => __('None', 'ba-cheetah'),
							'rounded' => __('Rounded', 'ba-cheetah'),
							'square' => __('Square', 'ba-cheetah'),
							'circle' => __('Circle', 'ba-cheetah'),
						),
						'toggle' => array(
							'rounded' => array('fields' => array('background', 'background_hover', 'border', 'padding')),
							'square' => array('fields' => array('background', 'background_hover', 'border', 'padding')),
							'circle' => array('fields' => array('background', 'background_hover', 'border', 'padding')),
							'custom' => array('fields' => array('background', 'background_hover', 'border', 'padding')),
						)
					),
					'border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__icon .icon__wrapper',
						),
					),
					
					'size' => array(
						'type' => 'unit',
						'label' => __('Size', 'ba-cheetah'),
						'default' => '45',
						'units' => array('px'),
						'default_unit' => 'px',
						'responsive' => true,
						'slider' => array(
							'min' => 0,
							'max' => 300,
							'step' => 1,
						),
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .ba-module__icon .icon__wrapper',
							'property' => 'font-size',
						),
					),
					
					'padding' => array(
						'type' => 'unit',
						'label' => __('Padding', 'ba-cheetah'),
						'help' => __('The space between the icon and the box border', 'ba-cheetah'),
						'default' => '',
						'units' => array('em', 'px'),
						'default_unit' => 'px',
						'responsive' => true,
						'slider' => true,
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .ba-module__icon .icon__wrapper',
							'property' => 'padding',
						),
					),
				)
			),
			'colors' => array(
				'title' => __('Colors', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'background' => array(
						'type' 		=> 'color',
						'label'		=> __('Background', 'ba-cheetah'),
						'default'	=> 'F4F5F0',
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .icon__wrapper',
							'property' => 'background-color'
						)
					),
					'color' => array(
						'type' 		=> 'color',
						'label'		=> __('Color', 'ba-cheetah'),
						'default'	=> 'FFCC33',
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .icon__wrapper',
							'property' => 'color'
						)
					),
					'background_hover' => array(
						'type' 		=> 'color',
						'label'		=> __('Background on hover', 'ba-cheetah'),
						'default'	=> '',
						'preview' => 'none'
					),
					'color_hover' => array(
						'type' 		=> 'color',
						'label'		=> __('Color on hover', 'ba-cheetah'),
						'default'	=> '',
						'preview' => 'none'
					),
				)
			),
		),
	),
));
