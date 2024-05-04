<?php

/**
 * Generic settings compatibility helper for all node types.
 *

 */
class BACheetahSettingsCompatGeneric extends BACheetahSettingsCompatHelper {

	/**
	 * Filter settings for all node types.
	 *

	 * @param object $settings
	 * @return object
	 */
	public function filter_settings( $settings ) {
		return $settings;
	}
}
