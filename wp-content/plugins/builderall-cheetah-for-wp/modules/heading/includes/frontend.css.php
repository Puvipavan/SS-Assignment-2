<?php if ( ! empty( $settings->color ) ) : ?>
.ba-cheetah-row .ba-cheetah-col .ba-cheetah-node-<?php echo $id; ?> <?php echo $settings->tag; ?>.ba-cheetah-heading a,
.ba-cheetah-row .ba-cheetah-col .ba-cheetah-node-<?php echo $id; ?> <?php echo $settings->tag; ?>.ba-cheetah-heading .ba-cheetah-heading-text,
.ba-cheetah-row .ba-cheetah-col .ba-cheetah-node-<?php echo $id; ?> <?php echo $settings->tag; ?>.ba-cheetah-heading .ba-cheetah-heading-text *,
.ba-cheetah-node-<?php echo $id; ?> <?php echo $settings->tag; ?>.ba-cheetah-heading .ba-cheetah-heading-text {
    color: <?php echo BACheetahColor::hex_or_rgb( $settings->color ); ?>;
}

<?php endif; ?>

<?php if ( ! empty( $settings->color_type ) && $settings->color_type === 'gradient' && BA_CHEETAH_PRO ) : ?>
.ba-cheetah-row .ba-cheetah-col .ba-cheetah-node-<?php echo $id; ?> <?php echo $settings->tag; ?>.ba-cheetah-heading a,
.ba-cheetah-row .ba-cheetah-col .ba-cheetah-node-<?php echo $id; ?> <?php echo $settings->tag; ?>.ba-cheetah-heading .ba-cheetah-heading-text,
.ba-cheetah-row .ba-cheetah-col .ba-cheetah-node-<?php echo $id; ?> <?php echo $settings->tag; ?>.ba-cheetah-heading .ba-cheetah-heading-text *,
.ba-cheetah-node-<?php echo $id; ?> <?php echo $settings->tag; ?>.ba-cheetah-heading .ba-cheetah-heading-text {
    background: <?php echo BACheetahColor::hex_or_rgb_or_gradient('gradient', $settings->color, $settings->color_gradient)?>;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

<?php endif; ?>
<?php

BACheetahCSS::typography_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'typography',
	'selector'     => ".ba-cheetah-node-$id.ba-cheetah-module-heading .ba-cheetah-heading",
) );
