<?php

/**
 * Logic for the user templates admin list table.
 *

 */
final class BACheetahUserTemplatesAdminList {

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init() {
		/* Actions */
		add_action( 'plugins_loaded', __CLASS__ . '::redirect' );
		add_action( 'wp', __CLASS__ . '::page_heading' );
		add_action( 'pre_get_posts', __CLASS__ . '::pre_get_posts' );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts' );
		add_action( 'manage_ba-cheetah-template_posts_custom_column', __CLASS__ . '::add_column_content', 10, 2 );

		/* Filters */
		add_filter( 'views_edit-ba-cheetah-template', __CLASS__ . '::modify_views' );
		add_filter( 'manage_ba-cheetah-template_posts_columns', __CLASS__ . '::add_column_headings' );
		
		// The line below removes the quick edit. We need this to allow editing the slug of templates in the content center 
		// add_filter( 'post_row_actions', __CLASS__ . '::row_actions' );
		
		add_action( 'restrict_manage_posts', __CLASS__ . '::restrict_listings' );
	}

	/**
	 * Enqueue scripts and styles for user templates.
	 *

	 * @return void
	 */
	static public function admin_enqueue_scripts() {
		global $pagenow;

		$screen  = get_current_screen();
		$slug    = 'ba-cheetah-user-templates-admin-';
		$url     = BA_CHEETAH_USER_TEMPLATES_URL;
		$version = BA_CHEETAH_VERSION;

		if ( 'edit.php' == $pagenow && 'ba-cheetah-template' == $screen->post_type ) {

			wp_enqueue_style( $slug . 'list', $url . 'css/' . $slug . 'list.css', array(), $version );
			wp_enqueue_script( $slug . 'list', $url . 'js/' . $slug . 'list.js', array(), $version );

			wp_localize_script( $slug . 'list', 'BACheetahConfig', array(
				'userTemplateType' => isset( $_GET['ba-cheetah-template-type'] ) ? sanitize_key( $_GET['ba-cheetah-template-type'] ) : 'layout',
				'addNewURL'        => admin_url( '/edit.php?post_type=ba-cheetah-template&page=ba-cheetah-add-new' ),
			) );
		}
	}

	/**
	 * Redirects the list table to show layout templates if no
	 * template type is set. We never want to show all templates
	 * (layouts, rows, modules) in a list table together.
	 *

	 * @return void
	 */
	static public function redirect() {
		global $pagenow;

		$post_type     = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : null;
		$template_type = isset( $_GET['ba-cheetah-template-type'] ) ? sanitize_key( $_GET['ba-cheetah-template-type'] ) : null;
		$page          = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : null;

		if ( 'edit.php' == $pagenow && 'ba-cheetah-template' == $post_type && ! $template_type && ! $page ) {

			$url = admin_url( '/edit.php?post_type=ba-cheetah-template&ba-cheetah-template-type=layout' );

			wp_redirect( $url );

			exit;
		}
	}

	/**
	 * Overrides the list table page headings for saved rows, cols and modules.
	 *

	 * @return void
	 */
	static public function page_heading() {
		global $pagenow;
		global $wp_post_types;

		if ( ! is_admin() ) {
			return;
		}

		$screen = get_current_screen();

		if ( 'edit.php' == $pagenow && isset( $_GET['ba-cheetah-template-type'] ) ) {

			if ( 'row' == $_GET['ba-cheetah-template-type'] ) {
				$wp_post_types['ba-cheetah-template']->labels->name = __( 'Saved Rows', 'ba-cheetah' );
			} elseif ( 'column' == $_GET['ba-cheetah-template-type'] ) {
				$wp_post_types['ba-cheetah-template']->labels->name = __( 'Saved Columns', 'ba-cheetah' );
			} elseif ( 'module' == $_GET['ba-cheetah-template-type'] ) {
				$wp_post_types['ba-cheetah-template']->labels->name = __( 'Saved Elements', 'ba-cheetah' );
			}
		}
	}

	/**
	 * Orders templates by title.
	 *

	 * @param object $query
	 * @return void
	 */
	static public function pre_get_posts( $query ) {
		if ( ! isset( $_GET['post_type'] ) || 'ba-cheetah-template' != $_GET['post_type'] ) {
			return;
		} elseif ( $query->is_main_query() && ! $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'ASC' );
		}
	}

	/**
	 * Modifies the views links to remove the counts since they
	 * are not correct for our list table approach.
	 *

	 * @param array $views
	 * @return array
	 */
	static public function modify_views( $views ) {
		$slug = 'ba-cheetah-template';
		$type = isset( $_GET['ba-cheetah-template-type'] ) ? sanitize_key( $_GET['ba-cheetah-template-type'] ) : 'layout';

		foreach ( $views as $key => $view ) {

			if ( strstr( $view, $slug ) ) {
				$view          = str_replace( $slug, $slug . '&#038;ba-cheetah-template-type=' . $type, $view );
				$view          = preg_replace( '/<span(.*)span>/', '', $view );
				$views[ $key ] = $view;
			}
		}

		return $views;
	}

	/**
	 * Adds the custom list table column headings.
	 *

	 * @param array $columns
	 * @return array
	 */
	static public function add_column_headings( $columns ) {
		if ( ! isset( $_GET['ba-cheetah-template-type'] ) ) {
			return;
		}
		if ( in_array( $_GET['ba-cheetah-template-type'], array( 'row', 'column', 'module' ) ) ) {
			$columns['fl_global'] = __( 'Global', 'ba-cheetah' );
		}

		$columns['taxonomy-ba-cheetah-template-category'] = __( 'Categories', 'ba-cheetah' );

		unset( $columns['date'] );

		return $columns;
	}

	/**
	 * Adds the custom list table column content.
	 *

	 * @param array $columns
	 * @return array
	 */
	static public function add_column_content( $column, $post_id ) {
		if ( 'fl_global' != $column ) {
			return;
		}

		if ( BACheetahModel::is_post_global_node_template( $post_id ) ) {
			echo  __('Yes', 'ba-cheetah');
		} else {
			echo '&#8212;';
		}
	}

	/**
	 * Removes the quick edit link as we don't need it.
	 *

	 * @param array $actions
	 * @return array
	 */
	static public function row_actions( $actions = array() ) {
		if ( isset( $_GET['post_type'] ) && 'ba-cheetah-template' == $_GET['post_type'] ) {
			unset( $actions['inline hide-if-no-js'] );
		}

		return $actions;
	}

	/**
	 * Add filter dropdown for Categories
	 *

	 */
	static public function restrict_listings() {
		global $typenow;
		if ( 'ba-cheetah-template' == $typenow ) {
			$taxonomy = 'ba-cheetah-template-category';
			$tax      = get_taxonomy( $taxonomy );
			$term     = sanitize_key( $_GET['ba-cheetah-template-type'] );
			wp_dropdown_categories(
				array(
					'show_option_all' => __( 'Show All Categories', 'ba-cheetah' ),
					'taxonomy'        => $taxonomy,
					'value_field'     => 'slug',
					'orderby'         => 'name',
					'selected'        => $term,
					'name'            => $taxonomy,
					'depth'           => 1,
					'show_count'      => false,
					'hide_empty'      => false,
				)
			);
		}
	}

}

BACheetahUserTemplatesAdminList::init();
