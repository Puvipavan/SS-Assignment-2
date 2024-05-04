<?php

/**
 * @class BACheetahPhotoModule
 */
class BACheetahPhotoModule extends BACheetahModule {

	/**
	 * @property $data
	 */
	public $data = null;

	/**
	 * @property $_editor
	 * @protected
	 */
	protected $_editor = null;

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Photo', 'ba-cheetah' ),
			'description'     => __( 'Upload a photo or display one from the media library.', 'ba-cheetah' ),
			'category'        => __('Media', 'ba-cheetah'),
			'icon'            => 'format-image.svg',
			'partial_refresh' => true,
		));
	}

	/**
	 * Ensure backwards compatibility with old settings.
	 *

	 * @param object $settings A module settings object.
	 * @param object $helper A settings compatibility helper.
	 * @return object
	 */
	public function filter_settings( $settings, $helper ) {

		// Handle old link fields.
		if ( isset( $settings->link_target ) ) {
			$settings->link_url_target = $settings->link_target;
			unset( $settings->link_target );
		}
		if ( isset( $settings->link_nofollow ) ) {
			$settings->link_url_nofollow = $settings->link_nofollow;
			unset( $settings->link_nofollow );
		}

		return $settings;
	}

    /**
     * @method enqueue_scripts
     */
    public function enqueue_scripts() {
        $override_lightbox = apply_filters( 'ba_cheetah_override_lightbox', false );

        if ( $this->settings && 'lightbox' == $this->settings->link_type ) {
            if ( ! $override_lightbox ) {
                $this->add_js('jquery-fancybox');
                $this->add_css('jquery-fancybox');
                $this->add_css('jquery-fancybox-custom');
            } else {
                wp_dequeue_script( 'jquery-fancybox' );
                wp_dequeue_style( 'jquery-fancybox' );
                wp_dequeue_style( 'jquery-fancybox-custom' );
            }
        }
    }

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings ) {
		// Make sure we have a photo_src property.
		if ( ! isset( $settings->photo_src ) ) {
			$settings->photo_src = '';
		}

		// Cache the attachment data.
		$settings->data = BACheetahPhoto::get_attachment_data( $settings->photo );

		// Save a crop if necessary.
		$this->crop();

		return $settings;
	}

	/**
	 * @method delete
	 */
	public function delete() {
		$cropped_path = $this->_get_cropped_path();

		if ( ba_cheetah_filesystem()->file_exists( $cropped_path['path'] ) ) {
			ba_cheetah_filesystem()->unlink( $cropped_path['path'] );
		}
	}

	/**
	 * @method crop
	 */
	public function crop() {
		// Delete an existing crop if it exists.
		$this->delete();

		// Do a crop.
		if ( ! empty( $this->settings->crop ) ) {

			$editor = $this->_get_editor();

			if ( ! $editor || is_wp_error( $editor ) ) {
				return false;
			}

			$cropped_path = $this->_get_cropped_path();
			$size         = $editor->get_size();
			$new_width    = $size['width'];
			$new_height   = $size['height'];

			// Get the crop ratios.
			if ( 'landscape' == $this->settings->crop ) {
				$ratio_1 = 1.43;
				$ratio_2 = .7;
			} elseif ( 'panorama' == $this->settings->crop ) {
				$ratio_1 = 2;
				$ratio_2 = .5;
			} elseif ( 'portrait' == $this->settings->crop ) {
				$ratio_1 = .7;
				$ratio_2 = 1.43;
			} elseif ( 'square' == $this->settings->crop ) {
				$ratio_1 = 1;
				$ratio_2 = 1;
			} elseif ( 'circle' == $this->settings->crop ) {
				$ratio_1 = 1;
				$ratio_2 = 1;
			}

			// Get the new width or height.
			if ( $size['width'] / $size['height'] < $ratio_1 ) {
				$new_height = $size['width'] * $ratio_2;
			} else {
				$new_width = $size['height'] * $ratio_1;
			}

			// Make sure we have enough memory to crop.
			try {
				ini_set( 'memory_limit', '300M' );
			} catch ( Exception $e ) {
				//
			}

			// Crop the photo.
			$editor->resize( $new_width, $new_height, true );

			// Save the photo.
			$editor->save( $cropped_path['path'] );

			/**
			 * Let third party media plugins hook in.
			 * @see ba_cheetah_photo_cropped
			 */
			do_action( 'ba_cheetah_photo_cropped', $cropped_path, $editor );

			// Return the new url.
			return $cropped_path['url'];
		}

		return false;
	}

	/**
	 * @method get_data
	 */
	public function get_data() {
		if ( ! $this->data ) {

			// Photo source is set to "url".
			if ( 'url' == $this->settings->photo_source ) {
				$this->data                = new stdClass();
				$this->data->alt           = $this->settings->caption;
				$this->data->caption       = $this->settings->caption;
				$this->data->link          = $this->settings->photo_url;
				$this->data->url           = $this->settings->photo_url;
				$this->settings->photo_src = $this->settings->photo_url;
				$this->data->title         = $this->settings->caption;
			} elseif ( is_object( $this->settings->photo ) ) {
				$this->data = $this->settings->photo;
			} else {
				$this->data = BACheetahPhoto::get_attachment_data( $this->settings->photo );
			}

			// Data object is empty, use the settings cache.
			if ( ! $this->data && isset( $this->settings->data ) ) {
				$this->data = $this->settings->data;
			}
		}
		/**
		 * Make photo data filterable.

		 * @see ba_cheetah_photo_data
		 */
		return apply_filters( 'ba_cheetah_photo_data', $this->data, $this->settings, $this->node );
	}

	/**
	 * @method get_classes
	 */
	public function get_classes() {
		$classes = array( 'ba-cheetah-photo-img' );

		if ( 'library' == $this->settings->photo_source && ! empty( $this->settings->photo ) ) {

			$data = self::get_data();

			if ( is_object( $data ) ) {

				if ( isset( $data->id ) ) {
					$classes[] = 'wp-image-' . $data->id;
				}

				if ( isset( $data->sizes ) ) {

					foreach ( $data->sizes as $key => $size ) {

						if ( $size->url == $this->settings->photo_src ) {
							$classes[] = 'size-' . $key;
							break;
						}
					}
				}
			}
		}

		return implode( ' ', $classes );
	}

	/**
	 * @method get_src
	 */
	public function get_src() {
		$src = $this->_get_uncropped_url();

		// Return a cropped photo.
		if ( $this->_has_source() && ! empty( $this->settings->crop ) ) {

			$cropped_path = $this->_get_cropped_path();

			if ( ba_cheetah_filesystem()->file_exists( $cropped_path['path'] ) ) {
				// An existing cropped photo exists.
				$src = $cropped_path['url'];
			} else {

				// A cropped photo doesn't exist, check demo sites then try to create one.
				$post_data    = BACheetahModel::get_cheetah_ba_data();
				$editing_node = isset( $post_data['node_id'] );
				$demo_domain  = BA_CHEETAH_DEMO_DOMAIN;

				if ( ! $editing_node && stristr( $src, $demo_domain ) && ! stristr( $demo_domain, $_SERVER['HTTP_HOST'] ) ) {
					$src = $this->_get_cropped_demo_url();
				} else {
					$url = $this->crop();
					$src = $url ? $url : $src;
				}
			}
		}
		return $src;
	}

	/**
	 * @method get_link
	 */
	public function get_link() {
		$photo = $this->get_data();

		if ( 'url' == $this->settings->link_type ) {
			$link = $this->settings->link_url;
        } elseif (isset($photo) && 'lightbox' == $this->settings->link_type) {
            $link = $photo->url;
		} elseif ( isset( $photo ) && 'file' == $this->settings->link_type ) {
			$link = $photo->url;
		} elseif ( isset( $photo ) && 'page' == $this->settings->link_type ) {
			$link = $photo->link;
		} else {
			$link = '';
		}

		return $link;
	}

	/**
	 * @method get_caption
	 */
	public function get_caption() {
		$photo   = $this->get_data();

		if ( $photo && ! empty( $this->settings->show_caption ) ) {
			if (!empty($this->settings->caption)) {
				return $this->settings->caption;
			}
			return $photo->caption;
		}
	}

	/**
	 * @method get_attributes
	 */
	public function get_attributes() {
		$photo = $this->get_data();
		$attrs = '';

		if ( isset( $this->settings->attributes ) ) {
			foreach ( $this->settings->attributes as $key => $val ) {
				$attrs .= $key . '="' . $val . '" ';
			}
		}

		if ( is_object( $photo ) && isset( $photo->sizes ) ) {
			foreach ( $photo->sizes as $size ) {
				if ( $size->url == $this->settings->photo_src && isset( $size->width ) && isset( $size->height ) ) {
					$attrs .= 'height="' . $size->height . '" width="' . $size->width . '" ';
				}
			}
		}

		/*
		if ( ! empty( $photo->title ) ) {
			$attrs .= 'title="' . htmlspecialchars( $photo->title ) . '" ';
		}
		*/

		if ( BACheetahModel::is_builder_active() ) {
			$attrs .= 'onerror="this.style.display=\'none\'" ';
		}

		/**
		 * Filter image attributes as a string.

		 * @see ba_cheetah_photo_attributes
		 */
		return apply_filters( 'ba_cheetah_photo_attributes', $attrs );
	}

	/**
	 * @method _has_source
	 * @protected
	 */
	protected function _has_source() {
		if ( 'url' == $this->settings->photo_source && ! empty( $this->settings->photo_url ) ) {
			return true;
		} elseif ( 'library' == $this->settings->photo_source && ! empty( $this->settings->photo_src ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @method _get_editor
	 * @protected
	 */
	protected function _get_editor() {
		if ( $this->_has_source() && null === $this->_editor ) {

			$url_path = $this->_get_uncropped_url();

			$file_path = trailingslashit( WP_CONTENT_DIR ) . ltrim( str_replace( basename( WP_CONTENT_DIR ), '', wp_make_link_relative( $url_path ) ), '/' );

			if ( is_multisite() && ! is_subdomain_install() ) {
				// take the original url_path and make a cleaner one, then rebuild file_path

				$subsite_path          = get_blog_details()->path;
				$url_parsed_path       = wp_parse_url( $url_path, PHP_URL_PATH );
				$url_parsed_path_parts = explode( '/', $url_parsed_path );

				if ( isset( $url_parsed_path_parts[1] ) && "/{$url_parsed_path_parts[1]}/" === $subsite_path ) {

					$path_right_half  = wp_make_link_relative( $url_path );
					$path_left_half   = str_replace( $path_right_half, '', $url_path );
					$path_right_half2 = str_replace( $subsite_path, '', $path_right_half );

					// rebuild file_path using a cleaner URL as input
					$url_path2 = $path_left_half . '/' . $path_right_half2;
					$file_path = trailingslashit( WP_CONTENT_DIR ) . ltrim( str_replace( basename( WP_CONTENT_DIR ), '', wp_make_link_relative( $url_path2 ) ), '/' );
				}
			}

			if ( file_exists( $file_path ) ) {
				$this->_editor = wp_get_image_editor( $file_path );
			} else {
				if ( ! is_wp_error( wp_safe_remote_head( $url_path, array( 'timeout' => 5 ) ) ) ) {
					$this->_editor = wp_get_image_editor( $url_path );
				}
			}
		}
		return $this->_editor;
	}

	/**
	 * @method _get_cropped_path
	 * @protected
	 */
	protected function _get_cropped_path() {
		$crop      = empty( $this->settings->crop ) ? 'none' : $this->settings->crop;
		$url       = $this->_get_uncropped_url();
		$cache_dir = BACheetahModel::get_cache_dir();

		if ( empty( $url ) ) {
			$filename = uniqid(); // Return a file that doesn't exist.
		} else {

			if ( stristr( $url, '?' ) ) {
				$parts = explode( '?', $url );
				$url   = $parts[0];
			}

			$pathinfo = pathinfo( $url );

			if ( isset( $pathinfo['extension'] ) ) {
				$dir      = $pathinfo['dirname'];
				$ext      = $pathinfo['extension'];
				$name     = wp_basename( $url, ".$ext" );
				$new_ext  = strtolower( $ext );
				$filename = "{$name}-{$crop}.{$new_ext}";
			} else {
				$filename = $pathinfo['filename'] . "-{$crop}.png";
			}
		}

		return array(
			'filename' => $filename,
			'path'     => $cache_dir['path'] . $filename,
			'url'      => $cache_dir['url'] . $filename,
		);
	}

	/**
	 * @method _get_uncropped_url
	 * @protected
	 */
	protected function _get_uncropped_url() {
		if ( 'url' == $this->settings->photo_source ) {
			$url = $this->settings->photo_url;
		} elseif ( ! empty( $this->settings->photo_src ) ) {
			$url = $this->settings->photo_src;
		} else {
			$url = apply_filters( 'ba_cheetah_photo_noimage', BA_CHEETAH_URL . 'img/demo/placeholder.png' );
		}

		return $url;
	}

	/**
	 * @method _get_cropped_demo_url
	 * @protected
	 */
	protected function _get_cropped_demo_url() {
		$info = $this->_get_cropped_path();
		$src  = $this->settings->photo_src;

		// Pull from a demo subsite.
		if ( stristr( $src, '/uploads/sites/' ) ) {
			$url_parts  = explode( '/uploads/sites/', $src );
			$site_parts = explode( '/', $url_parts[1] );
			return $url_parts[0] . '/uploads/sites/' . $site_parts[0] . '/wp-cheetah/cache/' . $info['filename'];
		}

		// Pull from the demo main site.
		return BA_CHEETAH_DEMO_CACHE_URL . $info['filename'];
	}

	/**
	 * Returns link rel

	 */
	public function get_rel() {
		$rel = array();
		if ( '_blank' == $this->settings->link_url_target ) {
			$rel[] = 'noopener';
		}
		if ( isset( $this->settings->link_url_nofollow ) && 'yes' == $this->settings->link_url_nofollow ) {
			$rel[] = 'nofollow';
		}
		$rel = implode( ' ', $rel );
		if ( $rel ) {
			$rel = ' rel="' . $rel . '" ';
		}
		return $rel;
	}

	public function get_pixel_attr() {
		return BACheetahTracking::get_pixel_attr($this->settings, 'tracking_pixel_event');
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahPhotoModule', array(
	'general' => array( // Tab
		'title'    => __( 'General', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'photo_source' => array(
						'type'    => 'select',
						'label'   => __( 'Photo Source', 'ba-cheetah' ),
						'default' => 'library',
						'options' => array(
							'library' => __( 'Media Library', 'ba-cheetah' ),
							'url'     => __( 'URL', 'ba-cheetah' ),
						),
						'toggle'  => array(
							'library' => array(
								'fields' => array( 'photo' ),
							),
							'url'     => array(
								'fields' => array( 'photo_url'),
							),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
					'photo'        => array(
						'type'        => 'photo',
						'label'       => __( 'Photo', 'ba-cheetah' ),
						'connections' => array( 'photo' ),
						'show_remove' => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'photo_url'    => array(
						'type'        => 'text',
						'label'       => __( 'Photo URL', 'ba-cheetah' ),
						'placeholder' => __( 'http://www.example.com/my-photo.jpg', 'ba-cheetah' ),
						'preview'     => array(
							'type' => 'none',
						),
					),
				),
			),
			'caption' => array(
				'title'  => __( 'Caption', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array(
					'show_caption' => array(
						'type'    => 'select',
						'label'   => __( 'Show Caption', 'ba-cheetah' ),
						'default' => '0',
						'options' => array(
							'0'     => __( 'Never', 'ba-cheetah' ),
							'hover' => __( 'On Hover', 'ba-cheetah' ),
							'below' => __( 'Below Photo', 'ba-cheetah' ),
						),

						'toggle'  => array(
							''      => array(),
							'hover' => array(
								'fields' => array( 'caption_typography', 'caption'),
							),

							'below' => array(
								'fields' => array( 'caption_typography', 'caption'),
							),
						),
					),
					'caption'      => array(
						'type'    => 'text',
						'placeholder' => __('Leave blank to use the media caption', 'ba-cheetah'),
						'label'   => __( 'Caption', 'ba-cheetah' )
					),
				),
			),
			'link'    => array(
				'title'  => __( 'Link', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array_merge( array(
					'link_type' => array(
						'type'    => 'select',
						'label'   => __( 'Link Type', 'ba-cheetah' ),
						'options' => array(
							''         	=> _x( 'None', 'Link type.', 'ba-cheetah' ),
							'url'      	=> __( 'URL', 'ba-cheetah' ),
                            'lightbox' 	=> __( 'Lightbox', 'ba-cheetah' ),
							'file'     	=> __( 'Photo File', 'ba-cheetah' ),
							'page'     	=> __( 'Photo Page', 'ba-cheetah' ),
							'popup' 	=> __( 'Popup', 'ba-cheetah' ),
							'video'		=> __( 'Video', 'ba-cheetah' )
						),
						'toggle'  => array(
							''     => array(),
							'url'  => array(
								'fields' => array( 'link_url' ),
							),
							'file' => array(),
							'page' => array(),
							'popup' => array(
								'fields' => array( 'popup_id' ),
							),
							'video' => array(
								'fields' => array( 'video_link' ),
							),
						),
						'help'    => __( 'Link type applies to how the image should be linked on click. You can choose a specific URL, the individual photo or a separate page with the photo.', 'ba-cheetah' ),
						'preview' => array(
							'type' => 'none',
						),
					),
					'link_url'  => array(
						'type'          => 'link',
						'label'         => __( 'Link URL', 'ba-cheetah' ),
						'show_target'   => true,
						'show_nofollow' => true,
						'preview'       => array(
							'type' => 'none',
						),
					),
					'popup_id'  => array(
						'type'    => 'select',
						'label'   => __( 'Popup', 'ba-cheetah' ),
						'default' => '',
						'options' => array(),
						'preview' => array(
							'type' => 'none',
						),
					),
					'video_link'           => array(
						'type'          => 'text',
						'label'         => __( 'Link', 'ba-cheetah' ),
						'placeholder'   => 'https://www.youtube.com/embed/7xL5HFghzfg',
						'preview'       => array(
							'type' => 'none',
						),
					),
				), BACheetahTracking::module_tracking_fields()),
			),
		),
	),
	'style'   => array( // Tab
		'title'    => __( 'Style', 'ba-cheetah' ), // Tab title
		'sections' => array( // Tab Sections
			'general' => array( // Section
				'title'  => '', // Section Title
				'fields' => array( // Section Fields
					'crop'               => array(
						'type'    => 'select',
						'label'   => __( 'Crop', 'ba-cheetah' ),
						'default' => '',
						'options' => array(
							''          => _x( 'None', 'Photo Crop.', 'ba-cheetah' ),
							'landscape' => __( 'Landscape', 'ba-cheetah' ),
							'panorama'  => __( 'Panorama', 'ba-cheetah' ),
							'portrait'  => __( 'Portrait', 'ba-cheetah' ),
							'square'    => __( 'Square', 'ba-cheetah' ),
							'circle'    => __( 'Circle', 'ba-cheetah' ),
						),
					),
					'width'              => array(
						'type'       => 'unit',
						'label'      => __( 'Width', 'ba-cheetah' ),
						'responsive' => true,
						'units'      => array(
							'px',
							'vw',
							'%',
						),
						'slider'     => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-cheetah-photo-img',
							'property'  => 'width',
							'important' => true,
						),
					),
					'align'              => array(
						'type'       => 'align',
						'label'      => __( 'Align', 'ba-cheetah' ),
						'default'    => 'left',
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.ba-cheetah-photo',
							'property'  => 'text-align',
							'important' => true,
						),
					),
					'border'             => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'ba-cheetah' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.ba-cheetah-photo-img',
						),
					),

					'caption_typography' => array(
						'type'       => 'typography',
						'label'      => __( 'Caption Typography', 'ba-cheetah' ),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node}.ba-cheetah-module-photo .ba-cheetah-photo-caption',
							'important' => true,
						),
					),

					'caption_color' => array(
						'type' => 'color',
						'label'		=> __('Caption color', 'ba-cheetah'),
						'preview' => array(
							'type' => 'css',
							'selector'  => '{node}.ba-cheetah-module-photo .ba-cheetah-photo-caption',
							'property'  => 'color',
						)
					),
				),
			),
		),
	),
));
