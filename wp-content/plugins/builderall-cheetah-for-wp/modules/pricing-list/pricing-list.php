<?php

/**
 * @class BACheetahListModule
 */
class BACheetahPricingListModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Pricing List', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Layout', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'pricing-list.svg',
		));

	}

	public function get_image_settings($item)
	{
		return array(
			'crop' => false,
			'photo' => $item->photo,
			'photo_src' => $item->photo_src,
			'show_caption' => false,
		);
	}

	public function get_image_styles_settings()
	{
		return array(
			'width' => $this->settings->photo_width,
			'width_unit' => $this->settings->photo_width_unit,
			'border' => $this->settings->photo_border,
			'align' => 'left',
			'caption_typography' => null,
		);
	}
}

/**
 * Subform accordion item
 */
BACheetah::register_settings_form('pricing_list_item', array(
	'title' => __('List item', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => '',
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'price' 		=> array(
							'type' 			=> 'text',
							'label' 		=> __('Price', 'ba-cheetah'),
							'default' 		=> '59',
						),
						'title'         => array(
							'type'          => 'text',
							'label'         => __('Title', 'ba-cheetah'),
							'default'		=> 'Product name'
						),
						'description'         => array(
							'type'          => 'editor',
							'media_buttons'	=> false,
							'default'       => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua',
						),
						'photo' => array(
							'type'          => 'photo',
							'label'         => __('Image', 'ba-cheetah'),
							'show_remove'   => true,
						),
						'link'         => array(
							'type'          => 'link',
							'label'         => __('Text', 'ba-cheetah'),
							'label'         => 'Link',
							'can_remove'	=> true,
							'show_target'   => true,
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
BACheetah::register_module('BACheetahPricingListModule', array(
	'items' => array(
		'title' => __('Items', 'ba-cheetah'),
		'sections' => array(
			'items' => array(
				'title' => '',
				'fields' => array(
					"items" => array(
						'type'          => 'form',
						'label'         => __('Product', 'ba-cheetah'),
						'form'          => 'pricing_list_item',
						'preview_text'  => 'title',
						'multiple'      => true,
						'limit'         => 50,
						'default' 		=> array(
							array('price' => '$19', 'title' => 'Product name', 'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua'),
						)
					),
				)
			),
		)
	),
	'styles'      => array(
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'items' => array(
				'title' => __('Items', 'ba-cheetah'),
				'collapsed' => false,
				'fields' => array(
					'space_between' => array(
						'type'       => 'unit',
						'label'      => __('Space between items', 'ba-cheetah'),
						'responsive' => true,
						'units'      => array('px'),
						'default_unit'=> 'px',
						'default' 	 => 50,
						'slider'     => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__pricing-list',
							'property'  => 'gap',
						),
					),
					'item_hover_color'      => array(
						'type'        => 'color',
						'show_reset'  => true,
						'show_remove' => true,
						'default'	  => 'F6F6FC',
						'label'       => __( 'Hover Color', 'ba-cheetah' ),
						'preview'     => array(
							'type'      => 'none'
						),
					),
					'item_padding' => array(
						'type'        => 'dimension',
						'label'       => __('Padding', 'ba-cheetah'),
						'description' => 'px',
						'responsive' => true,
						'default' => 20,
						'slider' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .pricing-list__item',
							'property' => 'padding',
						)
					),
				)
			),
			'heading' => array(
				'title'  => __( 'Heading', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array(
					'title_color'      => array(
						'type'        => 'color',
						'show_reset'  => true,
						'default'	  => '4054b2',
						'label'       => __( 'Title Color', 'ba-cheetah' ),
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-list__heading',
							'property'  => 'color',
						),
					),
					'price_color'      => array(
						'type'        => 'color',
						'default'	  => '4054b2',
						'show_reset'  => true,
						'label'       => __( 'Price Color', 'ba-cheetah' ),
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-list__heading .pricing-list__price',
							'property'  => 'color',
						),
					),
					'title_typography' => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'ba-cheetah' ),
						'responsive' => true,
						'default'	 => array(
							'font_family' => "Abril Fatface",
							'font_weight' => 400,
							'text_align' => 'left',
							'font_size' => array(
								'unit' => 'px',
								'length' => '28'
							)
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-list__heading',
							'important' => true,
						),
					),
				),
			),
			'divider' => array(
				'title' => __('Divider', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'divider_height' => array(
						'type' => 'unit',
						'label' =>  __('Weight', 'ba-cheetah'),
						'default' => 3,
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'preview'    => array(
							'type' => 'css',
							'selector' => '{node} .pricing-list__heading hr',
							'property' => 'border-top-width',
						),
					),
					'divider_margin' => array(
						'type' => 'unit',
						'label' =>  __('Horizontal margin', 'ba-cheetah'),
						'default' => 20,
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => true,
						'preview'    => array(
							'type' => 'css',
							'selector' => '{node} .pricing-list__heading',
							'property' => 'gap',
						),
					),
					'divider_style' => array(
						'type' => 'select',
						'label' =>  __('Style', 'ba-cheetah'),
						'default' => 'dotted',
						'options' => array(
							'none' => __('None', 'ba-cheetah'),
							'solid' => __('Solid', 'ba-cheetah'),
							'dashed' => __('Dashed', 'ba-cheetah'),
							'dotted' => __('Dotted', 'ba-cheetah'),
							'double' => __('Double', 'ba-cheetah'),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector' => '{node} .pricing-list__heading hr',
							'property'	=> 'border-top-style'
						),
					),
					'divider_align_v' => array(
						'type' => 'select',
						'label' => __('Vertical Align', 'ba-cheetah'),
						'default' => 'baseline',
						'options' => array(
							'baseline' => __('Base', 'ba-cheetah'),
							'center' => __('Center', 'ba-cheetah'),
						),
						'preview' => array(
							'type' => 'css',
							'selector' => '{node} .pricing-list__heading',
							'property' => 'align-items',
						)
					),
					'divider_color' => array(
						'type'          => 'color',
						'label'         => __('Color', 'ba-cheetah'),
						'default'       => 'e4e4f6',
						'show_reset'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector' => '{node} .pricing-list__heading hr',
							'property'	=> 'border-top-color'
						),
					),
				)
			),
			'description' => array(
				'title'  => __( 'Description', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array(
					'description_color' => array(
						'type'        => 'color',
						'show_reset'  => true,
						'default'	  => '7a7a7a',
						'label'       => __( 'Color', 'ba-cheetah' ),
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-list__description',
							'property'  => 'color',
						),
					),
					'description_typography' => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'ba-cheetah' ),
						'responsive' => true,
						'default'	 => array(
							'font_family' => "Montserrat",
							'font_weight' => 500,
							'text_align' => 'left',
							'font_size' => array(
								'unit' => 'px',
								'length' => '14'
							)
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-list__description',
							'important' => true,
						),
					),
				),
			),
			'photo' => array(
				'title' => __('Photo', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'photo_position' => array(
						'type' => 'select',
						'label' => __('Position', 'ba-cheetah'),
						'default' => 'left',
						'options' => array(
							'left' => __('Left', 'ba-cheetah'),
							'right' => __('Right', 'ba-cheetah'),
							'alternate' => __('Alternate', 'ba-cheetah'),
						),
						'preview' => array(
							'type' => 'attribute',
							'attribute' => 'data-photo-position',
							'selector' => '.ba-module__pricing-list'
						)
					),
					'photo_vertical_align' => array(
						'type' => 'select',
						'label' => __('Vertical align', 'ba-cheetah'),
						'default' => 'center',
						'options' => array(
							'flex-start' => __('Top', 'ba-cheetah'),
							'center' => __('Center', 'ba-cheetah'),
							'flex-end' => __('Bottom', 'ba-cheetah'),
						),
						'preview' => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-list__item',
							'property'  => 'align-items',
						)
					),
					'photo_width' => array(
						'type'       => 'unit',
						'label'      => __('Width', 'ba-cheetah'),
						'responsive' => true,
						'units'      => array('px', '%'),
						'default_unit'=> 'px',
						'default' 	 => 210,
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
					'photo_margin' => array(
						'type'       => 'unit',
						'label'      => __('Margin', 'ba-cheetah'),
						'responsive' => true,
						'units'      => array('px'),
						'default_unit'=> 'px',
						'default' 	 => 20,
						'slider'     => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .pricing-list__item',
							'property'  => 'gap',
						),
					),
					'photo_border'             => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'ba-cheetah' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-cheetah-photo-img',
						),
						'default' => array(
							'shadow' => array(
								'color' => 'e4e4f6',
								'horizontal' => '4',
								'vertical' => '4',
								'blur' => '1',
								'spread' => '0',
							),
							'radius' => array(
								'top_left' => '15',
								'top_right' => '15',
								'bottom_left' => '15',
								'bottom_right' => '15',
							)
						)
					),
				)
			)
		)
	),
));
