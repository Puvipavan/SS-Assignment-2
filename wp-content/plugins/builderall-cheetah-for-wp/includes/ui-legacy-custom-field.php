<?php

/**
 * PLEASE NOTE: This file is only around for backwards compatibility
 * with third party settings forms that are still being rendered via
 * AJAX. Going forward, all settings forms should be rendered on the
 * frontend using BACheetahSettingsForms.render.
 */

?>
<?php if ( empty( $field['label'] ) ) : ?>
<td class="ba-cheetah-field-control" colspan="2">
<?php else : ?>
<th class="ba-cheetah-field-label">
	<label for="<?php echo $name; ?>">
	<?php

	if ( 'button' == $field['type'] ) {
		echo '&nbsp;';
	} else {

		echo esc_html($field['label']);

		if ( isset( $i ) ) {
			echo ' <span class="ba-cheetah-field-index">' . esc_html( $i + 1 ) . '</span>';
		}
	}

	?>
	<?php if ( isset( $field['help'] ) ) : ?>
	<span class="ba-cheetah-help-tooltip">
		<svg class="ba-cheetah-help-tooltip-icon" width="16" height="16"><use xlink:href="#ba-cheetah-icon--help"></use></svg>
		<span class="ba-cheetah-help-tooltip-text"><?php echo esc_html($field['help']); ?></span>
	</span>
	<?php endif; ?>
	</label>
</th>
<td class="ba-cheetah-field-control">
<?php endif; ?>
<div class="ba-cheetah-field-control-wrapper">
	<?php if ( $responsive ) : ?>
	<i class="ba-cheetah-field-responsive-toggle dashicons dashicons-desktop" data-mode="default"></i>
	<?php endif; ?>
	<?php

	foreach ( array( 'default', 'medium', 'responsive' ) as $device ) {

		if ( 'default' != $device && ! $responsive ) {
			continue;
		}

		if ( $responsive ) {

			$name  = 'default' == $device ? $root_name : $root_name . '_' . $device;
			$value = isset( $settings->$name ) ? $settings->$name : '';

			echo '<div class="ba-cheetah-field-responsive-setting ba-cheetah-field-responsive-setting-' . $device . '" data-device="' . $device . '">';

			if ( is_array( $responsive ) ) {
				foreach ( $responsive as $responsive_key => $responsive_var ) {
					if ( is_array( $responsive_var ) && isset( $responsive_var[ $device ] ) ) {
						$field[ $responsive_key ] = $responsive_var[ $device ];
					}
				}
			}
		}

		do_action( 'ba_cheetah_before_control', $name, $value, $field, $settings );
		do_action( 'ba_cheetah_before_control_' . $field['type'], $name, $value, $field, $settings );
		do_action( 'ba_cheetah_control_' . $field['type'], $name, $value, $field, $settings );
		do_action( 'ba_cheetah_after_control_' . $field['type'], $name, $value, $field, $settings );
		do_action( 'ba_cheetah_after_control', $name, $value, $field, $settings );

		if ( $responsive ) {
			echo '</div>';
		}
	}

	?>
	<?php if ( isset( $field['description'] ) ) : ?>
	<span class="ba-cheetah-field-description"><?php echo esc_html($field['description']); ?></span>
	<?php endif; ?>
</div>
</td>
