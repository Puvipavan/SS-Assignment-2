<?php

$global_settings = BACheetahModel::get_global_settings();

$row_settings = array(
	'title' => __( 'Row Settings', 'ba-cheetah' ),
	'tabs'  => array(

		'style'    => array(
			'title'    => __( 'Style', 'ba-cheetah' ),
			'sections' => array(
				'general'          => array(
					'title'  => '',
					'fields' => array(
						'width'             => array(
							'type'    => 'select',
							'label'   => __( 'Width', 'ba-cheetah' ),
							'default' => $global_settings->row_width_default,
							'options' => array(
								'fixed' => __( 'Fixed', 'ba-cheetah' ),
								'full'  => __( 'Full Width', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'full' => array(
									'fields' => array( 'content_width' ),
								),
							),
							'help'    => __( 'Full width rows span the width of the page from edge to edge. Fixed rows are no wider than the Row Max Width set in the Global Settings.', 'ba-cheetah' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'content_width'     => array(
							'type'    => 'select',
							'label'   => __( 'Content Width', 'ba-cheetah' ),
							'default' => $global_settings->row_content_width_default,
							'options' => array(
								'fixed' => __( 'Fixed', 'ba-cheetah' ),
								'full'  => __( 'Full Width', 'ba-cheetah' ),
							),
							'help'    => __( 'Full width content spans the width of the page from edge to edge. Fixed content is no wider than the Row Max Width set in the Global Settings.', 'ba-cheetah' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'max_content_width' => array(
							'type'         => 'unit',
							'label'        => __( 'Fixed Width', 'ba-cheetah' ),
							'placeholder'  => $global_settings->row_width,
							'default'	   => $global_settings->row_width,
							'default_unit' => $global_settings->row_width_unit,
							'units'        => array(
								'px',
								'vw',
								'%',
							),
							'slider'       => array(
								'px' => array(
									'min'  => 0,
									'max'  => $global_settings->row_width,
									'step' => 10,
								),
							),
							'preview'      => array(
								'type' => 'none',
							),
						),
						'full_height'       => array(
							'type'    => 'select',
							'label'   => __( 'Height', 'ba-cheetah' ),
							'default' => 'default',
							'options' => array(
								'default' => __( 'Default', 'ba-cheetah' ),
								'full'    => __( 'Full Height', 'ba-cheetah' ),
								'custom'  => __( 'Minimum Height', 'ba-cheetah' ),
							),
							'help'    => __( 'Full height rows fill the height of the browser window. Minimum height rows are at least as tall as the value entered.', 'ba-cheetah' ),
							'toggle'  => array(
								'full'   => array(
									'fields' => array( 'content_alignment' ),
								),
								'custom' => array(
									'fields' => array( 'content_alignment', 'min_height' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'min_height'        => array(
							'type'       => 'unit',
							'label'      => __( 'Minimum Height', 'ba-cheetah' ),
							'responsive' => true,
							'units'      => array(
								'px',
								'vw',
								'vh',
							),
							'slider'     => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 10,
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.ba-cheetah-row-content-wrap',
								'property' => 'min-height',
							),
						),
						'content_alignment' => array(
							'type'    => 'select',
							'label'   => __( 'Vertical Alignment', 'ba-cheetah' ),
							'default' => 'center',
							'options' => array(
								'top'    => __( 'Top', 'ba-cheetah' ),
								'center' => __( 'Center', 'ba-cheetah' ),
								'bottom' => __( 'Bottom', 'ba-cheetah' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
				
				'background'       => array(
					'title'  => __( 'Background', 'ba-cheetah' ),
					'fields' => array(
						'bg_type' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'ba-cheetah' ),
							'default' => 'none',
							'options' => array(
								'none'      => _x( 'None', 'Background type.', 'ba-cheetah' ),
								'color'     => _x( 'Color', 'Background type.', 'ba-cheetah' ),
								'gradient'  => _x( 'Gradient', 'Background type.', 'ba-cheetah' ),
								'photo'     => _x( 'Photo', 'Background type.', 'ba-cheetah' ),
								'video'     => _x( 'Video', 'Background type.', 'ba-cheetah' ),
								'embed'     => _x( 'Embedded Code', 'Background type.', 'ba-cheetah' ),
								'slideshow' => array(
									'label'   => _x( 'Slideshow', 'Background type.', 'ba-cheetah' ),
									'pro' => true,
								),
								'parallax'  => array(
									'label'   => _x( 'Parallax', 'Background type.', 'ba-cheetah' ),
									'pro' => true,
								),
							),
							'toggle'  => array(
								'color'     => array(
									'sections' => array( 'bg_color' ),
								),
								'gradient'  => array(
									'sections' => array( 'bg_gradient' ),
								),
								'photo'     => array(
									'sections' => array( 'bg_color', 'bg_photo', 'bg_overlay' ),
								),
								'video'     => array(
									'sections' => array( 'bg_color', 'bg_video', 'bg_overlay' ),
								),
								'slideshow' => array(
									'sections' => array( 'bg_color', 'bg_slideshow', 'bg_overlay' ),
								),
								'parallax'  => array(
									'sections' => array( 'bg_color', 'bg_parallax', 'bg_overlay' ),
								),
								'pattern'   => array(
									'sections' => array( 'bg_pattern', 'bg_color', 'bg_overlay' ),
								),
								'embed'     => array(
									'sections' => array( 'bg_embed_section' ),
								),
							),
							'preview' => array(
								'type' => 'refresh',
							),
						),
					),
				),
				'bg_photo'         => array(
					'title'  => __( 'Background Photo', 'ba-cheetah' ),
					'fields' => array(
						'bg_image_source' => array(
							'type'    => 'select',
							'label'   => __( 'Photo Source', 'ba-cheetah' ),
							'default' => 'library',
							'options' => array(
								'library' => __( 'Media Library', 'ba-cheetah' ),
								'url'     => __( 'URL', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'library' => array(
									'fields' => array( 'bg_image' ),
								),
								'url'     => array(
									'fields' => array( 'bg_image_url', 'caption' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'bg_image_url'    => array(
							'type'        => 'text',
							'label'       => __( 'Photo URL', 'ba-cheetah' ),
							'placeholder' => __( 'https://www.example.com/my-photo.jpg', 'ba-cheetah' ),
							'connections' => array( 'photo' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-image',
							),
						),
						'bg_image'        => array(
							'type'        => 'photo',
							'show_remove' => true,
							'label'       => __( 'Photo', 'ba-cheetah' ),
							'responsive'  => true,
							'connections' => array( 'photo' ),
							'preview'     => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-image',
							),
						),
						'bg_repeat'       => array(
							'type'       => 'select',
							'label'      => __( 'Repeat', 'ba-cheetah' ),
							'default'    => 'none',
							'responsive' => true,
							'options'    => array(
								'no-repeat' => _x( 'None', 'Background repeat.', 'ba-cheetah' ),
								'repeat'    => _x( 'Tile', 'Background repeat.', 'ba-cheetah' ),
								'repeat-x'  => _x( 'Horizontal', 'Background repeat.', 'ba-cheetah' ),
								'repeat-y'  => _x( 'Vertical', 'Background repeat.', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-repeat',
							),
						),
						'bg_position'     => array(
							'type'       => 'select',
							'label'      => __( 'Position', 'ba-cheetah' ),
							'default'    => 'center center',
							'responsive' => true,
							'options'    => array(
								'left top'      => __( 'Left Top', 'ba-cheetah' ),
								'left center'   => __( 'Left Center', 'ba-cheetah' ),
								'left bottom'   => __( 'Left Bottom', 'ba-cheetah' ),
								'right top'     => __( 'Right Top', 'ba-cheetah' ),
								'right center'  => __( 'Right Center', 'ba-cheetah' ),
								'right bottom'  => __( 'Right Bottom', 'ba-cheetah' ),
								'center top'    => __( 'Center Top', 'ba-cheetah' ),
								'center center' => __( 'Center', 'ba-cheetah' ),
								'center bottom' => __( 'Center Bottom', 'ba-cheetah' ),
								'custom_pos'    => __( 'Custom Position', 'ba-cheetah' ),
							),
							'toggle'     => array(
								'custom_pos' => array(
									'fields' => array(
										'bg_x_position',
										'bg_y_position',
									),
								),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-position',
							),
						),
						'bg_x_position'   => array(
							'type'         => 'unit',
							'label'        => __( 'X Position', 'ba-cheetah' ),
							'units'        => array( 'px', '%' ),
							'default_unit' => '%',
							'responsive'   => true,
							'slider'       => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
							'preview'      => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-position-x',
							),
						),
						'bg_y_position'   => array(
							'type'         => 'unit',
							'label'        => __( 'Y Position', 'ba-cheetah' ),
							'units'        => array( 'px', '%' ),
							'default_unit' => '%',
							'responsive'   => true,
							'slider'       => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
							'preview'      => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-position-y',
							),
						),
						'bg_attachment'   => array(
							'type'       => 'select',
							'label'      => __( 'Attachment', 'ba-cheetah' ),
							'default'    => 'scroll',
							'responsive' => true,
							'options'    => array(
								'scroll' => __( 'Scroll', 'ba-cheetah' ),
								'fixed'  => __( 'Fixed', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-attachment',
							),
						),
						'bg_size'         => array(
							'type'       => 'select',
							'label'      => __( 'Scale', 'ba-cheetah' ),
							'default'    => 'cover',
							'responsive' => true,
							'options'    => array(
								'auto'    => _x( 'None', 'Background scale.', 'ba-cheetah' ),
								'contain' => __( 'Fit', 'ba-cheetah' ),
								'cover'   => __( 'Fill', 'ba-cheetah' ),
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-size',
							),
						),
					),
				),
				'bg_video'         => array(
					'title'  => __( 'Background Video', 'ba-cheetah' ),
					'fields' => array(
						'bg_video_source'      => array(
							'type'    => 'select',
							'label'   => __( 'Source', 'ba-cheetah' ),
							'default' => 'wordpress',
							'options' => array(
								'wordpress'     => __( 'Media Library', 'ba-cheetah' ),
								'video_url'     => 'URL',
								'video_service' => __( 'YouTube or Vimeo', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'wordpress'     => array(
									'fields' => array( 'bg_video', 'bg_video_webm' ),
								),
								'video_url'     => array(
									'fields' => array( 'bg_video_url_mp4', 'bg_video_url_webm' ),
								),
								'video_service' => array(
									'fields' => array( 'bg_video_service_url' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'bg_video'             => array(
							'type'        => 'video',
							'show_remove' => true,
							'label'       => __( 'Video (MP4)', 'ba-cheetah' ),
							'help'        => __( 'A video in the MP4 format to use as the background of this row. Most modern browsers support this format.', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'refresh',
							),
						),
						'bg_video_webm'        => array(
							'type'        => 'video',
							'show_remove' => true,
							'label'       => __( 'Video (WebM)', 'ba-cheetah' ),
							'help'        => __( 'A video in the WebM format to use as the background of this row. This format is required to support browsers such as FireFox and Opera.', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'refresh',
							),
						),
						'bg_video_url_mp4'     => array(
							'type'        => 'text',
							'label'       => __( 'Video URL (MP4)', 'ba-cheetah' ),
							'help'        => __( 'A video in the MP4 to use as the background of this row. Most modern browsers support this format.', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'refresh',
							),
							'connections' => array( 'custom_field' ),
						),
						'bg_video_url_webm'    => array(
							'type'        => 'text',
							'label'       => __( 'Video URL (WebM)', 'ba-cheetah' ),
							'help'        => __( 'A video in the WebM format to use as the background of this row. This format is required to support browsers such as FireFox and Opera.', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'refresh',
							),
							'connections' => array( 'custom_field' ),
						),
						'bg_video_service_url' => array(
							'type'        => 'text',
							'label'       => __( 'YouTube Or Vimeo URL', 'ba-cheetah' ),
							'help'        => __( 'A video from YouTube or Vimeo to use as the background of this row. Most modern browsers support this format.', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'refresh',
							),
							'connections' => array( 'custom_field' ),
						),
						'bg_video_audio'       => array(
							'type'    => 'select',
							'label'   => __( 'Enable Audio', 'ba-cheetah' ),
							'default' => 'no',
							'options' => array(
								'no'  => __( 'No', 'ba-cheetah' ),
								'yes' => __( 'Yes', 'ba-cheetah' ),
							),
							'preview' => array(
								'type' => 'refresh',
							),
						),
						'bg_video_mobile'      => array(
							'type'    => 'select',
							'label'   => __( 'Enable Video in Mobile', 'ba-cheetah' ),
							'help'    => __( 'If set to "Yes", audio is disabled on mobile devices.', 'ba-cheetah' ),
							'default' => 'no',
							'options' => array(
								'no'  => __( 'No', 'ba-cheetah' ),
								'yes' => __( 'Yes', 'ba-cheetah' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'bg_video_fallback'    => array(
							'type'        => 'photo',
							'show_remove' => true,
							'label'       => __( 'Fallback Photo', 'ba-cheetah' ),
							'help'        => __( 'A photo that will be displayed if the video fails to load.', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'refresh',
							),
							'connections' => array( 'photo' ),
						),
					),
				),
				'bg_slideshow'     => array(
					'title'  => __( 'Background Slideshow', 'ba-cheetah' ),
					'fields' => array(
						'ss_source'             => array(
							'type'    => 'select',
							'label'   => __( 'Source', 'ba-cheetah' ),
							'default' => 'wordpress',
							'options' => array(
								'wordpress' => __( 'Media Library', 'ba-cheetah' ),
								'smugmug'   => 'SmugMug',
							),
							'help'    => __( 'Pull images from the WordPress media library or a gallery on your SmugMug site by inserting the RSS feed URL from SmugMug. The RSS feed URL can be accessed by using the get a link function in your SmugMug gallery.', 'ba-cheetah' ),
							'toggle'  => array(
								'wordpress' => array(
									'fields' => array( 'ss_photos' ),
								),
								'smugmug'   => array(
									'fields' => array( 'ss_feed_url' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'ss_photos'             => array(
							'type'        => 'multiple-photos',
							'label'       => __( 'Photos', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'none',
							),
							'connections' => array( 'multiple-photos' ),
						),
						'ss_feed_url'           => array(
							'type'        => 'text',
							'label'       => __( 'Feed URL', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'none',
							),
							'connections' => array( 'custom_field' ),
						),
						'ss_speed'              => array(
							'type'        => 'unit',
							'label'       => __( 'Speed', 'ba-cheetah' ),
							'default'     => '3',
							'size'        => '5',
							'sanitize'    => 'BACheetahUtils::sanitize_non_negative_number',
							'slider'      => true,
							'description' => _x( 'seconds', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'ss_transition'         => array(
							'type'    => 'select',
							'label'   => __( 'Transition', 'ba-cheetah' ),
							'default' => 'fade',
							'options' => array(
								'none'            => _x( 'None', 'Slideshow transition type.', 'ba-cheetah' ),
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
							'preview' => array(
								'type' => 'none',
							),
						),
						'ss_transitionDuration' => array(
							'type'        => 'unit',
							'label'       => __( 'Transition Speed', 'ba-cheetah' ),
							'default'     => '1',
							'size'        => '5',
							'sanitize'    => 'BACheetahUtils::sanitize_non_negative_number',
							'slider'      => true,
							'description' => _x( 'seconds', 'Value unit for form field of time in seconds. Such as: "5 seconds"', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
						'ss_randomize'          => array(
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
				'bg_parallax'      => array(
					'title'  => __( 'Background Parallax', 'ba-cheetah' ),
					'fields' => array(
						'bg_parallax_image' => array(
							'type'        => 'photo',
							'show_remove' => true,
							'label'       => __( 'Photo', 'ba-cheetah' ),
							'preview'     => array(
								'type' => 'none',
							),
							'connections' => array( 'photo' ),
						),
						'bg_parallax_speed' => array(
							'type'    => 'select',
							'label'   => __( 'Speed', 'ba-cheetah' ),
							'default' => 'fast',
							'options' => array(
								'2' => __( 'Fast', 'ba-cheetah' ),
								'5' => _x( 'Medium', 'Speed.', 'ba-cheetah' ),
								'8' => __( 'Slow', 'ba-cheetah' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
				'bg_overlay'       => array(
					'title'  => __( 'Background Overlay', 'ba-cheetah' ),
					'fields' => array(
						'bg_overlay_type'     => array(
							'type'    => 'select',
							'label'   => __( 'Overlay Type', 'ba-cheetah' ),
							'default' => 'color',
							'options' => array(
								'none'     => __( 'None', 'ba-cheetah' ),
								'color'    => __( 'Color', 'ba-cheetah' ),
								'gradient' => __( 'Gradient', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'color'    => array(
									'fields' => array( 'bg_overlay_color' ),
								),
								'gradient' => array(
									'fields' => array( 'bg_overlay_gradient' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'bg_overlay_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Overlay Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'bg_overlay_gradient' => array(
							'type'    => 'gradient',
							'label'   => __( 'Overlay Gradient', 'ba-cheetah' ),
							'preview' => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap:after',
								'property' => 'background-image',
							),
						),
					),
				),
				'bg_color'         => array(
					'title'  => __( 'Background Color', 'ba-cheetah' ),
					'fields' => array(
						'bg_color' => array(
							'type'        => 'color',
							'label'       => __( 'Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
							'connections' => array( 'color' ),
						),
					),
				),
				'bg_gradient'      => array(
					'title'  => __( 'Background Gradient', 'ba-cheetah' ),
					'fields' => array(
						'bg_gradient' => array(
							'type'    => 'gradient',
							'label'   => __( 'Gradient', 'ba-cheetah' ),
							'preview' => array(
								'type'     => 'css',
								'selector' => '> .ba-cheetah-row-content-wrap',
								'property' => 'background-image',
							),
						),
					),
				),
				'bg_embed_section' => array(
					'title'  => __( 'Background Embedded Code', 'ba-cheetah' ),
					'fields' => array(
						'bg_embed_code' => array(
							'type'        => 'code',
							'editor'      => 'html',
							'rows'        => '8',
							'preview'     => array(
								'type' => 'refresh',
							),
							'connections' => array( 'string' ),
						),
					),
				),
				'colors'           => array(
					'title'  => __( 'Colors', 'ba-cheetah' ),
					'fields' => array(
						'text_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'link_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Link Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'hover_color'   => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Link Hover Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'heading_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Heading Color', 'ba-cheetah' ),
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
				'border'           => array(
					'title'  => __( 'Border', 'ba-cheetah' ),
					'fields' => array(
						'border' => array(
							'type'       => 'border',
							'label'      => __( 'Border', 'ba-cheetah' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.ba-cheetah-row-content-wrap',
							),
						),
					),
				),
			),
		),
		'advanced' => array(
			'title'    => __( 'Advanced', 'ba-cheetah' ),
			'sections' => array(
				'margins'       => array(
					'title'  => __( 'Spacing', 'ba-cheetah' ),
					'fields' => array(
						'margin'  => array(
							'type'       => 'dimension',
							'label'      => __( 'Margins', 'ba-cheetah' ),
							'slider'     => true,
							'units'      => array(
								'px',
								'%',
								'vw',
								'vh',
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.ba-cheetah-row-content-wrap',
								'property' => 'margin',
							),
							'responsive' => array(
								'default_unit' => array(
									'default'    => $global_settings->row_margins_unit,
									'medium'     => $global_settings->row_margins_medium_unit,
									'responsive' => $global_settings->row_margins_responsive_unit,
								),
								'placeholder'  => array(
									'default'    => empty( $global_settings->row_margins ) ? '0' : $global_settings->row_margins,
									'medium'     => empty( $global_settings->row_margins_medium ) ? '0' : $global_settings->row_margins_medium,
									'responsive' => empty( $global_settings->row_margins_responsive ) ? '0' : $global_settings->row_margins_responsive,
								),
							),
						),
						'padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Padding', 'ba-cheetah' ),
							'slider'     => true,
							'units'      => array(
								'px',
								'em',
								'%',
								'vw',
								'vh',
							),
							'preview'    => array(
								'type'     => 'css',
								'selector' => '.ba-cheetah-row-content-wrap',
								'property' => 'padding',
							),
							'responsive' => array(
								'default_unit' => array(
									'default'    => $global_settings->row_padding_unit,
									'medium'     => $global_settings->row_padding_medium_unit,
									'responsive' => $global_settings->row_padding_responsive_unit,
								),
								'placeholder'  => array(
									'default'    => empty( $global_settings->row_padding ) ? '0' : $global_settings->row_padding,
									'medium'     => empty( $global_settings->row_padding_medium ) ? '0' : $global_settings->row_padding_medium,
									'responsive' => empty( $global_settings->row_padding_responsive ) ? '0' : $global_settings->row_padding_responsive,
								),
							),
						),
					),
				),
				'visibility'    => array(
					'title'  => __( 'Visibility', 'ba-cheetah' ),
					'fields' => array(
						'responsive_display_warning' => array(
							'type' => 'raw',
							'content' => __('Visibility settings can only be viewed in preview mode. While you are editing, all elements will be visible.', 'ba-cheetah' ),
						),
						'responsive_display'         => array(
							'type'    => 'select',
							'label'   => __( 'Breakpoint', 'ba-cheetah' ),
							'options' => array(
								''               => __( 'All', 'ba-cheetah' ),
								'desktop'        => __( 'Large Devices Only', 'ba-cheetah' ),
								'desktop-medium' => __( 'Large &amp; Medium Devices Only', 'ba-cheetah' ),
								'medium'         => __( 'Medium Devices Only', 'ba-cheetah' ),
								'medium-mobile'  => __( 'Medium &amp; Small Devices Only', 'ba-cheetah' ),
								'mobile'         => __( 'Small Devices Only', 'ba-cheetah' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'visibility_display'         => array(
							'type'    => 'select',
							'label'   => __( 'Display', 'ba-cheetah' ),
							'options' => array(
								''           => __( 'Always', 'ba-cheetah' ),
								'logged_out' => __( 'Logged Out User', 'ba-cheetah' ),
								'logged_in'  => __( 'Logged In User', 'ba-cheetah' ),
								'0'          => __( 'Never', 'ba-cheetah' ),
							),
							'toggle'  => array(
								'logged_in' => array(
									'fields' => array( 'visibility_user_capability' ),
								),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
						'visibility_user_capability' => array(
							'type'        => 'text',
							'label'       => __( 'User Capability', 'ba-cheetah' ),
							/* translators: %s: wporg docs link */
							'description' => sprintf( __( 'Optional. Set the <a%s>capability</a> required for users to view this row.', 'ba-cheetah' ), ' href="http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table" target="_blank"' ),
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
				'animation'     => array(
					'title'  => __( 'Animation', 'ba-cheetah' ),
					'fields' => array(
						'animation' => array(
							'type'    => 'animation',
							'label'   => __( 'Animation', 'ba-cheetah' ),
							'preview' => array(
								'type'     => 'animation',
								'selector' => '{node}',
							),
						),
					),
				),
				'css_selectors' => array(
					'title'  => __( 'HTML Element', 'ba-cheetah' ),
					'fields' => array(
						'container_element' => array(
							'type'    => 'select',
							'label'   => __( 'Container Element', 'ba-cheetah' ),
							'default' => apply_filters( 'ba_cheetah_row_container_element_default', 'div' ),
							/**
							 * Filter to add/remove container types.
							 * @see ba_cheetah_node_container_element_options
							 */
							'options' => apply_filters( 'ba_cheetah_node_container_element_options', array(
								'div'     => '&lt;div&gt;',
								'section' => '&lt;section&gt;',
								'article' => '&lt;article&gt;',
								'aside'   => '&lt;aside&gt;',
								'main'    => '&lt;main&gt;',
								'header'  => '&lt;header&gt;',
								'footer'  => '&lt;footer&gt;',
							)),
							'help'    => __( 'Optional. Choose an appropriate HTML5 content sectioning element to use for this row to improve accessibility and machine-readability.', 'ba-cheetah' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'id'                => array(
							'type'    => 'text',
							'label'   => __( 'ID', 'ba-cheetah' ),
							'help'    => __( "A unique ID that will be applied to this row's HTML. Must start with a letter and only contain dashes, underscores, letters or numbers. No spaces.", 'ba-cheetah' ),
							'preview' => array(
								'type' => 'none',
							),
						),
						'class'             => array(
							'type'    => 'text',
							'label'   => __( 'Class', 'ba-cheetah' ),
							'help'    => __( "A class that will be applied to this row's HTML. Must start with a letter and only contain dashes, underscores, letters or numbers. Separate multiple classes with spaces.", 'ba-cheetah' ),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
	),
);

// Merge Shape Layer Sections
$style_sections                            = $row_settings['tabs']['style']['sections'];
$shape_sections                            = BACheetahArt::get_shape_settings_sections();
$row_settings['tabs']['style']['sections'] = array_merge( $style_sections, $shape_sections );

// Register
BACheetah::register_settings_form( 'row', $row_settings );
