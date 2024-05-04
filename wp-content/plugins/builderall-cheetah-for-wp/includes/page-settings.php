<?php

// Configure form settings to work page pased
require_once BA_CHEETAH_DIR . 'includes/page-background.php';
unset($page_background_settings['bg_type']['options']['theme']);

// Ajust models, footers and popups

$default_model = array("" => __('Default template') );
$models = ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) ? wp_get_theme()->get_page_templates() : array();
$models = array_merge($default_model, $models);

$sections = array(
	'page_layout' => array(
		'title'  => __('Page layout', 'ba-cheetah'),
		'fields' => array(
			'layout'     => array(
				'type'    => 'select',
				'label'   => __('Page layout', 'ba-cheetah'),
				'options' => $models,
				'preview' => array(
					'type' => 'none'
				)
			),
		),
	)
);

$header_translated = __('Header', 'ba-cheetah');
$footer_translated = __('Footer', 'ba-cheetah');

$sections = array_merge($sections, array(
	'cheetah_header' => array(
		'title'  => __('Builderall Builder header', 'ba-cheetah'),
		'fields' => array(
			'ba-cheetah-header-option' => array(
				'type'    => 'select',
				'label'   => sprintf(__('%s option', 'ba-cheetah'), 'Header'),
				'options' => array(
					'default'    => sprintf(__('Theme %s', 'ba-cheetah'), $header_translated),
					'custom'    => sprintf(__('Custom %s', 'ba-cheetah'), $header_translated),
					'global'    => sprintf(__('Global %s', 'ba-cheetah'), $header_translated),
					'disabled'    => sprintf(__('No %s', 'ba-cheetah'), $header_translated),
				),
				'toggle'        => array(
					'custom'      => array(
						'fields'        => array('ba-cheetah-custom-header-id', 'raw_manage_header'),
					),
				),
				'preview' => array(
					'type' => 'none'
				)
			),
			'ba-cheetah-custom-header-id'     => array(
				'type'    => 'select',
				'label'   => sprintf(__('Custom %s', 'ba-cheetah'), 'header'),
				'options' => array(),
				'preview' => array(
					'type' => 'none'
				)
			),
			'raw_manage_header' => array(
				'type' => 'raw',
				'content' => '<br>
						<a href="' . admin_url('edit.php?post_type=ba-cheetah-header') . '" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
				. __('Manage headers', 'ba-cheetah') .
				'<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>' .
				'</a>'
			)
		),
	),
	'cheetah_footer' => array(
		'title'  => __('Builderall Builder footer', 'ba-cheetah'),
		'fields' => array(
			'ba-cheetah-footer-option' => array(
				'type'    => 'select',
				'label'   => sprintf(__('%s option', 'ba-cheetah'), 'Footer'),
				'options' => array(
					'default'    => sprintf(__('Theme %s', 'ba-cheetah'), $footer_translated),
					'custom'    => sprintf(__('Custom %s', 'ba-cheetah'), $footer_translated),
					'global'    => sprintf(__('Global %s', 'ba-cheetah'), $footer_translated),
					'disabled'    => sprintf(__('No %s', 'ba-cheetah'), $footer_translated),
				),
				'toggle'        => array(
					'custom'      => array(
						'fields'        => array('ba-cheetah-custom-footer-id', 'raw_manage_footer'),
					),
				),
				'preview' => array(
					'type' => 'none'
				)
			),
			'ba-cheetah-custom-footer-id'     => array(
				'type'    => 'select',
				'label'   => sprintf(__('Custom %s', 'ba-cheetah'), 'footer'),
				'options' => array(),
				'preview' => array(
					'type' => 'none'
				)
			),
			'raw_manage_footer' => array(
				'type' => 'raw',
				'content' => '<br>
						<a href="' . admin_url('edit.php?post_type=ba-cheetah-footer') . '" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
				. __('Manage footers', 'ba-cheetah') .
				'<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>' .
				'</a>'
			)
		)
	),
	'cheetah_popup' => array(
		'title'  => __('Builderall Builder popups', 'ba-cheetah'),
			'enabled' => BA_CHEETAH_PRO,
			'fields' => array(
			'popup' => array(
				'type' => 'select',
				'label' => __('Popup', 'ba-cheetah'),
				'options' => array(),
				'preview' => array(
					'type' => 'none'
				)
			),
			'popup_trigger' => array(
				'type' => 'select',
				'label' =>  __('Trigger', 'ba-cheetah'),
				'options' => array(
					'exit' => __('Exit popup', 'ba-cheetah'),
					'scroll' => __('Scroll', 'ba-cheetah'),
					'timer' => __('Timer', 'ba-cheetah')
				),
				'toggle' => array(
					'scroll' => array('fields' => array('popup_scroll_point')),
					'timer' => array('fields' => array('popup_timer_minutes','popup_timer_seconds'))
				),
				'preview' => array(
					'type' => 'none'
				)
			),
			'popup_scroll_point' => array(
				'type' => 'unit',
				'label' => __('Scroll point'),
				'units' => array('%'),
				'default' => '50',
				'slider' => true,
				'preview' => array(
					'type' => 'none'
				)
			),
			'popup_timer_minutes' => array(
				'type' => 'select',
				'label' => __('Minutes', 'ba-cheetah'),
				'default' => '0',
				'options' => range(0, 60)
			),
			'popup_timer_seconds' => array(
				'type' => 'select',
				'label' => __('Seconds', 'ba-cheetah'),
				'default' => '15',
				'options' => range(0, 60)
			),
			'raw_manage_popup' => array(
				'type' => 'raw',
				'content' => '<br>
						<a href="' . admin_url('edit.php?post_type=ba-cheetah-popup') . '" target="_blank" class="ba-cheetah-button ba-cheetah-button-primary">'
				. __('Manage popups', 'ba-cheetah') .
				'<i style="margin-left: 8px" class="fas fa-external-link-alt"></i>' .
				'</a>'
			)
		)
	)
));

BACheetah::register_settings_form('pixel_page_events_onload', array(
	'title' => __('Pixel event', 'ba-cheetah'),
	'tabs'  => array(
		'general'      => array(
			'title'         => __('Pixel event', 'ba-cheetah'),
			'sections'      => array(
				'general'       => array(
					'title'         => __('Pixel event', 'ba-cheetah'),
					'fields'        => array(
						'event'         => array(
							'type'          => 'text',
							'label'			=> __('Event', 'ba-cheetah'),
							'default'       => '',
						),
					)
				),
			)
		)
	)
));


BACheetah::register_settings_form('page', array(
	'title' => __( 'Page settings', 'ba-cheetah' ),
	'tabs'  => array(
		'styles' => array(
			'title' => __( 'Page', 'ba-cheetah' ),
			'description' => __('These settings will apply only in this page.', 'ba-cheetah'),
			'sections' => array(
				'page_background' => array(
					'title' => __('Background', 'ba-cheetah'),
					'fields' => $page_background_settings
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
							'default' 	 => '0',
							'preview'    => array(
								'type'     => 'css',
								'selector' => 'body.ba-cheetah',
								'property' => 'padding',
								'important' => true
							),
						),
					)
				),
			)
		),
		'general' => array(
			'title'       => __( 'Layout', 'ba-cheetah' ),
			'description' => __( 'Define the layout that your page will use. It will be necessary to reload the page after saving', 'ba-cheetah' ),
			'sections'    => $sections
		),
		'css_js'     => array(
			'title'    => 'CSS/JS',
			'sections' => array_merge( array(
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
				'js' => array(
					'title' => 'Javascript',
					'collapsed' => true,
					'fields' => array(
						'js' => array(
							'type'    => 'code',
							'label'   => '',
							'description' => 'jkashd',
							'editor'  => 'javascript',
							'rows'    => '18',
							'preview' => array(
								'type' => 'none',
							),
						),
					)
				),

			), BACheetahTracking::is_facebook_pixel_enabled() ? ['tracking' => array(
				'title' => __('Tracking', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'pixel_events_raw' => array(
						'type' => 'hidden',
						'label' => '<span class="dashicons dashicons-facebook" style="font-size: 20px;line-height: 1; color: #0080fc;"></span>'.
							__('Pixel events', 'ba-cheetah'),
						'help' => __('Facebook Pixel events that will run on this page load. It is recommended not to remove the PageView event. But you can do that if you want to disable it for this page', 'ba-cheetah'),
						'preview' => array(
							'type' => 'none'
						)
					),
					'pixel_events_onload' => array(
						'type'          => 'form',
						'label'         => __('Event', 'ba-cheetah'),
						'form'          => 'pixel_page_events_onload',
						'preview_text'  => 'event',
						'multiple'      => true,
						'limit'         => 5,
						'default' 		=> array(
							array('event' => 'PageView'),
						)
					)
				)
			)] : []),
		),
	)
));
