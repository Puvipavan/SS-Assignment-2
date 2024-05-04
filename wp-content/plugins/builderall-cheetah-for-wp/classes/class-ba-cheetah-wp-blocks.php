<?php

/**
 * Cheetah Builder support for WordPress blocks.
 *

 */
final class BACheetahWPBlocks {

	/**

	 * @return void
	 */
	static public function init() {
		add_action( 'init', __CLASS__ . '::setup' );
	}

	/**

	 * @return void
	 */
	static public function setup() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Actions
		add_action( 'enqueue_block_editor_assets', __CLASS__ . '::enqueue_block_editor_assets' );

		// Filters
		add_filter( 'excerpt_allowed_blocks', __CLASS__ . '::excerpt_allowed_blocks' );

		// Block Files
		require_once BA_CHEETAH_DIR . 'classes/class-ba-cheetah-wp-blocks-layout.php';
	}

	/**
	 * Enqueues scripts and styles for the block editor.
	 *

	 * @return void
	 */
	static public function enqueue_block_editor_assets() {
		global $wp_version;
		global $post;

		if ( ! is_object( $post ) ) {
			return;
		} elseif ( ! in_array( $post->post_type, BACheetahModel::get_post_types() ) ) {
			return;
		}

		$branding         = BACheetahModel::get_branding();
		$post_type_object = get_post_type_object( $post->post_type );
		$post_type_name   = $post_type_object->labels->singular_name;
		$min              = ( ! BACheetah::is_debug() ) ? '.min' : '';

		wp_enqueue_style(
			'ba-cheetah-wp-editor',
			BA_CHEETAH_URL . 'css/build/wp-editor.bundle' . $min . '.css',
			array(),
			BA_CHEETAH_VERSION
		);

		wp_enqueue_script(
			'ba-cheetah-wp-editor',
			BA_CHEETAH_URL . 'js/build/wp-editor.bundle' . $min . '.js',
			array( 'wp-edit-post' ),
			BA_CHEETAH_VERSION
		);

		wp_localize_script( 'ba-cheetah-wp-editor', 'BACheetahConfig', array(
			'builder' => array(
				'access'       => BACheetahUserAccess::current_user_can( 'builder_access' ),
				'enabled'      => BACheetahModel::is_builder_enabled( $post->ID ),
				'nonce'        => wp_create_nonce( 'ba_cheetah_ajax_update' ),
				'unrestricted' => BACheetahUserAccess::current_user_can( 'unrestricted_editing' ),
				'showui'       => apply_filters( 'ba_cheetah_render_admin_edit_ui', true ),
			),
			'post'    => array(
				'id' => $post->ID,
			),
			'strings' => array(
				/* translators: 1: branded builder name: 2: post type name */
				'active'      => sprintf( _x( '%1$s is currently active for this %2$s.', '%1$s branded builder name. %2$s post type name.', 'ba-cheetah' ), $branding, strtolower( $post_type_name ) ),
				/* translators: %s: post type name */
				'convert'     => sprintf( _x( 'Convert to %s', '%s branded builder name.', 'ba-cheetah' ), $branding ),
				/* translators: %s: branded builder name */
				'description' => sprintf( _x( '%s lets you drag and drop your layout on the frontend.', '%s branded builder name.', 'ba-cheetah' ), $branding ),
				'editor'      => __( 'Use Standard Editor', 'ba-cheetah' ),
				/* translators: %s: branded builder name */
				'launch'      => sprintf( _x( 'Launch %s', '%s branded builder name.', 'ba-cheetah' ), $branding ),
				'title'       => $branding,
				/* translators: %s: post type name */
				'view'        => sprintf( _x( 'View %s', '%s post type name.', 'ba-cheetah' ), $post_type_name ),
				'warning'     => __( 'Switching to the native WordPress editor will disable your Builderall Builder layout until it is enabled again. Any edits made in the WordPress editor will not be converted to your Page Builder layout. Do you want to continue?', 'ba-cheetah' ),
			),
			'urls'    => array(
				'edit' => BACheetahModel::get_edit_url( $post->ID ),
				'view' => get_permalink( $post->ID ),
			),
			'wp'      => array(
				'version' => $wp_version,
			),
		) );
	}

	/**
	 * Adds our block(s) to the allowed blocks for excerpts.
	 *

	 * @param array $blocks
	 * @return array
	 */
	static public function excerpt_allowed_blocks( $blocks ) {
		$blocks[] = 'ba-cheetah/layout';
		return $blocks;
	}
}

BACheetahWPBlocks::init();
