<?php

/**
 * Front-end AJAX handler for the builder interface. We use this
 * instead of wp_ajax because that only works in the admin and
 * certain things like some shortcodes won't render there. AJAX
 * requests handled through this method only run for logged in users
 * for extra security. Developers creating custom modules that need
 * AJAX should use wp_ajax instead.
 *

 */
final class BACheetahAJAX {

	/**
	 * An array of registered action data.
	 *

	 * @access private
	 * @var array $actions
	 */
	static private $actions = array();

	/**
	 * Initializes hooks.
	 *

	 * @return void
	 */
	static public function init() {
		add_action( 'wp', __CLASS__ . '::run' );
	}

	/**
	 * Runs builder's frontend AJAX.
	 *

	 * @return void
	 */
	static public function run() {
		self::add_actions();
		self::call_action();
	}

	/**
	 * Adds a callable AJAX action.
	 *

	 * @param string $action The action name.
	 * @param string $method The method to call.
	 * @param array $args An array of method arg names that are present in the post data.
	 * @return void
	 */
	static public function add_action( $action, $method, $args = array() ) {
		self::$actions[ $action ] = array(
			'action' => $action,
			'method' => $method,
			'args'   => $args,
		);
	}

	/**
	 * Removes an AJAX action.
	 *

	 * @param string $action The action to remove.
	 * @return void
	 */
	static public function remove_action( $action ) {
		if ( isset( self::$actions[ $action ] ) ) {
			unset( self::$actions[ $action ] );
		}
	}

	/**
	 * Adds all callable AJAX actions.
	 *

	 * @access private
	 * @return void
	 */
	static private function add_actions() {

		// BACheetahModel
		self::add_action( 'get_node_settings', 'BACheetahModel::get_node_settings', array( 'node_id' ) );
		self::add_action( 'delete_node', 'BACheetahModel::delete_node', array( 'node_id' ) );
		self::add_action( 'delete_col', 'BACheetahModel::delete_col', array( 'node_id', 'new_width' ) );
		self::add_action( 'reorder_node', 'BACheetahModel::reorder_node', array( 'node_id', 'position' ) );
		self::add_action( 'reorder_col', 'BACheetahModel::reorder_col', array( 'node_id', 'position' ) );
		self::add_action( 'move_node', 'BACheetahModel::move_node', array( 'node_id', 'new_parent', 'position' ) );
		self::add_action( 'move_col', 'BACheetahModel::move_col', array( 'node_id', 'new_parent', 'position', 'resize' ) );
		self::add_action( 'resize_cols', 'BACheetahModel::resize_cols', array( 'col_id', 'col_width', 'sibling_id', 'sibling_width' ) );
		self::add_action( 'reset_col_widths', 'BACheetahModel::reset_col_widths', array( 'group_id' ) );
		self::add_action( 'resize_row_content', 'BACheetahModel::resize_row_content', array( 'node', 'width' ) );
		self::add_action( 'save_settings', 'BACheetahModel::save_settings', array( 'node_id', 'settings' ) );
		self::add_action( 'verify_settings', 'BACheetahModel::verify_settings', array( 'settings' ) );
		self::add_action( 'save_layout_settings', 'BACheetahModel::save_layout_settings', array( 'settings' ) );
		self::add_action( 'save_global_settings', 'BACheetahModel::save_global_settings', array( 'settings' ) );
		self::add_action( 'save_page_settings', 'BACheetahModel::save_page_settings', array('settings'));
		
		self::add_action( 'save_color_presets', 'BACheetahModel::save_color_presets', array( 'presets' ) );
		self::add_action( 'duplicate_post', 'BACheetahModel::duplicate_post' );
		self::add_action( 'duplicate_wpml_layout', 'BACheetahModel::duplicate_wpml_layout', array( 'original_post_id', 'post_id' ) );
		self::add_action( 'apply_user_template', 'BACheetahModel::apply_user_template', array( 'template_id', 'append' ) );
		self::add_action( 'apply_template', 'BACheetahModel::apply_template', array( 'template_id', 'append', 'content_central_id' ) );
		self::add_action( 'save_layout', 'BACheetahModel::save_layout', array( 'publish', 'exit' ) );
		self::add_action( 'save_draft', 'BACheetahModel::save_draft' );
		self::add_action( 'clear_draft_layout', 'BACheetahModel::clear_draft_layout' );
		self::add_action( 'disable_builder', 'BACheetahModel::disable' );
		self::add_action( 'clear_cache', 'BACheetahModel::delete_all_asset_cache' );
		self::add_action( 'set_cheetah_mode', 'BACheetahModel::set_cheetah_mode' );

		// BACheetahAJAXLayout
		self::add_action( 'render_layout', 'BACheetahAJAXLayout::render' );
		self::add_action( 'render_node', 'BACheetahAJAXLayout::render', array( 'node_id' ) );
		self::add_action( 'render_new_row', 'BACheetahAJAXLayout::render_new_row', array( 'cols', 'position', 'module' ) );
		self::add_action( 'render_new_row_template', 'BACheetahAJAXLayout::render_new_row_template', array( 'position', 'template_id', 'template_type', 'content_central_id' ) );
		self::add_action( 'copy_row', 'BACheetahAJAXLayout::copy_row', array( 'node_id', 'settings', 'settings_id' ) );
		self::add_action( 'render_new_column_group', 'BACheetahAJAXLayout::render_new_column_group', array( 'node_id', 'cols', 'position', 'module' ) );
		self::add_action( 'render_new_columns', 'BACheetahAJAXLayout::render_new_columns', array( 'node_id', 'insert', 'type', 'nested', 'module' ) );
		self::add_action( 'render_new_col_template', 'BACheetahAJAXLayout::render_new_col_template', array( 'template_id', 'parent_id', 'position', 'template_type' ) );
		self::add_action( 'copy_col', 'BACheetahAJAXLayout::copy_col', array( 'node_id', 'settings', 'settings_id' ) );
		self::add_action( 'render_new_module', 'BACheetahAJAXLayout::render_new_module', array( 'parent_id', 'position', 'type', 'alias', 'template_id', 'template_type' ) );
		self::add_action( 'copy_module', 'BACheetahAJAXLayout::copy_module', array( 'node_id', 'settings' ) );

		// BACheetahUISettingsForms
		self::add_action( 'render_legacy_settings', 'BACheetahUISettingsForms::render_legacy_settings', array( 'data', 'form', 'group', 'lightbox' ) );
		self::add_action( 'render_settings_form', 'BACheetahUISettingsForms::render_settings_form', array( 'type', 'settings' ) );
		self::add_action( 'render_icon_selector', 'BACheetahUISettingsForms::render_icon_selector' );

		// BACheetahRevisions
		self::add_action( 'render_revision_preview', 'BACheetahRevisions::render_preview', array( 'revision_id' ) );
		self::add_action( 'restore_revision', 'BACheetahRevisions::restore', array( 'revision_id' ) );
		self::add_action( 'refresh_revision_items', 'BACheetahRevisions::get_config', array( 'post_id' ) );

		// BACheetahHistoryManager
		self::add_action( 'save_history_state', 'BACheetahHistoryManager::save_current_state', array( 'label', 'module_type' ) );
		self::add_action( 'render_history_state', 'BACheetahHistoryManager::render_state', array( 'position' ) );
		self::add_action( 'clear_history_states', 'BACheetahHistoryManager::delete_states', array( 'post_id' ) );

		// BACheetahAutoSuggest
		self::add_action( 'ba_cheetah_autosuggest', 'BACheetahAutoSuggest::init' );
		self::add_action( 'get_autosuggest_values', 'BACheetahAutoSuggest::get_values', array( 'fields' ) );

		// Modules data
		self::add_action('get_popups', 'BACheetahPopups::get_popups_to_select');
		self::add_action('get_menus', 'BACheetahMenuModule::get_wp_menus');
		self::add_action('get_builderall_mailingboss_lists', 'BACheetahMailingbossModule::get_lists');
		self::add_action('get_builderall_supercheckout_products', 'BACheetahSupercheckoutModule::get_products');
		self::add_action('get_builderall_videos_hosting', 'BACheetahVideoHostingModule::get_videos');
		self::add_action('get_builderall_webinars', 'BACheetahWebinarModule::get_webinars');
		self::add_action('get_builderall_bookings', 'BACheetahBookingModule::get_calendars');
		self::add_action('get_headers_for_select', 'BACheetahAJAXLayout::get_headers_for_select' );
		self::add_action('get_footers_for_select', 'BACheetahAJAXLayout::get_footers_for_select' );
		
	}

	/**
	 * Runs the current AJAX action.
	 *

	 * @access private
	 * @return void
	 */
	static private function call_action() {
		// Only run for logged in users.
		if ( ! is_user_logged_in() ) {
			return;
		}

		// Verify the AJAX nonce.
		if ( ! self::verify_nonce() ) {
			return;
		}

		// Get the $_POST data.
		$ba_cheetah_data = BACheetahModel::get_cheetah_ba_data();
		$post_data = array_merge($ba_cheetah_data, $_POST);

		// Get the post ID.
		$post_id = BACheetahModel::get_post_id();

		// Make sure we have a post ID.
		if ( ! $post_id ) {
			return;
		}

		// Make sure the user can edit this post.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Get the action.
		if ( ! empty( $_REQUEST['ba_cheetah_action'] ) ) {
			$action = $_REQUEST['ba_cheetah_action'];
		} elseif ( ! empty( $post_data['ba_cheetah_action'] ) ) {
			$action = sanitize_text_field($post_data['ba_cheetah_action']);
		} else {
			return;
		}

		/**
		 * Allow developers to modify actions before they are called.
		 * @see ba_cheetah_ajax_before_call_action
		 */
		do_action( 'ba_cheetah_ajax_before_call_action', $action );

		// Make sure the action exists.
		if ( ! isset( self::$actions[ $action ] ) ) {
			return;
		}

		// Get the action data.
		$action    = self::$actions[ $action ];
		$args      = array();
		$keys_args = array();

		// Build the args array.
		foreach ( $action['args'] as $arg ) {
			// @codingStandardsIgnoreLine
			$args[] = $keys_args[ $arg ] = isset( $post_data[ $arg ] ) ? $post_data[ $arg ] : null;
		}

		// Tell WordPress this is an AJAX request.
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		/**
		 * Allow developers to hook before the action runs.
		 * @see ba_cheetah_ajax_before_
		 */
		do_action( 'ba_cheetah_ajax_before_' . $action['action'], $keys_args );

		/**
		 * Call the action and allow developers to filter the result.
		 * @see ba_cheetah_ajax_
		 */
		$result = apply_filters( 'ba_cheetah_ajax_' . $action['action'], call_user_func_array( $action['method'], $args ), $keys_args );

		/**
		 * Allow developers to hook after the action runs.
		 * @see ba_cheetah_ajax_after_
		 */
		do_action( 'ba_cheetah_ajax_after_' . $action['action'], $keys_args );

		/**
		 * Set header for JSON if headers have not been sent.
		 */
		if ( ! headers_sent() ) {
			header( 'Content-Type:text/plain' );
		}

		// JSON encode the result.
		echo BACheetahUtils::json_encode( $result );

		// Complete the request.
		die();
	}

	/**
	 * Checks to make sure the AJAX nonce is valid.
	 *

	 * @access private
	 * @return bool
	 */
	static private function verify_nonce() {
		$post_data = BACheetahModel::get_cheetah_ba_data();
		$nonce     = false;

		if ( isset( $post_data['_wpnonce'] ) ) {
			$nonce = sanitize_text_field($post_data['_wpnonce']);
		} elseif ( isset( $_REQUEST['_wpnonce'] ) ) {
			$nonce = sanitize_text_field($_REQUEST['_wpnonce']);
		}

		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'ba_cheetah_ajax_update' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Is this an AJAX response?

	 * @return bool
	 */
	static public function doing_ajax() {
		if ( function_exists( 'wp_doing_ajax' ) ) {
			return wp_doing_ajax();
		}
		if ( defined( 'DOING_AJAX' ) ) {
			return DOING_AJAX;
		}
		return false;
	}
}

BACheetahAJAX::init();
