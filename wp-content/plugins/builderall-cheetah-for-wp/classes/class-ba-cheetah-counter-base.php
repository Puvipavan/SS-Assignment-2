<?php

/**
 * Helper class for working with color values.
 *

 */
class BACheetahCounterBase {

	const DEFAULT_FROM = 0;
	const DEFAULT_TO = 100;
	const DEFAULT_DURATION = 2;
	const DEFAULT_WAIT = 0;
	const DEFAULT_MAX = 100;
	const DEFAULT_STEP = 1;

	public static function get_settings_for_increments_js($settings, $targets) {
		$options = array(
			'from' 		=> isset($settings->from) ? intval($settings->from) : self::DEFAULT_FROM,
			'to' 		=> isset($settings->to) ? intval($settings->to) : self::DEFAULT_TO,
			'wait' 		=> (isset($settings->wait) ? intval($settings->wait) : self::DEFAULT_WAIT) * 1000,
			'step' 		=> isset($settings->step) && intval($settings->step) > 0 ? intval($settings->step) : self::DEFAULT_STEP,
			'duration' 	=> (isset($settings->duration) ? intval($settings->duration) : self::DEFAULT_DURATION) * 1000,
			'max' 		=> isset($settings->max_value) && isset($settings->counter_type) && $settings->counter_type == 'value' ? intval($settings->max_value) : self::DEFAULT_MAX,
			'targets' 	=> $targets
		);

		return json_encode($options);
	}

	public static function get_settings_fields() {
		return array(
			'from' => array(
				'type'  => 'unit',
				'label' => __('From', 'ba-cheetah'),
				'default' => self::DEFAULT_FROM,
			),
			'to' => array(
				'type'  => 'unit',
				'label' => __('To', 'ba-cheetah'),
				'default' => self::DEFAULT_TO,
			),
			'step' => array(
				'type'  => 'unit',
				'label' => __('Step', 'ba-cheetah'),
				'help' => __('How many numbers to advance with each increment. If the final number is large and the animation duration is short, the step will be overlaid with an appropriate value.', 'ba-cheetah'),
				'default' => self::DEFAULT_STEP,
				'placeholder' => strval(self::DEFAULT_STEP),
				'slider' => array(
					'min' => '1',
					'step' => '1',
					'max' => '100'
				)
			),
			'duration' => array(
				'type'  => 'unit',
				'label' => __('Animation duration', 'ba-cheetah'),
				'units' => array('s'),
				'slider' => array(
					'min' => '0,5',
					'max' => '10',
					'step' => '0.5'
				),
				'default_unit' => 's',
				'default' => self::DEFAULT_DURATION,
				'placeholder' => strval(self::DEFAULT_DURATION),
			),
			'wait' => array(
				'type'  => 'unit',
				'label' => __('Animation wait', 'ba-cheetah'),
				'units' => array('s'),
				'default_unit' => 's',
				'default' => self::DEFAULT_WAIT,
				'placeholder' => strval(self::DEFAULT_WAIT),
				'help' => __('Time to wait before starting the counter', 'ba-cheetah'),
				'slider' => array(
					'min' => '0',
					'max' => '10',
					'step' => '0.5'
				),
			),

			/*
				Advanced settings
				
				'counter_type' => array(
					'type' => 'button-group',
					'label' => 'Counter type',
					'default' => 'percentage',
					'options' => array(
						'percentage' => __('Percentage', 'ba-cheetah'),
						'value' => __('Value', 'ba-cheetah'),
					),
				),
				'max_value' => array(
					'type'  => 'unit',
					'label' => 'Max value',
					'default' => '100',
				),
			*/
		);
	}
}
