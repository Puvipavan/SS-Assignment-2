<?php

if ( ! empty( $settings->color ) ) : ?>
	.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> .ba-cheetah-module-content .ba-cheetah-rich-text,
	.ba-cheetah-content .ba-cheetah-node-<?php echo $id; ?> .ba-cheetah-module-content .ba-cheetah-rich-text * {
		color: <?php echo BACheetahColor::hex_or_rgb( $settings->color ); ?>;
	}
	<?php
endif;

BACheetahCSS::typography_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'typography',
	'selector'     => ".ba-cheetah-content .ba-cheetah-node-$id .ba-cheetah-rich-text, .ba-cheetah-content .ba-cheetah-node-$id .ba-cheetah-rich-text *",
) );
