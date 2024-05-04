<?php

/**
 * PLEASE NOTE: This file is only around for backwards compatibility
 * with third party settings forms that are still being rendered via
 * AJAX. Going forward, all settings forms should be rendered on the
 * frontend using BACheetahSettingsForms.render.
 */

if ( isset( $field['class'] ) ) {
	$field['className'] = $field['class'];
}

ob_start();
do_action( 'ba_cheetah_before_control', $name, $value, $field, $settings );
do_action( 'ba_cheetah_before_control_' . $field['type'], $name, $value, $field, $settings );
$field['html_before'] = ob_get_clean();

ob_start();
do_action( 'ba_cheetah_after_control_' . $field['type'], $name, $value, $field, $settings );
do_action( 'ba_cheetah_after_control', $name, $value, $field, $settings );
$field['html_after'] = ob_get_clean();

?>
<tr id="ba-cheetah-field-<?php echo esc_attr($name); ?>"></tr>
<script>

var html   = null,
	fields = {
		'<?php echo esc_attr($name); ?>' : <?php echo wp_json_encode( $field ); ?>
	};

html = BACheetahSettingsForms.renderFields( fields, <?php echo wp_json_encode( $settings ); ?> );

jQuery( '#ba-cheetah-field-<?php echo esc_attr($name); ?>' ).after( html ).remove();

</script>
