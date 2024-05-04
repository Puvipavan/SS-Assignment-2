<?php

/**
 * Logic for the user templates admin add new form.
 *

 */
final class BACheetahUserTemplatesAdminAdd {

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init() {
		/* Actions */
		add_action( 'init', __CLASS__ . '::process_form', 11 );
		add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts' );
	}

	/**
	 * Enqueue scripts and styles for user templates.
	 *

	 * @return void
	 */
	static public function admin_enqueue_scripts() {
		$slug    = 'ba-cheetah-user-templates-admin-';
		$url     = BA_CHEETAH_USER_TEMPLATES_URL;
		$version = BA_CHEETAH_VERSION;
		$page    = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : null;
		$action  = __( 'Add', 'ba-cheetah' );

		if ( 'ba-cheetah-add-new' == $page ) {
			wp_enqueue_style( 'ba-cheetah-jquery-tiptip', BA_CHEETAH_URL . 'css/jquery.tiptip.css', array(), $version );
			wp_enqueue_script( 'ba-cheetah-jquery-tiptip', BA_CHEETAH_URL . 'js/jquery.tiptip.min.js', array( 'jquery' ), $version, true );
			wp_enqueue_script( 'jquery-validate', BA_CHEETAH_URL . 'js/jquery.validate.min.js', array(), $version, true );
			wp_enqueue_style( $slug . 'add', $url . 'css/' . $slug . 'add.css', array(), $version );
			wp_enqueue_script( $slug . 'add', $url . 'js/' . $slug . 'add.js', array(), $version, true );

			wp_localize_script( $slug . 'add', 'BACheetahConfig', apply_filters( 'ba_cheetah_user_templates_add_new_config', array(
				'strings' => array(
					'addButton' => array(
						'add'    => _x( 'Add', 'Generic add button label for adding new content.', 'ba-cheetah' ),
						/* translators: %s: add/edit or view */
						'layout' => sprintf( _x( '%s Saved Template', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $action ),
						/* translators: %s: add/edit or view */
						'row'    => sprintf( _x( '%s Saved Row', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $action ),
						/* translators: %s: add/edit or view */
						'module' => sprintf( _x( '%s Saved Element', '%s is an action like Add, Edit or View.', 'ba-cheetah' ), $action ),
					),
				),
			) ) );
		}
	}

	/**
	 * Renders the Add New page.
	 *

	 * @return void
	 */
	static public function render() {
		$modules       = BACheetahModel::get_categorized_modules();
		$selected_type = isset( $_GET['ba-cheetah-template-type'] ) ? sanitize_key( $_GET['ba-cheetah-template-type'] ) : '';

		$types = apply_filters( 'ba_cheetah_user_templates_add_new_types', array(
			10 => array(
				'key'   => 'ba-cheetah-header',
				'label' => __('Header', 'ba-cheetah'),
			),
			20 => array(
				'key'   => 'ba-cheetah-footer',
				'label' => __('Footer', 'ba-cheetah'),
			),
			30 => array(
				'key'   => 'ba-cheetah-popup',
				'label' => __('Popup', 'ba-cheetah'),
			),
			100 => array(
				'key'   => 'layout',
				'label' => __( 'Template', 'ba-cheetah' ),
			),
			200 => array(
				'key'   => 'row',
				'label' => __( 'Saved Row', 'ba-cheetah' ),
			),
			300 => array(
				'key'   => 'module',
				'label' => __( 'Saved Element', 'ba-cheetah' ),
			),
		) );

		include BA_CHEETAH_USER_TEMPLATES_DIR . 'includes/admin-add-new-form.php';
	}

	/**
	 * Adds a new template if the add new form was submitted.
	 *

	 * @return void
	 */
	static public function process_form() {
		$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : null;

		if ( 'ba-cheetah-add-new' != $page ) {
			return;
		}
		if ( ! isset( $_POST['ba-cheetah-add-template'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['ba-cheetah-add-template'], 'ba-cheetah-add-template-nonce' ) ) {
			return;
		}

		$title     = sanitize_text_field( $_POST['ba-cheetah-template']['title'] );
		$type      = sanitize_text_field( $_POST['ba-cheetah-template']['type'] );

		if(BACheetahUserTemplatesLayout::is_layout_content_type($type)) {
			$post_type = apply_filters( 'ba_cheetah_user_templates_add_new_post_type', $type, $type );
		} else {
			$post_type = apply_filters('ba_cheetah_user_templates_add_new_post_type', 'ba-cheetah-template', $type);
		}

		// Insert the post.
		$post_id = wp_insert_post( array(
			'post_title'     => $title,
			'post_type'      => $post_type,
			'post_status'    => 'draft',
			'ping_status'    => 'closed',
			'comment_status' => 'closed',
		) );

		// Enable the builder.
		update_post_meta( $post_id, '_ba_cheetah_enabled', true );

		/**
		 * Let extensions hook additional logic for custom types.
		 * @see ba_cheetah_user_templates_add_new_submit
		 */
		do_action( 'ba_cheetah_user_templates_add_new_submit', $type, $title, $post_id );

		// Setup a new layout, row or module template if we have one.
		self::setup_new_template( $type, $post_id );

		// Redirect to the new post.
		wp_redirect( admin_url( "/post.php?post={$post_id}&action=edit" ) );

		exit;
	}

	/**
	 * Sets the needed info for new templates.
	 *

	 * @private
	 * @return void
	 */
	static private function setup_new_template( $type, $post_id ) {
		// Make sure we have a template.
		if ( ! in_array( $type, array( 'layout', 'row', 'module' ) ) ) {
			return;
		}

		$template_id = BACheetahModel::generate_node_id();
		$global      = isset( $_POST['ba-cheetah-template']['global'] ) ? 1 : 0;
		$module      = sanitize_text_field( $_POST['ba-cheetah-template']['module'] );

		// Set the template type.
		wp_set_post_terms( $post_id, $type, 'ba-cheetah-template-type' );

		// Set row and module template meta.
		if ( in_array( $type, array( 'row', 'module' ) ) ) {
			update_post_meta( $post_id, '_ba_cheetah_template_id', $template_id );
			update_post_meta( $post_id, '_ba_cheetah_template_global', $global );
		}

		// Force the builder to use this post ID.
		BACheetahModel::set_post_id( $post_id );

		// Add a new row or module?
		if ( 'row' == $type ) {
			$saved_node = BACheetahModel::add_row();
		} elseif ( 'module' == $type ) {
			$settings   = BACheetahModel::get_module_defaults( $module );
			$saved_node = BACheetahModel::add_module( $module, $settings );
		}

		// Make the new template global?
		if ( $global && isset( $saved_node ) ) {

			$data = BACheetahModel::get_layout_data();

			foreach ( $data as $node_id => $node ) {

				if ( $node_id == $saved_node->node ) {
					$data[ $node_id ]->template_root_node = true;
				}

				$data[ $node_id ]->template_id      = $template_id;
				$data[ $node_id ]->template_node_id = $node_id;
			}

			BACheetahModel::update_layout_data( $data );
		}

		// make sure the layout saved as draft (and allow edit)
		if(empty(BACheetahModel::get_layout_data('draft'))) {
			BACheetahModel::update_layout_data(BACheetahModel::get_layout_data('published'), 'draft');
		}

		// Reset the builder's post ID.
		BACheetahModel::reset_post_id();
	}
}

BACheetahUserTemplatesAdminAdd::init();
