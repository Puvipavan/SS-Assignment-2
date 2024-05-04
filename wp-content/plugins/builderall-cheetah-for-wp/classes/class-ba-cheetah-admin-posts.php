<?php

/**
 * Handles logic for the post edit screen.
 *

 */
final class BACheetahAdminPosts {

	/**
	 * Initialize hooks.
	 *

	 * @return void
	 */
	static public function init() {
		/* Actions */
		add_action( 'current_screen', __CLASS__ . '::init_rendering' );

		/* Filters */
		add_filter( 'redirect_post_location', __CLASS__ . '::redirect_post_location' );
		add_filter( 'page_row_actions', __CLASS__ . '::render_row_actions_link' );
		add_filter( 'post_row_actions', __CLASS__ . '::render_row_actions_link' );
		add_action( 'pre_get_posts', __CLASS__ . '::sort_builder_enabled' );
	}

	/**

	 * @param WP_Post $post The post object.
	 */
	public static function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'ba_cheetah_css_js', 'ba_cheetah_css_js_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$data = get_post_meta( $post->ID, '_ba_cheetah_data_settings', true );

		if ( ! isset( $data->css ) ) {
			$css = '';
		} else {
			$css = $data->css;
		}
		if ( ! isset( $data->js ) ) {
			$js = '';
		} else {
			$js = $data->js;
		}
		?>
			<label for="ba_cheetah_css">
					<?php _e( 'CSS', 'ba-cheetah' ); ?>
			</label><br />
			<textarea style="width:100%" rows=10 id="ba_cheetah_css" name="ba_cheetah_css" value="<?php echo esc_attr( $css ); ?>"><?php echo esc_attr( $css ); ?></textarea><br />

			<label for="ba_cheetah_js">
					<?php _e( 'JS', 'ba-cheetah' ); ?>
			</label><br />
			<textarea style="width:100%" rows=10 id="ba_cheetah_js" name="ba_cheetah_js" value="<?php echo esc_attr( $js ); ?>"><?php echo esc_attr( $js ); ?></textarea>
			<?php
	}

	/**
	 * WordPress doesn't have a "right way" to get the current
	 * post type being edited and the new editor doesn't make
	 * this any easier. This method attempts to fix that.
	 *

	 * @return void
	 */
	static public function get_post_type() {
		global $post, $typenow, $current_screen;

		if ( is_object( $post ) && $post->post_type ) {
			return $post->post_type;
		} elseif ( $typenow ) {
			return $typenow;
		} elseif ( is_object( $current_screen ) && $current_screen->post_type ) {
			return $current_screen->post_type;
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			return sanitize_key( $_REQUEST['post_type'] );
		}

		return null;
	}

	/**
	 * Allow sorting by builder enabled in pages list.

	 */
	static public function sort_builder_enabled( $query ) {
		global $pagenow;
		if ( is_admin()
		&& 'edit.php' == $pagenow
		&& ! isset( $_GET['orderby'] )
		&& isset( $_GET['post_type'] )
		&& isset( $_GET['bbsort'] ) ) {
			$query->set( 'meta_key', '_ba_cheetah_enabled' );
			$query->set( 'meta_value', '1' );
		}
	}

	/**
	 * Sets the body class, loads assets and renders the UI
	 * if we are on a post type that supports the builder.
	 *

	 * @return void
	 */
	static public function init_rendering() {
		global $pagenow;

		if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ) ) ) {

			/**
			 * Enable/disable builder edit UI buttons
			 * @see ba_cheetah_render_admin_edit_ui
			 */
			$render_ui  = apply_filters( 'ba_cheetah_render_admin_edit_ui', true );
			$post_type  = self::get_post_type();
			$post_types = BACheetahModel::get_post_types();

			if ( $render_ui && in_array( $post_type, $post_types ) ) {
				add_filter( 'admin_body_class', __CLASS__ . '::body_class', 99 );
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
				add_action( 'edit_form_after_title', __CLASS__ . '::render' );
			}
		}
		/**
		 * Enable/disable sorting by BB enabled.
		 * @see ba_cheetah_admin_edit_sort_bb_enabled
		 */
		if ( 'edit.php' == $pagenow && true === apply_filters( 'ba_cheetah_admin_edit_sort_bb_enabled', true ) ) {
			$post_types = BACheetahModel::get_post_types();
			$post_type  = self::get_post_type();
			$block      = array(
				'ba-cheetah-template',
				'ba-cheetah-theme-layout',
			);

			/**
			 * Array of types to not show filtering on.
			 * @see ba_cheetah_admin_edit_sort_blocklist
			 */
			if ( ! in_array( $post_type, apply_filters( 'ba_cheetah_admin_edit_sort_blocklist', $block ) ) && in_array( $post_type, $post_types ) ) {
				wp_enqueue_script( 'ba-cheetah-admin-posts-list', BA_CHEETAH_URL . 'js/ba-cheetah-admin-posts-list.js', array( 'jquery' ), BA_CHEETAH_VERSION );
				$args    = array(
					'post_type'      => $post_type,
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'meta_query'     => array(
						array(
							'key'     => '_ba_cheetah_enabled',
							'compare' => '!=',
							'value'   => '',
						),
					),
				);
				$result  = new WP_Query( $args );
				$count   = is_array( $result->posts ) ? count( $result->posts ) : 0;
				$clicked = isset( $_GET['bbsort'] ) ? true : false;
				wp_localize_script( 'ba-cheetah-admin-posts-list',
					'ba_cheetah_enabled_count',
					array(
						'count'   => number_format_i18n( $count ),
						'brand'   => BACheetahModel::get_branding(),
						'clicked' => $clicked,
						'type'    => $post_type,
					)
				);
			}
		}
	}

	/**
	 * Enqueues the CSS/JS for the post edit screen.
	 *

	 * @return void
	 */
	static public function styles_scripts() {
		global $wp_version;

		// Styles
		wp_enqueue_style( 'ba-cheetah-admin-posts', BA_CHEETAH_URL . 'css/ba-cheetah-admin-posts.css', array(), BA_CHEETAH_VERSION );

		// Legacy WP Styles (3.7 and below)
		if ( version_compare( $wp_version, '3.7', '<=' ) ) {
			wp_enqueue_style( 'ba-cheetah-admin-posts-legacy', BA_CHEETAH_URL . 'css/ba-cheetah-admin-posts-legacy.css', array(), BA_CHEETAH_VERSION );
		}

		// Scripts
		wp_enqueue_script( 'json2' );
		wp_enqueue_script( 'ba-cheetah-admin-posts', BA_CHEETAH_URL . 'js/ba-cheetah-admin-posts.js', array(), BA_CHEETAH_VERSION );
	}

	/**
	 * Adds classes to the post edit screen body class.
	 *

	 * @param string $classes The existing body classes.
	 * @return string The body classes.
	 */
	static public function body_class( $classes = '' ) {
		global $wp_version;

		// Builder body class
		if ( BACheetahModel::is_builder_enabled() ) {
			$classes .= ' ba-cheetah-enabled';
		}

		// Pre WP 3.8 body class
		if ( version_compare( $wp_version, '3.8', '<' ) ) {
			$classes .= ' ba-cheetah-pre-wp-3-8';
		}

		return $classes;
	}

	/**
	 * Renders the HTML for the post edit screen.
	 *

	 * @return void
	 */
	static public function render() {
		global $post;

		$post_type_obj  = get_post_type_object( $post->post_type );
		$post_type_name = strtolower( $post_type_obj->labels->singular_name );
		$enabled        = BACheetahModel::is_builder_enabled();

		include BA_CHEETAH_DIR . 'includes/admin-posts.php';
	}

	/**
	 * Renders the action link for post listing pages.
	 *

	 * @param array $actions
	 * @return array The array of action data.
	 */
	static public function render_row_actions_link( $actions = array() ) {
		global $post;

		if ( 'trash' != $post->post_status && current_user_can( 'edit_post', $post->ID ) && wp_check_post_lock( $post->ID ) === false ) {

			/**
			 * Is post editable from admin post list
			 * @see ba_cheetah_is_post_editable
			 */
			$is_post_editable = (bool) apply_filters( 'ba_cheetah_is_post_editable', true, $post );
			$user_access      = BACheetahUserAccess::current_user_can( 'builder_access' );
			$post_types       = BACheetahModel::get_post_types();

			if ( in_array( $post->post_type, $post_types ) && $is_post_editable && $user_access ) {
				$enabled               = get_post_meta( $post->ID, '_ba_cheetah_enabled', true );
				$dot                   = ' <span style="color:' . ( $enabled ? '#6bc373' : '#d9d9d9' ) . '; font-size:18px;">&bull;</span>';
				$actions['ba-cheetah'] = '<a href="' . BACheetahModel::get_edit_url() . '">' . BACheetahModel::get_branding() . $dot . '</a>';
			}
		}

		return $actions;
	}

	/**
	 * Where to redirect this post on save.
	 *

	 * @param string $location
	 * @return string The location to redirect this post on save.
	 */
	static public function redirect_post_location( $location ) {
		if ( isset( $_POST['ba-cheetah-redirect'] ) ) {
			$location = BACheetahModel::get_edit_url( absint( $_POST['ba-cheetah-redirect'] ) );
		}

		return $location;
	}
}

BACheetahAdminPosts::init();
