<?php

$enabled_templates = BACheetahModel::get_enabled_templates();

?>
<div id="ba-cheetah-templates-form" class="ba-cheetah-settings-form">

	<h3 class="ba-cheetah-settings-form-header"><?php _e( 'Template Settings', 'ba-cheetah' ); ?></h3>

	<form id="templates-form" action="<?php BACheetahAdminSettings::render_form_action( 'templates' ); ?>" method="post">

		<?php if ( BACheetahAdminSettings::multisite_support() && ! is_network_admin() ) : ?>
		<label>
			<input class="ba-cheetah-override-ms-cb" type="checkbox" name="ba-cheetah-override-ms" value="1" <?php echo ( get_option( '_ba_cheetah_enabled_templates' ) ) ? 'checked="checked"' : ''; ?> />
			<?php _e( 'Override network settings?', 'ba-cheetah' ); ?>
		</label>
		<?php endif; ?>

		<div class="ba-cheetah-settings-form-content">

			<h4><?php _e( 'Enable Templates', 'ba-cheetah' ); ?></h4>
			<p><?php _e( 'Use this setting to enable or disable templates in the builder interface.', 'ba-cheetah' ); ?></p>
			<select name="ba-cheetah-template-settings">
				<option value="enabled" <?php selected( $enabled_templates, 'enabled' ); ?>><?php _e( 'Enable All Templates', 'ba-cheetah' ); ?></option>
				<option value="core" <?php selected( $enabled_templates, 'core' ); ?>><?php _e( 'Enable Core Templates Only', 'ba-cheetah' ); ?></option>
				<option value="user" <?php selected( $enabled_templates, 'user' ); ?>><?php _e( 'Enable User Templates Only', 'ba-cheetah' ); ?></option>
				<option value="disabled" <?php selected( $enabled_templates, 'disabled' ); ?>><?php _e( 'Disable All Templates', 'ba-cheetah' ); ?></option>
			</select>
			<?php do_action( 'ba_cheetah_admin_settings_templates_form' ); ?>
		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e( 'Save Template Settings', 'ba-cheetah' ); ?>" />
			<?php wp_nonce_field( 'templates', 'ba-cheetah-templates-nonce' ); ?>
		</p>
	</form>
</div>
