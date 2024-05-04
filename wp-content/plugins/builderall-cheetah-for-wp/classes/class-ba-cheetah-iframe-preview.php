<?php

/**
 * Handles rendering layouts in an iframe preview.
 *

 */
final class BACheetahIframePreview {

	/**
	 * Initialize on plugins loaded.
	 *

	 * @return void
	 */
	static public function init() {
		add_action( 'plugins_loaded', __CLASS__ . '::hook' );
	}

	/**
	 * Setup hooks.
	 *

	 * @return void
	 */
	static public function hook() {
		if ( ! BACheetahModel::is_builder_draft_preview() ) {
			return;
		}

		add_filter( 'show_admin_bar', '__return_false' );
		add_filter( 'ba_cheetah_node_status', __CLASS__ . '::filter_node_status' );
	}

	/**
	 * Forces draft node status for layout previews.
	 *

	 * @param string $status
	 * @return string
	 */
	static public function filter_node_status( $status ) {
		return 'draft';
	}
}

BACheetahIframePreview::init();
