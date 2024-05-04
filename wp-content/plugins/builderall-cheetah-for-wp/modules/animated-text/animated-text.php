<?php

/**
 * @class BACheetahAnimatedTextModule
 */
class BACheetahAnimatedTextModule extends BACheetahModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Animated Text', 'ba-cheetah' ),
			'description'     => '',
			'category'        => __( 'Animated', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'animated-text.svg',
		));
	}
}

/**
 * Sub-subform animated text
 */
BACheetah::register_settings_form('animated-text__item__texts', array(
    'title' => __('Texts', 'ba-cheetah'),
    'tabs'  => array(
        'general'      => array(
            'title'         => '',
            'sections'      => array(
                'general'       => array(
                    'title'         => '',
                    'fields'        => array(
                        'text' => array(
                            'type'  => 'text',
                            'label' => __('Text', 'ba-cheetah'),
                        ),
                    )
                ),
            )
        )
    )
));
/**
 * Subform animated text
 */
BACheetah::register_settings_form('animated-text__item', array(
    'title' => __('Animated Text Itens', 'ba-cheetah'),
    'tabs'  => array(
        'general'      => array(
            'title'         => '',
            'sections'      => array(
                'general'       => array(
                    'title'         => '',
                    'fields'        => array(
                        'type' => array(
                            'label' => __('Type', 'ba-cheetah'),
                            'type' => 'select',
                            'default' => 'disabled',
                            'options' => array(
                                'disabled'          => __('No-effect', 'ba-cheetah'),
                                'words'             => __('Words', 'ba-cheetah'),
                                'one_word'          => __('One Word', 'ba-cheetah'),
                            ),
                            'toggle' => array(
                                'disabled' => array(
                                    'fields' => array('text')
                                ),
                                'one_word' => array(
                                    'fields' => array('text', 'effect')
                                ),
                                'words' => array(
                                    'fields' => array('text_items', 'effect')
                                ),
                            ),
                        ),
                        'text' => array(
                            'type'  => 'text',
                            'label' => __('Text', 'ba-cheetah'),
                        ),
                        "text_items" => array(
                            'type'          => 'form',
                            'label'         => __('Texts', 'ba-cheetah'),
                            'form'          => 'animated-text__item__texts',
                            'preview_text'  => 'text',
                            'multiple'      => true,
                            'limit'         => 15,
                            'default' => array(
                                json_encode(array('text' => 'Instagram')),
                                json_encode(array('text' => 'Facebook')),
                                json_encode(array('text' => 'YouTube')),
                            )
                        ),
                        'color'      => array(
                            'type'        => 'color',
                            'connections' => array( 'color' ),
                            'label'       => __( 'Color', 'ba-cheetah' ),
                            'show_reset'  => true,
                            'default'     => '000',
                            'show_alpha'  => true,
                        ),
                        'effect' => array(
                            'label' => __('Effect', 'ba-cheetah'),
                            'type' => 'select',
                            'default' => 'none',
                            'options' => array(
                                'none'              => __('None', 'ba-cheetah'),
                                'slidingVertical'   => __('Vertical Slide', 'ba-cheetah'),
                                'slidingHorizontal' => __('Horizontal Slide', 'ba-cheetah'),
                                'fadeIn'            => __('Fade In', 'ba-cheetah'),
                                'verticalFlip'      => __('Vertical Flip', 'ba-cheetah'),
                                'horizontalFlip'    => __('Horizontal Flip', 'ba-cheetah'),
                                'antiClock'         => __('Anti Clock', 'ba-cheetah'),
                                'clockWise'         => __('Clock Wise', 'ba-cheetah'),
                                'popEffect'         => __('Pop', 'ba-cheetah'),
                                'pushEffect'        => __('Push', 'ba-cheetah'),
                            ),
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
BACheetah::register_module('BACheetahAnimatedTextModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
                    "items" => array(
                        'type'          => 'form',
                        'label'         => __('Texts', 'ba-cheetah'),
                        'form'          => 'animated-text__item',
                        'preview_text'  => 'text',
                        'multiple'      => true,
                        'limit'         => 15,
                        'default' => array(
                            array(
                                'type' => 'disabled',
                                'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ',
                            ),
                            array(
                                'type' => 'words',
                                'effect' => 'pushEffect',
                                'text_items' => array(
                                    json_encode(array('text' => 'vitae')),
                                    json_encode(array('text' => 'nisl')),
                                    json_encode(array('text' => 'eros')),
                                    json_encode(array('text' => 'massa')),
                                ),
                            )
                        )
                    ),
				),
			),
		),
	),
	'style'   => array( // Tab
		'title'    => __( 'Style', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'style' => array( // Section
				'title'  => __( 'Typography', 'ba-cheetah' ),
				'fields' => array( // Section Fields
					'typography' => array(
						'type'       => 'typography',
						'label'      => '',
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '{node} .ba-module__animated-text *',
						),
					),
				),
			),
		),
	),
));
