<?php

/**
 * @class BACheetahTabModule
 */
class BACheetahTabModule extends BACheetahModule
{
	const DEFAULT_CONTENT = '<p><span style="font-size: 16px;"><strong><em>I am a awesome element! I\'m looking forward to you editing me!</em></strong></span> </p>'
		. '<p><span style="color: #999999;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
		. 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate '
		. 'velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span></p>';

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Tabs', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Layout', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'tabs.svg',
		));

		$this->add_js('jquery-ui-tabs');
	}

	public function update( $settings ){
		return $this->auto_generate_form_node_ids($settings, 'items', $this->node);
	}

	public function invert_alignment() {
		switch ($this->settings->nav_item_align) {
			case 'flex-end'		: return 'flex-start'	; break;
			case 'flex-start'	: return 'flex-end'		; break;
			default				: return 'center'		; break;
		}
	}

	public function get_item_alignment() {
		$rules = [];

		switch ($this->settings->icon_position) {
			case 'row'				: $rules = ['align-items: center'	 , 'justify-content: ' . $this->settings->nav_item_align]; 	break;
			case 'row-reverse'		: $rules = ['align-items: center'	 , 'justify-content: ' . $this->invert_alignment()]; 		break;
			case 'column'			: $rules = ['justify-content: center', 'align-items: ' . $this->settings->nav_item_align]; 		break;
			case 'column-reverse'	: $rules = ['justify-content: center', 'align-items: ' . $this->settings->nav_item_align];		break;
		}
		return join(';', $rules);
	}
}

/**
 * Subform tab item
 */
BACheetah::register_settings_form('tab_item', array(
	'title' => __('Tab item', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => __('General', 'ba-cheetah'),
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						'dragdrop'         => array(
							'label' => __('Drag and Drop', 'ba-cheetah'),
							'type' => 'button-group',
							'enabled' => BA_CHEETAH_PRO,
							'default' => 'no',
							'options' => array(
								'yes' => __('Yes', 'ba-cheetah'),
								'no' => __('No', 'ba-cheetah'),
							),
							'toggle' => array(
								'no' => array(
									'fields' => array('body')
								),
							)
						),
						'row_node_id'         => array(
							'type'          => 'hidden',
						),
						'title'         => array(
							'type'          => 'text',
							'label'         => __('Title', 'ba-cheetah'),
							'default'       => ''
						),
						'icon' => array(
							'type'          => 'icon',
							'label'         => __('Icon', 'ba-cheetah'),
							'show_remove'   => true
						),
						'body'         => array(
							'type'          => 'editor',
							'label'         => __('Content', 'ba-cheetah'),
							'default'       => ''
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
BACheetah::register_module(
	'BACheetahTabModule',
	array(
		'items' => array(
			'title' => __('Tab items', 'ba-cheetah'),
			'sections' => array(
				'items' => array(
					'title' => '',
					'fields' => array(
						"items" => array(
							'type'          => 'form',
							'label'         => __('Tab item', 'ba-cheetah'),
							'form'          => 'tab_item',
							'preview_text'  => 'title',
							'multiple'      => true,
							'limit'         => 15,
							'default' 		=> array(
								array('title' => __('Tab title', 'ba-cheetah') . ' 1', 'icon' => 'fas fa-caret-right', 'body' => BACheetahTabModule::DEFAULT_CONTENT),
								array('title' => __('Tab title', 'ba-cheetah') . ' 2', 'icon' => 'fas fa-caret-right', 'body' => BACheetahTabModule::DEFAULT_CONTENT),
								array('title' => __('Tab title', 'ba-cheetah') . ' 3', 'icon' => 'fas fa-caret-right', 'body' => BACheetahTabModule::DEFAULT_CONTENT),
							)
						),
					)
				),
				'geral' => array(
					'title' => __('Settings', 'ba-cheetah'),
					'fields' => array(
						'direction' => array(
							'type' => 'button-group',
							'label' => __('Navigation direction', 'ba-cheetah'),
							'options' => array(
								'vertical' => __('Vertical', 'ba-cheetah'),
								'horizontal' => __('Horizontal', 'ba-cheetah')
							),
							'default' => 'horizontal',
							'toggle' => array(
								'vertical'  => array(
									'fields' => array('nav_vertical_min_width'),
								),
							)
						),
						'nav_vertical_min_width' => array(
							'type' => 'unit',
							'label' => __('Navigation min width', 'ba-cheetah'),
							'units' => array('px', '%'),
							'default_unit' => '%',
							'default' => '30',
							'slider' => array(
								'px'    => array(
									'min' => 0,
									'max' => 500,
									'step'    => 10,
								),
								'%'    => array(
									'min' => 0,
									'max' => 80,
									'step' => 1,
								),
							),
							'preview'    => array(
								'type'          => 'css',
								'selector'      => '.ba-module__tab.tab--vertical .tab__nav',
								'property'      => 'min-width',
							),
						),
						'grow' => array(
							'type' => 'select',
							'label' => __('Grow navigation items', 'ba-cheetah'),
							'help' => __('If you set it to yes, the navigation items in the tabs will occupy the total width of the header, being expanded proportionally', 'ba-cheetah'),
							'options' => array(
								0 => 'Não',
								1 => 'Sim'
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li',
								'property'	=> 'flex-grow'
							),
							'default' => 1,
							'toggle' => array(
								0  => array(
									'fields' => array('justify'),
								),
							)
						),
						'justify' => array(
							'type' => 'align',
							'responsive' => true,
							'label' => __('Navigation alignment', 'ba-cheetah'),
							'values' => array(
								'left' => 'flex-start',
								'center' => 'center',
								'right' => 'flex-end'
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav',
								'property'	=> 'justify-content'
							),
							'default' => 'flex-start'
						),
					)
				),
			)
		),
		'styles'      => array(
			'title' => __('Style', 'ba-cheetah'),
			'sections' => array(
				/*
				This configuration doesn't make a lot of sense
				'header' => array(
					'title' => 'Header',
					'fields' => array(
						'nav_background' => array(
							'type'          => 'color',
							'label'         => 'Background',
							'default'       => 'ffffff00',
							'show_reset'    => true,
							'show_alpha'    => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav',
								'property'	=> 'background-color'
							),
						),
						'nav_border'  => array(
							'type'       => 'border',
							'label'      => __('Border', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav',
							),
						),
					)
				),
				*/
				'nav_item' => array(
					'title' => __('Navigation item', 'ba-cheetah'),
					'fields' => array(
						'nav_item_background_type' => array(
							'label' => __('Background type', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'solid',
							'options' => array(
								'solid' => __('Solid', 'ba-cheetah'),
								'gradient' => __('Gradient', 'ba-cheetah'),
							),
							'toggle' => array(
								'solid' => array('fields' => array('nav_item_background')),
								'gradient' => array('fields' => array('nav_item_background_gradient'))
							)
						),
						'nav_item_background' => array(
							'type'          => 'color',
							'label'         => __('Background', 'ba-cheetah'),
							'show_alpha'    => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li',
								'property'	=> 'background-color'
							),
						),
						'nav_item_background_gradient' => array(
							'type'    => 'gradient',
							'label' => __('Gradient Background', 'ba-cheetah'),
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li',
								'property'  => 'background-image',
							),
						),
						'nav_item_text_color' => array(
							'type'        => 'color',
							'label'       => __('Text Color', 'ba-cheetah'),
							'show_reset'  => true,
							'show_alpha'  => true,
							'default' => '9498b2',
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li',
								'property'	=> 'color'
							),
						),
						'nav_item_align' => array(
							'type' => 'align',
							'label' => __('Alignment', 'ba-cheetah'),
							'default' => 'center',
							'values'  => array(
								'left'   => 'flex-start',
								'center' => 'center',
								'right'  => 'flex-end',
							),
						),
						'nav_item_typography' => array(
							'type'       => 'typography',
							'label'      => __('Typography', 'ba-cheetah'),
							'responsive' => true,
							'disabled' => array(
								'responsive' => array('text_align'),
								'medium' => array('text_align'),
								'default' => array('text_align'),
							),
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li',
							),
							'default' => array(
								'font_family' => 'Helvetica',
								'font_weight' => '700',
								'font_size' => array(
									'length' => '15',
									'unit' => 'px',
								),
								'line_height' => array(
									'length' => '40',
									'unit' => 'px',
								)
							)
						),
						'nav_item_border'  => array(
							'type'       => 'border',
							'label'      => __('Border', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li',
							),
						),
						'nav_item_padding' => array(
							'type'       => 'dimension',
							'label'      => __('Padding', 'ba-cheetah'),
							'slider'     => true,
							'responsive' => true,
							'default'	 => '15',
							'units'      => array('px'),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.ba-module__tab .tab__nav li',
								'property' => 'padding',
							),
						),
					)
				),
				'nav_item_active' => array(
					'title' => __('Navigation item active', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'nav_item_active_background_type' => array(
							'label' => __('Background type', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'solid',
							'options' => array(
								'solid' => __('Solid', 'ba-cheetah'),
								'gradient' => __('Gradient', 'ba-cheetah'),
							),
							'toggle' => array(
								'solid' => array('fields' => array('nav_item_active_background')),
								'gradient' => array('fields' => array('nav_item_active_background_gradient'))
							)
						),
						'nav_item_active_background' => array(
							'type'          => 'color',
							'label'         => __('Background', 'ba-cheetah'),
							'show_reset'    => true,
							'show_alpha'    => true,
							'default'		=> '4054b2',
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li.ui-tabs-active',
								'property'	=> 'background-color',
								'important' => true,
							),
						),
						'nav_item_active_background_gradient' => array(
							'type'    => 'gradient',
							'label' => __('Gradient Background', 'ba-cheetah'),
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li.ui-tabs-active',
								'property'  => 'background-image',
								'important' => true,
							),
						),
						'nav_item_active_text_color' => array(
							'type'        => 'color',
							'label'       => __('Text Color', 'ba-cheetah'),
							'show_reset'  => true,
							'default'	  => 'fcfcf6',
							'show_alpha'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li.ui-tabs-active',
								'property'	=> 'color',
								'important' => true,
							),
						),
						'nav_item_active_border'  => array(
							'type'       => 'border',
							'label'      => __('Border', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__nav li.ui-tabs-active',
								'important' => true,
							),
							/*
							'default' => array(
								'style' => 'solid',
								'color' => '0080fc',
								'width' => array(
									'bottom' => '2'
								)
							)
							*/
						),
					)
				),
				'icon' => array(
					'title' => __('Icon', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'icon_distance_from_text' => array(
							'type' => 'unit',
							'label' => __('Distance from text', 'ba-cheetah'),
							'units' => array('px', 'em'),
							'default_unit' => 'em',
							'default' => '1',
							'slider' => true,
							'preview' => array(
								'type' => 'css',
								'selector' => '{node} .tab__nav li a',
								'property' => 'gap'
							)
						),
						'icon_position' => array(
							'type' => 'select',
							'label' => __('Position', 'ba-cheetah'),
							'default' => 'row',
							'options' => array(
								'column' => __('Top', 'ba-cheetah'),
								'column-reverse' => __('Bottom', 'ba-cheetah'),
								'row' => __('Left', 'ba-cheetah'),
								'row-reverse' => __('Right', 'ba-cheetah'),
							),
							/*
							'preview' => array(
								'type' => 'css',
								'selector' => '{node} .tab__nav li a',
								'property' => 'flex-direction'
							)
							*/
						),
						'icon_size' => array(
							'type' => 'unit',
							'label' => __('Size', 'ba-cheetah'),
							'units' => array('px', 'em'),
							'default_unit' => 'em',
							'default' => '1',
							'slider' => true,
							'preview' => array(
								'type' => 'css',
								'selector' => '{node} .tab__nav li a i',
								'property' => 'font-size'
							)
						),
					),
				),
				'body' => array(
					'title' => __('Content', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'body_background_type' => array(
							'label' => __('Background type', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'solid',
							'options' => array(
								'solid' => __('Solid', 'ba-cheetah'),
								'gradient' => __('Gradient', 'ba-cheetah'),
							),
							'toggle' => array(
								'solid' => array('fields' => array('body_background')),
								'gradient' => array('fields' => array('body_background_gradient'))
							)
						),
						'body_background' => array(
							'type'          => 'color',
							'label'         => __('Background', 'ba-cheetah'),
							'show_reset'    => true,
							'show_alpha'    => true,
							'default' 		=> 'fcfcfc',
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__body',
								'property'	=> 'background-color'
							),
						),
						'body_background_gradient' => array(
							'type'    => 'gradient',
							'label' => __('Gradient Background', 'ba-cheetah'),
							'preview'     => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__body',
								'property'  => 'background-image',
							),
						),
						'body_text_color' => array(
							'type'        => 'color',
							'label'       => __('Text Color', 'ba-cheetah'),
							'default'     => '7c7c7c',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__body',
								'property'	=> 'color'
							),
						),
						'body_typography' => array(
							'type'       => 'typography',
							'label'      => __('Typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__body',
							),
						),
						'body_border'  => array(
							'type'       => 'border',
							'label'      => __('Border', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '.ba-module__tab .tab__body',
							),
							'default' => array(
								'style' => 'solid',
								'color' => 'd6d6d6',
								'width' => array(
									'top' => '0',
									'right' => '0',
									'bottom' => '1',
									'left' => '0',
								),
								'radius' => array(
									'top_left' => '0',
									'top_right' => '0',
									'bottom_left' => '20',
									'bottom_right' => '20',
								),
							),
						),

						'body_padding' => array(
							'type' => 'unit',
							'label' => __('Padding', 'ba-cheetah'),
							'default' => 20,
							'units' => array('px'),
							'default_unit' => 'px',
							'slider' => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
							'preview'    => array(
								'type' => 'css',
								'selector' => '.ba-module__tab .tab__body',
								'property' => 'padding',
							),
						),
					)
				)
			)
		),
	)
);
