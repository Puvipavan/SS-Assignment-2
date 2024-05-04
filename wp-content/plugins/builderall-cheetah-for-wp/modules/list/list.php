<?php

/**
 * @class BACheetahListModule
 */
class BACheetahListModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('List', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Basic', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'list.svg',
		));

	}

	/**
	 * Escape and prevent line wrap in list item
	 *
	 * @param string $text
	 * @return string
	 */
	public static function ksesListItem($text = '') {
		$allowed_tags = wp_kses_allowed_html('post');
		unset($allowed_tags['ul'], $allowed_tags['ol'], $allowed_tags['li']);
		return wp_kses($text, $allowed_tags);
	}
}

/**
 * Subform accordion item
 */
BACheetah::register_settings_form('list_item', array(
	'title' => __('List item', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => '',
			'sections'      => array(
				'general'       => array(
					'title'         => '',
					'fields'        => array(
						/*
						'dragdrop'         => array(
							'label' => __('Drag and Drop', 'ba-cheetah'),
							'type' => 'button-group',
							'default' => 'no',
							'options' => array(
								'yes' => __('Yes', 'ba-cheetah'),
								'no' => __('No', 'ba-cheetah'),
							),
							'toggle' => array(
								'no' => array(
									'fields' => array('icon', 'link', 'text')
								),
								'yes' => array(
									'fields' => array('show_icon')
								)
							)
						),
						'row_node_id'         => array(
							'type'          => 'hidden',
							'default'       =>  BACheetahModel::generate_node_id()
						),
						'show_icon'         => array(
							'type'          => 'button-group',
							'label'         => __('Show Icon', 'ba-cheetah'),
							'default' => 'yes',
							'options' => array(
								'yes' => __('Yes', 'ba-cheetah'),
								'no' => __('No', 'ba-cheetah'),
							)
						),
						*/
						'icon'         => array(
							'type'          => 'icon',
							'label'         => __('Icon', 'ba-cheetah'),
							'show_remove'   => true,
						),
						'link'         => array(
							'type'          => 'link',
							'label'         => __('Text', 'ba-cheetah'),
							'label'         => 'Link',
							'can_remove'	=> true,
							'show_target'   => true,
						),
						'text'         => array(
							'type'          => 'editor',
							'media_buttons'	=> false,
							'default'       => __('List item', 'ba-cheetah'),
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
BACheetah::register_module('BACheetahListModule', array(
	'items' => array(
		'title' => __('Items', 'ba-cheetah'),
		'sections' => array(
			'items' => array(
				'title' => '',
				'fields' => array(
					"items" => array(
						'type'          => 'form',
						'label'         => __('List item', 'ba-cheetah'),
						'form'          => 'list_item',
						'preview_text'  => 'text',
						'multiple'      => true,
						'limit'         => 15,
						'default' 		=> array(
							array('text' => 'List item 1 | Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'),
							array('text' => 'List item 2 | Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'),
							array('text' => 'List item 3 | Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'),
						)
					),
					'default_icon' => array(
						'type' => 'icon',
						'label' => __('Default icon', 'ba-cheetah'),
						'default' => 'fas fa-bookmark',
						'show_remove'   => true,
						'help' => __('Icon that will be displayed if you do not define icons for the items', 'ba-cheetah')
					),
				)
			),
		)
	),
	'styles'      => array(
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'general' => array(
				'title' => __('General', 'ba-cheetah'),
				'fields' => array(
					'space_between_items' => array(
						'type' => 'unit',
						'label' => __('Spacing', 'ba-cheetah'),
						'default' => '30',
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
					),
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
							'selector'  => '{node} ul .list__item, {node} ul .list__divider',
							'property'	=> 'justify-content'
						),
						'default' => 'flex-start'
					),
				)
			),
			'icon' => array(
				'title' => __('Icon', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'icon_size' => array(
						'type' => 'unit',
						'label' =>  __('Size', 'ba-cheetah'),
						'default' => 20,
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
							'selector' => '{node} ul .list__item .list__icon i',
							'property' => 'font-size',
						),
					),
					'icon_position' => array(
						'type' => 'select',
						'label' => __('Position', 'ba-cheetah'),
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
							'selector' => '{node} ul .list__item',
							'property' => 'flex-direction'
						)
					),
					'icon_color' => array(
						'type'          => 'color',
						'label'         => __('Color', 'ba-cheetah'),
						'default'       => '4054b2',
						'show_reset'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} ul .list__item .list__icon i',
							'property'	=> 'color'
						),
					),
					'icon_color_hover' => array(
						'type'          => 'color',
						'label'         => __('Color on Hover', 'ba-cheetah'),
						'default'       => '0080fc',
						'show_reset'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} ul .list__item .list__icon i:hover',
							'property'	=> 'color'
						),
					),
				)
			),
			'text' => array(
				'title' => __('Text', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'icon_text_gap' => array(
						'type' => 'unit',
						'label' => __('Text indent', 'ba-cheetah'),
						'default' => '20',
						'units' => array('px', '%'),
						'default_unit' => 'px',
						'help' => __('Spacing between icon and text', 'ba-cheetah'),
						'slider' => array(
							'min' => 0,
							'max' => 100,
							'step' => 1,
						),
						'preview'    => array(
							'type' => 'css',
							'selector' => '{node} ul .list__item',
							'property' => 'gap',
						),
					),
					'text_color' => array(
						'type'          => 'color',
						'label'         => __('Color', 'ba-cheetah'),
						'default'       => '7a7a7a',
						'show_reset'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} ul .list__item .list__text',
							'property'	=> 'color'
						),
					),
					'text_typography' => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'default'	 => array(
							'font_family' => "Helvetica",
							'font_weight' => 400,
							'text_align' => 'left',
							'font_size' => array(
								'unit' => 'px',
								'length' => '16'
							)
						),
						'disabled' => array(
							'default' => array('text_align'),
							'medium' => array('text_align'),
							'responsive' => array('text_align')
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} ul .list__item .list__text',
						),
					),
				)
			),
			'divider' => array(
				'title' => __('Divider', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'divider_height' => array(
						'type' => 'unit',
						'label' =>  __('Height', 'ba-cheetah'),
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
							'selector' => '{node} ul .list__divider hr',
							'property' => 'border-top-width',
						),
					),
					'divider_width' => array(
						'type' => 'unit',
						'label' =>  __('Width', 'ba-cheetah'),
						'default' => 100,
						'responsive' => true,
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
							'selector' => '{node} ul .list__divider hr',
							'property' => 'flex-basis',
						),
					),
					'divider_style' => array(
						'type' => 'select',
						'label' =>  __('Style', 'ba-cheetah'),
						'default' => 'solid',
						'options' => array(
							'solid' => __('Solid', 'ba-cheetah'),
							'dashed' => __('Dashed', 'ba-cheetah'),
							'dotted' => __('Dotted', 'ba-cheetah'),
							'double' => __('Double', 'ba-cheetah'),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector' => '{node} ul .list__divider hr',
							'property'	=> 'border-top-style'
						),
					),
					'divider_color' => array(
						'type'          => 'color',
						'label'         => __('Color', 'ba-cheetah'),
						'default'       => 'e7ebf7',
						'show_reset'    => true,
						'show_alpha'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector' => '{node} ul .list__divider hr',
							'property'	=> 'border-top-color'
						),
					),
				)
			)
		)
	),
));
