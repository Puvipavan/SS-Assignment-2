<?php if ($settings->menu) { ?>
<nav class="ba-module__menu ba-node-<?php echo $id?>">

	<!-- mobile menu -->
	<div class="menu__mobile">
		<div class="menu__mobile-logo">
			<?php if (!empty($settings->logo)) { ?>
				<img src="<?php echo esc_url($settings->logo_src) ?>">
			<?php }?>
		</div>

		<button class="menu__mobile-hamburger">
			<i class="<?php echo esc_attr($settings->hamburger_icon) ?>"></i>
		</button>
	</div>
	
	<!-- menu items -->
	<?php
	remove_filter('walker_nav_menu_start_el', 'twenty_twenty_one_add_sub_menu_toggle');
	wp_nav_menu(
		array(
			'menu' => $settings->menu,
			'container_class' => 'menu__container',
			'menu_class' => ''
		)
	);
	?>
</nav>


<?php
} else {
	echo 'Please select a menu';
}