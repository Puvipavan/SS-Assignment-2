<?php

include BA_CHEETAH_DIR . 'classes/class-ba-cheetah-counter-base.php';

/**
 * @class BACheetahCounterModule
 */
class BACheetahCounterModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Counter', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Animated', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'counter.svg',
		));

		$this->add_js('js-increments');
	}
}
/**
 * Register the module and its form settings.
 */
BACheetah::register_module(
	'BACheetahCounterModule',
	array(
		'settings' => array(
			'title' => __('Settings', 'ba-cheetah'),
			'sections' => array(
				'counter' => array(
					'title' => __('Counter', 'ba-cheetah'),
					'fields' => BACheetahCounterBase::get_settings_fields()
				),
				'texts' => array(
					'title' => __('Appends', 'ba-cheetah'),
					'fields' => array(
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
							'default' => '%',
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
				'numbers' => array(
					'title' => '',
					'fields' => array(
						'number_color' => array(
							'label' => __('Text color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '4054b2',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .counter__content',
								'property'	=> 'color'
							),
						),
						'number_typography'  => array(
							'type'       => 'typography',
							'label'      => __('Typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .counter__content',
							),
							'default' => array(
								'font_family' => 'Helvetica',
								'font_weight' => '400',
								'text_align' => 'center',
								'font_size' => array(
									'length' => '50',
									'unit' => 'px',
								),
							)
						),
					)
				),
			)
		)
	)

);
