<?php

/**
 * Handles undo/redo history for the builder.
 */
final class BACheetahHistoryManager {

	/**
	 * Initialize hooks.
	 */
	static public function init() {
		if ( ! defined( 'BA_CHEETAH_HISTORY_STATES' ) ) {
			define( 'BA_CHEETAH_HISTORY_STATES', 20 );
		}

		// Filters
		add_filter( 'ba_cheetah_ui_js_config', __CLASS__ . '::ui_js_config' );
		add_filter( 'ba_cheetah_main_menu', __CLASS__ . '::main_menu_config' );

		// Actions
		add_action( 'ba_cheetah_init_ui', __CLASS__ . '::init_states' );
	}

	/**
	 * Adds history data to the UI JS config.
	 */
	static public function ui_js_config( $config ) {
		$labels = array(
			// Layout
			'draft_created'           => __( 'Draft Created', 'ba-cheetah' ),
			'changes_discarded'       => __( 'Changes Discarded', 'ba-cheetah' ),
			'revision_restored'       => __( 'Revision Restored', 'ba-cheetah' ),

			// Save settings
			'row_edited'              => esc_attr__( 'Row Edited', 'ba-cheetah' ),
			'column_edited'           => esc_attr__( 'Column Edited', 'ba-cheetah' ),
			/* translators: %s: Module name */
			'module_edited'           => esc_attr_x( '%s Edited', 'Module name', 'ba-cheetah' ),
			'global_settings_edited'  => esc_attr__( 'Global Settings Edited', 'ba-cheetah' ),
			'layout_settings_edited'  => esc_attr__( 'Layout Settings Edited', 'ba-cheetah' ),

			// Add nodes
			'row_added'               => esc_attr__( 'Row Added', 'ba-cheetah' ),
			'columns_added'           => esc_attr__( 'Columns Added', 'ba-cheetah' ),
			'column_added'            => esc_attr__( 'Column Added', 'ba-cheetah' ),
			/* translators: %s: Module name */
			'module_added'            => esc_attr_x( '%s Added', 'Module name', 'ba-cheetah' ),

			// Delete nodes
			'row_deleted'             => esc_attr__( 'Row Deleted', 'ba-cheetah' ),
			'column_deleted'          => esc_attr__( 'Column Deleted', 'ba-cheetah' ),
			/* translators: %s: Module name */
			'module_deleted'          => esc_attr_x( '%s Deleted', 'Module name', 'ba-cheetah' ),

			// Duplicate nodes
			'row_duplicated'          => esc_attr__( 'Row Duplicated', 'ba-cheetah' ),
			'column_duplicated'       => esc_attr__( 'Column Duplicated', 'ba-cheetah' ),
			/* translators: %s: Module name */
			'module_duplicated'       => esc_attr_x( '%s Duplicated', 'Module name', 'ba-cheetah' ),

			// Move nodes
			'row_moved'               => esc_attr__( 'Row Moved', 'ba-cheetah' ),
			'column_moved'            => esc_attr__( 'Column Moved', 'ba-cheetah' ),
			/* translators: %s: Module name */
			'module_moved'            => esc_attr_x( '%s Moved', 'Module name', 'ba-cheetah' ),

			// Resize nodes
			'row_resized'             => esc_attr__( 'Row Resized', 'ba-cheetah' ),
			'columns_resized'         => esc_attr__( 'Columns Resized', 'ba-cheetah' ),
			'column_resized'          => esc_attr__( 'Column Resized', 'ba-cheetah' ),

			// Templates
			'template_applied'        => esc_attr__( 'Template Applied', 'ba-cheetah' ),
			'row_template_applied'    => esc_attr__( 'Row Template Added', 'ba-cheetah' ),
			'column_template_applied' => esc_attr__( 'Column Template Added', 'ba-cheetah' ),
			'history_disabled'        => __( 'Undo/Redo history is currently disabled.', 'ba-cheetah' ),
		);

		$hooks = array(
			// Layout
			'didDiscardChanges'             => 'changes_discarded',
			'didRestoreRevisionComplete'    => 'revision_restored',

			// Save settings
			'didSaveRowSettingsComplete'    => 'row_edited',
			'didSaveColumnSettingsComplete' => 'column_edited',
			'didSaveModuleSettingsComplete' => 'module_edited',
			'didSaveGlobalSettingsComplete' => 'global_settings_edited',
			'didSaveLayoutSettingsComplete' => 'layout_settings_edited',

			// Add nodes
			'didAddRow'                     => 'row_added',
			'didAddColumnGroup'             => 'columns_added',
			'didAddColumn'                  => 'column_added',
			'didAddModule'                  => 'module_added',

			// Delete nodes
			'didDeleteRow'                  => 'row_deleted',
			'didDeleteColumn'               => 'column_deleted',
			'didDeleteModule'               => 'module_deleted',

			// Duplicate nodes
			'didDuplicateRow'               => 'row_duplicated',
			'didDuplicateColumn'            => 'column_duplicated',
			'didDuplicateModule'            => 'module_duplicated',

			// Move nodes
			'didMoveRow'                    => 'row_moved',
			'didMoveColumn'                 => 'column_moved',
			'didMoveModule'                 => 'module_moved',

			// Resize nodes
			'didResizeRow'                  => 'row_resized',
			'didResetRowWidth'              => 'row_resized',
			'didResizeColumn'               => 'column_resized',
			'didResetColumnWidthsComplete'  => 'columns_resized',

			// Templates
			'didApplyTemplateComplete'      => 'template_applied',
			'didApplyRowTemplateComplete'   => 'row_template_applied',
			'didApplyColTemplateComplete'   => 'column_template_applied',
		);

		$config['history'] = array(
			'states'   => self::get_state_labels(),
			'position' => self::get_position(),
			'hooks'    => $hooks,
			'labels'   => $labels,
			'enabled'  => BA_CHEETAH_HISTORY_STATES && BA_CHEETAH_HISTORY_STATES > 0 ? true : false,
		);
		return $config;
	}

	/**
	 * Adds history data to the main menu config.
	 */
	static public function main_menu_config( $config ) {

		$config['main']['items'][36] = array(
			'label' => __( 'History', 'ba-cheetah' ),
			'icon'	=> 'ba-cheetah-icon--history',
			'type'  => 'view',
			'view'  => 'history',
		);

		$config['history'] = array(
			'name'       => __( 'History', 'ba-cheetah' ),
			'isShowing'  => false,
			'isRootView' => false,
			'items'      => array(),
		);

		return $config;
	}

	/**
	 * Adds an initial state if no states exist
	 * when the builder is active.
	 */
	static public function init_states() {
		if ( BA_CHEETAH_HISTORY_STATES && BA_CHEETAH_HISTORY_STATES > 0 ) {
			$states = self::get_states();

			if ( empty( $states ) ) {
				self::save_current_state( 'draft_created' );
			}
		} else {
			$post_id = BACheetahModel::get_post_id();
			self::delete_states( $post_id );
		}
	}

	/**
	 * Returns an array of saved layout states.
	 */
	static public function get_states() {
		global $wpdb;

		$post_id = BACheetahModel::get_post_id();
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->postmeta} WHERE meta_key LIKE %s AND post_id = %d ORDER BY meta_id", '%_ba_cheetah_history_state%', $post_id ) );
		$states  = array();

		foreach ( $results as $result ) {
			$value = maybe_unserialize( $result->meta_value );
			if ( is_array( $value ) ) {
				$states[] = $value;
			}
		}

		return $states;
	}

	/**
	 * Saves an array of layout states to post meta.
	 */
	static public function set_states( $states ) {
		$post_id = BACheetahModel::get_post_id();

		self::delete_states( $post_id );

		foreach ( $states as $i => $state ) {
			update_post_meta( $post_id, "_ba_cheetah_history_state_{$i}", $state );
		}
	}

	/**
	 * Deletes all history states for a post.
	 */
	static public function delete_states( $post_id ) {
		global $wpdb;

		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->postmeta} WHERE meta_key LIKE %s AND post_id = %d", '%_ba_cheetah_history_state%', $post_id ) );

		self::set_position( 0 );
	}

	/**
	 * Returns an array of saved layout states.
	 */
	static public function get_state_labels() {
		$states = self::get_states();
		$labels = array();
		foreach ( $states as $state ) {
			$labels[] = array(
				'label'      => $state['label'],
				'moduleType' => isset( $state['module_type'] ) ? $state['module_type'] : null,
			);
		}
		return $labels;
	}

	/**
	 * Returns the current history position.
	 */
	static public function get_position() {
		$post_id  = BACheetahModel::get_post_id();
		$position = get_post_meta( $post_id, '_ba_cheetah_history_position', true );
		return $position ? $position : 0;
	}

	/**
	 * Saves the current history position to post meta.
	 */
	static public function set_position( $position ) {
		$post_id = BACheetahModel::get_post_id();
		update_post_meta( $post_id, '_ba_cheetah_history_position', $position );
	}

	/**
	 * Appends the current layout state to the builder's
	 * history post meta. Pops off any trailing states if
	 * the last state isn't the current.
	 */
	static public function save_current_state( $label, $module_type = null ) {
		$position = self::get_position();
		$states   = array_slice( self::get_states(), 0, $position + 1 );
		$states[] = array(
			'label'       => $label,
			'module_type' => $module_type,
			'nodes'       => BACheetahModel::get_layout_data( 'draft' ),
			'settings'    => array(
				'global' => BACheetahModel::get_global_settings(),
				'layout' => BACheetahModel::get_layout_settings( 'draft' ),
			),
		);

		if ( count( $states ) > BA_CHEETAH_HISTORY_STATES ) {
			array_shift( $states );
		}

		self::set_states( $states );
		self::set_position( count( $states ) - 1 );

		return array(
			'states'   => self::get_state_labels(),
			'position' => self::get_position(),
		);
	}

	/**
	 * Renders the layout for the state at the given position.
	 */
	static public function render_state( $new_position = 0 ) {
		$states   = self::get_states();
		$position = self::get_position();

		if ( 'prev' === $new_position ) {
			$position = $position <= 0 ? 0 : $position - 1;
		} elseif ( 'next' === $new_position ) {
			$position = $position >= count( $states ) - 1 ? count( $states ) - 1 : $position + 1;
		} else {
			$position = $new_position < 0 || ! is_numeric( $new_position ) ? 0 : $new_position;
		}

		if ( ! isset( $states[ $position ] ) ) {
			return array(
				'error' => true,
			);
		}

		$state = $states[ $position ];
		self::set_position( $position );
		BACheetahModel::save_global_settings( (array) $state['settings']['global'] );
		BACheetahModel::update_layout_settings( (array) $state['settings']['layout'], 'draft' );
		BACheetahModel::update_layout_data( (array) $state['nodes'], 'draft' );

		return array(
			'position' => $position,
			'config'   => BACheetahUISettingsForms::get_node_js_config(),
			'layout'   => BACheetahAJAXLayout::render(),
			'settings' => array(
				'global' => BACheetahModel::get_global_settings(),
				'layout' => BACheetahModel::get_layout_settings( 'draft' ),
			),
		);
	}
}

BACheetahHistoryManager::init();
