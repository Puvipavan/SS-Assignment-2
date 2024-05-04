<?php

/**
 * Helper class for builder extensions.
 *

 */
final class BACheetahExtensions {

	/**
	 * Initializes any extensions found in the extensions directory.
	 *

	 * @param string $path Path to extensions to initialize.
	 * @return void
	 */
	static public function init( $path = null ) {
		$path       = $path ? trailingslashit( $path ) : BA_CHEETAH_DIR . 'extensions/';
		$extensions = glob( $path . '*' );

		if ( ! is_array( $extensions ) ) {
			return;
		}

		foreach ( $extensions as $extension ) {

			if ( ! is_dir( $extension ) ) {
				continue;
			}

			$path = trailingslashit( $extension ) . basename( $extension ) . '.php';

			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}
}

BACheetahExtensions::init();
