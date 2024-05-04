<?php

/**
 * @class BACheetahFlipBoxModule
 */
class BACheetahFlipBoxModule extends BACheetahModule
{

	const DEFAULT_PHOTO = BA_CHEETAH_URL . 'img/demo/card.jpg';
	const DEFAULT_PHOTO_INTERNAL = BA_CHEETAH_URL . 'img/demo/avatar.jpg';
	/**

	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Flip Box', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Layout', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'flip-box.svg',
		));
	}

	/**
	 * Get settings for button module
	 *
	 * @return array
	 */
	public function get_button_settings()
	{
		$settings = array(
			// $this->settings->id is not empty when this element is being consumed by another (eg: posts)
			'id' => !empty($this->settings->id) ? $this->settings->id : 'ba-cheetah-node-' . $this->node,
		);
		foreach ($this->settings as $key => $value) {
			if (strstr($key, 'btn_')) {
				$key              = str_replace('btn_', '', $key);
				$settings[$key] = $value;
			}
		}
		return $settings;
	}

	public static function get_styles_sections_config () {
		return array(
			'title'    => __('Style', 'ba-cheetah'),
			'sections' => array(
				'box' => array(
					'title' => __('Box', 'ba-cheetah'),
					'fields' => array(
                        'min_height'  => array(
                            'type'     => 'unit',
                            'label'    => __( 'Height Minimum', 'ba-cheetah' ),
                            'default'  => '150',
                            'sanitize' => 'absint',
                            'units'    => array( 'px' ),
                            'slider'   => array(
                                'step' => 1,
                                'max'  => 500,
                            ),
                        ),
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
								'selector'  => '{node} .ba-module__flip-box',
								'property'  => 'background-image',
							),
						),
						'box_background' => array(
							'label' => __('Background', 'ba-cheetah'),
							'type' => 'color',
							'default' => 'fff0',
							'show_alpha' => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .ba-module__flip-box',
								'property'	=> 'background'
							),
						),
						'box_padding'      => array(
							'type'       => 'dimension',
							'label'      => __('Padding', 'ba-cheetah'),
							'slider'     => true,
							'responsive' => true,
							'units'      => array('px'),
							'default' 	 => 0,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .ba-module__flip-box',
								'property' => 'padding',
							),
						),
						'box_border'  => array(
							'type' => 'border',
							'label' => __('Border', 'ba-cheetah'),
							'responsive' => true,
							'preview' => array(
								'type' => 'css',
								'selector' => '{node} .ba-module__flip-box',
							),
							'default' => array(
								'shadow' => array(
									'color' => 'c4c4c4',
									'horizontal' => '0',
									'vertical' => '0',
									'blur' => '0',
									'spread' => '0',
								),
								'radius' => array(
									'top_left' => '10',
									'top_right' => '10',
									'bottom_left' => '10',
									'bottom_right' => '10',
								)
							)
						),
					)
				),
				'content' => array(
					'title' => __('Content', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'content_padding' => array(
							'type'       => 'dimension',
							'label'      => __('Padding', 'ba-cheetah'),
							'slider'     => true,
							'responsive' => true,
							'units'      => array('px'),
							'default' 	 => 25,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .ba-module__flip-box .flip-box__content',
								'property' => 'padding',
							),
						),
						'content_spacing_between_items' => array(
							'type'    => 'unit',
							'label'   => __('Space between items', 'ba-cheetah'),
							'default' => '15',
							'default_unit' => 'px',
							'units' => array('px'),
							'slider'  => true,
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .ba-module__flip-box .flip-box__content',
								'property' => 'gap',
							),
						),
						'hr0' => array(
							'type' => 'raw',
							'content' => '<hr>'
						),
						'title_color' => array(
							'type' => 'color',
							'label'		=> __('Title color', 'ba-cheetah'),
							'default' => 'fff',
							'preview' => array(
								'type' => 'css',
								'selector'  => '{node} .flip-box__content .flip-box__title',
								'property'  => 'color',
							)
						),
						'title_typography' => array(
							'type'       => 'typography',
							'label'      => __('Title Typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .flip-box__content .flip-box__title',
							),
							'default' => array(
								'font_family' => 'Montserrat',
								'font_weight' => '700',
								'font_size' => array(
									'length' => '18',
									'unit' => 'px',
								),
							),
						),
						'hr1' => array(
							'type' => 'raw',
							'content' => '<hr>'
						),
						'subtitle_color' => array(
							'type' => 'color',
							'label'		=> __('Subtitle color', 'ba-cheetah'),
							'default' => 'fafafa',
							'preview' => array(
								'type' => 'css',
								'selector'  => '{node} .flip-box__content .flip-box__subtitle',
								'property'  => 'color',
							)
						),
						'subtitle_typography' => array(
							'type'       => 'typography',
							'label'      => __('Subtitle Typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .flip-box__content .flip-box__subtitle',
							),
							'default' => array(
								'font_family' => 'Montserrat',
								'font_weight' => '500',
								'font_size' => array(
									'length' => '13',
									'unit' => 'px',
								),
							),
						),
						'hr2' => array(
							'type' => 'raw',
							'content' => '<hr>'
						),
						'title_color_internal' => array(
							'type' => 'color',
							'label'		=> __('Title color internal', 'ba-cheetah'),
							'default' => 'fafafa',
							'preview' => array(
								'type' => 'css',
								'selector'  => '{node} .flip-box__content.internal .flip-box__title_internal',
								'property'  => 'color',
							)
						),
						'title_typography_internal' => array(
							'type'       => 'typography',
							'label'      => __('Title Typography internal', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .flip-box__content.internal .flip-box__title_internal',
							),
							'default' => array(
								'font_family' => 'Montserrat',
								'font_weight' => '700',
								'font_size' => array(
									'length' => '18',
									'unit' => 'px',
								),
							),
						),
						'hr3' => array(
							'type' => 'raw',
							'content' => '<hr>'
						),
						'subtitle_color_internal' => array(
							'type' => 'color',
							'label'		=> __('Subtitle color internal', 'ba-cheetah'),
							'default' => 'fff',
							'preview' => array(
								'type' => 'css',
								'selector'  => '{node} .flip-box__content.internal .flip-box__subtitle_internal',
								'property'  => 'color',
							)
						),
						'subtitle_typography_internal' => array(
							'type'       => 'typography',
							'label'      => __('Subtitle Typography internal', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .flip-box__content.internal .flip-box__subtitle_internal',
							),
							'default' => array(
								'font_family' => 'Montserrat',
								'font_weight' => '500',
								'font_size' => array(
									'length' => '13',
									'unit' => 'px',
								),
							),
						),
					)
				),
			),
		);
	}
	public static function get_button_sections_config () {
		return array(
			'title'    => __('Button', 'ba-cheetah'),
			'sections' => array(
				'cta' => array(
					'title' => __('Content', 'ba-cheetah'),
					'fields' => array_merge( array(
						'btn_text'           => array(
							'type'        => 'text',
							'label'       => __('Title', 'ba-cheetah'),
							'default'     => 'Learn more',
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
						'btn_click_action'   => array(
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
									'fields' => array( 'btn_link' ),
								),
								'popup' => array(
									'fields' => array( 'btn_popup_id' ),
								),
								'video' => array(
									'fields' => array( 'btn_video_link' ),
								),
							),
							'preview' => array(
								'type' => 'none',
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
						'btn_popup_id'  => array(
							'type'    => 'select',
							'label'   => __( 'Popup', 'ba-cheetah' ),
							'default' => '',
							'options' => array(),
							'preview' => array(
								'type' => 'none',
							),
						),
						'btn_video_link'           => array(
							'type'          => 'text',
							'label'         => __( 'Link', 'ba-cheetah' ),
							'placeholder'   => 'https://www.youtube.com/embed/7xL5HFghzfg',
							'preview'       => array(
								'type' => 'none',
							),
						),
						'btn_icon'           => array(
							'type'        => 'icon',
							'label'       => __('Icon', 'ba-cheetah'),
							'default'	  => 'fas fa-angle-right',
							'show_remove' => true,
							'show'        => array(
								'fields' => array('btn_icon_position'),
								'sections' => array('btn_icon_styles')
							),
						),
						'btn_icon_position'  => array(
							'type'    => 'select',
							'label'   => __('Icon Position', 'ba-cheetah'),
							'default' => 'after',
							'options' => array(
								'before' => __('Before Text', 'ba-cheetah'),
								'after'  => __('After Text', 'ba-cheetah'),
							),
						),
					), BACheetahTracking::module_tracking_fields('btn_')),
				),
				'style'  => array(
					'title'  => __('Size & Align', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'btn_width'        => array(
							'type'    => 'select',
							'label'   => __('Width', 'ba-cheetah'),
							'default' => 'auto',
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
							'default'	 => '18',
							'units'      => array('px'),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '{node} .ba-module__button .button__button',
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
							'type' => 'button-group',
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
								),
								'radius' => array(
									'top_left' => '6',
									'top_right' => '6',
									'bottom_left' => '6',
									'bottom_right' => '6',
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
								'font_family' => 'Montserrat',
								'font_weight' => '500',
								'font_size' => array(
									'length' => '15',
									'unit' => 'px',
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
			)
		);
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahFlipBoxModule', array(
	'general' => array(
		'title'    => __('Content', 'ba-cheetah'),
		'sections' => array(
			'general' => array(
				'title'  => __('Content', 'ba-cheetah'),
				'fields' => array(
                    'effect'         => array(
                        'type'    => 'select',
                        'label'   => __( 'Effect', 'ba-cheetah' ),
                        'default' => 'flip',
                        'options' => array(
                            'flip'       => __( 'Flip', 'ba-cheetah' ),
                            'vertical'   => __( 'Vertical', 'ba-cheetah' ),
                            'fade'       => __( 'Fade', 'ba-cheetah' ),
                        ),
                    ),
					'image' => array(
						'type'        => 'photo',
						'label'       => __('Photo', 'ba-cheetah'),
						'default'	  => BACheetahFlipBoxModule::DEFAULT_PHOTO,
						'show_remove' => true,
					),
                    'use_logo'        => array(
                        'type'    => 'select',
                        'label'   => __('Use logo', 'ba-cheetah'),
                        'default' => 'disabled',
                        'options' => array(
                            'disabled'   => __('Disabled', 'ba-cheetah'),
                            'enabled'    => __('Enabled', 'ba-cheetah'),
                        ),
                        'toggle' => array(
                            'enabled' => array('fields' => array('position_logo','logo', 'logo_height','logo_box_border')),
                        )
                    ),
                    'position_logo'        => array(
                        'type'    => 'select',
                        'label'   => __('Logo Position', 'ba-cheetah'),
                        'default' => 'center',
                        'options' => array(
                            'center'   => __('Center', 'ba-cheetah'),
                            'left'     => __('Left', 'ba-cheetah'),
                            'right'    => __('Right', 'ba-cheetah'),
                            'full'     => __('Full', 'ba-cheetah'),
                        ),
                    ),
					'logo' => array(
						'type'        => 'photo',
						'label'       => __('Logo', 'ba-cheetah'),
						'show_remove' => true,
					),
                    'logo_height'  => array(
                        'type'     => 'unit',
                        'label'    => __( 'Height Of Logo', 'ba-cheetah' ),
                        'default'  => '50',
                        'sanitize' => 'absint',
                        'units'    => array( 'px' ),
                        'slider'   => array(
                            'step' => 1,
                            'max'  => 200,
                        ),
                    ),
                    'logo_width'  => array(
                        'type'     => 'unit',
                        'label'    => __( 'Width Of Logo', 'ba-cheetah' ),
                        'default'  => '50',
                        'sanitize' => 'absint',
                        'units'    => array( 'px' ),
                        'slider'   => array(
                            'step' => 1,
                            'max'  => 200,
                        ),
                    ),
                    'logo_box_border'  => array(
                        'type' => 'border',
                        'label' => __('Border Logo', 'ba-cheetah'),
                        'responsive' => true,
                        'default' => array(
                            'shadow' => array(
                                'color' => 'c4c4c4',
                                'horizontal' => '0',
                                'vertical' => '0',
                                'blur' => '0',
                                'spread' => '0',
                            ),
                            'radius' => array(
                                'top_left' => '10',
                                'top_right' => '10',
                                'bottom_left' => '10',
                                'bottom_right' => '10',
                            )
                        )
                    ),
					'title' => array(
						'type'        => 'text',
						'label'       => __('Title', 'ba-cheetah'),
						'default'	  => 'I am a awesome element! I\'m looking forward to you editing me!',
						'preview'	  => array(
							'type' 		=> 'text',
							'selector' 	=> '{node} .flip-box__content .flip-box__title'
						)
					),
					'subtitle' => array(
						'type'        => 'text',
						'label'       => __('Subtitle', 'ba-cheetah'),
						'default'	  => 'Subtitle',
						'preview'	  => array(
							'type' 		=> 'text',
							'selector' 	=> '{node} .flip-box__content .flip-box__subtitle'
						)
					),
				),
			),
			'internal-general' => array(
				'title'  => __('Internal Content', 'ba-cheetah'),
				'fields' => array(
                    'reverse' => array(
                        'type' => 'button-group',
                        'label' => __('Reverse', 'ba-cheetah'),
                        'default' => '0',
                        'options' => array(
                            '0' => __('No', 'ba-cheetah'),
                            '1' => __('Yes', 'ba-cheetah'),
                        )
                    ),
					'image_internal' => array(
						'type'        => 'photo',
						'label'       => __('Photo', 'ba-cheetah'),
						'default'	  => BACheetahFlipBoxModule::DEFAULT_PHOTO_INTERNAL,
						'show_remove' => true,
					),
					'title_internal' => array(
						'type'        => 'text',
						'label'       => __('Title', 'ba-cheetah'),
						'default'	  => 'I am a awesome element! I\'m looking forward to you editing me!',
						'preview'	  => array(
							'type' 		=> 'text',
							'selector' 	=> '{node} .flip-box__content.internal .flip-box__title_internal'
						)
					),
					'subtitle_internal' => array(
						'type'        => 'text',
						'label'       => __('Subtitle', 'ba-cheetah'),
						'default'	  => 'Subtitle',
						'preview'	  => array(
							'type' 		=> 'text',
							'selector' 	=> '{node} .flip-box__content.internal .flip-box__subtitle_internal'
						)
					),

				),
			),
		),
	),
	'style'   => BACheetahFlipBoxModule::get_styles_sections_config(),
    'button'   => BACheetahFlipBoxModule::get_button_sections_config(),
));
