<?php

/**
 * Logic for the user templates post type.
 *

 */
final class BACheetahUserTemplatesPostType {

	static public $content_types = array('ba-cheetah-template');

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init() {
		/* Actions */
		add_action( 'init', __CLASS__ . '::register' );
		add_action( 'init', __CLASS__ . '::register_taxonomies' );
		add_action( 'save_post', __CLASS__ . '::save_post' );
		add_filter( 'rest_ba-cheetah-template_collection_params', __CLASS__ . '::maximum_api_filter');
	}

	/**
	 * maximum_api_filter
	 * Increases the limit of items per page for the endpoint that returns the templates
	 * @param  mixed $query_params
	 * @return void
	 */
	public static function maximum_api_filter($query_params)
	{
		$query_params['per_page']["default"] = 10000;
		$query_params['per_page']["maximum"] = 10000;
		return $query_params;
	}

	/**
	 * Registers the custom post type for user templates.
	 *

	 * @return void
	 */
	static public function register() {

		$admin_access    = BACheetahUserAccess::current_user_can( 'builder_admin' );
		$can_edit        = BACheetahUserAccess::current_user_can( 'unrestricted_editing' );
		$can_edit_global = BACheetahUserAccess::current_user_can( 'global_node_editing' );


		$args = apply_filters('ba_cheetah_register_template_post_type_args', array(
			'labels'      => array(
				'name'          => _x('Saved templates', 'Custom post type label', 'ba-cheetah'),
				'singular_name' => _x('Saved template', 'Custom post type label', 'ba-cheetah'),
			),
			'public'      => ($admin_access && $can_edit) || BA_CHEETAH_TEMPLATE_API_ENABLED  ? true : false,
			'has_archive' => false,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => false,
			'supports'            => array(
				'title',
				'revisions',
				'page-attributes',
				'thumbnail'
			),
			'taxonomies'          => array(
				'ba-cheetah-template-category',
			),
			'publicly_queryable'  => $can_edit || $can_edit_global || BA_CHEETAH_TEMPLATE_API_ENABLED,
			'show_in_rest' => BA_CHEETAH_TEMPLATE_API_ENABLED,
			'show_in_nav_menus'	=> false,
		));

		register_post_type(
			'ba-cheetah-template',
			$args
		);
	}

	/**
	 * Registers the taxonomies for user templates.
	 *

	 * @return void
	 */
	static public function register_taxonomies() {
		/**
		 * Register the template category taxonomy.
		 * @see ba_cheetah_register_template_category_args
		 */
		$args = apply_filters( 'ba_cheetah_register_template_category_args', array(
			'labels'            => array(
				/* translators: %s: branded builder name */
				'name'              => sprintf( _x( '%s Categories', 'Custom taxonomy label.', 'ba-cheetah' ), BACheetahModel::get_branding() ),
				'singular_name'     => _x( 'Category', 'Custom taxonomy label.', 'ba-cheetah' ),
				'search_items'      => _x( 'Search Categories', 'Custom taxonomy label.', 'ba-cheetah' ),
				'all_items'         => _x( 'All Categories', 'Custom taxonomy label.', 'ba-cheetah' ),
				'parent_item'       => _x( 'Parent Category', 'Custom taxonomy label.', 'ba-cheetah' ),
				'parent_item_colon' => _x( 'Parent Category:', 'Custom taxonomy label.', 'ba-cheetah' ),
				'edit_item'         => _x( 'Edit Category', 'Custom taxonomy label.', 'ba-cheetah' ),
				'update_item'       => _x( 'Update Category', 'Custom taxonomy label.', 'ba-cheetah' ),
				'add_new_item'      => _x( 'Add New Category', 'Custom taxonomy label.', 'ba-cheetah' ),
				'new_item_name'     => _x( 'New Category Name', 'Custom taxonomy label.', 'ba-cheetah' ),
				'menu_name'         => _x( 'Categories', 'Custom taxonomy label.', 'ba-cheetah' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'		=> BA_CHEETAH_TEMPLATE_API_ENABLED
		) );

		register_taxonomy( 'ba-cheetah-template-category', array( 'ba-cheetah-template', 'ba-cheetah-theme-layout' ), $args );

		/**
		 * Register the template type taxonomy.
		 * @see ba_cheetah_register_template_type_args
		 */
		$args = apply_filters( 'ba_cheetah_register_template_type_args', array(
			'label'             => _x( 'Type', 'Custom taxonomy label.', 'ba-cheetah' ),
			'hierarchical'      => false,
			'public'            => false,
			'show_admin_column' => false,
		) );

		register_taxonomy( 'ba-cheetah-template-type', array( 'ba-cheetah-template' ), $args );
	}

	public static function save_post()
	{
		if ( ! isset( $_POST['post_type'] ) || 'ba-cheetah-template' != $_POST['post_type'] ) {
			return;
		}

		update_post_meta($_POST['post_ID'], 'ba-cheetah-version', BA_CHEETAH_VERSION);
	}
}

BACheetahUserTemplatesPostType::init();
