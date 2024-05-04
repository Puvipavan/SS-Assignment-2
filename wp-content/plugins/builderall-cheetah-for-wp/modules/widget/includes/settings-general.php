<?php

// Get builder post data.
$post_data = BACheetahModel::get_cheetah_ba_data();

// Widget slug
if ( isset( $settings->widget ) ) {
	$widget_class = $settings->widget;
} elseif ( isset( $post_data['widget'] ) ) {
	$widget_class = $post_data['widget'];
}
$widget_class = urldecode( $widget_class );
if ( isset( $widget_class ) && class_exists( $widget_class ) ) {

	// Widget instance
	$widget_instance = new $widget_class();

	// Widget settings
	$settings_key    = 'widget-' . $widget_instance->id_base;
	$widget_settings = array();

	if ( isset( $settings->$settings_key ) ) {
		$widget_settings = (array) $settings->$settings_key;
	}

	// Widget title
	$widget_title = $widget_instance->name;

	// Widget form
	ob_start();
	BACheetahWidgetModule::render_form( $widget_class, $widget_instance, $widget_settings );
	echo '<input type="hidden" name="widget" value="' . esc_attr($widget_class) . '" />';
	$widget_form = ob_get_clean();

} elseif ( isset( $widget_class ) ) {

	// Widget doesn't exist!
	$widget_title = __( 'Widget', 'ba-cheetah' );

	// Widget form
	ob_start();
	echo '<div class="ba-cheetah-widget-missing">';
	/* translators: %s: widget slug */
	printf( _x( '%s no longer exists.', '%s stands for widget slug.', 'ba-cheetah' ), $widget_class );
	echo '</div>';
	$widget_form = ob_get_clean();
}
?>
<div class="ba-cheetah-settings-widget-wordpress">
	<h3 class="ba-cheetah-settings-title">
		<span class="ba-cheetah-settings-title-text-wrap"><?php echo $widget_title; ?></span>
	</h3>
	<table class="ba-cheetah-form-table">
		<tbody>
			<tr class="ba-cheetah-field" data-preview='{"type":"widget"}'>
				<td class="ba-cheetah-field-control">
					<div class="ba-cheetah-field-control-wrapper">
						<?php echo $widget_form; ?>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>