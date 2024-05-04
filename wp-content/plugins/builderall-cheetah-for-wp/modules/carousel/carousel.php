<?php

/**
 * @class BACheetahCarouselModule
 */
class BACheetahCarouselModule extends BACheetahModule {

    public function __construct() {
        parent::__construct(
            array(
                'name' => __('Carousel', 'ba-cheetah'),
                'description' => __('Display multiple photos in a carousel view.', 'ba-cheetah'),
                'category' => __('Media', 'ba-cheetah'),
                'editor_export' => false,
                'partial_refresh' => true,
                'icon' => 'carousel.svg',
                'version'=> 2
            )
        );

    }

    public function enqueue_scripts() {
        $this->add_js('swiper');
        $this->add_css('swiper');

        $this->add_js('keen-slider');
        $this->add_css('keen-slider');
    }

    public function update($settings) {

        $settings->photo_data = $this->get_source();

        $settings->version = 2;


        return $settings;
    }

    public function get_source() {

        $photos = $this->get_photos();

        if (count($photos) > 0) {

            $source = 'type: "urls", urls:';
            $objects = array();

            foreach ($photos as $photo) {
                $caption = str_replace(array("\r", "\n"), '', nl2br(htmlspecialchars($photo->caption)));
                $alt = empty($photo->alt) ? '' : esc_attr($photo->alt);
                $urls = '{' . "\n";
                $urls .= 'thumbURL: "' . $photo->thumbURL . '",';
                $urls .= 'largeURL: "' . $photo->largeURL . '",';
                $urls .= 'x3largeURL: "' . $photo->x3largeURL . '",';
                $urls .= 'caption: "' . $caption . '",';
                $urls .= 'alt: "' . $alt . '",';
                $urls .= '}';
                $objects[] = $urls;
            }

            return $source . '[' . implode(',', $objects) . ']';
        }

        return '';

    }

    /**
     * @method get_photos
     */
    public function get_photos() {
        $photos = array();
        $ids = $this->settings->photos;

        if (empty($ids)) {
            return $photos;
        }

        foreach ($ids as $id) {

            $photo = BACheetahPhoto::get_attachment_data($id);

            // Use the cache if we didn't get a photo from the id.
            if (!$photo) {

                if (!isset($this->settings->photo_data)) {
                    continue;
                }

                if (is_array($this->settings->photo_data)) {
                    $photos[$id] = $this->settings->photo_data[$id];
                } elseif (is_object($this->settings->photo_data)) {
                    $photos[$id] = $this->settings->photo_data->{$id};
                } else {
                    continue;
                }
            }

            // Only use photos who have the sizes object.
            if (isset($photo->sizes)) {

                // Photo data object
                $data = new stdClass();
                $data->caption = $photo->caption;
                $data->alt = $photo->alt;

                // Photo sizes
                if (isset($photo->sizes->large)) {

                    $data->largeURL = $photo->sizes->large->url;

                    if ($photo->sizes->full->width <= 2560) {
                        $data->x3largeURL = $photo->sizes->full->url;
                    } else {
                        $data->x3largeURL = $photo->sizes->large->url;
                    }
                } else {
                    $data->largeURL = $photo->sizes->full->url;
                    $data->x3largeURL = $photo->sizes->full->url;
                }

                // Thumb size
                if (isset($photo->sizes->thumbnail)) {
                    $data->thumbURL = $photo->sizes->thumbnail->url;
                } else {
                    $data->thumbURL = $photo->sizes->full->url;
                }

                // Push the photo data
                $photos[$id] = $data;
            }
        }

        return $photos;
    }

    public function render_carousel($id) {

        if($this->settings->version === 2){

            echo '<div class="swiper" id="'. $id .'">
            <div class="swiper-wrapper">';

            foreach ($this->get_photos() as $photo) {
                echo '<div class="swiper-slide">';
                echo BACheetah::render_module_html('photo', array('crop' => false, 'link_target' => '_self', 'link_type' => 'none', 'link_url' => $photo->largeURL, 'photo' => $photo->largeURL, 'photo_src' => $photo->largeURL,));
                echo '</div>';
            }
            echo '</div>
         <div class="swiper-pagination"></div>';


            if ($this->settings->styleGallery === 'thumbnail') {
                echo '<div id="' . $id . '-thumbnails" class="swiper thumbnail" thumbsSlider="">
                <div class="swiper-wrapper">';

                foreach ($this->get_photos() as $photo) {
                    echo '<div class="swiper-slide">';
                    echo BACheetah::render_module_html('photo', array('crop' => false, 'link_target' => '_self', 'link_type' => 'none', 'link_url' => $photo->largeURL, 'photo' => $photo->largeURL, 'photo_src' => $photo->largeURL,));
                    echo '</div>';
                }
                echo '</div></div>';
            }
            echo '</div>';
        }else{
            if ($this->settings->styleGallery === 'carousel') {
                echo '<div class="wrapper">
                        <div class="scene">
                            <div id="' . $id . '" class="carousel keen-slider keen-slider__parent">';

                foreach ($this->get_photos() as $photo) {
                    echo '<div class="keen-slider__slide carousel__cell">';
                    echo BACheetah::render_module_html('photo', array('crop' => false, 'link_target' => '_self', 'link_type' => 'none', 'link_url' => $photo->largeURL, 'photo' => $photo->largeURL, 'photo_src' => $photo->largeURL,));
                    echo '</div>';
                }
                echo '</div>
                        </div>
                    </div>';
            } elseif ($this->settings->styleGallery === 'thumbnail') {
                echo '<div id="' . $id . '" class="keen-slider keen-slider__parent">';

                foreach ($this->get_photos() as $photo) {
                    echo '<div class="keen-slider__slide">';
                    echo BACheetah::render_module_html('photo', array('crop' => false, 'link_target' => '_self', 'link_type' => 'none', 'link_url' => $photo->largeURL, 'photo' => $photo->largeURL, 'photo_src' => $photo->largeURL,));
                    echo '</div>';
                }

                echo '</div>
                        <div  id="' . $id . '-thumbnails" class="keen-slider keen-slider__parent thumbnail">';
                foreach ($this->get_photos() as $photo) {
                    echo '<div class="keen-slider__slide">';
                    echo BACheetah::render_module_html('photo', array('crop' => false, 'link_target' => '_self', 'link_type' => 'none', 'link_url' => $photo->largeURL, 'photo' => $photo->largeURL, 'photo_src' => $photo->largeURL,));
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<div id="' . $id . '" class="keen-slider keen-slider__parent">';
                foreach ($this->get_photos() as $photo) {
                    echo '<div class="keen-slider__slide">';
                    echo BACheetah::render_module_html('photo', array('crop' => false, 'link_target' => '_self', 'link_type' => 'none', 'link_url' => $photo->largeURL, 'photo' => $photo->largeURL, 'photo_src' => $photo->largeURL,));
                    echo '</div>';
                }
                echo '</div>';
            }
        }
    }

}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahCarouselModule', array(
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
						'default'  => '500',
						'sanitize' => 'absint',
						'units'    => array( 'px' ),
						'slider'   => array(
							'step' => 10,
							'max'  => 1000,
						),
					),
                    'spacing'  => array(
                        'type'     => 'unit',
                        'label'    => __( 'Spacing', 'ba-cheetah' ),
                        'default'  => '15',
                        'sanitize' => 'absint',
                        'units'    => array( 'px' ),
                        'slider'   => array(
                            'step' => 1,
                            'max'  => 200,
                        ),
                    ),
                    'mode'         => array(
                        'type'    => 'select',
                        'label'   => __( 'Mode', 'ba-cheetah' ),
                        'default' => 'snap',
                        'options' => array(
                            'snap'            => __( 'Snap', 'ba-cheetah' ),
                            'free'            => __( 'Free', 'ba-cheetah' ),
                        ),
                        'help' => __('In free mode, the slide will move freely by user click and drag', 'ba-cheetah'),
                    ),
                    'loop'          => array(
                        'type'    => 'select',
                        'label'   => __( 'Loop', 'ba-cheetah' ),
                        'default' => 'true',
                        'options' => array(
                            'false' => __( 'No', 'ba-cheetah' ),
                            'true'  => __( 'Yes', 'ba-cheetah' ),
                        ),
                    ),
                    'styleGallery'         => array(
                        'type'    => 'select',
                        'label'   => __( 'Type', 'ba-cheetah' ),
                        'default' => 'default',
                        'options' => array(
                            'default'         => __( 'Default', 'ba-cheetah' ),
                            'thumbnail'       => __( 'Thumbnail', 'ba-cheetah' ),
                            'carousel'        => __( 'Carousel', 'ba-cheetah' ),
                            'cube'            => __( 'Cube', 'ba-cheetah' ),
                            'flip'            => __( 'Flip', 'ba-cheetah' ),
                        ),
                        'toggle'  => array(
                            'default'     => array(
                                'fields' => array( 'per_view', 'origin' ),
                            ),
                            'thumbnail'     => array(
                                'fields' => array( 'heightThumbnail' ),
                            ),
                        ),
                    ),
                    'origin'         => array(
                        'type'    => 'select',
                        'label'   => __( 'Start Origin', 'ba-cheetah' ),
                        'default' => 'center',
                        'options' => array(
                            'center'          => __( 'Center', 'ba-cheetah' ),
                            'auto'            => __( 'Auto', 'ba-cheetah' ),
                        ),
                    ),
                    'per_view'  => array(
                        'type'     => 'unit',
                        'label'    => __( 'Per View', 'ba-cheetah' ),
                        'default'  => '1',
                        'sanitize' => 'BACheetahUtils::sanitize_non_negative_number',
                        'units'    => array( 'images' ),
                        'slider'   => true
                    ),
                    'heightThumbnail'  => array(
                        'type'     => 'unit',
                        'label'    => __( 'Height Thumbnail', 'ba-cheetah' ),
                        'default'  => '150',
                        'sanitize' => 'absint',
                        'units'    => array( 'px' ),
                        'slider'   => array(
                            'step' => 1,
                            'max'  => 500,
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

),),),)));


