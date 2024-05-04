<#

var defaults = {
	type: 'linear',
	angle: 90,
	position: 'center center',
	colors: [ '', '' ],
	stops: [ 0, 100 ],
};

var value = '' === data.value ? defaults : jQuery.extend( true, defaults, data.value );

var type = wp.template( 'ba-cheetah-field-select' )( {
	name: data.name + '[][type]',
	value: value.type,
	field: {
		className: 'ba-cheetah-gradient-picker-type-select',
		options: {
			'linear': '<?php esc_attr_e( 'Linear', 'ba-cheetah' ); ?>',
			'radial': '<?php esc_attr_e( 'Radial', 'ba-cheetah' ); ?>',
		},
	},
} );

var angle = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][angle]',
	value: value.angle,
	field: {
		className: 'ba-cheetah-gradient-picker-angle',
		slider: { max: 360 },
	},
} );

var position = wp.template( 'ba-cheetah-field-select' )( {
	name: data.name + '[][position]',
	value: value.position,
	field: {
		className: 'ba-cheetah-gradient-picker-position',
		options: {
			'left top': '<?php esc_attr_e( 'Left Top', 'ba-cheetah' ); ?>',
			'left center': '<?php esc_attr_e( 'Left Center', 'ba-cheetah' ); ?>',
			'left bottom': '<?php esc_attr_e( 'Left Bottom', 'ba-cheetah' ); ?>',
			'right top': '<?php esc_attr_e( 'Right Top', 'ba-cheetah' ); ?>',
			'right center': '<?php esc_attr_e( 'Right Center', 'ba-cheetah' ); ?>',
			'right bottom': '<?php esc_attr_e( 'Right Bottom', 'ba-cheetah' ); ?>',
			'center top': '<?php esc_attr_e( 'Center Top', 'ba-cheetah' ); ?>',
			'center center': '<?php esc_attr_e( 'Center Center', 'ba-cheetah' ); ?>',
			'center bottom': '<?php esc_attr_e( 'Center Bottom', 'ba-cheetah' ); ?>',
		},
	},
} );

var color0 = wp.template( 'ba-cheetah-field-color' )( {
	name: data.name + '[][colors][0]',
	value: value.colors[ 0 ],
	field: {
		className: 'ba-cheetah-gradient-picker-color',
		show_reset: false,
		show_alpha: true,
	},
} );

var stop0 = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][stops][0]',
	value: value.stops[ 0 ],
	field: {
		slider: true,
	},
} );

var color1 = wp.template( 'ba-cheetah-field-color' )( {
	name: data.name + '[][colors][1]',
	value: value.colors[ 1 ],
	field: {
		className: 'ba-cheetah-gradient-picker-color',
		show_reset: false,
		show_alpha: true,
	},
} );

var stop1 = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][stops][1]',
	value: value.stops[ 1 ],
	field: {
		slider: true,
	},
} );

#>
<div class="ba-cheetah-gradient-picker">
	<div class="ba-cheetah-gradient-picker-type">
		{{{type}}}
		<div class="ba-cheetah-gradient-picker-angle-wrap">
			{{{angle}}}
		</div>
		{{{position}}}
	</div>
	<div class="ba-cheetah-gradient-picker-colors">
		<div class="ba-cheetah-gradient-picker-color-row">
			{{{color0}}}
			<div class="ba-cheetah-gradient-picker-stop">
				{{{stop0}}}
			</div>
		</div>
		<div class="ba-cheetah-gradient-picker-color-row">
			{{{color1}}}
			<div class="ba-cheetah-gradient-picker-stop">
				{{{stop1}}}
			</div>
		</div>
	</div>
</div>
