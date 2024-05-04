<?php

/**
 * Manages settings that can be used to grant or
 * limit access to builder features.
 *

 */
final class BACheetahUserAccess {

	/**
	 * An array of registered data for each setting.
	 *

	 * @access private
	 * @var array $registered_settings
	 */
	static private $registered_settings = array();

	/**
	 * A cached array of saved settings.
	 *

	 * @access private
	 * @var array $settings
	 */
	static private $settings = null;

	/**
	 * Initialize user access.
	 *

	 * @return void
	 */
	static public function init() {
		add_action( 'after_setup_theme', __CLASS__ . '::register_default_settings' );
	}

	/**
	 * Registers a single user access setting.
	 *

	 * @param string $key The setting key.
	 * @param array $data The setting data.
	 * @return void
	 */
	static public function register_setting( $key, $data ) {
		if ( ! isset( $data['group'] ) ) {
			$data['group'] = __( 'Misc', 'ba-cheetah' );
		}
		if ( ! isset( $data['order'] ) ) {
			$data['order'] = '10';
		}
		self::$registered_settings[ $key ] = $data;
		self::$settings                    = null; // must bust the settings cache.
	}

	/**
	 * Returns the registered user access settings.
	 *

	 * @return array
	 */
	static public function get_registered_settings() {
		return self::$registered_settings;
	}

	/**
	 * Returns the registered user access settings in their
	 * defined groups.
	 *

	 * @return array
	 */
	static public function get_grouped_registered_settings() {
		$groups   = array();
		$settings = self::$registered_settings;

		uasort( $settings, array( __CLASS__, 'sort' ) );

		foreach ( $settings as $key => $data ) {

			if ( ! isset( $groups[ $data['group'] ] ) ) {
				$groups[ $data['group'] ] = array();
			}
			$groups[ $data['group'] ][ $key ] = $data;
		}
		return $groups;
	}

	/**
	 * Custom sort function instead of create_function which is deprecated in php 7.2

	 * TODO when we ditch php5 we can use the spaceship here <=>
	 */
	private static function sort( $a, $b ) {
		return ( $a['order'] > $b['order'] ) ? 1 : 0;
	}

	/**
	 * Returns the saved user access settings and merges in
	 * any default roles that haven't been saved.
	 *

	 * @return array
	 */
	static public function get_saved_settings() {
		if ( self::$settings ) {
			return self::$settings;
		}

		$roles       = self::get_all_roles();
		$settings    = BACheetahModel::get_admin_settings_option( '_ba_cheetah_user_access', true );
		$ms_settings = BACheetahModel::get_admin_settings_option( '_ba_cheetah_user_access', false );
		$ms_support  = BACheetahAdminSettings::multisite_support();

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		foreach ( self::$registered_settings as $key => $data ) {

			if ( ! isset( $settings[ $key ] ) ) {
				if ( $ms_support && isset( $ms_settings[ $key ] ) ) {
					$settings[ $key ] = $ms_settings[ $key ];
				} else {
					$settings[ $key ] = array();
				}
			}

			foreach ( $roles as $role_key => $role_data ) {

				if ( ! isset( $settings[ $key ][ $role_key ] ) ) {

					if ( ! isset( $data['default'] ) || ! $data['default'] ) {
						$settings[ $key ][ $role_key ] = false;
					} elseif ( is_array( $data['default'] ) ) {

						if ( in_array( $role_key, $data['default'] ) ) {
							$settings[ $key ][ $role_key ] = true;
						} else {
							$settings[ $key ][ $role_key ] = false;
						}
					} else {
						$settings[ $key ][ $role_key ] = true;
					}
				}
			}
		}

		self::$settings = $settings;

		return $settings;
	}

	/**
	 * Returns the raw user access settings without any
	 * defaults merged in.
	 *

	 * @return array
	 */
	static public function get_raw_settings() {
		$settings = BACheetahModel::get_admin_settings_option( '_ba_cheetah_user_access', true );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		return $settings;
	}

	/**
	 * Saves the user access settings.
	 *

	 * @param array $data The user access data to save.
	 * @return void
	 */
	static public function save_settings( $data = array() ) {
		$roles        = self::get_all_roles();
		$settings     = array();
		$ms_support   = BACheetahAdminSettings::multisite_support();
		$ms_overrides = $ms_support && isset( $_POST['ba_cheetah_ua_override_ms'] ) ? $_POST['ba_cheetah_ua_override_ms'] : array();

		foreach ( self::$registered_settings as $registered_key => $registered_data ) {

			if ( ! isset( $data[ $registered_key ] ) ) {
				$data[ $registered_key ] = array();
			}
		}

		foreach ( $data as $data_key => $data_roles ) {

			if ( ! is_network_admin() && $ms_support && ! isset( $ms_overrides[ $data_key ] ) ) {
				continue;
			}

			$settings[ $data_key ] = array();

			foreach ( $roles as $role_key => $role_data ) {
				$settings[ $data_key ][ $role_key ] = in_array( $role_key, $data_roles ) ? true : false;
			}
		}

		self::$settings = null;

		BACheetahModel::update_admin_settings_option( '_ba_cheetah_user_access', $settings, false );
	}

	/**
	 * Gets all roles that can be used for user access settings.
	 *

	 * @return array
	 */
	static public function get_all_roles() {
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/user.php' );
		}

		$editable_roles = get_editable_roles();
		$roles          = array();
		$caps           = apply_filters( 'ba_cheetah_user_access_capabilities', array( 'edit_posts' ) );

		foreach ( $editable_roles as $role => $data ) {
			foreach ( $caps as $cap ) {
				if ( isset( $data['capabilities'][ $cap ] ) && 1 == $data['capabilities'][ $cap ] ) {
					$roles[ $role ] = $data['name'];
				}
			}
		}

		return $roles;
	}

	/**
	 * Checks to see if the current user has access to a specific
	 * builder feature. Not meant as a security feature but more
	 * as a guide rail by simplifying the interface for clients.
	 *

	 * @param string $key The feature key to check.
	 * @return bool
	 */
	static public function current_user_can( $key ) {
		$user     = wp_get_current_user();
		$settings = self::get_saved_settings();

		// Return false if no settings saved.
		if ( ! isset( $settings[ $key ] ) ) {
			return false;
		}

		// Make sure super admins have administrator access.
		if ( is_multisite() && is_super_admin() && ! in_array( 'administrator', $user->roles ) ) {
			$user->roles[] = 'administrator';
		}

		// Check the user's roles against the saved settings.
		foreach ( $user->roles as $role ) {

			// Return true if the user has access.
			if ( isset( $settings[ $key ][ $role ] ) && $settings[ $key ][ $role ] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Registers the default user access settings.
	 *

	 * @private
	 * @return void
	 */
	static function register_default_settings() {
		self::register_setting( 'builder_access', array(
			'default'     => 'all',
			'group'       => __( 'Frontend', 'ba-cheetah' ),
			'label'       => __( 'Builder Access', 'ba-cheetah' ),
			'description' => __( 'The selected roles will have access to the builder for editing posts, pages, and CPTs.', 'ba-cheetah' ),
			'order'       => '1',
		) );

		self::register_setting( 'unrestricted_editing', array(
			'default'     => 'all',
			'group'       => __( 'Frontend', 'ba-cheetah' ),
			'label'       => __( 'Unrestricted Editing', 'ba-cheetah' ),
			'description' => __( 'The selected roles will have unrestricted access to all editing features within the builder.', 'ba-cheetah' ),
			'order'       => '2',
		) );
	}
}

BACheetahUserAccess::init();
