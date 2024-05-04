<?php

/**
 * Various fixes for various plugins.
 *

 */
final class BACheetahCompatibility {

	public static function init() {

		// Actions
		add_action( 'after_setup_theme', array( __CLASS__, 'pro_icons_enable' ) );
		add_action( 'after_setup_theme', array( __CLASS__, 'fix_woof_posts_module' ) );
		add_action( 'ba_cheetah_photo_cropped', array( __CLASS__, 'tinypng_support' ), 10, 2 );
		add_action( 'plugins_loaded', array( __CLASS__, 'wc_memberships_support' ), 11 );
		add_action( 'plugins_loaded', array( __CLASS__, 'admin_ssl_upload_fix' ), 11 );
		add_action( 'plugins_loaded', __CLASS__ . '::popup_builder' );
		add_action( 'added_post_meta', array( __CLASS__, 'template_meta_add' ), 10, 4 );
		add_action( 'ba_cheetah_insert_layout_render', array( __CLASS__, 'insert_layout_render_search' ), 10, 3 );
		add_action( 'ba_cheetah_fa_pro_save', array( __CLASS__, 'clear_theme_cache' ) );
		add_action( 'wp', array( __CLASS__, 'ee_suppress_notices' ) );
		add_action( 'ba_cheetah_ajax_before_call_action', array( __CLASS__, 'ee_before_ajax' ) );
		add_action( 'plugins_loaded', array( __CLASS__, 'fix_nextgen_gallery' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_tasty_recipes' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_generatepress_fa5' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_hummingbird' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_enjoy_instagram' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_templator' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_protector_gold' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_smush_it' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_frontend_dashboard_plugin' ), 1000 );
		add_action( 'template_redirect', array( __CLASS__, 'fix_um_switcher' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_pipedrive' ) );
		add_action( 'template_redirect', array( __CLASS__, 'fix_klaviyo_themer_layout' ) );
		add_action( 'template_redirect', array( __CLASS__, 'aggiungi_script_instafeed_owl' ), 1000 );
		add_action( 'template_redirect', array( __CLASS__, 'fix_happyfoxchat' ) );
		add_action( 'tribe_events_pro_widget_render', array( __CLASS__, 'tribe_events_pro_widget_render_fix' ), 10, 3 );
		add_action( 'wp_footer', array( __CLASS__, 'fix_woo_short_description_footer' ) );
		add_action( 'save_post', array( __CLASS__, 'fix_seopress' ), 9 );
		add_action( 'admin_init', array( __CLASS__, 'fix_posttypeswitcher' ) );
		add_action( 'widgets_init', array( __CLASS__, 'fix_google_reviews_business_widget' ), 11 );
		add_action( 'init', array( __CLASS__, 'fix_google_reviews_business_shortcode' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'gute_links_fix' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'fa_kit_support' ), 99999 );
		add_action( 'ba_cheetah_theme_builder_before_render_header', array( __CLASS__, 'fix_lazyload_header_start' ) );
		add_action( 'ba_cheetah_theme_builder_after_render_header', array( __CLASS__, 'fix_lazyload_header_end' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'ee_remove_stylesheet' ), 99999 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'fix_woocommerce_products_filter' ), 12 );
		add_action( 'pre_get_posts', array( __CLASS__, 'fix_woo_archive_loop' ), 99 );
		add_action( 'pre_get_posts', array( __CLASS__, 'fix_tribe_events_hide_from_listings_archive' ) );
		add_action( 'ba_cheetah_menu_module_before_render', array( __CLASS__, 'fix_menu_module_before_render' ) );
		add_action( 'ba_cheetah_menu_module_after_render', array( __CLASS__, 'fix_menu_module_after_render' ) );

		// Filters
		add_filter( 'ba_cheetah_is_post_editable', array( __CLASS__, 'bp_pages_support' ), 11, 2 );
		add_filter( 'jetpack_photon_skip_image', array( __CLASS__, 'photo_photon_exception' ), 10, 3 );
		add_filter( 'ba_cheetah_render_module_content', array( __CLASS__, 'render_module_content_filter' ), 10, 2 );
		add_filter( 'bwp_minify_is_loadable', array( __CLASS__, 'bwp_minify_is_loadable_filter' ) );
		add_filter( 'ba_cheetah_editor_content', array( __CLASS__, 'activemember_shortcode_fix' ) );
		add_filter( 'ba_cheetah_editor_content', array( __CLASS__, 'imember_shortcode_fix' ) );
		add_filter( 'ba_cheetah_ajax_layout_response', array( __CLASS__, 'render_ninja_forms_js' ) );
		add_filter( 'avf_enqueue_wp_mediaelement', array( __CLASS__, 'not_load_mediaelement' ), 10, 2 );
		add_filter( 'phpcompat_whitelist', array( __CLASS__, 'bb_compat_fix' ) );
		add_filter( 'ba_cheetah_editor_content', array( __CLASS__, 'theme_post_content_fix' ) );
		add_filter( 'ba_cheetah_admin_settings_post_types', array( __CLASS__, 'admin_settings_post_types_popup' ) );
		add_filter( 'woocommerce_product_get_short_description', array( __CLASS__, 'fix_woo_short_description' ) );
		add_filter( 'enlighter_startup', array( __CLASS__, 'enlighter_frontend_editing' ) );
		add_filter( 'option_sumome_site_id', array( __CLASS__, 'fix_sumo' ) );
		add_filter( 'ba_cheetah_admin_edit_sort_blocklist', array( __CLASS__, 'admin_edit_sort_blocklist_edd' ) );
		add_filter( 'option_cookiebot-nooutput', array( __CLASS__, 'fix_cookiebot' ) );
		add_filter( 'ba_cheetah_select2_enabled', array( __CLASS__, 'fix_memberium' ) );
		add_filter( 'option_wp-smush-lazy_load', array( __CLASS__, 'fix_smush' ) );
		add_filter( 'ba_cheetah_row_bg_video_wrapper_class', array( __CLASS__, 'fix_twenty_twenty_video' ) );
		add_filter( 'ba_cheetah_loop_rewrite_rules', array( __CLASS__, 'fix_wpseo_category_pagination_rule' ) );
		add_filter( 'ba_cheetah_loop_rewrite_rules', array( __CLASS__, 'fix_polylang_pagination_rule' ) );
		add_filter( 'ba_cheetah_loop_query_args', array( __CLASS__, 'fix_tribe_events_hide_from_listings' ) );
		add_filter( 'tribe_events_rewrite_rules_custom', array( __CLASS__, 'fix_tribe_events_pagination_rule' ), 10, 3 );
		add_filter( 'aioseo_conflicting_shortcodes', array( __CLASS__, 'aioseo_conflicting_shortcodes' ) );
		add_filter( 'ba_cheetah_responsive_ignore', array( __CLASS__, 'fix_real_media_library_lite' ) );
	}

	/**

	 */
	public static function popup_builder() {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			if ( class_exists( '\sgpb\PopupBuilderInit' ) ) {
				$instance = sgpb\PopupBuilderInit::getInstance();
				self::remove_filters_with_method_name( 'media_buttons', 'popupMediaButton', 10 );
			}
		}
	}

	public static function fix_smush( $option ) {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			$option['format']['iframe'] = false;
		}
		return $option;
	}

	public static function fix_memberium( $enabled ) {
		if ( defined( 'MEMBERIUM_VERSION' ) ) {
			return false;
		}

		return $enabled;
	}

	public static function clear_theme_cache( $enabled ) {
		if ( class_exists( 'BACheetahCustomizer' ) ) {
			if ( $enabled ) {
				add_filter( 'ba_cheetah_enable_fa5_pro', '__return_true' );
			}
			BACheetahCustomizer::refresh_css();
			if ( $enabled ) {
				remove_filter( 'ba_cheetah_enable_fa5_pro', '__return_true' );
			}
		}
	}

	/**
	 * Theme and themer rely on this filter.
	 */
	public static function pro_icons_enable() {
		if ( get_option( '_ba_cheetah_enable_fa_pro', false ) && ! is_admin() ) {
			add_filter( 'ba_cheetah_enable_fa5_pro', '__return_true' );
		}
	}

	/**
	 * Fix cookiebot plugin

	 */
	public static function fix_cookiebot( $arg ) {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			return true;
		}
		return $arg;
	}

	/**
	 * Add data-no-lazy to photo modules in themer header area.
	 * Fixes wp-rocket lazy load issue with shrink header.

	 */
	public static function fix_lazyload_header_start() {
		add_filter( 'ba_cheetah_photo_attributes', array( __CLASS__, 'fix_lazyload_header_attributes' ) );
	}
	public static function fix_lazyload_header_end() {
		remove_filter( 'ba_cheetah_photo_attributes', array( __CLASS__, 'fix_lazyload_header_attributes' ) );
	}
	public static function fix_lazyload_header_attributes( $attrs ) {
		return $attrs . ' data-no-lazy="1"';
	}

	/**
	 * Font Awesome KIT support

	 */
	public static function fa_kit_support() {
		$kit_url = BACheetah::fa5_kit_url();
		if ( BACheetah::fa5_pro_enabled() && '' !== $kit_url ) {
			wp_dequeue_style( 'font-awesome' );
			wp_dequeue_style( 'font-awesome-5' );
			wp_deregister_style( 'font-awesome' );
			wp_deregister_style( 'font-awesome-5' );
			wp_enqueue_script( 'fa5-kit', $kit_url );
		}
	}

	/**
		* Remove BB Template types from Gute Editor suggested urls

		*/
	public static function gute_links_fix( $query ) {
		if ( defined( 'REST_REQUEST' ) && $query->is_search() ) {
			$types = (array) $query->get( 'post_type' );
			$key   = array_search( 'ba-cheetah-template', $types, true );
			if ( $key ) {
				unset( $types[ $key ] );
				$query->set( 'post_type', $types );
			}
		}
	}

	/**
	 * Remove sorting from download type if EDD is active.

	 */

	public static function admin_edit_sort_blocklist_edd( $blocklist ) {
		$types = BACheetahModel::get_post_types();
		if ( in_array( 'download', $types ) && class_exists( 'Easy_Digital_Downloads' ) ) {
			$blocklist[] = 'download';
		}
		return $blocklist;
	}

	/**
	 * Fixes for Google Reviews Business Plugin shortcode

	 */
	public static function fix_google_reviews_business_shortcode() {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			remove_shortcode( 'google-reviews-pro' );
		}
	}

	/**
	 * Fixes for Google Reviews Business Plugin widget

	 */
	public static function fix_google_reviews_business_widget() {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			unregister_widget( 'Goog_Reviews_Pro' );
		}
	}

	/**
	 * Fix post type switcher

	 */
	public static function fix_posttypeswitcher() {
		global $pagenow;
		$disable = false;
		if ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'ba-cheetah-theme-layout' === $_GET['post_type'] ) {
			$disable = true;
		}
		if ( 'post.php' === $pagenow && isset( $_GET['post'] ) && ( 'ba-cheetah-theme-layout' === get_post_type( $_GET['post'] ) || 'ba-cheetah-template' === get_post_type( $_GET['post'] ) ) ) {
			$disable = true;
		}
		if ( $disable ) {
			add_filter( 'pts_allowed_pages', '__return_empty_array' );
		}
	}

	/**
	 * Fix pipedrive chat popup

	 */
	public static function fix_pipedrive() {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			remove_action( 'wp_head', 'pipedrive_add_embed_code' );
		}
	}

	/**
	 * Fix JS error caused by UM-Switcher plugin

	 */
	public static function fix_um_switcher() {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			remove_action( 'wp_footer', 'umswitcher_profile_subscription_expiration_footer' );
		}
	}

	/**
	 * Fix broken Themer Header Layout when Klaviyo is active.

	 */
	public static function fix_klaviyo_themer_layout() {
		return;
	}

	/**
	 * Fix icon issues with Frontend Dashboard version 1.3.4+

	 */
	public static function fix_frontend_dashboard_plugin() {
		if ( BACheetahModel::is_builder_active() ) {
			remove_action( 'wp_enqueue_scripts', 'fed_script_front_end', 99 );
		}
	}

	/**
	 * Remove Sumo JS when builder is open.

	 */
	public static function fix_sumo( $option ) {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			return false;
		}
		return $option;
	}

	/**
	 * Enlighter stops builder from loading.

	 */
	public static function enlighter_frontend_editing( $enabled ) {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			return false;
		}
		return $enabled;
	}

	/**
	 * Fix fatal error on adding Themer layouts and Templates with seopress.

	 */
	public static function fix_seopress() {
		if ( isset( $_POST['ba-cheetah-template'] ) ) {
			remove_action( 'save_post', 'seopress_bulk_quick_edit_save_post' );
		}
	}

	/**
	 * Footer action for ba_cheetah_fix_woo_short_description to print foundf css.

	 */
	public static function fix_woo_short_description_footer() {
		global $ba_cheetah_woo_description_fix;
		if ( is_array( $ba_cheetah_woo_description_fix ) && ! empty( $ba_cheetah_woo_description_fix ) ) {
			echo implode( "\n", esc_html($ba_cheetah_woo_description_fix) );
		}
	}

	/**
	 * If short description is blank and there is a layout in the product content
	 * css will not be enqueued because woocommerce adds the css to the json+ld

	 */
	public static function fix_woo_short_description( $content ) {

		global $post, $ba_cheetah_woo_description_fix;

		// if there is a short description no need to carry on.
		if ( '' !== $content ) {
			return $content;
		}

		// if the product content contains a layout shortcode then extract any css to add to footer later.
		if ( isset( $post->post_content ) && false !== strpos( $post->post_content, '[ba_cheetah_insert_layout' ) ) {
			$dummy   = do_shortcode( $post->post_content );
			$scripts = preg_match_all( "#<link rel='stylesheet'.*#", $dummy, $out );
			if ( is_array( $out ) ) {
				if ( ! is_array( $ba_cheetah_woo_description_fix ) ) {
					$ba_cheetah_woo_description_fix = array();
				}
				foreach ( $out[0] as $script ) {
					$ba_cheetah_woo_description_fix[] = $script;
				}
			}
			// now we will use the content as the short description.
			$content = strip_shortcodes( wp_strip_all_tags( $post->post_content ) );
		}
		return $content;
	}

	/**
	 * Fix HappyFoxChat issue with the Text Editor image button.

	 */
	public static function fix_happyfoxchat() {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) {
			remove_action( 'wp_footer', 'hfc_add_visitor_widget' );
		}
	}

	/**
	 * Remove Popup-Maker post-type from admin settings post-types.

	 */
	public static function admin_settings_post_types_popup( $types ) {
		if ( class_exists( 'Popup_Maker' ) && isset( $types['popup'] ) ) {
			unset( $types['popup'] );
		}
		return $types;
	}

	/**
	 * Remove wpbb post:content from post_content as it causes inception.

	 */
	public static function theme_post_content_fix( $content ) {
		return preg_replace( '#\[wpbb\s?post:content.*\]#', '', $content );
	}

	/**
	 * Whitelist files in bb-theme and bb-theme-builder in PHPCompatibility Checker plugin.

	 */
	public static function bb_compat_fix( $folders ) {
		return [];
	}

	/**
	 * Fix issue with WPMUDEV Smush It.

	 */
	public static function fix_smush_it() {
		if ( BACheetahModel::is_builder_active() ) {
			add_filter( 'wp_smush_enqueue', '__return_false' );
		}
	}

	/**
	 * Fix issue with Prevent Direct Access Gold.

	 */
	public static function fix_protector_gold() {
		if ( BACheetahModel::is_builder_active() && class_exists( 'Prevent_Direct_Access_Gold' ) && ! function_exists( 'get_current_screen' ) ) {
			function get_current_screen() {
				$args         = new StdClass;
				$args->id     = 'Cheetah';
				$args->action = 'Builder';
				return $args;
			}
		}
	}

	/**
	 * Fix issue with Templator plugin.

	 */
	public static function fix_templator() {
		if ( BACheetahModel::is_builder_active() && class_exists( 'Templator_Import' ) ) {
			remove_action( 'media_buttons', array( Templator_Import::get_instance(), 'import_template_button' ) );
		}
	}

	/**
	 * Fix for Enfold theme always loading wp-mediaelement

	 */
	public static function not_load_mediaelement( $condition, $options ) {
		if ( BACheetahModel::is_builder_active() ) {
			$condition = true;
		}
		return $condition;
	}

	/**
	 * Fix Event Calendar widget not loading assets when added as a widget module.

	 */
	public static function tribe_events_pro_widget_render_fix( $class, $args, $instance ) {
		if ( isset( $args['widget_id'] ) && false !== strpos( $args['widget_id'], 'ba_cheetah_widget' ) ) {
			if ( class_exists( 'Tribe__Events__Pro__Mini_Calendar' ) ) {
				if ( method_exists( Tribe__Events__Pro__Mini_Calendar::instance(), 'register_assets' ) ) {
					Tribe__Events__Pro__Mini_Calendar::instance()->register_assets();
				} else {
					if ( class_exists( 'Tribe__Events__Pro__Widgets' ) && method_exists( 'Tribe__Events__Pro__Widgets', 'enqueue_calendar_widget_styles' ) ) {
						Tribe__Events__Pro__Widgets::enqueue_calendar_widget_styles();
					}
				}
			}
		}
	}

	/**
	 * Fix Enjoy Instagram feed on website with WordPress Widget and Shortcode issues with the builder.

	 */
	public static function fix_enjoy_instagram() {
		if ( BACheetahModel::is_builder_active() ) {
			remove_action( 'wp_head', 'funzioni_in_head' );
		}
	}

	/**
	 * Turn off Hummingbird minification

	 */
	public static function fix_hummingbird() {
		if ( BACheetahModel::is_builder_active() ) {
			add_filter( 'wp_hummingbird_is_active_module_minify', '__return_false', 500 );
		}
	}

	/**
	 * Support for tinyPNG.
	 *
	 * Runs cropped photos stored in cache through tinyPNG.
	 */
	public static function tinypng_support( $cropped_path, $editor ) {

		if ( class_exists( 'Tiny_Settings' ) ) {
			try {
				$settings = new Tiny_Settings();
				$settings->xmlrpc_init();
				$compressor = $settings->get_compressor();
				if ( $compressor ) {
					$compressor->compress_file( $cropped_path['path'], false, false );
				}
			} catch ( Exception $e ) {
				//
			}
		}
	}

	/**
	 * Support for WooCommerce Memberships.
	 *
	 * Makes sure builder content isn't rendered for protected posts.
	 */
	public static function wc_memberships_support() {

		if ( function_exists( 'wc_memberships_is_post_content_restricted' ) ) {
			add_filter( 'ba_cheetah_do_render_content', function( $do_render, $post_id ) {
				if ( wc_memberships_is_post_content_restricted() ) {
					// check if user has access to restricted content
					if ( ! current_user_can( 'wc_memberships_view_restricted_post_content', $post_id ) ) {
						$do_render = false;
					} elseif ( ! current_user_can( 'wc_memberships_view_delayed_post_content', $post_id ) ) {
						$do_render = false;
					}
				}
				return $do_render;
			}, 10, 2 );
		}
	}

	/**
	 * If FORCE_SSL_ADMIN is enabled but the frontend is not SSL fixes a CORS error when trying to upload a photo.
	 * `add_filter( 'ba_cheetah_admin_ssl_upload_fix', '__return_false' );` will disable.
	 *

	 */
	public static function admin_ssl_upload_fix() {
		if ( defined( 'FORCE_SSL_ADMIN' ) && ! is_ssl() && is_admin() && BACheetahAJAX::doing_ajax() ) {
			/**
			 * Disable CORS upload fix when FORCE_SSL_ADMIN is enabled.
			 * @see ba_cheetah_admin_ssl_upload_fix
			 */
			if ( isset( $_POST['action'] ) && 'upload-attachment' === $_POST['action'] && true === apply_filters( 'ba_cheetah_admin_ssl_upload_fix', true ) ) {
				force_ssl_admin( false );
			}
		}
	}

	/**
	 * Disable support Buddypress pages since it's causing conflicts with `the_content` filter
	 *
	 * @param bool $is_editable Whether the post is editable or not
	 * @param $post The post to check from
	 * @return bool
	 */
	public static function bp_pages_support( $is_editable, $post = false ) {
		// Frontend check
		if ( ! is_admin() && class_exists( 'BuddyPress' ) && ! bp_is_blog_page() ) {
			$is_editable = false;
		}
		// Admin rows action link check and applies to page list
		if ( is_admin() && class_exists( 'BuddyPress' ) && $post && 'page' == $post->post_type ) {
			$bp = buddypress();
			if ( $bp->pages ) {
				foreach ( $bp->pages as $page ) {
					if ( $post->ID == $page->id ) {
						$is_editable = false;
						break;
					}
				}
			}
		}
		return $is_editable;
	}

	/**
	 * There is an issue with Jetpack Photon and circle cropped photo module
	 * returning the wrong image sizes from the bb cache folder.
	 * This filter disables photon for circle cropped photo module images.
	 */
	public static function photo_photon_exception( $val, $src, $tag ) {

		// Make sure its a bb cached image.
		if ( false !== strpos( $src, 'wp-cheetah/cache' ) ) {

			// now make sure its a circle cropped image.
			if ( false !== strpos( basename( $src ), '-circle' ) ) {
				/**
				 * Disable photon circle imgae fix default ( true )
				 * @see ba_cheetah_photo_photon_exception
				 */
				return apply_filters( 'ba_cheetah_photo_photon_exception', true );
			}
		}
		// return original val
		return $val;
	}

	/**
	 * Filter rendered module content and if safemode is active safely display a message.

	 */
	public static function render_module_content_filter( $contents, $module ) {
		if ( isset( $_GET['safemode'] ) && BACheetahModel::is_builder_active() ) {
			return sprintf( '<h3>[%1$s] %2$s %3$s</h3>', __( 'SAFEMODE', 'ba-cheetah' ), $module->name, __( 'element', 'ba-cheetah' ) );
		} else {
			return $contents;
		}
	}

	/**
	 * Duplicate posts plugin fixes when cloning BB template.
	 *

	 * @param int $meta_id The newly added meta ID
	 * @param int $object_id ID of the object metadata is for.
	 * @param string $meta_key Metadata key
	 * @param string $meta_value Metadata value
	 * @return void
	 */
	public static function template_meta_add( $meta_id, $object_id, $meta_key, $meta_value ) {
		global $pagenow;

		if ( 'admin.php' != $pagenow ) {
			return;
		}

		if ( ! isset( $_REQUEST['action'] ) || 'duplicate_post_save_as_new_post' != $_REQUEST['action'] ) {
			return;
		}

		$post_type = get_post_type( $object_id );
		if ( 'ba-cheetah-template' != $post_type || '_ba_cheetah_template_id' != $meta_key ) {
			return;
		}

		// Generate new template ID;
		$template_id = BACheetahModel::generate_node_id();
		update_post_meta( $object_id, '_ba_cheetah_template_id', $template_id );
	}

	/**
	 * Stop bw-minify from optimizing when builder is open.

	 */
	public static function bwp_minify_is_loadable_filter( $args ) {
		if ( BACheetahModel::is_builder_active() ) {
			return false;
		}
		return $args;
	}

	/**
	* Fixes an issue on search archives if one of the results contains same shortcode
	* as is currently trying to render.
	*

	* @param bool $render Render shortcode.
	* @param array $attrs Shortcode attributes.
	* @param array $args Passed to BACheetah::render_query
	* @return bool
	*/
	public static function insert_layout_render_search( $render, $attrs, $args ) {
		global $post, $wp_query;
		if ( is_search() && is_object( $post ) && is_array( $wp_query->posts ) ) {
			foreach ( $wp_query->posts as $queried_post ) {
				if ( $post->ID === $queried_post->ID ) {
					preg_match( '#(?<=ba_cheetah_insert_layout).*[id|slug]=[\'"]?([0-9a-z-]+)#', $post->post_content, $matches );
					if ( isset( $matches[1] ) ) {
						return false;
					}
				}
			}
		}
		return $render;
	}

	/**
	* Fixes ajax issues with Event Espresso plugin when builder is open.

	*/
	public static function ee_suppress_notices() {
		if ( BACheetahModel::is_builder_active() ) {
			add_filter( 'FHEE__EE_Front_Controller__display_errors', '__return_false' );
		}
	}

	/**
	 * Stops ee from outputting HTML into our ajax responses.

	 */
	public static function ee_before_ajax() {
		add_filter( 'FHEE__EE_Front_Controller__display_errors', '__return_false' );
	}

	/**
	 * Stops ee from loading espresso_default.css stylesheet in the builder to prevent hiding of buttons/tabs in TinyMCE

	 */
	public static function ee_remove_stylesheet() {
		if ( class_exists( 'BACheetahModel' ) && ( BACheetahModel::is_builder_active() ) ) {
				wp_deregister_style( 'espresso_default' );
		}
	}

	/**
	* Plugin Enjoy Instagram loads its js and css on all frontend pages breaking the builder.

	*/
	public static function aggiungi_script_instafeed_owl() {
		if ( BACheetahModel::is_builder_active() ) {
			remove_action( 'wp_enqueue_scripts', 'aggiungi_script_instafeed_owl' );
		}
	}

	/**
	 * Remove Activemember360 shortcodes from saved post content to stop them rendering twice.

	 */
	public static function activemember_shortcode_fix( $content ) {
		return preg_replace( '#\[mbr.*?\]#', '', $content );
	}

	/**
	 * Remove iMember360 shortcodes from saved post content to stop them rendering twice.

	 */
	public static function imember_shortcode_fix( $content ) {
		return preg_replace( '#\[i4w.*?\]#', '', $content );
	}

	/**
	 * Fix javascript issue caused by nextgen gallery when adding modules in the builder.

	 */
	public static function fix_nextgen_gallery() {
		if ( isset( $_GET['ba_cheetah'] ) || isset( $_POST['ba_cheetah_data'] ) || BACheetahAJAX::doing_ajax() ) {
			if ( ! defined( 'NGG_DISABLE_RESOURCE_MANAGER' ) ) {
				define( 'NGG_DISABLE_RESOURCE_MANAGER', true );
			}
		}
	}

	/**
	 * Fix Tasty Recipes compatibility issues with the builder.

	 */
	public static function fix_tasty_recipes() {
		if ( BACheetahModel::is_builder_active() ) {
			remove_action( 'wp_enqueue_editor', array( 'Tasty_Recipes\Assets', 'action_wp_enqueue_editor' ) );
			remove_action( 'media_buttons', array( 'Tasty_Recipes\Editor', 'action_media_buttons' ) );
		}
	}

	/**
	 * Dequeue GeneratePress fa5 js when builder is open.

	 */
	public static function fix_generatepress_fa5() {
		if ( BACheetahModel::is_builder_active() ) {
			add_filter( 'generate_fontawesome_essentials', '__return_true' );
		}
	}

	/**
	 * Try to render Ninja Forms JS templates when rendering an AJAX layout
	 * in case the layout includes one of their shortcodes. This won't do
	 * anything if no templates need to be rendered.

	 */
	public static function render_ninja_forms_js( $response ) {
		if ( class_exists( 'NF_Display_Render' ) ) {
			ob_start();
			NF_Display_Render::output_templates();
			$response['html'] .= ob_get_clean();
		}
		return $response;
	}

	/**
	 * Helper function
	 * @see https://github.com/herewithme/wp-filters-extras/blob/master/wp-filters-extras.php
	 */
	public static function remove_filters_with_method_name( $hook_name = '', $method_name = '', $priority = 0 ) {
		global $wp_filter;

		// Take only filters on right hook name and priority
		if ( ! isset( $wp_filter[ $hook_name ][ $priority ] ) || ! is_array( $wp_filter[ $hook_name ][ $priority ] ) ) {
			return false;
		}

		// Loop on filters registered
		foreach ( (array) $wp_filter[ $hook_name ][ $priority ] as $unique_id => $filter_array ) {
			// Test if filter is an array ! (always for class/method)
			if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {
				// Test if object is a class and method is equal to param !
				if ( is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && $filter_array['function'][1] == $method_name ) {
					// Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/)
					if ( is_a( $wp_filter[ $hook_name ], 'WP_Hook' ) ) {
						unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
					} else {
						unset( $wp_filter[ $hook_name ][ $priority ][ $unique_id ] );
					}
				}
			}
		}
		return false;
	}

	/**
	 * Fix row background video on Twenty Twenty theme.

	 */
	public static function fix_twenty_twenty_video( $classes ) {
		if ( 'twentytwenty' == get_option( 'template' ) && ! in_array( 'intrinsic-ignore', $classes ) ) {
			$classes[] = 'intrinsic-ignore';
		}

		return $classes;
	}

	/**
	 * Fix compatibility issue with Yoast SEO when category prefix is removed
	 * in the settings.
	 *

	 */
	public static function fix_wpseo_category_pagination_rule( $rewrite_rules ) {
		if ( ! class_exists( 'WPSEO_Rewrite' ) ) {
			return $rewrite_rules;
		}

		if ( ! isset( $GLOBALS['wpseo_rewrite'] ) ) {
			return $rewrite_rules;
		}

		if ( ! method_exists( $GLOBALS['wpseo_rewrite'], 'category_rewrite_rules' ) ) {
			return $rewrite_rules;
		}

		global $wp_rewrite;

		$wpseo_rewrite_rules = $GLOBALS['wpseo_rewrite']->category_rewrite_rules();
		$page_base           = $wp_rewrite->pagination_base;
		$flpaged_base        = 'paged-[0-9]{1,}';
		$flpaged_rules       = array();

		foreach ( $wpseo_rewrite_rules as $regex => $redirect ) {
			if ( strpos( $regex, '/' . $page_base . '/' ) !== false ) {
				$flregex = str_replace( $page_base, $flpaged_base, $regex );

				// Adds our custom paged rule.
				$flpaged_rules[ $flregex ] = 'index.php?category_name=$matches[1]&flpaged=$matches[2]';
			}
		}
		$rewrite_rules = array_merge( $flpaged_rules, $rewrite_rules );

		return $rewrite_rules;
	}

	/**
	 * Fix pagination compatibility with Polylang pages.
	 *

	 */
	public static function fix_polylang_pagination_rule( $rewrite_rules ) {
		if ( ! isset( $GLOBALS['polylang'] ) ) {
			return $rewrite_rules;
		}

		if ( ! function_exists( 'pll_languages_list' ) ) {
			return $rewrite_rules;
		}

		$langs = pll_languages_list();
		if ( ! empty( $langs ) ) {
			$lang_rules                = '(' . implode( '|', $langs ) . ')';
			$paged_rules               = $lang_rules . '/(.?.+?)/paged-[0-9]{1,}/?([0-9]{1,})/?$';
			$new_rules[ $paged_rules ] = 'index.php?lang=$matches[1]&pagename=$matches[2]&flpaged=$matches[3]';
			$rewrite_rules             = array_merge( $new_rules, $rewrite_rules );
		}

		return $rewrite_rules;
	}
	/**
	 * Fix compatibility issue Woocommerce Products Filter Add-on
	 *

	 */
	public static function fix_woocommerce_products_filter() {
		if ( class_exists( 'WooCommerce' )
		&& class_exists( 'WooCommerce_Product_Filter_Plugin\Plugin' )
		&& class_exists( 'BACheetahModel' )
		&& ( BACheetahModel::is_builder_active() ) ) {
			wp_deregister_script( 'wcpf-plugin-polyfills-script' );
		}
	}

	/**
	 * Fix compatibility issue in Woo archive product sorting.
	 *

	 */
	public static function fix_woo_archive_loop( $q ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		if ( is_admin() || ! $q->get( 'ba_cheetah_loop' ) || 'product_query' != $q->get( 'wc_query' ) ) {
			return;
		}

		if ( ! $q->is_post_type_archive( 'product' ) && ! $q->is_tax( get_object_taxonomies( 'product' ) ) ) {
			return;
		}

		// Add woo sorting to posts module query.
		$ordering = WC()->query->get_catalog_ordering_args();
		$q->set( 'orderby', $ordering['orderby'] );
		$q->set( 'order', $ordering['order'] );

		if ( isset( $ordering['meta_key'] ) ) {
			$q->set( 'meta_key', $ordering['meta_key'] );
		}
	}

	/**
	 * Fix compatibility when paginating TEC events archive.
	 *

	 */
	public static function fix_tribe_events_pagination_rule( $rules, $tribe_rewrite, $wp_rewrite ) {
		$bases = $tribe_rewrite->get_bases();

		// Archive
		$tec_archive_rules           = $bases->archive . '/paged-[0-9]{1,}/?([0-9]{1,})/?$';
		$rules[ $tec_archive_rules ] = 'index.php?post_type=tribe_events&eventDisplay=default&flpaged=$matches[1]';

		// Category
		$tec_cat_rules           = $bases->archive . '/(?:category)/(?:[^/]+/)*([^/]+)/paged-[0-9]{1,}/?([0-9]{1,})/?$';
		$rules[ $tec_cat_rules ] = 'index.php?post_type=tribe_events&tribe_events_cat=$matches[1]&eventDisplay=list&flpaged=$matches[2]';

		// Tag
		$tec_tag_rules           = $bases->archive . '/(?:tag)/([^/]+)/paged-[0-9]{1,}/?([0-9]{1,})/?$';
		$rules[ $tec_tag_rules ] = 'index.php?post_type=tribe_events&tag=$matches[1]&eventDisplay=list&flpaged=$matches[2]';

		return $rules;
	}
	/**
	 * Fix 'Hide From Event Listings' from the Event Options under the Event Edit Screen
	 * not being picked up by the Posts Grid module such as when used in a Themer Archive Layout.
	 *

	 */
	public static function fix_tribe_events_hide_from_listings_archive( $query ) {
		return;
	}

	/**
	 * Fix 'Hide From Event Listings' from the Event Options under the Event Edit Screen
	 * not being picked up by the Posts Grid module set to 'custom_query'.
	 *

	 */
	public static function fix_tribe_events_hide_from_listings( $args ) {
		if ( ! class_exists( 'Tribe__Events__Query' ) || is_admin() ) {
			return $args;
		}

		if ( empty( $args['settings']->post_type ) || empty( $args['settings']->data_source ) ) {
			return $args;
		}

		if ( 'tribe_events' !== $args['settings']->post_type || 'custom_query' !== $args['settings']->data_source ) {
			return $args;
		}

		$hide_upcoming_events = Tribe__Events__Query::getHideFromUpcomingEvents();
		if ( isset( $args['post__not_in'] ) ) {
			$args['post__not_in'] = array_merge( $args['post__not_in'], $hide_upcoming_events );
		} else {
			$args['post__not_in'] = $hide_upcoming_events;
		}

		return $args;
	}

	/**
	 * Fix nodes below Posts module not editable when it's set to the Products post type.

	 */
	public static function fix_woof_posts_module() {
		if ( class_exists( 'WOOF' ) && ( isset( $_GET['ba_cheetah'] ) || isset( $_GET['ba_builder'] ) ) ) {
			remove_action( 'init', array( $GLOBALS['WOOF'], 'init' ), 1 );
		}
	}

	/**
	 * Fix submenu toggle button showing on menu module when using Twenty Twenty-one theme.

	 */
	public static function fix_menu_module_before_render() {
		if ( function_exists( 'twenty_twenty_one_add_sub_menu_toggle' ) ) {
			remove_filter( 'walker_nav_menu_start_el', 'twenty_twenty_one_add_sub_menu_toggle', 10, 4 );
		}
	}
	/**
	 * Reset Twenty Twenty-one submenu toggle button filter.

	 */
	public static function fix_menu_module_after_render() {
		if ( function_exists( 'twenty_twenty_one_add_sub_menu_toggle' ) ) {
			add_filter( 'walker_nav_menu_start_el', 'twenty_twenty_one_add_sub_menu_toggle', 10, 4 );
		}
	}

	/**
	 * AIOSEO tries to render the layout shortcode too early.

	 */
	public static function aioseo_conflicting_shortcodes( $shortcodes ) {
		$shortcodes['Builderall Builder'] = '[ba_cheetah_insert_layout';
		return $shortcodes;
	}
	/**

	 */
	public static function fix_real_media_library_lite( $ignore ) {
		$ignore[] = 'real-media-library-lite';
		return $ignore;
	}
}
BACheetahCompatibility::init();
