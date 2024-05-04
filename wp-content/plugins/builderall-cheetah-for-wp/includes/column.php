<?php $container_element = ( ! empty( $col->settings->container_element ) ? $col->settings->container_element : 'div' ); ?>
<<?php echo esc_html($container_element); ?><?php echo BACheetah::render_column_attributes( $col ); ?>>
	<div class="ba-cheetah-col-content ba-cheetah-node-content">
	<?php BACheetah::render_modules( $col ); ?>
	</div>
</<?php echo esc_html($container_element); ?>>
