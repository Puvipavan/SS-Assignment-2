<?php
$classes = array(
	"ba-module__testimonials",
	"ba-node-$id",
	"ba-module__testimonials--".$settings->layout
);
?>

<section class="<?php echo join(' ', $classes) . $module->splide_class(' splide'); ?>">

	<div class="<?php echo $module->splide_class(' splide__track') ?>">
		<div class="testimonials__list <?php echo $module->splide_class(' splide__list') ?>">
			<?php foreach ($settings->testimonials as $key => $testimonial):
					if (empty($testimonial->content)) continue;
				?>
				<div class="testimonials__box <?php echo $module->splide_class(' splide__slide') ?>">
					<?php if ($testimonial->photo): ?>
						<div class="testimonials__avatar">
							<img
								src="<?php echo esc_url($testimonial->photo == 'default' ? $module::DEFAULT_AVATAR : $testimonial->photo_src) ?>"
								alt="<?php echo esc_attr($testimonial->author); ?>"
							>
						</div>
					<?php endif; ?>

					<div class="testimonials__content">
						<?php global $wp_embed; 
							echo wpautop( $wp_embed->autoembed( wp_kses_post($testimonial->content) ) );
						?>
					</div>

					<cite class="testimonials__credits">
						<span class="testimonials__author">
							<?php echo esc_html($testimonial->author); ?>
						</span>
						<span class="testimonials__role">
							<?php echo esc_html($testimonial->role); ?>
						</span>
					</cite>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

</section>