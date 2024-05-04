<?php

if ( ! class_exists( 'BACheetahLoader' ) ) {

	/**
	 * Responsible for setting up builder constants, classes and includes.
	 *

	 */
	final class BACheetahLoader {

		/**
		 * Load the builder if it's not already loaded, otherwise
		 * show an admin notice.
		 *

		 * @return void
		 */
		static public function init() {
			add_action('init', __CLASS__ . '::register_ob_start_to_dd');

			if ( ! function_exists( 'is_plugin_active' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			if ( class_exists( 'BACheetah' ) ) {
				add_action( 'admin_notices', __CLASS__ . '::double_install_admin_notice' );
				add_action( 'network_admin_notices', __CLASS__ . '::double_install_admin_notice' );
				return;
			}

			self::define_constants();
			self::load_files();
			self::check_permissions();

			define('BA_CHEETAH_OFFICE_URL', self::get_office_url());
		}

		/**
		 * Define builder constants.
		 *

		 * @return void
		 */
		static private function define_constants() {

			if(!defined('BA_CHEETAH_DASHBOARD_URL')) {
				define( 'BA_CHEETAH_DASHBOARD_URL', 'https://wordpress.builderall.com/');
			}

			if (!defined('BA_CHEETAH_TEMPLATE_API_ENABLED')) {
				define('BA_CHEETAH_TEMPLATE_API_ENABLED', false);
			}

			if (!defined('BA_CHEETAH_TEMPLATE_API_URL')) {
				define('BA_CHEETAH_TEMPLATE_API_URL', 'https://builderallbuilderwp.builderall.com/wp-json/wp/v2/');
			}

			define( 'BA_CHEETAH_FILE', trailingslashit( dirname( dirname( __FILE__ ) ) ) . 'ba-cheetah.php' );

			$plugin_data = get_plugin_data(BA_CHEETAH_FILE);
			$plugin_version = $plugin_data['Version'];
			define( 'BA_CHEETAH_VERSION', $plugin_version );

			define( 'BA_CHEETAH_DIR', plugin_dir_path( BA_CHEETAH_FILE ) );
			define( 'BA_CHEETAH_URL', plugins_url( '/', BA_CHEETAH_FILE ) );

			define( 'BA_CHEETAH_LANDINGPAGE_URL', 'https://builderallbuilderpro.com' );
			define( 'BA_CHEETAH_HELP_URL', get_locale() == 'pt_BR' ? 'https://ajuda.builderall.com/' : 'https://knowledgebase.builderall.com/' );
			define( 'BA_CHEETAH_YOUTUBE_URL', get_locale() == 'pt_BR' ? 'https://www.youtube.com/channel/UCCDeKQkMbxm18nYpQbqBlGQ' : 'https://www.youtube.com/channel/UCRzQ11vg83JBagbO8piZH1A' );
			define( 'BA_CHEETAH_DEMO_DOMAIN', 'builderallbuilderwp.builderall.com' );
			define( 'BA_CHEETAH_DEMO_URL', 'https://builderall.com/' );
			define( 'BA_CHEETAH_DEMO_CACHE_URL', 'https://builderall.com/' );
		}

		/**
		 * Loads classes and includes.
		 *

		 * @return void
		 */
		static private function load_files() {

			/* Classes */
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-authentication.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-filesystem.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-admin.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-admin-pointers.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-admin-posts.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-admin-settings.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-ajax.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-ajax-layout.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-art.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-auto-suggest.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-color.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-css.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-extensions.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-fonts.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-history-manager.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-debug.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-icons.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-iframe-preview.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-loop.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-model.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-module.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-photo.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-revisions.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-settings-compat.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-shortcodes.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-timezones.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-ui-content-panel.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-ui-settings-forms.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-user-access.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-user-settings.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-utils.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-wpml.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-seo.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-privacy.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-settings-presets.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-compatibility.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-auth-http.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-popups.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-tracking.php';
            require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-media.php';
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-retrieve-template-exception.php';

			/* WP CLI Commands */
			if ( defined( 'WP_CLI' ) ) {
				require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-wpcli-command.php';
			}

			/* WP Blocks Support */
			require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-wp-blocks.php';

			/* Includes */
			require_once BA_CHEETAH_DIR . 'includes/compatibility.php';
			require_once BA_CHEETAH_DIR . 'includes/helpers.php';

			/* Updater */
			if ( file_exists( BA_CHEETAH_DIR . 'includes/updater/updater.php' ) ) {
				require_once BA_CHEETAH_DIR . 'includes/updater/updater.php';
			}
		}

		/**
		 * Checks to see if we can write to files and shows
		 * an admin notice if we can't.
		 *

		 * @access private
		 * @return void
		 */
		static private function check_permissions() {
			if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], array( 'ba-cheetah-settings', 'ba-cheetah-multisite-settings' ) ) ) {

				$wp_upload_dir = wp_upload_dir( null, false );
				$bb_upload_dir = BACheetahModel::get_upload_dir();

				if ( ! ba_cheetah_filesystem()->is_writable( $wp_upload_dir['basedir'] ) || ! ba_cheetah_filesystem()->is_writable( $bb_upload_dir['path'] ) ) {
					add_action( 'admin_notices', __CLASS__ . '::permissions_admin_notice' );
					add_action( 'network_admin_notices', __CLASS__ . '::permissions_admin_notice' );
				}
			}
		}

		/**
		 * Shows an admin notice if we can't write to files.
		 *

		 * @return void
		 */
		static public function permissions_admin_notice() {
			$message = __( 'Builderall Builder may not be functioning correctly as it does not have permission to write files to the WordPress uploads directory on your server. Please update the WordPress uploads directory permissions before continuing or contact your host for assistance.', 'ba-cheetah' );

			self::render_admin_notice( $message, 'error' );
		}

		/**
		 * Shows an admin notice if another version of the builder
		 * has already been loaded before this one.
		 *

		 * @return void
		 */
		static public function double_install_admin_notice() {
			/* translators: %s: plugins page link */
			$message = __( 'You currently have two versions of Builderall Builder active on this site. Please <a href="%s">deactivate one</a> before continuing.', 'ba-cheetah' );

			self::render_admin_notice( sprintf( $message, admin_url( 'plugins.php' ) ), 'error' );
		}

		/**
		 * Renders an admin notice.
		 *

		 * @access private
		 * @param string $message
		 * @param string $type
		 * @return void
		 */
		static private function render_admin_notice( $message, $type = 'update' ) {
			if ( ! is_admin() ) {
				return;
			} elseif ( ! is_user_logged_in() ) {
				return;
			} elseif ( ! current_user_can( 'update_plugins' ) ) {
				return;
			}

			echo '<div class="' . esc_attr($type) . '">';
			echo '<p>' . wp_kses_post($message) . '</p>';
			echo '</div>';
		}

		static public function register_ob_start_to_dd() {
			ob_start();
		}

		/**
		 * Return office url
		 *
		 * @return string
		 */
		static public function get_office_url() {
			if(BACheetahAuthentication::is_pro_user() && !BACheetahAuthentication::is_builderall_user()) {
				return 'https://office.builderallbuilderpro.com/';
			}

			return 'https://office.builderall.com/';
		}
	}
}

BACheetahLoader::init();
