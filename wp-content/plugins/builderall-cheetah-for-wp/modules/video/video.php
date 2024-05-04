<?php

/**
 * @class BACheetahVideoModule
 */
class BACheetahVideoModule extends BACheetahModule {

	/**
	 * @property $data
	 */
	public $data = null;

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Video', 'ba-cheetah' ),
			'description'     => __( 'Render a WordPress or embedable video.', 'ba-cheetah' ),
			'category'        => __( 'Media', 'ba-cheetah' ),
			'partial_refresh' => true,
			'icon'            => 'format-video.svg',
		));

		$this->add_js( 'jquery-fitvids' );
		$this->add_css( 'dashicons' );


		add_filter( 'wp_video_shortcode', __CLASS__ . '::mute_video', 10, 4 );
	}

	/**
	 * @method get_data
	 */
	public function get_data() {
		if ( ! $this->data ) {

			$this->data = BACheetahPhoto::get_attachment_data( $this->settings->video );

			if ( ! $this->data && isset( $this->settings->data ) ) {
				$this->data = $this->settings->data;
			}
			if ( $this->data ) {
				$parts                 = explode( '.', $this->data->filename );
				$this->data->extension = array_pop( $parts );
				$this->data->poster    = isset( $this->settings->poster_src ) ? $this->settings->poster_src : '';
				$this->data->loop      = isset( $this->settings->loop ) && $this->settings->loop ? ' loop="yes"' : '';
				$this->data->autoplay  = isset( $this->settings->autoplay ) && $this->settings->autoplay ? ' autoplay="yes"' : '';

				// WebM format
				$webm_data              = BACheetahPhoto::get_attachment_data( $this->settings->video_webm );
				$this->data->video_webm = isset( $this->settings->video_webm ) && $webm_data ? ' webm="' . $webm_data->url . '"' : '';

			}
		}

		return $this->data;
	}

	/**

	 * @method render_poster_html
	 */
	public function render_video_html( $schema ) {
		$video_html   = '';
		$video_poster = $this->get_poster_url();
		$video_meta   = '';

		if ( 'media_library' === $this->settings->video_type ) {
			$vid_data = $this->get_data();
			$preload  = BACheetahModel::is_builder_active() && ! empty( $vid_data->poster ) ? ' preload="none"' : '';

			$video_meta .= '<meta itemprop="url" content="' . ( empty( $vid_data->url ) ? '' : $vid_data->url ) . '" />';
			if ( $schema ) {
				$video_meta .= '<meta itemprop="thumbnail" content="' . $video_poster . '" />';
			}

			$video_html = $video_meta;

			$video_sc = sprintf( '%s', __( 'Video not specified. Please select one to display.', 'ba-cheetah' ) );

			if ( ! empty( $vid_data->url ) ) {
				$video_sc = '[video ' . $vid_data->extension . '="' . $vid_data->url . '"' . $vid_data->video_webm . ' poster="' . $video_poster . '" ' . $vid_data->autoplay . $vid_data->loop . $preload . '][/video]';
			}
			/*
			if ( 'yes' === $this->settings->video_lightbox ) {
				$video_html .= '<div id="ba-cheetah-node-' . $this->node . '-lightbox-content" class="ba-cheetah-node-' . $this->node . '-lightbox-content' . ' ba-cheetah-video-lightbox-content ' . ( empty( $vid_data->url ) ? '' : 'mfp-hide' ) . '">';
				$video_html .= $video_sc;
				$video_html .= '</div>';
			} else {
			*/
				$video_html .= $video_sc;
			/*
				}
			*/
			
		} elseif ( 'embed' === $this->settings->video_type ) {
			global $wp_embed;

			$video_embed = '';
			if ( ! empty( $this->settings->embed_code ) ) {
				$video_embed = $wp_embed->autoembed( do_shortcode( $this->settings->embed_code ) );

				// autoplay for youtube
				if($this->settings->autoplay == '1' && (strpos($video_embed, 'youtu.be') !== false || strpos($video_embed, 'youtube.com') !== false)){
					$video_embed = preg_replace('@embed/([^"&]*)@', 'embed/$1&mute=1&autoplay=1', $video_embed);
				}

			} elseif ( ! isset( $this->settings->connections ) ) {
				$video_embed = sprintf( '%s', __( 'Video embed code not specified.', 'ba-cheetah' ) );
			}

			/*
			if ( 'yes' == $this->settings->video_lightbox ) {
				$video_html  = '<div id="ba-cheetah-node-' . $this->node . '-lightbox-content" class="ba-cheetah-node-' . $this->node . '-lightbox-content' . ' ba-cheetah-video-lightbox-content ' . ( empty( $this->settings->embed_code ) ? '' : 'mfp-hide' ) . '">';
				$video_html .= $video_embed;
				$video_html .= '</div>';
			} else {
			*/
				$video_html = $video_embed;
			/*
			}
			*/
		}

		echo $video_html;
	}

	/**

	 * @method render_poster_html
	 */
	public function render_poster_html() {
		$poster_html = '';

		/*
		if ( 'yes' === $this->settings->video_lightbox ) {
			$poster_url = $this->get_poster_url();
			if ( empty( $poster_url ) ) {
				$poster_html .= '<div class="ba-cheetah-video-poster">';
				$poster_html .= sprintf( '%s', __( 'Please specify a poster image if Video Lightbox is enabled.', 'ba-cheetah' ) );
				$poster_html .= '</div>';
			} else {
				$video_url    = $this->get_video_url();
				$poster_html .= '<div class="ba-cheetah-video-poster" data-mfp-src="' . $video_url . '">';
				$poster_html .= wp_get_attachment_image( $this->settings->poster, 'large', '', array( 'class' => 'img-responsive' ) );
				$poster_html .= '</div>';
			}
		}
		*/

		echo $poster_html;
	}

	/**

	 * @method get_poster_url
	 */
	private function get_poster_url() {
		$url = empty( $this->settings->poster ) ? '' : $this->settings->poster_src;
		return $url;
	}

	/**

	 * @method get_video_url
	 */

	 /*
	private function get_video_url() {
		$settings  = $this->settings;
		$video_url = '';

		if ( 'yes' === $settings->video_lightbox ) {
			if ( 'embed' == $settings->video_type ) {
				if ( strstr( $settings->embed_code, 'vimeo.com' ) ) {
					$vid_id    = $this->get_video_id( 'vimeo', $settings->embed_code );
					$video_url = 'https://vimeo.com/' . $vid_id;
				} elseif ( strstr( $settings->embed_code, 'youtube.com' ) || strstr( $settings->embed_code, 'youtu.be' ) ) {
					$vid_id    = $this->get_video_id( 'youtube', $settings->embed_code );
					$video_url = 'https://youtube.com/watch?v=' . $vid_id;
				} else {
					$video_url = '';
				}
			} elseif ( 'media_library' == $settings->video_type ) {
				$vid_data  = $this->get_data();
				$video_url = ! empty( $vid_data->url ) ? $vid_data->url : '';
			}
		}

		return $video_url;
	}
	*/

	/**
	 * @method get_video_id
	 * @param string $source
	 * @param string $embed_code
	 */
	private function get_video_id( $source = '', $embed_code = '' ) {
		$matches = array();
		$id      = '';
		$regex   = '';

		$youtube_regex = '~(?:(?:<iframe [^>]*src=")?|(?:(?:<object .*>)?(?:<param .*</param>)*(?:<embed [^>]*src=")?)?)?(?:https?:\/\/(?:[\w]+\.)*(?:youtu\.be/| youtube\.com| youtube-nocookie\.com)(?:\S*[^\w\-\s])?([\w\-]{11})[^\s]*)"?(?:[^>]*>)?(?:</iframe>|</embed></object>)?~ix';
		$vimeo_regex   = '~(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w]*\/videos?)?\/([0-9]+)[^\s]*)"?(?:[^>]*></iframe>)?(?:<p>.*</p>)?~ix';

		if ( 'vimeo' == $source ) {
			$regex = $vimeo_regex;
		} elseif ( 'youtube' == $source ) {
			$regex = $youtube_regex;
		}

		preg_match( $regex, $embed_code, $matches );

		if ( ! empty( $matches ) ) {
			$id = $matches[1];
		}

		return $id;
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings ) {
		// Cache the attachment data.
		if ( 'media_library' == $settings->video_type ) {

			$video = BACheetahPhoto::get_attachment_data( $settings->video );

			if ( $video ) {
				$settings->data = $video;
			} else {
				$settings->data = null;
			}
		}

		return $settings;
	}

	/**
	 * Temporary fix for autoplay in Chrome & Safari. Video shortcode doesn't support `muted` parameter.
	 * Bug report: https://core.trac.wordpress.org/ticket/42718.
	 *

	 * @param string $output  Video shortcode HTML output.
	 * @param array  $atts    Array of video shortcode attributes.
	 * @param string $video   Video file.
	 * @param int    $post_id Post ID.
	 * @return string
	 */
	static public function mute_video( $output, $atts, $video, $post_id ) {
		if ( false !== strpos( $output, 'autoplay="1"' ) && BACheetahModel::get_post_id() == $post_id ) {
			$output = str_replace( '<video', '<video muted', $output );
		}
		return $output;
	}


	/**
	 * Calculate video aspect ratio for style.
	 *

	 * @return float
	 */
	public function video_aspect_ratio() {
		$data = $this->get_data();
		if ( $data && function_exists( 'bcdiv' ) ) {
			$ratio = ( $data->height / $data->width ) * 100;
			return bcdiv( $ratio, 1, 2 );
		}
	}

	/**
	 * Returns structured data markup.

	 */
	public function get_structured_data() {
		$settings = $this->settings;
		$markup   = '';
		if ( 'yes' != $settings->schema_enabled ) {
			return false;
		}
		if ( '' == $settings->name || '' == $settings->description || '' == $settings->thumbnail || '' == $settings->up_date ) {
			return false;
		}
		$markup .= sprintf( '<meta itemprop="name" content="%s" />', esc_attr( $settings->name ) );
		$markup .= sprintf( '<meta itemprop="uploadDate" content="%s" />', esc_attr( $settings->up_date ) );
		$markup .= sprintf( '<meta itemprop="thumbnailUrl" content="%s" />', $settings->thumbnail_src );
		$markup .= sprintf( '<meta itemprop="description" content="%s" />', esc_attr( $settings->description ) );

		return $markup;
	}

}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahVideoModule', array(
	'general' => array(
		'title'    => __( 'General', 'ba-cheetah' ),
		'sections' => array(
			'general' => array(
				'title'  => __( 'Video', 'ba-cheetah' ),
				'fields' => array(
					'video_type'       => array(
						'type'    => 'button-group',
						'label'   => __( 'Video Origin', 'ba-cheetah' ),
						'default' => 'media_library',
						'options' => array(
							'media_library' => __( 'Media Library', 'ba-cheetah' ),
							'embed'         => __( 'Embed', 'ba-cheetah' ),
						),
						'toggle'  => array(
							'media_library' => array(
								'sections' => array( 'video_controls_section', 'video_fallback'),
								'fields'   => array( 'video', 'autoplay', 'loop' ),
							),
							'embed'         => array(
								'fields' => array( 'embed_code', 'autoplay' ),
							),
						),
					),
					'video'            => array(
						'type'        => 'video',
						'label'       => __( 'Video (MP4)', 'ba-cheetah' ),
						'help'        => __( 'A video in the MP4 format. Most modern browsers support this format.', 'ba-cheetah' ),
						'show_remove' => true,
					),
					'embed_code'       => array(
						'type'        => 'textarea',
						'wrap'        => true,
						'label' 	  => __('Embed code', 'ba-cheetah'),
						'rows'        => '5',
						'default'	  => 'https://www.youtube.com/embed/7xL5HFghzfg',
						'help'		  => __('Paste here a link, or embed code from vimeo, youtube or Builderall Video Hosting', 'ba-cheetah')
					),
					/*
					'poster'           => array(
						'type'        => 'photo',
						'show_remove' => true,
						'label'       => _x( 'Poster', 'Video preview/fallback image.', 'ba-cheetah' ),
						'help'        => __( 'An image must be specified for the Lightbox to work.', 'ba-cheetah' ),
					),
					'video_lightbox'   => array(
						'type'    => 'button-group',
						'label'   => __( 'Show Video on Popup', 'ba-cheetah' ),
						'default' => 'no',
						'options' => array(
							'no'  => __( 'No', 'ba-cheetah' ),
							'yes' => __( 'Yes', 'ba-cheetah' ),
						),
						'help'    => __( 'Poster Image must be specified for the Lightbox to work.', 'ba-cheetah' ),
					),
					*/
					'autoplay'         => array(
						'type'    => 'button-group',
						'label'   => __( 'Auto Play', 'ba-cheetah' ),
						'help' 	  => __( 'Video will automatically play with muted audio', 'ba-cheetah' ),
						'default' => '0',
						'options' => array(
							'0' => __( 'No', 'ba-cheetah' ),
							'1' => __( 'Yes', 'ba-cheetah' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
					'loop'             => array(
						'type'    => 'button-group',
						'label'   => __( 'Loop', 'ba-cheetah' ),
						'default' => '0',
						'options' => array(
							'0' => __( 'No', 'ba-cheetah' ),
							'1' => __( 'Yes', 'ba-cheetah' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			),
			'video_controls_section' => array(
				'title'  => __( 'Controls', 'ba-cheetah' ),
				'collapsed' => true,
				'fields' => array(
					'play_pause'  => array(
						'type'    => 'button-group',
						'label'   => __( 'Play/Pause', 'ba-cheetah' ),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						),
					),
					'timer'       => array(
						'type'    => 'button-group',
						'label'   => __( 'Timer', 'ba-cheetah' ),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						),
					),
					'time_rail'   => array(
						'type'    => 'button-group',
						'label'   => __( 'Time Rail', 'ba-cheetah' ),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						),
					),
					'duration'    => array(
						'type'    => 'button-group',
						'label'   => __( 'Duration', 'ba-cheetah' ),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						),
					),
					'volume'      => array(
						'type'    => 'button-group',
						'label'   => __( 'Volume', 'ba-cheetah' ),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						),
					),
					'full_screen' => array(
						'type'    => 'button-group',
						'label'   => __( 'Fullscreen', 'ba-cheetah' ),
						'default' => 'show',
						'options' => array(
							'show' => '<svg width="17.007" height="11.071"><use xlink:href="#ba-cheetah-icon--eye"></use></svg>',
							'hide' => '<svg width="17.007" height="15.383"><use xlink:href="#ba-cheetah-icon--eye-hide"></use></svg>',
						),
					),
				),
			),
			'sticky' => array(
				'title' => __('Sticky on Scroll', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'sticky_on_scroll' => array(
						'type'    => 'button-group',
						'label'   => __( 'Enabled', 'ba-cheetah' ),
						'default' => 'no',
						'options' => array(
							'no'  => __( 'No', 'ba-cheetah' ),
							'yes' => __( 'Yes', 'ba-cheetah' ),
						),
						'toggle' => array(
							'yes' => array('fields' => array('sticky_position', 'sticky_size', 'sticky_mobile', 'sticky_distance_x', 'sticky_distance_y'))
						),
						'preview' => array(
							'type' => 'none',
						),
					),
					'sticky_mobile' => array(
						'type'    => 'button-group',
						'label'   => __( 'Enabled for mobile devices', 'ba-cheetah' ),
						'default' => 'no',
						'options' => array(
							'no'  => __( 'No', 'ba-cheetah' ),
							'yes' => __( 'Yes', 'ba-cheetah' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
					'sticky_position' => array(
						'type' => 'select',
						'label' => __( 'Sticky Position', 'ba-cheetah' ),
						'default' => 'bottom_right',
						'options' => array(
							'top_left' => __( 'Top Left', 'ba-cheetah' ),
							'bottom_left' => __( 'Bottom Left', 'ba-cheetah' ),
							'top_right' => __( 'Top Right', 'ba-cheetah' ),
							'bottom_right' => __( 'Bottom Right', 'ba-cheetah' ),
						),
						'preview'    => array(
							'type'      => 'none'
						),
					),
					'sticky_size' => array(
						'type'  => 'unit',
						'label' => __('Size', 'ba-cheetah'),
						'default' => '300',
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => array(
							'min' => '100',
							'max' => '1000',
							'step' => '50'
						),
						'preview'    => array(
							'type'      => 'none'
						),
					),
					'sticky_distance_x' => array(
						'type'  => 'unit',
						'label' => __('X axis distance', 'ba-cheetah'),
						'default' => '5',
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => array(
							'min' => '0',
							'max' => '100',
							'step' => '1'
						),
						'preview'    => array(
							'type'      => 'none'
						),
					),
					'sticky_distance_y' => array(
						'type'  => 'unit',
						'label' => __('Y axis distance', 'ba-cheetah'),
						'default' => '5',
						'units' => array('px'),
						'default_unit' => 'px',
						'slider' => array(
							'min' => '0',
							'max' => '100',
							'step' => '1'
						),
						'preview'    => array(
							'type'      => 'none'
						),
					),
				),
			),
			'schema' => array(
				'title' => __('Structured Data', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'schema_enabled' => array(
						'type'    => 'select',
						'label'   => __( 'Enable Structured Data?', 'ba-cheetah' ),
						'default' => 'no',
						'preview' => array(
							'type' => 'none',
						),
						'toggle'  => array(
							'yes' => array(
								'fields' => array( 'name', 'description', 'thumbnail', 'up_date' ),
							),
						),
						'options' => array(
							'yes' => __( 'Yes', 'ba-cheetah' ),
							'no'  => __( 'No', 'ba-cheetah' ),
						),
					),
					'name'           => array(
						'type'    => 'text',
						'label'   => __( 'Video Name', 'ba-cheetah' ),
						'preview' => array(
							'type' => 'none',
						),
					),
					'description'    => array(
						'type'    => 'text',
						'label'   => __( 'Video Description', 'ba-cheetah' ),
						'preview' => array(
							'type' => 'none',
						),
					),
					'thumbnail'      => array(
						'type'        => 'photo',
						'label'       => __( 'Video Thumbnail', 'ba-cheetah' ),
						'show_remove' => true,
						'preview'     => array(
							'type' => 'none',
						),
					),
					'up_date'        => array(
						'type'    => 'date',
						'label'   => __( 'Upload Date', 'ba-cheetah' ),
						'preview' => array(
							'type' => 'none',
						),
					),
				),
			),
			'video_fallback' => array(
				'title' => __('Fallback', 'ba-cheetah'),
				'collapsed' => true,
				'fields' => array(
					'webm_raw' => array(
						'type' => 'raw',
						'content' => __( 'A video in the WebM format to use as fallback. This format is required to support browsers such as FireFox and Opera.', 'ba-cheetah' ),
					),
					'video_webm'       => array(
						'type'        => 'video',
						'show_remove' => true,
						'label'       => __( 'Video (WebM)', 'ba-cheetah' ),
						'preview'     => array(
							'type' => 'none',
						),
					),
				)
			)
		),
	),
	'style' => array(
		'title' => __('Style', 'ba-cheetah'),
		'sections' => array(
			'style' => array(
				'title' => '',
				'fields' => array(
					'border'  => array(
						'type'       => 'border',
						'label'      => __('Border', 'ba-cheetah'),
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '{node} .ba-cheetah-wp-video, {node} .fluid-width-video-wrapper iframe',
						),
					),
				)
			)
		)
	)
));
