<?php
$type 		= isset( $settings->type ) ? $settings->type : 'button'; 	// submit, button, reset
$tag 		= isset( $settings->tag ) ? $settings->tag : 'a';			// a, button
$node_id 	= isset( $settings->id ) && ! empty( $settings->id ) ? $settings->id : "ba-cheetah-node-$id";
?>

<div class="ba-module__button <?php echo $node_id; ?>">

	<!-- submit (eg. mailingboss) --> 
	<?php if ( $type == 'submit' ) : ?>
		<<?php echo $tag; ?>
			class="button__button" 
			type="submit"
			<?php echo $module->get_pixel_attr(); ?>
		>

	<!-- popup -->
	<?php elseif ( in_array($settings->click_action, ['popup', 'video'])) : ?>
		<<?php echo $tag; ?>
			class="button__button" 
			role="button"
			<?php echo $module->get_pixel_attr(); ?>
		>
	
	<!-- link -->
	<?php else : ?>
		<<?php echo $tag; ?>
			href="<?php echo esc_url($settings->link); ?>"
			<?php echo ( isset( $settings->link_download ) && 'yes' === $settings->link_download ) ? ' download' : ''; ?>
			target="<?php echo esc_attr($settings->link_target); ?>"
			class="button__button"
			role="button"
			<?php echo $module->get_rel(); ?>
			<?php echo $module->get_pixel_attr(); ?>
		>
	<?php endif; ?>
		
	<!-- content -->

	<!-- icon before -->
	<?php if ( ! empty( $settings->icon ) && ( 'before' == $settings->icon_position || ! isset( $settings->icon_position ) ) ) :?>
		<i class="button__icon <?php echo esc_attr($settings->icon); ?>" aria-hidden="true"></i>
	<?php endif; ?>

	<!-- text -->
	<?php if (( ! empty( $settings->text ) && $settings->text ) || ( ! empty( $settings->sub_text ) && $settings->sub_text )) : ?>
	<div class="button__content">
		<?php if ( ! empty( $settings->text ) && $settings->text ) : ?>
			<span class="button__text"><?php echo wp_kses_post($settings->text); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $settings->sub_text ) && $settings->sub_text ) : ?>
			<span class="button__subtext"><?php echo wp_kses_post($settings->sub_text); ?></span>
		<?php endif; ?>
	<?php endif; ?>
	</div>

	<!-- icon after -->
	<?php if ( ! empty( $settings->icon ) && 'after' == $settings->icon_position ) :?>
		<i class="button__icon <?php echo esc_attr($settings->icon); ?>" aria-hidden="true"></i>
	<?php endif; ?>
	
	</<?php echo $tag; ?>>
</div>