<?php

/**
 * @class BACheetahLineModule
 */
class BACheetahLineModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Separator', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Basic', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'line.svg',
		));
	}

	public function has_content() 
	{
		return $this->settings->icon || $this->settings->content_text;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module(
	'BACheetahLineModule',
	array(
		'border'      => array(
			'title' => __('Border', 'ba-cheetah'),
			'sections' => array(
				'border_settings' => array(
					'title' => '',
					'fields' => array(
						'border_height' => array(
							'type' => 'unit',
							'label' =>  __('Weight', 'ba-cheetah'),
							'default' => 2,
							'units' => array('px', 'em'),
							'default_unit' => 'px',
							'slider' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
							'preview'    => array(
								'type' => 'css',
								'selector' => '.ba-module__line .line__wrapper .line__border',
								'property' => 'border-top-width',
							),
						),
						'border_width' => array(
							'type' => 'unit',
							'label' =>  __('Width', 'ba-cheetah'),
							'responsive' => array(
								'default' => array(
									'default'    =>  100,
									'medium'     =>  100,
									'responsive' =>  100,
								)
							),
							'units' => array('px', '%'),
							'default_unit' => '%',
							'slider' => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
								'%' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								)
							),
							'preview'    => array(
								'type' => 'css',
								'selector' => '.ba-module__line .line__wrapper',
								'property' => 'width',
							),
						),
						'border_justify' => array(
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
								'selector'  => '.ba-module__line',
								'property'	=> 'justify-content'
							),
							'default' => 'center'
						),
					)
				),
				'border_styles_section' => array(
					'title' => __('Boder Style', 'ba-cheetah'),
					'fields' => array(
						'border_color' => array(
							'type'          => 'color',
							'label'         => __('Color', 'ba-cheetah'),
							'default'       => '4054b2',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__line .line__wrapper .line__border',
								'property'	=> 'border-top-color'
							),
						),
						'border_style' => array(
							'type' => 'select',
							'label' =>  __('Border style', 'ba-cheetah'),
							'default' => 'solid',
							'options' => array(
								'solid' => __('Solid', 'ba-cheetah'),
								'dashed' => __('Dashed', 'ba-cheetah'),
								'dotted' => __('Dotted', 'ba-cheetah'),
								'double' => __('Double', 'ba-cheetah'),
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__line .line__wrapper .line__border',
								'property'	=> 'border-top-style'
							),
						),
						'border_shadow' => array(
							'type'        => 'shadow',
							'label'       =>  __('Shadow', 'ba-cheetah'),
							'show_spread' => true,
							'preview'     => array(
								'type'     => 'css',
								'selector' => '.ba-module__line .line__wrapper .line__border',
								'property' => 'box-shadow',
							),
						),
						'border_radius' => array(
							'type' => 'unit',
							'label' =>  __('Radius', 'ba-cheetah'),
							'default' => 0,
							'units' => array('px'),
							'default_unit' => 'px',
							'slider' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
							'preview'    => array(
								'type' => 'css',
								'selector' => '.ba-module__line .line__wrapper .line__border',
								'property' => 'border-radius',
							),
						),
					)
				)
			),
		),
		'content'      => array(
			'title' => __('Content', 'ba-cheetah'),
			'sections' => array(
				'line_content_icon' => array(
					'title' =>  __('Icon', 'ba-cheetah'),
					'fields' => array(
						'icon' => array(
							'type'          => 'icon',
							'label'         => __('Icon', 'ba-cheetah'),
							'show_remove'   => true,
							'default' => ''
						),
						'icon_size' => array(
							'type' => 'unit',
							'label' =>  __('Size', 'ba-cheetah'),
							'default' => 18,
							'units' => array('px'),
							'default_unit' => 'px',
							'responsive' => true,
							'slider' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
							'preview'    => array(
								'type' => 'css',
								'selector' => '.ba-module__line .line__wrapper .line__content i',
								'property' => 'font-size',
							),
						),
					),
				),

				'line_content_text' => array(
					'title' => __('Text', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'content_text' => array(
							'type'          => 'text',
							'label'         => __('Text', 'ba-cheetah'),
						),
						'content_typography' => array(
							'type'       => 'typography',
							'label'      => __('Typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__line .line__wrapper .line__content',
							),
						),
					)
				),
				
				'line_content_styles' => array(
					'title' => __('Content Styles', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'content_justify' => array(
							'type' => 'align',
							'label' => __('Content Alignment', 'ba-cheetah'),
							'values' => array(
								'left' => 'row-reverse',
								'center' => 'initial',
								'right' => 'row'
							),
							'default' => 'initial'
						),
						'content_color' => array(
							'type'          => 'color',
							'label'         => __('Color', 'ba-cheetah'),
							'default'       => '0080fc',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__line .line__wrapper .line__content',
								'property'	=> 'color'
							),
						),
						'content_background' => array(
							'type'          => 'color',
							'label'         => __('Background', 'ba-cheetah'),
							'default'       => '',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__line .line__wrapper .line__content',
								'property'	=> 'background-color'
							),
						),
						'content_padding' => array(
							'type'        => 'dimension',
							'label'       => __('Padding', 'ba-cheetah'),
							'default' => 10,
							'units' => array('px'),
							'default_unit' => 'px',
							'slider' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
						),
						'content_border'  => array(
							'type'       => 'border',
							'label'      => __('Content Border', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__line .line__wrapper .line__content',
							),
						),
					)
				)
			)
		),
	)
);
