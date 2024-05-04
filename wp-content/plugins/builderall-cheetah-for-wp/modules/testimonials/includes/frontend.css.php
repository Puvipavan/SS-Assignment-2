.ba-node-<?php echo $id; ?> .testimonials__list {
	grid-template-columns: repeat(<?php echo $settings->columns ?>, 1fr);
}


.ba-node-<?php echo $id; ?> .testimonials__box{
	grid-template-rows: <?php echo $module->get_box_template_rows() ?>;
	
	<?php if ($settings->box_background_type == 'solid' && !empty( $settings->box_background)) : ?>
	background: <?php echo BACheetahColor::hex_or_rgb( $settings->box_background, 'transparent' ); ?>;
	<?php endif; ?>

	<?php if ($settings->box_background_type == 'gradient' && !empty( $settings->box_background_gradient)) : ?>
	background: <?php echo BACheetahColor::gradient( $settings->box_background_gradient ); ?>;
	<?php endif; ?>
}


.ba-node-<?php echo $id; ?> .testimonials__avatar{
	<?php echo $module->get_grid_row('avatar') ?>
}


.ba-node-<?php echo $id; ?> .testimonials__box .testimonials__content{
	color:  <?php echo BACheetahColor::hex_or_rgb($settings->content_color, 'initial'); ?>;
	background: <?php echo BACheetahColor::hex_or_rgb($settings->content_background, 'transparent'); ?>;
	<?php echo $module->get_grid_row('content') ?>
}


.ba-node-<?php echo $id; ?> .testimonials__box .testimonials__credits{
	background: <?php echo BACheetahColor::hex_or_rgb($settings->author_background, 'transparent'); ?>;
	<?php echo $module->get_grid_row('author') ?>
}
.ba-node-<?php echo $id; ?> .testimonials__box .testimonials__credits .testimonials__author{
	color:  <?php echo BACheetahColor::hex_or_rgb($settings->author_color, 'initial'); ?>;
}
.ba-node-<?php echo $id; ?> .testimonials__box .testimonials__credits .testimonials__role{
	color:  <?php echo BACheetahColor::hex_or_rgb($settings->role_color, 'initial'); ?>;
}

.ba-node-<?php echo $id; ?> .splide__arrows .splide__arrow,
.ba-node-<?php echo $id; ?> .splide__pagination__page {
	background: <?php echo BACheetahColor::hex_or_rgb($settings->carousel_nav_background, 'initial'); ?>;
}
.ba-node-<?php echo $id; ?> .splide__arrow svg {
	fill: <?php echo BACheetahColor::hex_or_rgb($settings->carousel_nav_emphasis_color, 'initial'); ?>;
}
.ba-node-<?php echo $id; ?> .splide__pagination__page.is-active {
	background: <?php echo BACheetahColor::hex_or_rgb($settings->carousel_nav_emphasis_color, 'initial'); ?>;
}

<?php 

// Columns responsive
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .testimonials__list",
	'media' => 'medium',
	'enabled' => !empty($settings->columns_medium) && !$module->is_carousel_active(),
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns_medium . ', 1fr)',
	),
));
BACheetahCSS::rule( array(
	'selector' => ".ba-node-$id .testimonials__list",
	'media' => 'responsive',
	'enabled' => !empty($settings->columns_responsive) && !$module->is_carousel_active(),
	'props' => array(
		'grid-template-columns' => 'repeat(' . $settings->columns_responsive . ', 1fr)',
	),
));

// gap
BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'gap',
	'enabled'	   => !$module->is_carousel_active(),
	'selector'     => ".ba-node-$id .testimonials__list",
	'prop'         => 'gap',
	'unit'		   => 'px'
));

// Avatar size - responsive
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'avatar_size',
	'selector'     => ".ba-node-$id .testimonials__box .testimonials__avatar",
	'props'        => array(
		'width' => array(
			'value' => $settings->avatar_size,
			'unit' =>  $settings->avatar_size_unit
		),
		'height' =>array(
			'value' => $settings->avatar_size,
			'unit' =>  $settings->avatar_size_unit
		),
	)
));

// Avatar margin - responsive
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'avatar_margin',
	'selector'     => ".ba-node-$id .testimonials__box .testimonials__avatar",
	'unit'         => 'px',
	'props'        => array(
		'margin-top'    => 'avatar_margin_top',
		'margin-right'  => 'avatar_margin_right',
		'margin-bottom' => 'avatar_margin_bottom',
		'margin-left'   => 'avatar_margin_left',
	),
));

// if the avatar has a negative margin inside the carousel, 
// it needs to compensate for this margin in the box so it doesn't get cut by the overflow
BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'avatar_margin_top',
	'enabled'	   => $settings->avatar_margin_top < 0 && $module->is_carousel_active(),
	'selector'     => ".ba-node-$id .testimonials__box",
	'props'		   => array(
		'margin-top' => array(
			'value' => $settings->avatar_margin_top * -1,
			'unit' =>  'px'
		),
	)
));
BACheetahCSS::responsive_rule(array(
	'settings'     => $settings,
	'setting_name' => 'avatar_margin_bottom',
	'enabled'	   => $settings->avatar_margin_bottom < 0 && $module->is_carousel_active(),
	'selector'     => ".ba-node-$id .testimonials__box",
	'props'		   => array(
		'margin-bottom' => array(
			'value' => $settings->avatar_margin_bottom * -1,
			'unit' =>  'px'
		),
	)
));

// avatar border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'avatar_border',
	'selector'  => ".ba-node-$id .testimonials__box .testimonials__avatar",
));

// box border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'box_border',
	'selector'  => ".ba-node-$id .testimonials__box",
));

// box Padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'content_padding',
	'selector'     => ".ba-node-$id .testimonials__box .testimonials__content",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'content_padding_top',
		'padding-right'  => 'content_padding_right',
		'padding-bottom' => 'content_padding_bottom',
		'padding-left'   => 'content_padding_left',
	),
));

// content Padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'box_padding',
	'selector'     => ".ba-node-$id .testimonials__box",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'box_padding_top',
		'padding-right'  => 'box_padding_right',
		'padding-bottom' => 'box_padding_bottom',
		'padding-left'   => 'box_padding_left',
	),
));

// content typorgraphy
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'content_typography',
	'selector'    => ".ba-node-$id .testimonials__box .testimonials__content",
));

// author Padding
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'author_padding',
	'selector'     => ".ba-node-$id .testimonials__box .testimonials__credits",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'author_padding_top',
		'padding-right'  => 'author_padding_right',
		'padding-bottom' => 'author_padding_bottom',
		'padding-left'   => 'author_padding_left',
	),
));

// author typorgraphy
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'author_typography',
	'selector'    => ".ba-node-$id .testimonials__box .testimonials__author",
));

// role typorgraphy
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'role_typography',
	'selector'    => ".ba-node-$id .testimonials__box .testimonials__role",
));