<?php $container_element = ( ! empty( $module->settings->container_element ) ? $module->settings->container_element : 'div' ); ?>
<<?php echo esc_html($container_element); ?><?php BACheetah::render_module_attributes( $module ); ?>>
	<div class="ba-cheetah-module-content ba-cheetah-node-content">
		<?php
		ob_start();

		if ( has_filter( 'ba_cheetah_module_frontend_custom_' . $module->slug ) ) {
			echo apply_filters( 'ba_cheetah_module_frontend_custom_' . $module->slug, (array) $module->settings, $module );
		} else {
			include apply_filters( 'ba_cheetah_module_frontend_file', $module->dir . 'includes/frontend.php', $module );
		}

		$out = ob_get_clean();

		echo apply_filters( 'ba_cheetah_render_module_content', $out, $module );

		?>
	</div>
</<?php echo esc_html($container_element); ?>>
