<#

var names = data.names;

if ( ! names ) {
	names = {
		family: data.name + '[][family]',
		weight: data.name + '[][weight]',
	};
}

data.value = JSON.stringify( data.value );

#>
<div class="ba-cheetah-font-field" data-value='{{{data.value}}}'>
	<div class="ba-cheetah-font-field-font-wrapper">
		<# if ( data.field.show_labels ) { #>
		<label for="{{names.family}}"><?php _e( 'Family', 'ba-cheetah' ); ?></label>
		<# } #>
		<select name="{{names.family}}" class="ba-cheetah-font-field-font">
			<?php BACheetahFonts::display_select_font( 'Default' ); ?>
		</select>
	</div>
	<div class="ba-cheetah-font-field-weight-wrapper">
		<# if ( data.field.show_labels ) { #>
		<label for="{{names.weight}}"><?php _e( 'Weight', 'ba-cheetah' ); ?></label>
		<# } #>
		<select name="{{names.weight}}" class="ba-cheetah-font-field-weight">
			<?php BACheetahFonts::display_select_weight( 'Default', '' ); ?>
		</select>
	</div>
</div>
