<?php

/**
 * @class BACheetahButtonModule
 */
class BACheetahButtonModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Button', 'ba-cheetah' ),
			'description'     => __( 'A simple call to action button.', 'ba-cheetah' ),
			'category'        => __('Basic', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'button.svg',
		));
	}

	public function get_pixel_attr() {
		return BACheetahTracking::get_pixel_attr($this->settings, 'tracking_pixel_event');
	}

	/**
	 * Returns button link rel based on settings

	 */
	public function get_rel() {
		$rel = array();
		if ( '_blank' == $this->settings->link_target ) {
			$rel[] = 'noopener';
		}
		if ( isset( $this->settings->link_nofollow ) && 'yes' == $this->settings->link_nofollow ) {
			$rel[] = 'nofollow';
		}
		$rel = implode( ' ', $rel );
		if ( $rel ) {
			$rel = ' rel="' . $rel . '" ';
		}
		return $rel;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahButtonModule', array(
	'general' => array(
		'title'    => __( 'Content', 'ba-cheetah' ),
		'sections' => array(
			'general'  => array(
				'title'  => '',
				'fields' => array_merge(array(
					'text'           => array(
						'type'        => 'text',
						'label'       => __( 'Title', 'ba-cheetah' ),
						'default'     => __( 'Click Here', 'ba-cheetah' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '{node} .button__content .button__text',
						),
					),
					'sub_text'           => array(
						'type'        => 'text',
						'label'       => __( 'Subtitle', 'ba-cheetah' ),
						'default'     => '',
						'preview'     => array(
							'type'     => 'text',
							'selector' => '{node} .button__content .button__subtext',
						),
					),
					'icon'           => array(
						'type'        => 'icon',
						'label'       => __( 'Icon', 'ba-cheetah' ),
						'show_remove' => true,
						'show'        => array(
							'fields' => array( 'icon_position'),
							'sections' => array('icon_styles')
						),
					),
					'icon_position'  => array(
						'type'    => 'select',
						'label'   => __( 'Icon Position', 'ba-cheetah' ),
						'default' => 'before',
						'options' => array(
							'before' => __( 'Before Text', 'ba-cheetah' ),
							'after'  => __( 'After Text', 'ba-cheetah' ),
						),
					),
					'click_action'   => array(
						'type'    => 'button-group',
						'label'   => __( 'Action', 'ba-cheetah' ),
						'default' => 'link',
						'options' => array(
							'link'=> __( 'Link', 'ba-cheetah' ),
							'popup' => __( 'Popup', 'ba-cheetah' ),
							'video'=> __( 'Video', 'ba-cheetah' ),
						),
						'toggle'  => array(
							'link'     => array(
								'fields' => array( 'link' ),
							),
							'popup' => array(
								'fields' => array( 'popup_id' ),
							),
							'video' => array(
								'fields' => array( 'video_link' ),
							),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
					'link'           => array(
						'type'          => 'link',
						'label'         => __( 'Link', 'ba-cheetah' ),
						'placeholder'   => __( 'http://www.example.com', 'ba-cheetah' ),
						'show_target'   => true,
						'show_nofollow' => true,
						'show_download' => true,
						'preview'       => array(
							'type' => 'none',
						),
					),
					'popup_id'  => array(
						'type'    => 'select',
						'label'   => __( 'Popup', 'ba-cheetah' ),
						'default' => '',
						'options' => array(), //BACheetahPopups::get_popups_to_select(),
						'preview' => array(
							'type' => 'none',
						),
					),
					'video_link'           => array(
						'type'          => 'text',
						'label'         => __( 'Link', 'ba-cheetah' ),
						'placeholder'   => 'https://www.youtube.com/embed/7xL5HFghzfg',
						'preview'       => array(
							'type' => 'none',
						),
					),
				), BACheetahTracking::module_tracking_fields()),
			),
		),
	),
	'style'   => array(
		'title'    => __( 'Style', 'ba-cheetah' ),
		'sections' => array(
			'style'  => array(
				'title'  => __( 'Size & Align', 'ba-cheetah' ),
				'fields' => array(
					'width'        => array(
						'type'    => 'select',
						'label'   => __( 'Width', 'ba-cheetah' ),
						'default' => 'auto',
						'options' => array(
							'auto'   => _x( 'Auto', 'Width.', 'ba-cheetah' ),
							'full'   => __( 'Full Width', 'ba-cheetah' ),
							'custom' => __( 'Custom', 'ba-cheetah' ),
						),
						'toggle'  => array(
							'auto'   => array(
								'fields' => array( 'align' ),
							),
							'full'   => array(),
							'custom' => array(
								'fields' => array( 'align', 'custom_width' ),
							),
						),
					),
					'custom_width' => array(
						'type'    => 'unit',
						'label'   => __( 'Custom Width', 'ba-cheetah' ),
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
							'selector' => 'a.button__button',
							'property' => 'width',
						),
					),
					'align'        => array(
						'type'       => 'align',
						'label'      => __( 'Align', 'ba-cheetah' ),
						'default'    => 'flex-start',
						'values' => array(
							'left' => 'flex-start',
							'center' => 'center',
							'right' => 'flex-end'
						),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.ba-module__button',
							'property' => 'justify-content',
						),
					),
					'padding'      => array(
						'type'       => 'dimension',
						'label'      => __( 'Padding', 'ba-cheetah' ),
						'responsive' => true,
						'slider'     => true,
						'units'      => array( 'px' ),
						'preview'    => array(
							'type'     => 'css',
							'selector' => 'a.button__button',
							'property' => 'padding',
						),
					),
				),
			),
			'colors' => array(
				'title'  => __( 'Background', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array(
					'bg_color_type' => array(
						'label' => __( 'Background type', 'ba-cheetah' ),
						'type' => 'button-group',
						'default' => 'solid',
						'options' => array(
							'solid' => __( 'Solid', 'ba-cheetah' ),
							'gradient' => __( 'Gradient', 'ba-cheetah' ),
						),
						'toggle' => array(
							'solid' => array('fields' => array('bg_color')),
							'gradient' => array('fields' => array('bg_gradient'))
						)
					),
					'bg_color'          => array(
						'label' => __( 'Background color', 'ba-cheetah' ),
						'type'        => 'color',
						'default'     => '4054b2',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => 'a.button__button',
							'property'  => 'background-color',
						),
					),
					'bg_gradient' => array(
						'type'    => 'gradient',
						'label' => __( 'Gradient Background', 'ba-cheetah' ),
						'preview'     => array(
							'type'      => 'css',
							'selector'  => 'a.button__button',
							'property'  => 'background-image',
						),
					),
					'miau' => array(
						'type' => 'raw',
						'content' => '<hr>'
					),
					'bg_hover_color_type' => array(
						'label'       => __( 'Background Hover Type', 'ba-cheetah' ),
						'type' => 'button-group',
						'default' => 'solid',
						'options' => array(
							'solid' => __( 'Solid', 'ba-cheetah' ),
							'gradient' => __( 'Gradient', 'ba-cheetah' ),
						),
						'toggle' => array(
							'solid' => array('fields' => array('bg_hover_color')),
							'gradient' => array('fields' => array('bg_hover_gradient'))
						)
					),
					'bg_hover_color'    => array(
						'label'       => __( 'Background Hover Color', 'ba-cheetah' ),
						'type'        => 'color',
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'bg_hover_gradient' => array(
						'label'       => __( 'Gradient Background Hover Color', 'ba-cheetah' ),
						'type'    => 'gradient',
						'preview'     => array(
							'type'      => 'none'
						),
					),
					'button_transition' => array(
						'type'    => 'select',
						'label'   => __( 'Background Animation', 'ba-cheetah' ),
						'default' => 'disable',
						'options' => array(
							'disable' => __( 'Disabled', 'ba-cheetah' ),
							'enable'  => __( 'Enabled', 'ba-cheetah' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			),
			'border' => array(
				'collapsed' => true,
				'title'  => __( 'Border', 'ba-cheetah' ),
				'fields' => array(
					'border'             => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'ba-cheetah' ),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => 'a.button__button',
							'important' => true,
						),
					),
					'border_hover_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Border Hover Color', 'ba-cheetah' ),
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
				'title'  => __( 'Title', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array(
					'text_color'       => array(
						'type'        => 'color',
						'label'       => __( 'Text Color', 'ba-cheetah' ),
						'default'     => 'ffffff',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => 'a.button__button .button__text',
							'property'  => 'color',
							'important' => true,
						),
					),
					'text_hover_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Text Hover Color', 'ba-cheetah' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'none',
						),
					),
					'typography'       => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'ba-cheetah' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => 'a.button__button .button__text',
						),
						'default' => array(
							'font_family' => "Helvetica",
							'font_weight' => 400,
							'text_align' => 'center',
							'font_size' => array(
								'unit' => 'px',
								'length' => '18'
							)
						),
					),
				),
			),
			'sub_text'   => array(
				'title'  => __( 'Subtitle', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array(
					'sub_text_color'       => array(
						'type'        => 'color',
						'label'       => __( 'Text Color', 'ba-cheetah' ),
						'default'     => 'fff',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => 'a.button__button .button__subtext',
							'property'  => 'color',
							'important' => true,
						),
					),
					'sub_typography'       => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'ba-cheetah' ),
						'responsive' => true,
						'default' => array(
							"font_size" => array(
								"length" => "12",
								"unit" => "px"
							),
						),
						'preview'    => array(
							'type'     => 'css',
							'selector' => 'a.button__button .button__subtext',
						),
					),
				),
			),
			'icon_styles'  => array(
				'title'  => __( 'Icon', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array(
					'icon_color' => array(
						'label'      => __( 'Icon Color', 'ba-cheetah' ),
						'type'       => 'color',
						'default'    => 'fff',
						'show_reset' => true,
						'show_alpha' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => 'i.button__icon',
							'property'  => 'color',
							'important' => true,
						),
					),
					'icon_size' => array(
						'label'      => __( 'Icon size', 'ba-cheetah' ),
						'type'       => 'unit',
						'default'    => '',
						'default_unit' => 'px',
						'units' => array('px', 'em'),
						'slider' 	=> true,
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => 'i.button__icon',
							'property'  => 'font-size',
						),
					),
					'gap' => array(
						'label'      => __( 'Distance from text', 'ba-cheetah' ),
						'type'       => 'unit',
						'default'    => '',
						'default_unit' => 'px',
						'units' => array('px'),
						'slider' 	=> true,
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .button__button',
							'property'  => 'gap',
						),
					),
				),
			),
		),
	),
));
