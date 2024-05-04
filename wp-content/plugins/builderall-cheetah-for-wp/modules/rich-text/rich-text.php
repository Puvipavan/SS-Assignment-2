<?php

/**
 * @class BACheetahRichTextModule
 */
class BACheetahRichTextModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Text', 'ba-cheetah' ),
			'description'     => __( 'A WYSIWYG text editor.', 'ba-cheetah' ),
			'category'        => __( 'Basic', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'text.svg',
		));
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahRichTextModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'text' => array(
						'type'        => 'editor',
						'label'       => '',
						'rows'        => 13,
						'default'	  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elitsed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud',
						'wpautop'     => false,
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.ba-cheetah-rich-text',
						),
						'connections' => array( 'string' ),
					),
				),
			),
		),
	),
	'style'   => array( // Tab
		'title'    => __( 'Style', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'color' => array(
				'title' => '',
				'fields' => array(
					'color'      => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Color', 'ba-cheetah' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-cheetah-rich-text, {node} .ba-cheetah-rich-text *',
							'property'  => 'color',
							'important' => true,
						),
					),
				)
				),
			'style' => array( // Section
				'title'  => __( 'Typography', 'ba-cheetah' ),
				'fields' => array( // Section Fields
					'typography' => array(
						'type'       => 'typography',
						'label'      => '',
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.ba-cheetah-rich-text, .ba-cheetah-rich-text *',
						),
						'default' => array(
							'font_family' => 'Montserrat',
							'font_weight' => '400'
						),
					),
				),
			),
		),
	),
));
