<select name="{{data.name}}">
	<?php
	foreach ( BACheetahPhoto::sizes() as $size => $atts ) :
			$label = ucwords( str_replace( array( '_', '-' ), ' ', $size ) ) . ' (' . implode( 'x', $atts ) . ')';
		?>
	<option value="<?php echo esc_attr($size); ?>"<# if ( data.value === '<?php echo $size; ?>' ) { #> selected="selected"<# } #>><?php echo esc_html($label); ?></option>
	<?php endforeach; ?>
	<option value="full"<# if ( data.value === 'full' ) { #> selected="selected"<# } #>><?php _e( 'Full Size', 'ba-cheetah' ); ?></option>
</select>
