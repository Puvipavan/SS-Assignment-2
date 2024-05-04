<?php

/**
 * Handles logic for the builder's content panel UI.
 *

 */
class BACheetahUIContentPanel {

	/**
	 * Get data structure required by the content panel.
	 *

	 * @access public
	 * @return array
	 */
	public static function get_panel_data() {

		// Don't load the panel for module templates.
		if ( BACheetahModel::is_post_user_template( 'module' ) ) {
			return array();
		}

		$data = array(
			'tabs' => array(),
		);

		$modules_data = self::get_modules_tab_data();
		if ( $modules_data['should_display'] ) {
			$modules_tab             = array(
				'handle'          => 'modules',
				'name'            => __( 'Elements', 'ba-cheetah' ),
				'views'           => $modules_data['views'],
				'isSearchEnabled' => true,
			);
			$data['tabs']['modules'] = $modules_tab;
		}

		$rows_data = self::get_rows_tab_data();
		if ( $rows_data['should_display'] ) {
			$rows_tab             = array(
				'handle' => 'rows',
				'name'   => __( 'Rows', 'ba-cheetah' ),
				'views'  => $rows_data['views'],
			);
			$data['tabs']['rows'] = $rows_tab;
		}

		$templates_data = self::get_templates_tab_data();
		if ( $templates_data['should_display'] ) {
			$templates_tab             = array(
				'handle' => 'templates',
				'name'   => __( 'Templates', 'ba-cheetah' ),
				'views'  => $templates_data['views'],
			);
			$data['tabs']['templates'] = $templates_tab;
		}

		// $saved_data = self::get_user_saved_tab_data();
		// if ( $saved_data['should_display'] ) {
		// 	$templates_tab             = array(
		// 		'handle' => 'saved',
		// 		'name'   => __( 'Saved', 'ba-cheetah' ),
		// 		'views'  => $saved_data['views'],
		// 	);
		// 	$data['tabs']['saved'] = $templates_tab;
		// }

		/**
		* Filter the tabs/views structure
		*

		* @param Array $data the initial tab data
		* @see ba_cheetah_content_panel_data
		*/
		return apply_filters( 'ba_cheetah_content_panel_data', $data );
	}

	/**
	 * Get user rows, cols and modules saved
	 *

	 * @access private
	 * @return array
	 */

	// private static function get_user_saved_tab_data() {
	// 	return array(
	// 		'should_display' => true,
	// 		'handle' => 'saved',
	// 		'name' => __( 'Saved', 'ba-cheetah' ),
	// 		'views' => array()
	// 	);
	// }

	/**
	 * Get module views for panel.
	 *

	 * @access private
	 * @return array
	 */
	private static function get_modules_tab_data() {

		$data = array(
			'should_display' => ! BACheetahModel::is_post_user_template( 'module' ),
			'views'          => array(),
		);

		// Standard Modules View
		$data['views'][] = array(
			'handle'              => 'standard',
			'name'                => __( 'Standard Elements', 'ba-cheetah' ),
			'query'               => array(
				'kind'        => 'module',
				'categorized' => true,
				// comment to show all components groups in main section
				//'group'       => 'standard',
			),
			'orderedSectionNames' => array_keys( BACheetahModel::get_module_categories() ),
		);

		// Third Party Module Groups
		$groups = BACheetahModel::get_module_groups();
		if ( ! empty( $groups ) ) {

			$data['views'][] = array(
				'type' => 'separator',
			);

			foreach ( $groups as $slug => $name ) {
				$data['views'][] = array(
					'handle'       => $slug,
					'name'         => $name,
					'query'        => array(
						'kind'        => array( 'module', 'template' ),
						'content'     => 'module',
						'type'        => 'core',
						'categorized' => true,
						'group'       => $slug,
					),
					'templateName' => 'ba-cheetah-content-panel-modules-view',
				);
			}
		}

		return $data;
	}

	/**
	 * Get data for the rows tab.
	 *

	 * @access private
	 * @return array
	 */
	private static function get_rows_tab_data() {

		$data = array(
			'should_display' => true, /* rows tab shows even if row template */
			'views'          => array(),
		);

		// Columns View
		$data['views'][] = array(
			'handle'       => 'columns',
			'name'         => __( 'Columns', 'ba-cheetah' ),
			'query'        => array(
				'kind' => 'colGroup',
			),
			'templateName' => 'ba-cheetah-content-panel-col-groups-view',
		);

		// Row Templates View
		$templates          = BACheetahModel::get_row_templates_data();
		$is_row_template    = BACheetahModel::is_post_user_template( 'row' );
		$is_column_template = BACheetahModel::is_post_user_template( 'column' );

		if ( ! $is_row_template && ! $is_column_template && isset( $templates['groups'] ) && ! empty( $templates['groups'] ) ) {

			$data['views'][] = array(
				'type' => 'separator',
			);

			ksort($templates['groups']);

			foreach ( $templates['groups'] as $slug => $group ) {

				$data['views'][] = array(
					'handle'      => $slug,
					'name'        => $group['name'],
					'hasChildren' => count( $group['categories'] ) > 1,
					'query'       => array(
						'kind'        => 'template',
						'type'        => 'core',
						'group'       => $slug,
						'content'     => 'row',
						'categorized' => true,
					),
				);

				if ( count( $group['categories'] ) < 2 ) {
					continue;
				}

				foreach ( $group['categories'] as $cat_slug => $category ) {
					$data['views'][] = array(
						'handle'    => $cat_slug,
						'name'      => $category['name'],
						'isSubItem' => true,
						'parent'    => $slug,
						'query'     => array(
							'kind'        => 'template',
							'type'        => 'core',
							'content'     => 'row',
							'group'       => $slug,
							'category'    => $cat_slug,
							'categorized' => true,
						),
					);
				}
			}
		}

		return $data;
	}

	/**
	 * Get data for the templates tab.
	 *

	 * @access private
	 * @return array
	 */
	private static function get_templates_tab_data() {
		$enabled            = BACheetahModel::get_enabled_templates();
		$is_module_template = BACheetahModel::is_post_user_template( 'module' );
		$is_column_template = BACheetahModel::is_post_user_template( 'column' );
		$is_row_template    = BACheetahModel::is_post_user_template( 'row' );
		$data               = array(
			'should_display' => ( ! $is_module_template && ! $is_column_template && ! $is_row_template && 'disabled' !== $enabled ),
			'views'          => array(),
		);

		$templates = BACheetahModel::get_template_selector_data();

		if ( ! isset( $templates['groups'] ) || empty( $templates['groups'] ) ) {

			return $data;
		}

		foreach ( $templates['groups'] as $slug => $group ) {

			$data['views'][] = array(
				'handle'      => $slug,
				'name'        => $group['name'],
				'hasChildren' => count( $group['categories'] ) > 1,
				'query'       => array(
					'kind'        => 'template',
					'type'        => 'core',
					'content'     => 'layout',
					'group'       => $slug,
					'categorized' => true,
				),
			);

			if ( count( $group['categories'] ) < 2 ) {
				continue;
			}

			foreach ( $group['categories'] as $cat_slug => $category ) {
				$data['views'][] = array(
					'handle'    => $cat_slug,
					'name'      => $category['name'],
					'isSubItem' => true,
					'parent'    => $slug,
					'query'     => array(
						'kind'        => 'template',
						'type'        => 'core',
						'content'     => 'layout',
						'group'       => $slug,
						'category'    => $cat_slug,
						'categorized' => true,
					),
				);
			}
		}

		return $data;
	}

	/**
	 * Get all the insertable content elements that make up the content library.
	 *

	 * @access public
	 * @return array
	 */
	public static function get_content_elements() {

		$data = array(

			/* Get all modules */
			'module'   => BACheetahModel::get_uncategorized_modules(),

			/* Get all column groups */
			'colGroup' => BACheetahModel::get_column_groups(),

			/* Get all templates */
			'template' => array(),

			/* Get all builderall modules */
			'builderall'      => BACheetahModel::get_builderall_modules_config(),

			/*  Get all pro modules */
			'pro'      => BACheetahModel::get_pro_modules_config(),
		);

		$static_modules   = BACheetahModel::get_module_templates_data();
		$module_templates = $static_modules['templates'];

		foreach ( $module_templates as $template ) {
			$data['template'][] = $template;
		}

		$static_columns   = BACheetahModel::get_column_templates_data();
		$column_templates = $static_columns['templates'];

		foreach ( $column_templates as $template ) {
			$data['template'][] = $template;
		}

		$static_rows   = BACheetahModel::get_row_templates_data();
		$row_templates = $static_rows['templates'];

		foreach ( $row_templates as $template ) {
			$data['template'][] = $template;
		}

		$static_templates = BACheetahModel::get_template_selector_data();
		$layout_templates = $static_templates['templates'];

		foreach ( $layout_templates as $template ) {
			$data['template'][] = $template;
		}

		/**
		* Filter the available content elements
		*

		* @param Array $data the initial content elements
		* @see ba_cheetah_content_elements_data
		*/
		return apply_filters( 'ba_cheetah_content_elements_data', $data );
	}
}
