<#

var defaults = {
	style: '',
	delay: 0.0,
	duration: 1.0,
};

var value = '' === data.value ? defaults : jQuery.extend( true, defaults, data.value );

#>
<?php

$styles = array(
	''       => _x( 'None', 'Animation style.', 'ba-cheetah' ),
	'fade'   => array(
		'label'   => _x( 'Fade', 'Animation style.', 'ba-cheetah' ),
		'options' => array(
			'fade-in'    => _x( 'Fade In', 'Animation style.', 'ba-cheetah' ),
			'fade-left'  => _x( 'Fade Left', 'Animation style.', 'ba-cheetah' ),
			'fade-right' => _x( 'Fade Right', 'Animation style.', 'ba-cheetah' ),
			'fade-up'    => _x( 'Fade Up', 'Animation style.', 'ba-cheetah' ),
			'fade-down'  => _x( 'Fade Down', 'Animation style.', 'ba-cheetah' ),
		),
	),
	'slide'  => array(
		'label'   => _x( 'Slide', 'Animation style.', 'ba-cheetah' ),
		'options' => array(
			'slide-in-left'  => _x( 'Slide Left', 'Animation style.', 'ba-cheetah' ),
			'slide-in-right' => _x( 'Slide Right', 'Animation style.', 'ba-cheetah' ),
			'slide-in-up'    => _x( 'Slide Up', 'Animation style.', 'ba-cheetah' ),
			'slide-in-down'  => _x( 'Slide Down', 'Animation style.', 'ba-cheetah' ),
		),
	),
	'zoom'   => array(
		'label'   => _x( 'Zoom', 'Animation style.', 'ba-cheetah' ),
		'options' => array(
			'zoom-in'    => _x( 'Zoom In', 'Animation style.', 'ba-cheetah' ),
			'zoom-left'  => _x( 'Zoom Left', 'Animation style.', 'ba-cheetah' ),
			'zoom-right' => _x( 'Zoom Right', 'Animation style.', 'ba-cheetah' ),
			'zoom-up'    => _x( 'Zoom Up', 'Animation style.', 'ba-cheetah' ),
			'zoom-down'  => _x( 'Zoom Down', 'Animation style.', 'ba-cheetah' ),
		),
	),
	'bounce' => array(
		'label'   => _x( 'Bounce', 'Animation style.', 'ba-cheetah' ),
		'options' => array(
			'bounce'       => _x( 'Bounce', 'Animation style.', 'ba-cheetah' ),
			'bounce-in'    => _x( 'Bounce In', 'Animation style.', 'ba-cheetah' ),
			'bounce-left'  => _x( 'Bounce Left', 'Animation style.', 'ba-cheetah' ),
			'bounce-right' => _x( 'Bounce Right', 'Animation style.', 'ba-cheetah' ),
			'bounce-up'    => _x( 'Bounce Up', 'Animation style.', 'ba-cheetah' ),
			'bounce-down'  => _x( 'Bounce Down', 'Animation style.', 'ba-cheetah' ),
		),
	),
	'rotate' => array(
		'label'   => _x( 'Rotate', 'Animation style.', 'ba-cheetah' ),
		'options' => array(
			'rotate-in'         => _x( 'Rotate In', 'Animation style.', 'ba-cheetah' ),
			'rotate-down-left'  => _x( 'Rotate Down Left', 'Animation style.', 'ba-cheetah' ),
			'rotate-down-right' => _x( 'Rotate Down Right', 'Animation style.', 'ba-cheetah' ),
			'rotate-up-left'    => _x( 'Rotate Up Left', 'Animation style.', 'ba-cheetah' ),
			'rotate-up-right'   => _x( 'Rotate Up Right', 'Animation style.', 'ba-cheetah' ),
		),
	),
	'flip'   => array(
		'label'   => _x( 'Flip', 'Animation style.', 'ba-cheetah' ),
		'options' => array(
			'flip-vertical'   => _x( 'Flip Vertical', 'Animation style.', 'ba-cheetah' ),
			'flip-horizontal' => _x( 'Flip Horizontal', 'Animation style.', 'ba-cheetah' ),
		),
	),
	'fancy'  => array(
		'label'   => _x( 'Fancy', 'Animation style.', 'ba-cheetah' ),
		'options' => array(
			'fancy-flash'       => _x( 'Flash', 'Animation style.', 'ba-cheetah' ),
			'fancy-pulse'       => _x( 'Pulse', 'Animation style.', 'ba-cheetah' ),
			'fancy-rubber-band' => _x( 'Rubber Band', 'Animation style.', 'ba-cheetah' ),
			'fancy-shake'       => _x( 'Shake', 'Animation style.', 'ba-cheetah' ),
			'fancy-swing'       => _x( 'Swing', 'Animation style.', 'ba-cheetah' ),
			'fancy-tada'        => _x( 'Tada', 'Animation style.', 'ba-cheetah' ),
			'fancy-wobble'      => _x( 'Wobble', 'Animation style.', 'ba-cheetah' ),
			'fancy-jello'       => _x( 'Jello', 'Animation style.', 'ba-cheetah' ),
			'fancy-light-speed' => _x( 'Light Speed', 'Animation style.', 'ba-cheetah' ),
			'fancy-jack-box'    => _x( 'Jack in the Box', 'Animation style.', 'ba-cheetah' ),
			'fancy-roll-in'     => _x( 'Roll In', 'Animation style.', 'ba-cheetah' ),
		),
	),
);

?>
<#

var style = wp.template( 'ba-cheetah-field-select' )( {
	name: data.name + '[][style]',
	value: value.style,
	field: {
		options: <?php echo json_encode( $styles ); ?>,
	},
} );

var delay = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][delay]',
	value: value.delay,
	field: {
		units: [ 'seconds' ],
		slider: true,
	},
} );

var duration = wp.template( 'ba-cheetah-field-unit' )( {
	name: data.name + '[][duration]',
	value: value.duration,
	field: {
		units: [ 'seconds' ],
		slider: true,
	},
} );

#>
<div class="ba-cheetah-compound-field ba-cheetah-animation-field">
	<div class="ba-cheetah-compound-field-section">
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-animation-field-style">
				{{{style}}}
			</div>
		</div>
		<div class="ba-cheetah-compound-field-row">
			<div class="ba-cheetah-compound-field-setting ba-cheetah-animation-field-delay">
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Delay', 'ba-cheetah' ); ?>
				</label>
				{{{delay}}}
			</div>
			<div class="ba-cheetah-compound-field-setting ba-cheetah-animation-field-duration">
				<label class="ba-cheetah-compound-field-label">
					<?php _e( 'Duration', 'ba-cheetah' ); ?>
				</label>
				{{{duration}}}
			</div>
		</div>
	</div>
</div>
