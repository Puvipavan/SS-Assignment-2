<?php

BACheetah::register_settings_form('col', array(
	'title' => __( 'Column Settings', 'ba-cheetah' ),
	'tabs'  => array(
		'style'    => array(
			'title'    => __( 'Style', 'ba-cheetah' ),
			'sections' => array(
				'general'     => array(
					'title'  => '',
					'fields' => array(
						'size'              => array(
							'type'       => 'unit',
							'label'      => __( 'Width', 'ba-cheetah' ),
							'default'    => '',
							'responsive' => true,
							'slider'     => true,
							'units'      => array(
								'%',
							),
							'preview'    => array(
								'type' => 'none',
							),
						),
						'min_height'        => array(
							'type'       => 'unit',
							'label'      => __( 'Minimum Height', 'ba-cheetah' ),
							'responsive' => true,
							'units'      => array(
								'px',
								'vh',
								'vw',
							),
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'min-height',
							),
						),
						'equal_height'      => array(
							'type'    => 'select',
							'label'   => __( 'Equalize Heights', 'ba-cheetah' ),
							'help'    => __( 'Setting this to yes will make all of the columns in this group the same height regardless of how much content is in each of them.', 'ba-cheetah' ),
							'default' => 'no',
							'options' => array(
								'no'  => __( 'No', 'ba-cheetah' ),
								'yes' => __( 'Yes', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'yes' => array(
									'fields' => array( 'content_alignment' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'content_alignment' => array(
							'type'    => 'select',
							'label'   => __( 'Vertical Alignment', 'ba-cheetah' ),
							'default' => 'top',
							'options' => array(
								'top'    => __( 'Top', 'ba-cheetah' ),
								'center' => __( 'Center', 'ba-cheetah' ),
								'bottom' => __( 'Bottom', 'ba-cheetah' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
				
				'background'  => array(
					'title'  => __( 'Background', 'ba-cheetah' ),
					'fields' => array(
						'bg_type' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'ba-cheetah' ),
							'default' => 'color',
							'options' => array(
								'none'     => _x( 'None', 'Background type.', 'ba-cheetah' ),
								'color'    => _x( 'Color', 'Background type.', 'ba-cheetah' ),
								'gradient' => _x( 'Gradient', 'Background type.', 'ba-cheetah' ),
								'photo'    => _x( 'Photo', 'Background type.', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'color'    => array(
									'sections' => array( 'bg_color' ),
								),
								'gradient' => array(
									'sections' => array( 'bg_gradient' ),
								),
								'photo'    => array(
									'sections' => array( 'bg_photo', 'bg_overlay', 'bg_color' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
				'bg_photo'    => array(
					'title'  => __( 'Background Photo', 'ba-cheetah' ),
					'fields' => array(
						'bg_image'      => array(
							'type'        => 'photo',
							'show_remove' => true,
							'label'       => __( 'Photo', 'ba-cheetah' ),
							'responsive'  => true,
							'connections' => array( 'photo' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'background-image',
							),
						),
						'bg_repeat'     => array(
							'type'       => 'select',
							'label'      => __( 'Repeat', 'ba-cheetah' ),
							'default'    => 'none',
							'responsive' => true,
							'options'    => array(
								'no-repeat' => _x( 'None', 'Background repeat.', 'ba-cheetah' ),
								'repeat'    => _x( 'Tile', 'Background repeat.', 'ba-cheetah' ),
								'repeat-x'  => _x( 'Horizontal', 'Background repeat.', 'ba-cheetah' ),
								'repeat-y'  => _x( 'Vertical', 'Background repeat.', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'background-repeat',
							),
						),
						'bg_position'   => array(
							'type'       => 'select',
							'label'      => __( 'Position', 'ba-cheetah' ),
							'default'    => 'center center',
							'responsive' => true,
							'options'    => array(
								'left top'      => __( 'Left Top', 'ba-cheetah' ),
								'left center'   => __( 'Left Center', 'ba-cheetah' ),
								'left bottom'   => __( 'Left Bottom', 'ba-cheetah' ),
								'right top'     => __( 'Right Top', 'ba-cheetah' ),
								'right center'  => __( 'Right Center', 'ba-cheetah' ),
								'right bottom'  => __( 'Right Bottom', 'ba-cheetah' ),
								'center top'    => __( 'Center Top', 'ba-cheetah' ),
								'center center' => __( 'Center', 'ba-cheetah' ),
								'center bottom' => __( 'Center Bottom', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'background-position',
							),
						),
						'bg_attachment' => array(
							'type'       => 'select',
							'label'      => __( 'Attachment', 'ba-cheetah' ),
							'default'    => 'scroll',
							'responsive' => true,
							'options'    => array(
								'scroll' => __( 'Scroll', 'ba-cheetah' ),
								'fixed'  => __( 'Fixed', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'background-attachment',
							),
						),
						'bg_size'       => array(
							'type'       => 'select',
							'label'      => __( 'Scale', 'ba-cheetah' ),
							'default'    => 'cover',
							'responsive' => true,
							'options'    => array(
								'auto'    => _x( 'None', 'Background scale.', 'ba-cheetah' ),
								'contain' => __( 'Fit', 'ba-cheetah' ),
								'cover'   => __( 'Fill', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'background-size',
							),
						),
					),
				),
				'bg_overlay'  => array(
					'title'  => __( 'Background Overlay', 'ba-cheetah' ),
					'fields' => array(
						'bg_overlay_type'     => array(
							'type'    => 'select',
							'label'   => __( 'Overlay Type', 'ba-cheetah' ),
							'default' => 'color',
							'options' => array(
								'none'     => __( 'None', 'ba-cheetah' ),
								'color'    => __( 'Color', 'ba-cheetah' ),
								'gradient' => __( 'Gradient', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'bg_overlay_color' ),
								),
								'gradient' => array(
									'fields' => array( 'bg_overlay_gradient' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'bg_overlay_color'    => array(
							'type'       => 'color',
							'label'      => __( 'Overlay Color', 'ba-cheetah' ),
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type' => 'none',
							),
						),
						'bg_overlay_gradient' => array(
							'type'    => 'gradient',
							'label'   => __( 'Overlay Gradient', 'ba-cheetah' ),
							'preview' => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content:after',
								'property' => 'background-image',
							),
						),
					),
				),
				'bg_color'    => array(
					'title'  => __( 'Background Color', 'ba-cheetah' ),
					'fields' => array(
						'bg_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
				'bg_gradient' => array(
					'title'  => __( 'Background Gradient', 'ba-cheetah' ),
					'fields' => array(
						'bg_gradient' => array(
							'type'    => 'gradient',
							'label'   => __( 'Gradient', 'ba-cheetah' ),
							'preview' => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'background-image',
							),
						),
					),
				),
				'text'        => array(
					'title'  => __( 'Text', 'ba-cheetah' ),
					'fields' => array(
						'text_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'link_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Link Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'hover_color'   => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Link Hover Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'heading_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Heading Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
				'border'      => array(
					'title'  => __( 'Border', 'ba-cheetah' ),
					'fields' => array(
						'border' => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'ba-cheetah' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
							),
						),
					),
				),
			),
		),
		'advanced' => array(
			'title'    => __( 'Advanced', 'ba-cheetah' ),
			'sections' => array(
				'margins'       => array(
					'title'  => __( 'Spacing', 'ba-cheetah' ),
					'fields' => array(
						'margin'  => array(
							'type'       => 'dimension',
							'label'      => __( 'Margins', 'ba-cheetah' ),
							'slider'     => array(
								'min' => -50,
								'step' => 1,
								'max' => 500
							),
							'units'      => array(
								'px',
								'%',
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'margin',
							),
							'responsive' => array(
								'default_unit' => array(
									'default'    => $global_settings->column_margins_unit,
									'medium'     => $global_settings->column_margins_medium_unit,
									'responsive' => $global_settings->column_margins_responsive_unit,
								),
								'placeholder'  => array(
									'default'    => empty( $global_settings->column_margins ) ? '0' : $global_settings->column_margins,
									'medium'     => empty( $global_settings->column_margins_medium ) ? '0' : $global_settings->column_margins_medium,
									'responsive' => empty( $global_settings->column_margins_responsive ) ? '0' : $global_settings->column_margins_responsive,
								),
							),
						),
						'padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'ba-cheetah' ),
							'slider'     => true,
							'units'      => array(
								'px',
								'em',
								'%',
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-col-content',
								'property' => 'padding',
							),
							'responsive' => array(
								'default_unit' => array(
									'default'    => $global_settings->column_padding_unit,
									'medium'     => $global_settings->column_padding_medium_unit,
									'responsive' => $global_settings->column_padding_responsive_unit,
								),
								'placeholder'  => array(
									'default'    => empty( $global_settings->column_padding ) ? '0' : $global_settings->column_padding,
									'medium'     => empty( $global_settings->column_padding_medium ) ? '0' : $global_settings->column_padding_medium,
									'responsive' => empty( $global_settings->column_padding_responsive ) ? '0' : $global_settings->column_padding_responsive,
								),
							),
						),
					),
				),
				'visibility'    => array(
					'title'  => __( 'Visibility', 'ba-cheetah' ),
					'fields' => array(
						'responsive_display_warning' => array(
							'type' => 'raw',
							'content' => __('Visibility settings can only be viewed in preview mode. While you are editing, all elements will be visible.', 'ba-cheetah' ),
						),
						'responsive_display'         => array(
							'type'    => 'select',
							'label'   => __( 'Breakpoint', 'ba-cheetah' ),
							'options' => array(
								''               => __( 'All', 'ba-cheetah' ),
								'desktop'        => __( 'Large Devices Only', 'ba-cheetah' ),
								'desktop-medium' => __( 'Large &amp; Medium Devices Only', 'ba-cheetah' ),
								'medium'         => __( 'Medium Devices Only', 'ba-cheetah' ),
								'medium-mobile'  => __( 'Medium &amp; Small Devices Only', 'ba-cheetah' ),
								'mobile'         => __( 'Small Devices Only', 'ba-cheetah' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'responsive_order'           => array(
							'type'    => 'select',
							'label'   => __( 'Stacking Order', 'ba-cheetah' ),
							'help'    => __( 'The order of the columns in this group when they are stacked for small devices.', 'ba-cheetah' ),
							'default' => 'default',
							'options' => array(
								'default'  => __( 'Default', 'ba-cheetah' ),
								'reversed' => __( 'Reversed', 'ba-cheetah' ),
								'set' => __( 'Set order...', 'ba-cheetah' ),
							),
							'toggle' => array(
								'set' => array(
									'fields' => array('responsive_order_set')
								)
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'responsive_order_set' => array(
							'type' => 'select',
							'label' => __('Responsive Order', 'ba-cheetah'),
							'options' => range(0, 12)
						),
						'visibility_display'         => array(
							'type'    => 'select',
							'label'   => __( 'Display', 'ba-cheetah' ),
							'options' => array(
								''           => __( 'Always', 'ba-cheetah' ),
								'logged_out' => __( 'Logged Out User', 'ba-cheetah' ),
								'logged_in'  => __( 'Logged In User', 'ba-cheetah' ),
								'0'          => __( 'Never', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'logged_in' => array(
									'fields' => array( 'visibility_user_capability' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'visibility_user_capability' => array(
							'type'        => 'text',
							'label'       => __( 'User Capability', 'ba-cheetah' ),
							/* translators: %s: wporg docs link */
							'description' => sprintf( __( 'Optional. Set the <a%s>capability</a> required for users to view this column.', 'ba-cheetah' ), ' href="http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table" target="_blank"' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
				'animation'     => array(
					'title'  => __( 'Animation', 'ba-cheetah' ),
					'fields' => array(
						'animation' => array(
							'type'    => 'animation',
							'label'   => __( 'Animation', 'ba-cheetah' ),
							'preview' => array(
								'type'     => 'animation',
								'selector' => '{node}',
							),
						),
					),
				),
				'css_selectors' => array(
					'title'  => __( 'HTML Element', 'ba-cheetah' ),
					'fields' => array(
						'container_element' => array(
							'type'    => 'select',
							'label'   => __( 'Container Element', 'ba-cheetah' ),
							'default' => 'div',
							/**
							 * Filter to add/remove container types.
							 * @see ba_cheetah_node_container_element_options
							 */
							'options' => apply_filters( 'ba_cheetah_node_container_element_options', array(
								'div'     => '&lt;div&gt;',
								'section' => '&lt;section&gt;',
								'article' => '&lt;article&gt;',
								'aside'   => '&lt;aside&gt;',
								'main'    => '&lt;main&gt;',
								'header'  => '&lt;header&gt;',
								'footer'  => '&lt;footer&gt;',
							) ),
							'help'    => __( 'Optional. Choose an appropriate HTML5 content sectioning element to use for this column to improve accessibility and machine-readability.', 'ba-cheetah' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'id'                => array(
							'type'    => 'text',
							'label'   => __( 'ID', 'ba-cheetah' ),
							'help'    => __( "A unique ID that will be applied to this column's HTML. Must start with a letter and only contain dashes, underscores, letters or numbers. No spaces.", 'ba-cheetah' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'class'             => array(
							'type'    => 'text',
							'label'   => __( 'CSS Class', 'ba-cheetah' ),
							'help'    => __( "A class that will be applied to this column's HTML. Must start with a letter and only contain dashes, underscores, letters or numbers. Separate multiple classes with spaces.", 'ba-cheetah' ),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
	),
));
