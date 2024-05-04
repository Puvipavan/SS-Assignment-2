<?php

/**
 * Handles logic for the admin settings page.
 *

 */
final class BACheetahAdminSettings {

	/**
	 * Holds any errors that may arise from
	 * saving admin settings.
	 *

	 * @var array $errors
	 */
	static public $errors = array();

	/**
	 * Initializes the admin settings.
	 *

	 * @return void
	 */
	static public function init() {
		add_action( 'init', __CLASS__ . '::init_hooks', 11 );
		// add_action( 'wp_ajax_ba_cheetah_welcome_submit', array( 'BACheetahAdminSettings', 'welcome_submit' ) );
	}

	/**
	 * AJAX callback for welcome email subscription form.

	 */
	static public function welcome_submit() {

	}

	/**
	 * Adds the admin menu and enqueues CSS/JS if we are on
	 * the builder admin settings page.
	 *

	 * @return void
	 */
	static public function init_hooks() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_menu', __CLASS__ . '::menu' );

		if ( isset( $_REQUEST['page'] ) && 'ba-cheetah-settings' == $_REQUEST['page'] ) {
			add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
			add_filter( 'admin_footer_text', array( __CLASS__, '_filter_admin_footer_text' ) );
			self::save();
		}
	}

	/**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *

	 * @return void
	 */
	static public function styles_scripts() {
		// Styles
		wp_enqueue_style( 'ba-cheetah-admin-settings', BA_CHEETAH_URL . 'css/ba-cheetah-admin-settings.css', array(), BA_CHEETAH_VERSION );
		wp_enqueue_style( 'jquery-multiselect', BA_CHEETAH_URL . 'css/jquery.multiselect.css', array(), BA_CHEETAH_VERSION );
		wp_enqueue_style( 'ba-cheetah-jquery-tiptip', BA_CHEETAH_URL . 'css/jquery.tiptip.css', array(), BA_CHEETAH_VERSION );

		if ( BACheetah::fa5_pro_enabled() ) {
			if ( '' !== get_option( '_ba_cheetah_kit_fa_pro' ) ) {
				wp_enqueue_script( 'fa5-kit', get_option( '_ba_cheetah_kit_fa_pro' ) );
			} else {
				wp_register_style( 'font-awesome-5', BACheetah::get_fa5_url() );
				wp_enqueue_style( 'font-awesome-5' );
			}
		}
		// Scripts
		wp_enqueue_script( 'ba-cheetah-admin-settings', BA_CHEETAH_URL . 'js/ba-cheetah-admin-settings.js', array( 'ba-cheetah-jquery-tiptip' ), BA_CHEETAH_VERSION );
		wp_enqueue_script( 'jquery-actual', BA_CHEETAH_URL . 'js/jquery.actual.min.js', array( 'jquery' ), BA_CHEETAH_VERSION );
		wp_enqueue_script( 'jquery-multiselect', BA_CHEETAH_URL . 'js/jquery.multiselect.js', array( 'jquery' ), BA_CHEETAH_VERSION );
		wp_enqueue_script( 'ba-cheetah-jquery-tiptip', BA_CHEETAH_URL . 'js/jquery.tiptip.min.js', array( 'jquery' ), BA_CHEETAH_VERSION, true );
		wp_enqueue_script( 'ba-cheetah-admin-pro-user', BA_CHEETAH_URL . 'js/ba-cheetah-admin-pro-user.js', array( 'jquery' ), BA_CHEETAH_VERSION );

		// Media Uploader
		wp_enqueue_media();
	}

	/**
	 * Renders the admin settings menu.
	 *

	 * @return void
	 */
	static public function menu() {
		$cap = BACheetahAdmin::admin_settings_capability();

		if(BACheetahUserAccess::current_user_can('unrestricted_editing')) {

			$title = BACheetahModel::get_branding();
			$slug  = 'ba-cheetah-settings';
			$func  = __CLASS__ . '::render';

			add_menu_page($title, $title, 'edit_pages', $slug, $func, BA_CHEETAH_URL . 'img/branding/menu-icon.svg?cache=1');
			add_submenu_page('ba-cheetah-settings', $title, $title, $cap, $slug, $func, -1 );
		}
	}

	/**
	 * Renders the admin settings.
	 *

	 * @return void
	 */
	static public function render() {
		include BA_CHEETAH_DIR . 'includes/admin-settings-js-config.php';
		include BA_CHEETAH_DIR . 'includes/admin-settings.php';
	}

	/**
	 * Renders the page class for network installs and single site installs.
	 *

	 * @return void
	 */
	static public function render_page_class() {
		if ( self::multisite_support() ) {
			echo 'ba-cheetah-settings-network-admin';
		} else {
			echo 'ba-cheetah-settings-single-install';
		}
	}

	/**
	 * Renders the admin settings page heading.
	 *

	 * @return void
	 */
	static public function render_page_heading() {
		$icon = BA_CHEETAH_URL . 'img/branding/icon.svg';

		if ( ! empty( $icon ) ) {
			echo '<img role="presentation" src="' . esc_attr($icon) . '" />';
		}
		/* translators: %s: builder branded name */
		echo '<span>' . sprintf( _x( '%s Settings', '%s stands for custom branded "Page Builder" name.', 'ba-cheetah' ), BACheetahModel::get_branding() ) . '</span>';
	}

	/**
	 * Renders the update message.
	 *

	 * @return void
	 */
	static public function render_update_message() {
		if ( ! empty( self::$errors ) ) {
			foreach ( self::$errors as $message ) {
				echo '<div class="error"><p>' . wp_kses_post($message) . '</p></div>';
			}
		} elseif ( ! empty( $_POST ) && ! isset( $_POST['email'] ) ) {
			$nameClass = isset($_POST['ba-cheetah-watermark-position']) ? ' watermark' : '';
			echo '<div class="updated'.$nameClass.'"><p>' . __( 'Settings updated!', 'ba-cheetah' ) . '</p></div>';
		}
	}

	/**
	 * Renders the nav items for the admin settings menu.
	 *

	 * @return void
	 */
	static public function render_nav_items() {
		/**
		 * Builder admin nav items
		 * @see ba_cheetah_admin_settings_nav_items
		 */
		$item_data = apply_filters( 'ba_cheetah_admin_settings_nav_items', array(
			'welcome'     => array(
				'title'    => __( 'Welcome', 'ba-cheetah' ),
				'show'     => ! BACheetahModel::is_white_labeled() && ( is_network_admin() || ! self::multisite_support() ),
				'priority' => 50,
			),
			'integrations' => array(
				'title'    => __( 'Integrations', 'ba-cheetah' ),
				'show'     => ( is_network_admin() || ! self::multisite_support() ),
				'priority' => 100,
			),
			'pro'       => array(
				'title'    => __( 'Builderall Builder Pro', 'ba-cheetah' ),
				'show'     => true,
				'priority' => 200,
			),
			/* @FUTURE - admin tabs disabled
			'modules'     => array(
				'title'    => __( 'Elements', 'ba-cheetah' ),
				'show'     => true,
				'priority' => 300,
			),
			'user-access' => array(
				'title'    => __( 'User Access', 'ba-cheetah' ),
				'show'     => true,
				'priority' => 500,
			),
			*/
			'post-types'  => array(
				'title'    => __( 'Post Types', 'ba-cheetah' ),
				'show'     => true,
				'priority' => 400,
			),
			'tools'       => array(
				'title'    => __( 'Tools', 'ba-cheetah' ),
				'show'     => true,
				'priority' => 700,
			),
			'general'       => array(
				'title'    => __( 'General Settings', 'ba-cheetah' ),
				'show'     => true,
				'priority' => 900,
			),
		) );

		$sorted_data = array();

		foreach ( $item_data as $key => $data ) {
			$data['key']                      = $key;
			$sorted_data[ $data['priority'] ] = $data;
		}

		ksort( $sorted_data );

		foreach ( $sorted_data as $data ) {
			if ( $data['show'] ) {
				echo '<li><a href="#' . esc_attr($data['key']) . '">' . wp_kses_post($data['title']) . '</a></li>';
			}
		}
	}

	/**
	 * Renders the admin settings forms.
	 *

	 * @return void
	 */
	static public function render_forms() {
		// Welcome
		if ( ! BACheetahModel::is_white_labeled() && ( is_network_admin() || ! self::multisite_support() ) ) {
			self::render_form( 'welcome' );
		}

		// License
		if ( is_network_admin() || ! self::multisite_support() ) {
			self::render_form( 'integrations' );
		}

		// Pro User
		self::render_form( 'pro' );

		// Upgrade (builderall Apps)
		if(BA_CHEETAH_BUILDERALL && (BA_CHEETAH_AUTENTICATED !== true)) {
			self::render_form( 'upgrade' );
		}


		/* @FUTURE - admin tabs disabled
		// Modules
		self::render_form( 'modules' );

		// Icons
		self::render_form( 'icons' );

		// User Access
		self::render_form( 'user-access' );
		*/

		// Post Types
		self::render_form( 'post-types' );

		// Tools
		self::render_form( 'tools' );

		// General Settings
		self::render_form( 'general' );

		/**
		 * Let extensions hook into form rendering.
		 * @see ba_cheetah_admin_settings_render_forms
		 */
		do_action( 'ba_cheetah_admin_settings_render_forms' );
	}

	/**
	 * Renders an admin settings form based on the type specified.
	 *

	 * @param string $type The type of form to render.
	 * @return void
	 */
	static public function render_form( $type ) {
		if ( self::has_support( $type ) ) {
			include BA_CHEETAH_DIR . 'includes/admin-settings-' . $type . '.php';
		}
	}

	/**
	 * Renders the action for a form.
	 *

	 * @param string $type The type of form being rendered.
	 * @return void
	 */
	static public function render_form_action( $type = '' ) {
		if ( is_network_admin() ) {
			echo network_admin_url( '/settings.php?page=ba-cheetah-multisite-settings#' . esc_attr($type) );
		} else {
			echo admin_url( '/admin.php?page=ba-cheetah-settings#' . esc_attr($type) );
		}
	}

	/**
	 * Returns the action for a form.
	 *

	 * @param string $type The type of form being rendered.
	 * @return string The URL for the form action.
	 */
	static public function get_form_action( $type = '' ) {
		if ( is_network_admin() ) {
			return network_admin_url( '/settings.php?page=ba-cheetah-multisite-settings#' . $type );
		} else {
			return admin_url( '/admin.php?page=ba-cheetah-settings#' . $type );
		}
	}

	/**
	 * Checks to see if a settings form is supported.
	 *

	 * @param string $type The type of form to check.
	 * @return bool
	 */
	static public function has_support( $type ) {
		return file_exists( BA_CHEETAH_DIR . 'includes/admin-settings-' . $type . '.php' );
	}

	/**
	 * Checks to see if multisite is supported.
	 *

	 * @return bool
	 */
	static public function multisite_support() {
		return is_multisite() && class_exists( 'BACheetahMultisiteSettings' );
	}

	/**
	 * Adds an error message to be rendered.
	 *

	 * @param string $message The error message to add.
	 * @return void
	 */
	static public function add_error( $message ) {
		self::$errors[] = $message;
	}

	/**
	 * Saves the admin settings.
	 *

	 * @return void
	 */
	static public function save() {
		// Only admins can save settings.
		if ( ! BACheetahAdmin::current_user_can_access_settings() ) {
			return;
		}

		/* @FUTURE - admin tabs disabled */
		
		// self::save_enabled_modules();
		self::save_enabled_post_types();
		// self::save_enabled_icons();
		self::save_general_settings();
		self::save_integrations();
		// self::save_user_access();
		self::active_pro_user_access();
		self::remove_pro_user_access();
		self::check_pro_level();
		self::send_token_pro_user_access();
		self::clear_cache();
		self::debug();
		self::beta();
		self::uninstall();

		/**
		 * Let extensions hook into saving.
		 * @see ba_cheetah_admin_settings_save
		 */
		do_action( 'ba_cheetah_admin_settings_save' );
	}

	/**
	 * Saves builderall general configurations.
	 *

	 * @access private
	 * @return void
	 */
	static private function save_general_settings() {
		if ( isset( $_POST['ba-cheetah-general-settings-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-general-settings-nonce'], 'ba-general-config' ) ) {
			
			$ba_configurations = array();

			$ba_configurations['canvas-mode'] = (isset($_POST['ba-cheetah-canvas-mode']) ? $_POST['ba-cheetah-canvas-mode'] : 0);

			BACheetahModel::update_admin_settings_option( '_ba_cheetah_general_settings', $ba_configurations, true );
		}
	}

	/**
	 * Saves watermark configurations.
	 *

	 * @access private
	 * @return void
	 */
	static private function save_integrations() {
		if ( isset( $_POST['ba-cheetah-integrations-config-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-integrations-config-nonce'], 'ba-integrations' ) ) {
			
			// watermark
			$watermark = array();
			$watermark['show'] = (isset($_POST['ba-cheetah-show-watermark']) ? $_POST['ba-cheetah-show-watermark'] : 0);
			$watermark['position'] = (isset($_POST['ba-cheetah-watermark-position']) ? $_POST['ba-cheetah-watermark-position'] : 'left');
			BACheetahModel::update_admin_settings_option( '_ba_cheetah_watermark', $watermark, true );

			// recaptcha
			$sitekey = sanitize_text_field($_POST['recaptcha_sitekey']);
			$secretkey = sanitize_text_field($_POST['recaptcha_secretkey']);
			BACheetahModel::update_admin_settings_option( '_ba_cheetah_recaptcha_sitekey', (isset($sitekey) ? $sitekey : ''), true );
			BACheetahModel::update_admin_settings_option( '_ba_cheetah_recaptcha_secretkey', (isset($secretkey) ? $secretkey : ''), true );
			
			// pixel
			$pixel = sanitize_text_field($_POST['pixel_id']);
			BACheetahModel::update_admin_settings_option( '_ba_cheetah_facebook_pixel_id', (isset($pixel) ? $pixel : ''), true );
		}
	}

	/**
	 * Saves the enabled modules.
	 *

	 * @access private
	 * @return void
	 */
	static private function save_enabled_modules() {
		if ( isset( $_POST['ba-cheetah-modules-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-modules-nonce'], 'modules' ) ) {

			$modules = array();

			if ( isset( $_POST['ba-cheetah-modules'] ) && is_array( $_POST['ba-cheetah-modules'] ) ) {
				$modules = array_map( 'sanitize_text_field', $_POST['ba-cheetah-modules'] );
			}

			if ( empty( $modules ) ) {
				self::add_error( __( 'Error! You must have at least one element enabled.', 'ba-cheetah' ) );
				return;
			}

			BACheetahModel::update_admin_settings_option( '_ba_cheetah_enabled_modules', $modules, true );
		}
	}

	/**
	 * Saves the enabled post types.
	 *

	 * @access private
	 * @return void
	 */
	static private function save_enabled_post_types() {
		if ( isset( $_POST['ba-cheetah-post-types-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-post-types-nonce'], 'post-types' ) ) {

			if ( is_network_admin() ) {
				$post_types = sanitize_text_field( $_POST['ba-cheetah-post-types'] );
				$post_types = str_replace( ' ', '', $post_types );
				$post_types = explode( ',', $post_types );
			} else {

				$post_types = array();

				if ( isset( $_POST['ba-cheetah-post-types'] ) && is_array( $_POST['ba-cheetah-post-types'] ) ) {
					$post_types = array_map( 'sanitize_text_field', $_POST['ba-cheetah-post-types'] );
				}
			}

			BACheetahModel::update_admin_settings_option( '_ba_cheetah_post_types', $post_types, true );
		}
	}

	/**
	 * Saves the enabled icons.
	 *

	 * @access private
	 * @return void
	 */
	static private function save_enabled_icons() {
		if ( isset( $_POST['ba-cheetah-icons-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-icons-nonce'], 'icons' ) ) {

			// Make sure we have at least one enabled icon set.
			if ( ! isset( $_POST['ba-cheetah-enabled-icons'] ) && empty( $_POST['ba-cheetah-new-icon-set'] ) ) {
				self::add_error( __( 'Error! You must have at least one icon set enabled.', 'ba-cheetah' ) );
				return;
			}

			$enabled_icons = array();

			// Sanitize the enabled icons.
			if ( isset( $_POST['ba-cheetah-enabled-icons'] ) && is_array( $_POST['ba-cheetah-enabled-icons'] ) ) {
				$enabled_icons = array_map( 'sanitize_text_field', $_POST['ba-cheetah-enabled-icons'] );
			}

			// Update the enabled sets.
			self::update_enabled_icons( $enabled_icons );

			// Enable pro?
			$enable_fa_pro = isset( $_POST['ba-cheetah-enable-fa-pro'] ) ? true : false;
			update_option( '_ba_cheetah_enable_fa_pro', $enable_fa_pro );
			do_action( 'ba_cheetah_fa_pro_save', $enable_fa_pro );
			// Update KIT url
			$kit_url = isset( $_POST['ba-cheetah-fa-pro-kit'] ) ? esc_url_raw( $_POST['ba-cheetah-fa-pro-kit'] ) : '';

			update_option( '_ba_cheetah_kit_fa_pro', $kit_url );

			// Delete a set?
			if ( ! empty( $_POST['ba-cheetah-delete-icon-set'] ) ) {

				$sets  = BACheetahIcons::get_sets();
				$key   = sanitize_text_field( $_POST['ba-cheetah-delete-icon-set'] );
				$index = array_search( $key, $enabled_icons );

				if ( false !== $index ) {
					unset( $enabled_icons[ $index ] );
				}
				if ( isset( $sets[ $key ] ) ) {
					ba_cheetah_filesystem()->rmdir( $sets[ $key ]['path'], true );
					BACheetahIcons::remove_set( $key );
				}
				/**
				 * After set is deleted.
				 * @see ba_cheetah_admin_settings_remove_icon_set
				 */
				do_action( 'ba_cheetah_admin_settings_remove_icon_set', $key );
			}

			// Upload a new set?
			if ( ! empty( $_POST['ba-cheetah-new-icon-set'] ) ) {

				$dir = BACheetahModel::get_cache_dir( 'icons' );
				$id  = (int) absint( $_POST['ba-cheetah-new-icon-set'] );
				/**
				 * Icon upload path
				 * @see ba_cheetah_icon_set_upload_path
				 */
				$path = apply_filters( 'ba_cheetah_icon_set_upload_path', get_attached_file( $id ) );
				/**
				 * @see ba_cheetah_icon_set_new_path
				 */
				$new_path = apply_filters( 'ba_cheetah_icon_set_new_path', $dir['path'] . 'icon-' . time() . '/' );

				ba_cheetah_filesystem()->get_filesystem();

				/**
				 * Before set is unzipped.
				 * @see ba_cheetah_before_unzip_icon_set
				 */
				do_action( 'ba_cheetah_before_unzip_icon_set', $id, $path, $new_path );

				$unzipped = unzip_file( $path, $new_path );

				// unzip returned a WP_Error
				if ( is_wp_error( $unzipped ) ) {
					/* translators: %s: unzip error message */
					self::add_error( sprintf( __( 'Unzip Error: %s', 'ba-cheetah' ), $unzipped->get_error_message() ) );
					return;
				}

				// Unzip failed.
				if ( ! $unzipped ) {
					self::add_error( __( 'Error! Could not unzip file.', 'ba-cheetah' ) );
					return;
				}

				// Move files if unzipped into a subfolder.
				$files = ba_cheetah_filesystem()->dirlist( $new_path );

				if ( 1 == count( $files ) ) {

					$values         = array_values( $files );
					$subfolder_info = array_shift( $values );
					$subfolder      = $new_path . $subfolder_info['name'] . '/';

					if ( ba_cheetah_filesystem()->file_exists( $subfolder ) && ba_cheetah_filesystem()->is_dir( $subfolder ) ) {

						$files = ba_cheetah_filesystem()->dirlist( $subfolder );

						if ( $files ) {
							foreach ( $files as $file ) {
								ba_cheetah_filesystem()->move( $subfolder . $file['name'], $new_path . $file['name'] );
							}
						}

						ba_cheetah_filesystem()->rmdir( $subfolder );
					}
				}

				/**
				 * After set is unzipped.
				 * @see ba_cheetah_after_unzip_icon_set
				 */
				do_action( 'ba_cheetah_after_unzip_icon_set', $new_path );

				/**
				 * @see ba_cheetah_icon_set_check_path
				 */
				$check_path = apply_filters( 'ba_cheetah_icon_set_check_path', $new_path );

				// Check for supported sets.
				$is_icomoon  = ba_cheetah_filesystem()->file_exists( $check_path . 'selection.json' );
				$is_fontello = ba_cheetah_filesystem()->file_exists( $check_path . 'config.json' );
				$is_awesome  = ba_cheetah_filesystem()->file_exists( $check_path . '/metadata/icons.json' );

				// Show an error if we don't have a supported icon set.
				if ( ! $is_icomoon && ! $is_fontello && ! $is_awesome ) {
					ba_cheetah_filesystem()->rmdir( $new_path, true );
					self::add_error( __( 'Error! Please upload an icon set from either Icomoon, Fontello or Font Awesome Pro Subset.', 'ba-cheetah' ) );
					return;
				}

				// check for valid Icomoon
				if ( $is_icomoon ) {
					$data = json_decode( ba_cheetah_filesystem()->file_get_contents( $check_path . 'selection.json' ) );
					if ( ! isset( $data->metadata ) ) {
						ba_cheetah_filesystem()->rmdir( $new_path, true );
						self::add_error( __( 'Error! When downloading from Icomoon, be sure to click the Download Font button and not Generate SVG.', 'ba-cheetah' ) );
						return;
					}
				}

				// we need to patch the all.css file because _reasons_
				if ( $is_awesome ) {
					$search  = array( '.fa,.fas{font-family:', '.fad{', '.fal,.far{font-family' );
					$replace = array( '.subset.fa,.subset.fas{font-family:', '.subset.fad{', '.subset.fal,.subset.far{font-family' );
					$css     = str_replace( $search, $replace, ba_cheetah_filesystem()->file_get_contents( $check_path . 'css/all.min.css' ) );
					ba_cheetah_filesystem()->file_put_contents( $check_path . 'css/all.min.css', $css );
				}

				// Enable the new set.
				if ( is_array( $enabled_icons ) ) {
					$key             = BACheetahIcons::get_key_from_path( $check_path );
					$enabled_icons[] = $key;
				}
			}

			// Update the enabled sets again in case they have changed.
			self::update_enabled_icons( $enabled_icons );
		}
	}

	/**
	 * Updates the enabled icons in the database.
	 *

	 * @access private
	 * @return void
	 */
	static private function update_enabled_icons( $enabled_icons = array() ) {
		BACheetahModel::update_admin_settings_option( '_ba_cheetah_enabled_icons', $enabled_icons, true );
	}

	/**
	 * Saves the user access settings
	 *

	 * @access private
	 * @return void
	 */
	static private function save_user_access() {
		if ( isset( $_POST['ba-cheetah-user-access-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-user-access-nonce'], 'user-access' ) ) {
			BACheetahUserAccess::save_settings( isset( $_POST['ba_cheetah_user_access'] ) ? $_POST['ba_cheetah_user_access'] : array() );
		}
	}

	/**
	 * Saves the user access settings
	 *

	 * @access private
	 * @return void
	 */
	static private function send_token_pro_user_access() {
		if ( isset( $_POST['ba-cheetah-pro-user-access-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-pro-user-access-nonce'], 'pro-user-token' ) ) {
			BACheetahAuthentication::send_pro_user_token( $_POST['cheetah_email'] );
		}
	}

	/**
	 * Saves the user access settings
	 *

	 * @access private
	 * @return void
	 */
	static private function active_pro_user_access() {
		if ( isset( $_POST['ba-cheetah-pro-user-access-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-pro-user-access-nonce'], 'pro-user' ) ) {
			BACheetahAuthentication::active_pro_user( $_POST['token'] );
		}
	}

	/**
	 * Saves the user access settings
	 *

	 * @access private
	 * @return void
	 */
	static private function remove_pro_user_access() {
		if ( isset( $_POST['ba-cheetah-pro-user-access-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-pro-user-access-nonce'], 'remove-pro-access' ) ) {
			BACheetahAuthentication::logoutProUser();
		}
	}

	/**
	 * Saves the user access settings
	 *

	 * @access private
	 * @return void
	 */
	static private function check_pro_level() {
		if ( isset( $_POST['ba-cheetah-pro-user-access-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-pro-user-access-nonce'], 'check-pro-level' ) ) {
			BACheetahAuthentication::check_user_level();
		}
	}

	/**
	 * Clears the builder cache.
	 *

	 * @access private
	 * @return void
	 */
	static private function clear_cache() {

		if ( ! BACheetahAdmin::current_user_can_access_settings() ) {
			return;
		} elseif ( isset( $_POST['ba-cheetah-cache-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-cache-nonce'], 'cache' ) ) {

			if ( is_network_admin() ) {
				self::clear_cache_for_all_sites();
			} else {

				// Clear builder cache.
				BACheetahModel::delete_asset_cache_for_all_posts();

				// Clear Builderall templates cache
				if(class_exists('BACheetahTemplates')) {
					BACheetahTemplates::clear_cache();
				}
			}
			/**
			 * Fires after cache is cleared.
			 * @see ba_cheetah_cache_cleared
			 */
			do_action( 'ba_cheetah_cache_cleared' );
		}
	}

	/**
	 * Enable/disable debug
	 *

	 * @access private
	 * @return void
	 */
	static private function debug() {
		if ( ! BACheetahAdmin::current_user_can_access_settings() ) {
			return;
		} elseif ( isset( $_POST['ba-cheetah-debug-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-debug-nonce'], 'debug' ) ) {
			$debugmode = get_transient( 'ba_cheetah_debug_mode' );

			if ( ! $debugmode ) {
				set_transient( 'ba_cheetah_debug_mode', md5( rand() ), 172800 ); // 48 hours 172800
			} else {
				delete_transient( 'ba_cheetah_debug_mode' );
			}
		}
	}

	/**
	 * Clears the builder cache for all sites on a network.
	 *

	 * @access private
	 * @return void
	 */
	static private function clear_cache_for_all_sites() {
		global $blog_id;
		global $wpdb;

		// Save the original blog id.
		$original_blog_id = $blog_id;

		// Get all blog ids.
		$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

		// Loop through the blog ids and clear the cache.
		foreach ( $blog_ids as $id ) {

			// Switch to the blog.
			switch_to_blog( $id );

			// Clear builder cache.
			BACheetahModel::delete_asset_cache_for_all_posts();

			// Clear theme cache.
			if ( class_exists( 'BACheetahCustomizer' ) && method_exists( 'BACheetahCustomizer', 'clear_all_css_cache' ) ) {
				BACheetahCustomizer::clear_all_css_cache();
			}
		}

		// Revert to the original blog.
		switch_to_blog( $original_blog_id );
	}

	/**
	 * Uninstalls the builder and all of its data.
	 *

	 * @access private
	 * @return void
	 */
	static private function uninstall() {
		if ( ! current_user_can( 'delete_plugins' ) ) {
			return;
		} elseif ( isset( $_POST['ba-cheetah-uninstall'] ) && wp_verify_nonce( $_POST['ba-cheetah-uninstall'], 'uninstall' ) ) {

			/**
			 * Disable Uninstall ( default true )
			 * @see ba_cheetah_uninstall
			 */
			$uninstall = apply_filters( 'ba_cheetah_uninstall', true );

			if ( $uninstall ) {
				BACheetahAdmin::uninstall();
			}
		}
	}

	/**
	 * Enable/disable beta updates
	 *

	 * @access private
	 * @return void
	 */
	static private function beta() {

		if ( ! current_user_can( 'delete_users' ) ) {
			return;
		} elseif ( isset( $_POST['ba-cheetah-beta-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-beta-nonce'], 'beta' ) ) {

			if ( isset( $_POST['beta-checkbox'] ) ) {
				update_option( 'ba_cheetah_beta_updates', true );
			} else {
				delete_option( 'ba_cheetah_beta_updates' );
			}

			if ( isset( $_POST['alpha-checkbox'] ) ) {
				update_option( 'ba_cheetah_alpha_updates', true );
			} else {
				delete_option( 'ba_cheetah_alpha_updates' );
			}
		}
	}


	/**


	 */
	static private function save_help_button() {
		_deprecated_function( __METHOD__, '1.8', 'BACheetahWhiteLabel::save_help_button_settings()' );
	}

	/**


	 */
	static private function save_branding() {
		_deprecated_function( __METHOD__, '1.8', 'BACheetahWhiteLabel::save_branding_settings()' );
	}

	/**


	 */
	static private function save_enabled_templates() {
		_deprecated_function( __METHOD__, '1.8', 'BACheetahUserTemplatesAdmin::save_settings()' );
	}

	/**

	 */
	static function _filter_admin_footer_text( $text ) {

		$stars = '<a target="_blank" href="https://wordpress.org/plugins/builderall-cheetah-for-wp/#reviews" >&#9733;&#9733;&#9733;&#9733;&#9733;</a>';

		$wporg = '<a target="_blank" href="https://wordpress.org/plugins/builderall-cheetah-for-wp/">wordpress.org</a>';

		/* translators: 1: stars link: 2: link to wporg page */
		return sprintf( __( 'Add your %1$s on %2$s to spread the love.', 'ba-cheetah' ), $stars, $wporg );
	}
}

BACheetahAdminSettings::init();
