<?php

/**
 * @class BACheetahAccordionModule
 */
class BACheetahAccordionModule extends BACheetahModule
{

	const DEFAULT_CONTENT = '<p><span style="font-size: 16px;"><strong><em>I am a awesome element! I\'m looking forward to you editing me!</em></strong></span> </p><hr />'
		. '<p><span style="color: #999999;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
		. 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate '
		. 'velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</span></p>';

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Accordion', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Layout', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'accordion.svg',
		));

		$this->add_js('jquery-ui-accordion');
	}

	public function update( $settings ){
		return $this->auto_generate_form_node_ids($settings, 'items', $this->node);
	}

	public function accordion_item_template($title = '', $body = '', $dragdrop = 'no', $nodeID = null, $parentID = null, $settings = null, $key = null)
	{
		?>
		<header class="accordion__title">
			<span> <?php echo esc_html($title) ?> </span>
		</header>
		<div class="accordion__body" style="display: none">
			<?php
			
				if('no' === $dragdrop) {
					echo wp_kses_post($body);
				} else {
					BACheetah::render_row_to_module($nodeID, $parentID);
				}
			?>
		</div> 
		<?php 
	}
}

/**
 * Subform accordion item
 */
BACheetah::register_settings_form('accordion_item', array(
	'title' => __('Accordion item', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => '',
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
							),
							'preview' => array(
								'type' => 'refresh'
							)
						),
						'title'         => array(
							'type'          => 'text',
							'label'         => 'Title',
							'default'       => 'Accordion header'
						),
						'row_node_id'         => array(
							'type'          => 'hidden',
						),
						'body'         => array(
							'type'          => 'editor',
							'label'         => __('Content', 'ba-cheetah'),
							'default'       => ''
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
BACheetah::register_module('BACheetahAccordionModule', array(
	'items' => array(
		'title' => __('Items', 'ba-cheetah'),
		'sections' => array(
			'items' => array(
				'title' => '',
				'fields' => array(
					"items" => array(
						'type'          => 'form',
						'label'         => __('Accordion item', 'ba-cheetah'),
						'form'          => 'accordion_item',
						'preview_text'  => 'title',
						'multiple'      => true,
						'limit'         => 15,
						'default' 		=> array(
							array('title' => __('Accordion item', 'ba-cheetah') . ' 1', 'body' => BACheetahAccordionModule::DEFAULT_CONTENT),
							array('title' => __('Accordion item', 'ba-cheetah') . ' 2', 'body' => BACheetahAccordionModule::DEFAULT_CONTENT),
							array('title' => __('Accordion item', 'ba-cheetah') . ' 3', 'body' => BACheetahAccordionModule::DEFAULT_CONTENT),
						)
					),
				)
			),
			'geral' => array(
				'title' => __('Settings', 'ba-cheetah'),
				'fields' => array(
					'active_index' => array(
						'type' => 'select',
						'label' => __('Expand first item', 'ba-cheetah'),
						'default' => '0',
						'options' => array(
							'0' => __('Yes', 'ba-cheetah'),
							'false' => __('No', 'ba-cheetah'),
						)
					),
					'faq_mode' => array(
						'type' => 'button-group',
						'label' => __('FAQ Mode', 'ba-cheetah'),
						'help' => __('If you want when the target item is clicked, the others close.', 'ba-cheetah'),
						'default' => '1',
						'options' => array(
							'1' => __('Yes', 'ba-cheetah'),
							'0' => __('No', 'ba-cheetah'),
						)
					),
					'space_between_items' => array(
						'type'    => 'unit',
						'label'   => __('Space between items', 'ba-cheetah'),
						'default' => '15',
						'default_unit' => 'px',
						'slider'  => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'units'   => array('px'),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.ba-module__accordion .accordion__title:not(:first-of-type), .ba-module__accordion .accordion__content.ui-accordion:not(:first-of-type)',
							'property' => 'margin-top',
						),
					),
				)
			),
		)
	),
	'styles'      => array(
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'header' => array(
				'title' => 'Header',
				'fields' => array(
					'header_background' => array(
						'type'          => 'color',
						'label'         => __('Background', 'ba-cheetah'),
						'default'		=> 'f6f6fc',
						'show_reset'    => true,
						'show_alpha'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-module__accordion .accordion__title',
							'property'	=> 'background-color'
						),
					),
					'header_text_color' => array(
						'type'        => 'color',
						'label'       => __('Text Color', 'ba-cheetah'),
						'default'     => '4054b2',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-module__accordion .accordion__title',
							'property'	=> 'color'
						),
					),
					'header_typography' => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'disabled' 	 => array(
							'responsive' => array('text_shadow'),
							'medium' => array('text_shadow'),
							'default' => array('text_shadow'),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-module__accordion .accordion__title',
						),
						'default' => array(
							'font_family' => 'Default',
							'font_weight' => '500',
							array(
								'length' => '15',
								'unit' => 'px',
							),
						)
					),
					'header_border'  => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-module__accordion .accordion__title',
							'important' => true,
						),
						'default' => array(
							'style' => 'solid',
							'color' => '4b71d1',
							'width' => array(
								'top' => '0',
								'right' => '0',
								'bottom' => '0',
								'left' => '6',
							),
							'radius' => array(
								'top_left' => '3',
								'top_right' => '6',
								'bottom_left' => '0',
								'bottom_right' => '0',
							),
						),
					),
					'header_padding' => array(
						'type'       => 'dimension',
						'label'      => __('Padding', 'ba-cheetah'),
						'slider'     => true,
						'responsive' => true,
						'units'      => array('px'),
						'default' 	 => '20',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.ba-module__accordion .accordion__title',
							'property' => 'padding',
						),
					),
				)
			),
			'icon' => array(
				'title' => __('Icon', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'icon_position' => array(
						'type' => 'select',
						'label' => __('Position', 'ba-cheetah'),
						'options' => array(
							'row' => __('Left', 'ba-cheetah'),
							'row-reverse' => __('Right', 'ba-cheetah'),
						),
						'default' => 'row-reverse'
					),
					'icon' => array(
						'type'          => 'icon',
						'label'         => __('Icon', 'ba-cheetah'),
						'show_remove'   => true,
						'default' => 'fas fa-plus'
					),
					'icon_active' => array(
						'type'          => 'icon',
						'label'         => __('Icon active', 'ba-cheetah'),
						'show_remove'   => true,
						'default' => 'fas fa-times'
					),
				)
			),
			'body' => array(
				'title' => 'Body',
				'collapsed' => true,
				'fields' => array(
					'body_background' => array(
						'type'          => 'color',
						'label'         => __('Background', 'ba-cheetah'),
						'show_reset'    => true,
						'default'		=> 'fcfcfc',
						'show_alpha'    => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-module__accordion .accordion__body',
							'property'	=> 'background-color'
						),
					),
					'body_text_color' => array(
						'type'        => 'color',
						'label'       => __('Text Color', 'ba-cheetah'),
						'default'     => '7a7a7a',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-module__accordion .accordion__body',
							'property'	=> 'color'
						),
					),
					'body_typography' => array(
						'type'       => 'typography',
						'label'      => __('Typography', 'ba-cheetah'),
						'responsive' => true,
						'disabled' 	 => array(
							'responsive' => array('text_shadow'),
							'medium' => array('text_shadow'),
							'default' => array('text_shadow'),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-module__accordion .accordion__body',
						),
						'default' => array(
							'font_family' => 'Default',
							'font_weight' => 'default',
							'font_size' => array(
								'length' => '16',
								'unit' => 'px',
							),
						)
					),
					'body_border'  => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-module__accordion .accordion__body',
							'important' => true,
						),
					),
					'body_margin' => array(
						'type'        => 'dimension',
						'label'       => __('Margin', 'ba-cheetah'),
						'description' => 'px',
						'responsive' => true,
						'default' => 0,
						'slider' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.ba-module__accordion .accordion__body',
							'property' => 'margin',
						)
					),
				)
			)
		)
	),
));
