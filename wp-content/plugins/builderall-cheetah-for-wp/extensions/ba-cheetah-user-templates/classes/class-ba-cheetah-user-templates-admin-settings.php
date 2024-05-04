<?php

/**
 * Admin settings for user defined templates in the builder.
 *

 */
final class BACheetahUserTemplatesAdminSettings {

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init() {
		if ( is_admin() && isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], array( 'ba-cheetah-settings', 'ba-cheetah-multisite-settings' ) ) ) {
			add_action( 'ba_cheetah_admin_settings_render_forms', __CLASS__ . '::admin_settings_render_form' );
			add_action( 'ba_cheetah_admin_settings_save', __CLASS__ . '::save_settings' );
		}
	}

	/**
	 * Renders the admin settings templates form.
	 *

	 * @return void
	 */
	static public function admin_settings_render_form() {
		$enabled_templates = BACheetahModel::get_enabled_templates();

		include BA_CHEETAH_USER_TEMPLATES_DIR . 'includes/admin-settings-templates.php';
	}

	/**
	 * Saves the template settings.
	 *

	 * @return void
	 */
	static public function save_settings() {
		if ( isset( $_POST['ba-cheetah-templates-nonce'] ) && wp_verify_nonce( $_POST['ba-cheetah-templates-nonce'], 'templates' ) ) {

			$enabled_templates = sanitize_text_field( $_POST['ba-cheetah-template-settings'] );

			BACheetahModel::update_admin_settings_option( '_ba_cheetah_enabled_templates', $enabled_templates, true );
		}
	}
}

BACheetahUserTemplatesAdminSettings::init();
