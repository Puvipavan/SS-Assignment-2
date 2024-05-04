<?php

/**
 * Logic for the user templates admin menu.
 *

 */
final class BACheetahUserTemplatesAdminMenu {

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init() {
		/* Actions */
		add_action( 'admin_menu', __CLASS__ . '::register' );

		/* Filters */
		add_filter( 'submenu_file', __CLASS__ . '::submenu_file', 999, 2 );
		add_filter('parent_file', __CLASS__ . '::parent_file', 999, 2);
		add_filter('views_edit-ba-cheetah-template', __CLASS__ . '::add_template_type_filter');
	}

	/**
	 * Registers the builder admin menu for user templates.
	 *

	 * @return void
	 */
	static public function register() {
		global $submenu, $_registered_pages;

		$parent       = 'ba-cheetah-settings';
		$cap          = 'edit_posts';
		$list_url     = 'edit.php?post_type=ba-cheetah-template&ba-cheetah-template-type=';
		$add_new_hook = 'admin_page_ba-cheetah-add-new';
		
		$submenu[$parent][100] = array(__('Saved Items', 'ba-cheetah'), $cap, $list_url . 'layout', 'Layouts');

		if (current_user_can($cap)) {
			add_action($add_new_hook, 'BACheetahUserTemplatesAdminAdd::render');
			$_registered_pages[$add_new_hook] = true;
		}

		$submenu[$parent] = apply_filters('ba_cheetah_user_templates_admin_menu', $submenu[$parent]);

	}

	/**
	 * Sets the active menu item for the builder admin submenu.
	 *

	 * @param string $submenu_file
	 * @param string $parent_file
	 * @return string
	 */
	static public function submenu_file( $submenu_file, $parent_file ) {

		global $pagenow;

		$screen   = get_current_screen();

		if (($pagenow === 'edit.php' || $pagenow === 'post.php')  && $screen->post_type === 'ba-cheetah-template') {
			$submenu_file = 'edit.php?post_type=ba-cheetah-template&ba-cheetah-template-type=layout';
		}

		return $submenu_file;
	}

	static public function parent_file($parent_file) {
		global $pagenow;
		$screen   = get_current_screen();

		if (($pagenow === 'edit.php' || $pagenow === 'post.php')  && $screen->post_type === 'ba-cheetah-template') {
			$parent_file = 'ba-cheetah-settings';
		}
		

		return $parent_file;
	}

	static public function add_template_type_filter($data) {
		
		$list_url = 'edit.php?post_type=ba-cheetah-template&ba-cheetah-template-type=';
		$cats_url     = 'edit-tags.php?taxonomy=ba-cheetah-template-category&post_type=ba-cheetah-template';

		global $pagenow;
		$screen   = get_current_screen();

		$page = sanitize_key( $_GET['ba-cheetah-template-type'] );

		$menu = array(
			'templates' => "<a href='" . $list_url . "layout' " . ($page === 'layout' ? "class='current'" : "") . ">" . __('Templates', 'ba-cheetah') . "</a>",
			'rows' => "<a href='" . $list_url . "row' " . ($page === 'row' ? "class='current'" : "") . ">" . __('Rows', 'ba-cheetah') . "</a>",
			'columns' => "<a href='" . $list_url . "column' " . ($page === 'column' ? "class='current'" : "") . ">" . __('Columns', 'ba-cheetah') . "</a>",
			'modules' => "<a href='" . $list_url . "module' " . ($page === 'module' ? "class='current'" : "") . ">" . __('Elements', 'ba-cheetah') . "</a>",
			'categories' => "<a href='" . $cats_url . "'>" . __('Categories', 'ba-cheetah') . "</a>",
		);

		return $menu;
	}

}

BACheetahUserTemplatesAdminMenu::init();
