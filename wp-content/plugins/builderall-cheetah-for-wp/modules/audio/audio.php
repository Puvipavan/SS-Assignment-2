<?php

/**
 * @class BACheetahAudioModule
 */
class BACheetahAudioModule extends BACheetahModule {

	/**
	 * @property $data
	 */
	public $data = null;

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Audio', 'ba-cheetah' ),
			'description'     => __( 'Render a WordPress audio shortcode.', 'ba-cheetah' ),
			'category'        => __('Media', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'format-audio.svg',
		));
	}

	/**
	 * @method get_data
	 */
	public function get_data() {
		if ( ! $this->data ) {

			// Get audio data if user selected only one audio file
			if ( is_array( $this->settings->audios ) && count( $this->settings->audios ) == 1 ) {
				$this->data = BACheetahPhoto::get_attachment_data( $this->settings->audios[0] );

				if ( ! $this->data && isset( $this->settings->data ) ) {
					$this->data = $this->settings->data;
				}
			}
		}

		return $this->data;
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings ) {
		// Cache the attachment data.
		if ( 'media_library' == $settings->audio_type ) {

			// Get audio data if user selected only one audio file
			if ( is_array( $settings->audios ) && count( $settings->audios ) == 1 ) {
				$audios = BACheetahPhoto::get_attachment_data( $settings->audios[0] );

				if ( $audios ) {
					$settings->data = $audios;
				}
			}
		}

		return $settings;
	}

}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahAudioModule', array(
	'general' => array(
		'title'    => __( 'General', 'ba-cheetah' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'audio_type'   => array(
						'type'    => 'select',
						'label'   => __( 'Audio Type', 'ba-cheetah' ),
						'default' => 'wordpress',
						'options' => array(
							'media_library' => __( 'Media Library', 'ba-cheetah' ),
							'link'          => __( 'Link', 'ba-cheetah' ),
						),
						'toggle'  => array(
							'link'          => array(
								'fields' => array( 'link' ),
							),
							'media_library' => array(
								'fields' => array( 'audios' ),
							),
						),
					),
					'audios'       => array(
						'type'   => 'multiple-audios',
						'label'  => __( 'Audio', 'ba-cheetah' ),
						'toggle' => array(
							'playlist'     => array(
								'fields' => array( 'style', 'tracklist', 'tracknumbers', 'images', 'artists' ),
							),
							'single_audio' => array(
								'fields' => array( 'autoplay', 'loop' ),
							),
						),
					),
					'link'         => array(
						'type'        => 'text',
						'label'       => __( 'Link', 'ba-cheetah' ),
						'connections' => array( 'url' ),
					),

					/**
					 * Single audio options
					 */
					'autoplay'     => array(
						'type'    => 'select',
						'label'   => __( 'Auto Play', 'ba-cheetah' ),
						'default' => '0',
						'options' => array(
							'0' => __( 'No', 'ba-cheetah' ),
							'1' => __( 'Yes', 'ba-cheetah' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
					'loop'         => array(
						'type'    => 'select',
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

					/**
					 * Playlist options - show only if user selected more than one files
					 */
					'style'        => array(
						'type'    => 'select',
						'label'   => __( 'Style', 'ba-cheetah' ),
						'default' => 'light',
						'options' => array(
							'light' => __( 'Light', 'ba-cheetah' ),
							'dark'  => __( 'Dark', 'ba-cheetah' ),
						),
					),
					'tracklist'    => array(
						'type'    => 'select',
						'label'   => __( 'Show Playlist', 'ba-cheetah' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'No', 'ba-cheetah' ),
							'1' => __( 'Yes', 'ba-cheetah' ),
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'tracknumbers' ),
							),
						),
					),
					'tracknumbers' => array(
						'type'    => 'select',
						'label'   => __( 'Show Track Numbers', 'ba-cheetah' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'No', 'ba-cheetah' ),
							'1' => __( 'Yes', 'ba-cheetah' ),
						),
						'preview' => array(
							'type' => 'none',
						),
					),
					'images'       => array(
						'type'    => 'select',
						'label'   => __( 'Show Thumbnail', 'ba-cheetah' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'No', 'ba-cheetah' ),
							'1' => __( 'Yes', 'ba-cheetah' ),
						),
					),
					'artists'      => array(
						'type'    => 'select',
						'label'   => __( 'Show Artist Name', 'ba-cheetah' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'No', 'ba-cheetah' ),
							'1' => __( 'Yes', 'ba-cheetah' ),
						),
					),
				),
			),
		),
	),
));
