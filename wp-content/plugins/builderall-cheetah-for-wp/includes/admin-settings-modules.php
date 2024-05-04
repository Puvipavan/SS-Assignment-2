<?php
if ( BACheetah::is_module_disable_enabled() ) {
	$used_modules = array();

	$args = array(
		'post_type'      => BACheetahModel::get_post_types(),
		'post_status'    => 'publish',
		'meta_key'       => '_ba_cheetah_enabled',
		'meta_value'     => '1',
		'posts_per_page' => -1,
	);

	$query           = new WP_Query( $args );
	$data['enabled'] = count( $query->posts );

	/**
	* Using the array of pages/posts using builder get a list of all used modules
	*/
	if ( is_array( $query->posts ) && ! empty( $query->posts ) ) {
		foreach ( $query->posts as $post ) {
			$meta = get_post_meta( $post->ID, '_ba_cheetah_data', true );
			foreach ( (array) $meta as $node_id => $node ) {
				if ( @isset( $node->type ) && 'module' === $node->type ) { // @codingStandardsIgnoreLine
					if ( ! isset( $used_modules[ $node->settings->type ][ $post->post_type ] ) ) {
						$used_modules[ $node->settings->type ][ $post->post_type ] = array();
					}

					if ( ! isset( $used_modules[ $node->settings->type ][ $post->post_type ][ $post->ID ] ) ) {
						$used_modules[ $node->settings->type ][ $post->post_type ][ $post->ID ] = 1;
					} else {
						$used_modules[ $node->settings->type ][ $post->post_type ][ $post->ID ] ++;
					}


					if ( ! isset( $used_modules[ $node->settings->type ][ $post->post_type ]['total'] ) ) {
						$used_modules[ $node->settings->type ][ $post->post_type ]['total'] = 1;
					} else {
						$used_modules[ $node->settings->type ][ $post->post_type ]['total'] ++;
					}
				}
			}
		}
	}
}

?>
<div id="ba-cheetah-modules-form" class="ba-cheetah-settings-form">
	<h3 class="ba-cheetah-settings-form-header"><?php _e( 'Enabled Elements', 'ba-cheetah' ); ?></h3>

	<form id="modules-form" action="<?php BACheetahAdminSettings::render_form_action( 'modules' ); ?>" method="post">

		<?php if ( BACheetahAdminSettings::multisite_support() && ! is_network_admin() ) : ?>
		<label>
			<input class="ba-cheetah-override-ms-cb" type="checkbox" name="ba-cheetah-override-ms" value="1" <?php echo ( get_option( '_ba_cheetah_enabled_modules' ) ) ? 'checked="checked"' : ''; ?> />
			<?php _e( 'Override network settings?', 'ba-cheetah' ); ?>
		</label>
		<?php endif; ?>

		<div class="ba-cheetah-settings-form-content">

			<p><?php _e( 'Check or uncheck elements below to enable or disable them.', 'ba-cheetah' ); ?></p>
			<?php


			$categories      = BACheetahModel::get_categorized_modules( true );
			$enabled_modules = BACheetahModel::get_enabled_modules();
			$checked         = in_array( 'all', $enabled_modules ) ? 'checked' : '';

			?>
			<label>
				<input class="ba-cheetah-module-all-cb" type="checkbox" name="ba-cheetah-modules[]" value="all" <?php echo esc_attr($checked); ?> />
				<?php _ex( 'All', 'Plugin setup page: Modules.', 'ba-cheetah' ); ?>
			</label>
			<?php foreach ( $categories as $title => $modules ) : ?>
			<h3><?php echo esc_html($title); ?></h3>
				<?php

				if ( __( 'WordPress Widgets', 'ba-cheetah' ) == $title ) :

					$checked = in_array( 'widget', $enabled_modules ) ? 'checked' : '';

					?>
				<p>
					<label>
						<input class="ba-cheetah-module-cb" type="checkbox" name="ba-cheetah-modules[]" value="widget" <?php echo esc_attr($checked); ?> />
						<?php echo esc_html($title); ?>
					</label>
				</p>
					<?php

					continue;

				endif;
				foreach ( $modules as $module ) :

					$checked = in_array( $module->slug, $enabled_modules ) ? 'checked' : '';

					?>
				<p>
					<label>
						<input class="ba-cheetah-module-cb" type="checkbox" name="ba-cheetah-modules[]" value="<?php echo esc_attr($module->slug); ?>" <?php echo esc_attr($checked); ?> />
						<?php
						$text = 'Not used';
						if ( isset( $used_modules[ $module->slug ] ) ) {
							$txt = array();
							foreach ( $used_modules[ $module->slug ] as $type => $used ) {
								$type  = str_replace( 'ba-cheetah-theme-layout', 'Themer Layout', $type );
								$type  = str_replace( 'ba-cheetah-template', 'Builder Template', $type );
								$txt[] = sprintf( '%s times on %s %ss', $used['total'], count( $used ) - 1, ucfirst( $type ) );
							}
							$text = implode( ', ', $txt );
						}
						?>
						<?php echo esc_html(( BACheetah::is_module_disable_enabled() ) ? sprintf( '%s ( %s )', $module->name, $text ) : $module->name); ?>
					</label>
				</p>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e( 'Save Element Settings', 'ba-cheetah' ); ?>" />
			<?php wp_nonce_field( 'modules', 'ba-cheetah-modules-nonce' ); ?>
		</p>
	</form>
</div>
