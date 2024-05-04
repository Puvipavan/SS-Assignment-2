<?php

/**
 * @class BACheetahMegaMenuModule
 */
class BACheetahMegaMenuModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Mega Menu', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Layout', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'mega-menu.svg',
		));

	}

	public function get_title($item)
	{
		if ($item->icon) {
			echo "<i class='" . esc_attr($item->icon) . "'></i>";
		}
		if ($item->title) {
			echo "<span>" . esc_html($item->title) . "</span>";
		}
	}

	public function update( $settings ){
		return $this->auto_generate_form_node_ids($settings, 'mega_menu_items', $this->node);
	}
}

/**
 * Subform accordion item
 */
BACheetah::register_settings_form('mega_menu_item', array(
	'title' => __('Mega menu item', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => '',
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'title'         => array(
							'type'          => 'text',
							'label'      	=> __('Title', 'ba-cheetah'),
							'default'       => __('Item', 'ba-cheetah'),
						),
						'icon'         => array(
							'type'          => 'icon',
							'label'         => __('Icon', 'ba-cheetah'),
							'show_remove'   => true,
						),
						'dragdrop'         => array(
							'label' => __('Type', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'yes',
							'options' => array(
								'yes' => __('Content', 'ba-cheetah'),
								'no' => __('Link', 'ba-cheetah'),
							),
							'toggle' => array(
								'no' => array(
									'fields' => array('link')
								),
							)
						),
						'row_node_id'         => array(
							'type'          => 'hidden',
						),
						'link' => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'ba-cheetah' ),
							'placeholder'   => __( 'http://www.example.com', 'ba-cheetah' ),
							'show_target'   => true,
							'show_nofollow' => true,
							'show_download' => true,
							'preview'       => array(
								'type' => 'none',
							),
						)
					)
				),
			)
		)
	)
));

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahMegaMenuModule', array(
	'items' => array(
		'title' => __('Items', 'ba-cheetah'),
		'sections' => array(
			'items' => array(
				'title' => '',
				'fields' => array(
					"mega_menu_items" => array(
						'type'          => 'form',
						'label'         => __('List item', 'ba-cheetah'),
						'form'          => 'mega_menu_item',
						'preview_text'  => 'title',
						'multiple'      => true,
						'limit'         => 10,
						'default' 		=> array(
							array('title' => 'Mega item', 'dragdrop' => 'yes', 'icon' => 'fas fa-angle-down'),
							array('title' => 'Mega item 2', 'dragdrop' => 'yes', 'icon' => 'fas fa-angle-down'),
							array('title' => 'Mega link', 'dragdrop' => 'no', 'icon' => 'fas fa-external-link-alt', 'link' => 'https://builderall.com'),
						)
					),
					/*
					"event" => array(
						'type' => 'button-group',
						'label' => __('Event', 'ba-cheetah'),
						'default' => 'click',
						'options' => array(
							'click' => __('Click', 'ba-cheetah'),
							'mouseover' => __('Hover', 'ba-cheetah')
						)
					)
					*/
				)
			),
		)
	),
	'styles'      => array(
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'style' => array(
				'title' => __('Navbar', 'ba-cheetah'),
				'fields' => array(
					'navbar_background' => array(
						'type' => 'color',
						'label'		=> __('Background', 'ba-cheetah'),
						'default' => '4054b2',
						'show_reset' => true,
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav',
							'property'  => 'background-color',
						)
					),
					'navbar_alignment' => array(
						'type' => 'align',
						'label' => __('Alignment', 'ba-cheetah'),
						'values' => array(
							'left' => 'flex-start',
							'center' => 'center',
							'right' => 'flex-end'
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__items',
							'property'	=> 'justify-content'
						),
						'default' => 'center'
					),
					'navbar_height' => array(
						'type'    => 'unit',
						'label'   => __('Height', 'ba-cheetah'),
						'help' 	  => __('This size will also be used for the height of the mobile menu', 'ba-cheetah'),
						'default' => '70',
						'units' => array('px'),
						'default_unit' => 'px',
						'slider'  => array(
							'min'  => 20,
							'max'  => 150,
							'step' => 1,
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__items',
							'property'	=> 'height'
						),
					),
					'navbar_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav',
						),
						'default' => array(
							'radius' => array(
								'top_left' => '8',
								'top_right' => '8',
								'bottom_left' => '8',
								'bottom_right' => '8',
							),
						),
					),
				),
			),
			'menu' => array(
				'title' => __('Menu items', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'menu_padding'      => array(
						'type'       => 'unit',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'default_unit' => 'px',
						'units'      => array('px'),
						'default' 	 => '40',
						'preview'    => array(
							'type'     => 'css',
							'rules' => array(
								array(
									'selector' => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__items .mega-menu__nav__item',
									'property' => 'padding-left',
								),
								array(
									'selector' => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__items .mega-menu__nav__item',
									'property' => 'padding-right',
								)
							)
						),
					),
					'menu_distance' => array(
						'type'    => 'unit',
						'label'   => __('Spacing between items', 'ba-cheetah'),
						'default' => '15',
						'default_unit' => 'px',
						'responsive' => true,
						'slider'  => array(
							'px' => array(
								'min'  => 0,
								'max'  => 500,
								'step' => 1,
							),
							'%' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							)
						),
						'units'   => array('px', '%'),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__items',
							'property' => 'gap',
						),
					),
					'icon_distance' => array(
						'type'    => 'unit',
						'label'   => __('Icon Spacing', 'ba-cheetah'),
						'default' => '20',
						'default_unit' => 'px',
						'responsive' => true,
						'slider'  => array(
							'px' => array(
								'min'  => 0,
								'max'  => 500,
								'step' => 1,
							),
							'%' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							)
						),
						'units'   => array('px', '%'),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .mega-menu__nav__item, {node} .mega-menu__nav__item a',
							'property' => 'gap',
						),
					),
					'icon_position' => array(
						'type' => 'select',
						'label' => __('Icon position', 'ba-cheetah'),
						'default' => 'row-reverse',
						'options' => array(
							'row' => __('Left', 'ba-cheetah'),
							'row-reverse' => __('Right', 'ba-cheetah'),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '{node} .mega-menu__nav__item, {node} .mega-menu__nav__item a',
							'property' => 'flex-direction',
						),
					),
					'raw_1' => array(
						'type' => 'raw',
						'content' => '<div class="ba-cheetah-forms-settings-section-sub">' . __('Normal state', 'ba-cheetah') . '</div>'
					),
					'item_background' => array(
						'type' => 'color',
						'label'		=> __('Background', 'ba-cheetah'),
						'show_reset' => true,
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__item:not(.mega-menu__nav__item--active)',
							'property'  => 'background-color',
						)
					),
					'item_color' => array(
						'type' => 'color',
						'label'		=> __('Color', 'ba-cheetah'),
						'default' => 'f6f6fc',
						'show_reset' => true,
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__item:not(.mega-menu__nav__item--active)',
							'property'  => 'color',
						)
					),
					'item_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__item:not(.mega-menu__nav__item--active)',
						)
					),
					'item_typography' => array(
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
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__item:not(.mega-menu__nav__item--active)',
						),
						'default' => array(
							'font_family' => 'Montserrat',
							'font_weight' => '600',
							'font_size' => array(
								'length' => '15',
								'unit' => 'px',
							),
							'line_height' =>  array(
								'length' => '0',
								'unit' => '',
							),
						)
					),
					'raw_2' => array(
						'type' => 'raw',
						'content' => '<div class="ba-cheetah-forms-settings-section-sub">' . __('On Hover', 'ba-cheetah') . '</div>'
					),
					'item_background_hover' => array(
						'type' => 'color',
						'label'		=> __('Background', 'ba-cheetah'),
						'default' => '102a89',
						'show_reset' => true,
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__item--active',
							'property'  => 'background-color',
						)
					),
					'item_color_hover' => array(
						'type' => 'color',
						'label'		=> __('Color', 'ba-cheetah'),
						'show_reset' => true,
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__item--active',
							'property'  => 'color',
						)
					),
					'item_border_hover' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__nav .mega-menu__nav__item--active',
						),
						'default' => array(
							'radius' => array(
								'top_left' => '8',
								'top_right' => '8',
								'bottom_left' => '8',
								'bottom_right' => '8',
							),
						),
					),
					'item_hover_transition' => array(
						'type'    => 'unit',
						'label'   => __('Transition', 'ba-cheetah'),
						'default' => 1000,
						'default_unit' => 'ms',
						'units'   => array('ms'),
						'slider'  => array(
							'ms' => array(
								'min'  => 0,
								'max'  => 5000,
								'step' => 100,
							),
						),
						'preview'    => array(
							'type'      => 'none'
						)
					)
				)
			),
			'content' => array(
				'title' => __('Content', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'content_background' => array(
						'type' => 'color',
						'help' => __('This is the global background color of the content area. The background color of each item (and other properties) can be set in the row properties', 'ba-cheetah'),
						'label'		=> __('Background Color', 'ba-cheetah'),
						'show_reset' => true,
						'show_alpha' => true,
						'default'	 => 'f6f6fc',
						'preview'    => array(
							'type'      => 'css',
							'property'	=> 'background-color',
							'selector'  => '{node} .ba-module__mega-menu .mega-menu__content',
						),
					),
				)
			)
		)
	),
));
