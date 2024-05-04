<?php

$settings = \BACheetahCacheClear\Plugin::get_settings();
$plugins  = \BACheetahCacheClear\Plugin::get_plugins();
?>

	<h3 class="ba-cheetah-settings-form-header"><?php _e( 'Cache Clearing Tool', 'ba-cheetah' ); ?></h3>
	<form id="cache-plugins-form" action="<?php BACheetahAdminSettings::render_form_action( 'tools' ); ?>" method="post">

		<div class="ba-cheetah-settings-form-content">
			<p><?php _e( 'This tool applies to caches created by the following:', 'ba-cheetah' ); ?></p>
			<?php echo $plugins; ?>
			<p>
				<?php /* translators: %s: branded builder name */ ?>
				<?php printf( __( 'Enable the following setting to clear the caches created by any of these caching plugins. If enabled, cache clearing occurs when layouts and templates are saved and when WordPress finishes updating plugins and themes. This setting also defines the DONOTCACHEPAGE constant, which is respected by most cache plugins, to keep the page from being cached when the %s editor is active.', 'ba-cheetah' ), BACheetahModel::get_branding() ); ?>
			</p>
			<p>
				<label>
					<input type="checkbox" name="ba-cheetah-cache-plugins-enabled" value="1" <?php checked( $settings['enabled'], 1 ); ?> />
					<span><?php _e( 'Enable the Cache Clearing Tool', 'ba-cheetah' ); ?></span>
				</label>
			</p>

			<?php if ( $settings['enabled'] ) : ?>
			<div class="ba-cheetah-cache-plugins-settings">

				<p>
					<?php _e( 'Some hosts use a proxy cache like Varnish or Litespeed. The following setting attempts to invalidate the cache using a remote request. If you are unsure what this does, leave it disabled.', 'ba-cheetah' ); ?>
				</p>
				<p>
					<label>
						<input type="checkbox" name="ba-cheetah-cache-varnish-enabled" value="1" <?php checked( $settings['varnish'], 1 ); ?> />
						<span><?php _e( 'Enable proxy cache clearing', 'ba-cheetah' ); ?></span>
					</label>
				</p>
			</div>

		<?php endif; ?>

		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e( 'Save Cache Clearing Tool Settings', 'ba-cheetah' ); ?>" />
			<?php wp_nonce_field( 'cache-plugins', 'ba-cheetah-cache-plugins-nonce' ); ?>
		</p>
	</form>
	<hr>
