<#

var names = data.names;
var values = data.values;
var keys = data.field.keys;
var placeholder = data.field.placeholder || '';
var units = data.field.units;
var slider = data.field.slider;
var labels = [];
var i;

/**
 * We need to handle responsive dimension fields like this for backwards
 * compatibility with old margin, padding and border fields. If we did not do this
 * the keys would be margin_medium_top instead of the existing margin_top_medium.
 */
var responsive = data.name.replace( data.rootName, '' );

/**
 * Setup keys and labels if custom config doesn't exist.
 */
if ( 'object' !== typeof keys ) {
	keys = {
		top: '<?php _e( 'Top', 'ba-cheetah' ); ?>',
		right: '<?php _e( 'Right', 'ba-cheetah' ); ?>',
		bottom: '<?php _e( 'Bottom', 'ba-cheetah' ); ?>',
		left: '<?php _e( 'Left', 'ba-cheetah' ); ?>',
	};
}

for ( i in keys ) {
	labels.push( keys[ i ] );
}

keys = Object.keys( keys );

/**
 * Setup input names if custom config doesn't exist.
 */
if ( 'object' !== typeof names ) {
	names = {};
	for ( i in keys ) {
		names[ keys[ i ] ] = data.rootName + '_' + keys[ i ] + responsive;
	}
}

/**
 * Setup values if custom config doesn't exist.
 */
if ( 'object' !== typeof values ) {
	values = {};
	for ( i in keys ) {
		values[ keys[ i ] ] = data.settings[ data.rootName + '_' + keys[ i ] + responsive ];
	}
}

/**
 * Setup placeholders if custom config doesn't exist.
 */
if ( 'object' !== typeof placeholder ) {
	var str = placeholder;
	placeholder = {};
	for ( i in keys ) {
		placeholder[ keys[ i ] ] = str;
	}
}

#>
<div class="ba-cheetah-dimension-field-units">
	<# for ( i = 0; i < keys.length ; i++ ) { #>
	<div class="ba-cheetah-dimension-field-unit">
		<input
			type="number"
			name="{{names[ keys[ i ] ]}}"
			value="{{values[ keys[ i ] ]}}"
			placeholder="{{placeholder[ keys[ i ] ]}}"
			data-unit="{{keys[ i ]}}"
			autocomplete="off"
		/>
		<# if ( slider ) {

			var sliderJSON;

			if ( 'object' === typeof slider && 'undefined' !== typeof slider[keys[i]] ) {
				// handle key-specific sliders
				sliderJSON = JSON.stringify( slider[keys[i]] );
			} else {
				sliderJSON = JSON.stringify( slider );
			}
		#>
		<div
			class="ba-cheetah-field-popup-slider"
			data-input="{{names[ keys[ i ] ]}}"
			data-slider="{{sliderJSON}}"
		>
			<div class="ba-cheetah-field-popup-slider-arrow"></div>
			<div class="ba-cheetah-field-popup-slider-input"></div>
		</div>
		<# } #>
		<label>{{{labels[ i ]}}}</label>
	</div>
	<# } #>
	<# if ( units ) { #>
	<div class="ba-cheetah-dimension-field-unit-select">
		<# if ( units.length > 1 ) {
			var unit = {
				name: 'undefined' !== typeof data.unit_name ? data.unit_name : data.rootName + responsive + '_unit',
				value: 'undefined' !== typeof data.unit_value ? data.unit_value : data.settings[ data.rootName + responsive + '_unit' ],
			};
		#>
		<select class="ba-cheetah-field-unit-select" name="{{unit.name}}">
			<# for ( var i = 0; i < units.length; i++ ) {
				var selected = units[i] === unit.value ? ' selected="selected"' : '';
				var label = '' === units[i] ? '&mdash;' : units[i];
			#>
			<option value="{{units[i]}}"{{{selected}}}>{{{label}}}</option>
			<# } #>
		</select>
		<# } else { #>
		<div class="ba-cheetah-field-unit-select">{{units[0]}}</div>
		<# } #>
	</div>
	<# } #>
</div>
