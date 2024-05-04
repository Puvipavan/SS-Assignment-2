.ba-module__gallery.ba-node-<?php echo $id; ?>.ba-module__gallery-grid {
    grid-template-columns: repeat(<?php echo $settings->columns ?>, 1fr);
    gap: <?php echo $settings->spacing ?>px;
}

.ba-module__gallery.ba-node-<?php echo $id; ?>.ba-module__gallery-grid .gallery__item .ba-cheetah-photo-content {
    height: <?php echo $settings->max_row_height ?>px;
}

<?php
BACheetahCSS::border_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border',
	'selector'     => ".ba-module__gallery.ba-node-$id .ba-cheetah-photo-content img",
) );

// Columns responsive
BACheetahCSS::rule( array(
	'selector' => ".ba-module__gallery.ba-node-$id.ba-module__gallery-grid",
	'media' => 'medium',
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns_medium . ', 1fr)',
	),
));
BACheetahCSS::rule( array(
	'selector' => ".ba-module__gallery.ba-node-$id.ba-module__gallery-grid",
	'media' => 'responsive',
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns_responsive . ', 1fr)',
	),
));