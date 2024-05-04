<?php

/**
 * @class BACheetahProgressBarModule
 */
class BACheetahProgressBarModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Progress Bar', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Animated', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'progress-bar.svg',
		));

		$this->add_js('js-increments');
	}

	public function counter_template() {
		$template = '<span class="progress__bar__counter">';
		
		if ($this->settings->prefix_text && $this->settings->counter_type == 'value')
		$template .= sprintf('<span class="counter__pre">%s</span>', $this->settings->prefix_text);

		// counter goes here
		$template .= '<span class="counter__increment">0</span>';

		if ($this->settings->sufix_text && $this->settings->counter_type == 'value')
		$template .= sprintf('<span class="counter__pos">%s</span>', $this->settings->sufix_text);

		$template .= '</span>';
		
		echo $template;
	}
}
/**
 * Register the module and its form settings.
 */
BACheetah::register_module(
	'BACheetahProgressBarModule',
	array(
		'settings' => array(
			'title' => __('Settings', 'ba-cheetah'),
			'sections' => array(
				'progress' => array(
					'title' => __('Bar', 'ba-cheetah'),
					'fields' => BACheetahCounterBase::get_settings_fields()
				),
				'counter' => array(
					'title' => __('Content', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'inner_text' => array(
							'type' => 'text',
							'label' => __('Internal text', 'ba-cheetah'),
							'default' => 'Your Title',
							'preview' => array(
								'type' => 'text',
								'selector' => '{node} .progress__bar__text',
							)
						),
						'show_counter' => array(
							'label' => __('Display counter', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'yes',
							'options' => array(
								'yes' => __('Yes', 'ba-cheetah'),
								'no' => __('No', 'ba-cheetah'),
							),
							'toggle' => array(
								'yes' => array(
									'fields' => array('counter_position', 'counter_type', 'number_color', 'number_typography')
								)
							)
						),
						'counter_position' => array(
							'type' => 'select',
							'label' => __('Counter position', 'ba-cheetah'),
							'default' => 'bar',
							'options' => array(
								'column' => 'Top',
								'column-reverse' => 'Bottom',
								'row' => 'Left',
								'row-reverse' => 'Right',
								'bar' => 'Bar'
							),
							/*
							'preview' => array(
								'type'      => 'css',
								'selector'  => '{node} .ba-module__progress',
								'property'	=> 'flex-direction'
							),
							*/
						),
						'counter_type' => array(
							'type' => 'button-group',
							'label' => 'Counter type',
							'default' => 'percentage',
							'help' => __('Whether you want to display the number in percentage or the real number. For example: a progress bar that goes from 0 to 30. When you get halfway, do you want to display 15 or 50%?', 'ba-cheetah'),
							'options' => array(
								'percentage' => __('Percentage', 'ba-cheetah'),
								'value' => __('Value', 'ba-cheetah'),
							),
							'toggle' => array(
								'value' => array(
									'fields' => array(
										'max_value', 'prefix_text', 'sufix_text'
									)
								)
							)
						),
						'max_value' => array(
							'type'  => 'unit',
							'label' => __('Max value', 'ba-cheetah'),
							'default' => '100',
							'help' => __('Starting with the example in the field above. Your progress bar goes from 0 to 15 with a limit of 30. So set from = 0, to = 15 and this field = 30', 'ba-cheetah'),
						),
						'prefix_text' => array(
							'type' => 'text',
							'label' => __('Prefix'),
							'default' => '',
							'preview' => array(
								'type' => 'text',
								'selector' => '{node} .counter__pre'
							)
						),
						'sufix_text' => array(
							'type' => 'text',
							'label' => __('Sufix'),
							'default' => '',
							'preview' => array(
								'type' => 'text',
								'selector' => '{node} .counter__pos'
							)
						)
					)
				)
			)
		),
		'style' => array(
			'title' => __('Style', 'ba-cheetah'),
			'sections' => array(
				'bar' => array(
					'title' => __( 'Bar', 'ba-cheetah'),
					'fields' => array(
						'bar_height' => array(
							'type'  => 'unit',
							'label' => __('Height', 'ba-cheetah'),
							'default_unit' => 'px',
							'default' => '40',
							'responsive' => true,
							'units' => array('px'),
							'slider' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress__bar__outer',
								'property'	=> 'height'
							),
						),
						'bar_border'  => array(
							'type' => 'border',
							'label' => __('Border', 'ba-cheetah'),
							'responsive' => true,
							'preview' => array(
								'type' => 'css',
								'selector' => '{node} .progress__bar__outer',
							),
							'default' => array(
								'radius' => array(
									'top_left' => '33',
									'top_right' => '33',
									'bottom_left' => '33',
									'bottom_right' => '33',
								),
								'shadow' => array(
									'color' => '9e8ada',
									'horizontal' => '0',
									'vertical' => '1',
									'blur' => '4',
									'spread' =>	'0'
								)
							),
						),
						'bar_outer_background' => array(
							'label' => __('Background color', 'ba-cheetah'),
							'type' => 'color',
							'default' => 'rgba(158,138,218,0.45)',
							'show_alpha' => true,
							'show_reset' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress__bar__outer',
								'property'	=> 'background-color'
							),
						),
						'bar_inner_bg_type' => array(
							'label' => __('Bar Background type', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'gradient',
							'options' => array(
								'solid' => __('Solid', 'ba-cheetah'),
								'gradient' => __('Gradient', 'ba-cheetah'),
								'photo' => __('Photo', 'ba-cheetah'),
							),
							'toggle' => array(
								'solid' => array('fields' => array('bar_inner_bg')),
								'gradient' => array('fields' => array('bar_inner_bg_gradient')),
								'photo' => array('fields' => array('bar_inner_bg'), 'sections' => array('inner_bg_photo')),
							)
						),
						'bar_inner_bg' => array(
							'label' => __('Bar background color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '4054b2',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress__bar__inner',
								'property'	=> 'background-color'
							),
						),
						'bar_inner_bg_gradient' => array(
							'type'    => 'gradient',
							'label' => __('Bar gradient Background', 'ba-cheetah'),
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '{node} .progress__bar__inner',
								'property'  => 'background-image',
							),
							'default' => array(
								'type' => 'linear',
								'angle' => '90',
								'position' => 'center center',
								'colors' => array('2f96ef', '9255c5'),
								'stops' => array('0', '100')
							)
						),						
					)
				),
				'inner_bg_photo' => array(
					'title'  => __( 'Background Photo', 'ba-cheetah' ),
					'fields' => array(
						'inner_bg_image'        => array(
							'type'        => 'photo',
							'show_remove' => true,
							'label'       => __( 'Photo', 'ba-cheetah' ),
							'responsive'  => true,
							'connections' => array( 'photo' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '{node} .progress__bar__inner',
								'property' => 'background-image',
							),
						),
						'inner_bg_repeat'       => array(
							'type'       => 'select',
							'label'      => __( 'Repeat', 'ba-cheetah' ),
							'default'    => 'repeat-x',
							'responsive' => true,
							'options'    => array(
								'no-repeat' => _x( 'None', 'Background repeat.', 'ba-cheetah' ),
								'repeat'    => _x( 'Tile', 'Background repeat.', 'ba-cheetah' ),
								'repeat-x'  => _x( 'Horizontal', 'Background repeat.', 'ba-cheetah' ),
								'repeat-y'  => _x( 'Vertical', 'Background repeat.', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .progress__bar__inner',
								'property' => 'background-repeat',
							),
						),
						'inner_bg_position'     => array(
							'type'       => 'select',
							'label'      => __( 'Position', 'ba-cheetah' ),
							'default'    => 'right center',
							'responsive' => true,
							'options'    => array(
								'left top'      => __( 'Left Top', 'ba-cheetah' ),
								'left center'   => __( 'Left Center', 'ba-cheetah' ),
								'left bottom'   => __( 'Left Bottom', 'ba-cheetah' ),
								'right top'     => __( 'Right Top', 'ba-cheetah' ),
								'right center'  => __( 'Right Center', 'ba-cheetah' ),
								'right bottom'  => __( 'Right Bottom', 'ba-cheetah' ),
								'center top'    => __( 'Center Top', 'ba-cheetah' ),
								'center center' => __( 'Center', 'ba-cheetah' ),
								'center bottom' => __( 'Center Bottom', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .progress__bar__inner',
								'property' => 'background-position',
							),
						),
						'inner_bg_size'         => array(
							'type'       => 'select',
							'label'      => __( 'Scale', 'ba-cheetah' ),
							'default'    => 'contain',
							'responsive' => true,
							'options'    => array(
								'auto'    => _x( 'None', 'Background scale.', 'ba-cheetah' ),
								'contain' => __( 'Fit', 'ba-cheetah' ),
								'cover'   => __( 'Fill', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .progress__bar__inner',
								'property' => 'background-size',
							),
						),
					),
				),
				'counter_styles' => array(
					'title' => __('Content', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'number_color' => array(
							'label' => __('Counter color', 'ba-cheetah'),
							'type' => 'color',
							'default' => 'f8f0fe',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress__bar__counter',
								'property'	=> 'color'
							),
						),
						'number_typography'  => array(
							'type'       => 'typography',
							'label'      => __('Counter typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress__bar__counter',
							),
							'default' => array(
								'font_family' => 'Montserrat',
								'font_weight' => '500',
								'text_align' => 'right',
								'font_size' => array(
									'length' => '1',
									'unit' => 'em',
								),
								'line_height' => array(
									'length' => '1',
									'unit' => '',
								),
							)
						),
						'internal_text_color' => array(
							'label' => __('Internal text color', 'ba-cheetah'),
							'type' => 'color',
							'default' => 'efffff',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress__bar__inner .progress__bar__text',
								'property'	=> 'color'
							),
						),
						'internal_text_typography'  => array(
							'type'       => 'typography',
							'label'      => __('Internal text typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress__bar__inner .progress__bar__text',
							),
							'default' => array(
								'font_family' => 'Montserrat',
								'font_weight' => '500',
								'font_size' => array(
									'length' => '17',
									'unit' => 'px',
								),
								'line_height' => array(
									'length' => '1',
									'unit' => '',
								),
							)
						),
					)
				)
			)
		)
	)
);
