<?php

/**
 * Handles logic for user specific settings.
 *

 */
class BACheetahUserSettings {

	const DEFAULT_SIDEBAR_POSITION = 'right';

	const DEFAULT_SIDEBAR_POSITION_RTL = 'left';

	const DEFAULT_SIDEBAR_WIDTH = 380;

	/**

	 * @return void
	 */
	static public function init() {
		BACheetahAJAX::add_action( 'save_ui_skin', __CLASS__ . '::save_ui_skin', array( 'skin_name' ) );
		BACheetahAJAX::add_action( 'save_lightbox_position', __CLASS__ . '::save_lightbox_position', array( 'data' ) );
		BACheetahAJAX::add_action( 'save_pinned_ui_position', __CLASS__ . '::save_pinned_ui_position', array( 'data' ) );
	}

	/**

	 * @return array
	 */
	static public function get() {
		$meta     = get_user_meta( get_current_user_id(), 'ba_cheetah_user_settings', true );
		$defaults = array(
			'skin'     => 'light',
			'lightbox' => null,
			'pinned'   => [
				'width' => self::DEFAULT_SIDEBAR_WIDTH,
				'position' => is_rtl() ? self::DEFAULT_SIDEBAR_POSITION_RTL : self::DEFAULT_SIDEBAR_POSITION,
			]
		);

		if ( ! $meta ) {
			$meta = array();
		}

		return array_merge( $defaults, $meta );
	}

	/**

	 * @param array $data
	 * @return mixed
	 */
	static public function update( $data ) {
		return update_user_meta( get_current_user_id(), 'ba_cheetah_user_settings', $data );
	}

	/**
	 * Handle saving UI Skin type.
	 *

	 * @param string $name
	 * @return array
	 */
	static public function save_ui_skin( $name ) {
		$settings         = self::get();
		$settings['skin'] = $name;

		return array(
			'saved' => self::update( $settings ),
			'name'  => $name,
		);
	}

	/**
	 * Handle saving the lightbox position.
	 *

	 * @param array $data
	 * @return array
	 */
	static public function save_lightbox_position( $data ) {
		$settings             = self::get();
		$settings['lightbox'] = $data;

		return self::update( $settings );
	}

	/**
	 * Handle saving the lightbox position.
	 *

	 * @param array $data
	 * @return array
	 */
	static public function save_pinned_ui_position( $data ) {
		$settings = self::get();
		$settings = array_merge( $settings, $data );

		return self::update( $settings );
	}
}

BACheetahUserSettings::init();
