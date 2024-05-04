<?php

BACheetah::register_settings_form('node_template', array(
	'tabs' => array(
		'general' => array(
			'title'    => __( 'General', 'ba-cheetah' ),
			'sections' => array(
				'general' => array(
					'title'  => '',
					'fields' => array(
						'name'   => array(
							'type'  => 'text',
							'label' => _x( 'Name', 'Template name.', 'ba-cheetah' ),
						),
						'global' => array(
							'type'    => 'select',
							'label'   => _x( 'Global', 'Whether this is a global row, column or module.', 'ba-cheetah' ),
							'help'    => __( 'Global rows, columns and elements can be added to multiple pages and edited in one place.', 'ba-cheetah' ),
							'default' => '0',
							'options' => array(
								'0' => __( 'No', 'ba-cheetah' ),
								'1' => __( 'Yes', 'ba-cheetah' ),
							),
						),
					),
				),
			),
		),
	),
));
