<?php $container_element = ( ! empty( $row->settings->container_element ) ? $row->settings->container_element : 'div' ); ?>
<<?php echo esc_html($container_element); ?><?php BACheetah::render_row_attributes( $row ); ?>>
	<div class="ba-cheetah-row-content-wrap">
		<?php BACheetah::render_row_bg( $row ); ?>
		<?php do_action( 'ba_cheetah_render_node_layers', $row ); ?>
		<div class="<?php BACheetah::render_row_content_class( $row ); ?>">
		<?php
		// $groups received as a magic variable from template loading.
		foreach ( $groups as $group ) {
			BACheetah::render_column_group( $group );
		}
		?>
		</div>
	</div>
</<?php echo esc_html($container_element); ?>>
