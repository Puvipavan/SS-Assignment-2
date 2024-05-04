<div id="ba-cheetah-tools-form" class="ba-cheetah-settings-form">

	<h3 class="ba-cheetah-settings-form-header"><?php _e('Cache', 'ba-cheetah'); ?></h3>

	<form id="cache-form" action="<?php BACheetahAdminSettings::render_form_action('tools'); ?>" method="post">
		<div class="ba-cheetah-settings-form-content">
			<p><?php _e('A CSS and JavaScript file is dynamically generated and cached each time you create a new layout. Sometimes the cache needs to be refreshed when you migrate your site to another server or update to the latest version. If you are running into any issues, please try clearing the cache by clicking the button below.', 'ba-cheetah'); ?></p>
			<p><?php _e('Clearing cache will also clear the cache for Builderall templates.', 'ba-cheetah'); ?></p>
			<?php if (is_network_admin()) : ?>
				<p><strong><?php _e('NOTE:', 'ba-cheetah'); ?></strong> <?php _e('This applies to all sites on the network.', 'ba-cheetah'); ?></p>
			<?php elseif (!is_network_admin() && is_multisite()) : ?>
				<p><strong><?php _e('NOTE:', 'ba-cheetah'); ?></strong> <?php _e('This only applies to this site. Please visit the Network Admin Settings to clear the cache for all sites on the network.', 'ba-cheetah'); ?></p>
			<?php endif; ?>

		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e('Clear Cache', 'ba-cheetah'); ?>" />
			<?php wp_nonce_field('cache', 'ba-cheetah-cache-nonce'); ?>
		</p>
	</form>
	<hr />

	<?php
	/*
	if ( version_compare( PHP_VERSION, '5.3.0', '>' ) && class_exists( '\BACheetahCacheClear\Plugin' ) ) {
		include BA_CHEETAH_CACHE_HELPER_DIR . 'includes/admin-settings-cache-plugins.php';
	}
	*/

	$debug = get_transient( 'ba_cheetah_debug_mode' );
	if ( $debug ) {
		$expire_opt = get_option( '_transient_timeout_ba_cheetah_debug_mode' );
		$datetime1  = new DateTime( 'now' );
		$datetime2  = new DateTime( gmdate( 'Y-m-d H:i:s', $expire_opt ) );
		$interval   = $datetime1->diff( $datetime2 );
	}
	?>
	<?php $header = ( $debug ) ? __( 'Support Mode Enabled', 'ba-cheetah' ) : __( 'Support Mode Disabled', 'ba-cheetah' ); ?>
	<h3 class="ba-cheetah-settings-form-header"><?php echo $header; ?></h3>

	<form id="debug-form" action="<?php BACheetahAdminSettings::render_form_action( 'tools' ); ?>" method="post">
		<div class="ba-cheetah-settings-form-content">
			<?php if ( ! $debug ) : ?>
			<p><?php _e( 'Enable Support mode to generate a unique support URL.', 'ba-cheetah' ); ?></p>
		<?php else : ?>
			<p><?php _e( 'Copy this unique URL and send it to support as directed.', 'ba-cheetah' ); ?></p>
		<?php endif; ?>
			<?php
			if ( $debug ) :
				$url = add_query_arg( array(
					'babuilderdebug' => $debug,
				), site_url() );
				?>
				<p><?php printf( '<code>%s</code>', $url ); ?></p>
				<p><?php printf( 'Link will expire in <strong>%s</strong>', $interval->format( '%d days %h hours %i minutes' ) ); ?></p>
			<?php endif; ?>
		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php echo ( $debug ) ? esc_attr__( 'Disable Support Mode', 'ba-cheetah' ) : esc_attr__( 'Enable Support Mode', 'ba-cheetah' ); ?>" />
			<?php wp_nonce_field( 'debug', 'ba-cheetah-debug-nonce' ); ?>
		</p>
	</form>
</div>