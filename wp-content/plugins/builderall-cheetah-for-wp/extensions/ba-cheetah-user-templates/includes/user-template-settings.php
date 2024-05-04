<?php

BACheetah::register_settings_form('user_template', array(
	'title' => __( 'Save Template', 'ba-cheetah' ),
	'tabs'  => array(
		'general' => array(
			'title'       => __( 'General', 'ba-cheetah' ),
			'description' => __( 'Save the current layout as a template that can be reused under <strong>Templates &rarr; Saved Templates</strong>.', 'ba-cheetah' ),
			'sections'    => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'name' => array(
							'type'  => 'text',
							'label' => _x( 'Name', 'Template name.', 'ba-cheetah' ),
						),
					),
				),
			),
		),
	),
));
