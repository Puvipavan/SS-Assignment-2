<#

var defaults = {
	style: '',
	color: '',
	width: {
		top: '',
		right: '',
		bottom: '',
		left: '',
	},
	radius: {
		top_left: '',
		top_right: '',
		bottom_left: '',
		bottom_right: '',
	},
	shadow: {
		color: '',
		horizontal: '',
		vertical: '',
		blur: '',
		spread: '',
	},
};

var value = '' === data.value ? defaults : jQuery.extend( true, defaults, data.value );

var style = wp.template( 'ba-cheetah-field-select' )( {
	name: data.name + '[][style]',
	value: value.style,
	field: {
		options: {
			'': '<?php esc_attr_e( 'Default', 'ba-cheetah' ); ?>',
			'none': '<?php esc_attr_e( 'None', 'ba-cheetah' ); ?>',
			'solid': '<?php esc_attr_e( 'Solid', 'ba-cheetah' ); ?>',
			'dashed': '<?php esc_attr_e( 'Dashed', 'ba-cheetah' ); ?>',
			'dotted': '<?php esc_attr_e( 'Dotted', 'ba-cheetah' ); ?>',
			'double': '<?php esc_attr_e( 'Double', 'ba-cheetah' ); ?>',
		},
	},
} );

var color = wp.template( 'ba-cheetah-field-color' )( {
	name: data.name + '[][color]',
	value: value.color,
	field: {
		className: 'ba-cheetah-border-field-color',
		show_reset: true,
		show_alpha: true,
	},
} );

var width = wp.template( 'ba-cheetah-field-dimension' )( {
	name: data.name,
	rootName: data.name,
	names: {
		top: data.name + '[][width][top]',
		right: data.name + '[][width][right]',
		bottom: data.name + '[][width][bottom]',
		left: data.name + '[][width][left]',
	},
	values: {
		top: value.width.top,
		right: value.width.right,
		bottom: value.width.bottom,
		left: value.width.left,
	},
	field: {
		units: [ 'px' ],
		slider: true,
	},
} );

var radius = wp.template( 'ba-cheetah-field-dimension' )( {
	name: data.name,
	rootName: data.name,
	names: {
		top_left: data.name + '[][radius][top_left]',
		top_right: data.name + '[][radius][top_right]',
		bottom_left: data.name + '[][radius][bottom_left]',
		bottom_right: data.name + '[][radius][bottom_right]',
	},
	values: {
		top_left: value.radius.top_left,
		top_right: value.radius.top_right,
		bottom_left: value.radius.bottom_left,
		bottom_right: value.radius.bottom_right,
	},
	field: {
		units: [ 'px' ],
		slider: true,
		keys: {
			top_left: '<?php esc_attr_e( 'Left', 'ba-cheetah' ); ?>',
			top_right: '<?php esc_attr_e( 'Right', 'ba-cheetah' ); ?>',
			bottom_left: '<?php esc_attr_e( 'Left', 'ba-cheetah' ); ?>',
			bottom_right: '<?php esc_attr_e( 'Right', 'ba-cheetah' ); ?>',
		},
	},
} );

var shadow = wp.template( 'ba-cheetah-field-shadow' )( {
	name: data.name + '[][shadow]',
	value: value.shadow,
	field: {
		show_spread: true,
	},
} );

#>
<div class="ba-cheetah-compound-field ba-cheetah-border-field">
	<div class="ba-cheetah-compound-field-section ba-cheetah-border-field-section-general">
		<div class="ba-cheetah-compound-field-section-toggle">
			<?php _e( 'General', 'ba-cheetah' ); ?>
			<svg width="12.588" height="7.494" ><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-border-field-style" data-property="border-style">
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Style', 'ba-cheetah' ); ?>
				</label>
				{{{style}}}
			</div>
			<div class="ba-cheetah-compound-field-setting ba-cheetah-border-field-color" data-property="border-color">
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Color', 'ba-cheetah' ); ?>
				</label>
				{{{color}}}
			</div>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-border-field-width" data-property="border-width">
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Width', 'ba-cheetah' ); ?>
				</label>
				{{{width}}}
			</div>
		</div>
	</div>
	<div class="ba-cheetah-compound-field-section ba-cheetah-border-field-section-radius">
		<div class="ba-cheetah-compound-field-section-toggle">
			<?php _e( 'Radius &amp; Shadow', 'ba-cheetah' ); ?>
			<svg width="12.588" height="7.494" ><use xlink:href="#ba-cheetah-icon--arrow"></use></svg>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-border-field-radius" data-property="border-radius">
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Radius', 'ba-cheetah' ); ?>
				</label>
				{{{radius}}}
			</div>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-border-field-shadow" data-property="box-shadow">
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Box Shadow', 'ba-cheetah' ); ?>
				</label>
				{{{shadow}}}
			</div>
		</div>
	</div>
</div>
