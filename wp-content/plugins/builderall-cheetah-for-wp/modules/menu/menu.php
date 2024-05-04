<?php

/**
 * @class BACheetahMenuModule
 */
class BACheetahMenuModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Menu', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Layout', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'menu.svg',
		));

		$this->add_css('dashicons');
	}

	/**
	 * Returns a list of wordpress menus
	 *
	 * @return array
	 */
	public static function get_wp_menus()
	{
		$wp_menus = wp_get_nav_menus();
		$menus_options = array();
		foreach ($wp_menus as $menu) {
			array_push($menus_options, array(
				'value' => $menu->term_id,
				'label' => $menu->name
			));
		}
		return $menus_options;
	}

	/**
	 * Returns the mobile CSS from the menu according to the chosen breakpoint 
	 * and the frontend.mobile.css file
	 * 
	 * @return string
	 */

	public function get_mobile_css() {
		$css = '';
		$file = $this->dir . 'css/frontend.mobile.css';

		if (ba_cheetah_filesystem()->file_exists( $file ) ) {
			$content = ba_cheetah_filesystem()->file_get_contents( $file );

			if ($this->settings->mobile_breakpoint == 'desktop') {
				$css = $content;
			}
			else {
				$global = BACheetahModel::get_global_settings();
				$media = $this->settings->mobile_breakpoint == 'medium' ? $global->medium_breakpoint : $global->responsive_breakpoint;
				$css = '@media (max-width: ' . $media . 'px) { ' . $content . ' }';
			}
			
		}
		return $css;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahMenuModule', array(
	'general' => array(
		'title'    => __('Menu', 'ba-cheetah'),
		'sections' => array(
			'menu' => array(
				'title' => '',
				'fields' => array(
					'menu' => array(
						'type' => 'select',
						'label' => __('Select a menu', 'ba-cheetah'),
						'options' => array(),
					),
					'raw_manage_menus' => array(
						'type' => 'raw',
						'content' => '<br><br>
							<a href="' . admin_url('nav-menus.php') . '" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
							. __('Manage Wordpress menus', 'ba-cheetah') .
							'<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>' .
							'</a>'
					)
				)
			),
		),
	),
	'style' => array(
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'navbar' => array(
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
							'selector'  => '{node} .ba-module__menu .menu__container > ul, {node} .ba-module__menu .menu__mobile',
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
							'selector'  => '{node} .ba-module__menu .menu__container > ul',
							'property'	=> 'justify-content'
						),
						'default' => 'center'
					),
					'navbar_height' => array(
						'type'    => 'unit',
						'label'   => __('Height', 'ba-cheetah'),
						'help' 	  => __('This size will also be used for the height of the mobile menu', 'ba-cheetah'),
						'default' => '70',
						'default_unit' => 'px',
						'slider'  => array(
							'min'  => 20,
							'max'  => 150,
							'step' => 1,
						),
					),
					'navbar_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul',
						)
					),
				),
			),
			'menu' => array(
				'title' => __('Menu items', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'menu_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'units'      => array('px'),
						'default' 	 => '20',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__menu .menu__container > ul > li.menu-item a',
							'property' => 'padding',
						),
					),
					'menu_distance' => array(
						'type'    => 'unit',
						'label'   => __('Spacing', 'ba-cheetah'),
						'default' => '1',
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
							'selector' => '{node} .ba-module__menu .menu__container > ul',
							'property' => 'gap',
						),
					),
					'menu_background' => array(
						'type' => 'color',
						'label'		=> __('Background', 'ba-cheetah'),
						'show_alpha'  => true,
						'show_reset'  => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item',
							'property'  => 'background-color',
						)
					),
					'menu_background_hover' => array(
						'type' => 'color',
						'label'		=> __('Background on Hover', 'ba-cheetah'),
						'default' => '102a89',
						'show_reset' => true,
						'show_alpha'  => true,
						'preview' => array(
							'type' => 'none'
						)
					),
					'menu_background_active' => array(
						'type' => 'color',
						'label'		=> __('Background on Active', 'ba-cheetah'),
						'help' => __('The active item is the one whose link points to the current page', 'ba-cheetah'),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item.current-menu-item',
							'property'  => 'background-color',
						)
					),
					'menu_color' => array(
						'type' => 'color',
						'label'		=> __('Color', 'ba-cheetah'),
						'default' => 'f6f6fc',
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item',
							'property'  => 'color',
						)
					),
					'menu_color_hover' => array(
						'type' => 'color',
						'label'		=> __('Color on Hover', 'ba-cheetah'),
						'preview' => array(
							'type' => 'none'
						)
					),
					'menu_typography' => array(
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
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item',
						),
						'default' => array(
							'font_family' => 'Helvetica',
							'font_weight' => '700',
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

				)
			),
			'submenu' => array(
				'title' => __('Submenu', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'submenu_border' => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul li.menu-item .sub-menu',
						),
						'responsive' => true,
						'default' => array(
							'style' => 'solid',
							'color' => '4054b2',
							'width' => array(
								'top' => '0',
								'right' => '0',
								'bottom' => '5',
								'left' => '5',
							),
							'radius' => array(
								'top_left' => '0',
								'top_right' => '0',
								'bottom_left' => '6',
								'bottom_right' => '6',
							),
							'shadow' => array(
								'color' => 'rgba(5,0,0,0.22)',
								'horizontal' => '0',
								'vertical' => '3',
								'blur' => '6',
								'spread' => '0',
							),
						),
					),
					'submenu_min_width' => array(
						'type'    => 'unit',
						'label'   => __('Width', 'ba-cheetah'),
						'default' => '200',
						'default_unit' => 'px',
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
							'selector' => '{node} .ba-module__menu .menu__container > ul > li.menu-item .sub-menu',
							'property' => 'min-width',
						),
					),
				),
			),
			'submenu_items' => array(
				'title' => __('Submenu items', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'submenu_padding'      => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'units'      => array('px'),
						'default' 	 => '20',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__menu .menu__container > ul > li.menu-item .sub-menu li.menu-item a',
							'property' => 'padding',
						),
					),
					'submenu_background' => array(
						'type' => 'color',
						'label'		=> __('Background', 'ba-cheetah'),
						'show_reset' => true,
						'show_alpha'  => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item .sub-menu li.menu-item',
							'property'  => 'background-color',
						)
					),
					'submenu_background_hover' => array(
						'type' => 'color',
						'label'		=> __('Background on Hover', 'ba-cheetah'),
						'default' => 'f6f6fc',
						'show_reset' => true,
						'show_alpha'  => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item .sub-menu li.menu-item:hover',
							'property'  => 'background-color',
						)
					),
					'submenu_background_active' => array(
						'type' => 'color',
						'label'		=> __('Background on Active', 'ba-cheetah'),
						'show_reset' => true,
						'show_alpha'  => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item .sub-menu li.menu-item.current-menu-item',
							'property'  => 'background-color',
						)
					),
					'submenu_color' => array(
						'type' => 'color',
						'label'		=> __('Color', 'ba-cheetah'),
						'default' => '4054b2',
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item .sub-menu li.menu-item',
							'property'  => 'color',
						)
					),
					'submenu_typography' => array(
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
							'selector'  => '{node} .ba-module__menu .menu__container > ul > li.menu-item .sub-menu li.menu-item',
						),
						'default' => array(
							'font_family' => 'Helvetica',
							'font_weight' => '400',
							'font_size' => array(
								'length' => '15',
								'unit' => 'px',
							),
						)
					),
				)
			)
		)
	),
	'mobile' => array(
		'title' => __('Mobile', 'ba-cheetah'),
		'sections' => array(
			'mobile_breakpoint_section' => array(
				'title' => '',
				'fields' => array(
					'mobile_breakpoint' => array(
						'type' => 'button-group',
						'label' => __('Breakpoint', 'ba-cheetah'),
						'default' => 'responsive',
						'help' => __('Breakpoint where the menu will be transformed into its mobile version', 'ba-cheetah'),
						'options' => array(
							'responsive' => __('Mobile', 'ba-cheetah'),
							'medium' => __('Tablet', 'ba-cheetah'),
							'desktop' =>  __('Desktop', 'ba-cheetah'),
						)
					),
				)
			),
			'mobile_hamburger' => array(
				'title' => '',
				'fields' => array(
					'hamburger_icon' => array(
						'type' => 'icon',
						'label' => __('Hamburger icon', 'ba-cheetah'),
						'default' => 'fas fa-bars',
					),
					'hamburger_background' => array(
						'type' => 'color',
						'label'		=> __('Icon background', 'ba-cheetah'),
						'default' => '352c24',
						'show_reset' => true,
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '.menu__mobile .menu__mobile-hamburger',
							'property'  => 'background-color',
						)
					),
					'hamburger_color' => array(
						'type' => 'color',
						'label'		=> __('Icon color', 'ba-cheetah'),
						'default' => 'ffffff',
						'show_reset' => true,
						'show_alpha' => true,
						'preview' => array(
							'type' => 'css',
							'selector'  => '.menu__mobile .menu__mobile-hamburger',
							'property'  => 'color',
						)
					),
				)
			),
			'mobile_logo' => array(
				'title' => '',
				'fields' => array(
					'logo'        => array(
						'type'        => 'photo',
						'label'       => __('Logo', 'ba-cheetah'),
						'show' => array(
							'fields' => array('logo_size', 'logo_position')
						)
					),
					'logo_size' => array(
						'type'    => 'unit',
						'label'   => __('Height', 'ba-cheetah'),
						'default' => '90',
						'default_unit' => '%',
						'units' => array('%'),
						'slider'  => true
					),
					'logo_position' => array(
						'type' => 'button-group',
						'label' => __('Logo position', 'ba-cheetah'),
						'default' => 'row',
						'options' => array(
							'row' => __('Left', 'ba-cheetah'),
							'row-reverse' => __('Right', 'ba-cheetah'),
						)
					),
				)
			)
		)
	),
));
