<?php

$global_settings = BACheetahModel::get_global_settings();

function buildViewPortFormItemToggle($label, $options, $default): array {
    return array(
        'label' => __($label, 'ba-cheetah'),
        'type' => 'select',
        'default' => $default,
        'options' => $options
    );
}


BACheetah::register_settings_form('module_advanced', array(
	'title'    => __( 'Advanced', 'ba-cheetah' ),
	'sections' => array(
		'margins'       => array(
			'title'  => __( 'Spacing', 'ba-cheetah' ),
			'fields' => array(
				'margin' => array(
					'type'       => 'dimension',
					'label'      => __( 'Margins', 'ba-cheetah' ),
					'slider'     => array(
						'min' => '0',
						'max' => '500',
						'step' => '1'
					),
					'units'      => array(
						'px',
						'%',
					),
					'preview'    => array(
						'type'     => 'css',
						'selector' => '.ba-cheetah-module-content',
						'property' => 'margin',
					),
					'responsive' => array(
						'default_unit' => array(
							'default'    => $global_settings->module_margins_unit,
							'medium'     => $global_settings->module_margins_medium_unit,
							'responsive' => $global_settings->module_margins_responsive_unit,
						),
						'placeholder'  => array(
							'default'    => empty( $global_settings->module_margins ) ?
                                '0' :
                                $global_settings->module_margins,
							'medium'     => empty( $global_settings->module_margins_medium ) ?
                                '0' :
                                $global_settings->module_margins_medium,
							'responsive' => empty( $global_settings->module_margins_responsive ) ?
                                '0' :
                                $global_settings->module_margins_responsive,
						),
					),
				),
			),
		),
		'visibility'    => array(
			'title'  => __( 'Visibility', 'ba-cheetah' ),
			'collapsed' => true,
			'fields' => array(
				'responsive_display_warning' => array(
					'type' => 'raw',
					'content' => __('Visibility settings can only be viewed in preview mode. 
					While you are editing, all elements will be visible.', 'ba-cheetah' ),
				),
				'responsive_display'         => array(
					'type'    => 'select',
					'label'   => __( 'Breakpoint', 'ba-cheetah' ),
					'options' => array(
						''               => __( 'Always', 'ba-cheetah' ),
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
					'description' => sprintf( __( 'Optional. Set the <a%s>capability</a> required for users to view this module.', 'ba-cheetah' ),
                        ' href="https://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table" target="_blank"' ),
					'preview'     => array(
						'type' => 'none',
					),
				),
			),
		),
		'animation'     => array(
			'title'  => __( 'Animation', 'ba-cheetah' ),
			'collapsed' => true,
			'fields' => array(
				'animation' => array(
					'type'    => 'animation',
					// 'label'   => __( 'Animation', 'ba-cheetah' ),
					'preview' => array(
						'type'     => 'animation',
						'selector' => '{node}',
					),
				),
			),
		),
		'scroll_animation'     => array(
			'title'  => __( 'Scroll Animation', 'ba-cheetah' ),
			'collapsed' => true,
            'enabled' => BA_CHEETAH_PRO,
			'fields' => array(
                'scroll_animation_warning' => array(
                    'type' => 'raw',
                    'content' => '<p class="ba-cheetah-settings-tab-description" style="margin-top: 0">'.
                        __('Use scroll animation to create amazing animations and interactions when the user scrolls through the page.
To make the effect more interesting, we use the page height and not just the viewport height (ie the visible size of the screen). 
All scroll effects also work on mobile devices.', 'ba-cheetah' ).'</p>',
                ),
                'vertical_effect' => array(
                    'label'       => __('Vertical effect', 'ba-cheetah'),
                    'type'        => 'button-group',
                    'default'     => '0',
                    'options' => array(
                        '0'       => __('Disabled', 'ba-cheetah'),
                        'active'  => __('Active', 'ba-cheetah'),
                    ),
                    'toggle'  => array(
                        'active' => array(
                            'fields' => array(
                                'vertical_effect_direction',
                                'vertical_viewport',
                            ),
                        ),
                    ),
                ),
                'vertical_effect_direction'  => buildViewPortFormItemToggle(
                    'Direction',
                    array(
                        'up'          => __('Up', 'ba-cheetah'),
                        'down'        => __('Down', 'ba-cheetah'),
                    ),
                    'down'),
                'vertical_viewport' => array(
                    'type'    => 'viewport',
                    'preview' => array(
                        'type'     => 'viewport',
                        'selector' => '{node}',
                    ),
                ),
                'hr0' => array(
                    'type' => 'raw',
                    'content' => '<hr>'
                ),
                'horizontal_effect' => array(
                    'label'       => __('Horizontal effect', 'ba-cheetah'),
                    'type'        => 'button-group',
                    'default'     => '0',
                    'options' => array(
                        '0'       => __('Disabled', 'ba-cheetah'),
                        'active'  => __('Active', 'ba-cheetah'),
                    ),
                    'toggle'  => array(
                        'active' => array(
                            'fields' => array(
                                'horizontal_effect_direction',
                                'horizontal_viewport',
                            ),
                        ),
                    ),
                ),
                'horizontal_effect_direction'  => buildViewPortFormItemToggle(
                    'Direction',
                    array(
                        'left'         => __('Left', 'ba-cheetah'),
                        'right'        => __('Right', 'ba-cheetah'),
                    ),
                    'left'),
                'horizontal_viewport' => array(
                    'type'    => 'viewport',
                    'preview' => array(
                        'type'     => 'viewport',
                        'selector' => '{node}',
                    ),
                ),
                'hr1' => array(
                    'type' => 'raw',
                    'content' => '<hr>'
                ),
                'rotate_effect' => array(
                    'label'       => __('Rotate effect', 'ba-cheetah'),
                    'type'        => 'button-group',
                    'default'     => '0',
                    'options' => array(
                        '0'       => __('Disabled', 'ba-cheetah'),
                        'active'  => __('Active', 'ba-cheetah'),
                    ),
                    'toggle'  => array(
                        'active' => array(
                            'fields' => array(
                                'rotate_effect_direction',
                                'rotate_viewport',
                            ),
                        ),
                    ),
                ),
                'rotate_effect_direction'  => buildViewPortFormItemToggle(
                    'Direction',
                    array(
                        'left'         => __('Left', 'ba-cheetah'),
                        'right'        => __('Right', 'ba-cheetah'),
                    ),
                    'left'),
                'rotate_viewport' => array(
                    'type'    => 'viewport',
                    'preview' => array(
                        'type'     => 'viewport',
                        'selector' => '{node}',
                    ),
                ),
                'hr2' => array(
                    'type' => 'raw',
                    'content' => '<hr>'
                ),
                'scale_effect' => array(
                    'label'       => __('Scale effect', 'ba-cheetah'),
                    'type'        => 'button-group',
                    'default'     => '0',
                    'options' => array(
                        '0'       => __('Disabled', 'ba-cheetah'),
                        'active'  => __('Active', 'ba-cheetah'),
                    ),
                    'toggle'  => array(
                        'active' => array(
                            'fields' => array(
                                'scale_effect_direction',
                                'scale_viewport',
                            ),
                        ),
                    ),
                ),
                'scale_effect_direction'  => buildViewPortFormItemToggle(
                    'Direction',
                    array(
                        'up'         => __('Scale Up', 'ba-cheetah'),
                        'down'       => __('Scale Down', 'ba-cheetah'),
                        'down-up'    => __('Scale Down Up', 'ba-cheetah'),
                        'up-down'    => __('Scale Up Down', 'ba-cheetah'),
                    ),
                    'up'),
                'scale_viewport' => array(
                    'type'    => 'viewport',
                    'preview' => array(
                        'type'     => 'viewport',
                        'selector' => '{node}',
                    ),
                ),
                'hr3' => array(
                    'type' => 'raw',
                    'content' => '<hr>'
                ),
                'blur_effect' => array(
                    'label'       => __('Blur effect', 'ba-cheetah'),
                    'type'        => 'button-group',
                    'default'     => '0',
                    'options' => array(
                        '0'       => __('Disabled', 'ba-cheetah'),
                        'active'  => __('Active', 'ba-cheetah'),
                    ),
                    'toggle'  => array(
                        'active' => array(
                            'fields' => array(
                                'blur_effect_direction',
                                'blur_viewport',
                            ),
                        ),
                    ),
                ),
                'blur_effect_direction'  => buildViewPortFormItemToggle(
                    'Direction',
                    array(
                        'in'        => __('Fade in', 'ba-cheetah'),
                        'out'       => __('Fade out', 'ba-cheetah'),
                        'out-in'    => __('Fade out in', 'ba-cheetah'),
                        'in-out'    => __('Fade in out', 'ba-cheetah'),
                    ),
                    'up'),
                'blur_viewport' => array(
                    'type'    => 'viewport',
                    'preview' => array(
                        'type'     => 'viewport',
                        'selector' => '{node}',
                    ),
                ),
                'hr4' => array(
                    'type' => 'raw',
                    'content' => '<hr>'
                ),
                'fade_effect' => array(
                    'label'       => __('Transparency effect', 'ba-cheetah'),
                    'type'        => 'button-group',
                    'default'     => '0',
                    'options' => array(
                        '0'       => __('Disabled', 'ba-cheetah'),
                        'active'  => __('Active', 'ba-cheetah'),
                    ),
                    'toggle'  => array(
                        'active' => array(
                            'fields' => array(
                                'fade_effect_direction',
                                'fade_viewport',
                            ),
                        ),
                    ),
                ),
                'fade_effect_direction'  => buildViewPortFormItemToggle(
                    'Direction',
                    array(
                        'in'        => __('Fade in', 'ba-cheetah'),
                        'out'       => __('Fade out', 'ba-cheetah'),
                        'out-in'    => __('Fade out in', 'ba-cheetah'),
                        'in-out'    => __('Fade in out', 'ba-cheetah'),
                    ),
                    'up'),
                'fade_viewport' => array(
                    'type'    => 'viewport',
                    'preview' => array(
                        'type'     => 'viewport',
                        'selector' => '{node}',
                    ),
                ),
			),
		),
		'mouse_animation'     => array(
			'title'  => __( 'Mouse Animation', 'ba-cheetah' ),
			'collapsed' => true,
            'enabled' => BA_CHEETAH_PRO,
			'fields' => array(
                'scroll_animation_warning' => array(
                    'type' => 'raw',
                    'content' => '<p class="ba-cheetah-settings-tab-description" style="margin-top: 0">'.
                        __("Create a sense of depth by making elements move in relation to the visitor’s mouse movement.
Once you switch the mouse to track on, the element will react and move right, left, up, or down in accordance with the cursor. 
Set the direction as opposite or direct to the mouse move, and increase the level of the effect by raising the speed scale.
The mouse effect won't work on tablets and mobile.", 'ba-cheetah' ).'</p>',
                ),
                'mouse_effect' => array(
                    'label'       => __('Mouse effect', 'ba-cheetah'),
                    'type'        => 'button-group',
                    'default'     => '0',
                    'options' => array(
                        '0'       => __('Disabled', 'ba-cheetah'),
                        'active'  => __('Active', 'ba-cheetah'),
                    ),
                    'toggle'  => array(
                        'active' => array(
                            'fields' => array(
                                'mouse_effect_direction',
                                'mouse_speed',
                            ),
                        ),
                    ),
                ),
                'mouse_effect_direction'  => buildViewPortFormItemToggle(
                    'Direction',
                    array(
                        'in'        => __('Track', 'ba-cheetah'),
                        'out'       => __('Opposite', 'ba-cheetah'),
                    ),
                    'in'),
                'mouse_speed' => array(
                    'type'     => 'unit',
                    'label'    => __('Speed', 'ba-cheetah'),
                    'default'  => '5',
                    'sanitize' => 'absint',
                    'slider' => array(
                        'min' => 1,
                        'max' => 20,
                        'step' => 1
                    ),
                ),
                'hr0' => array(
                    'type' => 'raw',
                    'content' => '<hr>'
                ),
                'mouse_effect_3d' => array(
                    'label'       => __('3D effect', 'ba-cheetah'),
                    'type'        => 'button-group',
                    'default'     => '0',
                    'options' => array(
                        '0'       => __('Disabled', 'ba-cheetah'),
                        'active'  => __('Active', 'ba-cheetah'),
                    ),
                    'toggle'  => array(
                        'active' => array(
                            'fields' => array(
                                'mouse_speed_3d',
                            ),
                        ),
                    ),
                ),
                'mouse_speed_3d' => array(
                    'type'     => 'unit',
                    'label'    => __('Intensity', 'ba-cheetah'),
                    'default'  => '5',
                    'sanitize' => 'absint',
                    'slider' => array(
                        'min' => 1,
                        'max' => 20,
                        'step' => 1
                    ),
                ),
			),
		),
		'css_selectors' => array(
			'title'  => __( 'HTML Element', 'ba-cheetah' ),
			'collapsed' => true,
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
					'help'    => __( 'Optional. Choose an appropriate HTML5 content sectioning element to use for this 
					module to improve accessibility and machine-readability.', 'ba-cheetah' ),
					'preview' => array(
						'type' => 'none',
					),
				),
				'id'                => array(
					'type'    => 'text',
					'label'   => __( 'ID', 'ba-cheetah' ),
					'help'    => __( "A unique ID that will be applied to this element's HTML. Must start with a letter 
					and only contain dashes, underscores, letters or numbers. No spaces.", 'ba-cheetah' ),
					'preview' => array(
						'type' => 'none',
					),
				),
				'class'             => array(
					'type'    => 'text',
					'label'   => __( 'Class', 'ba-cheetah' ),
					'help'    => __( "A class that will be applied to this module's HTML. Must start with a letter and 
					only contain dashes, underscores, letters or numbers. Separate multiple classes with spaces.", 'ba-cheetah' ),
					'preview' => array(
						'type' => 'none',
					),
				),
			),
		),
		'export_import' => array(
			'title'  => __( 'Export/Import', 'ba-cheetah' ),
			'collapsed' => true,
			'fields' => array(
				'export' => array(
					'type'    => 'raw',
					'label'   => __( 'Export', 'ba-cheetah' ),
					'preview' => 'none',
					'content' => '<button style="margin-right:10px" class="ba-cheetah-button ba-cheetah-button-light module-export-all" title="Copy Settings">
Copy Settings</button><button class="ba-cheetah-button ba-cheetah-button-light module-export-style" title="Copy Styles">Copy Styles</button>',
				),
				'import' => array(
					'type'    => 'raw',
					'label'   => __( 'Import', 'ba-cheetah' ),
					'preview' => 'none',
					'content' => '<div class="module-import-wrap"><input type="text" class="module-import-input" placeholder="Paste settings or styles here..." />
<button class="ba-cheetah-button ba-cheetah-button-light module-import-apply">Import</button></div><div class="module-import-error"></div>',
				),
			),
		),
	),
));
