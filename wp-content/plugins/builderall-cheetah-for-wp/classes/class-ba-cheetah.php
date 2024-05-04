<?php

/**
 * Main builder class.
 *

 */
final class BACheetah {

	/**
	 * The ID of a post that is currently being rendered.
	 *

	 * @var int $post_rendering
	 */
	static public $post_rendering = null;

	/**
	 * Stores the default directory name to look for in a theme for BB templates.
	 *

	 * @var string $template_dir
	 */
	static private $template_dir = 'ba-cheetah/includes';

	/**
	 * An array of asset paths that have already been rendered. This is
	 * used to ensure that the same asset isn't rendered twice on the same
	 * page. That typically can happen when you do things like insert the
	 * same layout twice using the ba_cheetah_insert_layout shortcode.
	 *

	 * @var bool $rendered_assets
	 */
	static private $rendered_assets = array();

	/**
	 * An array of which global assets have already been enqueued. This is
	 * used to ensure that only one copy of either the global CSS or JS is
	 * ever loaded on the page at one time.
	 *
	 * For example, if a layout CSS file with the global CSS included in it
	 * has already been enqueued, subsequent layout CSS files will not include
	 * the global CSS.
	 *

	 * @var bool $enqueued_global_assets
	 */
	static private $enqueued_global_assets = array();

	/**

	 */
	static private $enqueued_module_js_assets  = array();
	static private $enqueued_module_css_assets = array();

	/**
	 * Used to store JS that is to be rendered inline on the wp_footer
	 * action when the ba_cheetah_render_assets_inline filter is true.
	 *

	 * @var string $inline_js
	 */
	static private $inline_js = '';

	/**
	 * Font awesome urls.

	 */
	static public $fa4_url     = '';
	static public $fa5_pro_url = 'https://pro.fontawesome.com/releases/v5.15.1/css/all.css';

	/**
	 * Initializes hooks.
	 *

	 * @return void
	 */
	static public function init() {
		/* Actions */
		add_action( 'plugins_loaded', __CLASS__ . '::load_plugin_textdomain' );
		add_action( 'send_headers', __CLASS__ . '::no_cache_headers' );
		add_action( 'wp', __CLASS__ . '::init_ui', 11 );
		add_action( 'wp', __CLASS__ . '::rich_edit' );
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_layout_styles_scripts' );
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::enqueue_ui_styles_scripts', 11 );
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::enqueue_all_layouts_styles_scripts' );
		add_action( 'wp_head', __CLASS__ . '::render_custom_css_for_editing', 999 );
		add_action( 'wp_head', __CLASS__ . '::register_frontend_scripts_before_end_head', 998 );
		add_action( 'admin_bar_menu', __CLASS__ . '::admin_bar_menu', 999 );
		add_action( 'wp_body_open', __CLASS__ . '::open_content_wrapper');
		add_action( 'wp_footer', __CLASS__ . '::close_content_wrapper');
		add_action( 'wp_footer', __CLASS__ . '::render_ui' );

		/* Filters */
		add_filter( 'ba_cheetah_render_css', __CLASS__ . '::rewrite_css_cache_urls', 9999 );
		add_filter( 'body_class', __CLASS__ . '::body_class' );
		add_filter( 'wp_default_editor', __CLASS__ . '::default_editor' );
		add_filter( 'mce_css', __CLASS__ . '::add_editor_css' );
		add_filter( 'mce_buttons', __CLASS__ . '::editor_buttons' );
		add_filter( 'mce_buttons_2', __CLASS__ . '::editor_buttons_2' );
		add_filter( 'mce_external_plugins', __CLASS__ . '::editor_external_plugins', 9999 );
		add_filter( 'tiny_mce_before_init', __CLASS__ . '::editor_font_sizes' );
		add_filter( 'the_content', __CLASS__ . '::render_content' );
		add_filter( 'wp_handle_upload_prefilter', __CLASS__ . '::wp_handle_upload_prefilter_filter' );
		add_filter( 'wp_link_query_args', __CLASS__ . '::wp_link_query_args_filter' );
		add_filter( 'ba_cheetah_load_modules_paths', __CLASS__ . '::load_module_paths', 9999 );
	}

	/**

	 */
	static public function load_module_paths( $paths ) {

		$enabled      = array();
		$dependencies = self::module_dependencies();
		$protected    = array();

		if ( is_admin() ) {
			return $paths;
		}

		if ( ! self::is_module_disable_enabled() ) {
			return $paths;
		}

		$enabled_modules = BACheetahModel::get_enabled_modules();

		if ( is_array( $enabled_modules ) && empty( $enabled_modules ) ) {
			return $paths;
		}

		if ( isset( $enabled_modules[0] ) && 'all' === $enabled_modules[0] ) {
			return $paths;
		}

		// setup reverse dependencies
		foreach ( $paths as $k => $path ) {
			$module = basename( $path );
			$deps   = isset( $dependencies[ $module ] ) ? $dependencies[ $module ] : array();
			if ( count( $deps ) > 0 ) {
				foreach ( $deps as $dep ) {
					$protected[] = $dep;
				}
			}
		}

		foreach ( $paths as $k => $path ) {
			$module = basename( $path );

			if ( in_array( $module, $enabled_modules, true ) || in_array( $module, $protected, true ) ) {
				$enabled[] = $path;
			}
		}
		return ! empty( $enabled ) ? $enabled : $paths;
	}

	/**

	 */
	public static function is_module_disable_enabled() {
		/**
		 * Enable Module enable/disable advanced mode.

		 * @see is_module_disable_enabled
		 */
		return apply_filters( 'is_module_disable_enabled', false );
	}

	/**

	 */
	public static function module_dependencies() {
		$deps = array(
			'carousel' => array(
				'photo'
			),
			'card' => array(
				'button'
			),
			'gallery' => array(
				'photo'
			),
			'mailingboss' => array(
				'button'
			),
			'posts' => array(
				'card',
				'button'
			),
			'pricing-table' => array(
				'button',
				'photo'
			),
			'pricing-list' => array(
				'photo'
			),
            'flip-box' => array(
                'button'
            ),
		);
		return apply_filters( 'ba_cheetah_module_dependencies', $deps );
	}


	/**
	 * Localization
	 *
	 * Load the translation file for current language. Checks the default WordPress
	 * languages folder first and then the languages folder inside the plugin.
	 *

	 * @return string|bool The translation file path or false if none is found.
	 */
	static public function load_plugin_textdomain() {
		// Traditional WordPress plugin locale filter
		// Uses get_user_locale() which was added in 4.7 so we need to check its available.
		if ( function_exists( 'get_user_locale' ) ) {
			$locale = apply_filters( 'plugin_locale', get_user_locale(), 'ba-cheetah' );
		} else {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'ba-cheetah' );
		}

		/**
		 * Allow users to override the locale.
		 * @see ba_cheetah_set_ui_locale

		 */
		$locale = apply_filters( 'ba_cheetah_set_ui_locale', $locale );

		//Setup paths to current locale file
		$mofile_local  = trailingslashit( BA_CHEETAH_DIR ) . 'languages/' . $locale . '.mo';

		return load_textdomain( 'ba-cheetah', $mofile_local );

		//Nothing found
		return false;
	}

	static public function rich_edit() {
		global $wp_version;
		if ( BACheetahModel::is_builder_active() ) {
			if ( version_compare( $wp_version, '5.4.99', '<' ) ) {
				add_filter( 'get_user_option_rich_editing', '__return_true' );
			} else {
				add_filter( 'user_can_richedit', '__return_true' ); // WP 5.5
			}
		}
	}

	/**
	 * Alias method for registering a template data file with the builder.
	 *

	 * @param string $path The directory path to the template data file.
	 * @return void
	 */
	static public function register_templates( $path, $args = array() ) {
		BACheetahModel::register_templates( $path, $args );
	}

	/**
	 * Alias method for registering a module with the builder.
	 *

	 * @param string $class The module's PHP class name.
	 * @param array $form The module's settings form data.
	 * @return void
	 */
	static public function register_module( $class, $form ) {
		BACheetahModel::register_module( $class, $form );
	}

	/**
	 * Alias method for registering module aliases with the builder.
	 *

	 * @param string $alias The alias key.
	 * @param array $config The alias config.
	 * @return void
	 */
	static public function register_module_alias( $alias, $config ) {
		BACheetahModel::register_module_alias( $alias, $config );
	}

	/**
	 * Alias method for registering a settings form with the builder.
	 *

	 * @param string $id The form's ID.
	 * @param array $form The form data.
	 * @return void
	 */
	static public function register_settings_form( $id, $form ) {
		BACheetahModel::register_settings_form( $id, $form );
	}

	/**
	 * Send no cache headers when the builder interface is active.
	 *

	 * @return void
	 */
	static public function no_cache_headers() {
		//if ( isset( $_GET['ba_cheetah'] ) ) {
		if( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
			header( 'Cache-Control: no-store, no-cache, must-revalidate' );
			header( 'Cache-Control: post-check=0, pre-check=0', false );
			header( 'Pragma: no-cache' );
			header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
		}
	}

	/**
	 * Returns the markup for creating new WP editors in the builder.
	 *

	 * @return string
	 */
	static public function get_wp_editor() {
		ob_start();
		/**
		 * Args passed to wp_editor for text modules.
		 * @see ba_cheetah_get_wp_editor_args
		 */
		wp_editor( '{BA_CHEETAH_EDITOR_CONTENT}', 'bacheetaheditor', apply_filters( 'ba_cheetah_get_wp_editor_args', array(
			'media_buttons' => true,
			'wpautop'       => true,
			'textarea_rows' => 16,
		) ) );

		return ob_get_clean();
	}

	/**
	 * Set the default text editor to tinymce when the builder is active.
	 *

	 * @param string $type The current default editor type.
	 * @return string
	 */
	static public function default_editor( $type ) {
		return BACheetahModel::is_builder_active() ? 'tinymce' : $type;
	}

	/**
	 * Add custom CSS for the builder to the text editor.
	 *

	 * @param string $mce_css
	 * @return string
	 */
	static public function add_editor_css( $mce_css ) {
		if ( BACheetahModel::is_builder_active() ) {

			if ( ! empty( $mce_css ) ) {
				$mce_css .= ',';
			}

			$mce_css .= BA_CHEETAH_URL . 'css/editor.css';
		}

		return $mce_css;
	}

	/**
	 * Filter text editor buttons for the first row
	 *

	 * @param array $buttons The current buttons array.
	 * @return array
	 */
	static public function editor_buttons( $buttons ) {
		if ( BACheetahModel::is_builder_active() ) {
			if ( ( $key = array_search( 'wp_more', $buttons ) ) !== false ) { // @codingStandardsIgnoreLine
				unset( $buttons[ $key ] );
			}
		}

		return $buttons;
	}

	/**
	 * Add additional buttons to the text editor.
	 *

	 * @param array $buttons The current buttons array.
	 * @return array
	 */
	static public function editor_buttons_2( $buttons ) {
		global $wp_version;

		if ( BACheetahModel::is_builder_active() ) {

			array_shift( $buttons );
			array_unshift( $buttons, 'fontsizeselect' );

			if ( version_compare( $wp_version, '4.6.9', '<=' ) ) {
				array_unshift( $buttons, 'formatselect' );
			}

			if ( ( $key = array_search( 'wp_help', $buttons ) ) !== false ) { // @codingStandardsIgnoreLine
				unset( $buttons[ $key ] );
			}
		}

		return $buttons;
	}

	/**
	 * Custom font size options for the editor font size select.
	 *

	 * @param array $init The TinyMCE init array.
	 * @return array
	 */
	static public function editor_font_sizes( $init ) {
		if ( BACheetahModel::is_builder_active() ) {
			$init['fontsize_formats'] = implode( ' ', array(
				'10px',
				'12px',
				'14px',
				'16px',
				'18px',
				'20px',
				'22px',
				'24px',
				'26px',
				'28px',
				'30px',
				'32px',
				'34px',
				'36px',
				'38px',
				'40px',
				'42px',
				'44px',
				'46px',
				'48px',
			));
		}

		return $init;
	}

	/**
	 * Only allows certain text editor plugins to avoid conflicts
	 * with third party plugins.
	 *

	 * @param array $plugins The current editor plugins.
	 * @return array
	 */
	static public function editor_external_plugins( $plugins ) {
		if ( BACheetahModel::is_builder_active() ) {

			$allowed = array(
				'anchor',
				'code',
				'insertdatetime',
				'nonbreaking',
				'print',
				'searchreplace',
				'table',
				'visualblocks',
				'visualchars',
				'emoticons',
				'advlist',
				'wptadv',
			);

			foreach ( $plugins as $key => $val ) {
				if ( ! in_array( $key, $allowed ) ) {
					unset( $plugins[ $key ] );
				}
			}
		}

		return $plugins;
	}

	/**
	 * Register the styles and scripts for builder layouts.
	 *

	 * @return void
	 */
	static public function register_layout_styles_scripts() {
		$ver     = BA_CHEETAH_VERSION;
		$css_url = plugins_url( '/css/', BA_CHEETAH_FILE );
		$js_url  = plugins_url( '/js/', BA_CHEETAH_FILE );
		$font_url = plugins_url( '/fonts/', BA_CHEETAH_FILE );
		$min     = ( self::is_debug() ) ? '' : '.min';

		// Register additional CSS
		wp_register_style( 'ba-cheetah-slideshow', $css_url . 'ba-cheetah-slideshow' . $min . '.css', array( 'yui3' ), $ver );
		wp_register_style( 'yui3', $css_url . 'yui3.css', array(), $ver );
		wp_register_style( 'jquery-fancybox', $css_url . 'jquery.fancybox.min.css', array(), $ver );
		wp_register_style( 'jquery-fancybox-custom', $css_url . 'jquery.fancybox.custom.css', array(), $ver );
        wp_register_style( 'jquery-mosaic', $css_url . 'jquery.mosaic.min.css', array(), $ver );
        wp_register_style( 'splide', $css_url . 'splide.min.css', array(), $ver );
        wp_register_style( 'splide-theme', $css_url . 'splide-theme.min.css', array('splide'), $ver );
        wp_register_style( 'keen-slider', $css_url . 'keen-slider.min.css', array(), $ver );
        wp_register_style( 'swiper', $css_url . 'swiper-bundle.min.css', array(), $ver );

		// Register icon CDN CSS
		wp_register_style( 'font-awesome-5', self::get_fa5_url(), array(), $ver );
		wp_register_style( 'font-muli', $font_url . 'muli/muli.css', array(), $ver );
		wp_register_style( 'font-awesome', plugins_url( '/fonts/fontawesome/' . self::get_fa5_version() . '/css/v4-shims.min.css', BA_CHEETAH_FILE ), array( 'font-awesome-5' ), $ver );
		wp_register_style( 'foundation-icons', 'https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css', array(), $ver );

		// Register additional JS
		wp_register_script( 'ba-cheetah-slideshow', $js_url . 'ba-cheetah-slideshow' . $min . '.js', array( 'yui3' ), $ver, true );
		wp_register_script( 'ba-cheetah-gallery-grid', $js_url . 'ba-cheetah-gallery-grid.js', array( 'jquery' ), $ver, true );
		wp_register_script( 'jquery-easing', $js_url . 'jquery.easing.min.js', array( 'jquery' ), '1.4', true );
		wp_register_script( 'jquery-fitvids', $js_url . 'jquery.fitvids.min.js', array( 'jquery' ), '1.2', true );
		wp_register_script( 'jquery-infinitescroll', $js_url . 'jquery.infinitescroll.min.js', array( 'jquery' ), $ver, true );
		wp_register_script( 'jquery-mosaicflow', $js_url . 'jquery.mosaicflow.min.js', array( 'jquery' ), $ver, true );
		wp_register_script( 'jquery-waypoints', $js_url . 'jquery.waypoints.min.js', array( 'jquery' ), $ver, true );
		wp_register_script( 'jquery-wookmark', $js_url . 'jquery.wookmark.min.js', array( 'jquery' ), $ver, true );
		wp_register_script( 'jquery-throttle', $js_url . 'jquery.ba-throttle-debounce.min.js', array( 'jquery' ), $ver, true );
		wp_register_script( 'jquery-ui-countdown', $js_url . 'jquery.ui.countdown.min.js', array( 'jquery' ), $ver, true );
		wp_register_script( 'sweetalert2', $js_url . 'sweetalert2.all.min.js', array(), $ver, true );
		wp_register_script( 'splide', $js_url . 'splide.min.js', array(), $ver, true );
		wp_register_script( 'js-cookie', $js_url . 'js.cookie.min.js', array(), $ver, true );
		wp_register_script( 'js-increments', $js_url . 'js.increments.min.js', array(), $ver, true );
		wp_register_script( 'yui3', $js_url . 'yui3.min.js', array(), $ver, true );
		wp_register_script( 'keen-slider', $js_url . 'keen-slider.js', array(), $ver, true );
		wp_register_script( 'swiper', $js_url . 'swiper-bundle.min.js', array(), $ver, true );
		wp_register_script( 'youtube-player', 'https://www.youtube.com/iframe_api', array(), $ver, true );
		wp_register_script( 'vimeo-player', 'https://player.vimeo.com/api/player.js', array(), $ver, true );
		wp_deregister_script( 'imagesloaded' );
		wp_register_script( 'imagesloaded', includes_url( 'js/imagesloaded.min.js' ), array( 'jquery' ) );

        wp_register_script( 'jquery-fancybox', $js_url . 'jquery.fancybox.min.js', array( 'jquery' ), $ver, true );
        wp_register_script( 'jquery-mosaic', $js_url . 'jquery.mosaic.min.js', array( 'jquery' ), $ver, true );
	}

	/**
	 * Enqueue the styles and scripts for all builder layouts
	 * in the main WordPress query.
	 *

	 * @return void
	 */
	static public function enqueue_all_layouts_styles_scripts() {
		global $wp_query;
		global $post;

		$original_post = $post;
		$is_archive    = is_archive() || is_home() || is_search();

		// Enqueue assets for posts in the main query.
		if ( ! $is_archive && isset( $wp_query->posts ) ) {
			foreach ( $wp_query->posts as $post ) {
				self::enqueue_layout_styles_scripts();
			}
		}

		// Enqueue assets for posts via the ba_cheetah_global_posts filter.
		$post_ids = BACheetahModel::get_global_posts();

		if ( count( $post_ids ) > 0 ) {

			$posts = get_posts(array(
				'post__in'       => $post_ids,
				'post_type'      => get_post_types(),
				'posts_per_page' => -1,
			));

			foreach ( $posts as $post ) {
				self::enqueue_layout_styles_scripts();
			}
		}

		// Reset the global post variable.
		$post = $original_post;
	}

	/**
	 * Enqueue the styles and scripts for a single layout.
	 *

	 * @param bool $rerender Whether to rerender the CSS and JS.
	 * @return void
	 */
	static public function enqueue_layout_styles_scripts( $rerender = false ) {
		if ( BACheetahModel::is_builder_enabled() ) {

			$nodes = BACheetahModel::get_categorized_nodes();

			// Enqueue required row CSS and JS
			foreach ( $nodes['rows'] as $row ) {
				if ( 'slideshow' == $row->settings->bg_type ) {
					wp_enqueue_script( 'yui3' );
					wp_enqueue_script( 'ba-cheetah-slideshow' );
					wp_enqueue_script( 'imagesloaded' );
					wp_enqueue_style( 'ba-cheetah-slideshow' );
				} elseif ( 'video' == $row->settings->bg_type ) {
					wp_enqueue_script( 'imagesloaded' );
					if ( 'video_service' == $row->settings->bg_video_source ) {

						$video_data = BACheetahUtils::get_video_data( do_shortcode( $row->settings->bg_video_service_url ) );

						if ( 'youtube' == $video_data['type'] ) {
							wp_enqueue_script( 'youtube-player' );
						} elseif ( 'vimeo' == $video_data['type'] ) {
							wp_enqueue_script( 'vimeo-player' );
						}
					}
				}
				if ( is_array( $row->settings->animation ) && ! empty( $row->settings->animation['style'] ) ) {
					wp_enqueue_script( 'jquery-waypoints' );
				}
			}

			// Enqueue required column CSS and JS
			foreach ( $nodes['columns'] as $col ) {
				if ( is_array( $col->settings->animation ) && ! empty( $col->settings->animation['style'] ) ) {
					wp_enqueue_script( 'jquery-waypoints' );
				}
			}

			// Enqueue required module CSS and JS
			foreach ( $nodes['modules'] as $module ) {

				if( ($module->slug == 'button') && ($module->settings->click_action == 'popup') ){
					$rerender = true;
				}

                $module->enqueue_icon_styles();
				$module->enqueue_font_styles();
				$module->enqueue_scripts();

				foreach ( $module->css as $handle => $props ) {
					wp_enqueue_style( $handle, $props[0], $props[1], $props[2], $props[3] );
				}
				foreach ( $module->js as $handle => $props ) {
					wp_enqueue_script( $handle, $props[0], $props[1], $props[2], $props[3] );
				}
				if ( is_array( $module->settings->animation ) && ! empty( $module->settings->animation['style'] ) ) {
					wp_enqueue_script( 'jquery-waypoints' );
				}
			}

			// Enqueue Google Fonts
			BACheetahFonts::enqueue_google_fonts();

			// Enqueue popup
			wp_enqueue_script( 'sweetalert2' );

			// Enqueue layout CSS
			self::enqueue_layout_cached_asset( 'css', $rerender );

			// Enqueue layout JS
			self::enqueue_layout_cached_asset( 'js', $rerender );

			/*
			global $wp_scripts;
			global $wp_styles;
			error_log('id = '.BACheetahModel::get_post_id(). ' \n queue js = '.json_encode($wp_scripts->queue). '\n queue css = '.json_encode($wp_styles->queue));
			*/
		}
	}

	/**
	 * Enqueue the styles and scripts for a single layout
	 * using the provided post ID.
	 *

	 * @param int $post_id
	 * @return void
	 */
	static public function enqueue_layout_styles_scripts_by_id( $post_id ) {
		BACheetahModel::set_post_id( $post_id );
		BACheetah::enqueue_layout_styles_scripts();
		BACheetahModel::reset_post_id();
	}

	/**
	 * Enqueues the cached CSS or JS asset for a layout.
	 *

	 * @access private
	 * @param string $type The type of asset. Either CSS or JS.
	 * @param bool $rerender Whether to rerender the CSS or JS.
	 * @return string
	 */
	static private function enqueue_layout_cached_asset( $type = 'css', $rerender = false ) {
		$post_id    = BACheetahModel::get_post_id();
		$asset_info = BACheetahModel::get_asset_info();
		$asset_ver  = BACheetahModel::get_asset_version();
		$active     = BACheetahModel::is_builder_active();
		$preview    = BACheetahModel::is_builder_draft_preview();
		$handle     = 'ba-cheetah-layout-' . $post_id;
		/**
		 * Use this filter to add dependencies to the dependency array when the main builder layout CSS file is enqueued using wp_enqueue_style.
		 * @see ba_cheetah_layout_style_dependencies
		 */
		$css_deps  = apply_filters( 'ba_cheetah_layout_style_dependencies', array() );
		$css_media = apply_filters( 'ba_cheetah_layout_style_media', 'all' );

		// Enqueue with the global code included?
		if ( in_array( 'global-' . $type, self::$enqueued_global_assets ) ) {
			$path   = $asset_info[ $type . '_partial' ];
			$url    = $asset_info[ $type . '_partial_url' ];
			$global = false;
		} else {
			$path                           = $asset_info[ $type ];
			$url                            = $asset_info[ $type . '_url' ];
			$global                         = true;
			self::$enqueued_global_assets[] = 'global-' . $type;
		}

		// Render the asset inline instead of enqueuing the file?
		if ( 'inline' === BACheetahModel::get_asset_enqueue_method() ) {

			// Bail if we've already rendered this.
			if ( in_array( $path, self::$rendered_assets ) ) {
				return;
			} else {
				self::$rendered_assets[] = $path;
			}

			// Enqueue inline.
			if ( 'css' === $type ) {
				wp_register_style( $handle, false, $css_deps, $asset_ver, $css_media );
				wp_enqueue_style( $handle );
				wp_add_inline_style( $handle, self::render_css( $global ) );
			} else {
				self::$inline_js .= self::render_js( $global );
				if ( ! has_action( 'wp_footer', __CLASS__ . '::render_inline_js' ) ) {
					add_action( 'wp_footer', __CLASS__ . '::render_inline_js', PHP_INT_MAX );
				}
			}
		} else {

			// Render if the file doesn't exist.
			if ( ! in_array( $path, self::$rendered_assets ) /*&& ( ! ba_cheetah_filesystem()->file_exists( $path ) || $rerender || $preview || self::is_debug() )*/ ) {
				call_user_func_array( array( 'BACheetah', 'render_' . $type ), array( $global ) );
				self::$rendered_assets[] = $path;
			}

			// Don't enqueue if we don't have a file after trying to render.
			if ( ! ba_cheetah_filesystem()->file_exists( $path ) || 0 === ba_cheetah_filesystem()->filesize( $path ) ) {
				return;
			}

			if ( $global ) {
				$asset_ver = BACheetahModel::get_asset_version( $path );
			}

			// Enqueue.
			if ( 'css' == $type ) {
				wp_enqueue_style( $handle, $url, $css_deps, $asset_ver, $css_media );
			} elseif ( 'js' == $type ) {
				wp_enqueue_script( $handle, $url, array( 'jquery' ), $asset_ver, true );
			}
		}
	}

	/**
	 *
	 *

	 * @return void
	 */
	static public function render_inline_js() {
		echo '<script>' . esc_js(self::$inline_js) . '</script>';
	}

	/**
	 * Clears the enqueued global assets cache to ensure new asset
	 * renders include global node assets.
	 *

	 * @return void
	 */
	static public function clear_enqueued_global_assets() {
		self::$enqueued_global_assets = array();
	}

	/**
	 * Register common JS vendors
	 * This is primarily for consistent sharing with Assistant.
	 *
	 * @return void
	 */
	static public function register_shared_vendors() {
		global $wp_version;

		$ver        = BA_CHEETAH_VERSION;
		$css_build  = plugins_url( '/css/build/', BA_CHEETAH_FILE );
		$js_vendors = plugins_url( '/js/vendors/', BA_CHEETAH_FILE );
		$js_build   = plugins_url( '/js/build/', BA_CHEETAH_FILE );
		$tag        = '.bundle'; //'.bundle.min';
		// @bacheetahbuilder/app-core
		$app_core_deps = array( 'react', 'redux', 'react-router-dom', 'wp-i18n' );
		// @bacheetahbuilder/fluid
		$fluid_deps = array(
			'react',
			'react-dom',
			'redux',
			'react-router-dom',
			'framer-motion',
			'react-laag',
			'wp-i18n',
			'bb-icons',
		);

		// React polyfill for older versions of WordPress.
		if ( version_compare( $wp_version, '5.2', '<=' ) ) {

			// React
			wp_deregister_script( 'react' );
			wp_enqueue_script( 'react', "{$js_vendors}react.min.js", array(), $ver, true );

			// React-DOM
			wp_deregister_script( 'react-dom' );
			wp_enqueue_script( 'react-dom', "{$js_vendors}react-dom.min.js", array(), $ver, true );

			// @bacheetahbuilder/app-core
			$app_core_deps = array( 'react', 'redux', 'react-router-dom' );
			// @bacheetahbuilder/fluid
			$fluid_deps = array(
				'react',
				'react-dom',
				'redux',
				'react-router-dom',
				'framer-motion',
				'react-laag',
				'bb-icons',
			);
			if ( ! wp_script_is( 'wp-i18n', 'registered' ) ) {
				wp_enqueue_script( 'ba-cheetah-wp-i18n', "{$js_vendors}i18n-polyfill.js" );
			}
		}

		/**
		 * Shared Vendors
		 * These vendor bundles are special in that they attach a global reference to themselves on the vendors object.
		 */

		// redux
		wp_register_script( 'redux', "{$js_vendors}redux.min.js", array(), $ver, false );

		// react-router-dom
		wp_register_script( 'react-router-dom', "{$js_vendors}react-router-dom.min.js", array( 'react' ), $ver, false );

		// framer-motion
		wp_register_script( 'framer-motion', "{$js_build}vendor-framer-motion{$tag}.js", array( 'react', 'react-dom' ), $ver, false );

		// react-laag
		wp_register_script( 'react-laag', "{$js_build}vendor-react-laag{$tag}.js", array( 'react' ), $ver, false );

		wp_register_script( 'bb-app-core', "{$js_build}vendor-bb-app-core{$tag}.js", $app_core_deps, $ver, false );

		wp_register_script( 'bb-icons', "{$js_build}vendor-bb-icons{$tag}.js", array( 'react' ), $ver, false );

		wp_register_script( 'bb-fluid', "{$js_build}vendor-bb-fluid{$tag}.js", $fluid_deps, $ver, false );
		wp_register_style( 'bb-fluid', "{$css_build}vendor-bb-fluid{$tag}.css", array(), $ver, null );
	}

	/**
	 * Register and enqueue the styles and scripts for the builder UI.
	 *

	 * @return void
	 */
	static public function enqueue_ui_styles_scripts() {
		if ( BACheetahModel::is_builder_active() ) {
			
			global $wp_the_query;
			global $wp_version;

			// Remove wp admin bar top margin
			remove_action( 'wp_head', '_admin_bar_bump_cb' );

			$ver     = BA_CHEETAH_VERSION;
			$css_url = plugins_url( '/css/', BA_CHEETAH_FILE );
			$js_url  = plugins_url( '/js/', BA_CHEETAH_FILE );

			// Register React and other vendor bundles
			self::register_shared_vendors();

			/* Frontend builder styles */
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'font-awesome-5' );
			wp_enqueue_style( 'font-muli' );
			wp_enqueue_style( 'foundation-icons' );
			wp_enqueue_style( 'jquery-nanoscroller', $css_url . 'jquery.nanoscroller.css', array(), $ver );
			wp_enqueue_style( 'jquery-autosuggest', $css_url . 'jquery.autoSuggest.min.css', array(), $ver );
			wp_enqueue_style( 'ba-cheetah-jquery-tiptip', $css_url . 'jquery.tiptip.css', array(), $ver );
			wp_enqueue_style( 'bootstrap-tour', $css_url . 'bootstrap-tour-standalone.min.css', array(), $ver );
			if ( true === apply_filters( 'ba_cheetah_select2_enabled', true ) ) {
				wp_enqueue_style( 'select2', $css_url . 'select2.min.css', array(), $ver );
			}

			// Enqueue individual builder styles if WP_DEBUG is on.
			if ( self::is_debug()) {
				wp_enqueue_style( 'ba-cheetah-color-picker', $css_url . 'ba-cheetah-color-picker.css', array(), $ver );
				wp_enqueue_style( 'ba-cheetah-lightbox', $css_url . 'ba-cheetah-lightbox.css', array(), $ver );
				wp_enqueue_style( 'ba-cheetah-icon-selector', $css_url . 'ba-cheetah-icon-selector.css', array(), $ver );
				wp_enqueue_style( 'ba-cheetah', $css_url . 'ba-cheetah.css', array(), $ver );
				wp_enqueue_style( 'ba-cheetah-editor', $css_url . 'ba-cheetah-editor.css', array( 'bb-fluid' ), $ver );

			} else {
				wp_enqueue_style( 'ba-cheetah-editor-min', $css_url . 'build/ba-cheetah-editor.min.css', $ver );
			}

			/* Custom Icons */
			BACheetahIcons::enqueue_all_custom_icons_styles();

			/* RTL Support */
			if ( is_rtl() ) {
				wp_enqueue_style( 'ba-cheetah-rtl', $css_url . 'ba-cheetah-editor-rtl.css', array(), $ver );
			}

			/* We have custom versions of these that fixes bugs. */
			wp_deregister_script( 'jquery-ui-sortable' );
			wp_dequeue_script( 'jquery-touch-punch' );
			wp_deregister_script( 'jquery-touch-punch' );
			wp_register_script( 'jquery-touch-punch', $js_url . 'jquery.touch-punch.min.js', array(), $ver );

			/* Frontend builder scripts */
			wp_enqueue_media();
			wp_enqueue_script( 'heartbeat' );
			wp_enqueue_script( 'wpdialogs' );
			wp_enqueue_script( 'wpdialogs-popup' );
			wp_enqueue_script( 'wplink' );
			wp_enqueue_script( 'editor' );
			wp_enqueue_script( 'quicktags' );
			wp_enqueue_script( 'json2' );
			wp_enqueue_script( 'jquery-ui-droppable' );
			wp_enqueue_script( 'jquery-ui-draggable' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'jquery-ui-widget' );
			wp_enqueue_script( 'jquery-ui-position' );
			wp_enqueue_script( 'jquery-touch-punch' );

			/**
			 * Before jquery.ui.sortable.js is enqueued.
			 * @see ba_cheetah_before_sortable_enqueue
			 */
			do_action( 'ba_cheetah_before_sortable_enqueue' );

			wp_enqueue_script( 'jquery-ui-sortable', $js_url . 'jquery.ui.sortable.js', array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse' ), $ver );
			wp_enqueue_script( 'jquery-nanoscroller', $js_url . 'jquery.nanoscroller.min.js', array(), $ver );
			wp_enqueue_script( 'jquery-autosuggest', $js_url . 'jquery.autoSuggest.min.js', array(), $ver );
			wp_enqueue_script( 'ba-cheetah-jquery-tiptip', $js_url . 'jquery.tiptip.min.js', array(), $ver );
			wp_enqueue_script( 'jquery-showhideevents', $js_url . 'jquery.showhideevents.js', array(), $ver );
			wp_enqueue_script( 'jquery-simulate', $js_url . 'jquery.simulate.js', array(), $ver );
			wp_enqueue_script( 'jquery-validate', $js_url . 'jquery.validate.min.js', array(), $ver );
			wp_enqueue_script( 'bootstrap-tour', $js_url . 'bootstrap-tour-standalone.min.js', array(), $ver );
			wp_enqueue_script( 'ace', $js_url . 'ace/ace.js', array(), $ver );
			wp_enqueue_script( 'ace-language-tools', $js_url . 'ace/ext-language_tools.js', array(), $ver );
			wp_enqueue_script( 'mousetrap', $js_url . 'mousetrap-custom.js', array(), $ver );
			if ( true === apply_filters( 'ba_cheetah_select2_enabled', true ) ) {
				wp_enqueue_script( 'select2', $js_url . 'select2.min.js', array(), $ver );
			}

			// Enqueue individual builder scripts if WP_DEBUG is on.
			$bundle_deps = array( 'react', 'react-dom', 'bb-app-core', 'bb-fluid' );
			if ( self::is_debug() ) {

				wp_enqueue_script( 'ba-cheetah-color-picker', $js_url . 'ba-cheetah-color-picker.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-lightbox', $js_url . 'ba-cheetah-lightbox.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-icon-selector', $js_url . 'ba-cheetah-icon-selector.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-stylesheet', $js_url . 'ba-cheetah-stylesheet.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah', $js_url . 'ba-cheetah.js', array( 'jquery' ), $ver );
				wp_enqueue_script( 'ba-cheetah-libs', $js_url . 'ba-cheetah-libs.js', array( 'ba-cheetah' ), $ver );
				wp_enqueue_script( 'ba-cheetah-ajax-layout', $js_url . 'ba-cheetah-ajax-layout.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-preview', $js_url . 'ba-cheetah-preview.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-simulate-media-query', $js_url . 'ba-cheetah-simulate-media-query.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-responsive-editing', $js_url . 'ba-cheetah-responsive-editing.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-responsive-preview', $js_url . 'ba-cheetah-responsive-preview.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-tour', $js_url . 'ba-cheetah-tour.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-ui', $js_url . 'ba-cheetah-ui.js', array( 'ba-cheetah', 'mousetrap' ), $ver );
				wp_enqueue_script( 'ba-cheetah-ui-main-menu', $js_url . 'ba-cheetah-ui-main-menu.js', array( 'ba-cheetah-ui' ), $ver );
				wp_enqueue_script( 'ba-cheetah-ui-panel-content', $js_url . 'ba-cheetah-ui-panel-content-library.js', array( 'ba-cheetah-ui' ), $ver );
				wp_enqueue_script( 'ba-cheetah-ui-settings-forms', $js_url . 'ba-cheetah-ui-settings-forms.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-ui-settings-copy-paste', $js_url . 'ba-cheetah-ui-settings-copy-paste.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-ui-pinned', $js_url . 'ba-cheetah-ui-pinned.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-revisions', $js_url . 'ba-cheetah-revisions.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-search', $js_url . 'ba-cheetah-search.js', array( 'jquery' ), $ver );
				wp_enqueue_script( 'ba-cheetah-save-manager', $js_url . 'ba-cheetah-save-manager.js', array( 'jquery' ), $ver );
				wp_enqueue_script( 'ba-cheetah-history-manager', $js_url . 'ba-cheetah-history-manager.js', array(), $ver );
				wp_enqueue_script( 'ba-cheetah-system', $js_url . 'build/builder.bundle.js', $bundle_deps, $ver, true );
			} else {

				wp_enqueue_script( 'ba-cheetah-min', $js_url . 'build/ba-cheetah.min.js', array( 'jquery', 'mousetrap' ), $ver );
				wp_enqueue_script( 'ba-cheetah-system', $js_url . 'build/builder.bundle.min.js', $bundle_deps, $ver, true );
			}

			/* Additional module styles and scripts */
			foreach ( BACheetahModel::$modules as $module ) {

				$module->enqueue_scripts();

				foreach ( $module->css as $handle => $props ) {
					wp_enqueue_style( $handle, $props[0], $props[1], $props[2], $props[3] );
				}
				foreach ( $module->js as $handle => $props ) {
					wp_enqueue_script( $handle, $props[0], $props[1], $props[2], $props[3] );
				}
			}
		}
		wp_add_inline_style( 'admin-bar', '
			#wp-admin-bar-ba-cheetah-frontend-edit-link:hover .ab-icon:before{
				filter: unset;
			}		
			#wp-admin-bar-ba-cheetah-frontend-edit-link .ab-icon:before { 
				content: close-quote;
				width: 25px;
				height: 20px;
				background-image: url('. BA_CHEETAH_URL . 'img/branding/menu-icon.svg?cache=1);
				position: absolute;
				top: 6px;
				filter: grayscale(0.9) opacity(0.9);
				background-size: 24px;
				background-repeat: no-repeat;
				background-position: center;
			}'
		);
		$args = array(
			'product'     => BACheetahModel::get_branding(),
			'white_label' => BACheetahModel::is_white_labeled(),
			/**
			 * Custom info text for crash popup.
			 * @see ba_cheetah_crash_white_label_text
			 */
			'labeled_txt' => apply_filters( 'ba_cheetah_crash_white_label_text', '' ),
			'vars'        => array(
				'PHP Version'    => phpversion(),
				'Memory Limit'   => BA_CHEETAH_Debug::safe_ini_get( 'memory_limit' ),
				'max_input_vars' => BA_CHEETAH_Debug::safe_ini_get( 'max_input_vars' ),
				'modsecfix'      => ( defined( 'BA_CHEETAH_MODSEC_FIX' ) && BA_CHEETAH_MODSEC_FIX ) ? 'Enabled' : 'Disabled',
			),
		);
		wp_localize_script( 'ba-cheetah-min', 'crash_vars', $args );
		wp_localize_script( 'ba-cheetah', 'crash_vars', $args );
	}

	/**
	 * Adds builder classes to the body class.
	 *

	 * @param array $classes An array of existing classes.
	 * @return array
	 */
	static public function body_class( $classes ) {
		$do_render     = apply_filters( 'ba_cheetah_do_render_content', true, BACheetahModel::get_post_id() );
		$simple_ui     = ! BACheetahUserAccess::current_user_can( 'unrestricted_editing' );
		$template_type = BACheetahModel::get_user_template_type();

		if ( $do_render && BACheetahModel::is_builder_enabled() && ! is_archive() ) {
			$classes[] = 'ba-cheetah';
		}
		if ( BACheetahModel::is_builder_active() ) {
			$classes[] = 'ba-cheetah-edit';

			// Lite version
			if(BA_CHEETAH_BUILDERALL && (BA_CHEETAH_AUTENTICATED === true)) {
				$classes[] = 'ba-cheetah-autenticated';
			}

			// Simple UI
			if ( $simple_ui ) {
				$classes[] = 'ba-cheetah-simple';
			}

			// Simple pinned UI
			if ( $simple_ui || 'module' === $template_type ) {
				$classes[] = 'ba-cheetah-simple-pinned';
			}

			// Skin
			$user_settings = BACheetahUserSettings::get();
			$classes[]     = 'ba-cheetah-ui-skin--' . $user_settings['skin'];

			// Draft changes
			if ( BACheetahModel::layout_has_drafted_changes() ) {
				$classes[] = 'ba-cheetah--layout-has-drafted-changes';
			}

			// RTL
			if ( is_rtl() ) {
				$classes[] = 'ba-cheetah-direction-rtl';
			} else {
				$classes[] = 'ba-cheetah-direction-ltr';
			}

			// Has notifications
			// $has_new_notifications = BACheetahNotifications::get_notifications();
			// if ( ! $has_new_notifications['read'] ) {
			// 	$classes[] = 'ba-cheetah-has-new-notifications';
			// }
			$has_new_notifications = 0;

		}

		return $classes;
	}

	/**
	 * Adds the page builder button to the WordPress admin bar.
	 *

	 * @param object $wp_admin_bar An instance of the WordPress admin bar.
	 * @return void
	 */
	static public function admin_bar_menu( $wp_admin_bar ) {
		global $wp_the_query;

		if ( BACheetahModel::is_post_editable() && is_object( $wp_the_query->post ) ) {

			$enabled = get_post_meta( $wp_the_query->post->ID, '_ba_cheetah_enabled', true );
			$dot     = ' <span class="ba-cheetah-admin-bar-status-dot" style="color:' . ( $enabled ? '#6bc373' : '#d9d9d9' ) . '; font-size:18px; line-height:1;">&bull;</span>';

			$wp_admin_bar->add_node( array(
				'id'    => 'ba-cheetah-frontend-edit-link',
				'title' => '<span class="ab-icon" style="width: 25px"></span>' . BACheetahModel::get_branding() . $dot,
				'href'  => BACheetahModel::get_edit_url( $wp_the_query->post->ID ),
			));
		}
	}

	static public function locate_template_file( $template_base, $slug ) {
		$specific_template = $template_base . '-' . $slug . '.php';
		$general_template  = $template_base . '.php';
		$default_dir       = trailingslashit( BA_CHEETAH_DIR ) . 'includes/';

		// Try to find the specific template, then repeat the same process for general.

		$locate_template_order = apply_filters( 'ba_cheetah_locate_template_order', array(
			trailingslashit( self::$template_dir ) . $specific_template,
			trailingslashit( self::$template_dir ) . $general_template,
		), self::$template_dir, $template_base, $slug );

		$template_path = locate_template( $locate_template_order );

		if ( ! $template_path ) {
			if ( file_exists( $default_dir . $specific_template ) ) {
				$template_path = $default_dir . $specific_template;
			} elseif ( file_exists( $default_dir . $general_template ) ) {
				$template_path = $default_dir . $general_template;
			}
		}

		return apply_filters( 'ba_cheetah_template_path', $template_path, $template_base, $slug );
	}

	/**
	 * Initializes the builder interface.
	 *


	 * @return void
	 */
	static public function init_ui() {
		// Enable editing if the builder is active.
		if ( BACheetahModel::is_builder_active() && ! BACheetahAJAX::doing_ajax() ) {

			/**
			 * Fire an action as the builder inits.
			 * @see ba_cheetah_init_ui
			 */
			do_action( 'ba_cheetah_init_ui' );

			// Remove 3rd party editor buttons.
			remove_all_actions( 'media_buttons', 999999 );
			remove_all_actions( 'media_buttons_context', 999999 );

			// Increase available memory.
			if ( function_exists( 'wp_raise_memory_limit' ) ) {
				wp_raise_memory_limit( 'wp-cheetah' );
			}

			// Get the post.
			require_once ABSPATH . 'wp-admin/includes/post.php';
			$post_id = BACheetahModel::get_post_id();

			// Check to see if the post is locked.
			if ( wp_check_post_lock( $post_id ) !== false ) {
				header( 'Location: ' . admin_url( '/post.php?post=' . $post_id . '&action=edit' ) );
			}
			else if (BACheetahModel::is_builder_in_debug_tree_mode()) {
				include_once BA_CHEETAH_DIR . '/classes/class-ba-cheetah-debug-layout.php';
				new BACheetahDebugLayout($post_id, 'draft');
				die();
			}
			else {
				BACheetahModel::enable_editing();
			}
		}
	}

	/**
	 * Opens the tag for an element that is intended to be the site's content container
	 * (not including the editor's DOM elements).
	 * This class is necessary because it has a transform that creates a fictitious
	 * container that is respected by the position of a module while it is being moved.
	 */

	static public function open_content_wrapper() {
		if ( BACheetahModel::is_builder_active() && function_exists('wp_body_open') && function_exists('wp_footer') ) {
			echo '<div class="ba-cheetah-ui-pinned-content-transform">';
		}
	}

	/**
	 * Closes the  open_content_wrapper
	 */

	static public function close_content_wrapper() {
		if ( BACheetahModel::is_builder_active() && function_exists('wp_body_open') && function_exists('wp_footer')) {
			echo '</div>';
		}
	}

	/**
	 * Renders the markup for the builder interface.
	 *

	 * @return void
	 */
	static public function render_ui() {
		global $wp_the_query;

		if ( BACheetahModel::is_builder_active() ) {

			$post_id         = is_object( $wp_the_query->post ) ? $wp_the_query->post->ID : null;
			$unrestricted    = BACheetahUserAccess::current_user_can( 'unrestricted_editing' );
			$simple_ui       = ! $unrestricted;
			$global_settings = BACheetahModel::get_global_settings();

			include BA_CHEETAH_DIR . 'includes/ui-extras.php';
			include BA_CHEETAH_DIR . 'includes/ui-js-templates.php';
			include BA_CHEETAH_DIR . 'includes/ui-js-config.php';
			include BA_CHEETAH_DIR . 'includes/ui-svg-sprites.php';
		}
	}

	/**
	 * Get data structure for main builder menu.
	 *

	 * @return array
	 */
	static function get_main_menu_data() {
		global $post;

		$views             = array();
		$is_user_template  = BACheetahModel::is_post_user_template();
		$enabled_templates = BACheetahModel::get_enabled_templates();
		$is_simple_ui      = ! BACheetahUserAccess::current_user_can( 'unrestricted_editing' );
		$key_shortcuts     = self::get_keyboard_shortcuts();
		$help              = BACheetahModel::get_help_button_settings();
		$default_view      = array(
			'name'       => __( 'Unnamed Menu', 'ba-cheetah' ),
			'isShowing'  => false,
			'isRootView' => false,
			'items'      => array(),
		);

		// Tools
		$tools_view = array(
		'name'       => __( 'Settings', 'ba-cheetah' ) . '<a title="Wordpress admin" href="'.admin_url( 'index.php' ).'" target="_blank"><svg width="25.074" height="25.074" fill="#A5B9D5"><use xlink:href="#ba-cheetah-icon--wordpress"></use></svg></a>',
			'isShowing'  => true,
			'isRootView' => true,
			'items'      => array(),
		);

		$tools_view['items'][01] = array(
			'type' => 'separator',
		);

		if (!BACheetahUserTemplatesLayout::is_layout_content_type(get_post_type(get_the_ID()))) {
			$tools_view['items'][04] = array(
				'label'     => __('Page Settings', 'ba-cheetah'),
				'icon' 		=> 'ba-cheetah-icon--wordpress',
				'type'      => 'event',
				'eventName' => 'showPageSettings'
			);
		}

		$tools_view['items'][05] = array(
			'label'     => __( 'Global Settings', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--globe',
			'type'      => 'event',
			'eventName' => 'showGlobalSettings',
			'accessory' => $key_shortcuts['showGlobalSettings']['keyLabel'],
		);

		$tools_view['items'][07] = array(
			'type' => 'separator',
		);

		$tools_view['items'][10] = array(
			'label'     => __( 'Publish Layout', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--publish',
			'type'      => 'event',
			'eventName' => 'publishAndRemain',
			'accessory' => $key_shortcuts['publishAndRemain']['keyLabel'],
		);

		if ( ! $is_user_template && ( 'enabled' == $enabled_templates || 'user' == $enabled_templates ) ) {
			$tools_view['items'][15] = array(
				'label'     => __( 'Save Template', 'ba-cheetah' ),
				'icon' 		=> 'ba-cheetah-icon--save',
				'type'      => 'event',
				'eventName' => 'saveTemplate',
				'accessory' => isset( $key_shortcuts['saveTemplate'] ) ? $key_shortcuts['saveTemplate']['keyLabel'] : null,
			);
		}

		$tools_view['items'][20] = array(
			'label'     => __( 'Duplicate Layout', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--duplicate-page',
			'type'      => 'event',
			'eventName' => 'duplicateLayout',
		);

		/*
		Removed because button is in navbar
		$tools_view['items'][30] = array(
			'label'     => __( 'Preview Layout', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--wordpress',
			'type'      => 'event',
			'eventName' => 'previewLayout',
			'accessory' => $key_shortcuts['previewLayout']['keyLabel'],
		);

		Removed because device buttons are in navbar
		$tools_view['items'][31] = array(
			'label'     => __( 'Responsive Editing', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--responsive-editing',
			'type'      => 'event',
			'eventName' => 'responsiveEditing',
			'accessory' => $key_shortcuts['responsiveEditing']['keyLabel'],
		);
		*/

		$tools_view['items'][70] = array(
			'type' => 'separator',
		);

		$tools_view['items'][99] = array(
			'label' => __( 'Builderall Builder Admin', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--wordpress',
			'type'      => 'link',
			'url' 		=> admin_url( 'admin.php?page=ba-cheetah-settings' ),
		);

		$tools_view['items'][110] = array(
			'label' => __( 'Tour', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--tour-flag',
			'type'      => 'event',
			'eventName' => 'startTour'
		);

		/*
		$tools_view['items'][120] = array(
			'label'     => __( 'Keyboard Shortcuts', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--wordpress',
			'type'      => 'event',
			'eventName' => 'showKeyboardShortcuts',
		);
		*/

		if(BA_CHEETAH_BUILDERALL && (BA_CHEETAH_AUTENTICATED !== true)) {
			$tools_view['items'][121] = array(
				'label'     => __( 'Backoffice', 'ba-cheetah' ),
				'icon' 		=> 'ba-cheetah-icon--builderall',
				'type'      => 'link',
				'url' 		=> BA_CHEETAH_OFFICE_URL,
			);
		}

		$views['main'] = wp_parse_args( $tools_view, $default_view );

		/*
		// Admin
		$admin_view = array(
			'name'  => __( 'WordPress Admin', 'ba-cheetah' ),
			'items' => array(),
		);

		// Edit current post/page/cpt
		if ( is_single( $post->ID ) || is_page( $post->ID ) ) {
			$edit_label              = get_post_type_object( $post->post_type )->labels->edit_item;
			$admin_view['items'][10] = array(
				'label' => $edit_label,
				'icon' 		=> 'ba-cheetah-icon--wordpress',
				'type'  => 'link',
				'url'   => get_edit_post_link( $post->ID ),
			);
		}

		$admin_view['items'][15] = array(
			'type' => 'separator',
		);

		// Dashboard
		$admin_view['items'][17] = array(
			'label' => _x( 'Dashboard', 'label for the WordPress Dashboard link', 'ba-cheetah' ),
			'icon' 		=> 'ba-cheetah-icon--wordpress',
			'type'  => 'link',
			'url'   => admin_url( 'index.php' ),
		);

		$templates_enabled = BACheetahUserAccess::current_user_can( 'builder_admin' );

		if ( $templates_enabled ) {
			$admin_view['items'][20] = array(
				'label' => __( 'Manage Templates', 'ba-cheetah' ),
				'icon' 		=> 'ba-cheetah-icon--wordpress',
				'type'  => 'link',
				'url'   => admin_url( 'edit.php?post_type=ba-cheetah-template' ),
			);
		}

		if ( current_user_can( 'customize' ) ) {
			$post_url = get_permalink( $post->ID );
			if ( $post_url ) {
				$url = admin_url( 'customize.php?url=' . $post_url );
			} else {
				$url = admin_url( 'customize.php' );
			}
			$admin_view['items'][30] = array(
				'label' => __( 'Customize Theme', 'ba-cheetah' ),
				'icon' 		=> 'ba-cheetah-icon--wordpress',
				'type'  => 'link',
				'url'   => $url,
			);
		}

		$views['admin'] = wp_parse_args( $admin_view, $default_view );

		// Help
		if ( $help['enabled'] && ! $is_simple_ui ) {
			$help_view = array(
				'name'  => __( 'Help', 'ba-cheetah' ),
				'items' => array(),
			);

			if ( $help['video'] && isset( $help['video_embed'] ) ) {
				// Disable Auto Play
				$help['video_embed'] = str_replace( 'autoplay=1', 'autoplay=0', $help['video_embed'] );

				// Remove Height from iframe
				$help['video_embed'] = str_replace( 'height="315"', 'height="173"', $help['video_embed'] );

				$help_view['items'][10] = array(
					'type'  => 'video',
					'embed' => $help['video_embed'],
				);
			}

			if ( $help['tour'] ) {
				$help_view['items'][20] = array(
					'label'     => __( 'Take A Tour', 'ba-cheetah' ),
					'icon'	=> 'ba-cheetah-icon--wordpress',
					'type'      => 'event',
					'eventName' => 'beginTour',
				);
			}

			if ( $help['knowledge_base'] && isset( $help['knowledge_base_url'] ) ) {
				$help_view['items'][30] = array(
					'label' => __( 'View Knowledge Base', 'ba-cheetah' ),
					'icon'	=> 'ba-cheetah-icon--wordpress',
					'type'  => 'link',
					'url'   => $help['knowledge_base_url'],
				);
			}

			if ( $help['forums'] && isset( $help['forums_url'] ) ) {
				$help_view['items'][40] = array(
					'label' => __( 'Contact Support', 'ba-cheetah' ),
					'icon'	=> 'ba-cheetah-icon--wordpress',
					'type'  => 'link',
					'url'   => $help['forums_url'],
				);
			}

			$views['help'] = wp_parse_args( $help_view, $default_view );
		}
		*/

		return apply_filters( 'ba_cheetah_main_menu', $views );
	}

	/**
	 * Get array of registered keyboard shortcuts. The key corresponds to
	 * an event to be triggered by BAcheetah.triggerHook()
	 *

	 * @return array
	 */
	static function get_keyboard_shortcuts() {
		$default_action = array(
			'label'    => _x( 'Untitled Shortcut', 'A keyboard shortcut with no label given', 'ba-cheetah' ),
			'keyCode'  => '',
			'keyLabel' => '',
			'isGlobal' => false,
			'enabled'  => true,
		);
		$data           = array(
			'showModules'        => array(
				'label'   => _x( 'Open Elements Tab', 'Keyboard action to show modules tab', 'ba-cheetah' ),
				'keyCode' => 'j',
			),
			'showRows'           => array(
				'label'   => _x( 'Open Rows Tab', 'Keyboard action to show rows tab', 'ba-cheetah' ),
				'keyCode' => 'k',
			),
			'showTemplates'      => array(
				'label'   => _x( 'Open Templates Tab', 'Keyboard action to show templates tab', 'ba-cheetah' ),
				'keyCode' => 'l',
			),
			'showSaved'          => array(
				'label'   => _x( 'Open Saved Tab', 'Keyboard action to show saved tab', 'ba-cheetah' ),
				'keyCode' => ';',
			),
			'saveTemplate'       => array(
				'label'   => _x( 'Save New Template', 'Keyboard action to open save template form', 'ba-cheetah' ),
				'keyCode' => 'mod+j',
			),
			'previewLayout'      => array(
				'label'   => _x( 'Toggle Preview Mode', 'Keyboard action to toggle preview mode', 'ba-cheetah' ),
				'keyCode' => 'p',
			),
			'responsiveEditing'  => array(
				'label'   => _x( 'Toggle Responsive Editing Mode', 'Keyboard action to toggle responsive editing', 'ba-cheetah' ),
				'keyCode' => 'r',
			),
			'showGlobalSettings' => array(
				'label'   => _x( 'Open Global Settings', 'Keyboard action to open the global settings panel', 'ba-cheetah' ),
				'keyCode' => 'mod+u',
			),
			'showLayoutSettings' => array(
				'label'   => _x( 'Open Layout Settings', 'Keyboard action to open the layout settings panel', 'ba-cheetah' ),
				'keyCode' => 'mod+y',
			),
			'showSearch'         => array(
				'label'   => _x( 'Display Element Search', 'Keyboard action to open the element search panel', 'ba-cheetah' ),
				'keyCode' => 'mod+i',
			),
			'showSavedMessage'   => array(
				'label'    => _x( 'Save Layout', 'Keyboard action to save changes', 'ba-cheetah' ),
				'keyCode'  => 'mod+s',
				'isGlobal' => true,
			),
			'publishAndRemain'   => array(
				'label'    => _x( 'Publish changes without leaving builder', 'Keyboard action to publish any pending changes', 'ba-cheetah' ),
				'keyCode'  => 'mod+p',
				'isGlobal' => true,
			),
			'cancelTask'         => array(
				'label'    => _x( 'Dismiss Active Panel', 'Keyboard action to dismiss the current task or panel', 'ba-cheetah' ),
				'keyCode'  => 'esc',
				'isGlobal' => true,
			),
			'undo'               => array(
				'label'   => _x( 'Undo', 'Keyboard action to undo changes', 'ba-cheetah' ),
				'keyCode' => 'mod+z',
			),
			'redo'               => array(
				'label'   => _x( 'Redo', 'Keyboard action to redo changes', 'ba-cheetah' ),
				'keyCode' => 'shift+mod+z',
			),
		);

		$data = apply_filters( 'ba_cheetah_keyboard_shortcuts', $data );

		foreach ( $data as $hook => $args ) {

			// Check for old (alpha) format and normalize
			if ( is_string( $args ) ) {
				$args = array(
					'label'   => ucwords( preg_replace( '/([^A-Z])([A-Z])/', '$1 $2', $hook ) ),
					'keyCode' => $args,
				);
			}

			$args = wp_parse_args( $args, $default_action );

			// Unset this shortcut if it's not enabled.
			if ( ! $args['enabled'] ) {
				unset( $data[ $hook ] );
				continue;
			}

			// Map 'mod' to mac or pc equivalent
			$code = $args['keyCode'];
			$code = str_replace( '+', '', $code );

			if ( false !== strpos( $code, 'mod' ) ) {
				$is_mac = strpos( $_SERVER['HTTP_USER_AGENT'], 'Macintosh' ) ? true : false;

				if ( $is_mac ) {
					$code = str_replace( 'mod', 'command', $code );
				} else {
					$code = str_replace( 'mod', 'Ctrl+', $code );
				}
			}

			// Replace 'command'
			$code = str_replace( 'command', '&#8984;', $code );

			// Replace 'shift'
			$code = str_replace( 'shift', '&#x21E7;', $code );

			// Replace 'delete'
			$code = str_replace( 'delete', '&#x232b;', $code );

			// Replace 'left' arrow
			$code = str_replace( 'left', '&larr;', $code );

			// Replace 'right' arrow
			$code = str_replace( 'right', '&rarr;', $code );

			$args['keyLabel'] = $code;
			$data[ $hook ]    = $args;
		}

		return $data;
	}

	/**
	 * Renders the markup for the title in the builder's bar.
	 *

	 * @return void
	 */
	static public function render_ui_bar_title() {

		global $post;

		$title           = apply_filters( 'ba_cheetah_ui_bar_title', get_the_title( $post->ID ) );
		$icon_url        = BACheetahModel::get_branding_icon();

		$type = BACheetahModel::get_user_template_type( $post->ID );
		if ( $type && 'layout' !== $type ) {
			$edited_object_label = ucfirst( $type );
		} else {
			$edited_object_label = get_post_type_object( $post->post_type )->labels->singular_name;
		}

		/* translators: %s: post label */
		$pretitle = sprintf( _x( 'Currently Editing %s', 'Currently editing message', 'ba-cheetah' ), $edited_object_label );
		$pretitle = apply_filters( 'ba_cheetah_ui_bar_pretitle', $pretitle );

		// Render the bar title.
		include BA_CHEETAH_DIR . 'includes/ui-bar-title-area.php';
	}

	/**
	 * Renders the markup for the buttons in the builder's bar.
	 *

	 * @return void
	 */
	static public function render_ui_bar_buttons() {
		return '';
	}

	/**
	 * Renders layouts using a new instance of WP_Query with the provided
	 * args and enqueues the necessary styles and scripts. We set the global
	 * $wp_query variable so the builder thinks we are in the loop when content
	 * is rendered without having to call query_posts.
	 *
	 * @link https://codex.wordpress.org/Class_Reference/WP_Query See for a complete list of args.
	 *

	 * @param array|string $args An array or string of args to be passed to a new instance of WP_Query.
	 * @param int $site_id The ID of a site on a network to pull the query from.
	 * @return void
	 */
	static public function render_query( $args, $site_id = null ) {
		global $post;
		$switched = false;

		// Pull from a site on the network?
		if ( $site_id && is_multisite() ) {
			switch_to_blog( $site_id );
			$switched = true;
		}

		// Get the query.
		$query = new WP_Query( $args );

		// Loop through the posts.
		foreach ( $query->posts as $query_post ) {

			// Make sure this isn't the same post as the original post to prevent infinite loops.
			if ( is_object( $post ) && $post->ID === $query_post->ID && ! $switched ) {
				continue;
			}

			if ( BACheetahModel::is_builder_enabled( $query_post->ID ) ) {

				// Enqueue styles and scripts for this post.
				self::enqueue_layout_styles_scripts_by_id( $query_post->ID );

				// Print the styles if we are outside of the head tag.
				if ( did_action( 'wp_enqueue_scripts' ) && ! doing_filter( 'wp_enqueue_scripts' ) ) {
					wp_print_styles();
				}

				// Render the builder content.
				BACheetah::render_content_by_id( $query_post->ID );

			} else {

				// Render the WP editor content if the builder isn't enabled.
				echo apply_filters( 'the_content', $query_post->post_content );
			}
		}

		// Reset the site data?
		if ( $site_id && is_multisite() ) {
			restore_current_blog();
		}
	}

	/**
	 * Renders the layout for a post with the given post ID.
	 * This is useful for rendering builder content outside
	 * of the loop.
	 *

	 * @param int $post_id The ID of the post with the layout to render.
	 * @param string $tag The HTML tag for the content wrapper.
	 * @param array $attrs An array of key/value attribute data for the content wrapper.
	 * @return void
	 */
	static public function render_content_by_id( $post_id, $tag = 'div', $attrs = array() ) {
		// Force the builder to use this post ID.
		BACheetahModel::set_post_id( $post_id );

		// Build the attributes string.
		$attr_string = '';
		/**
		 * Change attributes for container.
		 * @see ba_cheetah_render_content_by_id_attrs
		 */
		$attrs = apply_filters( 'ba_cheetah_render_content_by_id_attrs', $attrs, $post_id );

		foreach ( $attrs as $attr_key => $attr_value ) {
			$attr_string .= ' ' . $attr_key . '="' . $attr_value . '"';
		}

		// Prevent the builder's render_content filter from running.
		add_filter( 'ba_cheetah_do_render_content', '__return_false' );

		/**
		 * Fire the render content start action.
		 * @see ba_cheetah_render_content_start
		 */
		do_action( 'ba_cheetah_render_content_start' );

		self::enqueue_layout_styles_scripts();

		// Render the content.
		ob_start();
		/**
		 * Before render content
		 * @see ba_cheetah_before_render_content
		 */
		do_action( 'ba_cheetah_before_render_content' );
		echo '<' . esc_html($tag) . ' class="' . self::render_content_classes() . '" data-post-id="' . esc_attr($post_id) . '"' . $attr_string . '>';
		self::render_nodes();
		echo '</' . esc_html($tag) . '>';
		/**
		 * After render content
		 * @see ba_cheetah_after_render_content
		 */
		do_action( 'ba_cheetah_after_render_content' );
		$content = ob_get_clean();

		// Allow the builder's render_content filter to run again.
		remove_filter( 'ba_cheetah_do_render_content', '__return_false' );

		// Process shortcodes.
		if ( apply_filters( 'ba_cheetah_render_shortcodes', true ) ) {
			global $wp_embed;
			$content = apply_filters( 'ba_cheetah_before_render_shortcodes', $content );
			$pattern = get_shortcode_regex();
			$content = preg_replace_callback( "/$pattern/s", 'BACheetah::double_escape_shortcodes', $content );
			$content = $wp_embed->run_shortcode( $content );
			$content = do_shortcode( $content );
			/**
			 * Allow content to be filtered after shortcodes are processed.
			 * @see ba_cheetah_after_render_shortcodes

			 */
			$content = apply_filters( 'ba_cheetah_after_render_shortcodes', $content );
		}

		// Add srcset attrs to images with the class wp-image-<ID>.
		if ( function_exists( 'wp_filter_content_tags' ) ) {
			$content = wp_filter_content_tags( $content );
		} elseif ( function_exists( 'wp_make_content_images_responsive' ) ) {
			$content = wp_make_content_images_responsive( $content );
		}

		/**
		 * Fire the render content complete action.
		 * @see ba_cheetah_render_content_complete
		 */
		do_action( 'ba_cheetah_render_content_complete' );

		// Stop forcing the builder to use this post ID.
		BACheetahModel::reset_post_id();

		echo $content;
	}

	/**
	 * Renders the content for a builder layout while in the loop.
	 * This method should only be called by the_content filter as
	 * defined in this class. To output builder content, use
	 * the_content function while in a WordPress loop or use
	 * the BACheetah::render_content_by_id method.
	 *

	 * @param string $content The existing content.
	 * @return string
	 */
	static public function render_content( $content ) {
		$post_id   = BACheetahModel::get_post_id( true );
		$enabled   = BACheetahModel::is_builder_enabled( $post_id );
		$rendering = $post_id === self::$post_rendering;
		$do_render = apply_filters( 'ba_cheetah_do_render_content', true, $post_id );
		$in_loop   = in_the_loop();
		$is_global = in_array( $post_id, BACheetahModel::get_global_posts() );

		if ( $enabled && ! $rendering && $do_render && ( $in_loop || $is_global ) ) {

			// Set the post rendering ID.
			self::$post_rendering = $post_id;

			// Try to enqueue here in case it didn't happen in the head for this layout.
			// self::enqueue_layout_styles_scripts();

			// Render the content.
			ob_start();
			self::render_content_by_id( $post_id );
			$content = ob_get_clean();

			// Clear the post rendering ID.
			self::$post_rendering = null;
		}

		return $content;
	}

	/**
	 * Escaped shortcodes need to be double escaped or they will
	 * be parsed by WP's shortcodes filter.
	 *

	 * @param array $matches The existing content.
	 * @return string
	 */
	static public function double_escape_shortcodes( $matches ) {
		if ( '[' == $matches[1] && ']' == $matches[6] ) {
			return '[' . $matches[0] . ']';
		}

		return $matches[0];
	}

	/**
	 * Renders the CSS classes for the main content div tag.
	 *

	 * @return string
	 */
	static public function render_content_classes() {
		global $wp_the_query;

		$post_id = BACheetahModel::get_post_id();

		// Build the content class.
		$classes = 'ba-cheetah-content ba-cheetah-content-' . $post_id;

		// Add the primary content class.
		if ( isset( $wp_the_query->post ) && $wp_the_query->post->ID == $post_id ) {
			$classes .= ' ba-cheetah-content-primary';
		}

		// Add browser specific classes.
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			if ( stristr( $_SERVER['HTTP_USER_AGENT'], 'Trident/7.0' ) && stristr( $_SERVER['HTTP_USER_AGENT'], 'rv:11.0' ) ) {
				$classes .= ' ba-cheetah-ie-11';
			}
		}

		return apply_filters( 'ba_cheetah_content_classes', $classes );
	}

	/**
	 * Renders the markup for all nodes in a layout.
	 *

	 * @return void
	 */
	static public function render_nodes() {
		/**
		 * Before render nodes.
		 * @see ba_cheetah_before_render_nodes
		 */
		do_action( 'ba_cheetah_before_render_nodes' );

		if ( apply_filters( 'ba_cheetah_render_nodes', true ) ) {
			self::render_rows();
		}

		/**
		 * After render nodes.
		 * @see ba_cheetah_after_render_nodes
		 */
		do_action( 'ba_cheetah_after_render_nodes' );
	}

	/**
	 * Renders the markup for a node's attributes.
	 *

	 * @param array $attrs
	 * @return void
	 */
	static public function render_node_attributes( $attrs ) {
		foreach ( $attrs as $attr_key => $attr_value ) {

			if ( empty( $attr_value ) ) {
				continue;
			} elseif ( is_string( $attr_value ) ) {
				echo ' ' . esc_html($attr_key) . '="' . esc_attr($attr_value) . '"';
			} elseif ( is_array( $attr_value ) && ! empty( $attr_value ) ) {

				echo ' ' . esc_html($attr_key) . '="';

				for ( $i = 0; $i < count( $attr_value ); $i++ ) {

					echo esc_attr($attr_value[ $i ]);

					if ( $i < count( $attr_value ) - 1 ) {
						echo ' ';
					}
				}

				echo '"';
			}
		}
	}

	/**
	 * Renders the stripped down content for a layout
	 * that is saved to the WordPress editor.
	 *

	 * @param string $content The existing content.
	 * @return string
	 */
	static public function render_editor_content() {
		$rows = BACheetahModel::get_nodes( 'row' );

		ob_start();

		// Render the modules.
		foreach ( $rows as $row ) {

			$groups = BACheetahModel::get_nodes( 'column-group', $row );

			foreach ( $groups as $group ) {

				$cols = BACheetahModel::get_nodes( 'column', $group );
				foreach ( $cols as $col ) {

					$col_children = BACheetahModel::get_nodes( null, $col );

					foreach ( $col_children as $col_child ) {

						if ( 'module' == $col_child->type ) {

							$module = BACheetahModel::get_module( $col_child );

							if ( $module && $module->editor_export ) {

								// Don't crop photos to ensure media library photos are rendered.
								if ( 'photo' == $module->settings->type ) {
									$module->settings->crop = false;
								}

								BACheetah::render_module_html( $module->settings->type, $module->settings, $module );
							}
						} elseif ( 'column-group' == $col_child->type ) {

							$group_cols = BACheetahModel::get_nodes( 'column', $col_child );

							foreach ( $group_cols as $group_col ) {

								$modules = BACheetahModel::get_modules( $group_col );

								foreach ( $modules as $module ) {

									if ( $module->editor_export ) {

										// Don't crop photos to ensure media library photos are rendered.
										if ( 'photo' == $module->settings->type ) {
											$module->settings->crop = false;
										}

										BACheetah::render_module_html( $module->settings->type, $module->settings, $module );
									}
								}
							}
						}
					}
				}
			}
		}

		// Get the content.
		$content = ob_get_clean();

		// Remove unnecessary tags and attributes.
		$content = preg_replace( '/<\/?div[^>]*\>/i', '', $content );
		$content = preg_replace( '/<\/?span[^>]*\>/i', '', $content );
		$content = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $content );
		$content = preg_replace( '/<\/?noscript[^>]*\>/i', '', $content );
		$content = preg_replace( '#<svg(.*?)>(.*?)</svg>#is', '', $content );
		$content = preg_replace( '/<i [^>]*><\\/i[^>]*>/', '', $content );
		$content = preg_replace( '/ class=".*?"/', '', $content );
		$content = preg_replace( '/ style=".*?"/', '', $content );

		// Remove empty lines.
		$content = preg_replace( '/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', "\n", $content );

		return apply_filters( 'ba_cheetah_editor_content', $content );
	}

	/**
	 * Renders a settings via PHP. This method is only around for
	 * backwards compatibility with third party settings forms that are
	 * still being rendered via AJAX. Going forward, all settings forms
	 * should be rendered on the frontend using BACheetahSettingsForms.render.
	 *

	 * @param array $form The form data.
	 * @param object $settings The settings data.
	 * @return array
	 */
	static public function render_settings( $form, $settings ) {
		return BACheetahUISettingsForms::render_settings( (array) $form, $settings );
	}

	/**
	 * Renders a settings form via PHP. This method is only around for
	 * backwards compatibility with third party settings forms that are
	 * still being rendered via AJAX. Going forward, all settings forms
	 * should be rendered on the frontend using BACheetahSettingsForms.render.
	 *

	 * @param string $type The type of form to render.
	 * @param object $settings The settings data.
	 * @return array
	 */
	static public function render_settings_form( $type = null, $settings = null ) {
		return BACheetahUISettingsForms::render_settings_form( $type, $settings );
	}

	/**
	 * Renders a settings field via PHP. This method is only around for
	 * backwards compatibility with third party settings forms that are
	 * still being rendered via AJAX. Going forward, all settings forms
	 * should be rendered on the frontend using BACheetahSettingsForms.render.
	 *

	 * @param string $name The field name.
	 * @param array $field An array of setup data for the field.
	 * @param object $settings Form settings data object.
	 * @return void
	 */
	static public function render_settings_field( $name, $field, $settings = null ) {
		return BACheetahUISettingsForms::render_settings_field( $name, $field, $settings );
	}

	/**
	 * Renders the markup for the icon selector.
	 *

	 * @return array
	 */
	static public function render_icon_selector() {
		return BACheetahUISettingsForms::render_icon_selector();
	}

	/**
	 * Renders the markup for all of the rows in a layout.
	 *

	 * @return void
	 */
	static public function render_rows() {
		$rows = BACheetahModel::get_nodes( 'row' );

		/**
		 * Before rendering the markup for all of the rows in a layout.
		 * @see ba_cheetah_before_render_rows
		 */
		do_action( 'ba_cheetah_before_render_rows', $rows );

		foreach ( $rows as $row ) {
			self::render_row( $row );
		}

		/**
		 * After rendering the markup for all of the rows in a layout.
		 * @see ba_cheetah_after_render_rows
		 */
		do_action( 'ba_cheetah_after_render_rows', $rows );
	}

	/**
	 * Renders the markup for a single row.
	 *

	 * @param object $row The row to render.
	 * @return void
	 */
	static public function render_row( $row ) {
		global $wp_the_query;

		$groups  = BACheetahModel::get_nodes( 'column-group', $row );
		$post_id = BACheetahModel::get_post_id();
		$active  = BACheetahModel::is_builder_active() && $post_id == $wp_the_query->post->ID;
		$visible = BACheetahModel::is_node_visible( $row );

		if ( $active || $visible ) {

			/**
			 * Before rendering a row
			 * @see ba_cheetah_before_render_row
			 */
			do_action( 'ba_cheetah_before_render_row', $row, $groups );

			$template_file = self::locate_template_file(
				apply_filters( 'ba_cheetah_row_template_base', 'row', $row ),
				apply_filters( 'ba_cheetah_row_template_slug', '', $row )
			);

			if ( $template_file ) {
				include $template_file;
			}

			/**
			 * After rendering a row.
			 * @see ba_cheetah_after_render_row
			 */
			do_action( 'ba_cheetah_after_render_row', $row, $groups );
		} else {
			/**
			 * Fires in place of a hidden row.
			 * @see ba_cheetah_hidden_node
			 */
			do_action( 'ba_cheetah_hidden_node', $row );
		}
	}

	/**
	 * Renders the HTML attributes for a single row.
	 *

	 * @param object $row A row node object.
	 * @return void
	 */
	static public function render_row_attributes( $row ) {
		/**
		 * Use this filter to work with the custom class a user adds to a row under Row Settings > Advanced > Class.
		 * @see ba_cheetah_row_custom_class
		 */
		$custom_class = apply_filters( 'ba_cheetah_row_custom_class', $row->settings->class, $row );
		$overlay_bgs  = array( 'photo', 'parallax', 'slideshow', 'video' );
		$active       = BACheetahModel::is_builder_active();
		$visible      = BACheetahModel::is_node_visible( $row );
		$has_rules    = BACheetahModel::node_has_visibility_rules( $row );
		$rules        = BACheetahModel::node_visibility_rules( $row );

		$attrs        = array(
			'id'        => $row->settings->id,
			'class'     => array(
				'ba-cheetah-row',
				'ba-cheetah-row-type-'.$row->type,
				'ba-cheetah-row-' . $row->settings->width . '-width',
				'ba-cheetah-row-bg-' . $row->settings->bg_type,
				'ba-cheetah-node-' . $row->node,
			),
			'data-node' => $row->node,
		);

		// Classes
		if ( ! empty( $row->settings->full_height ) && 'full' == $row->settings->full_height ) {

			$attrs['class'][] = 'ba-cheetah-row-full-height';

			if ( isset( $row->settings->content_alignment ) ) {
				$attrs['class'][] = 'ba-cheetah-row-align-' . $row->settings->content_alignment;
			}

			if ( isset( $row->settings->margin_top ) && (int) $row->settings->margin_top < 0 ) {
				$attrs['class'][] = 'ba-cheetah-row-overlap-top';
			}
		}
		if ( ! empty( $row->settings->full_height ) && 'custom' == $row->settings->full_height ) {

			$attrs['class'][] = 'ba-cheetah-row-custom-height';

			if ( isset( $row->settings->content_alignment ) ) {
				$attrs['class'][] = 'ba-cheetah-row-align-' . $row->settings->content_alignment;
			}

			if ( isset( $row->settings->margin_top ) && (int) $row->settings->margin_top < 0 ) {
				$attrs['class'][] = 'ba-cheetah-row-overlap-top';
			}
		}
		if ( in_array( $row->settings->bg_type, $overlay_bgs ) ) {
			if ( 'color' === $row->settings->bg_overlay_type && ! empty( $row->settings->bg_overlay_color ) ) {
				$attrs['class'][] = 'ba-cheetah-row-bg-overlay';
			} elseif ( 'gradient' === $row->settings->bg_overlay_type ) {
				$attrs['class'][] = 'ba-cheetah-row-bg-overlay';
			}
		}
		if ( ! empty( $row->settings->responsive_display ) ) {
			$attrs['class'][] = 'ba-cheetah-visible-' . $row->settings->responsive_display;
		}
		if ( is_array( $row->settings->animation ) && ! empty( $row->settings->animation['style'] ) ) {
			$attrs['class'][]                = 'ba-cheetah-animation ba-cheetah-' . $row->settings->animation['style'];
			$attrs['data-animation-delay'][] = $row->settings->animation['delay'];
			if ( isset( $row->settings->animation['duration'] ) ) {
				$attrs['data-animation-duration'][] = $row->settings->animation['duration'];
			}
		}
		if ( ! empty( $custom_class ) ) {
			$attrs['class'][] = trim( esc_attr( $custom_class ) );
		}
		if ( $active && ! $visible ) {
			$attrs['class'][] = 'ba-cheetah-node-hidden';
		}
		if ( $active && $has_rules ) {
			$attrs['class'][]         = 'ba-cheetah-node-has-rules';
			$attrs['data-rules-type'] = $rules['type'];
			$attrs['data-rules-text'] = esc_attr( $rules['text'] );
		}
		if ( ! empty( $row->settings->top_edge_shape ) || ! empty( $row->settings->bottom_edge_shape ) ) {
			$attrs['class'][] = 'ba-cheetah-row-has-layers';
		}
		if ( ( 'photo' === $row->settings->bg_type ) && ( 'fixed' === $row->settings->bg_attachment ) ) {
			$attrs['class'][] = 'ba-cheetah-row-bg-fixed';
		}

		// Data
		if ( 'parallax' == $row->settings->bg_type && ! empty( $row->settings->bg_parallax_image_src ) ) {
			$attrs['data-parallax-speed'] = $row->settings->bg_parallax_speed;
			$attrs['data-parallax-image'] = $row->settings->bg_parallax_image_src;
		}

		self::render_node_attributes( apply_filters( 'ba_cheetah_row_attributes', $attrs, $row ) );
	}

	/**
	 * Renders the markup for a row's background.
	 *

	 * @param object $row A row node object.
	 * @return void
	 */
	static public function render_row_bg( $row ) {
		/**
		 * Before rendering a row background
		 * @see ba_cheetah_before_render_row_bg
		 */
		do_action( 'ba_cheetah_before_render_row_bg', $row );

		if ( 'video' == $row->settings->bg_type ) {

			$vid_data = BACheetahModel::get_row_bg_data( $row );

			if ( $vid_data || in_array( $row->settings->bg_video_source, array( 'video_url', 'video_service', 'video_embed' ) ) ) {
				$template_file = self::locate_template_file(
					apply_filters( 'ba_cheetah_row_video_bg_template_base', 'row-video', $row ),
					apply_filters( 'ba_cheetah_row_video_bg_template_slug', '', $row )
				);

				if ( $template_file ) {
					include $template_file;
				}
			}
		} elseif ( 'embed' == $row->settings->bg_type && ! empty( $row->settings->bg_embed_code ) ) {
			echo '<div class="ba-cheetah-bg-embed-code">' . wp_kses_data($row->settings->bg_embed_code) . '</div>';
		} elseif ( 'slideshow' == $row->settings->bg_type ) {
			echo '<div class="ba-cheetah-bg-slideshow"></div>';
		}

		/**
		 * After rendering a row background
		 * @see ba_cheetah_after_render_row_bg
		 */
		do_action( 'ba_cheetah_after_render_row_bg', $row );
	}

	/**
	 * Renders the HTML class for a row's content wrapper.
	 *

	 * @param object $row A row node object.
	 * @return void
	 */
	static public function render_row_content_class( $row ) {
		echo 'ba-cheetah-row-content';
		echo ' ba-cheetah-row-' . esc_attr($row->settings->content_width) . '-width';
		echo ' ba-cheetah-node-content';
	}

	/**
	 * Renders the markup for a column group.
	 *

	 * @param object $group A column group node object.
	 * @return void
	 */
	static public function render_column_group( $group ) {
		$cols = BACheetahModel::get_nodes( 'column', $group );

		/**
		 * Before rendering a column group
		 * @see ba_cheetah_before_render_column_group
		 */
		do_action( 'ba_cheetah_before_render_column_group', $group, $cols );

		$template_file = self::locate_template_file(
			apply_filters( 'ba_cheetah_column_group_template_base', 'column-group', $group ),
			apply_filters( 'ba_cheetah_column_group_template_slug', '', $group )
		);

		if ( $template_file ) {
			include $template_file;
		}
		/**
		 * After rendering a column group.
		 * @see ba_cheetah_after_render_column_group
		 */
		do_action( 'ba_cheetah_after_render_column_group', $group, $cols );
	}

	/**
	 * Renders the attrs for a column group.
	 *

	 * @param object $group
	 * @return void
	 */
	static public function render_column_group_attributes( $group ) {
		$cols   = BACheetahModel::get_nodes( 'column', $group );
		$parent = BACheetahModel::get_node_parent( $group );
		$attrs  = array(
			'class'     => array(
				'ba-cheetah-col-group',
				'ba-cheetah-node-' . $group->node,
			),
			'data-node' => $group->node,
		);

		if ( isset( $parent->type ) && 'column' == $parent->type ) {
			$attrs['class'][] = 'ba-cheetah-col-group-nested';
		}

		foreach ( $cols as $col ) {

			if ( isset( $col->settings->equal_height ) && 'yes' == $col->settings->equal_height ) {
				if ( ! in_array( 'ba-cheetah-col-group-equal-height', $attrs['class'] ) ) {
					$attrs['class'][] = 'ba-cheetah-col-group-equal-height';
				}
				if ( isset( $col->settings->content_alignment ) ) {
					if ( ! in_array( 'ba-cheetah-col-group-align-' . $col->settings->content_alignment, $attrs['class'] ) ) {
						$attrs['class'][] = 'ba-cheetah-col-group-align-' . $col->settings->content_alignment;
					}
				}
			}
			if ( isset( $col->settings->size_responsive ) && ! empty( $col->settings->size_responsive ) ) {
				if ( ! in_array( 'ba-cheetah-col-group-custom-width', $attrs['class'] ) ) {
					$attrs['class'][] = 'ba-cheetah-col-group-custom-width';
				}
			}
			if ( isset( $col->settings->responsive_order ) && 'set' == $col->settings->responsive_order ) {
				if ( ! in_array( 'ba-cheetah-col-group-responsive-set', $attrs['class'] ) ) {
					$attrs['class'][] = 'ba-cheetah-col-group-responsive-set';
				}
			}
			else if ( isset( $col->settings->responsive_order ) && 'reversed' == $col->settings->responsive_order ) {
				if ( ! in_array( 'ba-cheetah-col-group-responsive-reversed', $attrs['class']) && ! in_array( 'ba-cheetah-col-group-responsive-set', $attrs['class']) ) {
					$attrs['class'][] = 'ba-cheetah-col-group-responsive-reversed';
				}
			}
		}

		self::render_node_attributes( apply_filters( 'ba_cheetah_column_group_attributes', $attrs, $group ) );
	}

	/**
	 * Renders the markup for a single column.
	 *

	 * @param string|object $col_id A column ID or object.
	 * @return void
	 */
	static public function render_column( $col_id = null ) {
		global $wp_the_query;

		$col     = is_object( $col_id ) ? $col_id : BACheetahModel::get_node( $col_id );
		$post_id = BACheetahModel::get_post_id();
		$active  = BACheetahModel::is_builder_active() && $post_id == $wp_the_query->post->ID;
		$visible = BACheetahModel::is_node_visible( $col );

		if ( $active || $visible ) {
			include BA_CHEETAH_DIR . 'includes/column.php';
		} else {
			/**
			 * Fires in place of a hidden column.
			 * @see ba_cheetah_hidden_node
			 */
			do_action( 'ba_cheetah_hidden_node', $col );
		}
	}

	/**
	 * Renders the HTML attributes for a single column.
	 *

	 * @param object $col A column node object.
	 * @return void
	 */
	static public function render_column_attributes( $col ) {
		/**
		 * Use this filter to work with the custom class a user adds to a column under Column Settings > Advanced > Class.
		 * @see ba_cheetah_column_custom_class
		 */
		$custom_class    = apply_filters( 'ba_cheetah_column_custom_class', $col->settings->class, $col );
		$overlay_bgs     = array( 'photo' );
		$nested          = BACheetahModel::get_nodes( 'column-group', $col );
		$active          = BACheetahModel::is_builder_active();
		$visible         = BACheetahModel::is_node_visible( $col );
		$has_rules       = BACheetahModel::node_has_visibility_rules( $col );
		$rules           = BACheetahModel::node_visibility_rules( $col );
		$attrs           = array(
			'id'        => $col->settings->id,
			'class'     => array(
				'ba-cheetah-col',
				'ba-cheetah-node-' . $col->node,
			),
			'data-node' => $col->node,
			'style'     => array(),
		);
		$global_settings = BACheetahModel::get_global_settings();

		// Classes
		if ( $col->settings->size <= 50 ) {
			$attrs['class'][] = 'ba-cheetah-col-small';

			if ( $global_settings->responsive_enabled && ! $global_settings->responsive_col_max_width ) {
				$attrs['class'][] = 'ba-cheetah-col-small-full-width';
			}
		}
		if ( count( $nested ) > 0 ) {
			$attrs['class'][] = 'ba-cheetah-col-has-cols';
		}
		if ( in_array( $col->settings->bg_type, $overlay_bgs ) ) {
			if ( 'color' === $col->settings->bg_overlay_type && ! empty( $col->settings->bg_overlay_color ) ) {
				$attrs['class'][] = 'ba-cheetah-col-bg-overlay';
			} elseif ( 'gradient' === $col->settings->bg_overlay_type ) {
				$attrs['class'][] = 'ba-cheetah-col-bg-overlay';
			}
		}
		if ( ! empty( $col->settings->responsive_display ) ) {
			$attrs['class'][] = 'ba-cheetah-visible-' . $col->settings->responsive_display;
		}
		if ( is_array( $col->settings->animation ) && ! empty( $col->settings->animation['style'] ) ) {
			$attrs['class'][]                = 'ba-cheetah-animation ba-cheetah-' . $col->settings->animation['style'];
			$attrs['data-animation-delay'][] = $col->settings->animation['delay'];
			if ( isset( $col->settings->animation['duration'] ) ) {
				$attrs['data-animation-duration'][] = $col->settings->animation['duration'];
			}
		}
		if ( ! empty( $custom_class ) ) {
			$attrs['class'][] = trim( esc_attr( $custom_class ) );
		}
		if ( $active && ! $visible ) {
			$attrs['class'][] = 'ba-cheetah-node-hidden';
		}
		if ( $active && $has_rules ) {
			$attrs['class'][]         = 'ba-cheetah-node-has-rules';
			$attrs['data-rules-type'] = $rules['type'];
			$attrs['data-rules-text'] = esc_attr( $rules['text'] );
		}

		// Style
		if ( $active ) {
			$attrs['style'][] = 'width: ' . $col->settings->size . '%;';
		}

		/**
		 * Column attributes.
		 * @see ba_cheetah_column_attributes
		 */
		self::render_node_attributes( apply_filters( 'ba_cheetah_column_attributes', $attrs, $col ) );
	}

	/**
	 * Renders the markup for all modules in a column.
	 *

	 * @param string|object $col_id A column ID or object.
	 * @return void
	 */
	static public function render_modules( $col_id = null ) {
		$nodes = BACheetahModel::get_nodes( null, $col_id );

		/**
		 * Before rendering modules in a column
		 * @see ba_cheetah_before_render_modules
		 */
		do_action( 'ba_cheetah_before_render_modules', $nodes, $col_id );

		foreach ( $nodes as $node ) {

			if ( 'module' == $node->type && BACheetahModel::is_module_registered( $node->settings->type ) ) {
				self::render_module( $node );
			} elseif ( 'column-group' == $node->type ) {
				self::render_column_group( $node );
			}
		}
		/**
		 * After rendering modules in a column
		 * @see ba_cheetah_after_render_modules
		 */
		do_action( 'ba_cheetah_after_render_modules', $nodes, $col_id );
	}

	/**
	 * Renders the markup for a single module.
	 *

	 * @param string|object $module_id A module ID or object.
	 * @return void
	 */
	static public function render_module( $module_id = null ) {
		global $wp_the_query;

		$module   = BACheetahModel::get_module( $module_id );
		$settings = $module->settings;
		$id       = $module->node;
		$post_id  = BACheetahModel::get_post_id();
		$active   = BACheetahModel::is_builder_active() && $post_id == $wp_the_query->post->ID;
		$visible  = BACheetahModel::is_node_visible( $module );

		if ( $active || $visible ) {

			/**
			 * Before single module is rendered via ajax.
			 * @see ba_cheetah_before_render_module
			 */
			do_action( 'ba_cheetah_before_render_module', $module );

			$template_file = self::locate_template_file(
				apply_filters( 'ba_cheetah_module_template_base', 'module', $module ),
				apply_filters( 'ba_cheetah_module_template_slug', '', $module )
			);

			if ( $template_file ) {
				include $template_file;
			}

			/**
			 * After single module is rendered via ajax.
			 * @see ba_cheetah_after_render_module
			 */
			do_action( 'ba_cheetah_after_render_module', $module );
		} else {
			/**
			 * Fires in place of a hidden module.
			 * @see ba_cheetah_hidden_node
			 */
			do_action( 'ba_cheetah_hidden_node', $module );
		}
	}

	/**
	 * Renders the markup for a single module. This can be used to render
	 * the markup of a module within another module by passing the type
	 * and settings params and leaving the module param null.
	 *

	 * @param string $type The type of module.
	 * @param object $settings A module settings object.
	 * @param object $module Optional. An existing module object to use.
	 * @return void
	 */
	static public function render_module_html( $type, $settings, $module = null ) {
		// Settings
		$defaults = BACheetahModel::get_module_defaults( $type );
		$settings = (object) array_merge( (array) $defaults, (array) $settings );

		// Module
		$class            = get_class( BACheetahModel::$modules[ $type ] );
		$module           = new $class();
		$module->settings = BACheetahSettingsCompat::filter_node_settings( 'module', $settings );

		// Shorthand reference to the module's id.
		$id = $module->node;

		/**
		 * Before single module html is rendered.
		 * used by render_module_html()
		 * @see ba_cheetah_render_module_html_before
		 */
		do_action( 'ba_cheetah_render_module_html_before', $type, $settings, $module );

		ob_start();

		if ( has_filter( 'ba_cheetah_module_frontend_custom_' . $module->slug ) ) {
			echo apply_filters( 'ba_cheetah_module_frontend_custom_' . $module->slug, (array) $module->settings, $module );
		} else {
			include apply_filters( 'ba_cheetah_render_module_html', $module->dir . 'includes/frontend.php', $type, $settings, $module );
		}

		$content = ob_get_clean();

		echo apply_filters( 'ba_cheetah_render_module_html_content', $content, $type, $settings, $module );

		/**
		 * Before single module html is rendered.
		 * used by render_module_html()
		 * @see ba_cheetah_render_module_html_after
		 */
		do_action( 'ba_cheetah_render_module_html_after', $type, $settings, $module );
	}

	/**
	 * Renders the HTML attributes for a single module.
	 *

	 * @param object $module A module node object.
	 * @return void
	 */
	static public function render_module_attributes( $module ) {
		/**
		 * Use this filter to work with the custom class a user adds to a module in the Class field on the Advanced tab.
		 * @see ba_cheetah_module_custom_class
		 */
		$custom_class = apply_filters( 'ba_cheetah_module_custom_class', $module->settings->class, $module );
		$active       = BACheetahModel::is_builder_active();
		$visible      = BACheetahModel::is_node_visible( $module );
		$has_rules    = BACheetahModel::node_has_visibility_rules( $module );
		$rules        = BACheetahModel::node_visibility_rules( $module );
		$attrs        = array(
			'id'        => esc_attr( $module->settings->id ),
			'class'     => array(
				'ba-cheetah-module',
				'ba-cheetah-module-' . $module->settings->type,
				'ba-cheetah-node-' . $module->node,
			),
			'data-node' => $module->node,
		);

		// Classes
		if ( ! empty( $module->settings->responsive_display ) ) {
			$attrs['class'][] = 'ba-cheetah-visible-' . $module->settings->responsive_display;
		}

        self::apply_scroll_animation($module, $attrs);

        if ( is_array( $module->settings->animation ) && ! empty( $module->settings->animation['style'] ) ) {
			$attrs['class'][]                = 'ba-cheetah-animation ba-cheetah-' . $module->settings->animation['style'];
			$attrs['data-animation-delay'][] = $module->settings->animation['delay'];
			if ( isset( $module->settings->animation['duration'] ) ) {
				$attrs['data-animation-duration'][] = $module->settings->animation['duration'];
			}
		}
		if ( ! empty( $custom_class ) ) {
			$attrs['class'][] = trim( esc_attr( $custom_class ) );
		}
		if ( $active && ! $visible ) {
			$attrs['class'][] = 'ba-cheetah-node-hidden';
		}
		if ( $active && $has_rules ) {
			$attrs['class'][]         = 'ba-cheetah-node-has-rules';
			$attrs['data-rules-type'] = $rules['type'];
			$attrs['data-rules-text'] = esc_attr( $rules['text'] );
		}

		// Data
		if ( $active ) {
			$attrs['data-parent'] = $module->parent;
			$attrs['data-type']   = $module->settings->type;
			$attrs['data-name']   = $module->name;
		}

		/**
		 * Module attributes.
		 * @see ba_cheetah_module_attributes
		 */
		self::render_node_attributes( apply_filters( 'ba_cheetah_module_attributes', $attrs, $module ) );
	}

	/**
	 * Renders the CSS for a single module.
	 *
	 * NOTE: This is not used to render CSS for modules in the BACheetah::render_css
	 * method. Instead it is used to render CSS for one module inside of another.
	 * For example, you can use this along with BACheetah::render_module_html to
	 * render a button module inside of a callout module. If you need to filter the
	 * CSS for the layout, consider using the ba_cheetah_render_css filter instead.
	 *

	 * @param string $type The type of module.
	 * @param object $id A module node ID.
	 * @param object $settings A module settings object.
	 * @return void
	 */
	static public function render_module_css( $type, $id, $settings ) {
		// Settings
		$global_settings = BACheetahModel::get_global_settings();
		$defaults        = BACheetahModel::get_module_defaults( $type );
		$settings        = (object) array_merge( (array) $defaults, (array) $settings );
		$settings        = apply_filters( 'ba_cheetah_render_module_css_settings', $settings, $id, $type );

		/**
		 * Make sure the Class is not NULL before trying to use it, see #513

		 */
		if ( null === BACheetahModel::$modules[ $type ] ) {
			printf( "\n/* Critical Error!! Class for %s with ID %s not found. */\n", $type, $id );
			return false;
		}

		// Module
		$class            = get_class( BACheetahModel::$modules[ $type ] );
		$module           = new $class();
		$module->settings = BACheetahSettingsCompat::filter_node_settings( 'module', $settings );

		// CSS
		ob_start();
		include $module->dir . 'includes/frontend.css.php';
		BACheetahCSS::render();
		$css = ob_get_clean();

		echo apply_filters( 'ba_cheetah_render_module_css', $css, $module, $id );
	}

	/**
	 * Renders the CSS and JS assets.
	 *

	 * @return void
	 */
	static public function render_assets() {
		self::render_css();
		self::render_js();
	}

	static public function register_frontend_scripts_before_end_head() {
		BACheetahTracking::render_facebook_pixel_header_code();
	}

	/**
	 * Renders custom CSS in a style tag so it can be edited
	 * using the builder interface.
	 *

	 * @return void
	 */
	static public function render_custom_css_for_editing() {

		if ( ! BACheetahModel::is_builder_active() && ! isset( $_GET['ba_cheetah_preview'] ) ) {
			return;
		}

		$global_settings = BACheetahModel::get_global_settings();
		$layout_settings = BACheetahModel::get_layout_settings();

		echo '<style id="ba-cheetah-global-css">' . self::maybe_do_shortcode( $global_settings->css ) . '</style>';
		echo '<style id="ba-cheetah-layout-css">' . self::maybe_do_shortcode( $layout_settings->css ) . '</style>';
	}


	/**
	 * If rendering the page background via AJAX (when editing),
	 * get the settings sent via post. Otherwise use the settings saved in the database
	 *
	 * @param [string] $type page or global
	 * @return mixed
	 */

	static public function get_ajax_page_settings($type) {
		$post_data = BACheetahModel::get_cheetah_ba_data();
		if (isset($post_data['node_preview']) && isset($post_data['preview_type']) && $post_data['preview_type'] == $type) {
			return (object) $post_data['node_preview'];
		}
		return false;
	}

	/**
	 * Renders and caches the CSS for a builder layout.
	 *

	 * @param bool $include_global
	 * @return string
	 */

	static public function render_css( $include_global = true ) {
		global $wp_the_query;

		$active          = BACheetahModel::is_builder_active();
		$nodes           = BACheetahModel::get_categorized_nodes();
		$node_status     = BACheetahModel::get_node_status();
		$global_settings = BACheetahModel::get_global_settings();
		$asset_info      = BACheetahModel::get_asset_info();
		$enqueuemethod   = BACheetahModel::get_asset_enqueue_method();
		$post_id         = BACheetahModel::get_post_id();
		$page_settings   = BACheetahModel::get_page_settings();
		$post            = get_post( $post_id );
		$css             = '';
		$path            = $include_global ? $asset_info['css'] : $asset_info['css_partial'];

		// Render the global css.
		if ( $include_global ) {
			$css .= self::render_global_css();
		}

		// Render the page css
		if (get_post_type() == 'page' && is_main_query()) {
			$settings = self::get_ajax_page_settings('page') ?: $page_settings;
			ob_start();
			include BA_CHEETAH_DIR . 'includes/page-css.php';
			BACheetahCSS::render();
			$css .= ob_get_clean();
		}

		// Loop through rows
		foreach ( $nodes['rows'] as $row ) {

			// Instance row css
			$settings = $row->settings;
			$id       = $row->node;
			ob_start();
			include BA_CHEETAH_DIR . 'includes/row-css.php';
			BACheetahCSS::render();
			$css .= ob_get_clean();

			// Instance row margins
			$css .= self::render_row_margins( $row );

			// Instance row padding
			$css .= self::render_row_padding( $row );

			// Instance row animation
			$css .= self::render_node_animation_css( $row->settings );
		}

		// Loop through the columns.
		foreach ( $nodes['columns'] as $col ) {

			// Instance column css
			$settings = $col->settings;
			$id       = $col->node;
			ob_start();
			include BA_CHEETAH_DIR . 'includes/column-css.php';
			BACheetahCSS::render();
			$css .= ob_get_clean();

			// Instance column margins
			$css .= self::render_column_margins( $col );

			// Instance column padding
			$css .= self::render_column_padding( $col );

			// Instance column animation
			$css .= self::render_node_animation_css( $col->settings );
		}

		// Loop through the modules.
		foreach ( $nodes['modules'] as $module ) {

			// modules to render its css
			$module_slugs_to_render = [$module->slug];

			// module dependencies
			if (isset($module->dependencies) && is_array($module->dependencies) && count($module->dependencies)){
				$module_slugs_to_render = array_merge($module_slugs_to_render, $module->dependencies);
			}

			// Only include global module css that hasn't been included yet.
			foreach ($module_slugs_to_render as $module_slug) {

				if ( ! in_array( $module_slug . '-module-css', self::$enqueued_global_assets ) ) {

					// Add to the compiled array so we don't include it again.
					self::$enqueued_global_assets[] = $module_slug . '-module-css';

					$file 				= BA_CHEETAH_DIR . 'modules/' . $module_slug . '/css/frontend.css';
					$file_responsive 	= BA_CHEETAH_DIR . 'modules/' . $module_slug . '/css/frontend.responsive.css';

					if ( ba_cheetah_filesystem()->file_exists( $file ) ) {
						$css .= ba_cheetah_filesystem()->file_get_contents( $file );
					}

					// Get the responsive module css.
					if ( $global_settings->responsive_enabled && ba_cheetah_filesystem()->file_exists( $file_responsive ) ) {
						$css .= '@media (max-width: ' . $global_settings->responsive_breakpoint . 'px) { ';
						$css .= ba_cheetah_filesystem()->file_get_contents( $file_responsive );
						$css .= ' }';
					}
				}
			}

			// Instance module css
			$file     = $module->dir . 'includes/frontend.css.php';
			$settings = $module->settings;
			$id       = $module->node;

			if ( ! in_array( $id, self::$enqueued_module_css_assets ) && ba_cheetah_filesystem()->file_exists( $file ) ) {
				self::$enqueued_module_css_assets[] = $id;
				ob_start();
				include $file;
				BACheetahCSS::render();
				$css .= ob_get_clean();
			}

			// Instance module margins
			$css .= self::render_module_margins( $module );

			if ( ! isset( $global_settings->auto_spacing ) || $global_settings->auto_spacing ) {
				$css .= self::render_responsive_module_margins( $module );
			}

			// Instance module animation
			$css .= self::render_node_animation_css( $module->settings );
		}
		// Loop through the modules.
		foreach ( $nodes['row-inside'] as $row ) {
			// Instance row css
			$settings = $row->settings;
			$id       = $row->node;

			ob_start();
			include BA_CHEETAH_DIR . 'includes/row-css.php';
			BACheetahCSS::render();
			$css .= ob_get_clean();

			// Instance row margins
			$css .= self::render_row_margins( $row );

			// Instance row padding
			$css .= self::render_row_padding( $row );

			// Instance row animation
			$css .= self::render_node_animation_css( $row->settings );
		}

		// Render all animation CSS when the builder is active.
		if ( $active ) {
			$css .= self::render_all_animation_css();
		}

		// Custom Global CSS (included here for proper specificity)
		if ( 'published' == $node_status && $include_global ) {
			$css .= self::js_comment( 'Global CSS', self::maybe_do_shortcode( $global_settings->css ) );
		}

		// Custom Global Nodes CSS
		$css .= self::js_comment( 'Global Nodes CSS', self::maybe_do_shortcode( self::render_global_nodes_custom_code( 'css' ) ) );

		// Custom Layout CSS
		if ( 'published' == $node_status || $post_id !== $wp_the_query->post->ID ) {
			$css .= self::js_comment( 'Layout CSS', self::maybe_do_shortcode( BACheetahModel::get_layout_settings()->css ) );
		}

		/**
		 * Use this filter to modify the CSS that is compiled and cached for each builder layout.
		 * @see ba_cheetah_render_css
		 */
		$css = apply_filters( 'ba_cheetah_render_css', $css, $nodes, $global_settings, $include_global );

		// Minify the CSS.
		if ( ! self::is_debug() ) {
			$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
			$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
		}

		// Save the CSS.
		if ( 'file' === $enqueuemethod ) {
			ba_cheetah_filesystem()->file_put_contents( $path, $css );
		}

		/**
		 * After CSS is compiled.
		 * @see ba_cheetah_after_render_css
		 */
		do_action( 'ba_cheetah_after_render_css' );

		return $css;
	}

	/**
	 * Renders the CSS used for all builder layouts.
	 *

	 * @return string
	 */
	static public function render_global_css() {
		// Get info on the new file.
		$global_settings = BACheetahModel::get_global_settings();

		// Core layout css
		$css = ba_cheetah_filesystem()->file_get_contents( BA_CHEETAH_DIR . 'css/ba-cheetah-layout.css' );

		// Core button defaults
		if ( ! defined( 'BA_CHEETAH_THEME_VERSION' ) ) {
			$css .= ba_cheetah_filesystem()->file_get_contents( BA_CHEETAH_DIR . 'css/ba-cheetah-layout-button-defaults.css' );
		}

		// Core layout RTL css
		if ( is_rtl() ) {
			$css .= ba_cheetah_filesystem()->file_get_contents( BA_CHEETAH_DIR . 'css/ba-cheetah-layout-rtl.css' );
		}

		// Global settings
		$settings = self::get_ajax_page_settings('global') ?: $global_settings;
		ob_start();
		include BA_CHEETAH_DIR . 'includes/global-css.php';
		BACheetahCSS::render();
		$css .= ob_get_clean();

		// Global node css
		foreach ( array(
			array( 'row_margins', '.ba-cheetah-row-content-wrap { margin: ' ),
			array( 'row_padding', '.ba-cheetah-row-content-wrap { padding: ' ),
			array( 'row_width', '.ba-cheetah-row-fixed-width { max-width: ' ),
			array( 'column_margins', '.ba-cheetah-col-content { margin: ' ),
			array( 'column_padding', '.ba-cheetah-col-content { padding: ' ),
			array( 'module_margins', '.ba-cheetah-module-content { margin: ' ),
		) as $data ) {
			if ( '' !== $global_settings->{ $data[0] } ) {
				$value = preg_replace( self::regex( 'css_unit' ), '', strtolower( $global_settings->{ $data[0] } ) );
				$css  .= $data[1] . esc_attr( $value );
				$css  .= ( is_numeric( $value ) ) ? ( $global_settings->{ $data[0] . '_unit' } . '; }' ) : ( '; }' );
			}
		}

		// Responsive layout css
		if ( $global_settings->responsive_enabled ) {

			// Medium devices
			$css .= '@media (max-width: ' . $global_settings->medium_breakpoint . 'px) { ';

			// Core medium layout css
			$css .= ba_cheetah_filesystem()->file_get_contents( BA_CHEETAH_DIR . 'css/ba-cheetah-layout-medium.css' );

			// Global node medium css
			foreach ( array(
				array( 'row_margins_medium', '.ba-cheetah-row[data-node] > .ba-cheetah-row-content-wrap { margin: ' ),
				array( 'row_padding_medium', '.ba-cheetah-row[data-node] > .ba-cheetah-row-content-wrap { padding: ' ),
				array( 'column_margins_medium', '.ba-cheetah-col[data-node] > .ba-cheetah-col-content { margin: ' ),
				array( 'column_padding_medium', '.ba-cheetah-col[data-node] > .ba-cheetah-col-content { padding: ' ),
				array( 'module_margins_medium', '.ba-cheetah-module[data-node] > .ba-cheetah-module-content { margin: ' ),
			) as $data ) {
				if ( '' !== $global_settings->{ $data[0] } ) {
					$value = preg_replace( self::regex( 'css_unit' ), '', strtolower( $global_settings->{ $data[0] } ) );
					$css  .= $data[1] . esc_attr( $value );
					$css  .= ( is_numeric( $value ) ) ? ( $global_settings->{ $data[0] . '_unit' } . '; }' ) : ( '; }' );
				}
			}

			$css .= ' }';

			// Responsive devices
			$css .= '@media (max-width: ' . $global_settings->responsive_breakpoint . 'px) { ';

			// Core responsive layout css
			$css .= ba_cheetah_filesystem()->file_get_contents( BA_CHEETAH_DIR . 'css/ba-cheetah-layout-responsive.css' );

			// Auto spacing
			if ( ! isset( $global_settings->auto_spacing ) || $global_settings->auto_spacing ) {
				$css .= ba_cheetah_filesystem()->file_get_contents( BA_CHEETAH_DIR . 'css/ba-cheetah-layout-auto-spacing.css' );
			}

			// Global node responsive css
			foreach ( array(
				array( 'row_margins_responsive', '.ba-cheetah-row[data-node] > .ba-cheetah-row-content-wrap { margin: ' ),
				array( 'row_padding_responsive', '.ba-cheetah-row[data-node] > .ba-cheetah-row-content-wrap { padding: ' ),
				array( 'column_margins_responsive', '.ba-cheetah-col[data-node] > .ba-cheetah-col-content { margin: ' ),
				array( 'column_padding_responsive', '.ba-cheetah-col[data-node] > .ba-cheetah-col-content { padding: ' ),
				array( 'module_margins_responsive', '.ba-cheetah-module[data-node] > .ba-cheetah-module-content { margin: ' ),
			) as $data ) {
				if ( '' !== $global_settings->{ $data[0] } ) {
					$value = preg_replace( self::regex( 'css_unit' ), '', strtolower( $global_settings->{ $data[0] } ) );
					$css  .= $data[1] . esc_attr( $value );
					$css  .= ( is_numeric( $value ) ) ? ( $global_settings->{ $data[0] . '_unit' } . '; }' ) : ( '; }' );
				}
			}

			$css .= ' }';
		}

		global $post;
		$post_id = isset( $post->ID ) ? $post->ID : false;
		if ( BACheetahModel::is_builder_enabled( $post_id ) ) {
			if ( ! $global_settings->show_default_heading && ! empty( $global_settings->default_heading_selector ) ) {
				$heading_selector = esc_attr( $global_settings->default_heading_selector );
				$css .= '.page ' . $heading_selector;
				$css .= ' { display:none; }';
			}
		}

		return $css;
	}

	/**
	 * Maybe run do_shortcode on CSS/JS if enabled.

	 */
	static public function maybe_do_shortcode( $code, $auto_semicolon = false ) {

		if ( true === apply_filters( 'ba_cheetah_enable_shortcode_css_js', false ) ) {
			$code = do_shortcode( $code );
		}
		if ($auto_semicolon && trim(substr($code, -1) != ';')) {
			$code .= ';';
		}

		return $code;
	}

	/**
	 * Forcing HTTPS in URLs when `BACheetahModel::is_ssl()` returns TRUE
	 *

	 * @param string $content A string where the URLs will be modified.
	 * @return string String with SSL ready URLs.
	 */
	static public function rewrite_css_cache_urls( $content ) {
		if ( BACheetahModel::is_ssl() ) {
			$content = str_ireplace( 'http:', 'https:', $content );
		}

		return $content;
	}

	/**
	 * Regular expressions.
	 *

	 * @param string $scope What regular expression to return?
	 * @return string Regular expression.
	 */
	static public function regex( $scope ) {
		$regex = array(
			'css_unit' => '/[^a-z0-9%.\-]/',
		);

		return ( isset( $regex[ $scope ] ) ) ? $regex[ $scope ] : null;
	}

	/**
	 * Renders the CSS spacing and border properties for a node.
	 *
	 * @param object $node A generic node object.
	 * @param string $prop_type One of [ 'padding', 'margin', 'border' ].
	 * @param string $selector_prefix Optional CSS selector prefix for better overrides.
	 * @return string A CSS string.
	 */
	static public function render_node_spacing( $node = null, $prop_type = '', $selector_prefix = '' ) {
		// Exit early if incorrect parameters
		if ( ! is_object( $node ) || empty( $prop_type ) ) {
			return;
		}

		$prop_type = strtolower( $prop_type );

		// Ensure type is valid
		if ( ! in_array( $prop_type, array( 'margin', 'padding', 'border' ), true ) ) {
			return;
		}

		$global_settings  = BACheetahModel::get_global_settings();
		$settings         = $node->settings;
		$css              = '';
		$selector_prefix .= ' .ba-cheetah-node-' . $node->node;

		// Determine selector suffix to apply spacing to
		switch ( $node->type ) {
			case 'row':
			case 'row-inside':
				$selector_suffix = ' > .ba-cheetah-row-content-wrap';
				break;
			case 'column':
				$selector_suffix = ' > .ba-cheetah-col-content';
				break;
			case 'module':
				$selector_suffix = ' > .ba-cheetah-module-content';
				break;
		}

		// Create rules for each breakpoint
		foreach ( array( 'default', 'medium', 'responsive' ) as $breakpoint ) {
			$breakpoint_css = '';
			$setting_suffix = ( 'default' !== $breakpoint ) ? '_' . $breakpoint : '';

			// Iterate over each direction
			foreach ( array( 'top', 'right', 'bottom', 'left' ) as $dir ) {
				$setting_key = $prop_type . '_' . $dir . $setting_suffix;
				$unit_key    = $prop_type . $setting_suffix . '_unit';
				$unit        = isset( $settings->{ $unit_key } ) ? $settings->{ $unit_key } : 'px';

				if ( ! isset( $settings->{ $setting_key } ) ) {
					continue;
				}

				$prop  = $prop_type . '-' . $dir;
				$value = preg_replace( self::regex( 'css_unit' ), '', strtolower( $settings->{ $setting_key } ) );

				if ( 'border' === $prop_type ) {

					if ( empty( $settings->border_type ) ) {
						continue;
					} else {
						$prop .= '-width';
					}
				}

				if ( '' !== $value ) {
					$breakpoint_css .= "\t";
					$breakpoint_css .= $prop . ':' . esc_attr( $value );
					$breakpoint_css .= ( is_numeric( trim( $value ) ) ) ? ( $unit . ';' ) : ( ';' );
					$breakpoint_css .= "\r\n";
				}
			}

			if ( ! empty( $breakpoint_css ) ) {

				// Build the selector
				if ( 'default' !== $breakpoint ) {
					$selector = $selector_prefix . '.ba-cheetah-' . str_replace( 'column', 'col', $node->type ) . $selector_suffix;
				} else {
					$selector = $selector_prefix . $selector_suffix;
				}

				// Wrap css in selector
				$breakpoint_css = $selector . ' {' . "\r\n" . $breakpoint_css . '}' . "\r\n";

				// Wrap css in media query
				if ( 'default' !== $breakpoint ) {
					$breakpoint_css = '@media ( max-width: ' . $global_settings->{ $breakpoint . '_breakpoint' } . 'px ) {' . "\r\n" . $breakpoint_css . '}' . "\r\n";
				}

				$css .= $breakpoint_css;
			}
		}

		return $css;
	}

	/**
	 * Renders the CSS margins for a row.
	 *

	 * @param object $row A row node object.
	 * @return string The row CSS margins string.
	 */
	static public function render_row_margins( $row ) {
		return self::render_node_spacing( $row, 'margin' );
	}

	/**
	 * Renders the CSS padding for a row.
	 *

	 * @param object $row A row node object.
	 * @return string The row CSS padding string.
	 */
	static public function render_row_padding( $row ) {
		return self::render_node_spacing( $row, 'padding' );
	}

	/**
	 * Renders the CSS margins for a column.
	 *

	 * @param object $col A column node object.
	 * @return string The column CSS margins string.
	 */
	static public function render_column_margins( $col ) {
		return self::render_node_spacing( $col, 'margin' );
	}

	/**
	 * Renders the CSS padding for a column.
	 *

	 * @param object $col A column node object.
	 * @return string The column CSS padding string.
	 */
	static public function render_column_padding( $col ) {
		return self::render_node_spacing( $col, 'padding' );
	}

	/**
	 * Renders the CSS margins for a module.
	 *

	 * @param object $module A module node object.
	 * @return string The module CSS margins string.
	 */
	static public function render_module_margins( $module ) {
		return self::render_node_spacing( $module, 'margin' );
	}

	/**
	 * Renders the (auto) responsive CSS margins for a module.
	 *

	 * @param object $module A module node object.
	 * @return string The module CSS margins string.
	 */
	static public function render_responsive_module_margins( $module ) {
		$global_settings = BACheetahModel::get_global_settings();
		$settings        = $module->settings;
		$margins         = '';
		$css             = '';

		// Bail early if we have global responsive margins.
		if ( '' != $global_settings->module_margins_responsive ) {
			return $css;
		}

		// Get the global default margin value to use.
		if ( '' != $global_settings->module_margins_medium ) {
			$default = trim( $global_settings->module_margins_medium );
		} else {
			$default = trim( $global_settings->module_margins );
		}

		// Set the responsive margin CSS if necessary.
		foreach ( array( 'top', 'bottom', 'left', 'right' ) as $dimension ) {

			$responsive = 'margin_' . $dimension . '_responsive';
			$medium     = 'margin_' . $dimension . '_responsive';
			$desktop    = 'margin_' . $dimension;

			if ( '' == $settings->$responsive ) {

				$value = '' == $settings->$medium ? $settings->$desktop : $settings->$medium;

				if ( '' != $value && ( $value > $default || $value < 0 ) ) {
					$margins .= 'margin-' . $dimension . ':' . esc_attr( $default ) . 'px;';
				}
			}
		}

		// Set the media query if we have margins.
		if ( '' !== $margins ) {
			$css .= '@media (max-width: ' . esc_attr( $global_settings->responsive_breakpoint ) . 'px) { ';
			$css .= '.ba-cheetah-node-' . $module->node . ' > .ba-cheetah-module-content { ' . $margins . ' }';
			$css .= ' }';
		}

		return $css;
	}

	/**
	 * Renders the animation CSS for a node if it has an animation.
	 *

	 * @param object $settings A node settings object.
	 * @return string A CSS string.
	 */
	static public function render_node_animation_css( $settings ) {
		$css = '';

		if ( ! is_array( $settings->animation ) || empty( $settings->animation ) ) {
			return $css;
		} elseif ( in_array( 'animation-' . $settings->animation['style'], self::$enqueued_global_assets ) ) {
			return $css;
		}

		self::$enqueued_global_assets[] = 'animation-' . $settings->animation['style'];
		$path                           = BA_CHEETAH_DIR . 'css/animations/' . $settings->animation['style'] . '.css';

		if ( file_exists( $path ) ) {
			$css = file_get_contents( $path );
		}

		return $css;
	}

	/**
	 * Renders all animation CSS for use in the builder UI.
	 *

	 * @return string A CSS string.
	 */
	static public function render_all_animation_css() {
		$css        = '';
		$animations = glob( BA_CHEETAH_DIR . 'css/animations/*.css' );

		if ( ! is_array( $animations ) ) {
			return $css;
		}

		foreach ( $animations as $path ) {
			$key = basename( $path, '.css' );

			if ( in_array( 'animation-' . $key, self::$enqueued_global_assets ) ) {
				continue;
			}

			self::$enqueued_global_assets[] = 'animation-' . $key;
			$css                           .= file_get_contents( $path );
		}

		return $css;
	}

	/**
	 * Renders and caches the JavaScript for a builder layout.
	 *

	 * @param bool $include_global
	 * @return string
	 */
	static public function render_js( $include_global = true ) {
		// Get info on the new file.
		$nodes           = BACheetahModel::get_categorized_nodes();
		$global_settings = BACheetahModel::get_global_settings();
		$layout_settings = BACheetahModel::get_layout_settings();
		$page_settings 	 = BACheetahModel::get_page_settings();
		$rows            = BACheetahModel::get_nodes( 'row' );
		$asset_info      = BACheetahModel::get_asset_info();
		$enqueuemethod   = BACheetahModel::get_asset_enqueue_method();
		$js              = '';
		$path            = $include_global ? $asset_info['js'] : $asset_info['js_partial'];

		// Render the global js.
		if ( $include_global ) {
			$js .= self::render_global_js();
		}

		// Page js
		if (get_post_type() == 'page' && is_main_query()) {
			ob_start();
			include BA_CHEETAH_DIR . 'includes/page-js.php';
			$js .= ob_get_clean();
		}

		// Loop through the rows.
		foreach ( $nodes['rows'] as $row ) {
			$js .= self::render_row_js( $row );
		}

		// Loop through the modules.
		foreach ( $nodes['modules'] as $module ) {
			$js .= self::render_module_js( $module );
		}

		

		// Add the layout settings JS.
		if ( ! isset( $_GET['safemode'] ) ) {
			$js .= self::js_comment( 'Global Node Custom JS', self::maybe_do_shortcode( self::render_global_nodes_custom_code( 'js' ) ) );
			$js .= ( is_array( $layout_settings->js ) || is_object( $layout_settings->js ) )
				? self::js_comment( 'Layout Custom JS', self::maybe_do_shortcode( json_encode( $layout_settings->js ), true ) )
				: self::js_comment( 'Layout Custom JS', self::maybe_do_shortcode( $layout_settings->js, true ) );
		}

		// Call the BACheetah._renderLayoutComplete method if we're currently editing.
		if ( stristr( $asset_info['js'], '-draft.js' ) || stristr( $asset_info['js'], '-preview.js' ) ) {
			$js .= "; if(typeof BACheetah !== 'undefined' && typeof BACheetah._renderLayoutComplete !== 'undefined') BACheetah._renderLayoutComplete();";
		}

		// Include BACheetahJSMin
		if ( ! class_exists( 'BACheetahJSMin' ) ) {
			include BA_CHEETAH_DIR . 'classes/class-ba-jsmin.php';
		}

		/**
		 * Use this filter to modify the JavaScript that is compiled and cached for each builder layout.
		 * @see ba_cheetah_render_js
		 */
		$js = apply_filters( 'ba_cheetah_render_js', $js, $nodes, $global_settings, $include_global );

		// Only proceed if we have JS.
		if ( ! empty( $js ) ) {

			// Minify the JS.
			if ( ! self::is_debug() ) {
				try {
					$min = BACheetahJSMin::minify( $js );
				} catch ( Exception $e ) {
				}

				if ( isset( $min ) ) {
					$js = $min;
				}
			}

			// Save the JS.
			if ( 'file' === $enqueuemethod ) {
				ba_cheetah_filesystem()->file_put_contents( $path, $js );
			}

			/**
			 * After JS is compiled.
			 * @see ba_cheetah_after_render_js
			 */
			do_action( 'ba_cheetah_after_render_js' );
		}

		return $js;
	}

	/**
	 * Render a row to a module, create a new if necessary.
	 *

	 * @param string $nodeID A row node ID.
	 * @param string $parentID A parent module ID.
	 * @return string
	 */
	static public function render_row_to_module($nodeID = null, $parentID = null, $module_slug = '', $index = 0) {
		if (empty($nodeID)) {
			if(BACheetahModel::is_builder_active()) {
				echo '<strong>*** The atribute "row_node_id" is missing. Please review your code and add it! ***</Strong>';
			}
		} else {
			$rowRender = BACheetahModel::get_node($nodeID);

			if(!is_object($rowRender) || empty($rowRender)) {
				$rowRender = BACheetahModel::add_row_to_module($nodeID, $parentID, $module_slug, $index);
			}

			self::render_row( $rowRender );

		}
	}

	/**
	 * Renders the JS used for all builder layouts.
	 *

	 * @return string
	 */
	static public function render_global_js() {
		$global_settings = BACheetahModel::get_global_settings();
		$js              = '';

		// Add the path legacy vars (BACheetahLayoutConfig.paths should be used instead).
		$js .= "var wpAjaxUrl = '" . admin_url( 'admin-ajax.php' ) . "';";
		$js .= "var baCheetahUrl = '" . BA_CHEETAH_URL . "';";

		// Layout config object.
		ob_start();
		include BA_CHEETAH_DIR . 'includes/layout-js-config.php';
		$js .= ob_get_clean();

		// Core layout JS.
		$js .= ba_cheetah_filesystem()->file_get_contents( BA_CHEETAH_DIR . 'js/ba-cheetah-layout.js' );

		// Add the global settings JS.
		if ( ! isset( $_GET['safemode'] ) ) {
			$js .= self::js_comment( 'Global JS', self::maybe_do_shortcode( $global_settings->js, true ) );
		}
		return $js;
	}

	static public function js_comment( $comment, $js ) {

		$js = sprintf( "\n/* Start %s */\n%s\n/* End %s */\n\n", $comment, $js, $comment );
		return $js;
	}

	/**
	 * Renders the JavaScript for a single row.
	 *

	 * @param string|object $row_id A row ID or object.
	 * @return string
	 */
	static public function render_row_js( $row_id ) {
		$row      = is_object( $row_id ) ? $row_id : BACheetahModel::get_node( $row_id );
		$settings = $row->settings;
		$id       = $row->node;

		ob_start();
		include BA_CHEETAH_DIR . 'includes/row-js.php';
		return ob_get_clean();
	}

	/**
	 * Renders the JavaScript for all modules in a single row.
	 *

	 * @param string|object $row_id A row ID or object.
	 * @return string
	 */
	static public function render_row_modules_js( $row_id ) {
		$row              = is_object( $row_id ) ? $row_id : BACheetahModel::get_node( $row_id );
		$nodes            = BACheetahModel::get_categorized_nodes();
		$template_post_id = BACheetahModel::is_node_global( $row );
		$js               = '';

		// Render the JS.
		foreach ( $nodes['groups'] as $group ) {
			if ( $row->node == $group->parent || ( $template_post_id && $row->template_node_id == $group->parent ) ) {
				foreach ( $nodes['columns'] as $column ) {
					if ( $group->node == $column->parent ) {
						foreach ( $nodes['modules'] as $module ) {
							if ( $column->node == $module->parent ) {
								$js .= self::render_module_js( $module );
							}
						}
					}
				}
			}
		}

		// Return the JS.
		return $js;
	}

	/**
	 * Renders the JavaScript for all modules in a single column group.
	 *

	 * @param string|object $group_id A row ID or object.
	 * @return string
	 */
	static public function render_column_group_modules_js( $group_id ) {
		$group = is_object( $group_id ) ? $group_id : BACheetahModel::get_node( $group_id );
		$nodes = BACheetahModel::get_categorized_nodes();
		$js    = '';

		// Render the JS.
		foreach ( $nodes['columns'] as $column ) {
			if ( $group->node == $column->parent ) {
				foreach ( $nodes['modules'] as $module ) {
					if ( $column->node == $module->parent ) {
						$js .= self::render_module_js( $module );
					}
				}
			}
		}

		// Return the JS.
		return $js;
	}

	/**
	 * Renders the JavaScript for all modules in a single column.
	 *

	 * @param string|object $col_id A column ID or object.
	 * @return string
	 */
	static public function render_column_modules_js( $col_id ) {
		$col   = is_object( $col_id ) ? $col_id : BACheetahModel::get_node( $col_id );
		$nodes = BACheetahModel::get_categorized_nodes();
		$js    = '';

		// Render the JS.
		foreach ( $nodes['modules'] as $module ) {
			if ( $col->node == $module->parent ) {
				$js .= self::render_module_js( $module );
			}
		}

		// Return the JS.
		return $js;
	}

	/**
	 * Renders the JavaScript for a single module.
	 *

	 * @param string|object $module_id A module ID or object.
	 * @return string
	 */
	static public function render_module_js( $module_id ) {
		$module          = is_object( $module_id ) ? $module_id : BACheetahModel::get_module( $module_id );
		$global_settings = BACheetahModel::get_global_settings();
		$js              = '';

		// Global module JS
		$file = $module->dir . 'js/frontend.js';

		if ( ba_cheetah_filesystem()->file_exists( $file ) && ! in_array( $module->settings->type . '-module-js', self::$enqueued_global_assets ) ) {
			$js                            .= "\n" . ba_cheetah_filesystem()->file_get_contents( $file );
			self::$enqueued_global_assets[] = $module->settings->type . '-module-js';
		}

		// Instance module JS
		$file     = $module->dir . 'includes/frontend.js.php';
		$settings = $module->settings;
		$id       = $module->node;

		if ( ! in_array( $id, self::$enqueued_module_js_assets ) && ba_cheetah_filesystem()->file_exists( $file ) ) {
			self::$enqueued_module_js_assets[] = $id;
			ob_start();
			include $file;
			$js .= ob_get_clean();
		}

		// Return the JS.
		return $js;
	}

	/**
	 * Renders the custom CSS or JS for all global nodes in a layout.
	 *

	 */
	static public function render_global_nodes_custom_code( $type = 'css' ) {
		$code     = '';
		$rendered = array();

		if ( ! BACheetahModel::is_post_node_template() ) {

			$nodes       = BACheetahModel::get_layout_data();
			$node_status = BACheetahModel::get_node_status();

			foreach ( $nodes as $node_id => $node ) {

				$template_post_id = BACheetahModel::is_node_global( $node );

				if ( $template_post_id && ! in_array( $template_post_id, $rendered ) ) {

					$rendered[] = $template_post_id;
					$code      .= BACheetahModel::get_layout_settings( $node_status, $template_post_id )->{ $type };
				}
			}
		}

		return $code;
	}

	/**
	 * Check if publish should require page to refresh.
	 *

	 * @return void
	 */
	static public function should_refresh_on_publish() {
		$refresh = ! is_admin_bar_showing();
		return apply_filters( 'ba_cheetah_should_refresh_on_publish', $refresh );
	}

	/**
	 * Register svg shape art to be used in a shape layer
	 *

	 * @param Array $args
	 * @return void
	 */
	static public function register_shape( $args = array() ) {
		BACheetahArt::register_shape( $args );
	}

	/**
	 * Custom logging function that handles objects and arrays.
	 *

	 * @return void
	 */
	static public function log() {
		foreach ( func_get_args() as $arg ) {
			ob_start();
			print_r( $arg );
			error_log( ob_get_clean() );
		}
	}

	/**
	 * Filter WP uploads and check filetype is valid for photo and video modules.

	 */
	static public function wp_handle_upload_prefilter_filter( $file ) {

		$type = isset( $_POST['ba_cheetah_upload_type'] ) ? sanitize_key($_POST['ba_cheetah_upload_type']) : false;

		$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

		$regex = array(
			'photo' => '#(jpe?g|png|gif|bmp|webp|tiff?)#i',
			'video' => '#(mp4|m4v|webm)#i',
		);

		if ( ! $type ) {
			return $file;
		}

		$regex = apply_filters( 'ba_cheetah_module_upload_regex', $regex, $type, $ext, $file );

		if ( ! preg_match( $regex[ $type ], $ext ) ) {
			/* translators: %s: extension type */
			$file['error'] = sprintf( __( 'The uploaded file is not a valid %s extension.', 'ba-cheetah' ), $type );
		}

		return $file;
	}

	/**
	 * Default HTML for no image.

	 * @return string
	 */
	static public function default_image_html( $classes ) {
		return sprintf( '<img src="%s" class="%s" />', BA_CHEETAH_URL . 'img/no-image.png', $classes );
	}

	/**
	 * Check if debug is enabled.

	 * @return bool
	 */
	static public function is_debug() {

		$debug = false;

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$debug = true;
		}

		return apply_filters( 'ba_cheetah_is_debug', $debug );
	}

	/**
	 * Get the fa5 url.

	 * @return string url
	 */
	static public function get_fa5_url() {

		/**
		 * Enable the PRO font-awesome-5 icon set.
		 * This will also enqueue the CSS from the CDN.
		 * @see ba_cheetah_enable_fa5_pro
		 */
		$url = ( self::fa5_pro_enabled() ) ? self::$fa5_pro_url : plugins_url( '/fonts/fontawesome/' . self::get_fa5_version() . '/css/all.min.css', BA_CHEETAH_FILE );

		/**
		 * Filter FA5 URL for enqueue.
		 * @see ba_cheetah_get_fa5_url

		 */
		return apply_filters( 'ba_cheetah_get_fa5_url', $url );
	}

	static public function get_fa5_version() {
		$data = glob( BA_CHEETAH_DIR . 'fonts/fontawesome/*' );
		return basename( $data[0] );
	}

	static public function fa5_pro_enabled() {
		$enabled = apply_filters( 'ba_cheetah_enable_fa5_pro', false );
		// if filter was set to true return true anyway.
		if ( $enabled ) {
			return true;
		}

		if ( is_multisite() && BACheetahAdminSettings::multisite_support() ) {
			// if switched...
			if ( $GLOBALS['switched'] ) {
				if ( get_blog_option( $GLOBALS['_wp_switched_stack'][0], '_ba_cheetah_enable_fa_pro' ) ) {
					// override enabled...
					return get_blog_option( $GLOBALS['_wp_switched_stack'][0], '_ba_cheetah_enable_fa_pro' );
				} else {
					return get_option( '_ba_cheetah_enable_fa_pro' );
				}
			}

			// were not switched...
			if ( ! get_option( '_ba_cheetah_enabled_icons' ) ) {
				$id = defined( 'BLOG_ID_CURRENT_SITE' ) ? BLOG_ID_CURRENT_SITE : 1;
				return get_blog_option( $id, '_ba_cheetah_enable_fa_pro' );
			}
		}
		return BACheetahModel::get_admin_settings_option( '_ba_cheetah_enable_fa_pro' );
	}

	/**

	 */
	static public function fa5_kit_url() {

		if ( is_multisite() && BACheetahAdminSettings::multisite_support() ) {
			// if switched...
			if ( $GLOBALS['switched'] ) {
				if ( get_blog_option( $GLOBALS['_wp_switched_stack'][0], '_ba_cheetah_kit_fa_pro' ) ) {
					// override enabled...
					return get_blog_option( $GLOBALS['_wp_switched_stack'][0], '_ba_cheetah_kit_fa_pro' );
				} else {
					return get_option( '_ba_cheetah_kit_fa_pro' );
				}
			}

			// were not switched...
			if ( ! get_option( '_ba_cheetah_enabled_icons' ) ) {
				$id = defined( 'BLOG_ID_CURRENT_SITE' ) ? BLOG_ID_CURRENT_SITE : 1;
				return get_blog_option( $id, '_ba_cheetah_kit_fa_pro' );
			}
		}
		return BACheetahModel::get_admin_settings_option( '_ba_cheetah_kit_fa_pro' );
	}

	/**
	 * Remove template type from wp-link suggestions.

	 */
	static public function wp_link_query_args_filter( $query ) {

		if ( array_search( 'ba-cheetah-template', $query['post_type'] ) ) {
			unset( $query['post_type'][ array_search( 'ba-cheetah-template', $query['post_type'] ) ] );
		}
		return $query;
	}

	/**

	 */
	static public function is_schema_enabled() {

		/**
		 * Disable all schema.
		 * @see ba_cheetah_disable_schema
		 */
		if ( false !== apply_filters( 'ba_cheetah_disable_schema', false ) ) {
			return false;
		} else {
			return true;
		}
	}

	/**

	 */
	static public function print_schema( $schema, $echo = true ) {
		if ( self::is_schema_enabled() ) {
			if ( $echo ) {
				echo $schema;
			} else {
				return $schema;
			}
		}
	}

	/**


	 */
	static public function layout_styles_scripts( $post_id ) {
		_deprecated_function( __METHOD__, '1.7.4', __CLASS__ . '::enqueue_layout_styles_scripts()' );

		self::enqueue_layout_styles_scripts();
	}

	/**


	 */
	static public function styles_scripts() {
		_deprecated_function( __METHOD__, '1.7.4', __CLASS__ . '::enqueue_ui_styles_scripts()' );

		self::enqueue_ui_styles_scripts();
	}

	/**


	 */
	static public function register_templates_post_type() {
		_deprecated_function( __METHOD__, '1.8', 'BACheetahUserTemplates::register_post_type()' );

		if ( class_exists( 'BACheetahUserTemplates' ) ) {
			BACheetahUserTemplates::register_post_type();
		}
	}

	/**


	 */
	static public function render_template( $template ) {
		_deprecated_function( __METHOD__, '1.8', 'BACheetahUserTemplates::template_include()' );

		if ( class_exists( 'BACheetahUserTemplates' ) ) {
			BACheetahUserTemplates::template_include();
		}
	}

	/**


	 */
	static public function render_ui_panel_node_templates() {
		_deprecated_function( __METHOD__, '1.8', 'BACheetahUserTemplates::render_ui_panel_node_templates()' );

		if ( class_exists( 'BACheetahUserTemplates' ) ) {
			BACheetahUserTemplates::render_ui_panel_node_templates();
		}
	}

	/**


	 */
	static public function render_user_template_settings() {
		_deprecated_function( __METHOD__, '1.8', 'BACheetahUserTemplates::render_settings()' );

		if ( class_exists( 'BACheetahUserTemplates' ) ) {
			BACheetahUserTemplates::render_settings();
		}
	}

	/**


	 */
	static public function render_node_template_settings( $node_id = null ) {
		_deprecated_function( __METHOD__, '1.8', 'BACheetahUserTemplates::render_node_settings()' );

		if ( class_exists( 'BACheetahUserTemplates' ) ) {
			BACheetahUserTemplates::render_node_settings( $node_id );
		}
	}

	/**


	 */
	static public function render_template_selector() {
		_deprecated_function( __METHOD__, '2.0' );

		return array(
			'html' => '',
		);
	}

	/**


	 */
	static public function render_ui_panel_row_templates() {
		_deprecated_function( __METHOD__, '2.0' );
	}

	/**


	 */
	static public function render_ui_panel_modules_templates() {
		_deprecated_function( __METHOD__, '2.0' );
	}

	/**


	 */
	static public function render_layout_settings() {
		_deprecated_function( __METHOD__, '2.0' );
	}

	/**


	 */
	static public function render_global_settings() {
		_deprecated_function( __METHOD__, '2.0' );
	}

	/**


	 */
	static public function render_row_settings( $node_id = null ) {
		_deprecated_function( __METHOD__, '2.0' );
	}

	/**


	 */
	static public function render_column_settings( $node_id = null ) {
		_deprecated_function( __METHOD__, '2.0' );
	}

	/**


	 */
	static public function render_module_settings( $node_id = null, $type = null, $parent_id = null, $render_state = true ) {
		_deprecated_function( __METHOD__, '2.0' );
	}

	/**


	 */
	static public function render_settings_config() {
		_deprecated_function( __METHOD__, '2.0.7', 'BACheetahUISettingsForms::render_settings_config()' );

		BACheetahUISettingsForms::render_settings_config();
	}

	/**


	 */
	static public function render_row_border( $row ) {
		_deprecated_function( __METHOD__, '2.2', 'BACheetahCSS::responsive_rule()' );
	}

	/**


	 */
	static public function render_column_border( $col ) {
		_deprecated_function( __METHOD__, '2.2', 'BACheetahCSS::responsive_rule()' );
	}

	/**


	 */
	static public function include_jquery() {
		_deprecated_function( __METHOD__, '2.2' );
	}

	static public function render_edit_link($id, $text = 'Edit') {
		$post = get_post($id);
		$url = get_permalink($post->ID);

		if(
			!$post
			or BACheetahModel::is_builder_active()
			or !current_user_can('edit_post', $post->ID)
			or !$url
		) {
			return;
		}

		$text = __($text);
		//$url .= '?ba_cheetah';
		$url .= '?ba_builder';
		$link = '<a target="_blank" class="ba-cheetahpost-edit-link" href="' . esc_url($url) . '"> <i class="dashicons dashicons-edit"></i>' . $text . '</a>';

		echo '<div class="ba-cheetahpost-edit-link-container">';
		echo $link;
		echo '</div>';
	}

	static public function render_watermark()
	{

		$baConfig = get_option( '_ba_cheetah_watermark' );

		if($baConfig) {
			$position = $baConfig['position'];
			$showWatermark = $baConfig['show'];
		} else {
			$position = 'left';
			$showWatermark = true;
		}

		if($showWatermark) {
			$watermark =  '<div class="ba-cheetah-watermark ' . $position . '">';

			$imgPowered = BA_CHEETAH_URL . "img/powered_by.png";
			$link = 'http://www.builderall.com';

			$user = BACheetahAuthentication::user();
			if ($user) {

				try {
					$urlCall = 'https://office.builderall.com/us/office/powered-by-builderall/' . $user['email'];

					$json = file_get_contents($urlCall);
					$returnUser = json_decode($json, TRUE);

					$imgPowered = $returnUser["data"]["image"];
					$link = $returnUser["data"]["target"];

				} catch (\Throwable $th) {
					$imgPowered = BA_CHEETAH_URL . "img/powered_by.png";
				}
			}

			$watermark .=  '	<a href="' . $link . '" target="_blank">';
			$watermark .= '			<img src="' . $imgPowered . '" />';
			$watermark .= ' 	</a>';
			$watermark .= '</div>';

			echo $watermark;
		}
	}

    public static function apply_scroll_animation($module, array &$attrs) {
        if (!empty($module->settings->vertical_effect) && ($module->settings->vertical_effect === 'active')) {
            $attrs['data-scroll-animation-vertical-effect'][] = $module->settings->vertical_effect;
            $attrs['data-scroll-animation-vertical-direction'][] = $module->settings->vertical_effect_direction;
            $attrs['data-scroll-animation-vertical-viewport-initial'][] = $module->settings->vertical_viewport['initialValue'];
            $attrs['data-scroll-animation-vertical-viewport-final'][] = $module->settings->vertical_viewport['finalValue'];
            $attrs['data-scroll-animation-vertical-viewport-speed'][] = $module->settings->vertical_viewport['speed'];

            $attrs['class'][] = 'ba-cheetah-scroll-animation';
        }
        if (!empty($module->settings->horizontal_effect) && ($module->settings->horizontal_effect === 'active')) {
            $attrs['data-scroll-animation-horizontal-effect'][] = $module->settings->horizontal_effect;
            $attrs['data-scroll-animation-horizontal-direction'][] = $module->settings->horizontal_effect_direction;
            $attrs['data-scroll-animation-horizontal-viewport-initial'][] = $module->settings->horizontal_viewport['initialValue'];
            $attrs['data-scroll-animation-horizontal-viewport-final'][] = $module->settings->horizontal_viewport['finalValue'];
            $attrs['data-scroll-animation-horizontal-viewport-speed'][] = $module->settings->horizontal_viewport['speed'];

            $attrs['class'][] = 'ba-cheetah-scroll-animation';
        }
        if (!empty($module->settings->rotate_effect) && ($module->settings->rotate_effect === 'active')) {
            $attrs['data-scroll-animation-rotate-effect'][] = $module->settings->rotate_effect;
            $attrs['data-scroll-animation-rotate-direction'][] = $module->settings->rotate_effect_direction;
            $attrs['data-scroll-animation-rotate-viewport-initial'][] = $module->settings->rotate_viewport['initialValue'];
            $attrs['data-scroll-animation-rotate-viewport-final'][] = $module->settings->rotate_viewport['finalValue'];
            $attrs['data-scroll-animation-rotate-viewport-speed'][] = $module->settings->rotate_viewport['speed'];

            $attrs['class'][] = 'ba-cheetah-scroll-animation';
        }
        if (!empty($module->settings->scale_effect) && ($module->settings->scale_effect === 'active')) {
            $attrs['data-scroll-animation-scale-effect'][] = $module->settings->scale_effect;
            $attrs['data-scroll-animation-scale-direction'][] = $module->settings->scale_effect_direction;
            $attrs['data-scroll-animation-scale-viewport-initial'][] = $module->settings->scale_viewport['initialValue'];
            $attrs['data-scroll-animation-scale-viewport-final'][] = $module->settings->scale_viewport['finalValue'];
            $attrs['data-scroll-animation-scale-viewport-speed'][] = $module->settings->scale_viewport['speed'];

            $attrs['class'][] = 'ba-cheetah-scroll-animation';
        }
        if (!empty($module->settings->blur_effect) && ($module->settings->blur_effect === 'active')) {
            $attrs['data-scroll-animation-blur-effect'][] = $module->settings->blur_effect;
            $attrs['data-scroll-animation-blur-direction'][] = $module->settings->blur_effect_direction;
            $attrs['data-scroll-animation-blur-viewport-initial'][] = $module->settings->blur_viewport['initialValue'];
            $attrs['data-scroll-animation-blur-viewport-final'][] = $module->settings->blur_viewport['finalValue'];
            $attrs['data-scroll-animation-blur-viewport-speed'][] = $module->settings->blur_viewport['speed'];

            $attrs['class'][] = 'ba-cheetah-scroll-animation';
        }
        if (!empty($module->settings->fade_effect) && ($module->settings->fade_effect === 'active')) {
            $attrs['data-scroll-animation-fade-effect'][] = $module->settings->fade_effect;
            $attrs['data-scroll-animation-fade-direction'][] = $module->settings->fade_effect_direction;
            $attrs['data-scroll-animation-fade-viewport-initial'][] = $module->settings->fade_viewport['initialValue'];
            $attrs['data-scroll-animation-fade-viewport-final'][] = $module->settings->fade_viewport['finalValue'];
            $attrs['data-scroll-animation-fade-viewport-speed'][] = $module->settings->fade_viewport['speed'];

            $attrs['class'][] = 'ba-cheetah-scroll-animation';
        }
        if (!empty($module->settings->mouse_effect) && ($module->settings->mouse_effect === 'active')) {
            $attrs['data-scroll-animation-mouse-effect'][] = $module->settings->mouse_effect;
            $attrs['data-scroll-animation-mouse-direction'][] = $module->settings->mouse_effect_direction;
            $attrs['data-scroll-animation-mouse-speed'][] = $module->settings->mouse_speed;

            $attrs['class'][] = 'ba-cheetah-scroll-animation-mouse';
        }
        if (!empty($module->settings->mouse_effect_3d) && ($module->settings->mouse_effect_3d === 'active')) {
            $attrs['data-scroll-animation-mouse-effect-perspective'][] = $module->settings->mouse_effect_3d;
            $attrs['data-scroll-animation-mouse-speed-perspective'][] = $module->settings->mouse_speed_3d;

            $attrs['class'][] = 'ba-cheetah-scroll-animation-mouse';
        }
    }

}

BACheetah::init();
