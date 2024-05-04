<?php

$photo    = $module->get_data();
$classes  = $module->get_classes();
$src      = $module->get_src();
$link     = $module->get_link();
$caption  = $module->get_caption();
$alt      = ($caption != null) ? $caption : $photo->alt;
$attrs    = $module->get_attributes();
$filetype = pathinfo( $src, PATHINFO_EXTENSION );
$rel      = $module->get_rel();

?>
<div class="ba-cheetah-photo<?php echo ( ! empty( $settings->crop ) ) ? ' ba-cheetah-photo-crop-' . $settings->crop : ''; ?> ba-cheetah-photo-align-<?php echo $settings->align; ?>"<?php BACheetah::print_schema( ' itemscope itemtype="https://schema.org/ImageObject"' ); ?>>
	<div class="ba-cheetah-photo-content ba-cheetah-photo-img-<?php echo $filetype; ?> <?php echo ( 'below' === $settings->show_caption && $caption) ? 'ba-cheetah-photo-with-caption': ''; ?>">
		<?php if ( ! empty( $link ) ) : ?>
		    <a href="<?php echo esc_url($link); ?>"
               target="<?php echo esc_attr($settings->link_url_target); ?>"
			   itemprop="url"
               data-link-type="<?php echo esc_attr($settings->link_type); ?>"
               data-caption="<?php echo esc_attr($caption); ?>"
               data-title="<?php echo esc_attr($photo->title); ?>"
			   <?php echo $rel; ?> 
			   <?php echo $module->get_pixel_attr(); ?>
               data-description="<?php echo property_exists($photo, 'description') ? esc_attr($photo->description) : ''; ?>">
		<?php endif; ?>
		    <img 
				class="<?php echo esc_attr($classes); ?>"
				src="<?php echo esc_url($src); ?>"
				alt="<?php echo esc_attr($alt); ?>"
				<?php echo $module->get_pixel_attr(); ?>
				itemprop="image" 
				<?php echo $attrs; ?> />
		<?php if ( ! empty( $link ) ) : ?>
		    </a>
		<?php endif; ?>

		<?php if ( 'hover' === $settings->show_caption ) : ?>
		    <div class="ba-cheetah-photo-caption ba-cheetah-photo-caption-hover" itemprop="caption"><?php echo esc_html($caption); ?></div>
		<?php endif; ?>
	</div>
	<?php if ( 'below' === $settings->show_caption && $caption) : ?>
	    <div class="ba-cheetah-photo-caption ba-cheetah-photo-caption-below" itemprop="caption"><?php echo esc_html($caption); ?></div>
	<?php endif; ?>
</div>
