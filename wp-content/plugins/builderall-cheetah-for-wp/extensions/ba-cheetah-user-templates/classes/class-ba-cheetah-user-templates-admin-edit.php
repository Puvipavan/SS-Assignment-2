<?php

/**
 * Logic for the user templates admin edit screen.
 *

 */
final class BACheetahUserTemplatesAdminEdit {

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init() {
		/* Actions */
		add_action( 'plugins_loaded', __CLASS__ . '::redirect' );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts' );
		add_action( 'edit_form_after_title', __CLASS__ . '::render_global_node_message' );
		add_action( 'add_meta_boxes', __CLASS__ . '::add_meta_boxes', 1 );

		/* Filters */
		add_filter( 'ba_cheetah_render_admin_edit_ui', __CLASS__ . '::remove_builder_edit_ui' );
	}

	/**
	 * Redirects the post-new.php page to our custom add new page.
	 *

	 * @return void
	 */
	static public function redirect() {
		global $pagenow;

		$post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : null;
		$args      = $_GET;

		if ( 'post-new.php' == $pagenow && 'ba-cheetah-template' == $post_type ) {

			$args['page'] = 'ba-cheetah-add-new';
			wp_redirect( admin_url( '/edit.php?' . http_build_query( $args ) ) );
			exit;
		}
	}

	/**
	 * Enqueue scripts and styles for user templates.
	 *

	 * @return void
	 */
	static public function admin_enqueue_scripts() {
		global $pagenow;
		global $post;

		$screen  = get_current_screen();
		$slug    = 'ba-cheetah-user-templates-admin-';
		$url     = BA_CHEETAH_USER_TEMPLATES_URL;
		$version = BA_CHEETAH_VERSION;

		if (
			( 'post.php' == $pagenow && 'ba-cheetah-template' == $screen->post_type )
			|| ( 'edit.php' == $pagenow && BACheetahUserTemplatesLayout::is_custom_layout_content_type($screen->post_type))
		) {
			wp_enqueue_style( $slug . 'edit', $url . 'css/' . $slug . 'edit.css', array(), $version );
			wp_enqueue_script( $slug . 'edit', $url . 'js/' . $slug . 'edit.js', array(), $version );

			if(BACheetahUserTemplatesLayout::is_custom_layout_content_type($screen->post_type)) {
				$type = $screen->post_type;
			} else {
				$type = BACheetahModel::get_user_template_type($post->ID);
			}

			wp_localize_script( $slug . 'edit', 'BACheetahConfig', array(
				'pageTitle'        => self::get_page_title(),
				'userTemplateType' => $type,
				'addNewURL'        => admin_url( '/edit.php?post_type=ba-cheetah-template&page=ba-cheetah-add-new' ),
			) );
		}
	}

	/**
	 * Returns the page title for the edit screen.
	 *

	 * @return string
	 */
	static public function get_page_title() {
		global $post;
		$screen  = get_current_screen();

		if (BACheetahUserTemplatesLayout::is_custom_layout_content_type($screen->post_type)) {
			$type = $screen->post_type;
		} else {
			$type = BACheetahModel::get_user_template_type($post->ID);
		}
		$action = __( 'Edit', 'ba-cheetah' );


		if ( 'row' == $type ) {
			/* translators: %s: add/edit or view */
			$label = sprintf( _x( '%s Saved Row', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $action );
		} elseif ( 'module' == $type ) {
			/* translators: %s: add/edit or view */
			$label = sprintf( _x( '%s Saved Element', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $action );
		} else {
			/* translators: %s: add/edit or view */
			$label = sprintf( _x( '%s Template', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $action );
		}

		if( 'ba-cheetah-header' === $type) {
			$label = sprintf(_x('%s Header', '%s is an action like Add, Edit or View.', 'ba-cheetah'), $action);
		}

		if ('ba-cheetah-footer' === $type) {
			$label = sprintf(_x('%s Footer', '%s is an action like Add, Edit or View.', 'ba-cheetah'), $action);
		}

		if ('ba-cheetah-popup' === $type) {
			$label = sprintf(_x('%s Popup', '%s is an action like Add, Edit or View.', 'ba-cheetah'), $action);
		}

		return $label;
	}

	/**
	 * Renders a notice div for global nodes.
	 *

	 * @return void
	 */
	static public function render_global_node_message() {
		global $pagenow;
		global $post;

		$screen = get_current_screen();

		if ( 'post.php' == $pagenow && 'ba-cheetah-template' == $screen->post_type ) {

			if ( BACheetahModel::is_post_global_node_template( $post->ID ) ) {

				$type = BACheetahModel::get_user_template_type( $post->ID );

				include BA_CHEETAH_USER_TEMPLATES_DIR . 'includes/admin-edit-global-message.php';
			}
		}
	}

	/**
	 * Callback for adding meta boxes to the user template
	 * post edit screen.
	 *

	 * @return void
	 */
	static public function add_meta_boxes() {

		$types = BACheetahUserTemplatesLayout::getLayoutPostTypes();
		$types[] = 'ba-cheetah-template';

		array_map(function ($type) {

			add_meta_box(
				'ba-cheetah-user-template-buttons',
				BACheetahModel::get_branding(),
				__CLASS__ . '::render_buttons_meta_box',
				$type,
				'normal',
				'high'
			);

		}, $types);
	}

	/**
	 * Adds custom buttons to the edit screen for launching the builder
	 * or viewing a template.
	 *

	 * @return void
	 */
	static public function render_buttons_meta_box() {
		global $post;

		$type = BACheetahModel::get_user_template_type( $post->ID );
		/* translators: %s: branded builder name */
		$edit = sprintf( _x( 'Launch %s', '%s stands for custom branded "Page Builder" name.', 'ba-cheetah' ), BACheetahModel::get_branding() );
		$view = __( 'View', 'ba-cheetah' );

		if ( 'ba-cheetah-template' == $post->post_type ) {

			if ( 'row' == $type ) {
				/* translators: %s: add/edit or view */
				$view = sprintf( _x( '%s Saved Row', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $view );
			} elseif ( 'module' == $type ) {
				/* translators: %s: add/edit or view */
				$view = sprintf( _x( '%s Saved Element', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $view );
			} else {
				/* translators: %s: add/edit or view */
				$view = sprintf( _x( '%s Template', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $view );
			}
		} else {
			$object = get_post_type_object( $post->post_type );
			/* translators: 1: add/edit or view: 2: post type label */
			$view = sprintf( _x( '%1$s %2$s', '%1$s is an action like Add, Edit or View. %2$s is post type label.', 'ba-cheetah' ), $view, $object->labels->singular_name );

		}

		include BA_CHEETAH_USER_TEMPLATES_DIR . 'includes/admin-edit-buttons.php';
	}

	/**
	 * Prevents the standard builder admin edit UI from rendering.
	 *

	 * @param bool $render_ui
	 * @return bool
	 */
	static public function remove_builder_edit_ui( $render_ui ) {

		$types = BACheetahUserTemplatesLayout::getLayoutPostTypes();
		$types[] = 'ba-cheetah-template';

		return in_array(BACheetahAdminPosts::get_post_type(), $types) ? false : $render_ui;
	}
}

BACheetahUserTemplatesAdminEdit::init();
