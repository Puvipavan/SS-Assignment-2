<?php

final class BA_CHEETAH_Debug
{

	static private $tests = array();

	public static function init()
	{
		if (isset($_GET['babuilderdebug']) && get_transient('ba_cheetah_debug_mode', false) === $_GET['babuilderdebug']) {
			if (isset($_GET['info'])) {
				phpinfo();
				exit;
			}
			add_action('init', array('BA_CHEETAH_Debug', 'display_tests'));
		}

		if (get_transient('ba_cheetah_debug_mode')) {
			self::enable_logging();
			add_filter('ba_cheetah_is_debug', '__return_true');
		}
	}


	public static function enable_logging()
	{
		@ini_set('display_errors', 1); // @codingStandardsIgnoreLine
		@ini_set('display_startup_errors', 1); // @codingStandardsIgnoreLine
		@error_reporting(E_ALL); // @codingStandardsIgnoreLine
	}

	public static function display_tests()
	{

		self::prepare_tests();

		echo '<style> .cheetah .e {background: #9dc9ff} </style>';

		echo '<div class="center cheetah">';

		echo '<h1>' . get_bloginfo( 'name' ) . '</h1>';

		echo '<table>';
		foreach ((array) self::$tests as $test) {
			echo self::display($test);
		}
		echo '</table>';

		echo '</div>';

		phpinfo();
		
		die();
	}

	private static function display($test)
	{
		if (isset($test['heading'])) {
			return sprintf("</table> <h2>%s</h2><table>", $test['name']);
		}

		if (is_array($test['data'])) {
			$list = '<ul>';
			foreach ($test['data'] as $key => $value) {
				$list .= sprintf('<li>%s</li>', $value);
			}
			$list .= '<ul>';
			$test['data'] = $list;
		}

		return sprintf("<tr><td class='e'>%s</td><td class='v'>%s</td></tr>", $test['name'], $test['data']);
	}

	private static function register($slug, $args)
	{
		self::$tests[$slug] = $args;
	}

	private static function formatbytes($size, $precision = 2)
	{
		$base     = log($size, 1024);
		$suffixes = array('', 'K', 'M', 'G', 'T');

		return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}

	private static function get_plugins()
	{

		$plugins = array();
		require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		require_once(ABSPATH . 'wp-admin/includes/update.php');

		$plugins_data = get_plugins();

		foreach ($plugins_data as $plugin_path => $plugin) {
			if (is_plugin_active($plugin_path)) {
				$plugins['active'][] = sprintf('%s - version %s by %s.', $plugin['Name'], $plugin['Version'], $plugin['Author']);
			} else {
				$plugins['deactive'][] = sprintf('%s - version %s by %s.', $plugin['Name'], $plugin['Version'], $plugin['Author']);
			}
		}
		return $plugins;
	}

	public static function safe_ini_get($ini)
	{
		return @ini_get($ini); // @codingStandardsIgnoreLine
	}


	private static function prepare_tests()
	{

		global $wpdb, $wp_version, $wp_json;

		$args = array(
			'name' => 'WordPress',
			'heading' => true,
		);
		self::register('wp', $args);

		$args = array(
			'name' => 'WordPress Address',
			'data' => get_option('siteurl'),
		);
		self::register('wp_url', $args);

		$args = array(
			'name' => 'Site Address',
			'data' => get_option('home'),
		);
		self::register('site_url', $args);

		$args = array(
			'name' => 'IP',
			'data' => $_SERVER['SERVER_ADDR'],
		);
		self::register('wp_ip', $args);

		$args = array(
			'name' => 'WP Version',
			'data' => $wp_version,
		);
		self::register('wp_version', $args);

		$args = array(
			'name' => 'WP Debug',
			'data' => defined('WP_DEBUG') && WP_DEBUG ? 'Yes' : 'No',
		);
		self::register('wp_debug', $args);

		$args = array(
			'name' => 'BA Cheetah Debug',
			'data' => BACheetah::is_debug() ? 'Yes' : 'No',
		);
		self::register('ba_cheetah_debug', $args);

		$args = array(
			'name' => 'BA Cheetah Modsec Fix',
			'data' => defined('BA_CHEETAH_MODSEC_FIX') && BA_CHEETAH_MODSEC_FIX ? 'Yes' : 'No',
		);
		self::register('ba_cheetah_modsec', $args);

		$args = array(
			'name' => 'SSL Enabled',
			'data' => is_ssl() ? 'Yes' : 'No',
		);
		self::register('wp_ssl', $args);

		$args = array(
			'name' => 'Language',
			'data' => get_locale(),
		);
		self::register('lang', $args);

		$args = array(
			'name' => 'Multisite',
			'data' => is_multisite() ? 'Yes' : 'No',
		);
		self::register('is_multi', $args);

		$args = array(
			'name' => 'WordPress memory limit',
			'data' => WP_MAX_MEMORY_LIMIT,
		);
		self::register('wp_max_mem', $args);

		if (get_option('upload_path') != 'wp-content/uploads' && get_option('upload_path')) {
			$args = array(
				'name' => 'Possible Issue: upload_path is set, can lead to cache dir issues and css not loading. Check Settings -> Media for custom path.',
				'data' => get_option('upload_path'),
			);
			self::register('wp_media_upload_path', $args);
		}

		if (defined('DISALLOW_UNFILTERED_HTML') && DISALLOW_UNFILTERED_HTML) {
			$args = array(
				'name' => 'Unfiltered HTML is globally disabled! ( DISALLOW_UNFILTERED_HTML )',
				'data' => 'Yes',
			);
			self::register('is_multi', $args);
		}

		$args = array(
			'name' => 'Plugins',
			'heading' => true,
		);
		self::register('plugins', $args);

		$args = array(
			'name' => 'Plugins',
			'heading' => true,
		);
		self::register('wp_plugins', $args);

		$defaults = array(
			'active'   => array(),
			'deactive' => array(),
		);

		$plugins = wp_parse_args(self::get_plugins(), $defaults);
		$args    = array(
			'name' => 'Active Plugins',
			'data' => $plugins['active'],
		);
		self::register('wp_plugins', $args);

		$args = array(
			'name' => 'Unactive Plugins',
			'data' => $plugins['deactive'],
		);
		self::register('wp_plugins_deactive', $args);

		$args = array(
			'name' => 'Themes',
			'heading' => true,
		);
		self::register('themes', $args);

		$theme = wp_get_theme();
		$args  = array(
			'name' => 'Active Theme',
			'data' => array(
				sprintf('%s - v%s', $theme->get('Name'), $theme->get('Version')),
				sprintf('Parent Theme: %s', ($theme->get('Template')) ? $theme->get('Template') : 'Not a child theme'),
			),
		);
		self::register('active_theme', $args);

		// child theme functions
		if ($theme->get('Template')) {
			$functions_file = trailingslashit(get_stylesheet_directory()) . 'functions.php';
			$contents       = file_get_contents($functions_file);
			$args           = array(
				'name' => 'Child Theme Functions',
				'data' => $contents,
			);
			self::register('child_funcs', $args);
		}

		$args = array(
			'name' => 'PHP',
			'heading' => true,
		);
		self::register('php', $args);

		$args = array(
			'name' => 'PHP SAPI',
			'data' => php_sapi_name(),
		);
		self::register('php_sapi', $args);

		$args = array(
			'name' => 'PHP JSON Support',
			'data' => ($wp_json instanceof Services_JSON) ? '*** NO JSON MODULE ***' : 'yes',
		);
		self::register('php_json', $args);

		$args = array(
			'name' => 'PHP Memory Limit',
			'data' => self::safe_ini_get('memory_limit'),
		);
		self::register('php_mem_limit', $args);

		$args = array(
			'name' => 'PHP Version',
			'data' => phpversion(),
		);
		self::register('php_ver', $args);

		$args = array(
			'name' => 'Post Max Size',
			'data' => self::safe_ini_get('post_max_size'),
		);
		self::register('post_max', $args);

		$args = array(
			'name' => 'PHP Max Input Vars',
			'data' => self::safe_ini_get('max_input_vars'),
		);
		self::register('post_max_input', $args);

		$args = array(
			'name' => 'PHP Max Execution Time',
			'data' => self::safe_ini_get('max_execution_time'),
		);
		self::register('post_max_time', $args);

		$args = array(
			'name' => 'Max Upload Size',
			'data' => self::formatbytes(wp_max_upload_size()),
		);
		self::register('post_max_upload', $args);

		$curl = (function_exists('curl_version')) ? curl_version() : false;
		$args = array(
			'name' => 'Curl',
			'data' => ($curl) ? sprintf('%s - %s', $curl['version'], $curl['ssl_version']) : 'Not Enabled.',
		);
		self::register('curl', $args);

		$args = array(
			'name' => 'PCRE Backtrack Limit ( default 1000000 )',
			'data' => self::safe_ini_get('pcre.backtrack_limit'),
		);
		self::register('backtrack', $args);

		$args = array(
			'name' => 'PCRE Recursion Limit ( default 100000 )',
			'data' => self::safe_ini_get('pcre.recursion_limit'),
		);
		self::register('recursion', $args);

		$zlib = self::safe_ini_get('zlib.output_compression');

		if ($zlib) {
			$args = array(
				'name' => 'ZLIB Output Compression',
				'data' => $zlib,
			);
			self::register('zlib', $args);
		}

		$zlib_handler = self::safe_ini_get('zlib.output_handler');

		if ($zlib_handler) {
			$args = array(
				'name' => 'ZLIB Handler',
				'data' => $zlib,
			);
			self::register('zlib_handler', $zlib_handler);
		}

		$args = array(
			'name' => 'Builderall Builder',
			'heading' => true,
		);
		self::register('ba', $args);

		$args = array(
			'name' => 'Version',
			'data' => BA_CHEETAH_VERSION,
		);
		self::register('ba_version', $args);

		if(BA_CHEETAH_BUILDERALL && (BA_CHEETAH_AUTENTICATED !== true)) {
			$args = array(
				'name' => 'Builderall Integration',
				'data' => 'No integration',
			);
			self::register('ba_autentication', $args);
		} else if ($user = BACheetahAuthentication::user())  {
			$args         = array(
				'name' => 'Builderall Integration',
				'data' => [$user['id'], $user['office_user_id'] , $user['email']],
			);
			self::register('ba_user', $args);
		}

		$args = array(
			'name' => 'Cache Folders',
			'heading' => true,
		);
		self::register('cache_folders', $args);

		$cache = BACheetahModel::get_cache_dir();

		$args = array(
			'name' => 'Builderall Builder Cache Path',
			'data' => $cache['path'],
		);
		self::register('bb_cache_path', $args);

		$args = array(
			'name' => 'Builderall Builder Path writable',
			'data' => (ba_cheetah_filesystem()->is_writable($cache['path'])) ? 'Yes' : 'No',
		);
		self::register('bb_cache_path_writable', $args);

		$args = array(
			'name' => 'WordPress Content Path',
			'data' => WP_CONTENT_DIR,
		);
		self::register('bb_content_path', $args);

		$args = array(
			'name' => 'Server',
			'heading' => true,
		);
		self::register('serv', $args);

		$args = array(
			'name' => 'MySQL Version',
			'data' => (!empty($wpdb->is_mysql) ? $wpdb->db_version() : 'Unknown'),
		);
		self::register('mysql_version', $args);

		$results = (array) $wpdb->get_results('SHOW VARIABLES');

		foreach ($results as $k => $result) {
			if ('max_allowed_packet' === $result->Variable_name) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$args = array(
					'name' => 'MySQL Max Allowed Packet',
					'data' => number_format($result->Value / 1048576) . 'MB', // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				);
				self::register('mysql_packet', $args);
			}
		}

		$db_bytes = $wpdb->get_var(
			$wpdb->prepare(
				'SELECT SUM(data_length + index_length) FROM information_schema.TABLES where table_schema = %s GROUP BY table_schema;',
				DB_NAME
			)
		);

		if (is_numeric($db_bytes)) {
			$args = array(
				'name' => 'MySQL Database Size',
				'data' => number_format($db_bytes / 1048576) . 'MB',
			);
			self::register('mysql_size', $args);
		}

		$args = array(
			'name' => 'Server Info',
			'data' => $_SERVER['SERVER_SOFTWARE'],
		);
		self::register('server', $args);

		$args = array(
			'name' => 'htaccess files',
			'heading' => true,
		);
		self::register('up_htaccess', $args);

		// detect uploads folder .htaccess file and display it if found.
		$uploads          = wp_upload_dir(null, false);
		$uploads_htaccess = trailingslashit($uploads['basedir']) . '.htaccess';
		$root_htaccess    = trailingslashit(ABSPATH) . '.htaccess';

		if (file_exists($root_htaccess)) {
			ob_start();
			readfile($root_htaccess);
			$htaccess = ob_get_clean();
			$args     = array(
				'name' => $root_htaccess . "\n",
				'data' => "<pre>$htaccess</pre>",
			);
			self::register('up_htaccess_root', $args);
		}
		if (file_exists($uploads_htaccess)) {
			ob_start();
			readfile($uploads_htaccess);
			$htaccess = ob_get_clean();
			$args     = array(
				'name' => $uploads_htaccess . "\n",
				'data' => $htaccess,
			);
			self::register('up_htaccess_uploads', $args);
		}

		$args = array(
			'name' => 'Post Counts',
			'heading' => true,
		);
		self::register('post_counts', $args);

		$templates = wp_count_posts('ba-cheetah-template');

		$post_types = get_post_types(null, 'object');

		foreach ($post_types as $type => $type_object) {

			if (in_array($type, array('wp_block', 'user_request', 'oembed_cache', 'customize_changeset', 'custom_css', 'nav_menu_item'))) {
				continue;
			}

			$count = wp_count_posts($type);

			$args = array(
				'name' => ('ba-cheetah-template' == $type) ? 'Builder Templates' : 'WordPress ' . $type_object->label,
				'data' => ($count->inherit > 0) ? $count->inherit : $count->publish,
			);
			self::register('wp_type_count_' . $type, $args);
		}

	}
}
add_action('plugins_loaded', array('BA_CHEETAH_Debug', 'init'));
