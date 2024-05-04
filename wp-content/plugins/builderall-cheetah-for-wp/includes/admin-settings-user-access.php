<?php $raw_settings = BACheetahUserAccess::get_raw_settings(); ?>
<div id="ba-cheetah-user-access-form" class="ba-cheetah-settings-form">

	<h3 class="ba-cheetah-settings-form-header"><?php _e( 'User Access Settings', 'ba-cheetah' ); ?></h3>
	<p><?php _e( 'Use these settings to limit which builder features users can access.', 'ba-cheetah' ); ?></p>

	<form id="editing-form" action="<?php BACheetahAdminSettings::render_form_action( 'user-access' ); ?>" method="post">
		<div class="ba-cheetah-settings-form-content">
			<?php foreach ( BACheetahUserAccess::get_grouped_registered_settings() as $group => $group_data ) : ?>

				<div class="ba-cheetah-user-access-group">
					<h3><?php echo esc_html($group); ?></h3>
					<?php $i = 1; foreach ( $group_data as $cap => $cap_data ) : ?>
						<div class="ba-cheetah-user-access-setting">
							<h4><?php echo $cap_data['label']; ?><i class="dashicons dashicons-editor-help" title="<?php echo esc_html( $cap_data['description'] ); ?>"></i></h4>
							<?php if ( BACheetahAdminSettings::multisite_support() && ! is_network_admin() ) : ?>
							<label class="ba-cheetah-ua-override-ms-label">
								<input class="ba-cheetah-ua-override-ms-cb" type="checkbox" name="ba_cheetah_ua_override_ms[<?php echo esc_attr($cap); ?>]" value="1" <?php echo ( isset( $raw_settings[ $cap ] ) ) ? 'checked' : ''; ?> />
								<?php _e( 'Override network settings?', 'ba-cheetah' ); ?>
							</label>
							<?php endif; ?>
							<select name="ba_cheetah_user_access[<?php echo $cap; ?>][]" class="ba-cheetah-user-access-select" multiple></select>
						</div>
						<?php if ( 0 === $i % 2 || count( $group_data ) == $i ) : ?>
						<div class="clear"></div>
						<?php endif; ?>
						<?php
						$i++;
						endforeach;
					?>
				</div>

			<?php endforeach; ?>
		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e( 'Save User Access Settings', 'ba-cheetah' ); ?>" />
			<?php wp_nonce_field( 'user-access', 'ba-cheetah-user-access-nonce' ); ?>
		</p>
	</form>
</div>
