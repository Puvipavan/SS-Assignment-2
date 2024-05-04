<?php if ( ! empty( $settings->height ) ) : ?>
.ba-cheetah-node-<?php echo $id; ?> .ba-module__carousel {
	height: <?php echo $settings->height; ?>px;
}
<?php endif; ?>

.ba-cheetah-node-<?php echo $id; ?> .keen-slider__parent,
.ba-cheetah-node-<?php echo $id; ?> .keen-slider__slide,
.ba-cheetah-node-<?php echo $id; ?> .ba-cheetah-photo-img {
    height: <?php echo $settings->height; ?>px !important;
}
.ba-cheetah-node-<?php echo $id; ?> .thumbnail.keen-slider__parent,
.ba-cheetah-node-<?php echo $id; ?> .thumbnail .keen-slider__slide,
.ba-cheetah-node-<?php echo $id; ?> .thumbnail .swiper__slide{
    height: <?php echo $settings->heightThumbnail; ?>px !important;
}

<?php if ( $settings->styleGallery === 'thumbnail' ) : ?>
.ba-cheetah-node-<?php echo $id; ?> .ba-module__carousel {
    height: <?php echo $settings->height + $settings->heightThumbnail; ?>px !important;
}
<?php endif; ?>
