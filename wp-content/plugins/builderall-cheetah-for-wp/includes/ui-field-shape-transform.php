<#
var position = data.field.preview.position;
var defaults = {
	translateX: '0',
	translateY: '0',
	skew: '',
	rotate: '',
	scaleX: '1',
	scaleXSign: '',
	scaleYSign: ''
};

var value = '' === data.value ? defaults : data.value;

var dimensions = {
	skewX: {
		label: '<?php _e( 'Skew X', 'ba-cheetah' ); ?>',
		min: -60,
		max: 60,
	},
	skewY: {
		label: '<?php _e( 'Skew Y', 'ba-cheetah' ); ?>',
		min: -60,
		max: 60,
	},
	scaleX: {
		label: '<?php _e( 'Scale X', 'ba-cheetah' ); ?>',
		min: 1,
		max: 10,
		step: .1,
	},
	rotate: {
		label: '<?php _e( 'Rotate', 'ba-cheetah' ); ?>',
		min: 0,
		max: 360,
	},
};

var xOrientation = wp.template( 'ba-cheetah-field-button-group' )( {
	name: data.name + '[][scaleXSign]',
	value: value.scaleXSign,
	field: {
		label: '<?php _e( 'Horizontal Orientation', 'ba-cheetah' ); ?>',
		unselectable: true,
		options: {
			'invert': '<i class="dashicons dashicons-image-flip-horizontal"></i>',
		},
	},
} );

var yOrientation = wp.template( 'ba-cheetah-field-button-group' )( {
	name: data.name + '[][scaleYSign]',
	value: value.scaleYSign,
	field: {
		label: '<?php _e( 'Vertical Orientation', 'ba-cheetah' ); ?>',
		unselectable: true,
		options: {
			'invert': '<i class="dashicons dashicons-image-flip-vertical"></i>',
		},
	},
} );

#>
<div class="ba-cheetah-shape-transform-field">
	<div class="ba-cheetah-compound-field-section-visible">
		<div class="ba-cheetah-compound-field-row">
			<span class="ba-cheetah-compound-field-cell ba-cheetah-shape-orientation-cell">
				<span class="ba-cheetah-shape-orientation-controls">
					{{{xOrientation}}}
					{{{yOrientation}}}
				</span>
				<label><?php _e( 'Orientation', 'ba-cheetah' ); ?></label>
			</span>
			<span class="ba-cheetah-compound-field-cell">
				<div class="ba-cheetah-dimension-field-units">
					<# for ( var key in dimensions ) {
						var slider = JSON.stringify( {
							min: dimensions[ key ].min,
							max: dimensions[ key ].max,
							step: dimensions[ key ].step ? dimensions[ key ].step : 1,
						} );
					#>
					<div class="ba-cheetah-dimension-field-unit ba-cheetah-shape-transform-field-{{key}}">
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

					<input type="hidden" name="{{data.name}}[][scaleY]" value="1" />
				</div>
			</span>
		</div>
	</div>
</div>
