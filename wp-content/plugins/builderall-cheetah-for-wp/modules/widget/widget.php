<?php

/**
 * @class BACheetahWidgetModule
 */
class BACheetahWidgetModule extends BACheetahModule {

	/**
	 * @return void
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Widget', 'ba-cheetah' ),
			'description'     => __( 'Display a WordPress widget.', 'ba-cheetah' ),
			'group'           => __( 'WordPress Widgets', 'ba-cheetah' ),
			'category'        => __( 'WordPress Widgets', 'ba-cheetah' ),
			'editor_export'   => false,
			'partial_refresh' => true,
		));
	}

	/**
	 * @return void
	 */
	public function update( $settings ) {
		// Make sure we have a widget.
		if ( ! isset( $settings->widget ) || ! class_exists( $settings->widget ) ) {
			return $settings;
		}

		// Get the widget instance.
		$class    = $settings->widget;
		$instance = new $class();

		// Get the widget settings.
		$settings_key    = 'widget-' . $instance->id_base;
		$widget_settings = array();

		if ( isset( $settings->$settings_key ) ) {
			$widget_settings = (array) $settings->$settings_key;
		}

		// Run the widget update method.
		$widget_settings = $instance->update( $widget_settings, array() );

		// Save the widget settings as an object.
		if ( is_array( $widget_settings ) ) {
			$settings->$settings_key = (object) $widget_settings;
		}

		// Delete the WordPress cache for this widget.
		wp_cache_delete( $settings->widget, 'widget' );

		$settings->widget = urlencode( $settings->widget );

		// Return the settings.
		return $settings;
	}

	/**

	 * @param string $class
	 * @param object $instance
	 * @param array $settings
	 * @return void
	 */
	static public function render_form( $class, $instance, $settings ) {
		if ( 'WP_Widget_Text' === $class ) {
			// Render the legacy text form since the one in 4.8 doesn't work in the builder.
			include BA_CHEETAH_DIR . 'modules/widget/includes/settings-text-widget.php';
		} else {
			$instance->form( $settings );
		}
	}
}

/**
 * Register the module and its form settings.
 */
BACheetah::register_module('BACheetahWidgetModule', array(
	'general' => array( // Tab
		'title' => __( 'General', 'ba-cheetah' ), // Tab title
		'file'  => BA_CHEETAH_DIR . 'modules/widget/includes/settings-general.php',
	),
));
