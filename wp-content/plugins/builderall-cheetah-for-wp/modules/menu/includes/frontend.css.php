<?php 
$node_selector = ".ba-node-$id.ba-module__menu .menu__container";
$node_selector_mobile = ".ba-node-$id.ba-module__menu .menu__mobile"

// root menus ?>
<?php echo $node_selector; ?> > ul {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->navbar_background, 'initial'); ?>;
	justify-content: <?php echo $settings->navbar_alignment ?>;
	min-height: <?php echo $settings->navbar_height ?>px;
}

<?php echo $node_selector; ?> > ul > li.menu-item {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->menu_background, 'transparent'); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->menu_color, 'initial'); ?>;
}

<?php echo $node_selector; ?> > ul > li.menu-item.current-menu-item {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->menu_background_active ? $settings->menu_background_active : $settings->menu_background); ?>;
}

<?php echo $node_selector; ?> > ul > li.menu-item:hover {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->menu_background_hover ? $settings->menu_background_hover : $settings->menu_background); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->menu_color_hover ? $settings->menu_color_hover : $settings->menu_color); ?>;
}

<?php // submenus ?>

<?php echo $node_selector; ?> > ul > li.menu-item .sub-menu {
	min-width: <?php echo $settings->submenu_min_width.$settings->submenu_min_width_unit;?>;
}

<?php echo $node_selector; ?> > ul > li.menu-item .sub-menu li.menu-item{
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->submenu_background, 'initial'); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->submenu_color, 'initial'); ?>;
}

<?php echo $node_selector; ?> > ul > li.menu-item .sub-menu li.menu-item.current-menu-item {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->submenu_background_active ? $settings->submenu_background_active : $settings->submenu_background); ?>;
}

<?php echo $node_selector; ?> > ul > li.menu-item .sub-menu li.menu-item a:hover {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->submenu_background_hover ? $settings->submenu_background_hover : $settings->submenu_background); ?>;
}

<?php echo $node_selector; ?> > ul a {
	justify-content: <?php echo $settings->navbar_alignment ?>;
}

<?php // mobile ?>
<?php echo $node_selector_mobile; ?> {
	flex-direction: <?php echo $settings->logo_position?>;
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->navbar_background, 'initial'); ?>;
	height: <?php echo $settings->navbar_height ?>px;
}

<?php echo $node_selector_mobile; ?> .menu__mobile-logo img {
	height: <?php echo $settings->logo_size?>%;
}

<?php echo $node_selector_mobile; ?> .menu__mobile-hamburger {
	background-color: <?php echo BACheetahColor::hex_or_rgb($settings->hamburger_background, 'initial'); ?>;
	color: <?php echo BACheetahColor::hex_or_rgb($settings->hamburger_color, 'initial'); ?>;
	width: <?php echo $settings->navbar_height ?>px;
}

<?php

// Make the position of the child dropdowns start from the top of their border and not the box
if (isset($settings->submenu_border['width']['top']) && !empty($settings->submenu_border['style']) && $settings->submenu_border['style'] != 'none') {
	echo $node_selector . ' > ul li.menu-item .sub-menu li.menu-item .sub-menu {';
	echo 'top: -' . $settings->submenu_border['width']['top'] . 'px';
	echo '}';
}

// space between menus
BACheetahCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'menu_distance',
	'selector'     => "$node_selector > ul",
	'unit' 		   => 'px',
	'prop'         => 'gap',
));

// Padding root menu
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'menu_padding',
	'selector'     => "$node_selector > ul > li.menu-item a",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'menu_padding_top',
		'padding-right'  => 'menu_padding_right',
		'padding-bottom' => 'menu_padding_bottom',
		'padding-left'   => 'menu_padding_left',
	),
));

// Navbar border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'navbar_border',
	'selector'  => "$node_selector > ul",
));

// Padding submenu
BACheetahCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'submenu_padding',
	'selector'     => "$node_selector > ul > li.menu-item .sub-menu li.menu-item a",
	'unit'         => 'px',
	'props'        => array(
		'padding-top'    => 'submenu_padding_top',
		'padding-right'  => 'submenu_padding_right',
		'padding-bottom' => 'submenu_padding_bottom',
		'padding-left'   => 'submenu_padding_left',
	),
));

// Submenu border
BACheetahCSS::border_field_rule(array(
	'settings'  => $settings,
	'setting_name'  => 'submenu_border',
	'selector'  => "$node_selector > ul > li.menu-item .sub-menu",
));

// typography item
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'menu_typography',
	'selector'    => "$node_selector > ul > li.menu-item",
));

// typography subitem
BACheetahCSS::typography_field_rule(array(
	'settings'    => $settings,
	'setting_name'    => 'submenu_typography',
	'selector'    => "$node_selector > ul > li.menu-item .sub-menu li.menu-item",
));

echo $module->get_mobile_css();