<div id="ba-cheetah-post-types-form" class="ba-cheetah-settings-form">

	<h3 class="ba-cheetah-settings-form-header"><?php _e( 'Post Types', 'ba-cheetah' ); ?></h3>

	<form id="post-types-form" action="<?php BACheetahAdminSettings::render_form_action( 'post-types' ); ?>" method="post">
		<?php if ( BACheetahAdminSettings::multisite_support() && ! is_network_admin() ) : ?>
		<label>
			<input class="ba-cheetah-override-ms-cb" type="checkbox" name="ba-cheetah-override-ms" value="1" <?php echo ( get_option( '_ba_cheetah_post_types' ) ) ? 'checked="checked"' : ''; ?> />
			<?php _e( 'Override network settings?', 'ba-cheetah' ); ?>
		</label>
		<?php endif; ?>

		<div class="ba-cheetah-settings-form-content">
			<?php if ( is_network_admin() ) : ?>

				<p><?php _e( 'Enter a comma separated list of the post types you would like the builder to work with.', 'ba-cheetah' ); ?></p>
				<p><?php _e( 'NOTE: Not all custom post types may be supported.', 'ba-cheetah' ); ?></p>
				<?php

				$saved_post_types = BACheetahModel::get_post_types();

				foreach ( $saved_post_types as $key => $post_type ) {

					if(BACheetahUserTemplatesLayout::is_layout_content_type($post_type)) {
						unset( $saved_post_types[ $key ] );
					}
					
				}

				$saved_post_types = implode( ', ', $saved_post_types );

				?>
				<input type="text" name="ba-cheetah-post-types" value="<?php echo esc_html( $saved_post_types ); ?>" class="regular-text" />
				<p class="description"><?php _e( 'Example: page, post, product', 'ba-cheetah' ); ?></p>

			<?php else : ?>

				<p><?php _e( 'Select the post types you would like the builder to work with.', 'ba-cheetah' ); ?></p>
				<p><?php _e( 'NOTE: Not all custom post types may be supported.', 'ba-cheetah' ); ?></p>

				<?php

				$saved_post_types = BACheetahModel::get_post_types();
				$post_types       = get_post_types( array(
					'public' => true,
				), 'objects' );
				/**
				 * Use this filter to modify the post types that are shown in the admin settings for enabling and disabling post types.
				 * @see ba_cheetah_admin_settings_post_types
				 */
				$post_types = apply_filters( 'ba_cheetah_admin_settings_post_types', $post_types );

				foreach ( $post_types as $post_type ) :

					$checked = in_array( $post_type->name, $saved_post_types ) ? 'checked' : '';

					if ( 'attachment' == $post_type->name ) {
						continue;
					}

					if(BACheetahUserTemplatesLayout::is_layout_content_type($post_type->name)) {
						continue;
					}

					?>
					<p>
						<label>
							<input type="checkbox" name="ba-cheetah-post-types[]" value="<?php echo esc_attr($post_type->name); ?>" <?php echo esc_attr($checked); ?> />
							<?php echo esc_html($post_type->labels->name); ?>
						</label>
					</p>
				<?php endforeach; ?>

			<?php endif; ?>

		</div>
		<p class="submit">
			<input type="submit" name="update" class="button-primary" value="<?php esc_attr_e( 'Save Post Types', 'ba-cheetah' ); ?>" />
			<?php wp_nonce_field( 'post-types', 'ba-cheetah-post-types-nonce' ); ?>
		</p>
	</form>
</div>
