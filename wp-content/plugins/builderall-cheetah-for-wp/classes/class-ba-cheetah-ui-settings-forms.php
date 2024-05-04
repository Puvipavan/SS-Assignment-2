<?php

/**
 * Handles logic for UI settings forms.
 *

 */
class BACheetahUISettingsForms {

	/**
	 * An array of JS templates for custom form tabs and
	 * sections that need to be loaded.
	 *

	 * @var int $form_templates
	 */
	static private $form_templates = array();

	/**
	 * An array of core fields that are used for style settings.
	 *

	 * @var int $style_fields
	 */
	static private $style_fields = array(
		'align',
		'animation',
		'border',
		'button-group',
		'color',
		'dimension',
		'font',
		'gradient',
		'photo-sizes',
		'select',
		'shadow',
		'shape-transform',
		'typography',
		'unit',
	);

	/**

	 * @return void
	 */
	static public function init() {
		add_action( 'init', __CLASS__ . '::init_style_fields' );
		add_action( 'wp', __CLASS__ . '::render_settings_config' );
		add_action( 'wp_enqueue_scripts', __CLASS__ . '::enqueue_settings_config', 11 );
		add_action( 'wp_footer', __CLASS__ . '::init_js_config', 1 );
		add_action( 'wp_footer', __CLASS__ . '::render_js_templates', 11 );
		add_filter( 'ba_cheetah_ui_js_config', __CLASS__ . '::layout_css_js' );
	}

	/**
	 * Allow developers to filter style fields and add their own.
	 *

	 * @return void
	 */
	static public function init_style_fields() {
		self::$style_fields = apply_filters( 'ba_cheetah_style_fields', self::$style_fields );
	}

	/**
	 * Adds an inline script for general settings config and
	 * one for module settings config.
	 *

	 * @return void
	 */
	static public function enqueue_settings_config() {
		global $wp_the_query;

		if ( BACheetahModel::is_builder_active() ) {

			$script_url  = add_query_arg( array(
				'ba_cheetah_load_settings_config' => true,
				'ver'                             => rand(),
			), BACheetahModel::get_edit_url( $wp_the_query->post->ID ) );
			$modules_url = add_query_arg( array(
				'ba_cheetah_load_settings_config' => 'modules',
				'ver'                             => rand(),
			), BACheetahModel::get_edit_url( $wp_the_query->post->ID ) );
			$script      = 'var s = document.createElement("script");s.type = "text/javascript";s.src = "%s";document.head.appendChild(s);';
			$config      = sprintf( $script, $script_url );
			$modules     = sprintf( $script, $modules_url );

			wp_add_inline_script( 'ba-cheetah', $config );
			wp_add_inline_script( 'ba-cheetah-min', $config );
			wp_add_inline_script( 'ba-cheetah', $modules );
			wp_add_inline_script( 'ba-cheetah-min', $modules );
		}
	}

	/**
	 * Renders the JS config for settings forms for the current page if requested
	 * and dies early so it can be loaded from a script tag.
	 *

	 * @return void
	 */
	static public function render_settings_config() {
		if ( BACheetahModel::is_builder_active() && isset( $_GET['ba_cheetah_load_settings_config'] ) ) {

			// Increase available memory.
			if ( function_exists( 'wp_raise_memory_limit' ) ) {
				wp_raise_memory_limit( 'wp-cheetah' );
			}

			$type = sanitize_key( $_GET['ba_cheetah_load_settings_config'] );

			if ( 'modules' === $type ) {
				$settings = BACheetahUISettingsForms::get_modules_js_config();
			} else {
				$settings = BACheetahUISettingsForms::get_js_config();
			}

			header( 'Content-Type: application/javascript' );

			ob_start();
			include BA_CHEETAH_DIR . 'includes/ui-settings-config.php';
			ob_end_flush();

			die();
		}
	}

	/**
	 * Attempts to use the output buffer gzip handler to compress
	 * the settings config. We have to do it this way to prevent
	 * errors we were running into on some hosts.


	 * @param string $buffer $mode
	 * @return string
	 */

	static public function compress_settings_config( $buffer, $mode ) {
		@ob_gzhandler( $buffer, null ); // @codingStandardsIgnoreLine
		return $buffer;
	}

	/**
	 * Initializes the JS config by calling the get method early on
	 * wp_footer (before the UI renders). This needs to be done so
	 * changes to the builder's form config arrays are made before the
	 * JS templates are printed.
	 *
	 *

	 * @return void
	 */
	static public function init_js_config() {
		self::get_js_config();
		self::get_modules_js_config();
	}

	/**
	 * Returns the JS config for all settings forms except
	 * modules which are loaded in a separate request.
	 *

	 * @return array
	 */
	static public function get_js_config() {
		return array(
			'forms'       => self::prep_forms_for_js_config( BACheetahModel::$settings_forms ),
			'editables'   => self::prep_editables_for_js_config(),
			'nodes'       => self::prep_node_settings_for_js_config(),
			'attachments' => self::prep_attachments_for_js_config(),
			'settings'    => array(
				'global' => BACheetahModel::get_global_settings(),
				'layout' => BACheetahModel::get_layout_settings(),
				'page'	 => BACheetahModel::get_page_settings(),
			),
			'defaults'    => array(
				'row'     => BACheetahModel::get_row_defaults(),
				'column'  => BACheetahModel::get_col_defaults(),
				'modules' => BACheetahModel::get_module_defaults(),
				'forms'   => self::prep_form_defaults_for_js_config( BACheetahModel::$settings_forms ),
			),
		);
	}

	/**
	 * Returns the JS config for all modules.
	 *

	 * @return array
	 */
	static public function get_modules_js_config() {
		return array(
			'modules' => self::prep_module_forms_for_js_config(),
		);
	}

	/**
	 * Returns only the node JS config for settings forms.
	 *

	 * @return array
	 */
	static public function get_node_js_config() {
		return array(
			'nodes'       => self::prep_node_settings_for_js_config(),
			'attachments' => self::prep_attachments_for_js_config(),
		);
	}

	/**
	 * Prepares form defaults for the JS config.
	 *

	 * @param array $forms
	 * @return array
	 */
	static private function prep_form_defaults_for_js_config( $forms ) {
		$defaults = array();

		foreach ( $forms as $form_key => $form ) {
			if ( isset( $form['tabs'] ) ) {
				$defaults[ $form_key ] = BACheetahModel::get_settings_form_defaults( $form_key );
			}
		}

		return $defaults;
	}

	/**
	 * Prepares forms for the JS config.
	 *

	 * @param array $forms
	 * @return array
	 */
	static private function prep_forms_for_js_config( $forms ) {

		foreach ( $forms as $form_key => &$form ) {

			if ( ! isset( $form['tabs'] ) ) {
				continue;
			}

			foreach ( $form['tabs'] as $tab_key => &$tab ) {

				if ( isset( $tab['template'] ) ) {
					self::$form_templates[ $tab['template']['id'] ] = $tab['template']['file'];
				}

				if ( ! isset( $tab['sections'] ) ) {
					continue;
				}

				foreach ( $tab['sections'] as $section_key => &$section ) {

					if ( isset( $section['file'] ) && BA_CHEETAH_DIR . 'includes/service-settings.php' === $section['file'] ) {
						$section['template'] = array(
							'id'   => 'ba-cheetah-service-settings',
							'file' => BA_CHEETAH_DIR . 'includes/ui-service-settings.php',
						);
						unset( $section['file'] );
					}

					if ( isset( $section['template'] ) ) {
						self::$form_templates[ $section['template']['id'] ] = $section['template']['file'];
					}

					if ( ! isset( $section['fields'] ) ) {
						continue;
					}

					foreach ( $section['fields'] as $field_key => &$field ) {
						self::prep_field_for_js_config( $field, $field_key, $form_key );
					}
				}
			}
		}

		return $forms;
	}

	/**
	 * Prepares a field for the JS config.
	 *

	 * @param array $field
	 * @param string $field_key
	 * @param string $form_key
	 * @return void
	 */
	static private function prep_field_for_js_config( &$field, $field_key = '', $form_key = '' ) {

		/**
		 * This filter hook replaces pre-2.0 `ba_cheetah_render_settings_field` one.
		 *
		 * @param  array  $field      An array of setup data for the field.
		 * @param  string $field_key  The field name/key.
		 * @param  string $form_key   Module/form key.
		 */
		$field = apply_filters( 'ba_cheetah_field_js_config', $field, $field_key, $form_key );

		// Bail if the field has no type.
		if ( ! isset( $field['type'] ) ) {
			return;
		}

		// Convert class to className for JS compat.
		if ( isset( $field['class'] ) ) {
			$field['className'] = $field['class'];
		}

		// Select fields
		if ( 'select' === $field['type'] ) {

			if ( is_string( $field['options'] ) && is_callable( $field['options'] ) ) {
				$field['options'] = call_user_func( $field['options'] );
			} else {
				$field['options'] = (array) $field['options'];
			}
		}

		// Mark fields as style fields.
		if ( ! isset( $field['is_style'] ) ) {
			$field['is_style'] = in_array( $field['type'], self::$style_fields );
		}
	}

	/**
	 * Gathers and prepares module forms for the JS config.
	 *

	 * @return array
	 */
	static public function prep_module_forms_for_js_config() {
		$module_forms = array();

		foreach ( BACheetahModel::$modules as $module ) {

			$cssurl = '';
			$jsurl  = '';

			$css_file_path = apply_filters( "ba_cheetah_module_settings_css_file_path_{$module->slug}", "{$module->dir}css/settings.css", $module );
			$css_file_uri  = apply_filters( "ba_cheetah_module_settings_css_file_uri_{$module->slug}", "{$module->url}css/settings.css", $module );
			$js_file_path  = apply_filters( "ba_cheetah_module_settings_js_file_path_{$module->slug}", "{$module->dir}js/settings.js", $module );
			$js_file_uri   = apply_filters( "ba_cheetah_module_settings_js_file_uri_{$module->slug}", "{$module->url}js/settings.js", $module );

			if ( file_exists( $css_file_path ) ) {
				$cssurl = $css_file_uri;
			}
			if ( file_exists( $js_file_path ) ) {
				$jsurl = $js_file_uri;
			}

			$module_forms[ $module->slug ] = array(
				'title'  => $module->name,
				'tabs'   => $module->form,
				'assets' => array(
					'jsurl' => $jsurl,
					'cssurl'=> $cssurl
				),
			);
		}

		return self::prep_forms_for_js_config( $module_forms );
	}

	/**
	 * Gathers and prepares inline module editing data for the JS config.
	 *

	 * @return array
	 */
	static public function prep_editables_for_js_config() {
		$editables = array();

		foreach ( BACheetahModel::$modules as $module ) {
			$fields = BACheetahModel::get_settings_form_fields( $module->form );

			foreach ( $fields as $key => $field ) {

				if ( 'code' === $field['type'] ) {
					continue;
				}

				if ( ! isset( $field['preview'] ) ) {
					continue;
				}

				if ( ! isset( $field['preview']['type'] ) || 'text' !== $field['preview']['type'] ) {
					continue;
				}

				if ( ! isset( $field['preview']['selector'] ) ) {
					continue;
				}

				if ( isset( $field['inline_editor'] ) && ! $field['inline_editor'] ) {
					continue;
				}

				if ( ! isset( $editables[ $module->slug ] ) ) {
					$editables[ $module->slug ] = array();
				}

				$editables[ $module->slug ][ $key ] = array(
					'selector' => $field['preview']['selector'],
					'field'    => array(
						'name'    => $key,
						'type'    => $field['type'],
						'toolbar' => isset( $field['inline_editor'] ) ? $field['inline_editor'] : null,
					),
				);
			}
		}

		return $editables;
	}

	/**
	 * Gathers and prepares node settings for the JS config.
	 *

	 * @return array
	 */
	static public function prep_node_settings_for_js_config() {
		$layout_data   = BACheetahModel::get_layout_data();
		$node_settings = array();

		foreach ( $layout_data as $node_id => $node ) {
			if ( ! is_object( $node ) || ! isset( $node->settings ) || ! is_object( $node->settings ) ) {
				continue;
			}
			$node_settings[ $node_id ] = BACheetahModel::get_node_settings( $node, false );
		}

		return $node_settings;
	}

	/**
	 * Gathers and prepares attachments for the JS config.
	 *

	 * @return array
	 */
	static private function prep_attachments_for_js_config() {

		$layout_data = BACheetahModel::get_layout_data();
		$attachments = array();

		// Look for background image
		$global_settings = BACheetahModel::get_global_settings();
		if (!empty($global_settings->bg_image) && is_numeric( $global_settings->bg_image )) {
			$data = self::prep_attachment_for_js_config( $global_settings->bg_image );
			if ( $data ) {
				$attachments[ $global_settings->bg_image ] = $data;
			}
		}

		foreach ( $layout_data as $node ) {

			if ( ! isset( $node->settings ) || ! is_object( $node->settings ) ) {
				continue;
			}

			if ( 'row' === $node->type ) {
				$fields = BACheetahModel::get_settings_form_fields( BACheetahModel::$settings_forms['row']['tabs'] );
			} elseif ( 'column' === $node->type ) {
				$fields = BACheetahModel::get_settings_form_fields( BACheetahModel::$settings_forms['col']['tabs'] );
			} elseif ( 'module' === $node->type && isset( BACheetahModel::$modules[ $node->settings->type ] ) ) {
				$fields = BACheetahModel::get_settings_form_fields( BACheetahModel::$modules[ $node->settings->type ]->form );
			} else {
				continue;
			}

			foreach ( $node->settings as $key => $value ) {

				// Look for image attachments.
				if ( strstr( $key, '_src' ) ) {

					$base = str_replace( '_src', '', $key );

					if ( isset( $node->settings->$base ) ) {

						if ( is_numeric( $node->settings->$base ) ) {
							$id   = $node->settings->$base;
							$data = self::prep_attachment_for_js_config( $id );
							if ( $data ) {
								$attachments[ $id ] = $data;
							}
						} elseif ( is_array( $node->settings->$base ) ) {
							foreach ( $node->settings->$base as $id ) {
								$data = self::prep_attachment_for_js_config( $id );
								if ( $data ) {
									$attachments[ $id ] = $data;
								}
							}
						}
					}
				}

				// Look for video attachments.
				if ( isset( $fields[ $key ] ) && 'video' === $fields[ $key ]['type'] ) {

					if ( is_numeric( $value ) ) {
						$id   = $value;
						$data = self::prep_attachment_for_js_config( $id );
						if ( $data ) {
							$attachments[ $id ] = $data;
						}
					} elseif ( is_array( $value ) ) {
						foreach ( $value as $id ) {
							$data = self::prep_attachment_for_js_config( $id );
							if ( $data ) {
								$attachments[ $id ] = $data;
							}
						}
					}
				}
			}
		}

		return $attachments;
	}

	/**
	 * Prepares a single attachment for the JS config.
	 *

	 * @param int $id
	 * @return array|bool
	 */
	static private function prep_attachment_for_js_config( $id ) {

		$url = wp_get_attachment_url( $id );

		if ( ! $url ) {
			return false;
		}

		$post           = get_post( $id );
		$filename       = wp_basename( $url );
		$base_url       = str_replace( $filename, '', $url );
		$meta           = wp_get_attachment_metadata( $id );
		$sizes          = array();
		$possible_sizes = apply_filters( 'image_size_names_choose', array(
			'thumbnail' => __( 'Thumbnail', 'ba-cheetah' ),
			'medium'    => __( 'Medium', 'ba-cheetah' ),
			'large'     => __( 'Large', 'ba-cheetah' ),
			'full'      => __( 'Full Size', 'ba-cheetah' ),
		) );

		if ( isset( $meta['sizes'] ) ) {
			foreach ( $meta['sizes'] as $size_key => $size ) {
				if ( ! isset( $possible_sizes[ $size_key ] ) ) {
					continue;
				}
				$sizes[ $size_key ] = array(
					'url'      => $base_url . $size['file'],
					'filename' => $size['file'],
					'width'    => $size['width'],
					'height'   => $size['height'],
				);
			}
		}

		if ( ! isset( $sizes['full'] ) ) {
			$sizes['full'] = array(
				'url'      => $url,
				'filename' => isset( $meta['file'] ) ? $meta['file'] : $filename,
				'width'    => isset( $meta['width'] ) ? $meta['width'] : '',
				'height'   => isset( $meta['height'] ) ? $meta['height'] : '',
			);
		}

		return array(
			'id'       => $id,
			'url'      => $url,
			'filename' => $filename,
			'caption'  => $post->post_excerpt,
			'sizes'    => apply_filters( 'ba_cheetah_photo_sizes_select', $sizes ),
		);
	}

	/**
	 * Renders the JS templates for settings forms.
	 *

	 * @return void
	 */
	static public function render_js_templates() {
		if ( ! BACheetahModel::is_builder_active() ) {
			return;
		}

		include BA_CHEETAH_DIR . 'includes/ui-settings-form.php';
		include BA_CHEETAH_DIR . 'includes/ui-settings-form-row.php';
		include BA_CHEETAH_DIR . 'includes/ui-field.php';

		$fields = glob( BA_CHEETAH_DIR . 'includes/ui-field-*.php' );
		$custom = apply_filters( 'ba_cheetah_custom_fields', array() );

		foreach ( $fields as $path ) {
			$slug = esc_attr(str_replace( array( 'ui-field-', '.php' ), '', basename( $path ) ));
			echo '<script type="text/html" id="tmpl-ba-cheetah-field-' . $slug . '">';
			include $path;
			echo '</script>';
		}

		foreach ( $custom as $type => $path ) {
			echo '<script type="text/html" id="tmpl-ba-cheetah-field-' . esc_attr($type) . '">';
			include $path;
			echo '</script>';
		}

		foreach ( self::$form_templates as $id => $path ) {
			if ( file_exists( $path ) ) {
				echo '<script type="text/html" id="tmpl-' . esc_attr($id) . '">';
				include $path;
				echo '</script>';
			}
		}
	}

	/**
	 * Pre-renders legacy settings tabs, sections and fields for
	 * new modules that are currently being sent to the frontend.
	 *

	 * @param string $type
	 * @param object $settings
	 * @return array
	 */
	static public function pre_render_legacy_module_settings( $type, $settings ) {
		$data   = array(
			'tabs'     => array(),
			'sections' => array(),
			'fields'   => array(),
			'settings' => $settings,
			'node_id'  => null,
		);
		$custom = apply_filters( 'ba_cheetah_custom_fields', array() );

		foreach ( BACheetahModel::$modules[ $type ]->form as $tab_id => $tab ) {

			if ( isset( $tab['file'] ) ) {
				$data['tabs'][] = $tab_id;
			}
			if ( ! isset( $tab['sections'] ) ) {
				continue;
			}

			foreach ( $tab['sections'] as $section_id => $section ) {

				if ( isset( $section['file'] ) ) {
					$data['sections'][] = array(
						'tab'     => $tab_id,
						'section' => $section_id,
					);
				}
				if ( ! isset( $section['fields'] ) ) {
					continue;
				}

				foreach ( $section['fields'] as $field_id => $field ) {

					$is_core   = file_exists( BA_CHEETAH_DIR . 'includes/ui-field-' . $field['type'] . '.php' );
					$is_custom = isset( $custom[ $field['type'] ] );

					if ( ! $is_core && ! $is_custom ) {
						$data['fields'][] = $field_id;
					}
				}
			}
		}

		return self::render_legacy_settings( $data, $type, 'module', null );
	}

	/**
	 * Renders legacy settings tabs, sections and fields.
	 *

	 * @param array $data
	 * @param string $form
	 * @param string $group
	 * @param string $lightbox
	 * @return array
	 */
	static public function render_legacy_settings( $data, $form, $group, $lightbox ) {
		$response = array(
			'lightbox' => $lightbox,
			'tabs'     => array(),
			'sections' => array(),
			'fields'   => array(),
			'extras'   => array(),
		);

		// Get the form tabs.
		if ( 'general' === $group ) {
			$tabs = BACheetahModel::$settings_forms[ $form ]['tabs'];
		} elseif ( 'module' === $group ) {
			$tabs = BACheetahModel::$modules[ $form ]->form;
		}

		// Get the form fields.
		$fields = BACheetahModel::get_settings_form_fields( $tabs );

		// Get the settings.
		if ( $data['node_id'] ) {
			$layout_data = BACheetahModel::get_layout_data();
			$settings    = $layout_data[ $data['node_id'] ]->settings;
		} else {
			$settings = isset( $data['settings'] ) ? (object) $data['settings'] : new stdClass();
		}

		// Render legacy custom fields.
		if ( isset( $data['fields'] ) ) {
			foreach ( $data['fields'] as $name ) {
				if ( ! isset( $fields[ $name ] ) ) {
					continue;
				}
				ob_start();
				self::render_settings_field( $name, (array) $fields[ $name ], $settings );
				$response['fields'][ $name ] = ob_get_clean();
			}
		}

		// Render legacy field extras with the before and after actions.
		foreach ( $fields as $name => $field ) {

			if ( in_array( $name, $response['fields'] ) ) {
				continue;
			}

			$value       = isset( $settings->$name ) ? $settings->$name : '';
			$is_multiple = isset( $field['multiple'] ) ? $field['multiple'] : false;

			if ( $is_multiple && is_array( $value ) ) {
				$before = array();
				$after  = array();
				foreach ( $value as $repeater_item_value ) {
					ob_start();
					do_action( 'ba_cheetah_before_control', $name, $repeater_item_value, $field, $settings );
					do_action( 'ba_cheetah_before_control_' . $field['type'], $name, $value, $field, $settings );
					$before[] = ob_get_clean();

					ob_start();
					do_action( 'ba_cheetah_after_control_' . $field['type'], $name, $value, $field, $settings );
					do_action( 'ba_cheetah_after_control', $name, $repeater_item_value, $field, $settings );
					$after[] = ob_get_clean();
				}
			} else {
				ob_start();
				do_action( 'ba_cheetah_before_control', $name, $value, $field, $settings );
				do_action( 'ba_cheetah_before_control_' . $field['type'], $name, $value, $field, $settings );
				$before = ob_get_clean();

				ob_start();
				do_action( 'ba_cheetah_after_control_' . $field['type'], $name, $value, $field, $settings );
				do_action( 'ba_cheetah_after_control', $name, $value, $field, $settings );
				$after = ob_get_clean();
			}

			if ( ! empty( $before ) || ! empty( $after ) ) {
				$response['extras'][ $name ] = array(
					'multiple' => $is_multiple,
					'before'   => $before,
					'after'    => $after,
				);
			}
		}

		// Render legacy custom sections.
		if ( isset( $data['sections'] ) ) {
			foreach ( $data['sections'] as $section_data ) {
				$tab     = $section_data['tab'];
				$name    = $section_data['section'];
				$section = $tabs[ $tab ]['sections'][ $name ];
				if ( file_exists( $section['file'] ) ) {
					if ( ! isset( $response['sections'][ $tab ] ) ) {
						$response['sections'][ $tab ] = array();
					}
					ob_start();
					include $section['file'];
					$response['sections'][ $tab ][ $name ] = ob_get_clean();
				}
			}
		}

		// Render legacy custom tabs.
		if ( isset( $data['tabs'] ) ) {
			foreach ( $data['tabs'] as $name ) {
				$tab = $tabs[ $name ];
				if ( BA_CHEETAH_DIR . 'includes/loop-settings.php' === $tab['file'] ) {
					$tab['file'] = BA_CHEETAH_DIR . 'includes/ui-loop-settings.php';
				}
				if ( file_exists( $tab['file'] ) ) {
					ob_start();
					include $tab['file'];
					$response['tabs'][ $name ] = ob_get_clean();
				}
			}
		}

		return $response;
	}

	/**
	 * Renders a settings via PHP. This method is only around for
	 * backwards compatibility with third party settings forms that are
	 * still being rendered via AJAX. Going forward, all settings forms
	 * should be rendered on the frontend using BACheetahSettingsForms.render.
	 *

	 * @param array $form The form data.
	 * @param object $settings The settings data.
	 * @return array
	 */
	static public function render_settings( $form, $settings ) {
		$defaults = array(
			'class'    => '',
			'attrs'    => '',
			'title'    => '',
			'badges'   => array(),
			'tabs'     => array(),
			'buttons'  => array(),
			'settings' => $settings,
		);

		/**
		 * Legacy filter for the config.
		 * @see ba_cheetah_settings_form_config
		 */
		$form = apply_filters( 'ba_cheetah_settings_form_config', array_merge( $defaults, (array) $form ) );

		// Setup the class var to be safe in JS.
		$form['className'] = $form['class'];
		unset( $form['class'] );

		// Get the form ID.
		foreach ( $form['tabs'] as $tab ) {
			$form['id'] = $tab['form_id'];
			break;
		}

		// We don't need to send tab data back.
		unset( $form['tabs'] );

		// Render and return!
		ob_start();
		include BA_CHEETAH_DIR . 'includes/ui-legacy-settings.php';
		$html = ob_get_clean();

		return array(
			'html' => $html,
		);
	}

	/**
	 * Renders a settings form via PHP. This method is only around for
	 * backwards compatibility with third party settings forms that are
	 * still being rendered via AJAX. Going forward, all settings forms
	 * should be rendered on the frontend using BACheetahSettingsForms.render.
	 *

	 * @param string $type The type of form to render.
	 * @param object $settings The settings data.
	 * @return array
	 */
	static public function render_settings_form( $type = null, $settings = null ) {
		$form = BACheetahModel::get_settings_form( $type );

		if ( isset( $settings ) && ! empty( $settings ) ) {
			$defaults = BACheetahModel::get_settings_form_defaults( $type );
			$settings = (object) array_merge( (array) $defaults, (array) $settings );
		} else {
			$settings = BACheetahModel::get_settings_form_defaults( $type );
		}

		return self::render_settings(array(
			'title' => $form['title'],
			'tabs'  => $form['tabs'],
		), $settings);
	}

	/**
	 * Renders a settings field via PHP. This method is only around for
	 * backwards compatibility with third party settings forms that are
	 * still being rendered via AJAX. Going forward, all settings forms
	 * should be rendered on the frontend using BACheetahSettingsForms.render.
	 *

	 * @param string $name The field name.
	 * @param array $field An array of setup data for the field.
	 * @param object $settings Form settings data object.
	 * @return void
	 */
	static public function render_settings_field( $name, $field, $settings = null ) {

		/**
		 * Use this filter to modify the config array for a field before it is rendered.
		 * @see ba_cheetah_render_settings_field

		 */
		$field = apply_filters( 'ba_cheetah_render_settings_field', $field, $name, $settings ); // Allow field settings filtering first

		if ( ! isset( $field['type'] ) ) {
			return;
		}

		$i                 = null;
		$is_multiple       = isset( $field['multiple'] ) && true === (bool) $field['multiple'];
		$supports_multiple = 'editor' != $field['type'] && 'service' != $field['type'];
		$settings          = ! $settings ? new stdClass() : $settings;
		$preview           = isset( $field['preview'] ) ? json_encode( $field['preview'] ) : json_encode( array(
			'type' => 'refresh',
		) );
		$row_class         = isset( $field['row_class'] ) ? ' ' . $field['row_class'] : '';
		$responsive        = false;
		$responsive_fields = array( 'unit' );
		$root_name         = $name;
		$global_settings   = BACheetahModel::get_global_settings();
		$value             = isset( $settings->$name ) ? $settings->$name : '';

		// Use a default value if not set in the settings.
		if ( ! isset( $settings->$name ) && isset( $field['default'] ) ) {
			$value = $field['default'];
		}

		// Check to see if responsive is enabled for this field.
		if ( $global_settings->responsive_enabled && isset( $field['responsive'] ) && ! $is_multiple && in_array( $field['type'], $responsive_fields ) ) {
			$responsive = $field['responsive'];
		}

		if ( file_exists( BA_CHEETAH_DIR . 'includes/ui-field-' . $field['type'] . '.php' ) ) {

			// Render old calls to *core* fields with JS.
			include BA_CHEETAH_DIR . 'includes/ui-legacy-field.php';

		} else {

			// Render old calls to *custom* fields with PHP.
			if ( $is_multiple && $supports_multiple ) {

				$values   = $value;
				$arr_name = $name;
				$name    .= '[]';

				echo '<tbody id="ba-cheetah-field-' . esc_attr($root_name) . '" class="ba-cheetah-field ba-cheetah-field-multiples" data-type="form" data-preview=\'' . $preview . '\'>';

				for ( $i = 0; $i < count( $values ); $i++ ) {
					$value = $values[ $i ];
					echo '<tr class="ba-cheetah-field-multiple" data-field="' . $arr_name . '">';
					include BA_CHEETAH_DIR . 'includes/ui-legacy-custom-field.php';
					echo '<td class="ba-cheetah-field-actions">';
					echo '<svg width="17" height="17" class="ba-cheetah-field-move"><use xlink:href="#ba-cheetah-icon--move"></use></svg>';
					echo '<svg width="14.205" height="18" class="ba-cheetah-field-copy"><use xlink:href="#ba-cheetah-icon--clone"></use></svg>';
					echo '<svg width="15.429" height="18" class="ba-cheetah-field-delete"><use xlink:href="#ba-cheetah-icon--trash"></use></svg>';
					echo '</td>';
					echo '</tr>';
				}

				echo '<tr>';

				if ( empty( $field['label'] ) ) {
					echo '<td colspan="2">';
				} else {
					echo '<td>&nbsp;</td><td>';
				}
				/* translators: %s: field name to add */
				echo '<a href="javascript:void(0);" onclick="return false;" class="ba-cheetah-field-add ba-cheetah-button" data-field="' . esc_attr($arr_name) . '">' . sprintf( _x( 'Add %s', 'Field name to add.', 'ba-cheetah' ), $field['label'] ) . '</a>';
				echo '</td>';
				echo '</tr>';
				echo '</tbody>';
			} else {
				echo '<tr id="ba-cheetah-field-' . esc_attr($name) . '" class="ba-cheetah-field' . esc_attr($row_class) . '" data-type="' . esc_attr($field['type']) . '" data-preview=\'' . $preview . '\'>';
				include BA_CHEETAH_DIR . 'includes/ui-legacy-custom-field.php';
				echo '</tr>';
			}
		}
	}

	/**
	 * Renders the markup for the icon selector.
	 *

	 * @return array
	 */
	static public function render_icon_selector() {
		$icon_sets = BACheetahIcons::get_sets();

		ob_start();
		include BA_CHEETAH_DIR . 'includes/icon-selector.php';
		$html = ob_get_clean();

		return array(
			'html' => $html,
		);
	}

	static public function layout_css_js( $config ) {

		$settings = BACheetahModel::get_page_settings();

		$config['layout_css_js'] = ( ( isset( $settings->css ) && '' !== $settings->css ) || ( isset( $settings->js ) && '' !== $settings->js ) ) ? true : false;
		return $config;
	}
}

BACheetahUISettingsForms::init();
