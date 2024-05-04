<?php

/**
 * @class BACheetahTimerModule
 */
class BACheetahTimerModule extends BACheetahModule
{

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'            => __('Timer', 'ba-cheetah'),
			'description'     => '',
			'category'        => __('Animated', 'ba-cheetah'),
			'partial_refresh' => true,
			'icon'            => 'clock.svg',
		));

		$this->add_js('jquery-ui-countdown');
		$this->add_js('js-cookie');
	}

	/**
	 * Delete related cookies on remove element from the editor
	 *
	 * @return void
	 */
	public function delete()
	{
		$cookie_name = $this->get_from_now_cookie_name();
		if (isset($_COOKIE[$cookie_name])) {
			unset($_COOKIE[$cookie_name]);
			setcookie($cookie_name, null, -1, '/');
		}
	}

	/**
	 * Get the final date to jquery contdown
	 * when the user specifies a due date
	 *
	 * @return string
	 */
	private function get_final_due_date()
	{

		if (
			$this->settings->timer_type != 'due_date' ||
			empty($this->settings->due_date_date) ||
			empty($this->settings->due_date_time['hours']) ||
			empty($this->settings->due_date_time['minutes'])
		) return;

		$hours 		= $this->settings->due_date_time['hours'];
		$minutes 	= $this->settings->due_date_time['minutes'];

		if ($this->settings->due_date_time['day_period'] == 'pm') {
			$hours += 12;
		}

		return $this->settings->due_date_date . " $hours:$minutes";
	}

	/**
	 * Returns the amount in seconds in unix format 
	 * to be added to now for the timer type "from now"
	 * @return string
	 */
	private function get_from_now_unix_seconds()
	{
		if ($this->settings->timer_type == 'due_date') return;

		$days 		= $this->settings->from_now_days 	? $this->settings->from_now_days 	: 0;
		$hours 		= $this->settings->from_now_hours 	? $this->settings->from_now_hours 	: 0;
		$minutes 	= $this->settings->from_now_minutes ? $this->settings->from_now_minutes : 0;
		$seconds 	= $this->settings->from_now_seconds ? $this->settings->from_now_seconds : 0;

		if (!!!($days + $hours + $minutes + $seconds)) return;

		$offset_seconds = ($seconds
			+ ($minutes * 60)
			+ ($hours * 60 * 60)
			+ ($days * 24 * 60 * 60));

		return $offset_seconds * 1000;
	}

	/**
	 * The name of the cookie for the 'Contdown Cookie' mode
	 *
	 * @return string
	 */
	public function get_from_now_cookie_name()
	{
		$node = $this->node;
		return "ba-cheetah__timer-$node-time_started";
	}


	public function get_data()
	{
		$final_due_date 		= $this->get_final_due_date();
		$from_now_seconds 		= $this->get_from_now_unix_seconds();

		if (!$final_due_date && !$from_now_seconds) return false;

		return [
			'node'				=> $this->node,
			'action' 			=> $this->settings->action_after_finish,
			'action_message'	=> $this->settings->action_message,
			'action_url'		=> $this->settings->action_url,
			'timer_type' 		=> $this->settings->timer_type,
			'final_due_date' 	=> $final_due_date,						# for due_date
			'from_now_seconds' 	=> $from_now_seconds,					# for from_now and from_now_cookie
			'cookie_name' 		=> $this->get_from_now_cookie_name(), 	# for from_now_cookie
		];
	}
}
/**
 * Register the module and its form settings.
 */
BACheetah::register_module(
	'BACheetahTimerModule',
	array(
		'settings' => array(
			'title' => __('Settings', 'ba-cheetah'),
			'sections' => array(
				'dates' => array(
					'title' => __('Date', 'ba-cheetah'),
					'fields' => array(
						'timer_type' => array(
							'label' => __('Timer type', 'ba-cheetah'),
							'type' => 'button-group',
							'options' => array(
								'due_date' => __('Due date', 'ba-cheetah'),
								'from_now' => __('Countdown', 'ba-cheetah'),
								'from_now_cookie' => __('Countdown Cookie', 'ba-cheetah'),
							),
							'default' => 'due_date',
							'help' => __('In Due date mode, the timer will be counted up to a specific date. In the Countdown mode, you define the number of days, hours, minutes and seconds that will be decremented from the moment the page is loaded. The Countdown Cookie mode works in the same way, with the difference being that the count is not reset every time the page is reloaded. This is the ideal option for promotions, for example', 'ba-cheetah'),
							'toggle' => array(
								'due_date' => array('fields' => array('due_date_date', 'due_date_time')),
								'from_now' => array('fields' => array('from_now_days', 'from_now_hours', 'from_now_minutes', 'from_now_seconds')),
								'from_now_cookie' => array('fields' => array('from_now_days', 'from_now_hours', 'from_now_minutes', 'from_now_seconds'))
							)
						),
						'due_date_date' => array(
							'type'  => 'date',
							'label' => __('Date', 'ba-cheetah'),
							'min'   => date('Y-m-d'),
							'default' => date('Y-m-d', strtotime('+7 days'))
						),
						'due_date_time' => array(
							'type'       => 'time',
							'label'      => __('Time', 'ba-cheetah'),
							'default'     => array(
								'hours'     => '12',
								'minutes'   => '00',
								'day_period'    => 'am'
							)
						),
						'from_now_days' => array(
							'type' => 'select',
							'label' => __('Days', 'ba-cheetah'),
							'default' => '1',
							'options' => range(0, 30)
						),
						'from_now_hours' => array(
							'type' => 'select',
							'label' => __('Hours', 'ba-cheetah'),
							'default' => '0',
							'options' => range(0, 24)
						),
						'from_now_minutes' => array(
							'type' => 'select',
							'label' => __('Minutes', 'ba-cheetah'),
							'default' => '0',
							'options' => range(0, 60)
						),
						'from_now_seconds' => array(
							'type' => 'select',
							'label' => __('Seconds', 'ba-cheetah'),
							'default' => '0',
							'options' => range(0, 60)
						),
					)
				),
				'after_finish' => array(
					'title' => __('After finish', 'ba-cheetah'),
					'fields' => array(
						'action_after_finish' => array(
							'label' => __('Action after finish', 'ba-cheetah'),
							'type' => 'select',
							'help' => __('What happens after the counter runs out', 'ba-cheetah'),
							'options' => array(
								'do_nothing'  => __('Do nothing', 'ba-cheetah'),
								'hide' => __('Hide', 'ba-cheetah'),
								'display_message' => __('Display a message', 'ba-cheetah').'...',
								'redirect_to_url' => __('Redirect to a URL', 'ba-cheetah').'...'
							),
							'default' => 'do_nothing',
							'toggle' => array(
								'display_message' => array(
									'fields' => array('action_message'),
									'sections' => array('message_styles')
								),
								'redirect_to_url' => array(
									'fields' => array('action_url', 'alert_url')
								)
							)
						),
						'action_message' => array(
							'label' => __('Text', 'ba-cheetah'),
							'type' => 'editor',
							'default' => __('Offer expired!', 'ba-cheetah'),
							'preview' => array(
								'type' => 'none'
							)
						),
						'action_url' => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'ba-cheetah' ),
							'placeholder'   => __( 'http://www.example.com', 'ba-cheetah' ),
							'show_download' => true,
							'preview'       => array(
								'type' => 'none',
							),
						),
						'alert_url' => array(
							'type' => 'raw',
							'content' => __('The redirect will only be performed outside the editor', 'ba-cheetah')
						)
					)
				)
			)
		),
		'style' => array(
			'title' => __('Style', 'ba-cheetah'),
			'sections' => array(
				'box' => array(
					'title' => __('Box', 'ba-cheetah'),
					'fields' => array(
						'box_width' => array(
							'type'    => 'unit',
							'label'   => __('Box Min Width', 'ba-cheetah'),
							'default' => 100,
							'responsive' => true,
							'slider'  => array(
								'px' => array(
									'min'  => 0,
									'max'  => 1000,
									'step' => 1,
								),
								'%' => array(
									'min'  => 0,
									'max'  => 100,
									'step' => 1,
								)
							),
							'units' => array('px', 'em'),
							'default_unit' => 'px',
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .timer__counter .timer__box',
								'property' => 'flex-basis',
							),
						),
						'box_gap' => array(
							'type'    => 'unit',
							'label'   => __('Space between items', 'ba-cheetah'),
							'default' => 5,
							'slider'  => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
							'units' => array('px', 'em'),
							'preview' => array(
								'type'     => 'css',
								'selector' => '{node} .timer__counter',
								'property' => 'gap',
							),
						),
						'box_padding' => array(
							'type'    => 'unit',
							'label'   => __('Vertical Padding', 'ba-cheetah'),
							'default' => 10,
							'slider'  => array(
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							),
							'units' => array('px'),
							'preview' => array(
								'type'     => 'css',
								'rules'           => array(
									array(
										'selector'     => '{node} .timer__box, {node} .timer__colon',
										'property'     => 'padding-top'
									),
									array(
										'selector'     => '{node} .timer__box, {node} timer__colon',
										'property'     => 'padding-bottom'
									),
								)
							),
						),
						'box_background' => array(
							'label' => __('Background', 'ba-cheetah'),
							'type' => 'color',
							'show_alpha'    => true,
							'show_reset'  => true,
							'default' => 'f6f6fc',
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__counter .timer__box',
								'property'	=> 'background'
							),
						),
						'box_border'  => array(
							'type'       => 'border',
							'label'      => __('Border', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__counter .timer__box',
							),
							'default' => array(
								'style' => 'solid',
								'color' => 'rgba(75,113,209,0.1)',
								'width' => array(
									'top' => '2',
									'right' => '2',
									'bottom' => '2',
									'left' => '2',
								),
								'radius' => array(
									'top_left' => '10',
									'top_right' => '10',
									'bottom_left' => '10',
									'bottom_right' => '10',
								),
								'shadow' => array(
									'color' => '',
									'horizontal' => '',
									'vertical' => '',
									'blur' => '',
									'spread' => '',
								),
							),
						),
					)
				),
				'numbers' => array(
					'title' => __('Numbers', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'colons_display' => array(
							'type' => 'button-group',
							'label' => __('Display colons?', 'ba-cheetah'),
							'options' => array(
								'block' => __('Yes', 'ba-cheetah'),
								'none' => __('No', 'ba-cheetah'),
							),
							'default' => 'block',
						),
						'numbers_color' => array(
							'label' => __('Text color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '4054b2',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__counter .timer__box output, {node} .timer__counter .timer__colon',
								'property'	=> 'color'
							),
						),
						'numbers_typography'  => array(
							'type'       => 'typography',
							'label'      => __('Typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__counter .timer__box output, {node} .timer__counter .timer__colon',
							),
							'default' => array(
								'font_family' => 'Helvetica',
								'font_weight' => '400',
								'font_size' => array(
									'length' => '50',
									'unit' => 'px',
								),
								'line_height' => array(
									'length' => '1.5',
									'unit' => '',
								),
							)
						),
					)
				),
				'labels' => array(
					'title' => __('Label', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'labels_display' => array(
							'type' => 'button-group',
							'label' => __('Display labels?', 'ba-cheetah'),
							'options' => array(
								'block' => __('Yes', 'ba-cheetah'),
								'none' => __('No', 'ba-cheetah'),
							),
							'default' => 'block',
							'toggle' => array(
								'block' => array('fields' => array('labels_background', 'labels_color', 'labels_typography'))
							),
						),
						'labels_background' => array(
							'label' => __('Background', 'ba-cheetah'),
							'type' => 'color',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__counter .timer__box .timer__label',
								'property'	=> 'background-color'
							),
						),
						'labels_color' => array(
							'label' => __('Text color', 'ba-cheetah'),
							'type' => 'color',
							'default' => '9498b2',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__counter .timer__box .timer__label',
								'property'	=> 'color'
							),
						),
						'labels_typography' => array(
							'type'       => 'typography',
							'label'      => __('Typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__counter .timer__box .timer__label',
							),
							'default' => array(
								'font_family' => 'Helvetica',
								'font_weight' => '400',
								'font_size' => array(
									'length' => '15',
									'unit' => 'px',
								),
								'line_height' => array(
									'length' => '2',
									'unit' => '',
								),
								'text_align' => 'center',
							)
						),
					)
				),
				'message_styles' => array(
					'title' => __('Message', 'ba-cheetah'),
					'collapsed' => true,
					'fields' => array(
						'message_color' => array(
							'label' => __('Text color', 'ba-cheetah'),
							'type' => 'color',
							'show_alpha'    => true,
							'show_reset'  => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__message',
								'property'	=> 'color'
							),
						),
						'message_typography' => array(
							'type'       => 'typography',
							'label'      => __('Typography', 'ba-cheetah'),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => '{node} .timer__message',
							),
						),
					)
				)
			)
		)
	)

);
