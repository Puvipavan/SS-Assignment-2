<?php 
$node_selector = ".ba-node-$id.ba-module__mega-menu";

// root menus ?>
<?php echo $node_selector; ?> .mega-menu__nav {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->navbar_background, 'initial'); ?>;
}

<?php echo $node_selector; ?> .mega-menu__nav .mega-menu__nav__item {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->item_color, 'initial'); ?>;
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->item_background, 'initial'); ?>;
	transition-duration: <?php echo $settings->item_hover_transition ?>ms;
}

<?php echo $node_selector; ?> .mega-menu__nav .mega-menu__nav__item:hover,
<?php echo $node_selector; ?> .mega-menu__nav .mega-menu__nav__item.mega-menu__nav__item--active {
	color: <?php echo BACheetahColor::hex_or_rgb($settings->item_color_hover, $settings->item_color); ?>;
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->item_background_hover, $settings->item_background); ?>;
}

<?php echo $node_selector; ?> .mega-menu__content {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->content_background, 'initial'); ?>;
}

<?php 

/**
 * Bar rules
 */
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'navbar_alignment',
	'selector'     => "$node_selector .mega-menu__nav .mega-menu__nav__items",
	'prop'         => 'justify-content',
));

BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'navbar_height',
	'selector'     => "$node_selector .mega-menu__nav .mega-menu__nav__items",
	'prop'         => 'height',
	'unit'			=> 'px'
));
BACheetahCSS::rule( array(
	'selector' => "$node_selector .mega-menu__nav .mega-menu__nav__items",
	'media' => 'responsive',
	'props' => array(
		'height' => 'auto',
		'align-items' => 'initial'
	),
));

BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'navbar_border',
	'selector'  => "$node_selector .mega-menu__nav",
));


/**
 * Item rules
 */

BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'item_border',
	'selector'  => "$node_selector .mega-menu__nav .mega-menu__nav__item",
));

BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'item_border_hover',
	'selector'  => "$node_selector .mega-menu__nav .mega-menu__nav__item:hover, $node_selector .mega-menu__nav .mega-menu__nav__item.mega-menu__nav__item--active",
));


BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'menu_distance',
	'selector'     => "$node_selector .mega-menu__nav .mega-menu__nav__items",
	'unit' 		   => 'px',
	'prop'         => 'gap',
));

BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'icon_distance',
	'selector'     => "$node_selector .mega-menu__nav__item a, $node_selector .mega-menu__nav__item",
	'unit' 		   => 'px',
	'prop'         => 'gap',
));

BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'icon_position',
	'selector'     => "$node_selector .mega-menu__nav__item a, $node_selector .mega-menu__nav__item",
	'prop'         => 'flex-direction',
));

BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'icon_position',
	'selector'     => "$node_selector .mega-menu__nav__item a, $node_selector .mega-menu__nav__item",
	'prop'         => 'flex-direction',
));

BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'menu_padding',
	'selector'     => "$node_selector .mega-menu__nav .mega-menu__nav__items .mega-menu__nav__item",
	'unit'         => 'px',
	'props'        => array(
		'padding-right'  => 'menu_padding',
		'padding-left'   => 'menu_padding',
	),
));

BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'item_typography',
	'selector'    => "$node_selector .mega-menu__nav__item",
));

/**
 * Mega menu logics
 */

BACheetahCSS::rule( array(
	'selector' => "$node_selector .mega-menu__content",
	'enabled' => !BACheetahModel::is_builder_active(),
	'props' => array(
		'position' => 'absolute',
		'width'  => '100%',
		'z-index' => '1',
	),
));
BACheetahCSS::rule( array(
	'selector' => "$node_selector .mega-menu__content",
	'enabled' => !BACheetahModel::is_builder_active(),
	'media' => 'responsive',
	'props' => array(
		'position' => 'relative',
	),
));
BACheetahCSS::rule( array(
	'selector' => "$node_selector .mega-menu__content > .mega-menu__content__item",
	'props' => array(
		'display' => 'none'
	),
));
