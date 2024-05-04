<?php

/**
 * @class BACheetahSlideshowModule
 */
class BACheetahSlideshowModule extends BACheetahModule {

	public static $array_nav_types = [  'prev' => 'Previous',
										'thumbs' => 'Thumbs',
										'caption' => 'Caption',
										/*'social' => 'Social',
										'buy' => 'Buy',*/
										'play' => 'Play',
										'fullscreen' => 'Fullscreen',
										'next' => 'Next'
									  ];

	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Slideshow', 'ba-cheetah' ),
			'description'     => __( 'Display multiple photos in a slideshow view.', 'ba-cheetah' ),
			'category'        => __( 'Media', 'ba-cheetah' ),
			'editor_export'   => false,
			'partial_refresh' => true,
			'icon'            => 'slider.svg',
		));

		$this->add_js( 'yui3' );
		$this->add_js( 'ba-cheetah-slideshow' );
		$this->add_css( 'ba-cheetah-slideshow' );
	}
	
	public static function getButtonsSection() {
		$return = [];
		$option = array(
			'none' => __( 'None', 'ba-cheetah' ),
			'left' => __( 'Left', 'ba-cheetah' ),
			'center'  => __( 'Center', 'ba-cheetah' ),
			'right'  => __( 'Right', 'ba-cheetah' )
		);
		
		foreach (BACheetahSlideshowModule::$array_nav_types as $key => $value) {

			$strEval = '$return["nav_icon_' . $key . '"] = array(
					"type"    => "select",
					"label"   => __( "'  . $value . ' Icon", "ba-cheetah" ),
					"options" => $option,
					"help"    => __( "Set the position to show this option", "ba-cheetah" ),
				);';

			eval($strEval);
		}
		
		return $return;
	}

	public function update( $settings ) {

        $settings->photo_data = $this->get_source();

		return $settings;
	}

	public function get_source() {

        $photos = $this->get_photos();

        if ( count( $photos ) > 0 ) {

            $source  = 'type: "urls", urls:';
            $objects = array();

            foreach ( $photos as $photo ) {
                $caption   = str_replace( array( "\r", "\n" ), '', nl2br( htmlspecialchars( $photo->caption ) ) );
                $alt       = empty( $photo->alt ) ? '' : esc_attr( $photo->alt );
                $urls      = '{' . "\n";
                $urls .= 'thumbURL: "' . $photo->thumbURL . '",';
                $urls .= 'largeURL: "' . $photo->largeURL . '",';
                $urls .= 'x3largeURL: "' . $photo->x3largeURL . '",';
                $urls     .= 'caption: "' . $caption . '",';
                $urls     .= 'alt: "' . $alt . '",';
                $urls     .= '}';
                $objects[] = $urls;
            }

            return $source . '[' . implode( ',', $objects ) . ']';
        }

        return '';

	}

	/**
	 * @method get_photos
	 */
	public function get_photos() {
		$photos   = array();
		$ids      = $this->settings->photos;

		if ( empty( $ids ) ) {
			return $photos;
		}

		foreach ( $ids as $id ) {

			$photo = BACheetahPhoto::get_attachment_data( $id );

			// Use the cache if we didn't get a photo from the id.
			if ( ! $photo ) {

                if (! isset( $this->settings->photo_data )) {
                    continue;
                }

                if (is_array( $this->settings->photo_data )) {
                    $photos[ $id ] = $this->settings->photo_data[ $id ];
                } elseif ( is_object( $this->settings->photo_data ) ) {
                    $photos[ $id ] = $this->settings->photo_data->{$id};
                } else {
                    continue;
                }
            }

			// Only use photos who have the sizes object.
			if ( isset( $photo->sizes ) ) {

				// Photo data object
				$data          = new stdClass();
				$data->caption = $photo->caption;
				$data->alt     = $photo->alt;

				// Photo sizes
				if ( isset( $photo->sizes->large ) ) {

					$data->largeURL = $photo->sizes->large->url;

					if ( $photo->sizes->full->width <= 2560 ) {
						$data->x3largeURL = $photo->sizes->full->url;
					} else {
						$data->x3largeURL = $photo->sizes->large->url;
					}
				} else {
					$data->largeURL = $photo->sizes->full->url;
					$data->x3largeURL = $photo->sizes->full->url;
				}

				// Thumb size
				if ( isset( $photo->sizes->thumbnail ) ) {
					$data->thumbURL = $photo->sizes->thumbnail->url;
				} else {
					$data->thumbURL = $photo->sizes->full->url;
				}

				// Push the photo data
				$photos[ $id ] = $data;
			}
		}

		return $photos;
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahSlideshowModule', array(
	'general'  => array(
		'title'    => __( 'General', 'ba-cheetah' ),
		'sections' => array(
			'general'      => array(
				'title'  => '',
				'fields' => array(
					'photos'   => array(
						'type'        => 'multiple-photos',
						'label'       => __( 'Photos', 'ba-cheetah' ),
						'connections' => array( 'multiple-photos' ),
					),
				),
			),
			'display'      => array(
				'title'  => __( 'Display', 'ba-cheetah' ),
				'fields' => array(
					'height'  => array(
						'type'     => 'unit',
						'label'    => __( 'Height', 'ba-cheetah' ),
						'default'  => '600',
						'sanitize' => 'absint',
						'units'    => array( 'px' ),
						'slider'   => array(
							'step' => 10,
							'max'  => 1000,
						),
					),
					'crop'    => array(
						'type'    => 'select',
						'label'   => __( 'Crop', 'ba-cheetah' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'No', 'ba-cheetah' ),
							'1' => __( 'Yes', 'ba-cheetah' ),
						),
						'help'    => __( 'Crop set to no will fit the slideshow images to the height you specify and keep the width proportional, whereas crop set to yes will fit the slideshow images to all sides of the content area while cropping the left and right to fit the height you specify.', 'ba-cheetah' ),
					),
					'protect' => array(
						'type'    => 'select',
						'label'   => __( 'Disable Right-Click', 'ba-cheetah' ),
						'default' => 'true',
						'options' => array(
							'false' => __( 'No', 'ba-cheetah' ),
							'true'  => __( 'Yes', 'ba-cheetah' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			)
		),
	),
	'playback' => array(
		'title'    => __( 'Playback', 'ba-cheetah' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'auto_play'          => array(
						'type'    => 'select',
						'label'   => __( 'Auto Play', 'ba-cheetah' ),
						'default' => 'true',
						'options' => array(
							'false' => __( 'No', 'ba-cheetah' ),
							'true'  => __( 'Yes', 'ba-cheetah' ),
						),
					),
					'speed'              => array(
						'type'     => 'unit',
						'label'    => __( 'Speed', 'ba-cheetah' ),
						'default'  => '3',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array( 'seconds' ),
						'slider'   => true,
					),
					'transition'         => array(
						'type'    => 'select',
						'label'   => __( 'Transition', 'ba-cheetah' ),
						'default' => 'fade',
						'options' => array(
							'none'            => _x( 'None', 'Slideshow transition.', 'ba-cheetah' ),
							'fade'            => __( 'Fade', 'ba-cheetah' ),
							'kenBurns'        => __( 'Ken Burns', 'ba-cheetah' ),
							'slideHorizontal' => __( 'Slide Horizontal', 'ba-cheetah' ),
							'slideVertical'   => __( 'Slide Vertical', 'ba-cheetah' ),
							'blinds'          => __( 'Blinds', 'ba-cheetah' ),
							'bars'            => __( 'Bars', 'ba-cheetah' ),
							'barsRandom'      => __( 'Random Bars', 'ba-cheetah' ),
							'boxes'           => __( 'Boxes', 'ba-cheetah' ),
							'boxesRandom'     => __( 'Random Boxes', 'ba-cheetah' ),
							'boxesGrow'       => __( 'Boxes Grow', 'ba-cheetah' ),
						),
					),
					'transitionDuration' => array(
						'type'     => 'unit',
						'label'    => __( 'Transition Speed', 'ba-cheetah' ),
						'default'  => '2',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array( 'seconds' ),
						'slider'   => true,
					),
					'randomize'          => array(
						'type'    => 'select',
						'label'   => __( 'Randomize Photos', 'ba-cheetah' ),
						'default' => 'false',
						'options' => array(
							'false' => __( 'No', 'ba-cheetah' ),
							'true'  => __( 'Yes', 'ba-cheetah' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			),
		),
	),
	'controls' => array(
		'title'    => __( 'Controls', 'ba-cheetah' ),
		'sections' => array(
			'navigation'             => array(
				'title'  => 'Navigation',
				'fields' => array(
					'image_nav' => array(
						'type'    => 'select',
						'label'   => __( 'Navigation Arrows', 'ba-cheetah' ),
						'default' => 'true',
						'options' => array(
							'false' => __( 'No', 'ba-cheetah' ),
							'true'  => __( 'Yes', 'ba-cheetah' ),
						),
						'help'    => __( 'Navigational arrows allow the visitor to freely move through the images in your slideshow. These are larger arrows that overlay your slideshow images and are separate from the control bar navigational arrows.', 'ba-cheetah' ),
					),
					'nav_type' => array(
						'type'    => 'select',
						'enabled' => BACheetahAuthentication::is_pro_user(),
						'label'   => __( 'Navigation Type', 'ba-cheetah' ),
						'default' => 'none',
						'options' => array(
							'none' => __( 'None', 'ba-cheetah' ),
							'buttons' => __( 'Buttons', 'ba-cheetah' ),
							'thumbs'  => __( 'Thumbs', 'ba-cheetah' ),
						),
						'hide' => array(
							'none' => array(
								'sections' => array('buttons', 'thumbs', 'overlay', 'caption'),
								'fields' => array('nav_position')
							),
							'buttons' => array(
								'sections' => array('thumbs'),
							),
							'thumbs' => array(
								'sections' => array('buttons', 'caption'),
							),
						),
					),
					'nav_position' => array(
						'type'    => 'select',
						'enabled' => BACheetahAuthentication::is_pro_user(),
						'label'   => __( 'Navigation Position', 'ba-cheetah' ),
						'default' => 'bottom',
						'options' => array(
							'top' => __( 'Top', 'ba-cheetah' ),
							'bottom' => __( 'Bottom', 'ba-cheetah' ),
						),
					),
				),
			),
			'buttons'             => array(
				'title'  => 'Navigation Buttons',
				'enabled' => BACheetahAuthentication::is_pro_user(),
				'fields' => BACheetahSlideshowModule::getButtonsSection(),
			),
			'thumbs'             => array(
				'title'  => 'Thumbs',
				'enabled' => BACheetahAuthentication::is_pro_user(),
				'fields' => array(
					'thumbs_pause_click' => array(
						'type'    => 'select',
						'label'   => __( 'Pause on Click', 'ba-cheetah' ),
						'default' => 'false',
						'options' => array(
							'true' => __( 'Yes', 'ba-cheetah' ),
							'false' => __( 'No', 'ba-cheetah' ),
						),
					),
					'thumbs_transition_duration'    => array(
						'type'     => 'unit',
						'label'    => __( 'Transition Speed', 'ba-cheetah' ),
						'default'  => '2',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array( 'seconds' ),
						'slider'   => true,
					),
					'thumbs_horizontal_spacing'              => array(
						'type'     => 'unit',
						'label'    => __( 'Horizontal Spacing', 'ba-cheetah' ),
						'default'  => '15',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array( 'px' ),
						'slider'   => true,
					),
					'thumbs_vertical_spacing'              => array(
						'type'     => 'unit',
						'label'    => __( 'Vertical Spacing', 'ba-cheetah' ),
						'default'  => '15',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array( 'px' ),
						'slider'   => true,
					),
					'thumbs_image_width'   => array(
						'type'     => 'unit',
						'label'    => __( 'Image Width', 'ba-cheetah' ),
						'default'  => '50',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array( 'px' ),
						'slider'   => true,
					),
					'thumbs_image_height'   => array(
						'type'     => 'unit',
						'label'    => __( 'Image Height', 'ba-cheetah' ),
						'default'  => '50',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array( 'px' ),
						'slider'   => true,
					),
				),
			),
			'overlay'             => array(
				'title'  => 'Overlay',
				'enabled' => BACheetahAuthentication::is_pro_user(),
				'fields' => array(
					'nav_overlay' => array(
						'type'    => 'select',
						'label'   => __( 'Navigation Menu Overlay', 'ba-cheetah' ),
						'default' => 'true',
						'options' => array(
							'true' => __( 'Yes', 'ba-cheetah' ),
							'false' => __( 'No', 'ba-cheetah' ),
						),
					),
					'nav_overlay_hide_move' => array(
						'type'    => 'select',
						'label'   => __( 'Hide on mouse move', 'ba-cheetah' ),
						'default' => 'false',
						'options' => array(
							'true' => __( 'Yes', 'ba-cheetah' ),
							'false' => __( 'No', 'ba-cheetah' ),
						),
						'toggle' => array(
							'true' => array(
								'fields' => array('nav_overlay_delay')
							)
						),
					),
					'nav_overlay_delay'              => array(
						'type'     => 'unit',
						'label'    => __( 'Delay to hide', 'ba-cheetah' ),
						'default'  => '3',
						'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
						'units'    => array( 'seconds' ),
						'slider'   => true,
						'help'     => __( 'Set the time in seconds to hide the overlay menu', 'ba-cheetah' ),
					),

					
				),
			),
			'caption'             => array(
				'title'  => 'Caption',
				'enabled' => BACheetahAuthentication::is_pro_user(),
				'fields' => array(
					'caption_more_link_text' => array(
						'type'        => 'textarea',
						'label'       => __( 'Link More Text', 'ba-cheetah' ),
						'default'     => __( 'Read More', 'ba-cheetah' ),
						'rows'		  => 3,
					),
					'caption_less_link_text' => array(
						'type'        => 'textarea',
						'label'       => __( 'Link Less Text', 'ba-cheetah' ),
						'default'     => __( 'Read Less', 'ba-cheetah' ),
						'rows'		  => 3,
					),
					'caption_text_length' => array(
						'type' => 'unit',
						'label' => __('Text length', 'ba-cheetah'),
						'help' => __('Number of words that will be displayed in the caption', 'ba-cheetah'),
						'units' => array('words'),
						'slider' => true,
						'default' => '200',
						'default_unit' => 'words',
					),
					'caption_strip_tags' => array(
						'type'    => 'select',
						'label'   => __( 'Strip tags', 'ba-cheetah' ),
						'help' => __('Strips out HTML tags from the caption text', 'ba-cheetah'),
						'default' => 'true',
						'options' => array(
							'true' => __( 'Yes', 'ba-cheetah' ),
							'false' => __( 'No', 'ba-cheetah' ),
						),
					),
				),
			),

					
		),
	),
));
