<?php

/**
 * @class BACheetahProgressBarModule
 */
class BACheetahProgressCircleModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Radial Progress', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Animated', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'progress-circle.svg',
		));

		$this->add_js('js-increments');
	}

	public function get_view_box()
	{
		$size = 400;

		if ($this->settings->circle_shadow) {
			try {
				$axis_px = max([
					abs($this->settings->circle_shadow['horizontal'] ?: 0),
					abs($this->settings->circle_shadow['vertical'] ?: 0),
				]) * 2;

				$blur_px = ($this->settings->circle_shadow['blur'] ?: 0) * 4;
				$size +=  ($axis_px + $blur_px);

			} catch (\Throwable $th) {
				$size = 400;
			}
		}

		return "0 0 $size $size";

	}
}
/**
 * Register the module and its form settings.
 */
BACheetah::register_module(
	'BACheetahProgressCircleModule',
	array(
		'settings' => array(
			'title' => __('Settings', 'ba-cheetah'),
			'sections' => array(
				'progress' => array(
					'title' => __('Bar', 'ba-cheetah'),
					'fields' => BACheetahCounterBase::get_settings_fields()
				),
			)
		),
		'style' => array(
			'title' => __('Style', 'ba-cheetah'),
			'sections' => array(
				'sizes' => array(
					'title' => __('Sizes', 'ba-cheetah'),
					'fields' => array(
						'size' => array(
							'type'  => 'unit',
							'label' => __('Size', 'ba-cheetah'),
							'responsive' => true,
							'default' => '250',
							'slider' => array(
								'min' => 100,
								'max' => 1000,
							),
							'default_unit' => 'px',
							'units' => array('px'),
							'preview' => array(
								'type' => 'css',
								'rules' => array(
									array(
										'selector' => '{node} .progress-circle__svg',
										'property' => 'height',
										'unit'	   => 'px',
									),
									array(
										'selector' => '{node} .progress-circle__svg',
										'property' => 'width',
										'unit'	   => 'px',
									),
								),
							)
						),
						'stroke_width' => array(
							'type'  => 'unit',
							'label' => __('Stroke', 'ba-cheetah'),
							'default' => '45',
							'slider' => true,
						),
					)
				),

				'circle' => array(
					'title' => __('Circle', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'circle_bg' => array(
							'label' => __('Background color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '#fff',
							'show_alpha' => true,
							'show_reset' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress-circle__circle',
								'property'	=> 'fill'
							),
						),
						'circle_shadow' => array(
							'type'        => 'shadow',
							'label'       => __('Shadow', 'ba-cheetah'),
							'show_spread' => true,
							'preview'     => array(
								'type'     => 'css',
								'selector'  => '{node} .progress-circle__circle',
								'property' => 'filter',
							),
						),
					)
				),

				'progress' => array(
					'title' => __( 'Progress', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'circle_stroke' => array(
							'label' => __('Background Color', 'ba-cheetah'),
							'type' => 'color',
							'default' => 'f6f6fc',
							'show_alpha' => true,
							'show_reset' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress-circle__circle',
								'property'	=> 'stroke'
							),
						),
						'stroke_linecap' => array(
							'label' => __('Rounded corners', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'round',
							'options' => array(
								'round' => __('Yes', 'ba-cheetah'),
								'initial' => __('No', 'ba-cheetah'),
							),
						),
						'progress_bg_type' => array(
							'label' => __('Background type', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'gradient',
							'options' => array(
								'solid' => __('Solid', 'ba-cheetah'),
								'gradient' => __('Gradient', 'ba-cheetah'),
							),
							'toggle' => array(
								'solid' => array('fields' => array('progress_bg_color')),
								'gradient' => array('fields' => array('progress_bg_gradient_color_1', 'progress_bg_gradient_color_2')),
							)
						),
						'progress_bg_color' => array(
							'label' => __('Background color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '4054b2',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress-circle__progress',
								'property'	=> 'stroke'
							),
						),
						'progress_bg_gradient_color_1' => array(
							'label' => __('Gradient color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '0080fc',
							'preview'    => array(
								'type'      => 'attribute',
								'selector'  => '{node} linearGradient stop:first-child',
								'attribute'	=> 'stop-color'
							),
						),
						'progress_bg_gradient_color_2' => array(
							'label' => __('Gradient color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '7767d1',
							'preview'    => array(
								'type'      => 'attribute',
								'selector'  => '{node} linearGradient stop:last-child',
								'attribute'	=> 'stop-color'
							),
						),
					)
				),

				'counter_styles' => array(
					'title' => __('Counter', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'number_enabled' => array(
							'label' => __('Enabled', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'yes',
							'options' => array(
								'yes' => __('Yes', 'ba-cheetah'),
								'no' => __('No', 'ba-cheetah'),
							),
							'toggle' => array(
								'yes' => array('fields' => array('number_color', 'number_font', 'number_size'))
							)
						),
						'number_color' => array(
							'label' => __('Color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '3a3a3a',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress-circle__svg text',
								'property'	=> 'fill'
							),
						),
						'number_font'  => array(
							'type'          => 'font',
							'label'         => __( 'Font', 'ba-cheetah' ),
							'default'       => array(
								'family'        => 'Montserrat',
								'weight'        => 600
							),
						),
						'number_size'  => array(
							'type'			=> 'unit',
							'label'			=> __( 'Size', 'ba-cheetah' ),
							'type'			=> 'unit',
							'default'		=> '3',
							'default_unit' 	=> 'em',
							'units'			=> array('em'),
							'slider' 		=> true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .progress-circle__svg text',
								'property'	=> 'font-size'
							),
						),
					)
				)
			)
		)
	)
);
