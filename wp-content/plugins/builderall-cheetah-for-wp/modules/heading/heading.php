<?php

/**
 * @class BACheetahHeadingModule
 */
class BACheetahHeadingModule extends BACheetahModule {

	/**

	 * @return void
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Title', 'ba-cheetah' ),
			'description'     => __( 'Display a title/page heading.', 'ba-cheetah' ),
			'category'        => __( 'Basic', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'heading.svg',
		));
	}


	/**
	 * Returns link rel based on settings.

	 * @return string
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

	public function get_tag() {
		return in_array($this->settings->tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? $this->settings->tag : 'h2';
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahHeadingModule', array(
	'general' => array(
		'title'    => __( 'Content', 'ba-cheetah' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'heading' => array(
						'type'        => 'textarea',
						'label'       => __( 'Heading text', 'ba-cheetah' ),
						'default'     => __( 'Your text here', 'ba-cheetah' ),
						'rows'		  => 3,
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.ba-cheetah-heading-text',
						),
					),
					'tag'     => array(
						'type'    => 'button-group',
						'label'   => __( 'HTML Tag', 'ba-cheetah' ),
						'default' => 'h1',
						'options' => array(
							'h1' => 'h1',
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'h6' => 'h6',
						),
					),
					'link'    => array(
						'type'          => 'link',
						'label'         => __( 'Link', 'ba-cheetah' ),
						'show_target'   => true,
						'show_nofollow' => true,
					),
				),
			),
		),
	),
	'style'   => array(
		'title'    => __( 'Style', 'ba-cheetah' ),
		'sections' => array(
			'colors' => array(
				'title'  => '',
				'fields' => array(

                    'color_type' => array(
                        'label' => __('Color type', 'ba-cheetah'),
                        'type' => 'button-group',
                        'default' => 'solid',
                        'options' => array(
                            'solid' => __('Solid', 'ba-cheetah'),
                            'gradient' => __('Gradient', 'ba-cheetah'),
                        ),
                        'toggle' => array(
                            'solid' => array('fields' => array('color')),
                            'gradient' => array('fields' => array('color_gradient')),
                        )
                    ),
                    'color_gradient' => array(
                        'type'    => 'gradient',
                        'label' => __('Gradient Background', 'ba-cheetah'),
						'enabled' => BA_CHEETAH_PRO,
                        'preview'     => array(
                            'type'      => 'css',
                            'selector'  => '{node} .ba-module__card',
                            'property'  => 'background-image',
                        ),
                    ),
                    'color'      => array(
                        'type'        => 'color',
                        'show_reset'  => true,
                        'show_alpha'  => true,
                        'default'	  => '4054b2',
                        'label'       => __( 'Color', 'ba-cheetah' ),
                        'preview'     => array(
                            'type'      => 'css',
                            'selector'  => '.ba-cheetah-module-content *',
                            'property'  => 'color',
                            'important' => true,
                        ),
                    ),


					'typography' => array(
						'type'       => 'typography',
						'label'      => __( 'Typography', 'ba-cheetah' ),
						'responsive' => true,
						'default'	 => array(
							'font_family' => "Helvetica",
							'font_weight' => 400,
							'text_align' => 'left',
							'font_size' => array(
								'unit' => 'px',
								'length' => '25'
							)
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node}.ba-cheetah-module-heading .ba-cheetah-heading',
							'important' => true,
						),
					),
				),
			),
		),
	),
));
