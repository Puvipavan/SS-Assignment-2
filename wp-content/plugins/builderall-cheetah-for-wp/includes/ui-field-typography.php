<#

var defaults = {
	font_family: 'Default',
	font_weight: 'default',
	font_size: {
		length: '',
		unit: 'px',
	},
	line_height: {
		length: '',
		unit: '',
	},
	text_align: '',
	letter_spacing: {
		length: '',
		unit: 'px',
	},
	text_transform: '',
	text_decoration: '',
	font_style: '',
	font_variant: '',
	text_shadow: {
		color: '',
		horizontal: '',
		vertical: '',
		blur: '',
	},
};

disabled_defaults = {
	default: {},
	medium: {},
	responsive: {}
}

var value           = '' === data.value ? defaults : jQuery.extend( true, defaults, data.value );
var device          = data.device ? data.device : 'default';
var disabled_fields = {}
var disabled        = []



if (typeof data.field.disabled !== 'undefined') {
	disabled_fields = jQuery.extend( true, disabled_defaults, data.field.disabled )
} else {
	disabled_fields = disabled_defaults
}

jQuery.each(disabled_fields[device], function(i,v){
	disabled.push(v)
})

/**
 * Helper function to check if a field is enabled.
 */
var ba_cheetah_typography_enabled = function( field ) {

	if( jQuery.inArray(field, disabled ) !== -1 ) {
		return false
	}
	return true;
}

var fontFamily = wp.template( 'ba-cheetah-field-font' )( {
	names: {
		family: data.name + '[][font_family]',
		weight: data.name + '[][font_weight]',
	},
	value: {
		family: value.font_family,
		weight: value.font_weight,
	},
	field: {
		show_labels: true,
	},
} );

var fontSize = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][font_size][length]',
	value: value.font_size.length,
	unit_name: data.name + '[][font_size][unit]',
	unit_value: value.font_size.unit,
	field: {
		units: [ 'px', 'em', 'rem', 'vw' ],
		slider: true,
	},
} );

var lineHeight = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][line_height][length]',
	value: value.line_height.length,
	unit_name: data.name + '[][line_height][unit]',
	unit_value: value.line_height.unit,
	field: {
		units: [ '', 'px', 'em' ],
		slider: true,
	},
} );

var textAlign = wp.template( 'ba-cheetah-field-align' )( {
	name: data.name + '[][text_align]',
	value: value.text_align,
	field: {},
} );

var letterSpacing = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][letter_spacing][length]',
	value: value.letter_spacing.length,
	unit_name: data.name + '[][letter_spacing][unit]',
	unit_value: value.letter_spacing.unit,
	field: {
		units: [ 'px' ],
		slider: {
			min: -10,
			max: 10,
			step: .1,
		},
	},
} );

var textTransform = wp.template( 'ba-cheetah-field-button-group' )( {
	name: data.name + '[][text_transform]',
	value: value.text_transform,
	field: {
		options: {
			none: 'Normal',
			capitalize: 'Tt',
			uppercase: 'TT',
			lowercase: 'tt',
		},
	},
} );

var textDecoration = wp.template( 'ba-cheetah-field-select' )( {
	name: data.name + '[][text_decoration]',
	value: value.text_decoration,
	field: {
		options: {
			'': '<?php esc_attr_e( 'Default', 'ba-cheetah' ); ?>',
			'none': '<?php esc_attr_e( 'None', 'ba-cheetah' ); ?>',
			'underline': '<?php esc_attr_e( 'Underline', 'ba-cheetah' ); ?>',
			'overline': '<?php esc_attr_e( 'Overline', 'ba-cheetah' ); ?>',
			'line-through': '<?php esc_attr_e( 'Line Through', 'ba-cheetah' ); ?>',
		},
	},
} );

var fontStyle = wp.template( 'ba-cheetah-field-select' )( {
	name: data.name + '[][font_style]',
	value: value.font_style,
	field: {
		options: {
			'': '<?php esc_attr_e( 'Default', 'ba-cheetah' ); ?>',
			'normal': '<?php esc_attr_e( 'Normal', 'ba-cheetah' ); ?>',
			'italic': '<?php esc_attr_e( 'Italic', 'ba-cheetah' ); ?>',
			'oblique': '<?php esc_attr_e( 'Oblique', 'ba-cheetah' ); ?>',
		},
	},
} );

var fontVariant = wp.template( 'ba-cheetah-field-select' )( {
	name: data.name + '[][font_variant]',
	value: value.font_variant,
	field: {
		options: {
			'': '<?php esc_attr_e( 'Default', 'ba-cheetah' ); ?>',
			'normal': '<?php esc_attr_e( 'Normal', 'ba-cheetah' ); ?>',
			'small-caps': '<?php esc_attr_e( 'Small Caps', 'ba-cheetah' ); ?>',
		},
	},
} );

var textShadow = wp.template( 'ba-cheetah-field-shadow' )( {
	name: data.name + '[][text_shadow]',
	value: value.text_shadow,
	field: {
		show_spread: false,
	},
} );

#>
<div class="ba-cheetah-compound-field ba-cheetah-typography-field">
	<div class="ba-cheetah-compound-field-section ba-cheetah-typography-field-section-general">
		<div class="ba-cheetah-compound-field-section-toggle">
			<?php _e( 'Font', 'ba-cheetah' ); ?>
			<svg width="12.588" height="7.494" ><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
		</div>
		<# if ( 'default' === device ) { #>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-family" data-property="font-family">
				<# if ( ba_cheetah_typography_enabled( 'font_family' ) ) { #>
				{{{fontFamily}}}
				<# } #>
			</div>
		</div>
		<# } #>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-size" data-property="font-size">
				<# if ( ba_cheetah_typography_enabled( 'font_size' ) ) { #>
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Size', 'ba-cheetah' ); ?>
				</label>
				{{{fontSize}}}
				<# } #>
			</div>
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-line-height" data-property="line-height">
			<# if ( ba_cheetah_typography_enabled( 'line_height' ) ) { #>
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Line Height', 'ba-cheetah' ); ?>
				</label>
				{{{lineHeight}}}
				<# } #>
			</div>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-auto" data-property="text-align">
			<# if ( ba_cheetah_typography_enabled( 'text_align' ) ) { #>
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Align', 'ba-cheetah' ); ?>
				</label>
				{{{textAlign}}}
				<# } #>
			</div>
		</div>
	</div>
	<div class="ba-cheetah-compound-field-section ba-cheetah-compound-field-section-style">
		<div class="ba-cheetah-compound-field-section-toggle">
			<?php _e( 'Style &amp Spacing', 'ba-cheetah' ); ?>
			<svg width="12.588" height="7.494" ><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-auto" data-property="letter-spacing">
				<# if ( ba_cheetah_typography_enabled( 'letter_spacing' ) ) { #>
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Spacing', 'ba-cheetah' ); ?>
				</label>
				{{{letterSpacing}}}
				<# } #>
			</div>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting" data-property="text-transform">
			<# if ( ba_cheetah_typography_enabled( 'text_transform' ) ) { #>
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Transform', 'ba-cheetah' ); ?>
				</label>
				{{{textTransform}}}
				<# } #>
			</div>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-decoration" data-property="text-decoration">
			<# if ( ba_cheetah_typography_enabled( 'text_decoration' ) ) { #>
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Decoration', 'ba-cheetah' ); ?>
				</label>
				{{{textDecoration}}}
				<# } #>
			</div>
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-style" data-property="font-style">
			<# if ( ba_cheetah_typography_enabled( 'font_style' ) ) { #>
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Style', 'ba-cheetah' ); ?>
				</label>
				{{{fontStyle}}}
				<# } #>
			</div>
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-variant" data-property="font-variant" style="display:none">
				<# if ( ba_cheetah_typography_enabled( 'font_variant' ) ) { #>
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Variant', 'ba-cheetah' ); ?>
				</label>
				{{{fontVariant}}}
				<# } #>
			</div>
		</div>
	</div>
	<div class="ba-cheetah-compound-field-section ba-cheetah-compound-field-section-shadow">
		<# if ( ba_cheetah_typography_enabled( 'text_shadow' ) ) { #>
		<div class="ba-cheetah-compound-field-section-toggle">
			<?php _e( 'Text Shadow', 'ba-cheetah' ); ?>
			<svg width="12.588" height="7.494" ><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-typography-field-shadow" data-property="text-shadow">
				{{{textShadow}}}
			</div>
		</div>
		<# } #>
	</div>
</div>
