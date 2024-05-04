<#

var defaults = {
	color: '',
	horizontal: '',
	vertical: '',
	blur: '',
	spread: '',
};

var value = '' === data.value ? defaults : data.value;

var picker = wp.template( 'ba-cheetah-field-color' )( {
	name: data.name + '[][color]',
	value: value.color,
	field: {
		className: 'ba-cheetah-shadow-field-color',
		show_reset: true,
		show_alpha: true,
	},
} );

var dimensions = {
	horizontal: {
		label: 'X',
		min: -100,
		max: 100,
	},
	vertical: {
		label: 'Y',
		min: -100,
		max: 100,
	},
	blur: {
		label: '<?php _e( 'Blur', 'ba-cheetah' ); ?>',
		min: 0,
		max: 100,
	},
	spread: {
		label: '<?php _e( 'Spread', 'ba-cheetah' ); ?>',
		min: -100,
		max: 100,
	},
};

if ( false === data.field.show_spread ) {
	delete dimensions.spread;
}

#>
<div class="ba-cheetah-shadow-field">
	{{{picker}}}
	<label>Size</label>
	<div class="ba-cheetah-dimension-field-units">
		<# for ( var key in dimensions ) {
			var slider = JSON.stringify( {
				min: dimensions[ key ].min,
				max: dimensions[ key ].max,
			} );
		#>
		<div class="ba-cheetah-dimension-field-unit ba-cheetah-shadow-field-{{key}}">
			<input
				type="number"
				name="{{data.name}}[][{{key}}]"
				value="{{value[ key ]}}"
				autocomplete="off"
			/>
			<div
				class="ba-cheetah-field-popup-slider"
				data-input="{{data.name}}[][{{key}}]"
				data-slider="{{slider}}"
			>
				<div class="ba-cheetah-field-popup-slider-arrow"></div>
				<div class="ba-cheetah-field-popup-slider-input"></div>
			</div>
			<label>{{dimensions[ key ].label}}</label>
		</div>
		<# } #>
		<!--
		<div class="ba-cheetah-dimension-field-unit-select">
			<div class="ba-cheetah-field-unit-select">px</div>
		</div>
		-->
	</div>
</div>
