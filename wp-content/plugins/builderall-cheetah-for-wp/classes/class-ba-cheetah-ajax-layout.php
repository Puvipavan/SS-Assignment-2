<?php

/**
 * Handles the rendering of the layout for AJAX refreshes.
 *

 */
final class BACheetahAJAXLayout {

	/**
	 * An array with data for partial refreshes.
	 *

	 * @access private
	 * @var array $partial_refresh_data
	 */
	static private $partial_refresh_data = null;

	/**
	 * Renders the layout data to be passed back to the builder.
	 *

	 * @param string $node_id The ID of a node to try and render instead of the entire layout.
	 * @param string $old_node_id The ID of a node that has been replaced in the layout.
	 * @return array
	 */
	static public function render( $node_id = null, $old_node_id = null ) {
		/**
		 * Before ajax layout rendered.
		 * @see ba_cheetah_before_render_ajax_layout
		 */
		do_action( 'ba_cheetah_before_render_ajax_layout' );

		// Update the node ID in the post data?
		if ( $node_id ) {
			BACheetahModel::update_post_data( 'node_id', $node_id );
		}

		// Register scripts needed for shortcodes and widgets.
		self::register_scripts();

		// Dequeue scripts and styles to only capture those that are needed.
		self::dequeue_scripts_and_styles();

		// Get the partial refresh data.
		$partial_refresh_data = self::get_partial_refresh_data();

		// Render the markup.
		$html = self::render_html();

		// Render scripts and styles.
		$scripts_styles = self::render_scripts_and_styles();

		// Render the assets.
		$assets = self::render_assets();

		/**
		 * After ajax layout rendered.
		 * @see ba_cheetah_after_render_ajax_layout
		 */
		do_action( 'ba_cheetah_after_render_ajax_layout' );

		/**
		 * Return filtered response.
		 * @see ba_cheetah_ajax_layout_response
		 */
		return apply_filters( 'ba_cheetah_ajax_layout_response', array(
			'partial'       => $partial_refresh_data['is_partial_refresh'],
			'nodeId'        => $partial_refresh_data['node_id'],
			'nodeType'      => $partial_refresh_data['node_type'],
			'moduleType'    => $partial_refresh_data['module_type'],
			'oldNodeId'     => $old_node_id,
			'html'          => $html,
			'scriptsStyles' => $scripts_styles,
			'css'           => $assets['css'],
			'js'            => $assets['js'],
		) );
	}

	/**
	 * Renders the layout data for a new row.
	 *

	 * @param string $cols The type of column layout to use.
	 * @param int $position The position of the new row in the layout.
	 * @param string $module Optional. The node ID of an existing module to move to this row.
	 * @return array
	 */
	static public function render_new_row( $cols = '1-col', $position = false, $module = null ) {
		// Add the row.
		$row = BACheetahModel::add_row( $cols, $position, $module );

		$js = BACheetah::render_row_modules_js($row->node);
		$js .= 'BACheetah._renderLayoutComplete();';

		/**
		 * Render the row.
		 * @see ba_cheetah_before_render_ajax_layout_html
		 */
		do_action( 'ba_cheetah_before_render_ajax_layout_html' );
		ob_start();
		BACheetah::render_row( $row );
		$html = ob_get_clean();

		/**
		 * After rendering row.
		 * @see ba_cheetah_after_render_ajax_layout_html
		 */
		do_action( 'ba_cheetah_after_render_ajax_layout_html' );

		// Return the response.
		return array(
			'partial'  => true,
			'nodeType' => $row->type,
			'html'     => $html,
			'js'       => $js,
		);
	}

	/**
	 * Renders the layout data for a new row template.
	 *

	 * @param int $position The position of the new row in the layout.
	 * @param string $template_id The ID of a row template to render.
	 * @param string $template_type The type of template. Either "user" or "core".
	 * @return array
	 */
	static public function render_new_row_template( $position, $template_id, $template_type = 'user', $content_central_id = 0 ) {

		try {

			if ( class_exists( 'BACheetahTemplatesOverride' ) && BACheetahTemplatesOverride::show_rows() && BACheetahTemplatesOverride::get_source_site_id() ) {
				$row = BACheetahModel::apply_node_template( $template_id, null, $position );
			} elseif ( 'core' == $template_type ) {
				$template = BACheetahModel::get_template( $template_id, 'row', $content_central_id );
				$row      = BACheetahModel::apply_node_template( $template_id, null, $position, $template );
			} else {
				$row = BACheetahModel::apply_node_template( $template_id, null, $position );
			}
	
			return array(
				'layout' => self::render( $row->node ),
				'config' => BACheetahUISettingsForms::get_node_js_config(),
			);
		} catch ( BACheetahRetrieveTemplateException $e ) {
			return array(
				'ba_cheetah_error' => $e->getMessage()
			);
		}
	}

	/**
	 * Renders the layout data for a copied row.
	 *

	 * @param string $node_id The ID of a row to copy.
	 * @param object $settings These settings will be used for the copy if present.
	 * @param string $settings_id The ID of the node who's settings were passed.
	 * @return array
	 */
	static public function copy_row( $node_id, $settings = null, $settings_id = null ) {
		$row = BACheetahModel::copy_row( $node_id, $settings, $settings_id );

		return self::render( $row->node );
	}

	/**
	 * Renders the layout data for a new column group.
	 *

	 * @param string $node_id The node ID of a row to add the new group to.
	 * @param string $cols The type of column layout to use.
	 * @param int $position The position of the new column group in the row.
	 * @param string $module Optional. The node ID of an existing module to move to this group.
	 * @return array
	 */
	static public function render_new_column_group( $node_id, $cols = '1-col', $position = false, $module = null ) {
		// Add the group.
		$group = BACheetahModel::add_col_group( $node_id, $cols, $position, $module );

		$js = BACheetah::render_column_group_modules_js($group->node);
		$js .= 'BACheetah._renderLayoutComplete();';

		/**
		 * Render the group.
		 * @see ba_cheetah_before_render_ajax_layout_html
		 */
		do_action( 'ba_cheetah_before_render_ajax_layout_html' );
		ob_start();
		BACheetah::render_column_group( $group );
		$html = ob_get_clean();

		/**
		 * After rendering group.
		 * @see ba_cheetah_after_render_ajax_layout_html
		 */
		do_action( 'ba_cheetah_after_render_ajax_layout_html' );

		// Return the response.
		return array(
			'partial'  => true,
			'nodeType' => $group->type,
			'html'     => $html,
			'js'       => $js,
		);
	}

	/**
	 * Renders the layout data for a new column or columns.
	 *

	 * @param string $node_id Node ID of the column to insert before or after.
	 * @param string $insert Either before or after.
	 * @param string $type The type of column(s) to insert.
	 * @param boolean $nested Whether these columns are nested or not.
	 * @param string $module Optional. The node ID of an existing module to move to this group.
	 * @return array
	 */
	static public function render_new_columns( $node_id, $insert, $type, $nested, $module = null ) {
		// Add the column(s).
		$group = BACheetahModel::add_cols( $node_id, $insert, $type, $nested, $module );

		// Return the response.
		return self::render( $group->node );
	}

	/**
	 * Renders a new column template.
	 *

	 * @param string $template_id The ID of a column template to render.
	 * @param string $parent_id A column node ID.
	 * @param int $position The new column position.
	 * @param string $template_type The type of template. Either "user" or "core".
	 * @return array
	 */
	static public function render_new_col_template( $template_id, $parent_id = null, $position = false, $template_type = 'user' ) {
		if ( 'core' == $template_type ) {
			$template = BACheetahModel::get_template( $template_id, 'column' );
			$column   = BACheetahModel::apply_node_template( $template_id, $parent_id, $position, $template );
		} else {
			$column = BACheetahModel::apply_node_template( $template_id, $parent_id, $position );
		}

		// Get the new column parent.
		$parent = ! $parent_id ? null : BACheetahModel::get_node( $parent_id );

		// Get the node to render.
		if ( ! $parent ) {
			$row       = BACheetahModel::get_col_parent( 'row', $column );
			$render_id = $row->node;
		} elseif ( 'row' == $parent->type ) {
			$group     = BACheetahModel::get_col_parent( 'column-group', $column );
			$render_id = $group->node;
		} elseif ( 'column-group' == $parent->type ) {
			$render_id = $parent->node;
		} else {
			$render_id = $column->node;
		}

		// Return the response.
		return array(
			'layout' => self::render( $render_id ),
			'config' => BACheetahUISettingsForms::get_node_js_config(),
		);
	}

	/**
	 * Renders the layout data for a copied column.
	 *

	 * @param string $node_id The ID of a column to copy.
	 * @param object $settings These settings will be used for the copy if present.
	 * @param string $settings_id The ID of the node who's settings were passed.
	 * @return array
	 */
	static public function copy_col( $node_id, $settings = null, $settings_id = null ) {
		$col = BACheetahModel::copy_col( $node_id, $settings, $settings_id );

		return self::render( $col->node );
	}

	/**
	 * Renders the layout data for a new module.
	 *

	 * @param string $parent_id A column node ID.
	 * @param int $position The new module position.
	 * @param string $type The type of module.
	 * @param string $alias Module alias slug if this module is an alias.
	 * @param string $template_id The ID of a module template to render.
	 * @param string $template_type The type of template. Either "user" or "core".
	 * @return array
	 */
	static public function render_new_module( $parent_id, $position = false, $type = null, $alias = null, $template_id = null, $template_type = 'user' ) {
		// Add a module template?
		if ( null !== $template_id ) {

			if ( 'core' == $template_type ) {
				$template = BACheetahModel::get_template( $template_id, 'module' );
				$module   = BACheetahModel::apply_node_template( $template_id, $parent_id, $position, $template );
			} else {
				$module = BACheetahModel::apply_node_template( $template_id, $parent_id, $position );
			}
		} else {
			$defaults = BACheetahModel::get_module_alias_settings( $alias );
			$module   = BACheetahModel::add_default_module( $parent_id, $type, $position, $defaults );
		}

		// Maybe render the module's parent for a partial refresh?
		if ( $module->partial_refresh ) {

			// Get the new module parent.
			$parent = ! $parent_id ? null : BACheetahModel::get_node( $parent_id );

			// Get the node to render.
			if ( ! $parent ) {
				$row       = BACheetahModel::get_module_parent( 'row', $module );
				$render_id = $row->node;
			} elseif ( 'row' == $parent->type ) {
				$group     = BACheetahModel::get_module_parent( 'column-group', $module );
				$render_id = $group->node;
			} elseif ( 'column-group' == $parent->type ) {
				$render_id = $parent->node;
			} else {
				$render_id = $module->node;
			}
		} else {
			$render_id = null;
		}

		// Return the response.
		return array(
			'type'     => $module->settings->type,
			'nodeId'   => $module->node,
			'parentId' => $module->parent,
			'global'   => BACheetahModel::is_node_global( $module ),
			'layout'   => self::render( $render_id ),
			'settings' => null === $template_id && ! $alias ? null : $module->settings,
			'legacy'   => BACheetahUISettingsForms::pre_render_legacy_module_settings( $module->settings->type, $module->settings ),
		);
	}

	/**
	 * Renders the layout data for a copied module.
	 *

	 * @param string $node_id The ID of a module to copy.
	 * @param object $settings These settings will be used for the copy if present.
	 * @return array
	 */
	static public function copy_module( $node_id, $settings = null ) {
		$module = BACheetahModel::copy_module( $node_id, $settings );

		return self::render( $module->node );
	}

	/**
	 * Returns an array of partial refresh data.
	 *

	 * @access private
	 * @return array
	 */
	static private function get_partial_refresh_data() {
		// Get the data if it's not cached.
		if ( ! self::$partial_refresh_data ) {

			$post_data       = BACheetahModel::get_cheetah_ba_data();
			$partial_refresh = false;
			$node_type       = null;
			$module_type     = null;

			// Check for partial refresh if we have a node ID.
			if ( isset( $post_data['node_id'] ) ) {

				// Get the node.
				$node_id   = sanitize_text_field($post_data['node_id']);
				$node      = BACheetahModel::get_node( $post_data['node_id'] );
				$node_type = null;

				// Check a module for partial refresh.
				if ( $node && 'module' == $node->type ) {
					$node            = BACheetahModel::get_module( $node_id );
					$node_type       = 'module';
					$module_type     = $node->settings->type;
					$partial_refresh = $node->partial_refresh;
				} elseif ( $node ) {
					$node_type       = $node->type;
					$partial_refresh = self::node_modules_support_partial_refresh( $node );
				}
			} else {
				$node_id   = null;
				$node      = null;
				$node_type = null;
			}

			// Cache the partial refresh data.
			self::$partial_refresh_data = array(
				'is_partial_refresh' => $partial_refresh,
				'node_id'            => $node_id,
				'node'               => $node,
				'node_type'          => $node_type,
				'module_type'        => $module_type,
			);
		}

		// Return the data.
		return self::$partial_refresh_data;
	}

	/**
	 * Checks to see if all modules in a node support partial refresh.
	 *

	 * @access private
	 * @param object $node The node to check.
	 * @return bool
	 */
	static private function node_modules_support_partial_refresh( $node ) {
		$nodes = BACheetahModel::get_categorized_nodes();

		if ( 'row' == $node->type ) {

			$template_post_id = BACheetahModel::is_node_global( $node );

			foreach ( $nodes['groups'] as $group ) {
				if ( $node->node == $group->parent || ( $template_post_id && $node->template_node_id == $group->parent ) ) {
					foreach ( $nodes['columns'] as $column ) {
						if ( $group->node == $column->parent ) {
							foreach ( $nodes['modules'] as $module ) {
								if ( $column->node == $module->parent ) {
									if ( ! $module->partial_refresh ) {
										return false;
									}
								}
							}
						}
					}
				}
			}
		} elseif ( 'column-group' == $node->type ) {
			foreach ( $nodes['columns'] as $column ) {
				if ( $node->node == $column->parent ) {
					foreach ( $nodes['modules'] as $module ) {
						if ( $column->node == $module->parent ) {
							if ( ! $module->partial_refresh ) {
								return false;
							}
						}
					}
				}
			}
		} elseif ( 'column' == $node->type ) {
			foreach ( $nodes['modules'] as $module ) {
				if ( $node->node == $module->parent ) {
					if ( ! $module->partial_refresh ) {
						return false;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Renders the html for the layout or node.
	 *

	 * @access private
	 * @return string
	 */
	static private function render_html() {
		/**
		 * Before html for layout or node is rendered.
		 * @see ba_cheetah_before_render_ajax_layout_html
		 */
		do_action( 'ba_cheetah_before_render_ajax_layout_html' );

		// Get the partial refresh data.
		$partial_refresh_data = self::get_partial_refresh_data();

		// Start the output buffer.
		ob_start();

		// Render a node?
		if ( $partial_refresh_data['is_partial_refresh'] ) {

			switch ( $partial_refresh_data['node']->type ) {

				case 'row':
				case 'row-inside':
					BACheetah::render_row( $partial_refresh_data['node'] );
					break;

				case 'column-group':
					BACheetah::render_column_group( $partial_refresh_data['node'] );
					break;

				case 'column':
					BACheetah::render_column( $partial_refresh_data['node'] );
					break;

				case 'module':
					BACheetah::render_module( $partial_refresh_data['node'] );
					break;
			}
		} else {
			BACheetah::render_nodes();
		}

		// Get the rendered HTML.
		$html = ob_get_clean();

		/**
		 * Use this filter to prevent the builder from rendering shortcodes.
		 * It is useful if you don't want shortcodes rendering while the builder UI is active.
		 * @see ba_cheetah_render_shortcodes
		 */
		if ( apply_filters( 'ba_cheetah_render_shortcodes', true ) ) {
			/**
			 * Used with ba_cheetah_render_shortcodes shortcode.
			 * @see ba_cheetah_before_render_shortcodes
			 */
			$html = apply_filters( 'ba_cheetah_before_render_shortcodes', $html );
			ob_start();
			echo do_shortcode( $html );
			$html = ob_get_clean();
		}
		/**
		 * After html for layout or node is rendered.
		 * @see ba_cheetah_after_render_ajax_layout_html
		 */
		do_action( 'ba_cheetah_after_render_ajax_layout_html' );

		// Return the rendered HTML.
		return $html;
	}

	/**
	 * Renders the assets for the layout or a node.
	 *

	 * @access private
	 * @return array
	 */
	static private function render_assets() {
		$partial_refresh_data = self::get_partial_refresh_data();
		$asset_info           = BACheetahModel::get_asset_info();
		$asset_ver            = BACheetahModel::get_asset_version();
		$enqueuemethod        = BACheetahModel::get_asset_enqueue_method();
		$assets               = array(
			'js'  => '',
			'css' => '',
		);

		// Ensure global assets are rendered.
		BACheetah::clear_enqueued_global_assets();

		// Render the JS.
		if ( $partial_refresh_data['is_partial_refresh'] ) {

			if ( ! class_exists( 'BACheetahJSMin' ) ) {
				include BA_CHEETAH_DIR . 'classes/class-ba-jsmin.php';
			}

			switch ( $partial_refresh_data['node']->type ) {

				case 'row':
				case 'row-inside':
					$assets['js']  = BACheetah::render_row_js( $partial_refresh_data['node'] );
					$assets['js'] .= BACheetah::render_row_modules_js( $partial_refresh_data['node'] );
					break;

				case 'column-group':
					$assets['js'] = BACheetah::render_column_group_modules_js( $partial_refresh_data['node'] );
					break;

				case 'column':
					$assets['js'] = BACheetah::render_column_modules_js( $partial_refresh_data['node'] );
					break;

				case 'module':
					$assets['js'] = BACheetah::render_module_js( $partial_refresh_data['node'] );
					break;
			}

			$assets['js'] .= 'BACheetah._renderLayoutComplete();';

			try {
				$min = BACheetahJSMin::minify( $assets['js'] );
				if ( $min ) {
					$assets['js'] = $min;
				}
			} catch ( Exception $e ) {
			}

		} elseif ( 'inline' === $enqueuemethod ) {
			$assets['js'] = BACheetah::render_js();
		} else {
			BACheetah::render_js();
			$assets['js'] = $asset_info['js_url'] . '?ver=' . $asset_ver;
		}

		// Render the CSS.
		if ( 'inline' === $enqueuemethod ) {
			$assets['css'] = BACheetah::render_css();
		} else {
			BACheetah::render_css();
			$assets['css'] = $asset_info['css_url'] . '?ver=' . $asset_ver;
		}

		// Return the assets.
		return $assets;
	}

	/**
	 * Do the wp_enqueue_scripts action to register any scripts or
	 * styles that might need to be registered for shortcodes or widgets.
	 *

	 * @access private
	 * @return void
	 */
	static private function register_scripts() {
		// Running these isn't necessary and can cause performance issues.
		remove_action( 'wp_enqueue_scripts', 'BACheetah::register_layout_styles_scripts' );
		remove_action( 'wp_enqueue_scripts', 'BACheetah::enqueue_ui_styles_scripts' );
		remove_action( 'wp_enqueue_scripts', 'BACheetah::enqueue_all_layouts_styles_scripts' );

		ob_start();
		do_action( 'wp_enqueue_scripts' );
		ob_end_clean();
	}

	/**
	 * Dequeue scripts and styles so we can capture only those
	 * enqueued by shortcodes or widgets.
	 *

	 * @access private
	 * @return void
	 */
	static private function dequeue_scripts_and_styles() {
		global $wp_scripts;
		global $wp_styles;

		if ( isset( $wp_scripts ) ) {
			$wp_scripts->queue = array();
		}
		if ( isset( $wp_styles ) ) {
			$wp_styles->queue = array();
		}

		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

	/**
	 * Renders scripts and styles enqueued by shortcodes or widgets.
	 *

	 * @access private
	 * @return string
	 */
	static private function render_scripts_and_styles() {
		global $wp_scripts;
		global $wp_styles;

		$partial_refresh_data = self::get_partial_refresh_data();
		$modules              = array();
		$scripts_styles       = '';

		// Enqueue module font styles.
		if ( ! $partial_refresh_data['is_partial_refresh'] ) {
			$modules = BACheetahModel::get_all_modules();
		} elseif ( 'module' !== $partial_refresh_data['node']->type ) {
			$nodes = BACheetahModel::get_nested_nodes( $partial_refresh_data['node'] );
			foreach ( $nodes as $node ) {
				if ( 'module' === $node->type && isset( BACheetahModel::$modules[ $node->settings->type ] ) ) {
					$node->form = BACheetahModel::$modules[ $node->settings->type ]->form;
					$modules[]  = $node;
				}
			}
		} else {
			$modules = array( $partial_refresh_data['node'] );
		}

		foreach ( $modules as $module ) {
			BACheetahFonts::add_fonts_for_module( $module );
		}

		BACheetahFonts::enqueue_styles();
		BACheetahFonts::enqueue_google_fonts();

		// Start the output buffer.
		ob_start();

		// Print scripts and styles.
		if ( isset( $wp_scripts ) ) {
			$wp_scripts->done[] = 'jquery';
			wp_print_scripts( $wp_scripts->queue );
		}
		if ( isset( $wp_styles ) ) {
			wp_print_styles( $wp_styles->queue );
		}

		// Return the scripts and styles markup.
		return ob_get_clean();
	}


	/**
	 * Get a list of headers to display in the page settings via ajax
	 *
	 * @return void
	 */
	public static function get_headers_for_select() 
	{
		return self::custom_post_type_list_to_select("ba-cheetah-header");
	}

	/**
	 * Get a list of footers to display in the page settings via ajax
	 *
	 * @return void
	 */
	public static function get_footers_for_select() 
	{
		return self::custom_post_type_list_to_select("ba-cheetah-footer");
	}

	private static function custom_post_type_list_to_select($type) 
	{
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $type,
			'suppress_filters' => true
		);
	
		$items = array(
			array(
				'value' => '',
				'label' => __('Select one', 'ba-cheetah')
			)
		);

		foreach (get_posts($args) as $key => $item) {
			array_push($items, array(
				'value' => $item->ID,
				'label' => $item->post_title
			));
		}
	
		return $items;
	}
}
