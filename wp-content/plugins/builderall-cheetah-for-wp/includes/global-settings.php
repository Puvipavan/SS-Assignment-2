<?php

// disable global option for global setting and set none default
require_once BA_CHEETAH_DIR . 'includes/page-background.php';
$page_global_background_settings = $page_background_settings;
unset($page_global_background_settings['bg_type']['options']['global']);
$page_global_background_settings['bg_type']['default'] = 'none';

BACheetah::register_settings_form('global', array(
	'title' => __( 'Global Settings', 'ba-cheetah' ),
	'tabs'  => array(
		'styles' => array(
			'title' => __( 'Page', 'ba-cheetah' ),
			'description' => __('These settings will apply to all pages that do not have specific settings for the page.', 'ba-cheetah'),
			'sections' => array(
				'page_background' => array(
					'title' => __('Background', 'ba-cheetah'),
					'fields' => $page_global_background_settings
				),
				'page_margin' => array(
					'title' => __('Padding', 'ba-cheetah'),
					'fields' => array(
						'body_padding'      => array(
							'type'       => 'dimension',
							'label'      => __('Padding', 'ba-cheetah'),
							'slider'     => true,
							'responsive' => true,
							'units'      => array('px'),
							'preview'    => array(
								'type'     => 'css',
								'selector' => 'body.ba-cheetah',
								'property' => 'padding',
							),
						),
					)
				),
			)
		),
		
		'general' => array(
			'title'       => __( 'Layout', 'ba-cheetah' ),
			// 'description' => __( '<strong>Note</strong>: These settings apply to all posts and pages.', 'ba-cheetah' ),
			'sections'    => array(
				'layout' => array(
					'title' => __('Layout', 'ba-cheetah'),
					'fields' => array(
						'show_default_heading' => array(
							'type' => 'button-group',
							'label' => __('Show default heading', 'ba-cheetah'),
							'default' => '1',
							'options' => array(
								'0' => __('No', 'ba-cheetah'),
								'1' => __('Yes', 'ba-cheetah'),
							),
							'toggle' => array(
								'0' => array('fields' => array('default_heading_selector'))
							),
						),
						'default_heading_selector' => array(
							'type' => 'text',
							'label' => __('Heading selector', 'ba-cheetah'),
							'default' => '.entry-header'
						)
					)
				),
				'rows'         => array(
					'title'  => __( 'Rows', 'ba-cheetah' ),
					'fields' => array(
						'row_margins'               => array(
							'type'       => 'unit',
							'label'      => __( 'Margins', 'ba-cheetah' ),
							'default'    => '0',
							'sanitize'   => 'absint',
							'responsive' => array(
								'placeholder' => array(
									'default'    => '0',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'units'      => array(
								'px',
								'%',
							),
						),
						'row_padding'               => array(
							'type'       => 'unit',
							'label'      => __( 'Padding', 'ba-cheetah' ),
							'default'    => '20',
							'sanitize'   => 'absint',
							'responsive' => array(
								'placeholder' => array(
									'default'    => '0',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'units'      => array(
								'px',
								'em',
								'%',
							),
						),
						'row_width'                 => array(
							'type'      => 'unit',
							'label'     => __( 'Max Width', 'ba-cheetah' ),
							'default'   => '992',
							'maxlength' => '4',
							'size'      => '5',
							'sanitize'  => 'absint',
							'help'      => __( 'All rows will default to this width. You can override this and make a row full width in the settings for each row.', 'ba-cheetah' ),
							'units'     => array(
								'px',
								'vw',
								'%',
							),
						),
						'row_width_default'         => array(
							'type'    => 'select',
							'label'   => __( 'Default Row Width', 'ba-cheetah' ),
							'default' => 'full',
							'options' => array(
								'fixed' => __( 'Fixed', 'ba-cheetah' ),
								'full'  => __( 'Full Width', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'full' => array(
									'fields' => array( 'row_content_width_default' ),
								),
							),
						),
						'row_content_width_default' => array(
							'type'    => 'select',
							'label'   => __( 'Default Row Content Width', 'ba-cheetah' ),
							'default' => 'fixed',
							'options' => array(
								'fixed' => __( 'Fixed', 'ba-cheetah' ),
								'full'  => __( 'Full Width', 'ba-cheetah' ),
							),
						),
					),
				),
				'columns'      => array(
					'title'  => __( 'Columns', 'ba-cheetah' ),
					'fields' => array(
						'column_margins' => array(
							'type'       => 'unit',
							'label'      => __( 'Margins', 'ba-cheetah' ),
							'default'    => '',
							'sanitize'   => 'absint',
							'responsive' => array(
								'placeholder' => array(
									'default'    => '0',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'units'      => array(
								'px',
								'%',
							),
						),
						'column_padding' => array(
							'type'       => 'unit',
							'label'      => __( 'Padding', 'ba-cheetah' ),
							'default'    => '',
							'sanitize'   => 'absint',
							'responsive' => array(
								'placeholder' => array(
									'default'    => '0',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'units'      => array(
								'px',
								'em',
								'%',
							),
						),
					),
				),
				'modules'      => array(
					'title'  => __( 'Elements', 'ba-cheetah' ),
					'fields' => array(
						'module_margins' => array(
							'type'       => 'unit',
							'label'      => __( 'Margins', 'ba-cheetah' ),
							'default'    => '20',
							'sanitize'   => 'absint',
							'responsive' => array(
								'placeholder' => array(
									'default'    => '0',
									'medium'     => '',
									'responsive' => '',
								),
							),
							'units'      => array(
								'px',
								'%',
							),
						),
					),
				),
				'popups' => array(
					'title' => __( 'Popups', 'ba-cheetah' ),
					'fields' => array(
						'popup_overlay_color' => array(
							'type' => 'color',
							'label' => __('Overlay color'),
							'show_alpha'  => true,
							'default' => 'rgba(0,0,0,0.8)',
						),
						'popup_width' => array(
							'type' => 'unit',
							'label' => __('Width'),
							'help' => __('Width used as default for new popups and for video popups', 'ba-cheetah'),
							'default' => 700,
							'units' => array('px', '%', 'vw'),
							'default_unit' => 'px',
							'slider' => array(
								'px' => array(
									'min'  => 0,
									'max'  => 2000,
									'step' => 1,
								),
								'vw' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
								'%' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								),
							)
						)
					)
				),
				'responsive'   => array(
					'title'  => __( 'Responsive Layout', 'ba-cheetah' ),
					'fields' => array(
						'responsive_enabled'       => array(
							'type'    => 'select',
							'label'   => _x( 'Enabled', 'General settings form field label. Intended meaning: "Responsive layout enabled?"', 'ba-cheetah' ),
							'default' => '1',
							'options' => array(
								'0' => __( 'No', 'ba-cheetah' ),
								'1' => __( 'Yes', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'1' => array(
									'fields' => array( 'auto_spacing', 'responsive_breakpoint', 'medium_breakpoint', 'responsive_col_max_width' ),
								),
							),
						),
						'auto_spacing'             => array(
							'type'    => 'select',
							'label'   => _x( 'Enable Auto Spacing', 'General settings form field label. Intended meaning: "Enable auto spacing for responsive layouts?"', 'ba-cheetah' ),
							'default' => '1',
							'options' => array(
								'0' => __( 'No', 'ba-cheetah' ),
								'1' => __( 'Yes', 'ba-cheetah' ),
							),
							'help'    => __( 'When auto spacing is enabled, the builder will automatically adjust the margins and padding in your layout once the small device breakpoint is reached. Most users will want to leave this enabled.', 'ba-cheetah' ),
						),
						'medium_breakpoint'        => array(
							'type'        => 'text',
							'label'       => __( 'Medium Device Breakpoint', 'ba-cheetah' ),
							'default'     => '992',
							'maxlength'   => '4',
							'size'        => '5',
							'description' => 'px',
							'sanitize'    => 'absint',
							'help'        => __( 'The browser width at which the layout will adjust for medium devices such as tablets.', 'ba-cheetah' ),
						),
						'responsive_breakpoint'    => array(
							'type'        => 'text',
							'label'       => __( 'Small Device Breakpoint', 'ba-cheetah' ),
							'default'     => '768',
							'maxlength'   => '4',
							'size'        => '5',
							'description' => 'px',
							'sanitize'    => 'absint',
							'help'        => __( 'The browser width at which the layout will adjust for small devices such as phones.', 'ba-cheetah' ),
						),
						'responsive_preview'       => array(
							'type'    => 'select',
							'label'   => __( 'Use responsive settings in previews?', 'ba-cheetah' ),
							'default' => '0',
							'options' => array(
								'0' => __( 'No', 'ba-cheetah' ),
								'1' => __( 'Yes', 'ba-cheetah' ),
							),
							'help'    => __( 'Preview and responsive editing will use these values when enabled.', 'ba-cheetah' ),
						),
						'responsive_col_max_width' => array(
							'type'    => 'select',
							'label'   => __( 'Enable Column Max Width', 'ba-cheetah' ),
							'default' => '1',
							'options' => array(
								'0' => __( 'No', 'ba-cheetah' ),
								'1' => __( 'Yes', 'ba-cheetah' ),
							),
							'help'    => __( 'When enabled, columns assigned 50% width or less are limited to max width 400px when screen width reaches or goes below the small device breakpoint.', 'ba-cheetah' ),
						),
						'responsive_base_fontsize' => array(
							'type'        => 'text',
							'label'       => __( 'Base Font Size', 'ba-cheetah' ),
							'default'     => '16',
							'maxlength'   => '4',
							'size'        => '5',
							'description' => 'px',
							'sanitize'    => 'absint',
							'help'        => __( 'When typography unit is set to vh/vw this unit will be used to calculate the font size.', 'ba-cheetah' ),
						),
					),
				),
			),
		),
		
		/*
		'fonts' => array(
			'title' => __( 'Fonts', 'ba-cheetah' ),
			'sections' => array(
				'h1' => array(
					'title' => 'H1',
					'fields' => array(
						'raw' => array(
							'type' => 'raw',
							'content' => 'Coming soon'
						)
					)
				),
				'h2' => array(
					'title' => 'H2',
					'collapsed' => true,
					'fields' => array(
						'raw' => array(
							'type' => 'raw',
							'content' => 'Coming soon'
						)
					)
				),
				'h3' => array(
					'title' => 'H3',
					'collapsed' => true,
					'fields' => array(
						'raw' => array(
							'type' => 'raw',
							'content' => 'Coming soon'
						)
					)
				),
				'h4' => array(
					'title' => 'H4',
					'collapsed' => true,
					'fields' => array(
						'raw' => array(
							'type' => 'raw',
							'content' => 'Coming soon'
						)
					)
				),
				'h5' => array(
					'title' => 'H5',
					'collapsed' => true,
					'fields' => array(
						'raw' => array(
							'type' => 'raw',
							'content' => 'Coming soon'
						)
					)
				),
				'h6' => array(
					'title' => 'H6',
					'collapsed' => true,
					'fields' => array(
						'raw' => array(
							'type' => 'raw',
							'content' => 'Coming soon'
						)
					)
				),
				'p' => array(
					'title' => 'Paragraph',
					'collapsed' => true,
					'fields' => array(
						'raw' => array(
							'type' => 'raw',
							'content' => 'Coming soon'
						)
					)
				),
			)
		),
		*/

		'css_js'     => array(
			'title'    => 'CSS/JS',
			'sections' => array(
				'css' => array(
					'title'  => 'CSS',
					'collapsed' => true,
					'fields' => array(
						'css' => array(
							'type'    => 'code',
							'label'   => '',
							'editor'  => 'css',
							'rows'    => '18',
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
				'js'=> array(
					'title' => 'Javascript',
					'collapsed' => true,
					'fields' => array(
						'js' => array(
							'type'    => 'code',
							'label'   => '',
							'editor'  => 'javascript',
							'rows'    => '18',
							'preview' => array(
								'type' => 'none',
							),
						),
					)
				)
			),
		),
	),
));
