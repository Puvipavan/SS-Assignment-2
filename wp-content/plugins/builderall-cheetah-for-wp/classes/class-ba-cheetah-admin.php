<?php

/**
 * Main builder admin class.
 *

 */
final class BACheetahAdmin
{

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init()
	{
		$basename = plugin_basename(BA_CHEETAH_FILE);

		// Activation
		register_activation_hook(BA_CHEETAH_FILE, __CLASS__ . '::activate');

		// Actions
		add_action('admin_init', __CLASS__ . '::sanity_checks');

		// Filters
		add_filter('plugin_action_links_' . $basename, __CLASS__ . '::render_plugin_action_links');

		// Css
		add_action('admin_head', __CLASS__ . '::register_style');
	}

	public static function register_style()
	{
		wp_enqueue_style('ba-cheetah-admin-style', BA_CHEETAH_URL . 'css/ba-cheetah-admin.css', array(), BA_CHEETAH_VERSION);
	}

	/**
	 * Called on plugin activation and checks to see if the correct
	 * WordPress version is installed and multisite is supported. If
	 * all checks are passed the install method is called.
	 *

	 * @return void
	 */
	static public function activate()
	{
		global $wp_version;

		// Check for WordPress 3.5 and above.
		if (!version_compare($wp_version, '4.6', '>=')) {
			self::show_activate_error(__('The <strong>Builderall Builder</strong> plugin requires WordPress version 4.6 or greater. Please update WordPress before activating the plugin.', 'ba-cheetah'));
		}

		/**
		 * Allow extensions to hook activation.
		 * @see ba_cheetah_activate
		 */
		$activate = apply_filters('ba_cheetah_activate', true);

		// Should we continue with activation?
		if ($activate) {

			// Check for multisite.
			if (is_multisite()) {
				$url = BACheetahModel::get_upgrade_url(array(
					'utm_medium'   => 'ba-cheetah-pro',
					'utm_source'   => 'plugins-admin-page',
					'utm_campaign' => 'no-multisite-support',
				));
				/* translators: %s: upgrade url */
				self::show_activate_error(sprintf(__('This version of the <strong>Builderall Builder</strong> plugin is not compatible with WordPress Multisite. <a%s>Please upgrade</a> to the Multisite version of this plugin.', 'ba-cheetah'), ' href="' . $url . '" target="_blank"'));
			}

			// Success! Run the install.
			self::install();

			// Trigger the activation notice.
			self::trigger_activate_notice();

			/**
			 * Allow add-ons to hook into activation.
			 * @see ba_cheetah_activated
			 */
			do_action('ba_cheetah_activated');

			// Flush the rewrite rules.
			flush_rewrite_rules();
		}
	}

	/**
	 * Restrict builder settings accessibility based on the defined capability.
	 *

	 * @return void
	 */
	static public function current_user_can_access_settings()
	{
		return current_user_can(self::admin_settings_capability());
	}

	/**
	 * Define capability.
	 *

	 * @return string
	 */
	static public function admin_settings_capability()
	{
		/**
		 * Default admin settings capability ( manage_options )
		 * @see ba_cheetah_admin_settings_capability
		 */
		return apply_filters('ba_cheetah_admin_settings_capability', 'manage_options');
	}

	/**
	 * Show a message if there is an activation error and
	 * deactivates the plugin.
	 *

	 * @param string $message The message to show.
	 * @return void
	 */
	static public function show_activate_error($message)
	{
		deactivate_plugins(BACheetahModel::plugin_basename(), false, is_network_admin());

		die($message);
	}

	/**

	 */
	static public function sanity_checks()
	{

		//Check for one.com htaccess file in uploads that breaks everything.
		$upload_dir = wp_upload_dir();
		$file       = trailingslashit($upload_dir['basedir']) . '.htaccess';
		if (file_exists($file)) {
			$htaccess = file_get_contents($file);
			if (false !== strpos($htaccess, 'Block javascript except for visualcomposer (VC) plugin')) {
				BACheetahAdminSettings::add_error(
					/* translators: %s formatted .htaccess */
					sprintf(__('Install Error! You appear to have a %s file in your uploads folder that will block all javascript files resulting in 403 errors. If you did not add this file please consult your host.', 'ba-cheetah'), '<code>.htaccess</code>')
				);
			}
		}
	}

	/**
	 * Sets the transient that triggers the activation notice
	 * or welcome page redirect.
	 *

	 * @return void
	 */
	static public function trigger_activate_notice()
	{
		if (self::current_user_can_access_settings()) {
			set_transient('_ba_cheetah_activation_admin_notice', true, 30);
		}
	}

	/**
	 * Installs the builder upon successful activation.
	 * Currently not used but may be in the future.
	 *

	 * @return void
	 */
	static public function install()
	{
        BACheetahModel::init_color_presets();

	}

	/**
	 * Uninstalls the builder.
	 *

	 * @return void
	 */
	static public function uninstall()
	{
		BACheetahModel::uninstall_database();
	}

	/**
	 * Renders the link for the row actions on the plugins page.
	 *

	 * @param array $actions An array of row action links.
	 * @return array
	 */
	static public function render_plugin_action_links($actions)
	{

		$actions[] = '<a href="' . menu_page_url('ba-cheetah-settings', false) . '">' . _x('Settings', 'Plugin action link label.', 'ba-cheetah') . '</a>';

		if (!BA_CHEETAH_PRO) {
			$actions[] = '<a style="font-weight:bold" target="_blank" href="' . BA_CHEETAH_LANDINGPAGE_URL . '">' . _x('Go Pro', '', 'ba-cheetah') . '</a>';
		}

		return $actions;
	}

	static public function render_form()
	{
		_deprecated_function(__METHOD__, '1.0.2');
	}

	/**


	 */
	static public function init_classes()
	{
		_deprecated_function(__METHOD__, '1.8');
	}

	/**


	 */
	static public function init_settings()
	{
		_deprecated_function(__METHOD__, '1.8');
	}

	/**


	 */
	static public function init_multisite()
	{
		_deprecated_function(__METHOD__, '1.8');
	}

	/**


	 */
	static public function init_templates()
	{
		_deprecated_function(__METHOD__, '1.8');
	}

	/**


	 */
	static public function white_label_plugins_page($plugins)
	{
		_deprecated_function(__METHOD__, '1.8', 'BACheetahWhiteLabel::plugins_page()');

		if (class_exists('BACheetahWhiteLabel')) {
			return BACheetahWhiteLabel::plugins_page($plugins);
		}

		return $plugins;
	}

	/**


	 */
	static public function white_label_themes_page($themes)
	{
		_deprecated_function(__METHOD__, '1.8', 'BACheetahWhiteLabel::themes_page()');

		if (class_exists('BACheetahWhiteLabel')) {
			return BACheetahWhiteLabel::themes_page($themes);
		}

		return $themes;
	}

	/**


	 */
	static public function white_label_theme_gettext($text)
	{
		if (class_exists('BACheetahWhiteLabel')) {
			return BACheetahWhiteLabel::theme_gettext($text);
		}

		return $text;
	}
}

BACheetahAdmin::init();
