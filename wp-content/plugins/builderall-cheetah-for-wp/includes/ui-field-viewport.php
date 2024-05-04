<#

var defaults = {
    initialValue: 15,
    finalValue: 80,
    speed: 3,
};

var value = '' === data.value ? defaults : jQuery.extend( true, defaults, data.value );


var initialValue = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][initialValue]',
	value: value.initialValue,
	field: {
        min: 0,
        max:100,
		slider: true,
	},
} );

var finalValue = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][finalValue]',
	value: value.finalValue,
	field: {
        min: 0,
        max:100,
		slider: true,
	},
} );

var speed = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][speed]',
	value: value.speed,
	field: {
        slider: {
            min: 0,
            max:20,
        },
	},
} );

#>
<div class="ba-cheetah-compound-field ba-cheetah-animation-field">
	<div class="ba-cheetah-compound-field-section">
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-animation-field-initial">
                <label>
					<?php _e( 'Initial page scroll percentage', 'ba-cheetah' ); ?>
				</label>
				{{{initialValue}}}
			</div>
			<div class="ba-cheetah-compound-field-setting ba-cheetah-animation-field-final">
				<label>
					<?php _e( 'Final page scroll percentage', 'ba-cheetah' ); ?>
				</label>
				{{{finalValue}}}
			</div>
			<div class="ba-cheetah-compound-field-setting ba-cheetah-animation-field-final">
				<label>
					<?php _e( 'Speed', 'ba-cheetah' ); ?>
				</label>
				{{{speed}}}
			</div>
		</div>
	</div>
</div>
