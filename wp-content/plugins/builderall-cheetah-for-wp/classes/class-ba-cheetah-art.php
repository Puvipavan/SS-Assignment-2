<?php
/**
 * Handle SVG Artwork for the system.
 */
class BACheetahArt {

	/**
	 * All registered shapes
	 */
	static private $artwork = array();

	/**
	 * Which node types support layers
	 */
	static private $supported_node_types = array( 'row' );

	/**
	* Initialize the artwork handling
	*
	* @return void
	*/
	static public function init() {

		// Render layer(s) output into node output
		add_action( 'ba_cheetah_render_node_layers', 'BACheetahArt::render_node_layers' );

		// Setup Shapes and Preset definitions
		add_action( 'ba_cheetah_register_presets', 'BACheetahArt::register_shapes' );

		// Add special <option> sets for js output
		add_filter( 'ba_cheetah_shared_option_sets', 'BACheetahArt::filter_shared_option_sets' );
	}

	/**
	 * Register the system art and presets. Called by the ba_cheetah_register_presets action (see BACheetahSettingsPresets )
	 *
	 * @return void
	 */
	static public function register_shapes() {

		$art_dir = BA_CHEETAH_DIR . 'includes/shapes/';

		self::register_shape(array(
			'label'  => __( 'Slanted Edge', 'ba-cheetah' ),
			'name'   => 'edge-slant',
			'width'  => 422,
			'height' => 33.98,
			'render' => $art_dir . 'edge-slant.svg.php',
		));

		self::register_shape(array(
			'label'  => __( 'Waves', 'ba-cheetah' ),
			'name'   => 'wavy',
			'width'  => 800,
			'height' => 102,
			'render' => $art_dir . 'wavy.svg.php',
		));

		self::register_shape( array(
			'label'  => __( 'Midpoint', 'ba-cheetah' ),
			'name'   => 'midpoint',
			'width'  => 800,
			'height' => 50,
			'render' => $art_dir . 'midpoint.svg.php',
		));

		self::register_shape( array(
			'label'  => __( 'Triangle', 'ba-cheetah' ),
			'name'   => 'triangle',
			'width'  => 50,
			'height' => 34,
			'render' => $art_dir . 'triangle.svg.php',
		));
		self::register_shape( array(
			'label'  => __( 'Circle', 'ba-cheetah' ),
			'name'   => 'circle',
			'width'  => 100,
			'height' => 100,
			'render' => $art_dir . 'circle.svg.php',
		));
		self::register_shape( array(
			'label'  => __( 'Concave', 'ba-cheetah' ),
			'name'   => 'concave',
			'width'  => 800,
			'height' => 50,
			'render' => $art_dir . 'concave.svg.php',
		));
		self::register_shape( array(
			'label'  => __( 'Spots', 'ba-cheetah' ),
			'name'   => 'dot-cluster',
			'width'  => 800,
			'height' => 315,
			'render' => $art_dir . 'dot-cluster.svg.php',
		));
		self::register_shape( array(
			'label'  => __( 'Topography', 'ba-cheetah' ),
			'name'   => 'topography',
			'width'  => 600,
			'height' => 600,
			'render' => $art_dir . 'topography.svg.php',
		));
		self::register_shape( array(
			'label'  => __( 'Rectangle', 'ba-cheetah' ),
			'name'   => 'rect',
			'width'  => 800,
			'height' => 450,
			'render' => $art_dir . 'rect.svg.php',
		));

		/**
		 * Trigger registration process for external shapes.
		 * @see ba_cheetah_register_art
		 */
		do_action( 'ba_cheetah_register_art' );
	}

	/**
	 * Register a new piece of SVG art into the system
	 *
	 * @param Array $args - the metadata for a piece of art
	 * @return void
	 */
	static public function register_shape( $args = array() ) {
		$defaults = array(
			'label'                 => __( 'Untitled Shape', 'ba-cheetah' ),
			'name'                  => 'untitled-shape',
			'x'                     => 0,
			'y'                     => 0,
			'width'                 => 0,
			'height'                => 0,
			'preserve_aspect_ratio' => 'none',
			'render'                => '',
			'preset_settings'       => array(),
		);

		$args = wp_parse_args( $args, $defaults );
		/**
		 * Filter shape args during shape_register()
		 * @see ba_cheetah_art_register_shape

		 */
		$args = apply_filters( 'ba_cheetah_art_register_shape', $args );
		$key  = $args['name'];

		/**
		 * Setup a preset to reference the shape's initial configuration later
		 * This is so when you choose a shape, we can also setup other fields for the optimal initial appearance.
		 */
		BACheetahSettingsPresets::register( 'shape', array(
			'name'     => $args['name'],
			'label'    => $args['label'],
			'settings' => $args['preset_settings'],
			'data'     => array(
				'viewBox' => array(
					'x'      => $args['x'],
					'y'      => $args['y'],
					'width'  => $args['width'],
					'height' => $args['height'],
				),
			),
		));

		self::$artwork[ $key ] = $args;
	}

	/**
	 * Return the array of registered artwork
	 *
	 * @param String $key - index key in the artwork array
	 * @return Array
	 */
	static public function get_art( $key = null ) {
		/**
		 * Array of all registered shapes
		 * @see ba_cheetah_shape_artwork
		 */
		$art = apply_filters( 'ba_cheetah_shape_artwork', self::$artwork );

		if ( $key && isset( $art[ $key ] ) ) {
			return $art[ $key ];
		}

		return $art;
	}

	/**
	* Create option sets for each preset type and add to BACheetahConfig.optionSets
	*
	* @param Array $option_sets - previously set option sets
	* @return Array
	*/
	static public function filter_shared_option_sets( $option_sets ) {
		$art = self::get_art();

		$option_sets['shapes'] = array(
			'' => __( 'None', 'ba-cheetah' ),
		);

		foreach ( $art as $handle => $shape ) {
			$option_sets['shapes'][ $handle ] = $shape['label'];
		}

		return $option_sets;
	}

	/**
	 * Render the shape artwork with the current settings.
	 *
	 * @param Array $shape - the registered metadata for the current shape
	 * @param Object $settings - the current node's settings object
	 * @return String - the rendered string
	 */
	static public function render_art( $shape, $settings ) {

		// Render artwork into a buffer
		if ( $shape ) {
			ob_start();
			$render = $shape['render'];

			if ( is_string( $render ) && file_exists( $render ) ) {
				include $render;
			}
			$output = ob_get_clean();
		}
		return $output;
	}

	/**
	 * Get the node types that support layers
	 *
	 * @return Array
	 */
	static public function get_supported_node_types() {
		return self::$supported_node_types;
	}

	/**
	 * Get any layers added to a node
	 *
	 * @param Object $node being rendered
	 * @return Array of layer descriptions
	 */
	static public function get_node_layers( $node ) {
		$layers = array();

		if ( in_array( $node->type, self::get_supported_node_types() ) ) {

			$settings = $node->settings;

			if ( ! empty( $settings->{'top_edge_shape'} ) ) {
				$layers['top'] = array(
					'label'    => __( 'Top Shape Layer', 'ba-cheetah' ),
					'type'     => 'shape',
					'prefix'   => 'top_edge_',
					'position' => 'top',
				);
			}
			if ( ! empty( $settings->{'bottom_edge_shape'} ) ) {
				$layers['bottom'] = array(
					'label'    => __( 'Bottom Shape Layer', 'ba-cheetah' ),
					'type'     => 'shape',
					'prefix'   => 'bottom_edge_',
					'position' => 'bottom',
				);
			}
		}

		return $layers;
	}

	/**
	 * Render any layers a node has
	 *
	 * @param Object $node
	 * @return void
	 */
	static public function render_node_layers( $node ) {
		$layers = self::get_node_layers( $node );

		if ( ! empty( $layers ) ) {
			foreach ( $layers as $key => $layer ) {
				self::render_node_layer( $layer, $node );
			}
		}
	}

	/**
	 * Render a single layer into a node
	 *
	 * @param Array $layer meta
	 * @param Object $node
	 * @return void
	 */
	static public function render_node_layer( $layer, $node ) {
		if ( 'shape' === $layer['type'] ) {
			self::render_node_shape_layer( $layer, $node );
			return;
		}
	}

	/**
	 * Render a shape layer into a node
	 *
	 * @param Array $layer meta
	 * @param Object $node
	 * @return void
	 */
	static public function render_node_shape_layer( $layer, $node ) {

		$settings   = $node->settings;
		$id         = $node->node;
		$position   = $layer['position'];
		$prefix     = $layer['prefix'];
		$shape_name = $settings->{ $prefix . 'shape' };
		$shape_args = self::get_art( $shape_name );
		$content    = self::render_art( $shape_args, $settings );

		$x                     = $shape_args['x'];
		$y                     = $shape_args['y'];
		$width                 = $shape_args['width'];
		$height                = $shape_args['height'];
		$view_box              = "$x $y $width $height";
		$preserve_aspect_ratio = $shape_args['preserve_aspect_ratio'];

		$align     = $settings->{ $prefix . 'align' };
		$ending    = str_replace( ' ', '-', $align );
		$svg_class = 'ba-cheetah-layer-align-' . $ending;
		include BA_CHEETAH_DIR . 'includes/shape-layer.php';
	}


	/**
	 * Get the settings form for shapes
	 *
	 * @return void
	 */
	static public function get_shape_settings_sections() {
		$sections = array();
		$layers   = array(
			'top'    => __( 'Top', 'ba-cheetah' ),
			'bottom' => __( 'Bottom', 'ba-cheetah' ),
		);

		foreach ( $layers as $position => $position_label ) {
			$prefix = $position . '_edge_';

			// Preset & Shape Section
			$sections[ $prefix . 'shape' ] = array(
				/* translators: %s: position label */
				'title'  => sprintf( __( '%s Shape', 'ba-cheetah' ), $position_label ),
				'fields' => array(
					$prefix . 'shape' => array(
						'type'    => 'select',
						'label'   => __( 'Shape', 'ba-cheetah' ),
						'options' => 'shapes',
						'hide'    => array(
							'' => array(
								'sections' => array(
									$prefix . 'style',
								),
								'fields'   => array(
									$prefix . 'size',
									$prefix . 'align',
									$prefix . 'z_pos',
								),
							),
						),
						'preview' => array(
							'type'     => 'callback',
							'callback' => 'previewShape',
							'prefix'   => $prefix,
							'position' => $position,
						),
					),
					$prefix . 'size'  => array(
						'type'    => 'dimension',
						'label'   => __( 'Size', 'ba-cheetah' ),
						'units'   => array( 'px', 'vw', 'vh', '%' ),
						'slider'  => array(
							'width'  => array(
								'px' => array(
									'min'  => 0,
									'max'  => 5000,
									'step' => 10,
								),
								'vw' => array(
									'min' => 0,
									'max' => 500,
								),
								'vh' => array(
									'min' => 0,
									'max' => 500,
								),
								'%'  => array(
									'min' => 0,
									'max' => 300,
								),
							),
							'height' => array(
								'px' => array(
									'min'  => 0,
									'max'  => 2000,
									'step' => 10,
								),
								'vw' => array(
									'min' => 0,
									'max' => 200,
								),
								'vh' => array(
									'min' => 0,
									'max' => 200,
								),
								'%'  => array(
									'min' => 0,
									'max' => 100,
								),
							),
							'top'    => array(
								'px' => array(
									'min' => -500,
									'max' => 500,
								),
								'vw' => array(
									'min' => -20,
									'max' => 20,
								),
								'vh' => array(
									'min' => -20,
									'max' => 20,
								),
								'%'  => array(
									'min' => 0,
									'max' => 100,
								),
							),
						),
						'keys'    => array(
							'width'  => __( 'Width', 'ba-cheetah' ),
							'height' => __( 'Height', 'ba-cheetah' ),
							'top'    => __( 'Y Offset', 'ba-cheetah' ),
						),
						'preview' => array(
							'type'     => 'callback',
							'callback' => 'previewShapeLayerSize',
							'prefix'   => $prefix,
							'position' => $position,
						),
					),
					$prefix . 'align' => array(
						'type'    => 'select',
						'label'   => __( 'Align', 'ba-cheetah' ),
						'default' => $position . ' center',
						'options' => array(
							'top left'      => __( 'Top Left', 'ba-cheetah' ),
							'top center'    => __( 'Top Center', 'ba-cheetah' ),
							'top right'     => __( 'Top Right', 'ba-cheetah' ),
							'center left'   => __( 'Center Left', 'ba-cheetah' ),
							'center center' => __( 'Center', 'ba-cheetah' ),
							'center right'  => __( 'Center Right', 'ba-cheetah' ),
							'bottom left'   => __( 'Bottom Left', 'ba-cheetah' ),
							'bottom center' => __( 'Bottom Center', 'ba-cheetah' ),
							'bottom right'  => __( 'Bottom Right', 'ba-cheetah' ),
						),
						'preview' => array(
							'type'     => 'callback',
							'callback' => 'previewShapeAlign',
							'prefix'   => $prefix,
							'selector' => ".ba-cheetah-$position-edge-layer > *",
						),
					),
				),
			);

			// Shape Styles
			$sections[ $prefix . 'style' ] = array(
				/* translators: %s: position label */
				'title'  => sprintf( __( '%s Shape Style', 'ba-cheetah' ), $position_label ),
				'fields' => array(
					$prefix . 'fill_style'    => array(
						'type'    => 'button-group',
						'options' => array(
							'color'    => __( 'Color Fill', 'ba-cheetah' ),
							'gradient' => __( 'Gradient Fill', 'ba-cheetah' ),
						),
						'default' => 'color',
						'preview' => array(
							'type'     => 'callback',
							'callback' => 'previewShapeFillStyle',
							'position' => $position,
							'prefix'   => $prefix,
							'selector' => ".ba-cheetah-$position-edge-layer .ba-cheetah-shape-content .ba-cheetah-shape",
						),
						'toggle'  => array(
							'color'    => array(
								'fields' => array(
									$prefix . 'fill_color',
								),
							),
							'gradient' => array(
								'fields' => array(
									$prefix . 'fill_gradient',
								),
							),
						),
					),
					$prefix . 'fill_color'    => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Color', 'ba-cheetah' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'responsive'  => true,
						'default'     => 'aaa',
						'preview'     => array(
							'type'     => 'css',
							'selector' => ".ba-cheetah-$position-edge-layer .ba-cheetah-shape-content .ba-cheetah-shape",
							'property' => 'fill',
						),
					),
					$prefix . 'fill_gradient' => array(
						'type'    => 'gradient',
						'label'   => __( 'Gradient', 'ba-cheetah' ),
						'default' => '',
						'preview' => array(
							'type'     => 'callback',
							'callback' => 'previewShapeGradientFill',
							'position' => $position,
							'prefix'   => $prefix,
						),
					),

					$prefix . 'transform'     => array(
						'type'    => 'shape-transform',
						'label'   => __( 'Transform', 'ba-cheetah' ),
						'preview' => array(
							'type'     => 'callback',
							'callback' => 'previewShapeTransform',
							'selector' => ".ba-cheetah-$position-edge-layer",
							'position' => $position,
						),
					),
				),
			);
		}

		$sections['shapes_container'] = array(
			'title'  => __( 'Shape Container', 'ba-cheetah' ),
			'fields' => array(
				'container_overflow' => array(
					'type'    => 'select',
					'label'   => __( 'Clip Within Container', 'ba-cheetah' ),
					'options' => array(
						''       => __( 'No Clip', 'ba-cheetah' ),
						'hidden' => __( 'Clip Contents', 'ba-cheetah' ),
					),
					'preview' => array(
						'type'     => 'css',
						'selector' => '.ba-cheetah-row-content-wrap',
						'property' => 'overflow',
					),
				),
			),
		);
		return $sections;
	}

	/**
	 * Render the CSS for any shape layers set on a given node
	 *
	 * @param Object $node - the current node
	 * @return void
	 */
	static public function render_shape_layers_css( $node ) {
		$settings = $node->settings;
		$id       = $node->node;

		$layers = array( 'top', 'bottom' );

		foreach ( $layers as $position ) {
			$prefix = $position . '_edge_';

			if ( ! empty( $settings->{ $prefix . 'shape' } ) ) {

				$shape_name = $settings->{ $prefix . 'shape' };
				$presets    = BACheetahSettingsPresets::get_presets();
				$preset     = ( isset( $presets['shape'][ $shape_name ] ) ) ? $presets['shape'][ $shape_name ] : false;

				if ( ! $preset ) {
					continue;
				}

				BACheetahCSS::rule( array(
					'selector' => ".ba-cheetah-node-$id .ba-cheetah-$position-edge-layer",
					'enabled'  => $settings->{ $prefix . 'size_top'} && $settings->{ $prefix . 'size_unit' },
					'props'    => array(
						$position => $settings->{ $prefix . 'size_top'} . $settings->{ $prefix . 'size_unit' },
					),
				) );

				// Width, Height & Align
				$shape_selector = ".ba-cheetah-node-$id .ba-cheetah-$position-edge-layer > *";
				$shape_align    = explode( ' ', $settings->{ $prefix . 'align' } );
				$align_y        = $shape_align[0];
				$align_x        = $shape_align[1];
				$width          = $settings->{ $prefix . 'size_width'};
				$height         = $settings->{ $prefix . 'size_height' };
				$size_unit      = $settings->{ $prefix . 'size_unit' };

				// Defaults
				$shape_size_rule = array(
					'selector' => $shape_selector,
					'enabled'  => true,
					'props'    => array(),
				);
				$size_props      = array(
					'width'  => '100%',
					'left'   => 'auto',
					'right'  => 'auto',
					'height' => 'auto',
					'top'    => 'auto',
					'bottom' => 'auto',
				);

				if ( ! empty( $width ) ) {
					$size_props['width'] = $width . $size_unit;
					$width_offset        = ( $width / 2 ) . $size_unit;

					switch ( $align_x ) {
						case 'left':
							$size_props['left'] = '0';
							break;
						case 'right':
							$size_props['right'] = '0';
							break;
						case 'center':
							$size_props['left'] = "calc( 50% - $width_offset )";
							break;
					}
				}

				$height_offset = '';
				if ( ! empty( $height ) ) {
					$height_offset        = ( $height / 2 ) . $size_unit;
					$size_props['height'] = $height . $size_unit;
				} elseif ( $width ) {
					$view_box_height = $preset['data']['viewBox']['width'];
					$implied_height  = ( $width / $view_box_height ) * 100;
					$height_offset   = ( $implied_height / 2 ) . $size_unit;
				}

				switch ( $align_y ) {
					case 'top':
						$size_props['top'] = '0';
						break;
					case 'bottom':
						$size_props['bottom'] = '0';
						$size_props['top']    = 'auto';
						break;
					case 'center':
						$size_props['top'] = "calc( 50% - $height_offset )";
						break;
				}

				$shape_size_rule['props'] = $size_props;
				BACheetahCSS::rule( $shape_size_rule );

				// Shape Transforms

				$transforms       = $settings->{ $prefix . 'transform' };
				$layer_transforms = array();
				$shape_transforms = array();
				$sign             = '';
				if ( ! empty( $transforms ) ) {

					foreach ( $transforms as $prop => $value ) {
						switch ( $prop ) {
							case 'scaleXSign':
							case 'scaleYSign':
								break;

							case 'scaleX':
							case 'scaleY':
								if ( empty( $value ) ) {
									$value = 1;
								}

								// Positive or negative?
								if ( 'scaleX' === $prop ) {
									if ( isset( $transforms['scaleXSign'] ) ) {
										$sign = $transforms['scaleXSign'];
									}
								} else {
									if ( isset( $transforms['scaleYSign'] ) ) {
										$sign = $transforms['scaleYSign'];
									}
								}
								if ( 'invert' === $sign ) {
									$value = -abs( $value );
								} else {
									$value = abs( $value );
								}

								$value              = $prop . '(' . $value . ')';
								$shape_transforms[] = $value;
								break;

							case 'translateX':
							case 'translateY':
								if ( ! empty( $value ) ) {
									$value              = $prop . '(' . $value . 'px)';
									$shape_transforms[] = $value;
								}
								break;

							case 'skewX':
							case 'skewY':
								if ( ! empty( $value ) ) {
									$shape_transforms[] = $prop . '(' . $value . 'deg)';
								}
								break;

							case 'rotate':
								if ( ! empty( $value ) ) {
									$shape_transforms[] = 'rotate(' . $value . 'deg)';
								}
								break;
						}
					}
					// Shape Transforms
					BACheetahCSS::rule( array(
						'settings' => $settings,
						'enabled'  => ! empty( $shape_transforms ),
						'selector' => ".ba-cheetah-node-$id .ba-cheetah-$position-edge-layer > *",
						'props'    => array(
							'transform' => implode( ' ', $shape_transforms ),
						),
					) );
				}

				// Shape Fill
				if ( ! empty( $settings->{ $prefix . 'fill_style' } ) ) {
					switch ( $settings->{ $prefix . 'fill_style' } ) {

						case 'color':
							BACheetahCSS::responsive_rule( array(
								'settings'     => $settings,
								'setting_name' => $prefix . 'fill_color',
								'selector'     => ".ba-cheetah-node-$id .ba-cheetah-$position-edge-layer .ba-cheetah-shape-content .ba-cheetah-shape",
								'prop'         => 'fill',
							) );
							break;

						case 'gradient':
							$gradient_type = $settings->{ $prefix . 'fill_gradient' }['type'];
							$gradient_id   = "ba-cheetah-row-$id-$prefix-$gradient_type-gradient";
							BACheetahCSS::rule( array(
								'selector' => ".ba-cheetah-node-$id .ba-cheetah-$position-edge-layer .ba-cheetah-shape",
								'enabled'  => $settings->{ $prefix . 'fill_gradient' },
								'props'    => array(
									'fill' => 'url(#' . $gradient_id . ')',
								),
							) );
							break;
						case 'pattern':
							$pattern_id = "ba-cheetah-row-$id-$prefix-pattern";
							BACheetahCSS::rule( array(
								'selector' => ".ba-cheetah-node-$id .ba-cheetah-$position-edge-layer .ba-cheetah-shape-content .ba-cheetah-shape",
								'enabled'  => true,
								'props'    => array(
									'fill' => 'url(#' . $pattern_id . ')',
								),
							) );
							BACheetahCSS::rule( array(
								'selector' => ".ba-cheetah-node-$id .ba-cheetah-$position-edge-layer pattern .ba-cheetah-shape",
								'enabled'  => true,
								'props'    => array(
									'fill' => $settings->{ $prefix . 'fill_pattern_shape_color' },
								),
							) );
							break;
					}
				}
			}
		}

		// Shared styles
		BACheetahCSS::responsive_rule( array(
			'settings'     => $settings,
			'setting_name' => 'container_overflow',
			'selector'     => ".ba-cheetah-node-$id .ba-cheetah-row-content-wrap",
			'prop'         => 'overflow',
		) );
	}

	/**
	 * Convert a position keyword ( left, right, center, ... ) to a position integer ( 0.0 - 1.0 )
	 *
	 * @param String $position
	 * @return Int | Null
	 */
	static public function get_int_for_position_name( $position = '' ) {

		switch ( $position ) {
			case 'left':
			case 'top':
				return 0;
			case 'center':
				return .5;
			case 'right':
			case 'bottom':
				return 1;
			default:
				return null;
		}
	}
}
BACheetahArt::init();
