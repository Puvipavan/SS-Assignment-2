<?php

/**
 * Settings compatibility helper for all module nodes.
 *

 */
class BACheetahSettingsCompatModule extends BACheetahSettingsCompatHelper {

	/**
	 * Filter settings for modules.
	 *

	 * @param object $settings
	 * @return object
	 */
	public function filter_settings( $settings ) {
		$this->handle_animation_inputs( $settings );
		return $settings;
	}
}
