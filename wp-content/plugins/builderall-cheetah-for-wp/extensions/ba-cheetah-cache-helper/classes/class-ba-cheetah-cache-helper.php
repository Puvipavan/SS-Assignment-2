<?php
namespace BACheetahCacheClear;
class Plugin {

	private $classes = array();

	private $filters = array();

	private static $plugins = array();

	private $actions = array(
		'ba_cheetah_cache_cleared',
		'ba_cheetah_after_save_layout',
		'ba_cheetah_after_save_user_template',
	);

	function __construct() {

		add_action( 'plugins_loaded', array( $this, 'unload_helper_plugin' ) );
		add_action( 'plugins_loaded', array( $this, 'load_files' ) );
		add_action( 'ba_cheetah_admin_settings_save', array( $this, 'save_settings' ) );
	}

	/**
	 * Save settings added to Tools page.

	 */
	public function save_settings() {
		if ( ! isset( $_POST['ba-cheetah-cache-plugins-nonce'] ) || ! wp_verify_nonce( $_POST['ba-cheetah-cache-plugins-nonce'], 'cache-plugins' ) ) {
			return false;
		}

		$enabled = isset( $_POST['ba-cheetah-cache-plugins-enabled'] ) ? absint( $_POST['ba-cheetah-cache-plugins-enabled'] ) : 0;
		$varnish = isset( $_POST['ba-cheetah-cache-varnish-enabled'] ) ? absint( $_POST['ba-cheetah-cache-varnish-enabled'] ) : 0;

		$settings = array(
			'enabled' => $enabled,
			'varnish' => $varnish,
		);

		\BACheetahModel::update_admin_settings_option( '_ba_cheetah_cache_plugins', $settings, false );
	}

	/**
	 * Get settings.

	 */
	public static function get_settings() {

		$defaults = array(
			'enabled' => true,
			'varnish' => false,
		);

		$settings = \BACheetahModel::get_admin_settings_option( '_ba_cheetah_cache_plugins', false );
		return wp_parse_args( $settings, $defaults );
	}

	/**
	 * Remove actions added by the cache helper plugin.

	 */
	public function unload_helper_plugin() {
		if ( class_exists( 'BA_CHEETAH_Cache_Buster' ) ) {
			$settings = self::get_settings();
			if ( $settings['enabled'] ) {
				remove_action( 'upgrader_process_complete', array( 'BA_CHEETAH_Cache_Buster', 'clear_caches' ) );
				remove_action( 'ba_cheetah_after_save_layout', array( 'BA_CHEETAH_Cache_Buster', 'clear_caches' ) );
				remove_action( 'ba_cheetah_after_save_user_template', array( 'BA_CHEETAH_Cache_Buster', 'clear_caches' ) );
				remove_action( 'ba_cheetah_cache_cleared', array( 'BA_CHEETAH_Cache_Buster', 'clear_caches' ) );
				remove_action( 'template_redirect', array( 'BA_CHEETAH_Cache_Buster', 'donotcache' ) );
			}
		}
	}

	/**
	 * Load the cache plugin files.
	 */
	public function load_files() {

		foreach ( glob( BA_CHEETAH_CACHE_HELPER_DIR . 'plugins/*.php' ) as $file ) {

			$classname = 'BACheetahCacheClear\\' . ucfirst( str_replace( '.php', '', basename( $file ) ) );
			include_once( $file );
			$class = new $classname();

			$actions = isset( $class->actions ) ? $class->actions : $this->actions;
			$filters = isset( $class->filters ) ? $class->filters : $this->filters;

			if ( isset( $class->name ) ) {
				self::$plugins[ $classname ]['name'] = $class->name;
			}

			if ( isset( $class->url ) ) {
				self::$plugins[ $classname ]['url'] = $class->url;
			}

			$settings = self::get_settings();
			if ( ! $settings['enabled'] ) {
				return false;
			}

			if ( ! empty( $actions ) ) {
				$this->add_actions( $class, $actions );
			}
			if ( ! empty( $filters ) ) {
				$this->add_filters( $class, $filters );
			}
		}
	}

	/**
	 * Return list of plugins to be used on admin page.
	 */
	public static function get_plugins() {
		$plugins = self::$plugins;
		$output  = '';
		foreach ( $plugins as $plugin ) {
			if ( isset( $plugin['url'] ) ) {
				$output .= sprintf( '<li><a target="_blank" href="%s">%s</a>', $plugin['url'], $plugin['name'] );
			} else {
				$output .= sprintf( '<li>%s</li>', $plugin['name'] );
			}
		}
		return '<ul>' . $output . '</ul>';
	}

	function add_actions( $class, $actions ) {
		foreach ( $actions as $action ) {
			add_action( $action, array( $class, 'run' ) );
		}
	}

	function add_filters( $class, $filters ) {
		foreach ( $filters as $filter ) {
			add_action( $filter, array( $class, 'filters' ) );
		}
	}

	public static function define( $define, $setting = true ) {
		if ( ! defined( $define ) ) {
			define( $define, $setting );
		}
	}
}
new Plugin;
