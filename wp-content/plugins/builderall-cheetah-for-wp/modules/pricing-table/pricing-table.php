<?php

/**
 * @class BACheetahPricingTableModule
 */
class BACheetahPricingTableModule extends BACheetahModule
{

	/**

	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Pricing table', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Layout', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'pricing-table.svg',
		));
	}

	/**
	 * Return a order of section
	 *
	 * @param string $section
	 * @return integer
	 */
	public function get_section_order($section = '')
	{
		foreach ($this->settings->order as $key => $element) {
			if ($element == $section) {
				return $key;
			}
		}
		return 0;
	}

	/**
	 * Get settings for button module
	 *
	 * @return array
	 */
	public function get_button_settings()
	{
		$settings = array(
			'id' 			=> 'ba-cheetah-node-' . $this->node,
			'click_action' 	=> 'link'
		);
		foreach ($this->settings as $key => $value) {
			if (strstr($key, 'btn_')) {
				$key              = str_replace('btn_', '', $key);
				$settings[$key] = $value;
			}
		}
		return $settings;
	}

	/**
	 * Get settings for image module
	 *
	 * @return array
	 */
	public function get_image_settings()
	{
		add_filter('ba_cheetah_photo_noimage', function() {
			return null;
		});
		return array(
			'crop' => false,
			'photo' => $this->settings->photo,
			'photo_src' => $this->settings->photo_src,
			'show_caption' => false,
			'width' => $this->settings->photo_width,
			'width_unit' => $this->settings->photo_width_unit,
			'align' => $this->settings->photo_align,
			'border' => $this->settings->photo_border,
			'caption_typography' => null,
		);
	}
}

/**
 * Subform features
 */

BACheetah::register_settings_form('pricing_table_features', array(
	'title' => __('Features', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => '',
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'icon'         => array(
							'type'          => 'icon',
							'label'         => __('Icon', 'ba-cheetah'),
							'show_remove'   => true,
						),
						'icon_color' => array(
							'label' => __('Icon Color', 'ba-cheetah'),
							'type' => 'color',
							'default' => 'a3a3a3',
						),
						'description'         => array(
							'type'          => 'editor',
							'media_buttons'	=> false,
							'default'       => '',
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
BACheetah::register_module('BACheetahPricingTableModule', array(
	'general' => array(
		'title'    => __('Content', 'ba-cheetah'),
		'sections' => array(
			'header' => array(
				'title'  => __('Header', 'ba-cheetah'),
				'fields' => array(
					'title' => array(
						'type' => 'text',
						'label' => __('Title', 'ba-cheetah'),
						'default' => 'Premium',
						'preview' => array(
							'type' => 'refresh',
							'selector' => '{node} .pricing-table__title'
						)
					),
					'subtitle' => array(
						'type' => 'text',
						'label' => __('Subtitle', 'ba-cheetah'),
						'default' => 'I am a awesome element! I am looking forward to you editing me!',
						'preview' => array(
							'type' => 'refresh',
							'selector' => '{node} .pricing-table__subtitle'
						)
					)
				),
			),
			'price' => array(
				'title' => __('Price', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'currency' => array(
						'type' => 'text',
						'label' => __('Currency', 'ba-cheetah'),
						'default' => '$',
						'preview' => array(
							'type' => 'refresh',
							'selector' => '{node} .pricing-table__price .pricing-table__currency'
						)
					),
					'value' => array(
						'type' => 'text',
						'label' => __('Value', 'ba-cheetah'),
						'default' => '59',
						'preview' => array(
							'type' => 'refresh',
							'selector' => '{node} .pricing-table__price .pricing-table__value'
						)
					),
					'period' => array(
						'type' => 'text',
						'label' => __('Period', 'ba-cheetah'),
						'default' => __('/m', 'ba-cheetah'),
						'preview' => array(
							'type' => 'refresh',
							'selector' => '{node} .pricing-table__price .pricing-table__period'
						)
					)
				),
			),
			'image' => array(
				'title' => __('Image', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'photo' => array(
						'type'          => 'photo',
						'label'         => __('Image', 'ba-cheetah'),
						'show_remove'   => true,
					),
					'photo_width'              => array(
						'type'       => 'unit',
						'label'      => __('Width', 'ba-cheetah'),
						'responsive' => true,
						'units'      => array('px', '%'),
						'slider'     => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
							'%' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-cheetah-photo-img, {node} .ba-cheetah-photo-content',
							'property'  => 'width',
							'important' => true,
						),
					),
					'photo_align' => array(
						'type'       => 'align',
						'label'      => __('Align', 'ba-cheetah'),
						'default'    => 'center',
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-cheetah-photo',
							'property'  => 'text-align',
							'important' => true,
						),
					),
					'photo_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.ba-cheetah-photo-img',
						),
					),
				)
			),
			'items' => array(
				'title' => __('Features', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					"features" => array(
						'type'          => 'form',
						'label' 		=> __('Feature', 'ba-cheetah'),
						'form'          => 'pricing_table_features',
						'preview_text'  => 'description',
						'multiple'      => true,
						'limit'         => 100,
						'default' 		=> array(
							array('icon' => 'fas fa-star', 'icon_color' => '4054b2', 'description' => __('Feature', 'ba-cheetah') . ' 1'),
							array('icon' => 'fas fa-star', 'icon_color' => '4054b2', 'description' => __('Feature', 'ba-cheetah') . ' 2'),
							array('icon' => 'fas fa-star', 'icon_color' => '4054b2', 'description' => __('Feature', 'ba-cheetah') . ' 3'),
						)
					),
					'show_features' => array(
						'type' => 'button-group',
						'label' => __('Show features ?', 'ba-cheetah'),
						'default' => 'yes',
						'options' => array(
							'no' => __('No', 'ba-cheetah'),
							'yes' => __('Yes', 'ba-cheetah'),
						),
						'toggle' => array(
							'yes' => array(
								'fields' => array('features'),
								'sections' => array('features_style')
							)
						)
					),
				)
			),
			'ribbon' => array(
				'title' => __('Ribbon', 'ba-cheetah'),
				'collapsed' => true,
				'enabled' => BA_CHEETAH_PRO,
				'fields' => array(
					'ribbon_display' => array(
						'type' => 'button-group',
						'label' => __('Show', 'ba-cheetah'),
						'default' => 'none',
						'options' => array(
							'none' => __('No', 'ba-cheetah'),
							'block' => __('Yes', 'ba-cheetah')
						),
						'toggle'  => array(
							'block' => array(
								'fields' => array('ribbon_text', 'ribbon_position'),
								'sections' => array('ribbon_styles')
							),
						),
					),
					'ribbon_text' => array(
						'type' => 'text',
						'label' => __('Title', 'ba-cheetah'),
						'default' => 'Popular',
						'preview' => array(
							'type'     => 'attribute',
							'attribute'=> 'data-text',
							'selector' => '{node} .pricing-table__box .pricing-table__ribbon',
						)
					),
					'ribbon_position' => array(
						'type'    => 'align',
						'label'   => __('Position', 'ba-cheetah'),
						'default' => 'left',
						'values'  => array(
							'left'   => 'left',
							'right'  => 'right',
						),
						'preview'    => array(
							'type'     => 'attribute',
							'attribute'=> 'data-position',
							'selector' => '{node} .pricing-table__box .pricing-table__ribbon',
						),
					),
				)
			),
			'cta' => array(
				'title' => __('Button', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array_merge( array(
					'btn_text'           => array(
						'type'        => 'text',
						'label'       => __('Title', 'ba-cheetah'),
						'default'     => __('Buy Now!', 'ba-cheetah'),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '{node} .ba-module__button .button__content .button__text',
						),
					),
					'btn_sub_text'           => array(
						'type'        => 'text',
						'label'       => __('Subtitle', 'ba-cheetah'),
						'default'     => '',
						'preview'     => array(
							'type'     => 'text',
							'selector' => '{node} .ba-module__button .button__content .button__subtext',
						),
					),
					'btn_link'           => array(
						'type'          => 'link',
						'label'         => __('Link', 'ba-cheetah'),
						'placeholder'   => __('http://www.example.com', 'ba-cheetah'),
						'show_target'   => true,
						'show_nofollow' => true,
						'show_download' => true,
						'preview'       => array(
							'type' => 'none',
						),
					),
					'btn_icon'           => array(
						'type'        => 'icon',
						'label'       => __('Icon', 'ba-cheetah'),
						'show_remove' => true,
						'show'        => array(
							'fields' => array('btn_icon_position'),
							'sections' => array('btn_icon_styles')
						),
					),
					'btn_icon_position'  => array(
						'type'    => 'select',
						'label'   => __('Icon Position', 'ba-cheetah'),
						'default' => 'before',
						'options' => array(
							'before' => __('Before Text', 'ba-cheetah'),
							'after'  => __('After Text', 'ba-cheetah'),
						),
					),
				), BACheetahTracking::module_tracking_fields('btn_')),
			)
		),
	),
	'style'   => array(
		'title'    => __('Style', 'ba-cheetah'),
		'sections' => array(
			'box' => array(
				'title'  => __('Container', 'ba-cheetah'),
				'fields' => array(
					'box_background_type' => array(
						'label' => __('Background type', 'ba-cheetah'),
						'type' => 'button-group',
						'default' => 'solid',
						'options' => array(
							'solid' => __('Solid', 'ba-cheetah'),
							'gradient' => __('Gradient', 'ba-cheetah'),
						),
						'toggle' => array(
							'solid' => array('fields' => array('box_background')),
							'gradient' => array('fields' => array('box_background_gradient'))
						)
					),
					'box_background_gradient' => array(
						'type'    => 'gradient',
						'label' => __('Gradient Background', 'ba-cheetah'),
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box',
							'property'  => 'background-image',
						),
					),
					'box_background' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'default' => 'f6f6fc',
						'show_alpha'    => true,
						'show_reset'  => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box',
							'property'	=> 'background'
						),
					),
					'box_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'responsive' => true,
						'units'      => array('px'),
						'default' 	 => '0',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box',
							'property' => 'padding',
						),
					),
					'box_border'  => array(
						'type' => 'border',
						'label' => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .pricing-table__box',
						),
						'default' => array(
							'shadow' =>array(
								'color' => 'c4c4c4',
								'horizontal' => '1',
								'vertical' => '1',
								'blur' => '4',
								'spread' => '0',
							),
						)
					),

				)
			),
			'header' => array(
				'title'  => __('Header', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'header_background' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'show_alpha'    => true,
						'show_reset'  => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__header',
							'property'	=> 'background'
						),
					),

					'hr1' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),
					'header_title_color' => array(
						'label' => __('Title Color', 'ba-cheetah'),
						'type' => 'color',
						'default' => '4054b2',
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__header .pricing-table__title',
							'property'	=> 'color'
						),
					),
					'header_title_typography' => array(
						'type'       => 'typography',
						'label'      => __('Title Typography', 'ba-cheetah'),
						'responsive' => true,
						'default' => array(
							'font_family' => 'Helvetica',
							'font_weight' => '700',
							'font_size' => array(
								'length' => '30',
								'unit' => 'px',
							),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__header .pricing-table__title',
						),
					),
					'hr2' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),
					'header_subtitle_color' => array(
						'label' => __('Subitle Color', 'ba-cheetah'),
						'type' => 'color',
						'default' => '9498b2',
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__header .pricing-table__subtitle',
							'property'	=> 'color'
						),
					),
					'header_subtitle_typography' => array(
						'type'       => 'typography',
						'label'      => __('Subtitle Typography', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__header .pricing-table__subtitle',
						),
						'default' => array(
							'font_family' => 'Helvetica',
							'font_weight' => '300',
							'font_size' => array(
								'length' => '15',
								'unit' => 'px',
							),
						)
					),
					'hr3' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),
					'header_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'units'      => array('px'),
						'default' 	 => 20,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__header',
							'property' => 'padding',
						),
					),
					'header_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__header',
						),
					),
				)
			),
			'pricing' => array(
				'title' => __('Price', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'price_background' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'default' => 'fcfcfc',
						'show_alpha'    => true,
						'show_reset'  => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__price',
							'property'	=> 'background'
						),
					),
					'price_color' => array(
						'label' => __('Text Color', 'ba-cheetah'),
						'type' => 'color',
						'default'       => '4054b2',
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__price',
							'property'	=> 'color'
						),
					),
					'price_typography' => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__price',
						),
						'default' => array(
							'font_family' => 'Helvetica',
							'font_weight' => '700',
							'font_size' => array(
								'length' => '78',
								'unit' => 'px',
							),
							'line_height' => array(
								'length' => '0.7',
								'unit' => '',
							),
						)
					),

					'hr1' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),
					'currency_size' => array(
						'type'    => 'unit',
						'label'   => __('Currency size', 'ba-cheetah'),
						'default' => 25,
						'slider'  => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'units' => array('%'),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__price .pricing-table__currency',
							'property' => 'font-size',
						),
					),
					'currency_position' => array(
						'type' => 'select',
						'label' => __('Currency vertical position', 'ba-cheetah'),
						'default' => 'flex-start',
						'options' => array(
							'flex-start' => __('Top', 'ba-cheetah'),
							'center'    => __('Center', 'ba-cheetah'),
							'flex-end'  => __('Bottom', 'ba-cheetah'),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__price .pricing-table__currency',
							'property' => 'align-self',
						),
					),
					'hr2' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),
					'period_size' => array(
						'type'    => 'unit',
						'label'   => __('Period size', 'ba-cheetah'),
						'default' => 20,
						'slider'  => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'units' => array('%'),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__price .pricing-table__period',
							'property' => 'font-size',
						),
					),
					'period_position' => array(
						'type' => 'select',
						'label' => __('Period position', 'ba-cheetah'),
						'default' => 'flex-end',
						'options' => array(
							'flex-start' => __('Top', 'ba-cheetah'),
							'center'    => __('Center', 'ba-cheetah'),
							'flex-end'  => __('Bottom', 'ba-cheetah'),
							'bellow'  => __('Bellow', 'ba-cheetah'),
						),
					),
					'period_color' => array(
						'label' => __('Period color', 'ba-cheetah'),
						'type' => 'color',
						'default' => 'rgba(64,84,178,0.55)',
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__price .pricing-table__period',
							'property'	=> 'color'
						),
					),
					'hr3' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),
					'pricing_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'default' 	 => '60',
						'units'      => array('px'),
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__price',
							'property' => 'padding',
						),
					),
					'pricing_elements_gap' => array(
						'type'    => 'unit',
						'label'   => __('Internal Gap', 'ba-cheetah'),
						'default' => 10,
						'slider'  => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'units' => array('px'),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__price',
							'property' => 'gap',
						),
					),
					'pricing_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__price',
						),
					),
				)
			),
			'features_style' => array(
				'title'  => __('Features', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'features_background' => array(
						'label' => __('Background', 'ba-cheetah'),
						'type' => 'color',
						'show_alpha'    => true,
						'show_reset'  => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__features',
							'property'	=> 'background'
						),
					),
					'features_color' => array(
						'label' => __('Text Color', 'ba-cheetah'),
						'type' => 'color',
						'default' => '4054b2',
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__features .pricing-table__feature-item span',
							'property'	=> 'color'
						),
					),
					'features_alignment' => array(
						'type' => 'align',
						'label' => __('Alignment', 'ba-cheetah'),
						'values' => array(
							'left' => 'flex-start',
							'center' => 'center',
							'right' => 'flex-end'
						),
						'default' => 'center'
					),

					'space_between_items' => array(
						'type' => 'unit',
						'label' => __('Item spacing', 'ba-cheetah'),
						'default' => '30',
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),

					'features_typography' => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'disabled' => array(
							'default' => array('text_align'),
							'medium' => array('text_align'),
							'responsive' => array('text_align')
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__features .pricing-table__feature-item',
						),
						'default' => array(
							'font_family' => 'Helvetica',
							'font_weight' => '400'
						)
					),

					'hr1' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),

					'features_icon_position' => array(
						'type' => 'select',
						'label' => __('Icon Position', 'ba-cheetah'),
						'default' => 'row',
						'responsive' => true,
						'options' => array(
							'column' => __('Top', 'ba-cheetah'),
							'column-reverse' => __('Bottom', 'ba-cheetah'),
							'row' => __('Left', 'ba-cheetah'),
							'row-reverse' => __('Right', 'ba-cheetah'),
						),
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__features .pricing-table__feature-item',
							'property' => 'flex-direction'
						)
					),

					'features_icon_size' => array(
						'type' => 'unit',
						'label' =>  __('Icon Size', 'ba-cheetah'),
						'default' => 1,
						'units' => array('px', 'em'),
						'default_unit' => 'em',
						'responsive' => true,
						'slider' => array(
							'px' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
							'em' => array(
								'min'  => 0,
								'max'  => 10,
								'step' => 0.1,
							)
						),
						'preview'    => array(
							'type' => 'css',
							'selector'  => '{node} .pricing-table__box .pricing-table__features .pricing-table__feature-item i',
							'property' => 'font-size',
						),
					),

					'hr4' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),

					'show_feature_divider' => array(
						'type'    => 'button-group',
						'label'   =>  __('Show divider?', 'ba-cheetah'),
						'default' => 'yes',
						'options' => array(
							'no'    => __('No', 'ba-cheetah'),
							'yes'    => __('Yes', 'ba-cheetah'),
						),
						'toggle' => array(
							'yes' => array(
								'fields' => array(
									'divider_height',
									'divider_width',
									'divider_style',
									'divider_color'
								)
							)
						),
					),
					'divider_height' => array(
						'type' => 'unit',
						'label' =>  __('Divider height', 'ba-cheetah'),
						'default' => 1,
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'preview'    => array(
							'type' => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__features .pricing-table__feature-item--divider hr',
							'property' => 'border-top-width',
						),
					),
					'divider_width' => array(
						'type' => 'unit',
						'label' =>  __('Divider Width', 'ba-cheetah'),
						'default' => 100,
						'units' => array('px', '%'),
						'default_unit' => '%',
						'slider' => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 1,
							),
							'%' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
						),
						'preview'    => array(
							'type' => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__features .pricing-table__feature-item--divider hr',
							'property' => 'flex-basis',
						),
					),
					'divider_style' => array(
						'type' => 'select',
						'label' =>  __('Divider Style', 'ba-cheetah'),
						'default' => 'solid',
						'options' => array(
							'solid' => __('Solid', 'ba-cheetah'),
							'dashed' => __('Dashed', 'ba-cheetah'),
							'dotted' => __('Dotted', 'ba-cheetah'),
							'double' => __('Double', 'ba-cheetah'),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__features .pricing-table__feature-item--divider hr',
							'property'	=> 'border-top-style'
						),
					),
					'divider_color' => array(
						'type'          => 'color',
						'label'         => __('Divider Color', 'ba-cheetah'),
						'default'       => 'rgba(148,152,178,0.25)',
						'show_reset'    => true,
						'show_alpha'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__features .pricing-table__feature-item--divider hr',
							'property'	=> 'border-top-color'
						),
					),
					'hr2' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),
					'features_padding' => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'units'      => array('px'),
						'default' 	 => '30',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__features',
							'property' => 'padding',
						),
					),
					'features_margin' => array(
						'type'       => 'dimension',
						'label'      => __('Margin', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'units'      => array('px'),
						'default' 	 => '0',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__features',
							'property' => 'margin',
						),
					),
					'features_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__features',
						),
					),
				)
			),
			'ribbon_styles' => array(
				'title' => __('Ribbon', 'ba-cheetah'),
				'collapsed' => true,
				'enabled' => BA_CHEETAH_PRO,
				'fields' => array(
					'ribbon_background_color' => array(
						'label'		  => __('Background color', 'ba-cheetah'),
						'type'        => 'color',
						'default'     => '0080FC',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'    => array(
							'type'     => 'css',
							'property' => 'background-color',
							'selector' => '{node} .pricing-table__box .pricing-table__ribbon::before',
						),
					),
					'ribbon_color' => array(
						'label'		  => __('Text color', 'ba-cheetah'),
						'type'        => 'color',
						'default'     => 'ffffff',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'    => array(
							'type'     => 'css',
							'property' => 'color',
							'selector' => '{node} .pricing-table__box .pricing-table__ribbon::before',
						),
					),
					'ribbon_typography' => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__ribbon::before',
						),
						'disabled' => array( 
							'responsive' => array( 'text_align' ),
							'medium' => array( 'text_align' ),
							'default' => array( 'text_align' ) 
						),
						'default' => array(
							"font_size" => array(
								"length" => "16",
								"unit" => "px"
							),
						),
					),
					'ribbon_depth' => array(
						'type' => 'unit',
						'label' =>  __('Depth', 'ba-cheetah'),
						'default' => 6,
						'units' => array('px', 'em'),
						'default_unit' => 'px',
						'slider' => true,
						'preview'    => array(
							'type'     => 'callback',
							'callback' => 'previewCSSVar',
							'selector' => '.pricing-table__box .pricing-table__ribbon',
							'variable' => '--ribbon-depth'
						),
					),
					'ribbon_distance' => array(
						'type' => 'unit',
						'label' =>  __('Distance', 'ba-cheetah').'/'.__('Padding', 'ba-cheetah'),
						'default' => 4,
						'units' => array('px', 'em'),
						'default_unit' => 'em',
						'slider' => true,
						'preview' => array(
							'type' => 'css',
							'rules' => array(
								array(
									'selector' => '{node} .pricing-table__box .pricing-table__ribbon::before',
									'property' => 'padding-left'
								),
								array(
									'selector' => '{node} .pricing-table__box .pricing-table__ribbon::before',
									'property' => 'padding-right'
								),
							)
						),
					),
				)
			),
			'ordering' => array(
				'title' => __('Ordering', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'order' => array(
						'type' => 'ordering',
						'options' => array(
							'header' => __('Title', 'ba-cheetah'),
							'price' => __('Price', 'ba-cheetah'),
							'image' => __('Image', 'ba-cheetah'),
							'features' => __('Features', 'ba-cheetah'),
							'cta' => __('Button', 'ba-cheetah'),
						),
						'default' => array(
							'header',
							'price',
							'image',
							'features',
							'cta'
						)
					),
				)
			)
		)
	),
	'button_style'   => array(
		'title'    => __('Button style', 'ba-cheetah'),
		'sections' => array(
			'style'  => array(
				'title'  => __('Size & Align', 'ba-cheetah'),
				'fields' => array(
					'btn_width'        => array(
						'type'    => 'select',
						'label'   => __('Width', 'ba-cheetah'),
						'default' => 'full',
						'options' => array(
							'auto'   => _x('Auto', 'Width.', 'ba-cheetah'),
							'full'   => __('Full Width', 'ba-cheetah'),
							'custom' => __('Custom', 'ba-cheetah'),
						),
						'toggle'  => array(
							'auto'   => array(
								'fields' => array('btn_align'),
							),
							'full'   => array(),
							'custom' => array(
								'fields' => array('btn_align', 'btn_custom_width'),
							),
						),
					),
					'btn_custom_width' => array(
						'type'    => 'unit',
						'label'   => __('Custom Width', 'ba-cheetah'),
						'default' => '200',
						'slider'  => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
						),
						'units'   => array(
							'px',
							'vw',
							'%',
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button .button__button',
							'property' => 'width',
						),
					),
					'btn_align'        => array(
						'type'       => 'align',
						'label'      => __('Align', 'ba-cheetah'),
						'default'    => 'flex-start',
						'values' => array(
							'left' => 'flex-start',
							'center' => 'center',
							'right' => 'flex-end'
						),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button',
							'property' => 'justify-content',
						),
					),
					'btn_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Button padding', 'ba-cheetah'),
						'responsive' => true,
						'slider'     => true,
						'units'      => array('px'),
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button .button__button',
							'property' => 'padding',
						),
					),
					'hr_cta' => array(
						'type'    => 'raw',
						'content' => '<hr>',
					),
					'cta_padding' => array(
						'type'       => 'dimension',
						'label'      => __('Button container padding', 'ba-cheetah'),
						'slider'     => true,
						'responsive' => true,
						'units'      => array('px'),
						'default' 	 => '20',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-table__box .pricing-table__cta',
							'property' => 'padding',
						),
					),
				),
			),
			'colors' => array(
				'title'  => __('Background', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'btn_bg_color_type' => array(
						'label' => __('Background type', 'ba-cheetah'),
						'type' => 'button-group',
						'default' => 'solid',
						'options' => array(
							'solid' => __('Solid', 'ba-cheetah'),
							'gradient' => __('Gradient', 'ba-cheetah'),
						),
						'toggle' => array(
							'solid' => array('fields' => array('btn_bg_color')),
							'gradient' => array('fields' => array('btn_bg_gradient'))
						)
					),
					'btn_bg_color'          => array(
						'label' => __('Background color', 'ba-cheetah'),
						'type'        => 'color',
						'default'     => '0080FC',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button',
							'property'  => 'background-color',
						),
					),
					'btn_bg_gradient' => array(
						'type'    => 'gradient',
						'label' => __('Gradient Background', 'ba-cheetah'),
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button',
							'property'  => 'background-image',
						),
					),
					'btn_miau' => array(
						'type' => 'raw',
						'content' => '<hr>'
					),
					'btn_bg_hover_color_type' => array(
						'label'       => __('Background Hover Type', 'ba-cheetah'),
						'type' => 'select',
						'default' => 'solid',
						'options' => array(
							'solid' => __('Solid', 'ba-cheetah'),
							'gradient' => __('Gradient', 'ba-cheetah'),
						),
						'toggle' => array(
							'solid' => array('fields' => array('btn_bg_hover_color')),
							'gradient' => array('fields' => array('btn_bg_hover_gradient'))
						)
					),
					'btn_bg_hover_color'    => array(
						'label'       => __('Background Hover Color', 'ba-cheetah'),
						'type'        => 'color',
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'btn_bg_hover_gradient' => array(
						'label'       => __('Gradient Background Hover Color', 'ba-cheetah'),
						'type'    => 'gradient',
						'preview'     => array(
							'type'      => 'none'
						),
					),
					'btn_transition' => array(
						'type'    => 'select',
						'label'   => __('Background Animation', 'ba-cheetah'),
						'default' => 'disable',
						'options' => array(
							'disable' => __('Disabled', 'ba-cheetah'),
							'enable'  => __('Enabled', 'ba-cheetah'),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			),
			'border' => array(
				'collapsed' => true,
				'title'  => __('Border', 'ba-cheetah'),
				'fields' => array(
					'btn_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button',
							'important' => true,
						),
						'default' => array(
							'style' => 'solid',
							'color' => '',
							'width' => array(
								'top' => '0',
								'right' => '0',
								'left' => '0',
								'bottom' => '0'
							)
						)
					),
					'btn_border_hover_color' => array(
						'type'        => 'color',
						'connections' => array('color'),
						'label'       => __('Border Hover Color', 'ba-cheetah'),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
				),
			),
			'text'   => array(
				'title'  => __('Title', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'btn_text_color'       => array(
						'type'        => 'color',
						'label'       => __('Text Color', 'ba-cheetah'),
						'default'     => 'fff',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button .button__text',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_text_hover_color' => array(
						'type'        => 'color',
						'connections' => array('color'),
						'label'       => __('Text Hover Color', 'ba-cheetah'),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'none',
						),
					),
					'btn_typography'       => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button .button__button .button__text',
						),
						'default' => array(
							"font_size" => array(
								"length" => "18",
								"unit" => "px"
							),
						),
					),
				),
			),
			'sub_text'   => array(
				'title'  => __('Subtitle', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'btn_sub_text_color'       => array(
						'type'        => 'color',
						'label'       => __('Text Color', 'ba-cheetah'),
						'default'     => 'fff',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button .button__subtext',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_sub_typography'       => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'default' => array(
							"font_size" => array(
								"length" => "12",
								"unit" => "px"
							),
						),
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__button .button__button .button__subtext',
						),
					),
				),
			),
			'btn_icon_styles'  => array(
				'title'  => __('Icon', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'btn_icon_color' => array(
						'label'      => __('Icon Color', 'ba-cheetah'),
						'type'       => 'color',
						'default'    => 'fff',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button i.button__icon',
							'property'  => 'color',
							'important' => true,
						),
					),
					'btn_icon_size' => array(
						'label'      => __('Icon size', 'ba-cheetah'),
						'type'       => 'unit',
						'default'    => '',
						'default_unit' => 'px',
						'units' => array('px', 'em'),
						'slider' 	=> true,
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button i.button__icon',
							'property'  => 'font-size',
						),
					),
					'btn_gap' => array(
						'label'      => __('Distance from text', 'ba-cheetah'),
						'type'       => 'unit',
						'default'    => '',
						'default_unit' => 'px',
						'units' => array('px'),
						'slider' 	=> true,
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__button .button__button',
							'property'  => 'gap',
						),
					),
				),
			),
		),
	),
));
