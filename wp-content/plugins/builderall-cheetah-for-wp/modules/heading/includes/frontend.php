<<?php echo $module->get_tag(); ?> class="ba-cheetah-heading">

	<?php if (!empty( $settings->link ) ) : ?>
		<a 
			href="<?php echo esc_url($settings->link); ?>"
			title="<?php echo esc_attr($settings->heading); ?>"
			target="<?php echo esc_attr($settings->link_target); ?>"
			<?php echo $module->get_rel(); ?>
		>
	<?php endif; ?>

	<span class="ba-cheetah-heading-text">
		<?php echo wp_kses_post($settings->heading); ?>
	</span>

	<?php if ( ! empty( $settings->link ) ) : ?>
	</a>
	<?php endif; ?>

</<?php echo $module->get_tag(); ?>>
